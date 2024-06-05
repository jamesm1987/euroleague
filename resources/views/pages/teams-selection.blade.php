<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Select Your Teams') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
                    <p class="text-2xl font-bold mb-6">Pick 2 teams from each league</p>

                    @livewire('team-selection', ['leagues' => $leagues])
                </div>
            </div>
        </div>
    </div>

</x-app-layout>