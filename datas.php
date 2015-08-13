<?php
$onServer=true;
if($onServer) {
    $dbURL = "littleo.co.in";
    $dbName = "clublife";
    $dbusername = "praveen_clublife";
    $dbpassword = "database";
}else{
    $dbURL = "localhost";
    $dbName = "clublife";
    $dbusername = "root";
    $dbpassword = "";
}
$dbdetails = array(
    'url' => $dbURL,
    'name' => $dbName,
    'username' => $dbusername,
    'password' => $dbpassword
);


$errorCode=array(
    1=>"Unprocessed",
    2=>"Insufficient Parameters",
    3=>"DB Connectivity Error",
    4=>"Query Error",
    5=>"Autherntication Failure",
    6=>"Functional Error"
);

$appdata=array(
    'apiKey'=>'AIzaSyC4jLvGVORZveiBu3icMfYwy5EvWIdnJ_g',
    'gcmURL'=>'https://android.googleapis.com/gcm/send'
);


?>
