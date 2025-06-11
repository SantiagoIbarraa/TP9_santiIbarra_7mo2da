# Plataforma de Películas estilo Netflix

Este repositorio contiene una plataforma web full-stack (PHP + MySQL + HTML/CSS/JS) que permite:

* Registro y login de usuarios con roles (usuario y administrador).
* CRUD de películas para administradores.
* UI responsiva con Bootstrap 5.
* Favoritos offline con IndexedDB (pendiente).
* Reproductor de video HTML5.

## Estructura
```
plataforma-style-platform/
├── frontend/
│   ├── index.html
│   ├── login.html
│   ├── register.html
│   ├── admin/
│   │   └── movies.html (pendiente)
│   ├── css/
│   │   └── custom.css
│   └── js/
│       └── main.js
├── backend/
│   ├── db.php
│   ├── login.php
│   ├── register.php
│   ├── crud_movies.php
│   └── database/
│       └── create_tables.sql
└── README.md
```

## Instalación rápida (XAMPP)
1. Copia la carpeta a `C:/xampp/htdocs/`.
2. Crea una base de datos `movies_db` en phpMyAdmin.
3. Ejecuta el SQL de `backend/database/create_tables.sql`.
4. Ajusta credenciales en `backend/db.php` si es necesario.
5. Inicia Apache y MySQL desde XAMPP.
6. Abre `http://localhost/TP9_2/frontend/index.html` en tu navegador.
