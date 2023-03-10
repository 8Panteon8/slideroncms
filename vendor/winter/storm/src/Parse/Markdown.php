<?php namespace Winter\Storm\Parse;

use Winter\Storm\Support\Facades\Event;
use Winter\Storm\Parse\Parsedown\Parsedown;

/**
 * Markdown helper class.
 *
 * Calling Markdown::parse($text) returns the HTML corresponding
 * to the Markdown input in $text.
 *
 * WinterCMS uses ParsedownExtra as its Markdown parser,
 * but fires markdown.beforeParse and markdown.parse events
 * allowing hooks into the default parsing,
 *
 * The markdown.beforeParse event passes a MarkdownData
 * instance, containing a public $text variable. Event
 * listeners can modify $text, for example to filter out
 * or protect snippets from being interpreted by ParseDown.
 *
 * Similarly, markdown.parse is fired after ParseDown has
 * interpreted the (possibly modified) input. This event
 * passes an array [$text, $data], where $text is the
 * original unmodified Markdown input, and $data is the HTML
 * code generated by ParseDown.
 *
 * @author Alexey Bobkov, Samuel Georges
 **/
class Markdown
{
    use \Winter\Storm\Support\Traits\Emitter;

    /**
     * @var string Parsedown class
     */
    protected $parserClass = \Winter\Storm\Parse\Parsedown\Parsedown::class;

    /**
     * Gets an instance of the parser.
     *
     * We return a new instance each time to prevent contamination of clean instances.
     *
     * @return Parsedown
     */
    protected function getParser()
    {
        return new $this->parserClass;
    }

    /**
     * Sets the Markdown parser.
     *
     * @param string|object $parserClass
     * @return void
     */
    public function setParser(string|object $parserClass)
    {
        if (is_object($parserClass)) {
            $this->parserClass = get_class($parserClass);
        } else {
            $this->parserClass = $parserClass;
        }
    }

    /**
     * Parse text using Markdown and Markdown-Extra
     * @param string $text Markdown text to parse
     * @return string Resulting HTML
     */
    public function parse($text)
    {
        return $this->parseInternal($text);
    }

    /**
     * Enables safe mode
     * @param  string $text Markdown text to parse
     * @return string       Resulting HTML
     */
    public function parseClean($text)
    {
        $parser = $this->getParser()->setSafeMode(true);

        return $this->parseInternal($text, 'text', $parser);
    }

    /**
     * Disables code blocks caused by indentation.
     * @param  string $text Markdown text to parse
     * @return string       Resulting HTML
     */
    public function parseSafe($text)
    {
        $parser = $this->getParser()->setUnmarkedBlockTypes([]);

        return $this->parseInternal($text, 'text', $parser);
    }

    /**
     * Parse a single line
     * @param  string $text Markdown text to parse
     * @return string       Resulting HTML
     */
    public function parseLine($text)
    {
        return $this->parseInternal($text, 'line');
    }

    /**
     * Internal method for parsing
     */
    protected function parseInternal($text, $method = 'text', Parsedown $parser = null)
    {
        if (is_null($parser)) {
            $parser = $this->getParser();
        }
        $data = new MarkdownData($text);

        $this->fireEvent('beforeParse', [$data], false);
        Event::fire('markdown.beforeParse', [$data], false);

        $result = $data->text;

        $result = $parser->$method($result);

        $data->text = $result;

        // The markdown.parse gets passed both the original
        // input and the result so far.
        $this->fireEvent('parse', [$text, $data], false);
        Event::fire('markdown.parse', [$text, $data], false);

        return $data->text;
    }
}
