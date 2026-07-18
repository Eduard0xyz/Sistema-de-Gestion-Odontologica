Grupo 2


 Avelino Martin, Piero Antony


 Cuya Vargas, Joan Bradley


 Janampa Esteban, Carlos Antonio


 Morales Martin, Eduardo Andrés


 Verde Inocencio Stibhen Jherark




## Instalación y Puesta en Marcha

Sigue estos pasos en orden para dejar el sistema funcionando:

### 1. Requisitos previos
- Tener instalado **XAMPP** o **Laragon** (cualquiera de los dos sirve, ambos incluyen Apache y MySQL).
- Tener instalado **MySQL Workbench** para cargar la base de datos.
- Copiar la carpeta del proyecto dentro de:
  - Si usas XAMPP: `C:\xampp\htdocs\`
  - Si usas Laragon: `C:\laragon\www\`

### 2. Cargar la base de datos con MySQL Workbench
1. Inicia el servicio de MySQL (desde el Panel de Control de XAMPP, o automáticamente si usas Laragon).
2. Abre **MySQL Workbench** y conéctate a tu servidor local (normalmente `root` sin contraseña, host `127.0.0.1`, puerto `3306`).
3. Ve a **File > Open SQL Script...** y selecciona el archivo `base_datos.sql` incluido en el proyecto (dentro de `php/config/`).
4. Con el script abierto, ejecútalo completo (ícono del rayo ⚡ o `Ctrl + Shift + Enter`). Esto creará la base de datos `clinica_dental` con todas sus tablas (usuario, paciente, odontologo, cita, etc.).
5. Verifica en el panel izquierdo (Schemas) que la base `clinica_dental` haya aparecido con sus tablas.

### 3. Crear los usuarios de prueba
1. Con la base de datos ya cargada, entra en el navegador a:

http://localhost/Sistema-de-Gestion-Odontologica-main/php/config/crear_usuarios.php

2. Este script crea automáticamente un usuario por cada rol, con su contraseña encriptada:
   - `admin` / `admin123` (Administrador)
   - `odontologo1` / `odonto123` (Odontólogo)
   - `recepcion` / `recepcion123` (Recepcionista)
3. **Ejecutar solo una vez.** Si vuelves a entrar a esa URL y ya existen usuarios, el script no duplicará los datos.

### 4. Iniciar sesión
1. Entra a:

http://localhost/Sistema-de-Gestion-Odontologica-main/

2. Serás redirigido automáticamente a la vista de **Login**.
3. Ingresa con cualquiera de los usuarios de prueba creados en el paso anterior.
4. Según el rol con el que ingreses, verás un menú distinto:
   - **Administrador / Recepcionista:** Dashboard, Pacientes, Odontólogos, Citas, Historial Clínico, Recordatorios.
   - **Odontólogo:** Mi Agenda, Historial Clínico.


