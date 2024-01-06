<?php

try{

$baglanti=new PDO("mysql:host=localhost; dbname=yazlab2_dersprogrami",'root','');

}
catch(Exception $e){

echo $e->getMessage();

}

?>