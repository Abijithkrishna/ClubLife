<?php
require_once("praveenlib.php");
require_once("applib.php");
$keys=array("eventId");
$respjson= array(
    "status"=>"unprocessed",
    "errorCode"=>1
);
if(checkGET($keys)){
    $conn=connectSQL();
    if($conn){
        $eventId=safeString($conn,$_GET['eventId']);
        $sql="select userId,userName,ticketId from eventregistration where eventId=$eventId and status=1";
        if($export=$conn->query($sql)) {
            $header = '';
            $data ='';

            while ($fieldinfo = mysqli_fetch_field($export)) {
                $header .= $fieldinfo->name . "\t";
            }

            while ($row = mysqli_fetch_row($export)) {
                $line = '';
                foreach ($row as $value) {
                    if ((!isset($value)) || ($value == "")) {
                        $value = "\t";
                    } else {
                        $value = str_replace('"', '""', $value);
                        $value = '"' . $value . '"' . "\t";
                    }
                    $line .= $value;
                }
                $data .= trim($line) . "\n";
            }
            $data = str_replace("\r", "", $data);

            if ($data == "") {
                $data = "\nNo Record(s) Found!\n";
            }

// allow exported file to download forcefully
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=devzone_co_in_export.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            print "$header\n$data";

        }
    }
}

