# Agenda Personal de Contactos

**Asignatura:** Desarrollo Web  
**Tecnologías utilizadas:** PHP, MySQL, HTML y CSS.  
**Herramientas:** Visual Studio Code, XAMPP, Git y GitHub.

## Propósito

Crear una aplicación web para guardar y administrar contactos personales. El sistema permite registrar usuarios, iniciar sesión, agregar contactos, editarlos, consultarlos y eliminarlos.

## Requisitos para su ejecución

- Tener instalado Visual Studio Code.
- Tener instalado XAMPP.
- Contar con un navegador web.
- Tener Git instalado para el control de versiones.
- Tener una cuenta de GitHub.

## Cómo instalar y ejecutar la aplicación localmente

1. Instalar y abrir XAMPP.
2. Iniciar los servicios Apache y MySQL.
3. Copiar la carpeta `agenda_contactos` dentro de `C:\xampp\htdocs\`.
4. Entrar a phpMyAdmin desde el navegador.
5. Crear una base de datos llamada `agenda_personal`.
6. Importar el archivo `agenda_personal_actualizada.sql`.
7. Revisar que la conexión a la base de datos esté configurada correctamente en `conexion.php`.
8. Abrir el navegador e ingresar a `http://localhost/agenda_contactos/`.
9. Registrarse e iniciar sesión para utilizar la aplicación.

## Funcionalidades

- Registrar nuevos usuarios.
- Iniciar y cerrar sesión.
- Agregar nuevos contactos.
- Consultar los contactos guardados.
- Editar la información de los contactos.
- Eliminar contactos.
- Validar que los teléfonos tengan exactamente 10 dígitos.
- Permitir que cada usuario administre sus propios contactos.

## Mejoras implementadas

**1. Búsqueda de contactos**

Se incorporó una herramienta de búsqueda que permite localizar contactos por nombre, correo electrónico o ciudad.

**2. Orden
