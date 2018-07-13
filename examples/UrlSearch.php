<?php

set_time_limit(0);

require '../src/Filter.class.php';

use \Duitni\Filter\Search as Search;

// Search with string like pattern in file
$opts = array(
    'trimResult' => true
);

$search  = new Search($opts, 'password', 'http://example.de');
$matches = $search->getMatches();
$stats   = $search->getStats();

echo '<pre>';
var_dump($matches);
var_dump($stats);
echo '</pre>';
