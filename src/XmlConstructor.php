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
 *             [
 *                 'tag' => 'tag4',
 *                 'content' => '<b>content4</b>',
 *                 'cdata' => true, // by default - false
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
    /**
     * @var boolean Flag of completed preparing.
     */
    private $_prepared = false;
    /**
     * @var boolean Flag of added start document tag.
     * @since 1.2.0
     */
    private $_startDocument = false;
    
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
            $content = null;
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
                    if (isset($element['cdata']) && $element['cdata']) {
                        $this->writeCdata($content);
                    } else {
                        $this->text($content);
                    }
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
            if ($this->_startDocument) {
                $this->endDocument();
            }
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
            $this->configIndentString($config['indentString']);
        } else {
            $this->configIndentString(str_repeat(' ', 4));
        }

        if (isset($config['startDocument'])) {
            $this->configStartDocument($config['startDocument']);
        } else {
            $this->configStartDocument(['1.0', 'UTF-8']);
        }
    }
    
    /**
     * Tooggle identation on.
     * @param string|false $string String used for indenting.
     * @since 1.2.0
     */
    protected function configIndentString($string)
    {
        if ($string !== false) {
            $this->setIndent(true);
            parent::setIndentString($string);
        }
    }
    
    /**
     * Create document tag.
     * @param array|false $arguments Arguments of method `startDocument()`.
     * @since 1.2.0
     */
    protected function configStartDocument($arguments)
    {
        if (is_array($arguments)) {
            if (call_user_func_array([$this, 'startDocument'], $arguments)) {
                $this->_startDocument = true;
            }
        }
    }
}
