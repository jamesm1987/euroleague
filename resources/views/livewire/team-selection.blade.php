<div x-data="{ activeTab: 0, selectedTeams: @entangle('selectedTeams'), remainingBudget: @entangle('remainingBudget') }">
    <div id="budget-display" class="text-lg font-semibold mb-4 bg-blue-100 p-4 rounded-lg">
        Remaining Budget: £{{ number_format($remainingBudget, 2) }}m
    </div>
    
    <div class="mb-4">
        <nav class="flex space-x-4">
            @foreach ($leagues as $index => $league)
                <button 
                    :class="{ 'bg-blue-500 text-white': activeTab === {{ $index }}, 'bg-gray-200 text-gray-700': activeTab !== {{ $index }} }" 
                    class="px-4 py-2 rounded-lg focus:outline-none"
                    @click="activeTab = {{ $index }}">
                    {{ $league->name }}
                </button>
            @endforeach
        </nav>
    </div>

    @foreach ($leagues as $index => $league)
        <div x-show="activeTab === {{ $index }}" class="space-y-6">
            <section class="league mb-6">
                <p class="text-xl font-semibold pt-2 mb-4 flex items-center">                                                    
                    <img class="h-auto max-w-full mr-2 mb-2" width="40" height="50" src="{{ config('services.api-football.league_logo_url') }}/{{ $league->api_id }}.png" />
                    {{ $league->name }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($league->teams as $team)
                        <div class="relative flex items-center p-2 border rounded-lg cursor-pointer"
                            :class="{ 'bg-blue-500 text-white': @json($selectedTeams).includes({{ $team->id }}) }"
                            wire:click="toggleTeam({{ $team->id }}, {{ $team->price }})">
                            <img class="h-auto max-w-full mr-2" width="25" height="25" src="{{ \Team::getTeamLogo($team->id) }}" />
                            <input type="checkbox" name="teams[]" value="{{ $team->id }}" data-price="{{ $team->getRawOriginal('price') }}" class="hidden team-checkbox">
                            <label for="team-{{ $team->id }}" class="ml-2 block text-sm">
                                {{ $team->name }} ({!! $team->price !!})
                            </label>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    @endforeach

    <div class="mt-4">
        <button id="submit-button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            Submit Selections
        </button>
    </div>

</div>