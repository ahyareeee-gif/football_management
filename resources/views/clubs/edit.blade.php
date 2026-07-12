<!DOCTYPE html>
<html>
<head>
    <title>Edit Club</title>
</head>
<body>

<h1>Edit Club</h1>

<form action="{{ route('clubs.update', $club->id) }}"
      method="POST">

    @csrf
    @method('PUT')

    <p>
        <input type="text"
               name="name"
               value="{{ $club->name }}">
    </p>

    <p>
        <input type="number"
               name="founded_year"
               value="{{ $club->founded_year }}">
    </p>

    <p>
        <input type="text"
               name="city"
               value="{{ $club->city }}">
    </p>

    <p>
        <textarea name="address">{{ $club->address }}</textarea>
    </p>

    <p>
        <textarea name="description">{{ $club->description }}</textarea>
    </p>

    <button type="submit">
        Update
    </button>

</form>

</body>
</html>