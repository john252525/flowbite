@extends('app', [
    'title' => 'Аккаунты'    
])

@section('content')
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Список аккаунтов</h1>
        <select id="platform-list" class="block w-full p-2 mb-6 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="listAccount(this.value)">
            <option value="telegram">Telegram</option>
            <option value="whatsapp">WhatsApp</option>
        </select>
    </div>
    <div>
        <button type="button" id="add-account-button" data-modal-target="add-account" data-modal-toggle="add-account" class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
            Добавить
        </button>
    </div>
</div>
<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
                <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4 w-full text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                Логин
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                Действия
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700" id="accounts">
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            <td colspan="6" class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Загрузка...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="hidden fixed left-0 right-0 z-50 items-center justify-center overflow-x-hidden overflow-y-auto top-4 md:inset-0 h-modal sm:h-full flex" id="add-account" aria-modal="true" role="dialog">
    <div class="relative w-full h-full max-w-2xl px-4 md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
            <form action="/accounts/add" method="POST">
                @csrf

                <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                    <h3 class="text-xl font-semibold dark:text-white">
                        Добавление аккаунта
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white" data-modal-toggle="add-account">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                    </button>
                </div>
                <div class="p-6 space-y-6">
                    @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="platform" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Платформа</label>
                            <select name="platform" id="platform" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                <option value="0">Выберите платформу</option>
                                <option value="telegram">Telegram</option>
                                <option value="whatsapp">WhatsApp</option>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="login" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Логин</label>
                            <input type="text" name="login" id="login" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ваш логин или номер телефона" required="">
                        </div>
                    </div>
                </div>
                <div class="items-center p-6 border-t border-gray-200 rounded-b dark:border-gray-700">
                    <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="submit">Добавить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full" id="delete-account" aria-modal="true" role="dialog">
    <div class="relative w-full h-full max-w-md px-4 md:h-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="flex justify-end p-2">
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white" data-modal-toggle="delete-account">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                </button>
            </div>
            <div class="p-6 pt-0 text-center">
                <svg class="w-16 h-16 mx-auto text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="mt-5 mb-6 text-lg text-gray-500 dark:text-gray-400">Вы подтверждаете удаление данного аккаунта?</h3>
                <div id="toast-warning" class="flex items-center w-full mb-6 p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800 hidden" role="alert">
                    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                        </svg>
                        <span class="sr-only">Внимание</span>
                    </div>
                    <div class="ms-3 text-sm font-normal" id="message-error"></div>
                    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-warning" aria-label="Close">
                        <span class="sr-only">Закрыть</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
                <a href="#" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-red-800" id="confirm-delete-account">
                    Подтверждаю
                </a>
                <a href="#" class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" data-modal-toggle="delete-account">
                    Отмена
                </a>
            </div>
        </div>
    </div>
</div>

<button type="button" class="hidden" data-modal-target="delete-account" data-modal-toggle="delete-account" id="delete-account-button"></button>

<template id="accounts-load">
    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
        <td colspan="6" class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">
            <div class="p-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                <span class="font-medium">Загрузка...</span>
            </div>
        </td>
    </tr>
</template>

<template id="accounts-notfound">
    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
        <td colspan="6" class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">
            <div class="p-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                <span class="font-medium">Пусто!</span> Аккаунтов отсутствуют.
            </div>
        </td>
    </tr>
</template>

<script>
    const template = (login, platform) => `<tr class="hover:bg-gray-100 dark:hover:bg-gray-700" id="account-${login}">
        <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">
            ${login}
        </td>
        <td class="p-4 space-x-2 whitespace-nowrap">
            <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900" onclick="deleteAccount({login: '${login}', platform: '${platform}'}, 'confirm')">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                Удалить аккаунт
            </button>
        </td>
    </tr>`
    document.addEventListener('DOMContentLoaded', (e) => {
        listAccount(document.getElementById('platform-list').value)

        @if($errors->any())
        setTimeout(() => {
            addAccount()
        }, 1000)
        @endif
    })

    function listAccount(type)
    {
        document.getElementById('accounts').innerHTML = document.getElementById('accounts-load').innerHTML

        const url = {
            'telegram': '{{ route('account.list', ['type' => 'telegram']) }}',
            'whatsapp': '{{ route('account.list', ['type' => 'whatsapp']) }}'
        }
        
        axios.post(url[type])
        .then(res => {
            const accounts = document.getElementById('accounts')

            accounts.innerHTML = ''

            if(res.data == '')
                document.getElementById('accounts').innerHTML = document.getElementById('accounts-notfound').innerHTML
            else
                for(let e in res.data)
                    accounts.insertAdjacentHTML('beforeend', template(res.data[e], type))
        })
        .catch(err => {
            console.error(err); 
        })
    }

    function deleteAccount(data, confirm)
    {
        if(confirm) {
            document.getElementById('delete-account-button').click()

            return document.getElementById('confirm-delete-account').addEventListener('click', (e) => {
                deleteAccount(data)
            }, {once: true}) 
        }

        axios.post('{{ route('account.delete') }}', {login: data.login, platform: data.platform})
        .then(res => {
            if(res.data.status === 'success') {
                document.getElementById('account-'+data.login).remove()
                document.getElementById('delete-account-button').click()
            } else {
                const toast = document.getElementById('toast-warning')

                const message = document.getElementById('message-error')

                message.innerHTML = res.data.message

                toast.classList.remove('hidden')

                setTimeout(() => {
                    toast.classList.add('hidden')
                }, 1500)
            }
        })
        .catch(err => {
            console.error(err); 
        })
    }

    function addAccount() {
        document.getElementById('add-account-button').click()
    }
</script>
@endsection