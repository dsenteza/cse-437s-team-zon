<?php
$jsonarray = [];
$index = [];
$mysqli = new mysqli('localhost', 'l.florence', 'PASSWORD', '437s');
$stmt = $mysqli->prepare("
SELECT CONCAT('[', better_result, ']') AS best_result FROM ( SELECT GROUP_CONCAT('{', my_json, '}' SEPARATOR ',') AS better_result FROM (   SELECT      CONCAT     (       ''asin':'   , '"', asin   , '"', ','        ''title':', '"', title, '"', ','       ''author':'  , author     ) AS my_json   FROM booksdatabase4 ) AS more_json ) AS yet_more_json;
");

$stmt->execute();

$stmt->bind_result($jsonarray);

$stmt->fetch();

$file = 'jsonfile.json';
file_put_contents($file, $jsonarray);


?>
