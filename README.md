xml-constructor
---------------

[![Latest Stable Version](https://poser.pugx.org/bupy7/xml-constructor/v/stable)](https://packagist.org/packages/bupy7/xml-constructor)
[![Total Downloads](https://poser.pugx.org/bupy7/xml-constructor/downloads)](https://packagist.org/packages/bupy7/xml-constructor)
[![Latest Unstable Version](https://poser.pugx.org/bupy7/xml-constructor/v/unstable)](https://packagist.org/packages/bupy7/xml-constructor)
[![License](https://poser.pugx.org/bupy7/xml-constructor/license)](https://packagist.org/packages/bupy7/xml-constructor)
[![Build Status](https://travis-ci.org/bupy7/xml-constructor.svg?branch=master)](https://travis-ci.org/bupy7/xml-constructor)
[![Coverage Status](https://coveralls.io/repos/bupy7/xml-constructor/badge.svg?branch=master&service=github)](https://coveralls.io/github/bupy7/xml-constructor?branch=master)

The XML of document structure constructor.

### Install

Add the following to `require` section of your `composer.json`:

```
"bupy7/xml-constructor": "*"
```

Then do `composer install`.

### Usage

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
        ],
    ],
];
echo $xml->fromArray($in)->toOutput();
```

if you send data to browser raw, use header for content type:

```php
header('Content-Type: application/xml; charset=utf-8');
```

### License

**xml-constructor** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.
