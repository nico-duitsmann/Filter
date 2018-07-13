<?php

require '../src/Filter.class.php';

use \Duitni\Filter\Search as Search;

// Search with string like pattern in file
$opts = array(
    'trimResult' => false,
    'hColor' => Search::DEFAULT_HCOLOR_WEB
);
$search  = new Search($opts, 'MyPattern', '/var/www/html');
$matches = $search->getMatches();
$stats   = $search->getStats();

echo '<pre>';
var_dump($matches);
var_dump($stats);
echo '</pre>';