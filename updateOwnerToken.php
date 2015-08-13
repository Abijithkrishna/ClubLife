<?php
require_once("praveenlib.php");
$keys=array("userId","token");
$respjson= array(
    "status"=>"unprocessed",
    "errorCode"=>1
);
if(checkPOST($keys)){
    $conn=connectSQL();
    if($conn){
        $userId=safeString($conn,$_POST['userId']);
        $token=safeString($conn,$_POST['token']);
        $sql="update owners set ownerToken='$token' where id=$userId";
        if($result=$conn->query($sql)){
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
