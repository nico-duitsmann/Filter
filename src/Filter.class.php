<?php

namespace Duitni\Filter;

use \DOMDocument;

/**
 * Filter regular expression and pattern search (https://github.com/nico-duitsmann/Filter)
 * 
 * @author  Nico Duitsmann
 * @version 0.1dev
 * @license GNU General Public License v3

 */


/**
 * Filter search class.
 * 
 * @package Filter
 */
class Search
{
    const VERSION = '0.1dev';

    const DEFAULT_MAX_LINE_LEN = 50;
    const DEFAULT_HCOLOR_WEB = 'red';
    const DEFAULT_HCOLOR_CLI = "\033[31m";

    // Default options array
    const DEFAULT_OPTS = array(
        'patternIsRegex' => false,
        'trimResult'     => true,
        'maxLineLen'     => self::DEFAULT_MAX_LINE_LEN,
        'colored'        => true,
        'hColor'         => '',
    );

    /**
     * The searched pattern.
     *
     * @var string
     */
    protected $pattern;

    /**
     * The subject where to search.
     *
     * @var string
     */ 
    protected $subject;

    /**
     * Use regular expression instead of pattern.
     *
     * @var bool
     */
    protected $patternIsRegex;

    /**
     * Trim result before outputting.
     *
     * @var bool
     */
    protected $trimResult;

    /**
     * Ignore case sensitive.
     *
     * @var bool
     */
    protected $ignoreCase;

    /**
     * Max line length for trimming output.
     *
     * @var int
     */
    protected $maxLineLen;

    /**
     * Colored output.
     *
     * @var bool
     */
    protected $colored;

    /**
     * Highlight color.
     *
     * @var string
     */
    protected $hColor;

    /**
     * Matches stored in array.
     *
     * @var array
     */
    protected $matches = array();

    /**
     * Stats stored in array.
     *
     * @var array
     */
    protected $stats = array();

    /**
     * Number of total processed files.
     *
     * @var int
     */
    protected $totalFiles;

    /**
     * Creates a new Filter Search class object.
     * 
     * @param array  $options Options array. Call static method viewOpts() to show available options.
     * @param string $pattern The searched pattern can be a string like pattern, or a regular expression.
     *                        When using regex as pattern, option 'patternIsRegex' must be set to true.
     * @param string $subject The subject where to search.
     */
    function __construct(array $options = array(), string $pattern, string ...$subject)
    {
        $this->pattern = $pattern;
        self::setOptions($options);

        if ( is_array($subject) ) {
            foreach ($subject as $sub) {
                self::initSearch($sub);
            }
        } else self::initSearch($subject);
    }

    /**
     * Returns class version.
     *
     * @return string
     */
    function __toString()
    {
        return self::VERSION;
    }

    /**
     * Parse and set options.
     *
     * @param array $options
     * @return void
     */
    private function setOptions(array $options)
    {        
        // Default options
        $opts = array_merge(self::DEFAULT_OPTS, $options);

        // Set options
        $this->patternIsRegex = $opts['patternIsRegex'];
        $this->trimResult     = $opts['trimResult'];
        $this->maxLineLen     = $opts['maxLineLen'];
        $this->colored        = $opts['colored'];
        $this->hColor         = $opts['hColor'];
    }

    /**
     * Return matches as array.
     *
     * @return array
     */
    public function getMatches(): array
    {
        return $this->matches;
    }

    /**
     * Get search stats.
     *
     * @return array
     */
    public function getStats(): array
    {
        return $this->stats;
    }

    /**
     * View available options.
     *
     * @return array
     */
    public static function viewOpts(): array
    {
        return self::DEFAULT_OPTS;
    }

    /**
     * Trim given text.
     *
     * @param string $text
     * @param integer $start
     * @param integer $length
     * @return string
     */
    public static function trimText(string $text, int $start, int $length): string
    {
        if ( strlen($text) <= $length )
            return $text;

        $lastSpace = strrpos(substr($text, 0, $length), ' ');
        $trimmed   = substr($text, $start, $lastSpace);

        $trimmed  .= trim(preg_replace('/\s\s+/', ' ', $trimmed));
        $trimmed .= '...';

        return $trimmed;
    }

    /**
     * Push result to array.
     *
     * @param string $foundPattern
     * @param string $subject
     * @param integer $index
     * @param string $file
     * @return void
     */
    private function pushResult(string $foundPattern, string $subject, int $index, string $file='')
    {
        if ( $this->trimResult )
            $subject = self::trimText($subject, $index, $this->maxLineLen);

        if ( $this->colored ) {
            if ( php_sapi_name() == 'cli' ) {
                $subject = str_replace(
                    $foundPattern, "\033[1m".$this->hColor."$foundPattern\033[0m", $subject
                );
            } else {
                $subject = str_replace(
                    $foundPattern, "<span style='color: ".$this->hColor."'><strong>$foundPattern</strong></span>", htmlspecialchars($subject)
                );
            }
        }

        $result = array(
            'pattern'  => $foundPattern,
            'file'     => $file,
            'position' => $index,
            'subject'  => $subject,
        );

        array_push($this->matches, $result);

    }

    /**
     * Pattern search.
     *
     * @param string $subject
     * @param string $pattern
     * @param string $file
     * @return void
     */
    public function patternSearch(string $subject, string $pattern, string $file = '')
    {
        if ( $this->ignoreCase )
            $index = @stripos($subject, $pattern);
        else
            $index = @strpos($subject, $pattern);

        if ( $index !== false )
            self::pushResult($pattern, $subject, $index, $file);
    }

    /**
     * Regular expression search.
     *
     * @param string $subject
     * @param string $regex
     * @param string $file
     * @return void
     */
    public function regexSearch(string $subject, string $regex, string $file = '')
    {
        preg_match_all($regex, $subject, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        foreach ($matches as $match) {
            self::pushResult($match[0][0], $subject, $match[0][1], $file);
        }
    }

    /**
     * Search in file for given pattern.
     *
     * @param string $file
     * @param string $pattern
     * @return void
     */
    public function fileSearch(string $file, string $pattern) {
        $handle = @fopen($file, 'rb');
        while ( $line = @fgets($handle) ) {
            if ( $this->patternIsRegex )
                self::regexSearch($line, $pattern, $file);
            else
                self::patternSearch($line, $pattern, $file);
        }
        @fclose($handle);
    }

    /**
     * Search all files within a directory for given pattern.
     *
     * @param string $dir
     * @param string $pattern
     * @return void
     */
    public function dirSearch(string $dir, string $pattern) {
        $iter = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $dir, \RecursiveDirectoryIterator::SKIP_DOTS
            ),
            \RecursiveIteratorIterator::SELF_FIRST,
            \RecursiveIteratorIterator::CATCH_GET_CHILD
        );

        foreach ($iter as $file => $_dir) {
            if ( !is_dir($_dir) )
                $this->totalFiles++;
                self::fileSearch($file, $pattern);
        }
    }

    /**
     * Follow crawled links in url and search for pattern.
     *
     * @param string $url
     * @param string $pattern
     * @return void
     */
    public function urlSearch(string $url, string $pattern): void
    {
        if ( $this->patternIsRegex ) $this->regexSearch(file_get_contents($url), $pattern, $url);
        else $this->patternSearch(file_get_contents($url), $pattern, $url);

        $dom = new DOMDocument();
        $dom->loadHTML(file_get_contents($url));

        foreach ($dom->getElementsByTagName('a') as $node) {
            $link = $node->getAttribute('href');
            $content = @file_get_contents($link);
            
            if ( $this->patternIsRegex ) $this->regexSearch($content, $pattern, $link);
            else $this->patternSearch($content, $pattern, $link);
        }
    }

    /**
     * Return the source type.
     *
     * @param string $source
     * @return string
     */
    private function returnType(string $source): string {
        if ( is_dir($source) )
            return 'dir';
        elseif ( is_file($source) )
            return 'file';
        elseif ( filter_var($source, FILTER_VALIDATE_URL) !== false )
            return 'url';
        else
            return 'str';
    }

    /**
     * Initialize new search.
     *
     * @param string $subject
     * @return void
     */
    private function initSearch(string $subject)
    {
        $start = microtime(true);

        switch ( self::returnType($subject) ) {
            case 'dir':
                $this->dirSearch($subject, $this->pattern);
                break;
            case 'file':
                $this->fileSearch($subject, $this->pattern);
                break;
            case 'url':
                $this->urlSearch($subject, $this->pattern);
                break;
            case 'str':
                if ( $this->patternIsRegex ) {
                    $this->regexSearch($subject, $this->pattern);
                    break;
                } else {
                    $this->patternSearch($subject, $this->pattern);
                    break;
                }
        }

        $diff = (microtime(true) - $start);

        $stats = array(
            'matches'       => sizeof($this->matches),
            'subject'       => $subject,
            'total_files'   => $this->totalFiles,
            'elapsed_time'  => $diff,
            'time'          => date("i:s", $diff)
        );
        array_push($this->stats, $stats);
    }
}
