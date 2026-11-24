<?php
session_start();

require_once "conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $correo = trim($_POST["correo"]);
    $password = $_POST["password"];

    if (empty($correo) || empty($password)) {

        $mensaje = "Complete todos los campos.";

    } else {

        $consulta = $conexion->prepare(
            "SELECT id, nombre, correo, password
             FROM usuarios
             WHERE correo = ?"
        );

        $consulta->execute([$correo]);

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario["password"])) {

            session_regenerate_id(true);

            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["nombre"] = $usuario["nombre"];
            $_SESSION["correo"] = $usuario["correo"];

            header("Location: panel.php");
            exit();

        } else {

            $mensaje = "Correo o contraseña incorrectos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>

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

        .formulario {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            width: 320px;
            margin: 40px auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.10);
        }

        h3 {
            color: #263445;
            text-align: center;
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

        .mensaje {
            text-align: center;
            color: red;
            font-weight: bold;
            margin: 15px auto;
        }

        .enlace {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #263445;
        }
    </style>
</head>

<body>

    <h2>AGENDA PERSONAL DE CONTACTOS</h2>

    <?php if (!empty($mensaje)) { ?>
        <div class="mensaje">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php } ?>

    <div class="formulario">

        <h3>Iniciar sesión</h3>

        <form action="inicio.php" method="POST">

            <label>Correo electrónico:</label>
            <input type="email" name="correo" required>
            <br><br>

            <label>Contraseña:</label>
            <input type="password" name="password" required>
            <br><br>

            <button type="submit">Ingresar</button>

        </form>

        <div class="enlace">
            <p>¿No tienes una cuenta?</p>
            <a href="registro.php">Registrarse</a>
        </div>

    </div>

</body>
</html>
