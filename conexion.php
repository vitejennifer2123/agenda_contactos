<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "agenda_personal";

try {
    $conexion = new PDO(
        "mysql:host=$servidor;dbname=$base_datos;charset=utf8mb4",
        $usuario,
        $password
    );
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Error al conectar con la base de datos.");
}

?>
