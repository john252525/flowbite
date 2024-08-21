@extends('app', [
    'title' => __('Accounts')
])

@section('content')
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="flex items-center gap-2 mb-3">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ __('Accounts') }}</h1>
        <span class="hidden lg:block px-3 py-1 bg-gray-50 dark:bg-gray-800 dark:border dark:border-gray-700 text-gray-500 dark:text-white text-xl font-bold rounded-md platform-title">
            <div class="px-3 py-1 text-xs font-medium leading-none text-center text-blue-800 rounded-full animate-pulse dark:text-blue-200" role="status">{{ __('Loading') }}...</div>
        </span>
    </div>
    <div class="flex items-center gap-2">
        <button data-ui-toggle="platform-select" class="ui-btn ui-btn--primary ui-btn--md ui-btn--soft flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-view-list" viewBox="0 0 16 16">
                <path d="M3 4.5h10a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2m0 1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1zM1 2a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 2m0 12a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 14"/>
            </svg>
            <span class="platform-title"></span>
        </button>
        <div class="ui-dropdown" data-ui-trigger="click" data-ui-dropdown id="platform-select" hidden>
            <a href="#" class="ui-dropdown-item" data-ui-dropdown-item id="item-telegram" onclick="account.platform = 'telegram'; account.action('list')">Telegram</a>
            <a href="#" class="ui-dropdown-item" data-ui-dropdown-item id="item-whatsapp" onclick="account.platform = 'whatsapp'; account.action('list')">WhatsApp</a>
        </div>

        <button data-ui-toggle="add-account" class="ui-btn ui-btn--primary ui-btn--md">
            <svg class="w-5 h-5 mr-1 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
            {{ __('Add') }}
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
                            <th scope="col" class="p-4 w-auto text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                {{ __('messages.account.login') }}
                            </th>
                            <th scope="col" class="p-4 w-full text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                {{ __('messages.account.step') }}
                            </th>
                            <th scope="col" class="p-4 text-end text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                {{ __('messages.account.action') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700" id="accounts">
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            <td colspan="6" class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ __('Loading') }}...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
 
<!-- Add Account Modal -->
<dialog data-ui-dialog class="ui-dialog ui-dialog--lg" id="add-account" hidden>
    <div data-ui-dialog-title class="ui-dialog-title">{{ __('Adding an account') }}</div>
    <div data-ui-dialog-description>
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-3">
                    <label for="platform" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('messages.account.platform') }}</label>
                    <select name="platform" id="platform" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <option value="0">{{ __('messages.account.platform_select') }}</option>
                        <option value="telegram">Telegram</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <label for="login" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('messages.account.login') }}</label>
                    <input type="text" name="login" id="login" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{ __('messages.account.placeholders.login_or_phone') }}" required="">
                </div>
            </div>
        </div>
        <div class="text-end p-6 border-t border-gray-200 rounded-b dark:border-gray-700">
            <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" onclick="account.action('add')">{{ __('Add') }}</button>
        </div>
    </div>
    <button data-ui-dismiss class="ui-btn-close ui-dialog-close" aria-label="Close"></button>
</dialog>

<!-- Delete Account Modal -->
<div class="ui-dialog-root" data-ui-dialog id="delete-account" hidden>
    <div class="ui-dialog-backdrop" data-ui-dialog-backdrop></div>
    <div class="ui-dialog" data-ui-dialog-content>
        <div data-ui-dialog-description class="ui-dialog-description">
            <div class="p-6 pt-0 text-center">
                <svg class="w-16 h-16 mx-auto text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="mt-5 mb-6 text-lg text-gray-500 dark:text-gray-400">{{ __('messages.account.confirm_delete') }}?</h3>

                <a href="#" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-red-800" id="confirm-delete-account">
                    {{ __('Confirm') }}
                </a>
                <a href="#" class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" data-ui-dismiss>
                    {{ __('Cancel') }}
                </a>
            </div>
        </div>
        <button data-ui-dismiss class="ui-btn-close ui-dialog-close" aria-label="Close"></button>
    </div>
</div>

<template id="accounts-load">
    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
        <td colspan="6" class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">
            <div class="flex items-center justify-center h-16 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                <div class="px-3 py-1 text-xs font-medium leading-none text-center text-blue-800 bg-blue-200 rounded-full animate-pulse dark:bg-blue-900 dark:text-blue-200">{{ __('Loading') }}...</div>
            </div>
        </td>
    </tr>
</template>

<template id="accounts-notfound">
    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
        <td colspan="6" class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">
            <div class="p-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                <span class="font-medium">{{ __('Emtpy') }}!</span> {{ __('messages.account.empty') }}.
            </div>
        </td>
    </tr>
</template>

<template id="account-actions">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
    </svg>
    <span>{{ __('messages.account.action') }}</span>
</template>

<template id="account-action-load">
    <div class="px-3 py-1 text-xs font-medium leading-none text-center text-blue-800 rounded-full animate-pulse dark:text-blue-200" role="status">{{ __('Loading') }}...</div>
</template>

<!-- Screenshots -->
<dialog data-ui-dialog class="ui-dialog ui-dialog--md" id="screenshot_dialog" hidden>
    <div data-ui-dialog-title class="ui-dialog-title">{{ __('Sceenshot') }}</div>
    <div data-ui-dialog-description>
        <div class="px-3 py-1 text-xs font-medium leading-none text-center text-blue-800 rounded-full animate-pulse dark:text-blue-200" role="status">{{ __('Loading') }}...</div>
    </div>
    <button data-ui-dismiss class="ui-btn-close ui-dialog-close" aria-label="Close" onclick="account.__status('#screenshot_dialog [role=status]')"></button>
</dialog>

<!-- Settings -->
<dialog data-ui-dialog class="ui-dialog ui-dialog--md" id="settings_dialog" hidden>
    <div data-ui-dialog-description>
        <label for="webhooks" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Webhook URLs ({{ __('messages.account.other.webhookTitle') }})</label>
        <textarea id="webhooks" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 disabled"></textarea>

        <div class="px-3 py-1 text-xs font-medium leading-none text-center text-blue-800 rounded-full animate-pulse dark:text-blue-200" role="status">{{ __('Loading') }}...</div>

        <div class="text-end mt-5 rounded-b dark:border-gray-700 hidden" role="status">
            <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('Save') }}</button>
        </div>
    </div>
    <button data-ui-dismiss class="ui-btn-close ui-dialog-close" aria-label="Close" onclick="account.__status('#settings_dialog [role=status]')"></button>
</dialog>

<script src="{{ asset('assets/js/account.min.js') }}"></script>

@php
    $actions = ['settings', 'screenshot', 'setState', 'forceStop', 'getNewProxy', 'Reset', 'disablePhoneAuth', 'enablePhoneAuth', 'getAuthCode', 'delete'];
    $classesHover = [
        'setState' => 'hover:text-green-500',
        'Reset' => 'hover:text-red-500',
        'delete' => 'hover:text-red-500'
    ];
@endphp
<script>
    const template = (login, data, platform) => `<tr class="hover:bg-gray-100 dark:hover:bg-gray-700" id="account-${login}">
        <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">
            ${login}
        </td>
        <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white group">
            ${data?.step ? `
            <span class="group-hover:hidden">${data.step.value}</span>
            <span class="hidden group-hover:block">${data.step.message}</span>
            ` : ''}
        </td>
        <td class="p-4 space-x-2 whitespace-nowrap">
            <button data-ui-toggle="actions-list-${login}" id="actions-${login}" class="ui-btn ui-btn--primary ui-btn--md ui-btn--soft flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                </svg>
                <span>{{ __('messages.account.action') }}</span>
            </button>
            <div class="ui-dropdown" data-ui-trigger="click" data-ui-dropdown id="actions-list-${login}" hidden>
                @foreach ($actions as $action)
                @if($action === 'enablePhoneAuth')
                <button class="ui-dropdown-item {{ $classesHover[$action] ?? '' }}" data-ui-toggle="enablePhoneAuth-${login}">{{ __('messages.account.actions.'.$action) }}</button>
                <div class="ui-popover" data-ui-popover id="enablePhoneAuth-${login}" hidden>
                    <div>
                        <label for="price" class="block text-sm font-medium leading-6 text-gray-900">{{ __('Phone number') }}</label>
                        <div class="relative mt-2 rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2">
                                <span class="text-gray-500 sm:text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-phone" viewBox="0 0 16 16">
                                        <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                        <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                    </svg>
                                </span>
                            </div>
                            <input type="text" id="phone_number" placeholder="+14155550132" class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>

                        <button type="button" role="status" class="ui-btn ui-btn--primary ui-btn--xs ui-btn--soft mt-2" onclick="account.action('{{ $action }}', {login: '${login}', phone: document.getElementById('phone_number').value})">{{ __('messages.account.actions.'.$action) }}</button>
                        <div class="px-3 py-1 text-xs font-medium leading-none text-center text-blue-800 rounded-full animate-pulse dark:text-blue-200 hidden" role="status">{{ __('Loading') }}...</div>
                    </div>
                    <button class="ui-btn-close ui-popover-close" aria-label="Close" data-ui-dismiss></button>
                </div>
                @else
                <a href="javascript:;" class="ui-dropdown-item {{ $classesHover[$action] ?? '' }}" data-ui-dropdown-item onclick="account.action('{{ $action }}', {login: '${login}'})">
                    {{ __('messages.account.actions.'.$action) }}
                </a>
                @endif
                @endforeach
            </div>
        </td>
    </tr>`

    document.addEventListener('DOMContentLoaded', (e) => {
        account.path = `{{ route('account.action') }}`
        account.platform = localStorage.getItem('platform-select') || 'telegram'

        account.action('list')
    })
</script>
@endsection