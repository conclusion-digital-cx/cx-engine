
# Quick start
In this quick start guide we'll create our first CxEngine REST API server and a simple website to use it from scratch. It will show how easy it is to get started with CxEngine even without a generator or boilerplate.
 
Let's create a new folder for our application:
```sh
mkdir cxengine-app
cd cxengine-app
```

## Install from boilerplate
1. `git clone https://github.com/jellehak/cx-engine.git`
1. Rename `config.examples.php` to `config.php`.
2. Edit the settings to suit your needs.
3. Copy the files to your server.
4. Enter the url of your server in a webbrowser and you are good to go.

## Install as package
Since CxEngine is a Node package, you can use the `npm` tool to install and update the engine.

- create a default package.json
```sh
npm init -y
npm i https://github.com/jellehak/cx-engine.git
```

<!-- > Tip: If you want to share an installation locally use `npm link cx-engine` -->

<!-- Run installer
```sh
php node_modules/cx-engine/install.php
``` -->

- Add the following to `package.json`:
```json
 "scripts": {
    "start": "cd public_html && php -S localhost:8666"
  },
```

- Create a new folder called `public_html`

- Add here a file called `index.php` with:
```php
<?php
include "../node_modules/cx-engine/bootstrap.php";
```
- Start the PHP server with:
```sh
npm start
```

- Add the admin interface assets:
```sh
cp -R node_modules/cx-engine/admin ./public_html/admin
```

- Visit the admin interface:
http://localhost:8668/admin

- Create a config:
```
cp node_modules/cx-engine/config.example.php ./public_html/config.php
```
> **Note**: to support installations in sub directories the config file is placed alongside `index.php`

- Scaffold sqlite database:
```
cp node_modules/cx-engine/example.db ./public_html/db.db
```

- Add basic types:
```

```

## Create your first page

