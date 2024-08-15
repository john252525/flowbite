@extends('app', [
    'title' => 'Пополнение баланса',
    'keywords' => '', # Ключевые слова
    'description' => '' # Описание страницы
])

@section('content')
<section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
    <form action="{{ route('payment.create') }}" method="POST" class="mx-auto max-w-screen-xl px-4 2xl:px-0">
        @csrf

        <div class="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12 xl:gap-16">
            <div class="min-w-0 flex-1 space-y-8">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Платежная система
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Сумма
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Дата
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Статус
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $types = [
                                    'payments' => [
                                        'yookassa' => 'YooKassa'
                                    ],

                                    'status' => [
                                        '0' => 'Ожидается',
                                        '1' => 'Оплачен',
                                        '2' => 'Отменён',
                                        '3' => 'Возврат'
                                    ]
                                ];
                            @endphp

                            @forelse ($payments as $item)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $types['payments'][$item->type] }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $item->amount }} руб.
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->created_at }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $types['status'][$item->status] }}
                                </td>
                            </tr>
                            @empty
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td colspan="6" class="px-6 py-4">
                                    <div class="p-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                                        <span class="font-medium">Пусто!</span> Пополнений отсутствуют.
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6 w-full space-y-6 sm:mt-8 lg:mt-0 lg:max-w-xs xl:max-w-md">
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Пополнение баланса</h2>

                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" placeholder="Введите сумму" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" required>

                    <ul class="grid w-full gap-6 md:grid-cols-2">
                        <li>
                            <input type="radio" id="yookassa" name="payment_system" value="yookassa" class="hidden peer" />
                            <label for="yookassa" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">                           
                                <div class="block">
                                    <div class="w-full text-lg font-semibold">YooKassa</div>
                                    <div class="w-full text-sm font-medium">Комиссия от 0%</div>
                                </div>

                                <svg class="w-10 h-10 ms-3" fill="none" viewBox="0 0 95 64" xmlns="http://www.w3.org/2000/svg"><g fill="#0a2540"><path d="m136.578 19.3674h-8.748l-6.191 10.7836h-3.187l-.101-23.33758h-8.282v42.14848h8.282l.101-11.5682h3.167l8.22 11.5682h9.175l-10.717-15.3304z"/><path d="m199.259 33.2494c-1.683-1.0793-3.506-1.9254-5.42-2.5149l-1.827-.684-.494-.1839c-1.135-.4216-2.327-.8648-2.368-2.009-.01-.3397.066-.6765.222-.9791s.387-.561.672-.7511c.599-.4097 1.302-.6465 2.029-.6841 1.586-.1085 3.158.3561 4.425 1.3078l.224.1408 4.425-5.0498-.224-.181c-.551-.4868-1.149-.9181-1.786-1.2876-1.141-.6472-2.383-1.1027-3.674-1.348-1.86-.3922-3.783-.3922-5.643 0-1.798.2359-3.504.929-4.953 2.0119-.926.7182-1.7 1.6103-2.278 2.6252-.578 1.0148-.949 2.1327-1.091 3.2896-.254 2.0879.281 4.195 1.502 5.9149 1.622 1.7799 3.742 3.0406 6.089 3.6213l.366.1208.832.2816c3.004 1.006 3.857 1.4083 4.344 2.0119.226.3036.354.6686.365 1.0461 0 1.4285-1.766 2.0119-2.963 2.374-.838.1559-1.698.14-2.529-.0469s-1.615-.5406-2.302-1.0395c-1.113-.7375-2.065-1.6883-2.801-2.7964-.123.1268-.53.5299-1.062 1.0568-1.493 1.479-3.971 3.9336-3.911 3.9929l.142.2012c2.21 2.7429 5.307 4.647 8.768 5.3918.791.1516 1.592.2524 2.396.3018h.832c2.734.0568 5.413-.7694 7.632-2.3539 1.501-1.0522 2.652-2.5228 3.309-4.2249.399-1.1427.54-2.3582.414-3.5609-.126-1.2028-.517-2.3634-1.145-3.4001-.642-1.0339-1.5-1.919-2.517-2.5953z"/><path d="m220.614 30.7343c1.907.5896 3.724 1.4357 5.399 2.5148.999.6764 1.842 1.5539 2.476 2.5752.628 1.0368 1.019 2.1974 1.145 3.4001.126 1.2028-.015 2.4183-.414 3.561-.656 1.7021-1.808 3.1726-3.309 4.2249-2.219 1.5844-4.898 2.4106-7.632 2.3538h-.832c-.804-.0481-1.604-.1489-2.395-.3017-3.462-.7448-6.558-2.6489-8.769-5.3918l-.162-.2012c-.04-.0528 1.917-1.9991 3.397-3.4696.777-.7724 1.422-1.4139 1.576-1.5802.747 1.0993 1.697 2.0482 2.801 2.7965.691.4994 1.478.8533 2.312 1.0402.834.1868 1.698.2025 2.539.0462 1.198-.3621 2.943-.9456 2.943-2.374.011-.3778-.111-.7476-.345-1.0462-.487-.6035-1.339-1.0059-4.364-2.0118l-.832-.2817-.345-.1207c-2.348-.5807-4.468-1.8414-6.09-3.6213-1.231-1.715-1.767-3.8263-1.502-5.9149.149-1.1567.527-2.273 1.112-3.2846s1.366-1.8986 2.298-2.6101c1.452-1.0766 3.157-1.769 4.953-2.0119 1.867-.3927 3.796-.3927 5.663 0 1.285.246 2.52.7015 3.654 1.348.647.3644 1.252.796 1.807 1.2875l.203.1811-4.425 5.0498-.203-.1409c-1.269-.9483-2.84-1.4125-4.425-1.3077-.728.0376-1.431.2744-2.03.6841-.278.1977-.505.4572-.664.7576-.159.3005-.244.6335-.25.9726.061 1.1467 1.239 1.5893 2.396 2.0118l.466.1811z"/><path clip-rule="evenodd" d="m163.777 19.3673v2.8971h-.365c-2.267-2.2497-5.338-3.5221-8.546-3.5409-1.966-.0385-3.919.3339-5.73 1.0931-1.812.7591-3.442 1.8879-4.784 3.3129-2.707 3.0045-4.159 6.9176-4.06 10.9445-.105 4.0944 1.344 8.0789 4.06 11.1658 1.31 1.4259 2.914 2.5556 4.704 3.3124s3.723 1.1229 5.668 1.0735c3.212-.0598 6.301-1.2346 8.728-3.3196h.325v2.5953h8.607v-29.5341zm.426 14.8676c.087 2.3801-.715 4.7081-2.253 6.5386-.737.8178-1.646 1.4652-2.663 1.8963s-2.117.6353-3.223.5984c-1.072.0179-2.135-.2044-3.109-.6503-.973-.4459-1.833-1.1039-2.514-1.9249-1.519-1.8684-2.298-4.2218-2.192-6.619-.069-2.3252.726-4.5945 2.233-6.3776.694-.8086 1.56-1.4552 2.535-1.8934.976-.4382 2.037-.6573 3.108-.6415 1.098-.0339 2.19.174 3.198.6089 1.007.4349 1.904 1.0858 2.627 1.9059 1.537 1.8385 2.339 4.1726 2.253 6.5586z" fill-rule="evenodd"/><path clip-rule="evenodd" d="m257.394 22.2646v-2.897h8.606v29.5341h-8.606v-2.5953h-.325c-2.428 2.085-5.517 3.2597-8.728 3.3195-1.946.0494-3.879-.3167-5.668-1.0734-1.79-.7568-3.395-1.8865-4.705-3.3124-2.716-3.0869-4.164-7.0714-4.059-11.1658-.099-4.0269 1.352-7.94 4.059-10.9446 1.348-1.4235 2.982-2.5511 4.796-3.31 1.815-.7589 3.77-1.1322 5.739-1.0959 3.201.024 6.263 1.296 8.525 3.5408zm-1.827 18.5092c1.546-1.8257 2.349-4.1571 2.253-6.5386.095-2.3875-.708-4.7249-2.253-6.5587-.723-.82-1.621-1.471-2.628-1.9058-1.007-.4349-2.1-.6429-3.198-.609-1.071-.0157-2.132.2033-3.107.6416-.976.4382-1.842 1.0848-2.536 1.8934-1.507 1.783-2.301 4.0524-2.232 6.3776-.107 2.3972.673 4.7506 2.192 6.619.681.821 1.54 1.4789 2.514 1.9248.973.4459 2.036.6682 3.108.6504 1.106.0369 2.206-.1674 3.223-.5985 1.018-.4311 1.927-1.0784 2.664-1.8962z" fill-rule="evenodd"/></g><path clip-rule="evenodd" d="m26.4424 31.7092c.0477-17.4683 14.4906-31.7092 32.6397-31.7092 17.9726 0 32.8465 14.288 32.6399 31.7959 0 17.5079-14.6673 31.7959-32.6399 31.7959-17.9431 0-32.5917-14.0406-32.6397-31.7088v23.6592h-11.5685l-14.8739-46.2852h26.4424zm20.4514.0867c0 6.4397 5.5777 11.8732 12.1883 11.8732 6.8172 0 12.1884-5.4335 12.1884-11.8732s-5.5777-11.8731-12.1884-11.8731c-6.6106 0-12.1883 5.4334-12.1883 11.8731z" fill="#0070f0" fill-rule="evenodd"/></svg>
                            </label>
                        </li>
                        <!-- <li>
                            <input type="radio" id="yookassa1" name="payment_system" value="yookassa" class="hidden peer">
                            <label for="yookassa1" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                                <div class="block">
                                    <div class="w-full text-lg font-semibold">YooKassa</div>
                                    <div class="w-full text-sm font-medium">Комиссия от 0%</div>
                                </div>

                            </label>
                        </li> -->
                    </ul>

                    <div class="mu-3"></div>

                    @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <button type="submit" class="flex w-full items-center justify-center rounded-lg text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Пополнить баланс</button>
                </div>
            </div>
        </div>
    </form>

</section>
@endsection