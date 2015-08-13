<?php
require_once('praveenlib.php');
$conn=connectSQL();
$conn->multi_query("call takeTicket(11)");
do{
if($result=$conn->store_result()){
    while($row=$result->fetch_array())print_r($row);
    $result->free_result();
}
}while($conn->next_result());

echo "\n done ";