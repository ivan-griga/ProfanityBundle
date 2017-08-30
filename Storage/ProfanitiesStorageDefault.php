<?php

namespace Vangrg\ProfanityBundle\Storage;

use Symfony\Component\Yaml\Yaml;

/**
 * Class ProfanitiesStorageDefault
 * @package Vangrg\ProfanityBundle\Storage
 */
class ProfanitiesStorageDefault implements ProfanitiesStorageInterface
{
    /**
     * @var array
     */
    private $profanities = [];

    /**
     * @var bool
     */
    private $profanitiesIsChanged = false;

    /**
     * ProfanitiesStorageDefault constructor.
     *
     * @param string $fileName
     * @param string $sourceFormat
     */
    public function __construct($fileName, $sourceFormat)
    {
        $this->profanities = $this->loadProfanitiesFromFile($fileName, $sourceFormat);
    }

        /**
     * Return a list of bad words.
     * 
     * @return array
     */
    public function getProfanities()
    {
        $this->profanitiesIsChanged = false;
        return $this->profanities;
    }

    /**
     * Set a list of bad words.
     *
     * @param array $profanities
     */
    public function setProfanities(array $profanities)
    {
        $this->profanities = $profanities;
        $this->profanitiesIsChanged = true;
    }

    /**
     * Check if list of profanities has been changed.
     *
     * @return bool
     */
    public function checkIfDataHasChanged()
    {
        return $this->profanitiesIsChanged;
    }

    /**
     * Load 'profanities' from config file.
     *
     * @return array
     */
    protected function loadProfanitiesFromFile($sourceFileName, $sourceFormat)
    {
        switch ($sourceFormat) {
            case 'yaml':
                $result = Yaml::parse(file_get_contents($sourceFileName));
                $result = $result['profanities'];
                break;
            case 'xml':
                $xml=simplexml_load_file($sourceFileName);
                $result = (array)$xml->word;
                break;
            case 'json':
                $str = file_get_contents($sourceFileName);
                $json = json_decode($str, true);
                $result = $json['profanities'];
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Not supported source format %s'), $sourceFormat);
        }

        return $result;
    }
}