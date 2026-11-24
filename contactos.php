
<?php
session_start();

require_once "conexion.php";

// Comprobar que el usuario haya iniciado sesión
if (!isset($_SESSION["usuario_id"])) {
    header("Location: inicio.php");
    exit();
}

$mensaje = "";
$tipo_mensaje = "";

// Registrar un nuevo contacto
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $ciudad = trim($_POST["ciudad"] ?? "");

    if (empty($nombre) || empty($telefono)) {
        $mensaje = "El nombre y el teléfono son obligatorios.";
        $tipo_mensaje = "error";

    } elseif (strlen($nombre) > 30) {
        $mensaje = "El nombre no puede superar los 30 caracteres.";
        $tipo_mensaje = "error";

    } elseif (!preg_match('/^[0-9]{10}$/', $telefono)) {
        $mensaje = "El teléfono debe contener exactamente 10 dígitos numéricos.";
        $tipo_mensaje = "error";

    } elseif (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Ingrese un correo electrónico válido.";
        $tipo_mensaje = "error";

    } else {

        $insertar = $conexion->prepare(
            "INSERT INTO contactos (id_usuario, nombre, telefono, correo, ciudad)
             VALUES (?, ?, ?, ?, ?)"
        );

        $insertar->execute([
            $_SESSION["usuario_id"],
            $nombre,
            $telefono,
            $correo,
            $ciudad
        ]);

        header("Location: contactos.php?registro=exito");
        exit();
    }
}

// Mensajes del sistema
if (isset($_GET["registro"]) && $_GET["registro"] == "exito") {
    $mensaje = "¡Contacto registrado correctamente!";
    $tipo_mensaje = "exito";
}

if (isset($_GET["eliminado"]) && $_GET["eliminado"] == "exito") {
    $mensaje = "¡Contacto eliminado correctamente!";
    $tipo_mensaje = "exito";
}

// BÚSQUEDA DE CONTACTOS
$busqueda = trim($_GET["buscar"] ?? "");

// ORDENAMIENTO
$orden = $_GET["orden"] ?? "recientes";

$ordenamientos = [
    "nombre" => "nombre ASC",
    "ciudad" => "ciudad ASC",
    "recientes" => "id DESC"
];

if (!array_key_exists($orden, $ordenamientos)) {
    $orden = "recientes";
}

// CONSULTA DE CONTACTOS
$sql = "SELECT * FROM contactos WHERE id_usuario = ?";

if ($busqueda !== "") {
    $sql .= " AND (
        nombre LIKE ?
        OR correo LIKE ?
        OR ciudad LIKE ?
    )";
}

$sql .= " ORDER BY " . $ordenamientos[$orden];

$consulta = $conexion->prepare($sql);

if ($busqueda !== "") {

    $termino = "%" . $busqueda . "%";

    $consulta->execute([
        $_SESSION["usuario_id"],
        $termino,
        $termino,
        $termino
    ]);

} else {
    $consulta->execute([$_SESSION["usuario_id"]]);
}

$contactos = $consulta->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis contactos</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 30px 20px;
        }

        h2, h3 {
            color: #263445;
            text-align: center;
        }

        .contenedor {
            max-width: 800px;
            margin: auto;
        }

        .formulario, .lista {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.10);
        }

        label {
            font-weight: bold;
        }

        input, select {
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

        .contacto {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 15px 0;
            border-radius: 7px;
            background-color: #fafafa;
        }

        .mensaje {
            text-align: center;
            font-weight: bold;
        }

        .exito {
            color: green;
        }

        .error {
            color: red;
        }

        .enlace {
            display: block;
            text-align: center;
            margin: 20px;
            color: #263445;
        }

        .boton-editar,
        .boton-eliminar {
            display: inline-block;
            padding: 9px 18px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .boton-editar {
            background-color: #263445;
        }

        .boton-eliminar {
            background-color: #a83232;
        }

        .limpiar {
            display: inline-block;
            margin-top: 12px;
            color: #263445;
        }

    </style>
</head>

<body>

<div class="contenedor">

    <h2>AGENDA PERSONAL DE CONTACTOS</h2>

    <div class="formulario">

        <h3>Agregar nuevo contacto</h3>

        <?php if (!empty($mensaje)) { ?>
            <p class="mensaje <?php echo $tipo_mensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </p>
        <?php } ?>

        <form action="contactos.php" method="POST">

            <label>Nombre completo:</label>
            <input type="text" name="nombre" maxlength="30" required>
            <br><br>

            <label>Teléfono:</label>
            <input
                type="tel"
                name="telefono"
                minlength="10"
                maxlength="10"
                pattern="[0-9]{10}"
                inputmode="numeric"
                required>
            <br><br>

            <label>Correo electrónico:</label>
            <input type="email" name="correo">
            <br><br>

            <label>Ciudad:</label>
            <input type="text" name="ciudad">
            <br><br>

            <button type="submit">Guardar contacto</button>

        </form>

    </div>

    <div class="lista">

        <h3>Mis contactos registrados</h3>

        <!-- FORMULARIO DE BUSQUEDA Y ORDENAMIENTO -->
        <form action="contactos.php" method="GET">

            <label>Buscar contacto:</label>

            <input
                type="text"
                name="buscar"
                placeholder="Buscar por nombre, correo o ciudad..."
                value="<?php echo htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>">

            <br><br>

            <label>Ordenar contactos por:</label>

            <select name="orden">

                <option value="recientes" <?php echo $orden == "recientes" ? "selected" : ""; ?>>
                    Más recientes
                </option>

                <option value="nombre" <?php echo $orden == "nombre" ? "selected" : ""; ?>>
                    Nombre (A-Z)
                </option>

                <option value="ciudad" <?php echo $orden == "ciudad" ? "selected" : ""; ?>>
                    Ciudad (A-Z)
                </option>

            </select>

            <br><br>

            <button type="submit">Buscar y ordenar</button>

            <a class="limpiar" href="contactos.php">
                Limpiar búsqueda
            </a>

        </form>

        <hr>

        <?php if (count($contactos) > 0) { ?>

            <?php foreach ($contactos as $contacto) { ?>

                <div class="contacto">

                    <p>
                        <strong>Nombre:</strong>
                        <?php echo htmlspecialchars($contacto["nombre"]); ?>
                    </p>

                    <p>
                        <strong>Teléfono:</strong>
                        <?php echo htmlspecialchars($contacto["telefono"]); ?>
                    </p>

                    <p>
                        <strong>Correo:</strong>
                        <?php echo htmlspecialchars($contacto["correo"] ?? "No registrado"); ?>
                    </p>

                    <p>
                        <strong>Ciudad:</strong>
                        <?php echo htmlspecialchars($contacto["ciudad"] ?? "No registrada"); ?>
                    </p>

                    <a class="boton-editar"
                       href="editar.php?id=<?php echo $contacto['id']; ?>">
                        Editar contacto
                    </a>

                    <a class="boton-eliminar"
                       href="eliminar.php?id=<?php echo $contacto['id']; ?>">
                        Eliminar contacto
                    </a>

                </div>

            <?php } ?>

        <?php } else { ?>

            <p>No se encontraron contactos.</p>

        <?php } ?>

    </div>

    <a class="enlace" href="panel.php">
        Volver al panel principal
    </a>

</div>

</body>
</html>
