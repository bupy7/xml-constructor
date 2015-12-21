<?php

namespace bupy7\xml\constructor;

use XMLWriter;

/**
 * Creating XML document from array.
 * 
 * Example:
 * 
 * ~~~
 * $xml = new XmlConstructor();
 * $in = [
 *     [
 *         'tag' => 'root',
 *         'elements' => [
 *             [
 *                 'tag' => 'tag1',
 *                 'attributes' => [
 *                     'attr1' => 'val1',
 *                     'attr2' => 'val2',
 *                 ],
 *             ],
 *             [
 *                 'tag' => 'tag2',
 *                 'content' => 'content2',
 *             ],
 *             [
 *                 'tag' => 'tag3',
 *                 'elements' => [
 *                     [
 *                         'tag' => 'tag4',
 *                         'content' => 'content4',
 *                     ],
 *                 ],
 *             ],
 *         ],
 *     ],
 * ];
 * $xml->fromArray($in);
 * echo $xml->toOutput();
 * ~~~
 * 
 * @author Belosludcev Vasilij <https://github.com/bupy7>
 * @see http://php.net/manual/ru/ref.xmlwriter.php
 * @since 1.0.0
 */
class XmlConstructor extends XMLWriter
{
    private $_prepared = false;
    
    /**
     * Constructor of XML document structure.
     * @param array $config name-value pairs that will be used to initialize the object
     */
    public function __construct(array $config = [])
    {
        $this->openMemory();
        $this->configure($config);
    }

    /**
     * Construct elements and texts from an array.
     * The array should contain an attribute's name in index part.
     * and a attribute's text in value part.
     * 
     * @param array $in Contains tags, attributes and texts.
     * @return static
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
        return $this;
    }

    /**
     * Return the content of a current xml document.
     * @return string XML document.
     * @since 1.1.0
     */
    public function toOutput()
    {
        return $this->preparing()->outputMemory();
    }
    
    /**
     * Preparing document to output.
     * @return static
     */
    protected function preparing()
    {
        if (!$this->_prepared) {
            $this->endDocument();
            $this->_prepared = true;
        }
        return $this;
    }
    
    /**
     * Configures an object with the initial property values.
     * @param array $config name-value pairs that will be used to initialize the object
     * @since 1.2.0
     */
    protected function configure(array $config)
    {
        if (isset($config['indentString'])) {
            $this->setIndentString($config['indentString']);
        } else {
            $this->setIndentString(' ');
        }

        if (isset($config['startDocument'])) {
            $this->setStartDocument($config['startDocument']);
        } else {
            $this->setStartDocument(['1.0', 'UTF-8']);
        }
    }
    
    /**
     * Tooggle identation on.
     * @param string|false $string String used for indenting.
     * @since 1.2.0
     */
    protected function setIndentStrng($string)
    {
        if ($string !== false) {
            $this->setIndent(true);
            $this->setIndentString($string);
        }
    }
    
    /**
     * Create document tag.
     * @param array|false $arguments Arguments of method `startDocument()`.
     * @since 1.2.0
     */
    protected function setStartDocument(array $arguments)
    {
        if ($arguments !== false) {
            call_user_func_array([$this, 'startDocument'], $arguments);
        }
    }
}
