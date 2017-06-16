<h1>Profanity Filter Bundle</h1>

A symfony bundle to test if a string has a profanity in it.

<b>Straight matching</b>
Checks string for profanity as it is against list of bad words. E.g. badword

<b>Substitution</b>
Checks string for profanity with characters substituted for each letter. E.g. bâdΨ0rd

<b>Obscured</b>
Checks string for profanity obscured with punctuation between. E.g. b|a|d|w|o|r|d

<b>Combinations</b>
Also works with combinations of the above. E.g. b|â|d|Ψ|0|rr|d

<h2>Installation</h2>
<ul>
  <li>
    Install this package via composer.

    php composer.phar require vangrg/profanity-bundle
  </li>
  <li>
    Add to your AppKernel.php:

    new Vangrg\ProfanityBundle\VangrgProfanityBundle(),
   </li>
   <li>
      If you want to use a database to store your profanities:

    php app/console doctrine:schema:update --force
   </li>
   <li>
        For populate default profanities data:
        
    php app/console vangrg:profanities:populate
   </li>
</ul>

<h2>Usage</h2>

<pre>
/* default constructor */
    $check = new Check();
    $hasProfanity = $check->hasProfanity($badWords);
    $cleanWords = $check->obfuscateIfProfane($badWords);

/* customized word list from file */
    $check = new Check('path.to/wordlist.php');

/* customized word list from array */
    $badWords = array('bad', 'words') 
    /* or load from db */
    $badWords = $this->getDoctrine()->getManagerForClass('Vangrg\ProfanityBundle\Entity\Profanity')
          ->getRepository('VangrgProfanityBundle:Profanity')->getProfanitiesArray()
          
    $check = new Check($badWords);
</pre>
<h2>Remark</h2>
Bundle is built on the basis of the library 
https://github.com/mofodojodino/ProfanityFilter with modifications in filter logic
