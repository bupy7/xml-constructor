<?php

namespace bupy7\xml\constructor\tests;

use bupy7\xml\constructor\RuntimeException;
use bupy7\xml\constructor\XmlConstructor;

use function preg_replace;

/**
 * @since 1.2.1
 */
class XmlConstructTest extends TestCase
{
    private $in1 = [
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
    private $in2 = [
        [
            'tag' => 'root',
            'elements' => [
                [
                    'tag' => 'tag1',
                    // 'content' => 'content1', // no content
                ],
                [
                    'tag' => 'tag2',
                    'content' => 'content2',
                ],
                [
                    'tag' => 'tag3',
                    // 'content' => 'content3', // no content
                ],
            ],
        ],
    ];
    /**
     * @var array
     */
    private $in3 = [
        [
            'tag' => 'root',
            'elements' => [
                [
                    'tag' => 'tag1',
                    'content' => '<b>content1</b>',
                    'cdata' => true,
                ],
                [
                    'tag' => 'tag2',
                    'content' => 'content2',
                ],
            ],
        ],
    ];

    public function testDefaultDocument1()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
    <tag1 attr1="val1" attr2="val2"/>
    <tag2>content2</tag2>
    <tag3>
        <tag4>content4</tag4>
    </tag3>
</root>

XML;
        $xml = new XmlConstructor();
        $out = $xml->fromArray($this->in1)->toOutput();
        $this->assertEquals($expected, $out);
    }

    public function testDefaultDocument2()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
    <tag1/>
    <tag2>content2</tag2>
    <tag3/>
</root>

XML;
        $xml = new XmlConstructor();
        $out = $xml->fromArray($this->in2)->toOutput();
        $this->assertEquals($expected, $out);
    }

    /**
     * @since 2.0.1
     */
    public function testWithoutStartDocument1()
    {
        $expected = <<<XML
<root>
    <tag1 attr1="val1" attr2="val2"/>
    <tag2>content2</tag2>
    <tag3>
        <tag4>content4</tag4>
    </tag3>
</root>

XML;
        $xml = new XmlConstructor(['startDocument' => false]);
        $out = $xml->fromArray($this->in1)->toOutput();
        $this->assertEquals($expected, $out);
    }

    /**
     * @since 2.0.1
     */
    public function testWithoutStartDocument2()
    {
        $expected = <<<XML
<root>
    <tag1 attr1="val1" attr2="val2"/>
    <tag2>content2</tag2>
    <tag3>
        <tag4>content4</tag4>
    </tag3>
</root>

XML;
        $xml = new XmlConstructor(['startDocument' => null]);
        $out = $xml->fromArray($this->in1)->toOutput();
        $this->assertEquals($expected, $out);
    }

    public function testWithStartDocument()
    {
        $expected = <<<XML
<?xml version="1.1" encoding="ASCII"?>
<root>
    <tag1 attr1="val1" attr2="val2"/>
    <tag2>content2</tag2>
    <tag3>
        <tag4>content4</tag4>
    </tag3>
</root>

XML;
        $xml = new XmlConstructor(['startDocument' => ['1.1', 'ASCII']]);
        $out = $xml->fromArray($this->in1)->toOutput();
        $this->assertEquals($expected, $out);
    }

    public function testCustomIndentString1()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root><tag1 attr1="val1" attr2="val2"/><tag2>content2</tag2><tag3><tag4>content4</tag4></tag3></root>

XML;
        $xml = new XmlConstructor(['indentString' => false]);
        $out = $xml->fromArray($this->in1)->toOutput();
        $this->assertEquals($expected, $out);
    }

    public function testCustomIndentString2()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
-<tag1 attr1="val1" attr2="val2"/>
-<tag2>content2</tag2>
-<tag3>
--<tag4>content4</tag4>
-</tag3>
</root>

XML;
        $xml = new XmlConstructor(['indentString' => '-']);
        $out = $xml->fromArray($this->in1)->toOutput();
        $this->assertEquals($expected, $out);
    }

    /**
     * @since 2.0.1
     */
    public function testCustomIndentString3()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root><tag1 attr1="val1" attr2="val2"/><tag2>content2</tag2><tag3><tag4>content4</tag4></tag3></root>

XML;
        $xml = new XmlConstructor(['indentString' => null]);
        $out = $xml->fromArray($this->in1)->toOutput();
        $this->assertEquals($expected, $out);
    }

    /**
     * @since 2.0.1
     */
    public function testCustomIndentString4()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
<tag1 attr1="val1" attr2="val2"/>
<tag2>content2</tag2>
<tag3>
<tag4>content4</tag4>
</tag3>
</root>

XML;
        $xml = new XmlConstructor(['indentString' => '']);
        $out = $xml->fromArray($this->in1)->toOutput();
        $this->assertEquals($expected, $out);
    }

    /**
     * @since 1.3.0
     */
    public function testCdataContent()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
    <tag1><![CDATA[<b>content1</b>]]></tag1>
    <tag2>content2</tag2>
</root>
XML;
        $xml = new XmlConstructor();
        $out = $xml->fromArray($this->in3)->toOutput();
        $this->assertEquals($this->prepare($expected), $this->prepare($out));
    }

    /**
     * @since 2.0.0
     */
    public function testInvalidConfiguration()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
XML;
        $xml = new XmlConstructor();
        $out = $xml->fromArray(['incorrect' => 'array'])->toOutput();
        $this->assertEquals($this->prepare($expected), $this->prepare($out));
    }

    /**
     * @since 2.0.0
     */
    public function testDoubleToOutput()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
    <tag1 attr1="val1" attr2="val2"/>
    <tag2>content2</tag2>
    <tag3>
        <tag4>content4</tag4>
    </tag3>
</root>
XML;
        $xml = new XmlConstructor();

        $out = $xml->fromArray($this->in1)->toOutput();
        $this->assertEquals($this->prepare($expected), $this->prepare($out));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The constructor is closed. You have to create new one to flush its again.');
        $xml->toOutput();
    }

    /**
     * @since 2.0.0
     */
    public function testDoubleFromArray()
    {
        $expected = <<<XML
<?xml version="1.1" encoding="ASCII"?>
<root>
 <tag1 attr1="val1" attr2="val2"/>
 <tag2>content2</tag2>
 <tag3>
  <tag4>content4</tag4>
 </tag3>
</root>

XML;
        $xml = new XmlConstructor([
            'startDocument' => ['1.1', 'ASCII'],
            'indentString' => ' ',
        ]);

        $out = $xml->fromArray($this->in1)->toOutput();
        $this->assertEquals($expected, $out);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The constructor is closed. You have to create new one to create '
            . 'an XML document again.');
        $xml->fromArray($this->in1);
    }

    /**
     * @param string $xml
     * @return string
     */
    private function prepare($xml)
    {
        return preg_replace('/\s/', '', $xml);
    }
}
