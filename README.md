# L3TTRO - Backend

Este directorio contiene el Backend del proyecto L3TTRO, el cual actúa principalemente como el núcleo de lógica, validación y persistencia de datos de la plataforma.

## ¿Qué contiene?

El proyecto de Backend está construido utilizando el popular framework de PHP, **Laravel**. Este módulo contiene:
- **API RESTful**: Expone y maneja los diferentes endpoints de comunicación (ej. `/api/stories` y `/api/genres`) que el Frontend consume para leer, crear y editar información.
- **Base de Datos**: Utiliza una base de datos local **SQLite** (ubicada en `database/database.sqlite`) para persistir la información de manera ligera. En la base de datos se guarda la información pertinente al funcionamiento de las historias creadas, géneros disponibles y más recursos si el proyecto lo requiere.
- **Lógica de negocio y CORS**: Implementa toda la seguridad asociada al servidor y los dominios remotos (CORS) permitiendo recibir y enviar las peticiones al dominio donde corre el Frontend (como el puerto 5173 localmente).

El proyecto fue desarrollado por **Sergio Cubero** y **Antonio Sillero**.

## ¿Cómo funciona?

El Backend tiene la responsabilidad exclusiva de procesar peticiones y manejar el acceso a datos.

1. Recibe peticiones HTTP estructuradas que provienen del cliente (Frontend).
2. Procesa la solicitud a través del sistema de enrutamiento (`routes/api.php`) dirigiendo la petición hacia el controlador adecuado.
3. El controlador puede validar o analizar los parámetros enviados.
4. Consulta o actualiza los datos persistentes alojados en la estructura de base de datos comunicándose a través de los Modelos de Laravel basados en "Eloquent ORM".
5. Finalmente, procesa todo el flujo y devuelve una respuesta estructurada siempre en formato JSON.

## Comandos para el desarrollo

Para levantar el entorno de desarrollo del servidor localmente, asegúrate de seguir los siguientes pasos:

1. Instala en tu máquina las dependencias de Composer:
   ```bash
   composer install
   ```
2. Recuerda configurar el archivo `.env` tomando como base `.env.example`, y procede a generar la clave y aplicar migraciones si es tu primera vez iniciando el backend:
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   ```
3. Ejecuta el servidor de desarrollo en la terminal:
   ```bash
   php artisan serve
   ```
El servidor por defecto responderá en `http://localhost:8000` o `http://127.0.0.1:8000`.
