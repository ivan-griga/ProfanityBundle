# Profanity Filter Bundle

A symfony bundle to test if a string has a profanity in it.

**Straight matching**
Checks string for profanity as it is against list of bad words. E.g. badword

**Substitution**
Checks string for profanity with characters substituted for each letter. E.g. bâdΨ0rd

**Obscured**
Checks string for profanity obscured with punctuation between. E.g. b|a|d|w|o|r|d

**Combinations**
Also works with combinations of the above. E.g. b|â|d|Ψ|0|rr|d

## Installation

- Install this package via composer.
    ```
    php composer.phar require vangrg/profanity-bundle
    ```
- Add to your AppKernel.php:
    ```
    new Vangrg\ProfanityBundle\VangrgProfanityBundle(),
    ```
- If you want to use a database to store your profanities:
    ```
    php bin/console doctrine:schema:update --force
    ```
- For populate default profanities data:
    ```
    php bin/console vangrg:profanities:populate
    ```

## Usage

### Default usage

```php
<?php
$check = $this->get('vangrg_profanity.checker');
$hasProfanity = $check->hasProfanity($badWords);
$cleanWords = $check->obfuscateIfProfane($badWords);

/* customized word list from array */
$badWords = array('bad', 'words');
/* or load from db */
$badWords = $this->getDoctrine()->getManagerForClass('Vangrg\ProfanityBundle\Entity\Profanity')
      ->getRepository(Profanity::class)->getProfanitiesArray();

$this->get('vangrg_profanity.storage')->setProfanities($badWords);

```

### Override profanities configuration

```yaml
# add to config.yml
vangrg_profanity:
    storage: your service # By default use 'vangrg_profanity.storage.default', custom storage service must implement "Vangrg\ProfanityBundle\Storage\ProfanitiesStorageInterface"
    allow_bound_by_words: true # Also match profanities inside words e.g. a.r.s.esomething, somethingarse, but be careful also words like glasses
    profanities_source:
        file_name: # your custom filename with profanities
        format: # profanities source format (default yaml), can be yaml, json or xml

```

## Annotation usage

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vangrg\ProfanityBundle\Validator\Constraints as ProfanityAssert;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * 
     * @Assert\Length(min=8, max=64)
     * @ProfanityAssert\ProfanityCheck
     * @ORM\Column(name="title", type="string", length=64)
     */
    private $title;
}
```

# Remark
Bundle is built on the basis of the library 
https://github.com/mofodojodino/ProfanityFilter with an improvement in the logic of the filter
