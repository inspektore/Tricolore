#Tricolore [![Build Status](https://travis-ci.org/Macsch15/Tricolore.svg)](https://travis-ci.org/Macsch15/Tricolore) [![Coverage Status](https://coveralls.io/repos/Macsch15/Tricolore/badge.svg?branch=master)](https://coveralls.io/r/Macsch15/Tricolore?branch=master)

#####Discussion software
*Work in progress...*

###Environment requirements
- PHP 5.5 (5.6 recommended)
- PostgreSQL 9.3 (or later)
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

###Using Grunt to manage LESS
```
$ git clone https://github.com/Macsch15/Tricolore.git
$ cd Tricolore
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
