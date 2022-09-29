<?php include '../includes/config.php';

d($_SESSION);

d(getcart());


/**
 * Create Unique Arrays using an md5 hash
 *
 * @param array $array
 * @return array
 */


/*function arrayUniqueidentifier($array, $preserveKeys = false)
{
    // Unique Array for return
    $arrayRewrite = array();
    // Array with the md5 hashes
    $arrayHashes = array();
    foreach($array as $key => $item) {
        // Serialize the current element and create a md5 hash
        $hash = md5(serialize($item));
        // If the md5 didn't come up yet, add the element to
        // to arrayRewrite, otherwise drop it
        if (!isset($arrayHashes[$hash])) {
            // Save the current element hash
            $arrayHashes[$hash] = $hash;
            // Add element to the unique Array
            if ($preserveKeys) {
                $arrayRewrite[$key] = $item;
            } else {
                $arrayRewrite[] = $item;
            }
        }
    }
    return $arrayRewrite;
}

$uniqueArray = arrayUnique($_SESSION);
var_dump($uniqueArray);


*/

$uid = $_GET['uid'];
d(productidfromuid($uid));


use \StathisG\GreekSlugGenerator\GreekSlugGenerator;

// produces: the-class-can-be-used-for-english-only-titles-as-well
echo GreekSlugGenerator::getSlug('The class can be used for ENGLISH-ONLY titles as well');

// produces: it-ignores-multiple-spaces-between-the-words
echo GreekSlugGenerator::getSlug('It   ignores   multiple   spaces   between   the   words');

// produces: as-well-as-brackets-quotes-commas-and-full-stops
echo GreekSlugGenerator::getSlug('As well as brackets {[()]}, quotes `\'", commas, and full stops.');