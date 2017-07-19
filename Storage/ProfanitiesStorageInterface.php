<?php

namespace Vangrg\ProfanityBundle\Storage;


interface ProfanitiesStorageInterface
{
    /**
     * Return a list of bad words.
     *
     * @return array
     */
    public function getProfanities();

    /**
     * Set a list of bad words.
     *
     * @param array $profanities
     */
    public function setProfanities(array $profanities);
}