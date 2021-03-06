<?php
require_once("praveenlib.php");
$keys=array("eventId");
$respjson= array(
    "status"=>"unprocessed",
    "errorCode"=>1
);
if(checkPOST($keys)){
    $conn=connectSQL();
    if($conn){
        $eventId=safeString($conn,$_POST['eventId']);
        $sql="select userName,userId,ticketId from eventregistration WHERE eventId={$eventId} and status=1";
        if($result=$conn->query($sql)){
            $respjson["list"]=array();
            while($row=$result->fetch_array()){
                $entry=array(
                    $row['userName'],
                    $row['userId'],
                    $row['ticketId']
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
}else{
    $respjson["status"]="insufficient Data";
    $respjson["errorCode"]=2;
}

echo json_encode($respjson);
