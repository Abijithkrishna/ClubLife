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
        $userId=safeString($conn,$_POST['userId']);
        $sql="select userName,link from users WHERE id={$userId} limit 1";
        if($result=$conn->query($sql)){
            $rowCount=$result->num_rows;
            if($rowCount>0){
                $row=$result->fetch_array();

                $respjson['userName']=$row['userName'];
                $respjson['FBLink']=$row['link'];
                $respjson["status"]="success";
                $respjson["errorCode"]=0;


            }else{
                $respjson["status"]="user Not Found";
                $respjson["errorCode"]=6;
            }
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
