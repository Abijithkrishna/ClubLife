<?php
require_once("praveenlib.php");
$keys=array("ticketId");
$respjson= array(
    "status"=>"unprocessed",
    "errorCode"=>1
);
if(checkPOST($keys)){
    $conn=connectSQL();
    if($conn){
        $ticketId=safeString($conn,$_POST['ticketId']);
        $sql="update eventregistration set status=2 where ticketId={$ticketId};"
        ."update events set ticketCount=ticketCount+1 where eventId=(select eventId from eventregistration where ticketId={$ticketId});";
        if($conn->multi_query($sql)){


            $respjson["status"]="success";
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
