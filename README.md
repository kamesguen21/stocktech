Stockteck
========================

"Stockteck" is a Symfony project created to visualise stock data, you can create stocks add descriptions and tickers and view charts on the frontend

Requirements
------------

  * PHP 7.3 or higher;
  * and the [usual Symfony application requirements][2].

Installation
------------

[Download Symfony][4] to install the `symfony` binary on your computer and run
these commands:

```bash
$ git clone 
$ compose install
$ yarn install
```
Usage
-----

There's no need to configure anything to run the application. If you have
[installed Symfony][4] binary, run this command:

```bash
$ cd stockteck/
$ php bin/console doctrine:migrations:migrate
$ symfony server:start
```

Then access the application in your browser at the given URL (<https://localhost:8000> by default).

If you don't have the Symfony binary installed, run `php -S localhost:8000 -t public/`
to use the built-in PHP web server or [configure a web server][3] like Nginx or
Apache to run the application.

Guide
-----
to install stock historical data run this command

 ```bash
  php bin/console import-data
 ```
when creating an account you need to configure mailer with your email to receive verification link.

change email to your email

 ```bash
  ->from(new Address('email', 'stocktech'))
 ```
in
 ```bash
src/Controller/RegistrationController.php
 ``` 
change mail & password to your gmail and password
```bash
 MAILER_DSN=gmail+smtp://email:password@default
 ```
in
 ```bash
.env
 ```
Or use default user 'admin@admin.com' 'nimda19'

[1]: https://symfony.com/doc/current/best_practices.html
[2]: https://symfony.com/doc/current/reference/requirements.html
[3]: https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
[4]: https://symfony.com/download
