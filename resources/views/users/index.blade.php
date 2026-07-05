<h1>Data User</h1>

<a href="{{ route('users.create') }}">
    Tambah User
</a>

<hr>

<table border="1">
    <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>Role</th>
    </tr>

    @foreach($users as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->roles->first()?->name }}</td>
    </tr>
    @endforeach
</table>