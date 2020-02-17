# Intro
Cx Engine is lightweight, modular and fast headless CMS based on PHP and Vue.

# Roadmap
- [ ] [api] Schema based policies
- [ ] [api] Add roles / policies
- [ ] [api] add JWT tokens support
- [ ] support nature === 'manyToOne'
- [ ] [admin] Plugin management
- [ ] [blocks] Add example blocks to connect with prismic

# Installation
1. Rename `config.examples.php` to `config.php`.
2. Edit the settings to suit your needs.
3. Copy the files to your server.
4. Enter the url of your server in a webbrowser and you are good to go.

# Development
Run `npm start` to spin up a PHP development server. 
This will start a new development server at http://localhost:8666/. ( This readme will use this host in examples. )

# Headless API
The headless API is a light Restfull API that supports all SQL databases (including MySQL, MSSQL, SQLite, MariaDB, PostgreSQL, Sybase, Oracle and more). Get quickly started with sqlite and later in production switch to more preformant databases if needed.

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


# Your first website
To make CxEngine speedy, a webpage should be generated lean. In other words nothing should happen if it is not needed. This makes CxEngine very speedy. A webpage is generated in the following fashion: 
1. match the request to a `page`
2. match the `page` to a `theme` or use the default `theme`
3. set the `regions['main']` with page content
4. include the `theme`
5. render the required regions

regions can consist of 3 types: closure, object (block) or a string.
```php
// Closure
$regions['main'][] = function () {
    echo "hello world";
};

// Block
$regions['main'][] => [
    $blocks['hello']
]

// String
$regions['main'][] = "hello world";
```

# Theming
To create a theme, just create a new folder inside `/themes`. And create an `index.php` file. This file will be used to generate the main layout. Inside this file you will have to define `regions`. The following regions are more or less mandatory for a proper working and good integration with plugins.
```php
    'head' => [],
    'afterbody' => [],
    'content' => [],
    'menu' => [],
    'main' => [],
    'footer' => []-
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

## Your first plugin
A plugin is the way to add extra functionality to CxEngine. A plugin is being included through the `register.php` file in the root. Here you should register your `blocks` and map them to the `regions`.

## Your first block
...


