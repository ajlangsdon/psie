<?php

$nid = $nid = arg(1);
$node = node_load($nid);

printvar($node);




function printvar($array) {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}