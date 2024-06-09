<div>
    <h2>League Table</h2>
    <table>
        <thead>
            <tr>
                <th>Position</th>
                <th>Team</th>
                <th>Points</th>
                <th>Played</th>
                <th>Won</th>
                <th>Drawn</th>
                <th>Lost</th>
                <th>Goals For</th>
                <th>Goals Against</th>
                <th>Goal Difference</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teams as $index => $team)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->fixture_points }}</td>
                    <td>{{ $team->played }}</td>
                    <td>{{ $team->won }}</td>
                    <td>{{ $team->drawn }}</td>
                    <td>{{ $team->lost }}</td>
                    <td>{{ $team->goals_for }}</td>
                    <td>{{ $team->goals_against }}</td>
                    <td>{{ $team->goal_difference }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>