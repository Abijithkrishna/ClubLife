<?php
require_once("praveenlib.php");
$keys=array("userId");
$respjson= array(
    "status"=>"unprocessed",
    "errorCode"=>1
);
if(checkPOST($keys)){
    $conn=connectSQL();
    if($conn){
        $userid=safeString($conn,$_POST['userId']);
        $sql="select eventName,eventId from events WHERE userId={$userid} AND eventDate>NOW()";
        if($result=$conn->query($sql)){
            $respjson["list"]=array();
            while($row=$result->fetch_array()){
                $entry=array(
                    "eventId"=>$row['eventId'],
                    "eventName"=>$row['eventName']
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
