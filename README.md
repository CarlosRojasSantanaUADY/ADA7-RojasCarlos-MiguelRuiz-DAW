# TecnoFix - ADA 7

Proyecto final de Desarrollo de Aplicaciones Web. Es la version concluida del sistema de gestion de reparaciones desarrollado con PHP y MySQL.

Pagina publicada: https://ada7-rojasruiz.gamer.free/index.php

Integrantes:
- Carlos Rojas Santana
- Miguel Jesus Ruiz May

---

## Como correr el proyecto en local (Manual Tecnico)

Necesitas tener instalado XAMPP (Apache y MySQL).

### Paso 1: Poner los archivos en tu servidor local
Descarga o clona este repositorio dentro de la carpeta `htdocs` de tu XAMPP (normalmente en `C:\xampp\htdocs`):

    git clone https://github.com

### Paso 2: Subir la base de datos
1. Enciende Apache y MySQL desde el panel de XAMPP.
2. Abre `http://localhost/phpmyadmin` en tu navegador.
3. Ve a la pestana Importar, selecciona el archivo `sql/init.sql` que viene en el proyecto y dale a Importar.
4. Esto crea la base `tecnofix_db` con las 3 tablas (`usuarios`, `ordenes_reparacion` y `servicios`) junto con algunos registros de ejemplo.

### Paso 3: Revisar `conexion.php`
El archivo `conexion.php` ya viene configurado para XAMPP por defecto:
- Servidor: `localhost`
- Usuario: `root`
- Contrasena: `""`
- Base de datos: `tecnofix_db`

### Paso 4: Probar en el navegador
Entra a `http://localhost/ADA7-RojasCarlos-MiguelRuiz-DAW/index.php`.

### Usuarios de prueba
Si no quieres registrar un usuario nuevo desde cero, puedes iniciar sesion directo con estos correos que ya vienen en la base de datos:
- Administrador: `carlos@tecnofix.com`
- Cliente: `juan@correo.com`

---

## Como usar la pagina (Manual de Usuario)

- Inicio: Muestra la fecha del dia, los servicios del taller y una calculadora en JavaScript para cotizaciones rapidas. En la parte de arriba te dice si estas como invitado o con sesion iniciada.
- Servicios: Muestra el catalogo de reparaciones y precios del taller (CRUD de servicios).
  - Cualquier visitante o cliente puede consultar la lista de servicios.
  - Si inicias sesion con perfil de Administrador o Tecnico, te aparece el formulario para agregar servicios nuevos y los botones Editar y Eliminar en cada fila.
- Acceso: Tiene dos formularios. El de arriba sirve para iniciar sesion con tu correo y el de abajo para registrar un usuario nuevo con su perfil (cliente, tecnico o administrador).
- Ordenes: Es el CRUD principal de ordenes de reparacion con control por sesion y rol.
  - Si entras como Cliente, solo puedes ver, registrar y editar tus propias ordenes.
  - Si entras como Administrador o Tecnico, ves todas las ordenes del taller y puedes actualizar su estado (Recibido, En diagnostico, En reparacion, Listo para entrega o Entregado) y su costo estimado.
- Cerrar Sesion: Aparece en el menu cuando entras con una cuenta y sirve para cerrar tu sesion activa.
