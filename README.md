#Tricolore [![Build Status](https://travis-ci.org/Macsch15/Tricolore.svg)](https://travis-ci.org/Macsch15/Tricolore) [![Coverage Status](https://coveralls.io/repos/Macsch15/Tricolore/badge.svg?branch=master)](https://coveralls.io/r/Macsch15/Tricolore?branch=master) [![Dependency Status](https://www.versioneye.com/user/projects/551af4933661f134fe0001e8/badge.svg?style=flat)](https://www.versioneye.com/user/projects/551af4933661f134fe0001e8)

#####A robust discussion software
*Work in progress...*

###Environment requirements
- PHP 5.5 (5.6 recommended)
- PostgreSQL 9.1 (or later)
- INTL extension
- pdo_pgsql extension **(currently unsupported by HHVM)**
- zlib (optional)
- xdebug (optional)

###Downloading and installing dependencies
```
$ git clone https://github.com/Macsch15/Tricolore.git
$ cd Tricolore
$ composer install
```

###Example Nginx configuration
```
server {
    # Nginx configuration

    location ~ /tricolore_directory/(app|storage|src) {
        deny all;
        return 405;
    }

    location /tricolore_directory {
        if ( $uri !~ /(static)/ ) {
            rewrite /tricolore_directory/([A-Za-z0-9/]+) /tricolore_directory/index.php?/$1;
        }
    }

    # Nginx configuration
}
```

###Unit testing

Create database for tests
```
$ createdb tricolore_tests
```

Then run tests
```
$ phpunit
```

###Using Grunt to manage LESS

Run in the root directory:
```
$ npm install
```

Build CSS from LESS:
```
$ grunt less
```

Watch:
```
$ grunt watch
```

###MIT Licence

Copyright (c) 2015 Maciej Schmidt

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
