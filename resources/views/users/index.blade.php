<h1>Data User</h1>

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

<a href="{{ route('users.create') }}">
    Tambah User
</a>

<hr>

<table border="1" cellpadding="10">
    <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>Role</th>
        <th>Aksi</th>
    </tr>

    @foreach($users as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->roles->first()?->name }}</td>
        <td>
            <a href="{{ route('users.edit', $user) }}">
                Edit
            </a>

            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')

                <button type="submit">
                    Hapus
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
