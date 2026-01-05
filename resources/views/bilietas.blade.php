<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Sukurti bilietą
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('bilietas.saugoti') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">Pavadinimas</label>
                        <input type="text" name="pavadinimas" required
                               class="w-full p-2 rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">Prioritetas</label>
                        <select name="prioritetas" required
                                class="w-full p-2 rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option>Labai skubus</option>
                            <option>Skubus</option>
                            <option>Vidutinis</option>
                            <option>Žemas</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">Kategorija</label>
                        <select name="kategorija" required
                                class="w-full p-2 rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option>Techninė įranga</option>
                            <option>Programinė įranga</option>
                            <option>Tinklas</option>
                            <option>Prieiga</option>
                            <option>Kita</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">Aprašymas</label>
                        <textarea name="aprasymas" maxlength="500" rows="5" required
                                  class="w-full p-2 rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                    </div>

                    <button type="submit"
                            class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">
                        Registruoti
                    </button>
                </form>

            </div>
        </div>
    <x-footer />
    </div>
</x-app-layout>
