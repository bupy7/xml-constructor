xml-constructor
---------------

The XML of document structure constructor.

### Install

Add the following to `require` section of your `composer.json`:

```
"bupy7/xml-constructor": "dev-master"
```

Then do `composer install`.

### Usage

```php
$xml = new XmlConstructor('root');
$in = [
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
];
echo $xml->fromArray($in)->asXml();
```

### License

**xml-constructor** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.
