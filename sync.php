<?php

extract($_GET);
extract($_POST);
date_default_timezone_set('America/New_York');

$timestamp = date('Y-m-d--') . date('H-i-s');

if ($payload) {
    file_put_contents('fac.json', $payload);
}

// assetReport
if ($assetReport) {

    file_put_contents("assetReport-$uid-$timestamp.json", $assetReport);
}
