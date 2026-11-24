<?php
session_start();

require_once "conexion.php";

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION["usuario_id"])) {
    header("Location: inicio.php");
    exit();
}

// Obtener los contactos del usuario que inició sesión
$consulta = $conexion->prepare(
    "SELECT * FROM contactos WHERE id_usuario = ?"
);

$consulta->execute([$_SESSION["usuario_id"]]);

$contactos = $consulta->fetchAll(PDO::FETCH_ASSOC);

$total_contactos = count($contactos);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel principal</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 40px 20px;
        }

        .contenedor {
            background-color: white;
            max-width: 650px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.10);
            text-align: center;
        }

        h2, h3 {
            color: #263445;
        }

        .datos {
            background-color: #eaf2ff;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .boton {
            display: block;
            padding: 13px;
            margin: 15px 0;
            background-color: #263445;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .boton:hover {
            background-color: #40566e;
        }

        .salir {
            background-color: #a83232;
        }

        .salir:hover {
            background-color: #822525;
        }
    </style>
</head>

<body>

    <div class="contenedor">

        <h2>AGENDA PERSONAL DE CONTACTOS</h2>

        <h3>
            ¡Bienvenido/a,
            <?php echo htmlspecialchars($_SESSION["nombre"]); ?>!
        </h3>

        <div class="datos">

            <p>
                <strong>Correo:</strong>
                <?php echo htmlspecialchars($_SESSION["correo"]); ?>
            </p>

            <p>
                <strong>Contactos registrados:</strong>
                <?php echo $total_contactos; ?>
            </p>

        </div>

        <h3>Panel principal</h3>

        <p>Desde aquí puedes administrar tu agenda personal.</p>

        <a href="contactos.php" class="boton">
            Administrar mis contactos
        </a>

        <a href="cerrar_sesion.php" class="boton salir">
            Cerrar sesión
        </a>

    </div>

</body>
</html>
