# 📰 Acción Bogotá

<div align="center">

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black)

</div>

> Portal de **noticias y voluntariado** con perfiles de usuario, suscripciones y panel administrativo.

---

## 📋 Descripción

Portal web para publicar noticias y convocatorias de voluntariado en Bogotá. Los usuarios se registran, crean su perfil con foto, se suscriben a boletines, se postulan como voluntarios y exploran las noticias publicadas por el equipo editorial.

## ✨ Características

- 📰 Publicación y consulta de noticias
- 👤 Registro, login y perfiles con avatar
- 🙋 Postulación a voluntariados (`voluntario.php`)
- 📩 Suscripción de usuarios y empresas (`subscribe.php`, `subscribe_company.php`)
- 🔍 Búsqueda de contenido (`search.php`)
- 🛠️ Panel de administración
- 🗄️ Base de datos incluida (`base de datos/bogota.sql`)

## 🛠️ Stack

`PHP` · `MySQL` · `JavaScript` · `HTML/CSS`

## 🚀 Instalación

### Requisitos

- [XAMPP](https://www.apachefriends.org/) (PHP 8+ y MySQL)

### Pasos

1. **Clonar** en `htdocs`:

```bash
cd C:/xampp/htdocs
git clone https://github.com/Ricy24/Accion-Bogota.git
```

2. **Importar la base de datos**: crea la BD en `http://localhost/phpmyadmin` e importa [`base de datos/bogota.sql`](base%20de%20datos/bogota.sql).

3. **Configurar la conexión** en [`db.php`](db.php).

4. **Ejecutar**: abre `http://localhost/Accion-Bogota/`

## 📁 Estructura

```text
Accion-Bogota/
├── index.php                 # Portada de noticias
├── detalle.php               # Noticia completa
├── register.php / login.php  # Autenticación
├── voluntario.php            # Postulación a voluntariado
├── subscribe*.php            # Suscripciones
├── search.php                # Búsqueda
├── db.php                    # Conexión a MySQL
├── base de datos/bogota.sql  # Esquema y datos
├── admin/                    # Panel administrativo
├── uploads/                  # Imágenes subidas
└── vendor/                   # Dependencias
```

## 📄 Licencia

Proyecto desarrollado con **fines académicos**.
