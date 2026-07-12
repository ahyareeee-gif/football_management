<!DOCTYPE html>
<html>
<head>
    <title>Data Club</title>
</head>
<body>

<h1>Data Club</h1>

<a href="{{ route('clubs.create') }}">
    Tambah Club
</a>

<br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>No</th>
        <th>Nama Club</th>
        <th>Kota</th>
        <th>Tahun Berdiri</th>
        <th>Aksi</th>
    </tr>

    @foreach($clubs as $club)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $club->name }}</td>
        <td>{{ $club->city }}</td>
        <td>{{ $club->founded_year }}</td>
        <td>
            <a href="{{ route('clubs.edit', $club->id) }}">
                Edit
            </a>

            <form action="{{ route('clubs.destroy', $club->id) }}"
                  method="POST"
                  style="display:inline">

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

</body>
</html>