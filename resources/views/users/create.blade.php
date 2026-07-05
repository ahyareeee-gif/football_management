<h1>Tambah User</h1>

<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <input type="text" name="name" placeholder="Nama">
    <br><br>

    <input type="email" name="email" placeholder="Email">
    <br><br>

    <input type="password" name="password" placeholder="Password">
    <br><br>

    <select name="role">
        @foreach($roles as $role)
            <option value="{{ $role->name }}">
                {{ $role->name }}
            </option>
        @endforeach
    </select>

    <br><br>

    <button type="submit">
        Simpan
    </button>
</form>