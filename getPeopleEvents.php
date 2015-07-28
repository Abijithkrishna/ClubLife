<?php
require_once("praveenlib.php");
$respjson= array(
    "status"=>"unprocessed",
    "errorCode"=>1
);

$conn=connectSQL();
if($conn){
    $sql="select eventName,eventId from events WHERE  eventDate>NOW()";
    if($result=$conn->query($sql)){
        $respjson["list"]=array();
        while($row=$result->fetch_array()){
            $entry=array(
                $row['eventId'],
                $row['eventName']
            );
            $respjson["list"][]=$entry;
        }
        $respjson["status"]="Success";
        $respjson["errorCode"]=0;
    }else{
        $respjson["status"]="SQL error";
        $respjson["SqlError"]=$conn->error;
        $respjson["errorCode"]=4;
    }
}else{
    $respjson["status"]="SQL Connection error";
    $respjson["SqlError"]=$conn->error;
    $respjson["errorCode"]=3;
}


echo json_encode($respjson);
