<!-- App Globals -->
<script>
    window.App = {
        // Laravel CSRF Token
        csrfToken: '{{ csrf_token() }}',

        // Current User ID
        userId: {!! Auth::user() ? Auth::id() : 'null' !!},

        // Current Team ID
        currentTeamId: null,
        {{--@if (Auth::user() && Spark::usingTeams() && Auth::user()->hasTeams())--}}
            {{--currentTeamId: {{ Auth::user()->currentTeam->id }},--}}
        {{--@else--}}
        {{--@endif--}}
    }
</script>