<?php

namespace Vangrg\ProfanityBundle\Storage;

/**
 * Class ProfanitiesStorage
 * @package Vangrg\ProfanityBundle\Storage
 */
class ProfanitiesStorage implements ProfanitiesStorageInterface
{
    /**
     * @var array
     */
    private $profanities = [];

    public function __construct()
    {
        $this->profanities = $this->loadProfanitiesFromFile(__DIR__ . '/../data/profanities.php');
    }

    /**
     * Return a list of bad words.
     * 
     * @return array
     */
    public function getProfanities()
    {
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
    }

    /**
     * Load 'profanities' from config file.
     *
     * @param $config
     *
     * @return array
     */
    private function loadProfanitiesFromFile($config)
    {
        /** @noinspection PhpIncludeInspection */
        return include($config);
    }
}