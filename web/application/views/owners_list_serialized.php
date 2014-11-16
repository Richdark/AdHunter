<?php

$owners_arr = array();

// make array from objects
foreach ($owners as $o)
{
	array_push($owners_arr, array('id' => $o->id, 'name' => $o->nazov));
}

// JSON encode
$owners_json = json_encode($owners_arr);

// print JSON
echo $owners_json;

?>