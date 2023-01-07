xml-constructor
===

[![Stable Version](https://poser.pugx.org/bupy7/xml-constructor/v/stable)](https://packagist.org/packages/bupy7/xml-constructor)
[![Build status](https://github.com/bupy7/xml-constructor/actions/workflows/build.yml/badge.svg)](https://github.com/bupy7/xml-constructor/actions/workflows/build.yml)
[![Coverage Status](https://coveralls.io/repos/bupy7/xml-constructor/badge.svg?branch=master&service=github)](https://coveralls.io/github/bupy7/xml-constructor?branch=master)
[![Total Downloads](https://poser.pugx.org/bupy7/xml-constructor/downloads)](https://packagist.org/packages/bupy7/xml-constructor)
[![License](https://poser.pugx.org/bupy7/xml-constructor/license)](https://packagist.org/packages/bupy7/xml-constructor)

The array-like constructor of XML document structure.

Supporting PHP from 5.6 up to 8.2.

Install
---

Add the following to `require` section of your `composer.json`:

```
"bupy7/xml-constructor": "*"
```

Then do `composer install`;

or execute the command:

```
$ composer require bupy7/xml-constructor
```

Usage
---

**Input:**

```php
$xml = new XmlConstructor();
$in = [
    [
        'tag' => 'root',
        'elements' => [
            [
                'tag' => 'tag1',
                'attributes' => [
                    'attr1' => 'val1',
                    'attr2' => 'val2',
                ],
            ],
            [
                'tag' => 'tag2',
                'content' => 'content2',
            ],
            [
                'tag' => 'tag3',
                'elements' => [
                    [
                        'tag' => 'tag4',
                        'content' => 'content4',
                    ],
                ],
            ],
            [
                'tag' => 'tag4',
                'content' => '<b>content4</b>',
                'cdata' => true, // by default - false, see https://en.wikipedia.org/wiki/CDATA
            ],
        ],
    ],
];
echo $xml->fromArray($in)->toOutput();
```

**Output:**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<root>
    <tag1 attr1="val1" attr2="val2"/>
    <tag2>content2</tag2>
    <tag3>
        <tag4>content4</tag4>
    </tag3>
    <tag4><![CDATA[<b>content4</b>]]></tag4>
</root>
```

Testing
---

Run tests:

```
$ ./vendor/bin/phpunit --no-coverage
```

Run tests with coverage:

```
$ XDEBUG_MODE=coverage ./vendor/bin/phpunit
```

HTML coverage path: `build/coverage/index.html`

Code style
---

To fix code style, run:

```
~/.composer/vendor/bin/php-cs-fixer fix --config=./phpcs.php --verbose
```

You have to install [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) at first, if you
don't use build-in Docker image:

```
composer global require friendsofphp/php-cs-fixer "^3.13.0"
```

License
---

**xml-constructor** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.
