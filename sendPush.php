<?php
$url = 'https://android.googleapis.com/gcm/send';

$msg=array("message"=>"This is Praveen");
$ids=array("eCTaW81LgAs:APA91bHGx2St02422bTalQJ1TpiAapDE6HSrjcJrvMnoiGXTHVx7jS_fTzsby2NYvKoJFeaKWWR4ouZ1kzqk3ASDZLtEGu8AwAsAwFy0NpeX8wnkRK0lcBT5rL2rT-NYC5djbqik0xWC");
$fields = array(
    'registration_ids' => $ids,
    'data' => $msg
);

$headers = array(
    'Authorization: key=AIzaSyDv9rrBxYtOiUy8Cp6I64IuMKYgyYDPrmU',
    'Content-Type: application/json'
);
// Open connection
$ch = curl_init();

// Set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Disabling SSL Certificate support temporarly
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

// Execute post
$result = curl_exec($ch);
if ($result === FALSE) {
    die('Curl failed: ' . curl_error($ch));
}

// Close connection
curl_close($ch);
echo $result;