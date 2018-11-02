<?php
/**
 * https://stackoverflow.com/questions/14131833/mongodb-aggregate-query-using-php-driver
 */

$result = $c->aggregate([
  [ '$project' => [ 'day' => ['$dayOfYear' => '$executed']  ]  ],
  [ '$group' => ['_id' => ['day' => '$day'], 'n' => ['$sum' => 1]  ] ],
  [ '$sort' => ['_id' => 1] ],
  [ '$limit' => 14 ]
]);



