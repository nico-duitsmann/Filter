<?php

require '../src/Filter.class.php';

use \Duitni\Filter\Search as Search;

// Search with string like pattern in file
$opts = array(
    'patternIsRegex' => true,
    'trimResult' => false,
    'hColor' => Search::DEFAULT_HCOLOR_WEB
);
$regex   = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; // Email regex

$search  = new Search($opts, $regex, 'file.txt');
$matches = $search->getMatches();
$stats   = $search->getStats();

echo '<pre>';
var_dump($matches);
var_dump($stats);
echo '</pre>';