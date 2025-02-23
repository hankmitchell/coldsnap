<?php

extract($_GET);
extract($_POST);
date_default_timezone_set('America/New_York');

$timestamp = date('Y-m-d--') . date('H-i-s');

if ($assetImage) {
    // $assetImage = str_replace('data:image/png;base64,', '', $assetImage);
    // //$assetImage = str_replace(' ', '+', $assetImage);
    // $data = base64_decode($assetImage);
    // $file = $assetName;
    // $success = file_put_contents($file, $data);
    // echo $file;
    saveDataUriAsImage($assetImage, $assetName);
}


function saveDataUriAsImage($dataUri, $assetName)
{
    // Check if the data URI is valid
    if (preg_match('/^data:image\/(\w+);base64,(.+)$/', $dataUri, $matches)) {
        $imageType = $matches[1]; // Extract the image type (e.g., png, jpeg, gif)
        $imageData = base64_decode($matches[2]); // Decode the base64 data

        if ($imageData === false) {
            return false; // Decoding failed
        }

        // Define the output file path
        // $filePath = rtrim($assetName .  '.' . $imageType);
        $filePath = rtrim($assetName);

        // Save the image file
        if (file_put_contents("images/$filePath", $imageData)) {
            return $filePath; // Return the saved file path
        }
    }

    return false; // Invalid data URI or failed to save
}
