# 🛍️ TiendaFlex

> Catálogo de e-commerce construido con un framework MVC en PHP puro, hecho
> desde cero (sin Laravel/Symfony/etc.): routing con parámetros, modelos
> sobre PDO con consultas parametrizadas, protección CSRF y un carrito de
> compras en sesión que nunca confía en el precio que manda el cliente.
> Pensado como base de aprendizaje/portafolio para quien quiera ver cómo
> se arma un mini-framework MVC por dentro.

## Características

Verificadas contra el código real de este repositorio:

- **Framework MVC propio**: Router con parámetros de ruta (`/shop/product/{id}`),
  Request/Response, Controller base, Model ligero sobre PDO, motor de vistas
  con layouts (`app/core/*.php`).
- **Catálogo de productos**: listado (`/shop`), detalle (`/shop/product/{id}`)
  y filtrado por categoría (`/shop/category/{id}`), con productos
  relacionados. Actualmente corre sobre datos de ejemplo en memoria
  (`Product::getSampleProducts()`), no requiere base de datos para navegarse.
- **Búsqueda y filtros**: búsqueda por texto (`/api/search`) y ordenamiento
  (nombre A-Z/Z-A, precio ascendente/descendente) sobre el catálogo.
- **Carrito de compras en sesión** (`/cart`, `/cart/add`, `/cart/update`,
  `/cart/remove`, `/api/cart/count`): el precio de cada línea se resuelve
  **siempre** del catálogo del servidor a partir del `product_id`, nunca de
  un campo enviado por el cliente; la cantidad se valida y se limita a un
  rango razonable.
- **Formulario de contacto** (`/contact`) con validación server-side y envío
  a un log (`logs/contact.log`); no envía correo real todavía.
- **Protección CSRF real**: token por sesión, generado en cada página y
  verificado en el servidor antes de cualquier envío de formulario o
  llamada AJAX de estado (soporta tanto formularios clásicos como
  `application/json`).
- **Acceso a datos parametrizado**: todas las consultas SQL del proyecto
  usan sentencias preparadas de PDO; no hay concatenación de entrada de
  usuario en SQL en ningún punto del código.
- **Helper de subida de archivos endurecido** (`upload_file()`): valida
  extensión contra una lista blanca y el tipo MIME real del contenido
  (no el nombre ni el `Content-Type` del cliente). Todavía no está
  conectado a ningún formulario del sitio.

### Limitaciones actuales (para no sobrevender)

- No hay autenticación, cuentas de usuario ni panel de administración
  todavía (el formulario de "cerrar sesión" en el menú y el enlace de
  login son solo interfaz, sin backend detrás).
- No hay checkout ni pasarela de pago real: el carrito calcula totales
  correctamente, pero no existe un flujo de "pagar" ni tablas de pedidos
  conectadas.
- El catálogo vive en datos de ejemplo en memoria; `database/schema.sql`
  define el esquema real (productos, pedidos, pagos, reseñas, etc.) para
  cuando se conecte el catálogo a MySQL de verdad.

## Cómo usar

1. Cloná el repositorio y entrá a la carpeta del proyecto.
2. Copiá `.env.example` a `.env` y ajustá los valores si hace falta
   (el catálogo de ejemplo funciona sin base de datos).
3. Levantá el servidor embebido de PHP apuntando a `public/` como raíz.
4. Navegá `/`, `/shop`, `/shop/product/1`, `/contact`, `/cart`.

## Instalación y uso local

```bash
git clone https://github.com/Luiss2080/TiendaFlex.git
cd TiendaFlex

cp .env.example .env

# Servidor de desarrollo embebido de PHP (necesita PHP 7.4+)
php -S localhost:8000 -t public public/index.php
```

Luego abrí `http://localhost:8000/` en el navegador.

Si vas a ejercitar rutas que sí golpean una base de datos real, creá el
esquema con:

```sql
mysql -u root -p < database/schema.sql
```

y completá las variables `DB_*` en tu `.env`.

## Tecnologías

- **Backend**: PHP puro (framework MVC propio, sin dependencias externas
  en producción), PDO para acceso a datos.
- **Frontend**: Bootstrap 5, jQuery, Slick Carousel, Font Awesome
  (plantilla original de TemplateMo, adaptada a este framework).
- **Base de datos**: MySQL/MariaDB (esquema en `database/schema.sql`).
- **Tooling**: Composer (autoload PSR-4 y metadatos del paquete).

## Tests

Suite de pruebas propia, sin dependencias externas (corre con solo
`php`, no requiere `composer install` ni PHPUnit):

```bash
php tests/run.php
# o, equivalentemente:
composer test
```

Cubre, entre otras cosas: generación/verificación de tokens CSRF, que
los parámetros de ruta llegan al controlador correcto, que el helper de
subida de archivos rechaza contenido que no es una imagen real (aunque
tenga extensión de imagen), y que el carrito calcula el total correcto
ignorando cualquier precio manipulado por el cliente.

Hay además un workflow de GitHub Actions (`.github/workflows/ci.yml`)
que corre `php -l` sobre todo el código y esta suite en cada push/PR,
contra varias versiones de PHP.

## Licencia

No se encontró un archivo `LICENSE` en este repositorio. Hasta que se
agregue uno explícitamente, el código no tiene una licencia abierta
declarada.
