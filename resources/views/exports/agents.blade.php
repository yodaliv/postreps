<table>
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>Zipcode</th>
            <th>Email</th>
            <th>Phone</th>
            <th>RE License</th>
            <th>Account Status</th>
            <th>Office</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($self->agents as $agent)
            <tr>
                <td>{{$agent->first_name}}</td>
                <td>{{$agent->last_name}}</td>
                <td>{{$agent->address}}</td>
                <td>{{$agent->city}}</td>
                <td>{{$agent->state}}</td>
                <td>{{$agent->zipcode}}</td>
                <td>{{$agent->email}}</td>
                <td>{{$agent->phone}}</td>
                <td>{{$agent->re_license}}</td>
                <td>{{! $agent->inactive ? 'active' : 'inactive'}}</td>
                <td>{{$agent->office_name}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
