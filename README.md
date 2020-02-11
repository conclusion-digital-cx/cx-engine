A online block editor + light headless PHP backend.

# What is it
Cx Engine is a light online page editor and comes with a basic PHP based restfull API.

# Headless API
It comes out of the box with a light PHP based restfull API driven by a SQLite database. This let's you quickly scaffold a project and suits most light production websites. For high traffic you can also pick any other headless restfull api or Saas vendors like prismic or contentfull.

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

# Installation
1. Rename `storage_example` to `storage`
2. Copy the files to your server
3. Go to your website

# Development
Run `npm start` to spin up a PHP development server. 
This will start a new development server at http://localhost:8666/. 
In this readme this will be used as host, change this according to the server you are running on.

# Website
http://localhost:8666
http://localhost:8666/admin
http://localhost:8666/control ( WIP )

# Restfull Api
The restfull api is build around the altorouter and a tiny sqlite ORM library.
https://altorouter.com/
http://localhost:8666/api

# Blocks
There are two ways of creating blocks. Server side (PHP) or client side (Vue / Js).

Client side blocks can be found in `/blocksjs`. You can create your own blocks inside this folder. 

## Your first block
...

