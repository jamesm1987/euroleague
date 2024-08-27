
<template>
    <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Select Your Teams
        </h2>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
                        <p class="text-2xl font-bold mb-6">Pick 2 teams from each league</p>

                        <div class="text-lg font-semibold mb-4 bg-blue-100 p-4 rounded-lg">
                            Remaining Budget: £{{ remainingBudget }}m
                        </div>

                        <div>
                            <nav class="flex space-x-4 mb-4">
                                <button
                                    v-for="(league, index) in leagues"
                                    :key="league.id"
                                    :class="{ 'outline:blue-500': activeTab === index, 'text-gray-700': activeTab !== index }"
                                    class="px-4 py-2 rounded-lg focus:outline-none flex items-center"
                                    @click="activeTab = index"
                                >
                                    {{ league.name }}
                                    <img class="h-auto max-w-full ml-2 mb-2" width="25" height="40" :src="getLeagueLogoUrl(league.api_id)" />
                                </button>
                            </nav>
                        </div>

                        <form @submit.prevent="submitSelections">
                            <div v-for="(league, index) in leagues" :key="league.id" v-show="activeTab === index">
                                <section class="league mb-6">
                                    <p class="text-xl font-semibold pt-2 mb-4 flex items-center">
                                        <img class="h-auto max-w-full mr-2 mb-2" width="40" height="50" :src="getLeagueLogoUrl(league.api_id)" />
                                        {{ league.name }}
                                    </p>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <div
                                            v-for="team in league.teams"
                                            :key="team.id"
                                            class="relative flex items-center p-2 border rounded-lg cursor-pointer">
                                            <img class="absolute h-full w-full object-contain pointer-events-none pl-2 py-3 object-left top-0 left-0 mr-2" width="32" height="32" :src="getTeamLogoUrl(team.api_id)" />
                                            <input :id="'team-' + team.id" type="checkbox" class="hidden">
                                            <label :for="'team-' + team.id" class="pl-10 block text-sm w-full flex justify-between items-center">
                                                {{ team.name }} (£{{ team.price }}m)
                                            
                                            <button
                                                :disabled="!selectedTeams.includes(team.id) && leagueSelections[league.id] >= 2"
                                                :class="{
                                                    'bg-blue-500 text-white border-transparent': selectedTeams.includes(team.id), 
                                                    'bg-transparent text-blue-700 border-blue-500 hover:bg-blue-500 hover:text-white hover:border-transparent': !selectedTeams.includes(team.id) && leagueSelections[league.id] < 2,
                                                    'opacity-50 cursor-not-allowed': !selectedTeams.includes(team.id) && leagueSelections[league.id] >= 2
                                                }"
                                                class="font-semibold py-2 px-4 rounded border"
                                                @click.stop.prevent="toggleTeam(team.id, team.price, league.id)">
                                                {{ selectedTeams.includes(team.id) ? 'Selected' : 'Select' }}
                                            </button>
                                        </label>

                                        </div>
                                    </div>
                                </section>
                            </div>

                            <div class="mt-4">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                    Submit Selections
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        leagues: Array
    },
    data() {
        return {
            activeTab: 0,
            selectedTeams: [],
            leagueSelections: {},
            remainingBudget: 125
        }
    },
    methods: {
        getCountryLogoUrl(api_id) {
            return `${this.$page.props.apiUrls.country_logo_url}/${api_id}.svg`
        },
        getLeagueLogoUrl(api_id) {
            return `${this.$page.props.apiUrls.league_logo_url}/${api_id}.png`
        },
        getTeamLogoUrl(api_id) {
            return `${this.$page.props.apiUrls.team_logo_url}/${api_id}.png`
        },
        toggleTeam(teamId, teamPrice, leagueId) {
            const selectedTeamsInLeague = this.leagueSelections[leagueId] || 0;

            if (this.selectedTeams.includes(teamId)) {
                this.selectedTeams = this.selectedTeams.filter(id => id !== teamId)
                this.remainingBudget += teamPrice

                this.leagueSelections[leagueId]--
            } else {

                if (this.remainingBudget >= teamPrice && selectedTeamsInLeague < 2) {
                    this.selectedTeams.push(teamId)
                    this.remainingBudget -= teamPrice
                    if (!this.leagueSelections[leagueId]) {
                        this.leagueSelections[leagueId] = 1
                    } else {
                        this.leagueSelections[leagueId]++
                        this.text = 'Select'
                    }
                }
            }

            console.log(this.selectedTeams)
        },
        getTeamLeagueId(teamId) {

            for (let league of this.leagues) {
                if (league.teams.some(team => team.id === teamId)) {
                    return league.id
                }
            }
            return null;
        },
        submitSelections() {
            if (Object.values(this.leagueSelections).some(count => count !== 2)) {
                alert('Please select exactly 2 teams from each league.');
                return;
            }

            this.$inertia.post('/teams-selection', {
                selectedTeams: this.selectedTeams
            }, {
                // Optional success message or redirection can go here
                onSuccess: () => {
                    console.log('Selection submitted successfully!');
                },
                onError: () => {
                    console.error('There was an error submitting the selections.');
                }
            });
        }
    }
};
</script>

<style scoped>
/* Add custom styles here */
</style>