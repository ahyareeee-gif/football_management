<!DOCTYPE html>
<html>
<head>
    <title>Tambah Club</title>
</head>
<body>

    <h1>Tambah Club</h1>

    <form action="{{ route('clubs.store') }}" method="POST">
        @csrf

        <p>
            <input
                type="text"
                name="name"
                placeholder="Nama Club"
                required>
        </p>

        <p>
            <input
                type="number"
                name="founded_year"
                placeholder="Tahun Berdiri">
        </p>

        <p>
            <input
                type="text"
                name="city"
                placeholder="Kota">
        </p>

        <p>
            <textarea
                name="address"
                placeholder="Alamat"></textarea>
        </p>

        <p>
            <textarea
                name="description"
                placeholder="Deskripsi Club"></textarea>
        </p>

        <button type="submit">
            Simpan
        </button>

    </form>

</body>
</html>