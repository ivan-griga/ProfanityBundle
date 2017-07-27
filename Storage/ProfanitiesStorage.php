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

    /**
     * @var bool
     */
    private $profanitiesIsChanged = false;

    /**
     * ProfanitiesStorage constructor.
     */
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