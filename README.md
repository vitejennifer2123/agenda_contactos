# Agenda Personal de Contactos
**Asignatura:** Desarrollo Web
**Tecnologias utilizadas:** PHP, MySQL, HTML y CSS.
**Herramientas:** Visual Studio Code, XAMPP, Git y GitHub.
## Proposito
Crear una pagina web para guardar y administrar contactos personales, el sistema permite registrar usuarios, iniciar sesion, agregar contactos, editarlos, consultarlos y eliminarlos.
## Requisitos para su ejecucion
- Tener instalado Visual Studio Code.
- Tener instalado XAMPP.
- Contar con un navegador web.
- Tener Git instalado para el control de versiones.
- Tener una cuenta de GitHub para subir el proyecto.
## Como instalar y ejecutar la aplicacion
- Instalar y abrir XAMPP.
- Iniciar los servicios Apache y MySQL.
- Copiar la carpeta `agenda_contactos` dentro de `C:\xampp\htdocs`.
- Entrar a phpMyAdmin desde el navegador.
- Crear una base de datos llamada `agenda_personal`.
- Importar el archivo `agenda_personal.sql`.
- Revisar que la conexion a la base de datos este configurada correctamente en `conexion.php`.
- Abrir el navegador e ingresar a `http://localhost/agenda_contactos/`.
- Registrarse e iniciar sesion para utilizar la pagina.
## Funcionalidades
- Registrar nuevos usuarios.
- Iniciar y cerrar sesion.
- Agregar nuevos contactos.
- Ver los contactos guardados.
- Editar la informacion de los contactos.
- Eliminar contactos.
- Validar que los telefonos tengan exactamente 10 digitos.
- Permitir que cada usuario administre sus propios contactos.
## Seguridad
- Las contraseñas se guardan utilizando `password_hash()`.
- Se utiliza `password_verify()` para comprobar las contraseñas.
- Se utilizan consultas preparadas para trabajar con la base de datos.
- Se verifica que el usuario haya iniciado sesion.
- Cada usuario solamente puede ver, editar y eliminar sus propios contactos.
## Git y GitHub
- Se utilizara Git para llevar el control de versiones del proyecto.
- Se utilizara GitHub para guardar y compartir el codigo fuente.
- Se creara un repositorio publico para la entrega del trabajo.
## Entregable
- Codigo fuente del proyecto.
- Archivo de la base de datos `agenda_personal.sql`.
- Archivo `README.md`.
- Repositorio publico en GitHub.
## Consideracion importante
Para utilizar el sistema es necesario tener activos Apache y MySQL en XAMPP.