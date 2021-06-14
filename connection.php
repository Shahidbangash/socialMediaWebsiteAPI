<?php

header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');

$servername = getenv("SERVERNAME");
$username = getenv("USERNAME");
$password = getenv("DATABASE_PASSWORD");
$databaseName = getenv("DATABASE_NAME");

$conn = mysqli_connect($servername, $username, $password, $databaseName);


if (!$conn) {
    // if connectoin to db does not established
    die("Connection failed: " . mysqli_connect_error());
    $response_header['status_code'] = 404;
    $response_header['response_message'] = 'We cannot connect to database ' . mysqli_connect_error();
    echo json_encode($response_header);
}


function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function cleanInput($input)
{
    $search = array(
        '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
        '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
    );

    $output = preg_replace($search, '', $input);

    $output = stripslashes($output);


    return preg_replace('/[^A-Za-z0-9@.]/', ' ', $output); // Removes special chars.but we will allow @ and . because email has @ and . character
}
