<?php

namespace Vangrg\ProfanityBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class ProfanityRepository
 * @package Vangrg\ProfanityBundle\Entity
 */
class ProfanityRepository extends EntityRepository
{
    public function getProfanitiesArray()
    {
        $result = $this->createQueryBuilder('p')->select('p.word')->getQuery()->getResult();

        return array_column($result, 'word');
    }
}