<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use YooKassa\Client;
use YooKassa\Model\Notification\NotificationFactory;
use YooKassa\Model\Notification\NotificationEventType;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'amount' => ['required', 'numeric', 'min:10', 'max:50000'],
            'payment_system' => 'required|in:yookassa',
        ], [
            'amount' => __('Минимальная сумма пополнения от 10 руб и максимальная 50000 руб.'),
            'payment_system' => __('Выбранная платежная система не найдена либо не выбрана')
        ]);

        switch($request->payment_system)
        {
            case 'yookassa':
                if(config('settings.yookassa_id') == '' || config('settings.yookassa_key') == '')
                    return back()->withErrors(__('Платежная система временно недоступна'));

                $response = $this->yookassa($request);
                break;
        }

        $payment = Payment::create([
            'user_id' => $user->id,
            'type' => $response['type'],
            'signature' => $response['signature'],
            'link' => $response['payment_url'],
            'amount' => $response['amount'],
            'description' => $response['description']
        ]);

        return redirect($response['payment_url']);
    }

    public function notifications(string $type)
    {
        if(!in_array($type, ['yookassa']))
            return 'Платежная система не найдена';

        switch($type)
        {
            case 'yookassa':
                return $this->__yookassa();
        }
    }

    private function yookassa(Request $request)
    {        
        $user = $request->user();

        $client = new Client();
        $client->setAuth(config('settings.yookassa_id'), config('settings.yookassa_key'));

        $latest = Payment::latest()->first();

        if(!$latest)
            $order = 1;
        else
            $order = $latest->id + 1;
    
        try {
            $payment = $client->createPayment(
                array(
                    'amount' => array(
                        'value' => $request->amount,
                        'currency' => 'RUB',
                    ),
                    'confirmation' => array(
                        'type' => 'redirect',
                        'return_url' => 'https://'.$_SERVER['SERVER_NAME'].'/',
                    ),
                    'capture' => true,
                    'description' => 'Пополнение баланса №'.$order,
                ),
                uniqid('pay', true)
            );

            return [
                'type' => 'yookassa',
                'signature' => $payment['id'],
                'payment_url' => $payment['confirmation']['confirmation_url'],
                'amount' => $request->amount,
                'description' => 'Пополнение баланса №'.$order,
            ];
        } catch (\Exception $e) {
            $response = $e;

            Log::debug($response);

            return back()->withErrors(__('Пополнение с помощью данной платежной системы временно недоступна')); 
        }
    }

    protected function __yookassa()
    {
        try {
            $source = file_get_contents('php://input');
            $data = json_decode($source, true);
        
            $factory = new NotificationFactory();
            $notificationObject = $factory->factory($data);
            $responseObject = $notificationObject->getObject();
            
            $client = new Client();
        
            if (!$client->isNotificationIPTrusted($_SERVER['REMOTE_ADDR'])) {
                header('HTTP/1.1 400 Something went wrong');
                exit();
            }

            $response = array(
                'paymentId' => $responseObject->getId(),
                'paymentStatus' => $responseObject->getStatus(),
            );

            $payment = Payment::where('signature', $response['paymentId'])->first();
            $user = User::find($payment->user_id);

            if(!$payment || !$user)
                return 'Платеж или пользователь не найден';

            if($payment->status == 1)
                return 'Платеж оплачен';

            if ($notificationObject->getEvent() === NotificationEventType::PAYMENT_SUCCEEDED) {
                $user->balance += $payment->amount;
                $user->save();

                $payment->status = 1;
                $payment->save();
            } elseif ($notificationObject->getEvent() === NotificationEventType::PAYMENT_WAITING_FOR_CAPTURE) {
            } elseif ($notificationObject->getEvent() === NotificationEventType::PAYMENT_CANCELED) {
                $payment->status = 2;
                $payment->save();
            } elseif ($notificationObject->getEvent() === NotificationEventType::REFUND_SUCCEEDED) {
                $payment->status = 3;
                $payment->save();
            } else {
                header('HTTP/1.1 400 Something went wrong');
                exit();
            }
        } catch (Exception $e) {
            header('HTTP/1.1 400 Something went wrong');
            exit();
        }
    }
}
