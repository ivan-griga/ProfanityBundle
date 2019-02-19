<?php

namespace Vangrg\ProfanityBundle\Service;

use Vangrg\ProfanityBundle\Storage\ProfanitiesStorageInterface;

/**
 * Class Check
 * @package Vangrg\ProfanityBundle\Service
 */
class ProfanityChecker
{
    const SEPARATOR_PLACEHOLDER = '{!!}';

    /**
     * @var ProfanitiesStorageInterface
     */
    private $storage;

    /**
     * @var string
     */
    private $currentExpression = '';

    /**
     * @var string
     */
    private $currentProfanity = '';

    /**
     * @var array
     */
    private $regularExpressions = [];

    /**
     * @var array
     */
    private $profanities = [];

    /**
     * Escaped separator characters
     */
    protected $escapedSeparatorCharacters = array(
        '\s',
    );
    /**
     * Unescaped separator characters.
     * @var array
     */
    protected $separatorCharacters = array(
        '@',
        '#',
        '%',
        '&',
        '_',
        ';',
        "'",
        '"',
        ',',
        '~',
        '`',
        '|',
        '!',
        '$',
        '^',
        '*',
        '(',
        ')',
        '-',
        '+',
        '=',
        '{',
        '}',
        '[',
        ']',
        ':',
        '<',
        '>',
        '?',
        '.',
        '/',
    );
    /**
     * List of potential character substitutions as a regular expression.
     *
     * @var array
     */
    protected $characterSubstitutions = array(
        '/a/' => array(
            'a',
            '4',
            '@',
            'Á',
            'á',
            'À',
            'Â',
            'à',
            'Â',
            'â',
            'Ä',
            'ä',
            'Ã',
            'ã',
            'Å',
            'å',
            'æ',
            'Æ',
            'α',
            'Δ',
            'Λ',
            'λ',
        ),
        '/b/' => array('b', '8', '\\', '3', 'ß', 'Β', 'β'),
        '/c/' => array('c', 'Ç', 'ç', 'ć', 'Ć', 'č', 'Č', '¢', '€', '<', '(', '{', '©'),
        '/d/' => array('d', '\\', ')', 'Þ', 'þ', 'Ð', 'ð', 'ď', 'Ď'),
        '/e/' => array(
            'e',
            '3',
            '€',
            'È',
            'è',
            'É',
            'é',
            'Ê',
            'ê',
            'ë',
            'Ë',
            'ē',
            'Ē',
            'ė',
            'Ė',
            'ę',
            'Ę',
            '∑',
            'ě',
            'Ě',
        ),
        '/f/' => array('f', 'ƒ'),
        '/g/' => array('g', '6', '9'),
        '/h/' => array('h', 'Η'),
        '/i/' => array('i', '!', '|', ']', '[', '1', '∫', 'Ì', 'Í', 'Î', 'Ï', 'ì', 'í', 'î', 'ï', 'ī', 'Ī', 'į', 'Į'),
        '/j/' => array('j'),
        '/k/' => array('k', 'Κ', 'κ'),
        '/l/' => array('l', '!', '|', ']', '[', '£', '∫', 'Ì', 'Í', 'Î', 'Ï', 'ł', 'Ł', 'ľ', 'Ľ'),
        '/m/' => array('m'),
        '/n/' => array('n', 'η', 'Ν', 'Π', 'ñ', 'Ñ', 'ń', 'Ń'),
        '/o/' => array(
            'o',
            '0',
            'Ο',
            'ο',
            'Φ',
            '¤',
            '°',
            'ø',
            'ô',
            'Ô',
            'ö',
            'Ö',
            'ò',
            'Ò',
            'ó',
            'Ó',
            'œ',
            'Œ',
            'ø',
            'Ø',
            'ō',
            'Ō',
            'õ',
            'Õ',
        ),
        '/p/' => array('p', 'ρ', 'Ρ', '¶', 'þ'),
        '/q/' => array('q'),
        '/r/' => array('r', '®', 'ř', 'Ř'),
        '/s/' => array('s', '5', '$', '§', 'ß', 'Ś', 'ś', 'Š', 'š'),
        '/t/' => array('t', 'Τ', 'τ', 'ť', 'Ť'),
        '/u/' => array('u', 'υ', 'µ', 'û', 'ü', 'ù', 'ú', 'ū', 'Û', 'Ü', 'Ù', 'Ú', 'Ū', 'ů', 'Ů'),
        '/v/' => array('v', 'υ', 'ν'),
        '/w/' => array('w', 'ω', 'ψ', 'Ψ'),
        '/x/' => array('x', 'Χ', 'χ'),
        '/y/' => array('y', '¥', 'γ', 'ÿ', 'ý', 'Ÿ', 'Ý'),
        '/z/' => array('z', 'Ζ', 'ž', 'Ž', 'ź', 'Ź', 'ż', 'Ż'),
    );

    private $separatorExpression;
    private $characterExpressions;

    /**
     * @var bool
     */
    private $allowBoundByWords;

    /**
     * Check constructor.
     * @param ProfanitiesStorageInterface $storage
     * @param bool $allowBoundByWords
     */
    public function __construct(ProfanitiesStorageInterface $storage, $allowBoundByWords)
    {
        $this->storage = $storage;

        $this->separatorExpression  = $this->generateSeparatorExpression();
        $this->characterExpressions = $this->generateCharacterExpressions();
        $this->allowBoundByWords = $allowBoundByWords;
    }

    /**
     * Checks string for profanities based on list 'profanities'
     *
     * @param $string
     *
     * @return bool
     */
    public function hasProfanity($string)
    {
        if (empty($string)) {
            return false;
        }

        $this->currentExpression = '';
        $this->currentProfanity = '';

        $expressions = $this->generateRegularExpressions();

        foreach ($expressions as $key => $expression) {
            if ($this->stringHasProfanity($string, $expression)) {
                $this->currentExpression = $expression;
                $this->currentProfanity = $this->profanities[$key];
                return true;
            }
        }

        return false;
    }

    /**
     * Obfuscated a 'profanity' in the string.
     *
     * @param $string
     *
     * @return string
     */
    public function obfuscateIfProfane($string)
    {
        while ($this->hasProfanity($string)) {
            $string = preg_replace($this->currentExpression, str_repeat("*", strlen($this->currentProfanity)), $string);
        }

        return $string;
    }

    /**
     * @return array
     */
    private function generateRegularExpressions()
    {
        if ( !$this->storage->checkIfDataHasChanged() && !empty($this->regularExpressions) ) {
            return $this->regularExpressions;
        }

        $this->regularExpressions = [];

        $this->profanities = $this->storage->getProfanities();

        foreach ($this->profanities as $profanity) {
            $this->regularExpressions[] = $this->generateProfanityExpression(
                $profanity,
                $this->characterExpressions,
                $this->separatorExpression
            );
        }

        return $this->regularExpressions;
    }

    /**
     * Checks a string against a profanity.
     *
     * @param $string
     * @param $expression
     *
     * @return bool
     */
    private function stringHasProfanity($string, $expression)
    {
        return preg_match($expression, $string) === 1;
    }

    /**
     * Generate a regular expression for a particular word
     *
     * @param $word
     * @param $characterExpressions
     * @param $separatorExpression
     *
     * @return mixed
     */
    private function generateProfanityExpression($word, $characterExpressions, $separatorExpression)
    {
        $startOfExpression = '/(^|'.$separatorExpression.')' . $this->getOptionalWordsBounding();
        $endOfExpression = '($|' . $separatorExpression . ')' . $this->getOptionalWordsBounding();
        $expression = $startOfExpression . preg_replace('/'.self::SEPARATOR_PLACEHOLDER.'$/', '', preg_replace(
                array_keys($characterExpressions),
                array_values($characterExpressions),
                $word
            )) . $endOfExpression . '/i';

        return str_replace(self::SEPARATOR_PLACEHOLDER, $separatorExpression.'*', $expression);
    }

    private function getOptionalWordsBounding()
    {
        return $this->allowBoundByWords ? '?' : '';
    }

    /**
     * Generates the separator regex to test characters in between letters.
     *
     * @param array  $characters
     * @param array  $escapedCharacters
     *
     * @return string
     */
    private function generateEscapedExpression(
        array $characters = array(),
        array $escapedCharacters = array()
    ) {
        $regex = $escapedCharacters;
        foreach ($characters as $character) {
            $regex[] = preg_quote($character, '/');
        }

        return '[' . implode('', $regex) . ']';
    }

    /**
     * Generates the separator regular expression.
     *
     * @return string
     */
    private function generateSeparatorExpression()
    {
        return $this->generateEscapedExpression($this->separatorCharacters, $this->escapedSeparatorCharacters);
    }

    /**
     * Generates a list of regular expressions for each character substitution.
     *
     * @return array
     */
    private function generateCharacterExpressions()
    {
        $characterExpressions = array();
        foreach ($this->characterSubstitutions as $character => $substitutions) {
            $characterExpressions[ $character ] = $this->generateEscapedExpression(
                    $substitutions,
                    array()
                ) . self::SEPARATOR_PLACEHOLDER;
        }

        return $characterExpressions;
    }
}
