<p align="center">
  <img src="https://storage.macsch15.pl/images/KW7Ii9GYfRMuIkJ6bujxPlGh4nVamy.png">
</p>


## Tricolore [![Build Status](https://travis-ci.org/Macsch15/Tricolore.svg?branch=master)](https://travis-ci.org/Macsch15/Tricolore) [![Coverage Status](https://coveralls.io/repos/github/Macsch15/Tricolore/badge.svg)](https://coveralls.io/github/Macsch15/Tricolore) [![StyleCI](https://github.styleci.io/repos/21590926/shield?branch=master)](https://github.styleci.io/repos/21590926) [![Dependabot Status](https://api.dependabot.com/badges/status?host=github&repo=Macsch15/Tricolore)](https://dependabot.com)

Tricolore is a fat-free environment that provides flexible solutions for create and maintain an online community.

## Screenshots
*Work in progress...*

## Requirements
- PHP >= 7.2.0
- BCMath PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- SSH access on server
- Composer
- Git

For development:
- PHPUnit
- Node & NPM
- SASS

## Installation
*Work in progress...*

**Step 1**

Clone repository to local machine.

Hint: [How to install Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git "Git")

```
$ git clone https://github.com/Macsch15/Tricolore.git
```

**Step 2**

Run composer to install required dependencies.

Hint: [How to install composer](https://getcomposer.org/doc/00-intro.md "Composer")

```
$ composer install --no-dev --optimize-autoloader
```

**Step 3**

Configure Tricolore in **.env** file.

Most important settings to change:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=database_name
DB_USERNAME=database_user
DB_PASSWORD=secret
```

**Step 4**

Run migrations.
```
$ cd /home/tricolore/path
$ php artisan migrate --seed
```

**Step 5**

Run tricolore installer to create admin account.
```
$ php artisan tricolore:account admin
```


## Pretty URLs
**Apache (.htaccess file)**
```
Options +FollowSymLinks -Indexes
RewriteEngine On

RewriteCond %{HTTP:Authorization} .
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```

**Nginx**
```
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## Author
**Maciej Schmidt**
- [Homepage](https://www.macsch15.pl/ "Homepage")
- [Twitter](https://twitter.com/Macsch15 "Twitter")
- [Donate with PayPal](https://www.paypal.me/MaciejSchmidt "Donate with PayPal")

## Bug reports and feedback
If you have found bug, please report it on [issue tracker](https://github.com/Macsch15/Tricolore/issues "issue tracker").

If you discover a security vulnerability, please send an e-mail to *macsch15[at]protonmail.com*.

### MIT Licence

Copyright (c) 2019 Maciej Schmidt

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
