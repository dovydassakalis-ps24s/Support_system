<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pagalbos bilietų sistema</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col items-center justify-center">
        <img src="{{ asset('images/tickets.svg') }}" alt="Logo" class="h-20 w-auto">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">
            Pagalbos bilietų sistema
        </h1>

        <div class="flex gap-4">
    <a href="{{ route('login') }}"
       class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">
        Prisijungti
    </a>

    <a href="{{ route('register') }}"
       class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">
        Registruotis
    </a>
</div>
<footer class="mt-12 text-center text-sm text-gray-500 dark:text-gray-400">
    &copy; 2026 Laravel projektas. Dovydas Sakalis PS24s.
</footer>
</body>
</html>
