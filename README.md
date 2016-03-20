## Synopsis
"single-page" aplikacija za upravljanje s seznamom opravil - TODO list

## Details
- dodajanje / urejanje / brisanje opravil
- seznam opravil z ostranjevanje (ang. pagination)
- zaključevanje opravil
- posamezno opravilo vsebuje podatke: naziv / datum zaključevanja / oseba / opis
- opravila se shranjujejo v MySQL bazo s pomočjo RESTful API-ja
- RESTful API je razvit v CakePHP ogrodju
- validacija vnosnih podatkov v klientu in na strežniku
- "front-end" uporablja JS ogrodje: Angular.js + Bootstrap

## Installation
Requirements:
- Git
- VirtualBox
- Vagrant
- Composer

After that, it only takes one command to set everything up:
```sh
vagrant up && composer install
```

Install database schema (ssh to Vagrant VM "vagrant ssh"):
```sh
cd /var/www/html
Console/cake schema create [y] [y]
```

Point your browser to:
```sh
http://127.0.0.1/webroot/app
```

This is downloaded and installed automatically:

- Apache strežnik (installed and configured)
- MySQL podatkovna baza (installed and configured)
- CakePHP 2.x ogrodje
- Database schema

## Initial project setup (only relevant when creating a project)

1. Create composer.json file
```json
  {
    "name": "todo-manager",
    "description": "single-page aplikacija za upravljanje s seznamom opravil - TODO list",
    "require": {
      "cakephp/cakephp": "2.8.*",
      "cakephp/debug_kit": "2.2.*"
    },
    "config": {
      "vendor-dir": "Vendor/"
    }
  }
```

2. Install composer packages
```sh
composer install
```

3. Up vagrant VM
```sh
vagrant up
```
get tea . . .

4. Bake a cake in root of your project folder

  ssh to Vagrant VM "vagrant ssh" and bake a cake:
```sh
cd /var/www/html
Vendor/bin/cake bake project /var/www/html [y]
```

5. Reenable composer autoload by appending this to "Config/bootstrap.php"
```php
// Load Composer autoload
require APP . 'Vendor/autoload.php';
// Remove and re-prepend CakePHPs autoloader as Composer thinks it is the most important
spl_autoload_unregister(array('App', 'load'));
spl_autoload_register(array('App', 'load'), true, true);
```

6. Enable debug-kit by uncommenting / adding this to your "Config/bootstrap.php"
```php
CakePlugin::load('DebugKit');
```

7. In "Controller/AppController.php" file (within the class), add:
```
public $components = array(
    'DebugKit.Toolbar'
);
```

8. Add database configuration. Make a copy of "Config/database.php.default", and rename it to "database.php". Add your database credentials to "$default" array. You can delete "$test" array, since we will not be using it.
```php
  public $default = array(
    'datasource' => 'Database/Mysql',
    'persistent' => false,
    'host' => '127.0.0.1',
    'login' => 'todomanager',
    'password' => '5todo6manager',
    'database' => 'todo_manager',
    'prefix' => '',
    'encoding' => 'utf8'
  );
```

9. Add jQuery, AngularJS and Bootstrap for your frontend
  Create "app" directory in your "webroot" folder and copy jQuery, AngularJS and Bootstrap to "lib" subfolder. Should look something like this:
```sh
  /webroot
    /app
      /lib
        /bootstrap
        /angular
          angular.min.js
          angular-route.min.js
        jquery.min.js
```

10. Create "index.html" in your "webroot/app" folder, to hold a master template, and include CSS stylesheets and javascript:
```html
  <!doctype html>
  <html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width = device-width, initial-scale = 1">
      <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
      <script src="lib/jquery.min.js"></script>
      <script src="lib/bootstrap/js/bootstrap.min.js"></script>
      <script src="lib/angular/angular.min.js"></script>
      <script src="lib/angular/angular-route.min.js"></script>
    </head>
    <body>
      <!--TODO -->
    </body>
  </html>
```
  Now you have an empty project, ready to be developed

11. Initial SQL script:
```sql
  CREATE TABLE IF NOT EXISTS `ticket` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL COMMENT 'Assigned user ID',
    `title` varchar(128) CHARACTER SET utf8 NOT NULL COMMENT 'Ticket title',
    `description` text CHARACTER SET utf8 NOT NULL COMMENT 'Ticket description',
    `priority` int(11) NOT NULL COMMENT 'Ticket priority (1(highest) - 5(lowest))',
    `created` datetime NOT NULL COMMENT 'Ticket created time',
    `modified` datetime DEFAULT NULL COMMENT 'Ticket modified time',
    `closed` datetime DEFAULT NULL COMMENT 'Ticket closed time',
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

  INSERT INTO `ticket` (`id`, `user_id`, `title`, `description`, `priority`, `created`, `modified`, `closed`) VALUES
  (1, 2, 'Todo 1', 'Lorem ipsum dolor sit amet', 2, '2016-03-19 00:00:00', NULL, NULL),
  (2, 2, 'Todo 2', 'Lorem ipsum dolor sit amet', 3, '2016-03-20 00:00:00', NULL, NULL),
  (3, 1, 'Admin TODO 1', 'Lorem ipsum dolor sit amet', 1, '2016-03-19 00:00:00', NULL, NULL),
  (4, 1, 'Admin TODO 2', 'Lorem ipsum dolor sit amet', 5, '2016-03-19 00:00:00', '2016-03-20 00:00:00', NULL);

  CREATE TABLE IF NOT EXISTS `user` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(128) CHARACTER SET utf8 NOT NULL,
    `created` datetime DEFAULT NULL COMMENT 'User created time',
    `modified` datetime DEFAULT NULL COMMENT 'User modified time',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

  INSERT INTO `user` (`id`, `username`, `created`, `modified`) VALUES
  (1, 'Jack', '2016-03-19 00:00:00', NULL),
  (2, 'John', '2016-03-19 00:00:00', NULL);
```

## License
MIT

## Author
Andrej Zadnikar
