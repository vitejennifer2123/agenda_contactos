
<?php
session_start();

require_once "conexion.php";

// Verificar que exista una sesión
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

// Buscar el contacto y comprobar que pertenezca al usuario
$consulta = $conexion->prepare(
    "SELECT * FROM contactos WHERE id = ? AND id_usuario = ?"
);

$consulta->execute([$id, $_SESSION["usuario_id"]]);

$contacto = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$contacto) {
    header("Location: contactos.php");
    exit();
}

$mensaje = "";

// Actualizar el contacto
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $telefono = trim($_POST["telefono"]);
    $correo = trim($_POST["correo"]);
    $ciudad = trim($_POST["ciudad"]);

    if (empty($nombre) || empty($telefono)) {

        $mensaje = "El nombre y el teléfono son obligatorios.";

    } elseif (strlen($nombre) > 30) {

        $mensaje = "El nombre no puede superar los 30 caracteres.";

    } elseif (!preg_match('/^[0-9]{10}$/', $telefono)) {

        $mensaje = "El teléfono debe contener exactamente 10 dígitos numéricos.";

    } elseif (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {

        $mensaje = "Ingrese un correo electrónico válido.";

    } else {

        $actualizar = $conexion->prepare(
            "UPDATE contactos
             SET nombre = ?, telefono = ?, correo = ?, ciudad = ?
             WHERE id = ? AND id_usuario = ?"
        );

        $actualizar->execute([
            $nombre,
            $telefono,
            $correo,
            $ciudad,
            $id,
            $_SESSION["usuario_id"]
        ]);

        header("Location: contactos.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar contacto</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 40px 20px;
        }

        .formulario {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            width: 320px;
            margin: 40px auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.10);
        }

        h2, h3 {
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

        .mensaje {
            color: red;
            text-align: center;
            font-weight: bold;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #263445;
        }
    </style>
</head>

<body>

    <div class="formulario">

        <h2>Editar contacto</h2>

        <?php if (!empty($mensaje)) { ?>
            <p class="mensaje">
                <?php echo htmlspecialchars($mensaje); ?>
            </p>
        <?php } ?>

        <form action="editar.php?id=<?php echo $id; ?>" method="POST">

            <label>Nombre completo:</label>
            <input type="text" name="nombre"
                   maxlength="30" required
                   value="<?php echo htmlspecialchars($contacto["nombre"]); ?>">
            <br><br>

            <label>Teléfono:</label>
            <input type="tel" name="telefono"
                   minlength="10"
                   maxlength="10"
                   pattern="[0-9]{10}"
                   inputmode="numeric"
                   title="Ingrese exactamente 10 dígitos numéricos"
                   required
                   value="<?php echo htmlspecialchars($contacto["telefono"]); ?>">
            <br><br>

            <label>Correo electrónico:</label>
            <input type="email" name="correo"
                   value="<?php echo htmlspecialchars($contacto["correo"] ?? ""); ?>">
            <br><br>

            <label>Ciudad:</label>
            <input type="text" name="ciudad"
                   value="<?php echo htmlspecialchars($contacto["ciudad"] ?? ""); ?>">
            <br><br>

            <button type="submit">Guardar cambios</button>

        </form>

        <a href="contactos.php">Volver a mis contactos</a>

    </div>

</body>
</html>
