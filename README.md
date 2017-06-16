Profanity Filter Bundle

A symfony bundle to test if a string has a profanity in it.
Checks performed
Straight matching

Checks string for profanity as it is against list of bad words. E.g. badword
Substitution

Checks string for profanity with characters substituted for each letter. E.g. bâdΨ0rd
Obscured

Checks string for profanity obscured with punctuation between. E.g. b|a|d|w|o|r|d

Combinations

Also works with combinations of the above. E.g. b|â|d|Ψ|0|rr|d
Installation

Install this package via composer.

php composer.phar require vangrg/profanity-bundle

Add to your AppKernel.php:

new Vangrg\ProfanityBundle\VangrgProfanityBundle(),


If you want to use a database to store your profanities:

php app/console doctrine:schema:update --force


