<?php

namespace bupy7\xml\constructor\tests;

use bupy7\xml\constructor\XmlConstructor;

/**
 * @author Belosludcev Vasilij <https://github.com/bupy7>
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
     * @since 1.3.0
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
        $out1 = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
    <tag1 attr1="val1" attr2="val2"/>
    <tag2>content2</tag2>
    <tag3>
        <tag4>content4</tag4>
    </tag3>
</root>
XML;
        $xml = new XmlConstructor;
        $out2 = $xml->fromArray($this->in1)->toOutput();
        $this->assertEquals($this->prepare($out1), $this->prepare($out2));
    }

    public function testDefaultDocument2()
    {
        $out1 = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
    <tag1/>
    <tag2>content2</tag2>
    <tag3/>
</root>
XML;
        $xml = new XmlConstructor;
        $out2 = $xml->fromArray($this->in2)->toOutput();
        $this->assertEquals($this->prepare($out1), $this->prepare($out2));
    }

    public function testWithoutStartDocument()
    {
        $out1 = <<<XML
<root>
    <tag1 attr1="val1" attr2="val2"/>
    <tag2>content2</tag2>
    <tag3>
        <tag4>content4</tag4>
    </tag3>
</root>
XML;
        $xml = new XmlConstructor(['startDocument' => false]);
        $out2 = $xml->fromArray($this->in1)->toOutput();
        $this->assertEquals($this->prepare($out1), $this->prepare($out2));
    }
    
    public function testCustomIndentString()
    {
        $out1 = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
    <tag1 attr1="val1" attr2="val2"/>
    <tag2>content2</tag2>
    <tag3>
        <tag4>content4</tag4>
    </tag3>
</root>
XML;
        $xml = new XmlConstructor(['indentString' => false]);
        $out2 = $xml->fromArray($this->in1)->toOutput();
        $this->assertEquals($this->prepare($out1), $this->prepare($out2));
    }

    /**
     * @since 1.3.0
     */
    public function testCdataContent()
    {
        $out1 = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
    <tag1><![CDATA[<b>content1</b>]]></tag1>
    <tag2>content2</tag2>
</root>
XML;
        $xml = new XmlConstructor(['indentString' => false]);
        $out2 = $xml->fromArray($this->in3)->toOutput();
        $this->assertEquals($this->prepare($out1), $this->prepare($out2));
    }
    
    public function testError()
    {
        $out1 = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
XML;
        $xml = new XmlConstructor;
        $out2 = $xml->fromArray(['incorrect' => 'array'])->toOutput();
        $this->assertEquals($this->prepare($out1), $this->prepare($out2));
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
