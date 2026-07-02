<<<<<<< HEAD
# Sistema Comercializadora — PHP MVC + PDO + Bootstrap 5

Sistema de gestión comercial: inventario con imágenes y alertas de stock,
ventas (POS), clientes, reportes y auditoría de usuarios.

## 🚀 Instalación en XAMPP

1. Copia la carpeta `comercializadora` completa dentro de `C:\xampp\htdocs\`
   (o `/Applications/XAMPP/htdocs/` en Mac).
2. Abre **phpMyAdmin** (`http://localhost/phpmyadmin`).
3. Crea una base de datos llamada `comercializadora` **o simplemente importa
   el archivo** `database/comercializadora.sql` (él ya crea la base de datos).
4. Ve a la pestaña **Importar** → selecciona `database/comercializadora.sql` → Continuar.
5. Verifica los datos de conexión en `app/config/Database.php` (por defecto
   usuario `root` sin contraseña, tal cual XAMPP trae de fábrica).
6. Activa el módulo `mod_rewrite` de Apache (viene activo por defecto en XAMPP).
7. Abre tu navegador en:

   ```
   http://localhost/comercializadora/
   ```

8. Inicia sesión con:

   | Rol       | Correo                          | Contraseña |
   |-----------|----------------------------------|------------|
   | Admin     | admin@comercializadora.com       | 123456     |
   | Vendedor  | vendedor@comercializadora.com    | 123456     |

## 📁 Estructura del proyecto

```
comercializadora/
├── app/
│   ├── config/Database.php        Conexión PDO (singleton)
│   ├── core/                      Router, Controller y Model base
│   ├── controllers/               Login, Dashboard, Clientes, Productos,
│   │                               Ventas, Reportes, Auditoría
│   ├── models/                    Usuario, Cliente, Producto, Categoria,
│   │                               Venta, Auditoria
│   └── views/                     Vistas por módulo + layouts (header/sidebar/footer)
├── public/
│   ├── css/style.css              Tema oscuro / futurista
│   ├── js/main.js
│   └── img/products/              Imágenes subidas de productos
├── database/comercializadora.sql  Script completo con datos de ejemplo
├── index.php                      Front controller
└── .htaccess                      Reescritura de URLs
```

## ✨ Módulos incluidos

- **Login con sesiones** y roles (admin / vendedor).
- **Dashboard** con ventas del día/mes, gráfico de 7 días, alertas de stock
  y productos más vendidos.
- **Clientes** — CRUD completo con búsqueda.
- **Productos** — CRUD con imagen, precio de compra/venta, stock y
  **stock mínimo configurable** (genera alerta automática cuando el stock
  llega o baja de ese umbral). Incluye botón para registrar entradas de
  stock (reposición) y un historial de movimientos.
- **Ventas (POS)** — selección visual de productos con imagen y precio,
  carrito interactivo, validación de stock en tiempo real, descuento,
  generación de factura y descuento automático de inventario (transacción PDO).
- **Reportes** — ventas por rango de fechas, ventas por vendedor, productos
  más vendidos, valor total de inventario y productos con stock bajo.
- **Auditoría** — bitácora de todas las acciones relevantes: quién inició
  sesión, quién registró una venta, quién creó/editó un producto o cliente,
  y quién aumentó el stock de qué producto y por qué motivo. Filtrable por
  módulo, usuario y rango de fechas.

## 🔒 Seguridad implementada

- Contraseñas con `password_hash()` / `password_verify()` (bcrypt).
- Consultas 100% con **PDO y sentencias preparadas** (previene inyección SQL).
- Validación de sesión en cada controlador (`requireAuth()`).
- Borrado lógico de clientes/productos (no se pierde el historial de ventas).
- Transacciones PDO en el registro de ventas (o se guarda todo o no se
  guarda nada, evitando inconsistencias de stock).

## 🎨 Diseño

Tema oscuro "futurista" con gradientes indigo → cian, tarjetas con sombra
suave, tipografía Inter, iconos Bootstrap Icons y gráficos con Chart.js.
Totalmente responsive (el sidebar se colapsa en móvil).

## 🖼️ Imágenes de productos

Al crear o editar un producto puedes subir una imagen (jpg, png, webp).
Si no subes ninguna, se usa automáticamente un ícono de reemplazo
(`public/img/products/no-image.svg`).

## 📝 Notas para tu defensa

- Los precios de ejemplo (`database/comercializadora.sql`) ya son realistas
  en bolivianos y puedes editarlos libremente desde el módulo de Productos.
- El módulo de Auditoría es ideal para mostrar trazabilidad ante el tribunal:
  cada venta, cada ajuste de stock y cada acceso queda registrado con
  usuario, fecha e IP.
- Cuando el sistema esté funcionando, retomamos el informe (ISO 25010,
  IEEE 730, casos de prueba caja blanca/negra, capturas e informe APA 7)
  usando este sistema real como base.
=======

## Arquitectura

El sistema fue desarrollado utilizando el patrón Modelo Vista Controlador (MVC).

## Tecnologías

PHP

MySQL

Bootstrap

JavaScript

Git

## Control de Versiones

Repositorio administrado mediante Git y GitHub utilizando Git Flow.

# comercializadora-santa-cruz
Sistema de Gestión Comercializadora Santa Cruz S.R.L.
>>>>>>> f793ba95f4d96d8b0b87fd8a00f5d32b4470384f
