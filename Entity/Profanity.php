<?php

namespace Vangrg\ProfanityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Profanity
 * @package Vangrg\ProfanityBundle\Entity
 *
 * @ORM\Table(name="profanities")
 * @ORM\Entity(repositoryClass="Vangrg\ProfanityBundle\Entity\ProfanityRepository")
 * @UniqueEntity(fields={"word"}, message="This word is already taken.")
 */
class Profanity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="word", type="string", nullable=false, unique=true)
     * @Assert\NotBlank()
     */
    private $word;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set word
     *
     * @param string $word
     * @return Profanity
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return string 
     */
    public function getWord()
    {
        return $this->word;
    }
}
