# User Management API

## Descripción
Esta API proporciona funcionalidades para la gestión de usuarios, roles y autenticación en un entorno seguro. Incluye endpoints para registrar usuarios, iniciar sesión, listar usuarios, exportar datos y más.

---

## Instrucciones para instalar y configurar el proyecto

### Requisitos previos
- PHP >= 8.1
- Composer
- Servidor Apache o Nginx
- Base de datos MySQL
- Opcional: Postman para pruebas

### Pasos para la instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/Blaaaxo/api_rest_php.git
   cd <NOMBRE_DEL_PROYECTO>
   ```

2. **Instalar dependencias con Composer**
   ```bash
   composer install
   ```

3. **Configurar variables de entorno**
   - Copia el archivo de ejemplo `.env.example` y renómbralo como `.env`.
     ```bash
     cp .env.example .env
     ```
   - Configura las variables necesarias en el archivo `.env`:
     ```env
     DB_HOST=localhost
     DB_PORT=3306
     DB_DATABASE=nombre_base_de_datos
     DB_USERNAME=usuario
     DB_PASSWORD=contraseña
     JWT_SECRET=clave_secreta_para_jwt
     ```

4. **Configurar la base de datos**
   - Importa el esquema desde el archivo SQL que se encuentra en el proyecto, se llama "prueba_tecnica.sql":

5. **Configurar el servidor web**
   - Asegúrate de que la raíz del servidor web apunte al directorio `public/` del proyecto.
   - Si usas xampp asegurate de copiar la carpeta dentro de "htdocs" luego, entra por medio de la url a el directorio public por ejemplo `http://localhost/prueba_tecnica/public/`.

6. **Iniciar el servidor local**
   - Si estás usando PHP directamente:
     ```bash
     php -S localhost:8000 -t public
     ```
   - Accede a la API en: `http://localhost:8000`

---

## Endpoints disponibles

### Autenticación

- **Documentación de la api**
  - Se encuentra en la ruta `/docs`

- **Registro**
  - `POST /api/register`
  - Registra un nuevo usuario.

- **Inicio de sesión**
  - `POST /api/login`
  - Genera un token JWT para autenticación.

### Gestión de usuarios

- **Listar usuarios**
  - `GET /api/users`
  - Lista todos los usuarios con soporte para paginación y filtros.

- **Obtener usuario por ID**
  - `GET /api/users/{id}`
  - Obtiene información de un usuario específico.

- **Actualizar usuario**
  - `PUT /api/users/{id}`
  - Actualiza la información de un usuario existente.

- **Eliminar usuario**
  - `DELETE /api/users/{id}`
  - Elimina un usuario por su ID.

## Recuperación de contraseñas

- **Solicitar el token de restablecimiento de contraseña**
  -`POST /api/password/forgot`
  - Nos da el token para poder solicitar el restablecimiento de la contraseña.

- **Restablecimiento de contraseña**
  - `POST /api/password/reset`
  - Restablecimiento de nuestra contraseña

### Exportación de datos

- **Exportar a CSV**
  - `GET /api/users/export/csv`
  - Genera un archivo CSV con la lista de usuarios.

- **Exportar a PDF**
  - `GET /api/users/export/pdf`
  - Genera un archivo PDF con la lista de usuarios.

---

## Decisiones técnicas tomadas y justificaciones

### Por qué se hizo el proyecto de esta forma

El proyecto fue diseñado de esta manera para garantizar una arquitectura modular, escalable y fácil de mantener. Cada decisión técnica se tomó considerando las mejores prácticas de desarrollo y las necesidades típicas de una API de gestión de usuarios:

1. **Estructura del proyecto**:
   - **Patrón MVC**: Este patrón separa claramente la lógica de negocios, la lógica de la aplicación y las rutas, facilitando la comprensión y la ampliación del proyecto.
   - **PSR-4**: Siguiendo el estándar de autoloading de PHP, se logró una organización clara y predecible de las clases, lo que permite escalar el proyecto a medida que crezca.

2. **Librerías utilizadas**:
   - **Bramus/Router**: Esta librería ligera fue elegida por su simplicidad y flexibilidad para manejar rutas de manera eficiente.
   - **Firebase/JWT**: Proporciona un sistema de autenticación seguro basado en tokens, evitando la necesidad de mantener sesiones en el servidor.
   - **FPDF**: Permite generar reportes en PDF, lo cual es útil para exportar datos en un formato presentable.

3. **Base de datos**:
   - Se utilizó MySQL.
   - La mejor práctica era separar los roles en una tabla aparte (`roles`) para normalizar los datos y garantizar la escalabilidad. En la tabla `users`, se utilizaría un campo `role_id` como clave foránea apuntando al rol correspondiente.
   - Sin embargo, para esta práctica en específico, se utilizó un campo `enum` para los roles en lugar de una relación normalizada, simplificando la implementación sin perder funcionalidad básica.


4. **Seguridad**:
   - **Encriptación de contraseñas**: Todas las contraseñas se almacenan utilizando `password_hash()` con su metodo para bcrypt para protegerlas frente a ataques.
   - **Protección contra inyección SQL**: Todas las consultas a la base de datos utilizan sentencias preparadas.
   - **Autenticación con JWT**: Los endpoints protegidos a traves de un middleware aseguran que solo usuarios autorizados puedan acceder a los recursos.

5. **Manejo de errores**:
   - Las respuestas están estructuradas y utilizan códigos de estado HTTP adecuados (`200`, `400`, `404`, `500`) para proporcionar retroalimentación clara al cliente.

Estas decisiones técnicas hacen que el proyecto sea robusto, seguro y extensible, mientras se mantiene fácil de entender y modificar.

---

