<?php
session_start();

require_once "conexion.php";

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION["usuario_id"])) {
    header("Location: inicio.php");
    exit();
}

// Obtener el ID del contacto
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: contactos.php");
    exit();
}

// Verificar que el contacto pertenezca al usuario
$consulta = $conexion->prepare(
    "SELECT id FROM contactos WHERE id = ? AND id_usuario = ?"
);

$consulta->execute([$id, $_SESSION["usuario_id"]]);

if (!$consulta->fetch()) {
    header("Location: contactos.php");
    exit();
}

// Eliminar únicamente mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $eliminar = $conexion->prepare(
        "DELETE FROM contactos WHERE id = ? AND id_usuario = ?"
    );

    $eliminar->execute([
        $id,
        $_SESSION["usuario_id"]
    ]);

    header("Location: contactos.php?eliminado=exito");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar contacto</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 40px 20px;
        }

        .contenedor {
            background-color: white;
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.10);
            text-align: center;
        }

        h2 {
            color: #263445;
        }

        p {
            color: #444;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #a83232;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #822525;
        }

        a {
            display: block;
            margin-top: 20px;
            color: #263445;
        }
    </style>
</head>

<body>

    <div class="contenedor">

        <h2>Eliminar contacto</h2>

        <p>¿Estás seguro de que deseas eliminar este contacto?</p>

        <form method="POST"
              action="eliminar.php?id=<?php echo $id; ?>">

            <button type="submit">Sí, eliminar contacto</button>

        </form>

        <a href="contactos.php">Cancelar y volver</a>

    </div>

</body>
</html>
