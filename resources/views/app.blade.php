@php
    $user = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @isset($title)
            {{ $title }} | 
        @endisset
        {{ config('app.name') }}
    </title>

    @isset($keywords)
    <meta name="keywords" content="{{ $keywords }}">
    @endisset

    @isset($description)
    <meta name="description" content="{{ $description }}">
    @endisset

    <link rel="stylesheet" href="{{ asset('assets/css/ui/jolty.min.css') }}">
    @vite('resources/css/app.css')

    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            document.documentElement.setAttribute('data-ui-mode', 'dark')
        } else {
            document.documentElement.classList.remove('dark')
            document.documentElement.removeAttribute('data-ui-mode')
        }

        document.addEventListener('DOMContentLoaded', () => {
            jolty.Toast.container(({position, name}) => {
                const nameClass = name ? `ui-toasts--${name}` : "";
                const positionClass = position ? `ui-toasts--${position}` : "";
                return `<div class="ui-toasts ${nameClass} ${positionClass}"></div>`;
            })

            jolty.Toast.template(({content, type, dismiss, autohide}) => {
                const className = `ui-toast ${type ? "ui-toast--" + type : ""}`;
                const closeBtn = dismiss
                    ? `<button class="ui-btn-close ui-btn-close--no-bg" aria-label="Close"></button>`
                    : "";
                const progress = autohide
                    ? '<div class="ui-toast-progress" data-ui-autohide-progress></div>'
                    : "";
                return `<div class="${className}" data-ui-dismiss><div class="ui-toast-icon"></div><div class="ui-toast-content">${content}</div>${closeBtn}${progress}</div>`;
            });
        })

        const showToast = (content, type) => {
            return new jolty.Toast({
                type,
                content,
                autohide: 3000,
                limit: 3,
                position: "top-end",
                breakpoints: {
                    1024: {
                        limit: 5,
                        position: "top-end",
                    },
                },
            });
        };
        
        const toast = {
            info: (content) => showToast(content, "info"),
            success: (content) => showToast(content, "success"),
            warning: (content) => showToast(content, "warning"),
            error: (content) => showToast(content, "error")
        };
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-800">
    @auth
        @include('layouts.auth')
    @else
        @include('layouts.noauth')
    @endauth

    @vite('resources/js/app.js')
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/ui/jolty.min.js') }}"></script>
</body>
</html>