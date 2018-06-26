<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Cambio de contraseña</title>
</head>
<body>
    <p>Hola! {{ $manager->fullName }}.</p>
    <p>Has solicitado un cambio de contraseña</p>
    <ul>
        <li>Nueva contraseña: {{ $manager->identification->identification_number }}</li>
    </ul>
</body>
</html>