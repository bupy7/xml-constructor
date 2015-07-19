<?php

namespace bupy7\xml\constructor;

use XMLWriter;

/**
 * Creating XML document from array.
 * 
 * Example:
 * 
 * ~~~
 * $xml = new XmlConstructor('root');
 * $in = [
 *     [
 *         'tag' => 'tag1',
 *         'attributes' => [
 *             'attr1' => 'val1',
 *             'attr2' => 'val2',
 *         ],
 *     ],
 *     [
 *         'tag' => 'tag2',
 *         'content' => 'content2',
 *     ],
 *     [
 *         'tag' => 'tag3',
 *         'elements' => [
 *             [
 *                 'tag' => 'tag4',
 *                 'content' => 'content4',
 *             ],
 *         ],
 *     ],
 * ];
 * $xml->fromArray($in);
 * echo $xml->getDocument();
 * ~~~
 * 
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @see http://php.net/manual/ru/ref.xmlwriter.php
 */
class XmlConstructor extends XMLWriter
{
    /**
     * Constructor of XML document structure.
     * @param string $root A root element's name of a current xml document.
     * @param string $file Path of a XSLT file.
     */
    public function __construct($root, $file = null)
    {
        $this->openMemory();
        $this->setIndent(true);
        $this->setIndentString(' ');
        $this->startDocument('1.0', 'UTF-8');
        if ($file !== null) {
            $this->writePi('xml-stylesheet', 'type="text/xsl" href="' . $file . '"');
        }
        $this->startElement($root);
    }

    /**
     * Construct elements and texts from an array.
     * The array should contain an attribute's name in index part.
     * and a attribute's text in value part.
     * 
     * @param array $in Contains tags, attributes and texts.
     */
    public function fromArray($in)
    {
        foreach ($in as $element) {
            if (!is_array($element) || !isset($element['tag'])) {
                continue;
            }
            $tag = $element['tag'];
            $attributes = [];
            if (isset($element['attributes']) && is_array($element['attributes'])) {
                $attributes = $element['attributes'];
            }
            if (isset($element['content'])) {
                $content = $element['content'];
            } elseif (isset($element['elements']) && is_array($element['elements'])) {
                $content = $element['elements'];
            }
            $this->startElement($tag);
            if (is_array($attributes)) {
                foreach ($attributes as $attribute => $value) {
                    $this->writeAttribute($attribute, $value);
                }
            }
            if (isset($content)) {
                if (is_array($content)) {
                    $this->fromArray($content);
                } else {
                    $this->text($content);
                }
            }
            $this->endElement();  
        }
    }

    /**
     * Return the content of a current xml document.
     * @return string XML document.
     */
    public function getDocument()
    {
        $this->endElement();
        $this->endDocument();
        return $this->outputMemory();
    }
}
