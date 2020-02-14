# Intro
Cx Engine is an online page editor combined with a headless restfull API written in PHP.

# Roadmap
- [ ] [api] Add roles and protect with JWT tokens
- [ ] [admin] Plugin management
- [ ] [blocks] Add example blocks to connect with prismic

# Installation
1. Rename `config.examples.php` to `config.php`.
2. Edit the settings to suit your needs.
3. Copy the files to your server.
4. Enter the url of your server in a webbrowser and you are good to go.

# Development
Run `npm start` to spin up a PHP development server. 
This will start a new development server at http://localhost:8666/. This readme will use futher use this host to show examples.

# Headless API
It comes out of the box with a light Restfull API that supports all SQL databases (including MySQL, MSSQL, SQLite, MariaDB, PostgreSQL, Sybase, Oracle and more). This let's you quickly scaffold a project.

# API Endpoints
- POST /upload

- GET /:entity
- POST /:entity
- GET /:entity/:id
- PUT /:entity/:id
- DELETE /:entity/:id

- GET http://localhost:8666/api/blocks/[a:id]/render
- GET http://localhost:8666/api/layouts
- GET http://localhost:8666/api/blocks
- GET http://localhost:8666/api/blocksjs

# Theming
To create a theme, just create a new folder inside `/themes`. And create an `index.php` file. This file will be used to generate the main layout. Inside this file you will have to define `zones`. The following zones are mandatory for a proper working and good integration with plugins.
```php
    'head' => [],
    'afterbody' => [],
    'content' => [],
    'menu' => [],
    'main' => [],
    'footer' => []
```

# Website
http://localhost:8666
http://localhost:8666/admin

# Restfull Api
The restfull api is build around the altorouter and a tiny sqlite ORM library.
https://altorouter.com/
http://localhost:8666/api

# Blocks
There are two ways of creating blocks. Server side (PHP) or client side (Vue / Js).

Client side blocks can be found in `/blocksjs`. You can create your own blocks inside this folder. 

## Your first block
...


## Your 




