
<?php
//  include_once("./session.php");
include_once("./connection.php");
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
session_start();
$incoming_data = json_decode(file_get_contents('php://input'), true);

$postbox = cleanInput($incoming_data['postbox']);
$currentdate = date('Y-m-d');
$dummy_likes = 0;

$incoming_data = json_decode(file_get_contents('php://input'), true);

$user_id = (int)$incoming_data['userID'] ?: 1;

$query = mysqli_query($conn, "SELECT max(postID) as id FROM Posts");
while ($row = mysqli_fetch_assoc($query)) {
    $postID = $row['id'];
}
$postID = (int)$postID + 1;

// postID, userID, postContent, postDate, likes

$sql_insert = "INSERT INTO Posts ( `postID`, `userID`, `postContent`, `postDate`, `likes`  ) VALUES ('$postID', '$user_id' , '$postbox' , '$currentdate' , '$dummy_likes' )";
if (mysqli_query($conn, $sql_insert)) {
    $response_header['status_code'] = 200;
    $response_header['response_message'] = 'Post Added Successfully';
    echo json_encode($response_header);
    // mysql_free_result() 
} else {
    $response_header['status_code'] = 206;
    $response_header["userID"] = $user_id;
    $response_header['response_message'] = 'Error' . mysqli_error($conn);
    echo json_encode($response_header);
}
