<?php

namespace bupy7\xml\constructor;

use XMLWriter;

use function call_user_func_array;
use function is_array;
use function is_string;
use function str_repeat;
use function array_key_exists;

/**
 * Creating an XML document from an array.
 *
 * Usage:
 *
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
 *
 * @see http://php.net/manual/ru/ref.xmlwriter.php
 * @since 1.0.0
 */
class XmlConstructor
{
    /**
     * @var bool
     */
    private $flushed = false;
    /**
     * @var bool
     */
    private $made = false;
    /**
     * @var bool
     */
    private $hasDocumentStart = false;
    /**
     * @var XMLWriter
     */
    private $document;

    /**
     * @param array $config name-value pairs that will be used to initialize the object
     */
    public function __construct(array $config = [])
    {
        $this->document = new XMLWriter();
        $this->document->openMemory();

        // see XMLWriter::setIndent() and XMLWriter::setIndentString()
        if (array_key_exists('indentString', $config)) {
            if (is_string($config['indentString'])) {
                $this->setIndent($config['indentString']);
            }
        } else {
            $this->setIndent(str_repeat(' ', 4));
        }
        // see XMLWriter::startDocument()
        if (array_key_exists('startDocument', $config)) {
            if (is_array($config['startDocument'])) {
                call_user_func_array([$this, 'setDocumentStart'], $config['startDocument']);
            }
        } else {
            $this->setDocumentStart('1.0', 'UTF-8');
        }
    }

    /**
     * Make XML document from an array.
     * The array should contain an attribute name in index part and an attribute text in value part.
     *
     * Example:
     *
     * $xml->fromArray([
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
     * ]);
     *
     * @param array $in XML document structure as PHP array.
     * @return static
     * @throws RuntimeException
     */
    public function fromArray(array $in)
    {
        if ($this->made) {
            throw new RuntimeException('XML document is made already.');
        }

        $this->made = true;

        if ($this->flushed) {
            throw new RuntimeException('The constructor is closed. You have to create new one to create an XML document'
                . ' again.');
        }

        $this->_fromArray($in);

        return $this;
    }

    /**
     * Return the content of a current xml document.
     * @return string XML document.
     * @throws RuntimeException
     * @since 1.1.0
     */
    public function toOutput()
    {
        if ($this->flushed) {
            throw new RuntimeException('The constructor is closed. You have to create new one to flush its again.');
        }

        $this->flushed = true;

        if ($this->hasDocumentStart) {
            $this->document->endDocument();
        }

        return $this->document->outputMemory();
    }

    /**
     * @param string $indent
     * @return void
     * @see XMLWriter::setIndent()
     * @see XMLWriter::setIndentString()
     */
    private function setIndent($indent)
    {
        $this->document->setIndent(true);
        $this->document->setIndentString($indent);
    }

    /**
     * @param string $version
     * @param string|null $encoding
     * @param string|null $standalone
     * @return void
     * @see XMLWriter::startDocument()
     */
    private function setDocumentStart($version = '1.0', $encoding = null, $standalone = null)
    {
        $this->document->startDocument($version, $encoding, $standalone);
        $this->hasDocumentStart = true;
    }

    /**
     * @param array $in
     * @return void
     */
    private function _fromArray(array $in)
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

            $this->document->startElement((string)$tag);
            foreach ($attributes as $attribute => $value) {
                $this->document->writeAttribute((string)$attribute, (string)$value);
            }
            if ($content !== null) {
                if (is_array($content)) {
                    $this->_fromArray($content);
                } else {
                    if (isset($element['cdata']) && (bool)$element['cdata']) {
                        $this->document->writeCdata((string)$content);
                    } else {
                        $this->document->text((string)$content);
                    }
                }
            }
            $this->document->endElement();
        }
    }
}
