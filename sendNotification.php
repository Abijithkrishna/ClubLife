<?php
require_once("praveenlib.php");
require_once("applib.php");
$keys=array("userId","message");
$respjson= array(
    "status"=>"unprocessed",
    "errorCode"=>1
);
if(checkPOST($keys)){


        $userId=$_POST['userId'];
        $message=$_POST['message'];
        $ids=array();
        $ids[]=$userId;
        $tokenData=getTokens($ids);
        $tokens=$tokenData['tokens'];
        $title="Server Notification";

        $respjson[]=sendPushNotification($tokens,$title,$message);


}else{
    $respjson["status"]="insufficient Data";
    $respjson["errorCode"]=2;
}

echo json_encode($respjson);
