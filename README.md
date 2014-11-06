#Tricolore [![Build Status](http://img.shields.io/travis/Macsch15/Tricolore.svg?style=flat)](https://travis-ci.org/Macsch15/Tricolore) [![Unicorn](http://img.shields.io/badge/unicorn-on-ff69b4.svg?style=flat)](https://github.com/Macsch15/Tricolore) [![Licence](http://img.shields.io/badge/licence-gnu-red.svg?style=flat)](https://github.com/Macsch15/Tricolore/blob/master/LICENSE.md)

#####Open source bug tracker application
*Work in progress...*

###Environment requirements
- PHP 5.5 (5.6 recommended)
- PostgreSQL 9.3 (or later)

On HHVM 3.3 and later put in your **php.ini**:
```
hhvm.libxml.ext_entity_whitelist = file,http,https
```
And restart HHVM
```
$ sudo service hhvm restart
```

###3-rd party libraries
- [Symfony](https://github.com/symfony/symfony)
- [Twig](https://github.com/twigphp/Twig)
- [jQuery](https://github.com/jquery/jquery)
- [Carbon](https://github.com/briannesbitt/Carbon)
- [Bootstrap](https://github.com/twbs/bootstrap)
- [highlight.js](https://github.com/isagalaev/highlight.js)

###Licence

Copyright (c) 2014 Maciej Schmidt

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
