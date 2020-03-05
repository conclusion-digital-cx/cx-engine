
# Philosophy
To make a website speedy, a webpage should be generated lean. In other words nothing should happen if it is not needed. This makes CxEngine very speedy.

# Your first website
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

# Out of the box endpoints
- http://localhost:8666
- http://localhost:8666/admin

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
