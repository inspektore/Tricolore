## Tricolore [![Build Status](https://travis-ci.org/Macsch15/Tricolore.svg?branch=master)](https://travis-ci.org/Macsch15/Tricolore) [![StyleCI](https://github.styleci.io/repos/21590926/shield?branch=master)](https://github.styleci.io/repos/21590926) [![Requirements Status](https://requires.io/github/Macsch15/Tricolore/requirements.svg?branch=master)](https://requires.io/github/Macsch15/Tricolore/requirements/?branch=master) ![GitHub issues](https://img.shields.io/github/issues/Macsch15/Tricolore) ![GitHub release (latest by date)](https://img.shields.io/github/v/release/Macsch15/Tricolore)

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
