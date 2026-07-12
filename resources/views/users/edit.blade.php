<h1>Edit User</h1>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Nama">
    <br><br>

    <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Email">
    <br><br>

    <input type="password" name="password" placeholder="Password baru (opsional)">
    <br><br>

    <select name="role">
        @foreach($roles as $role)
            <option value="{{ $role->name }}" @selected(old('role', $user->roles->first()?->name) === $role->name)>
                {{ $role->name }}
            </option>
        @endforeach
    </select>

    <br><br>

    <button type="submit">
        Update
    </button>
</form>
