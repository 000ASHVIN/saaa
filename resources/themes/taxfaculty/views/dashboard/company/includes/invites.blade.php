<table class="table table-bordered table-striped">
    <thead>
    <th>Full Name</th>
    <th>Email Address</th>
    <th>Date</th>
    <th colspan="3">Status</th>
    </thead>
    <tbody>
    @if(count($invites))
        @foreach($invites as $invite)
            <tr>
                <td>{{ $invite->first_name.' '.$invite->last_name }}</td>
                <td>{{ $invite->email }}</td>
                <td>{{ date_format($invite->created_at, 'd F') }}</td>
                <td>{{ ($invite->completed ? "Completed" : "Pending") }}</td>
                @if(! $invite->completed)
                    <td class="text-center"><a data-toggle="tooltip" data-placement="top" title="Resend" onclick="spin(this)" href="{{ route('dashboard.company.resend_invite', $invite->token) }}"><div class="label label-info"><i class="fa fa-refresh"></i></div></a></td>
                    <td class="text-center"><a data-toggle="tooltip" data-placement="top" title="Cancel" onclick="spin(this)" href="{{ route('dashboard.company.cancel_invite', $invite->token) }}"><div class="label label-danger"><i class="fa fa-close"></i></div></a></td>
                @endif
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="4">You have not send any invitations</td>
        </tr>
    @endif
    </tbody>
</table>