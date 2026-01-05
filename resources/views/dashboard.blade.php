<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                    <p class="mt-4">Sveiki atvykę į pagalbos bilietų sistemos valdymo skydelį. Čia galite tvarkyti savo bilietus ir stebėti jų būseną.</p>
                </div>
            </div>

                
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <a href="{{ route('bilietas') }}"
                class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 text-center text-gray-900 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <span class="text-lg font-semibold">Sukurti bilietą</span>
                </a>

                <a href="{{ route('aktyvus') }}" 
                class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 text-center text-gray-900 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <span class="text-lg font-semibold">Aktyvūs bilietai</span>
                </a>

            </div> 
        <x-footer />
        </div>
    </div>
</x-app-layout>
