<?php
/**
   This file receives post request from the agent software and handles the file recieved.

   @author Mazharul Onim
 */
 
$uploaddir = '/var/www/event-uploads/';
$uploadfile = $uploaddir.basename($_FILES['userfile']['name']);

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
{
    $json = array("success" => "1");
    echo json_encode($json);
    http_response_code(200);
}
else {
    $json = array("error" => "file upload failed");
    echo json_encode($json);
    http_response_code(409);
}

?>

