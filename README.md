Guild Application
========================

Requirements
------------

  * PHP 7.2.9 or higher;
  * PDO-SQLite PHP extension enabled;
  * and the [usual Symfony application requirements][1].

Installation
------------

You can use Git clone:

```bash
$ git clone https://github.com/mvchn/guild.git my_project
$ cd my_project/
$ composer install 
```

Usage
-----

There's no need to configure anything to run the application. If you have
[installed Symfony][2] binary, run this command:

```bash
$ cd my_project/
$ symfony serve
```

Then access the application in your browser at the given URL (<https://localhost:8000> by default).

If you don't have the Symfony binary installed, run `php -S localhost:8000 -t public/`
to use the built-in PHP web server or [configure a web server][3] like Nginx or
Apache to run the application.

Tests
-----

Execute this command to run tests:

```bash
$ cd my_project/
$ ./bin/phpunit
```

[1]: https://symfony.com/doc/current/reference/requirements.html
[2]: https://symfony.com/download
[3]: https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html