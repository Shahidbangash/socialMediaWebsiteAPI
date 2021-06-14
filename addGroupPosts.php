<?php
include_once("./connection.php");
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');

$incoming_data = json_decode(file_get_contents('php://input'), true);

$groupID = cleanInput($incoming_data['groupID']);
$postContent = cleanInput($incoming_data['postContent']);
$currentdate = date('Y-m-d');
$user_id = cleanInput((int)$incoming_data['userID']);


$query = mysqli_query($conn, "SELECT max(postID) as id FROM groupPosts");
while ($row = mysqli_fetch_assoc($query)) {
    $postID = $row['id'];
}
$postID = (int)$postID + 1;

// postID, userID, postContent, postDate, likes

$sql_insert = "INSERT INTO groupPosts(`postID`, `userID`, `groupID` , `postContent`, `postDate`, `likes`) VALUES ('$postID', '$user_id' , '$groupID' , '$postContent' , '$currentdate' , '0' )";
if (mysqli_query($conn, $sql_insert)) {
    $response_header['status_code'] = 200;
    $response_header['response_message'] = 'Post Added Successfully';
    echo json_encode($response_header);
    // mysql_free_result()
} else {
    $response_header['status_code'] = 206;
    $response_header['response_message'] = 'Error ' . mysqli_error($conn);
    echo json_encode($response_header);
}
