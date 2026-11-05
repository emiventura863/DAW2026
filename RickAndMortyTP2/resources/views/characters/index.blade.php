<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Personajes - Rick and Morty</title>
</head>

<body>
    <h1>Personajes de Rick and Morty</h1>

    @foreach ($characters as $character) {{-- Itera sobre cada personaje en la colección $characters --}}
    <div>
        <h2>{{ $character->name }}</h2> {{-- obtiene el nombre del personaje y lo muestra en un encabezado h2 --}}
        <p>Estado: {{ $character->status }}</p>
        <p>Especie: {{ $character->species }}</p>
        <img src="{{ $character->image }}"
            alt="{{ $character->name }}"
            width="150">
    </div>
    @endforeach
</body>

</html>