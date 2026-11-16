<?php
require_once "conexion.php";

$mensaje = "";
$tipo_mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $password = $_POST["password"];
    $confirmar_password = $_POST["confirmar_password"];

    if (empty($nombre) || strlen($nombre) > 30) {

        $mensaje = "El nombre debe tener entre 1 y 30 caracteres.";

    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {

        $mensaje = "Ingrese un correo electrónico válido.";

    } elseif ($password !== $confirmar_password) {

        $mensaje = "Las contraseñas no coinciden.";

    } elseif (
        preg_match_all('/[0-9]/', $password) < 6 ||
        preg_match_all('/[A-Z]/', $password) < 2 ||
        preg_match_all('/[^a-zA-Z0-9\s]/', $password) < 2
    ) {

        $mensaje = "La contraseña no cumple los requisitos de seguridad.";

    } else {

        $consulta = $conexion->prepare(
            "SELECT id FROM usuarios WHERE correo = ?"
        );

        $consulta->execute([$correo]);

        if ($consulta->fetch()) {

            $mensaje = "Este correo ya se encuentra registrado.";

        } else {

            $password_seguro = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $insertar = $conexion->prepare(
                "INSERT INTO usuarios (nombre, correo, password)
                 VALUES (?, ?, ?)"
            );

            $insertar->execute([
                $nombre,
                $correo,
                $password_seguro
            ]);

            $mensaje = "¡Registro realizado correctamente!";
            $tipo_mensaje = "exito";
        }
    }

    if ($tipo_mensaje == "") {
        $tipo_mensaje = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de usuario</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 40px 20px;
        }

        h2 {
            text-align: center;
            color: #263445;
        }

        .contenedor {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 25px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .formulario, .requisitos {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            width: 320px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.10);
        }

        h3 {
            color: #263445;
        }

        label {
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 7px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #263445;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #40566e;
        }

        .requisitos {
            background-color: #eaf2ff;
        }

        .requisitos li {
            margin-bottom: 12px;
        }

        .mensaje {
            text-align: center;
            font-weight: bold;
            margin: 15px auto;
        }

        .exito {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>

    <h2>AGENDA PERSONAL DE CONTACTOS</h2>

 <?php if (!empty($mensaje)) { ?>
    <div class="mensaje <?php echo $tipo_mensaje; ?>">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>

    <?php if ($tipo_mensaje == "exito") { ?>
        <a href="inicio.php">Volver al inicio de sesión</a>
    <?php } ?>
<?php } ?>

    <div class="contenedor">

        <div class="formulario">

            <h3>Crear una cuenta</h3>

            <form action="registro.php" method="POST">

                <label>Nombre completo:</label>
                <input type="text" name="nombre"
                       maxlength="30" required>
                <br><br>

                <label>Correo electrónico:</label>
                <input type="email" name="correo" required>
                <br><br>

                <label>Crear contraseña:</label>
                <input type="password" name="password"
                       minlength="10" required>
                <br><br>

                <label>Repetir contraseña:</label>
                <input type="password" name="confirmar_password"
                       minlength="10" required>
                <br><br>

                <button type="submit">Registrarse</button>

            </form>

        </div>

        <div class="requisitos">

            <h3>Seguridad de contraseña</h3>

            <p>Tu contraseña debe cumplir los siguientes requisitos:</p>

            <ul>
                <li>Contener al menos 6 números.</li>
                <li>Contener al menos 2 letras mayúsculas.</li>
                <li>Contener al menos 2 caracteres especiales.</li>
                <li>Repetir la misma contraseña en ambos campos.</li>
            </ul>

            <p><strong>Caracteres especiales permitidos como ejemplo:</strong></p>

            <p>@ # $ ! % & *</p>

        </div>

    </div>

</body>
</html>


