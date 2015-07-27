<?php
require_once("praveenlib.php");
$keys=array("FBId","userName","link");
$respjson= array(
    "userType"=>"people",
    "status"=>"unprocessed",
    "errorCode"=>1
);
if(checkPOST($keys)){
    $conn=connectSQL();
    if($conn){
        $fbId=safeString($conn,$_POST['FBId']);
        $userName=safeString($conn,$_POST['userName']);
        $link=safeString($conn,$_POST['link']);
        $sql="select (id) from users where FBId={$fbId} limit 1";
        if($result=$conn->query($sql)){
            $userCount=$result->num_rows;
            if($userCount==0){
                $sql="insert into users(FBId,userName,link) values ({$fbId},'{$userName}','{$link}')";
                $result=$conn->query($sql);
                if($result){
                    $id=$conn->insert_id;
                    $respjson['userId']=$id;
                    $respjson["status"]="Success";
                    $respjson["errorCode"]=0;
                }else{
                    $respjson["status"]="SQL error";
                    $respjson["SqlError"]=$conn->error;
                    $respjson["errorCode"]=4;
                }
            }else{
                $row=$result->fetch_array();
                $respjson['userId']=$row["id"];
                $respjson["status"]="Success";
                $respjson["errorCode"]=0;
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
