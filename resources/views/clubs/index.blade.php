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

    <hr>

    <table border="1" cellpadding="10">
        <tr>
            <th>No</th>
            <th>Nama Club</th>
            <th>Kota</th>
            <th>Tahun Berdiri</th>
        </tr>

        @forelse($clubs as $club)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $club->name }}</td>
            <td>{{ $club->city }}</td>
            <td>{{ $club->founded_year }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4">Belum ada data club</td>
        </tr>
        @endforelse

    </table>

</body>
</html>