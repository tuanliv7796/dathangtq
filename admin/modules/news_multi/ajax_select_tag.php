<?php
require_once("inc_security.php");
$new_id 				= getValue("new_id", "str", "POST", 0);
$tag_id 				= getValue("tag_id", "str", "POST", 0);
$status 				= getValue("status", "int", "POST", 0);

if($status == 0){
    $sql = "DELETE FROM news_tag WHERE nt_news_id = '".$new_id."' AND nt_tag_id = ".$tag_id;
    //echo $sql;
    $db_excute = new db_execute($sql);
    unset($db_excute);
}elseif($status == 1){
    $sql = "INSERT INTO news_tag(nt_news_id,nt_tag_id,nt_date) VALUES ('".$new_id."', ".$tag_id.",".time().")";
    //echo $sql;
    $db_excute = new db_execute($sql);
    unset($db_excute);
}