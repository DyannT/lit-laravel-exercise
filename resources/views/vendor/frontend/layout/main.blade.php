<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', '@Master Layout'))</title>

    @yield('style-libraries')
    <style>
        .button {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            background-color: #007bff;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
    @yield('styles')
</head>
<body>
@if(backpack_auth()->check())
    <a class="button" href="/admin/dashboard">Dashboard</a>
    <a class="button" href="/admin/logout">Logout</a>
@else
    <a class="button" href="/admin/login">Login</a>
    <a class="button" href="/admin/register">Register</a>
@endif
@yield('content')
@yield('scripts')
</body>
</html>
