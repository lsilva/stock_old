<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    	
$host = "localhost";
$port =  "3306"; 
$name= "stock";//, $user, $pass,$opcoes)';
$user = 'root';
$pass = '123456';
$opcoes = array(
    /*PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_CASE => PDO::CASE_LOWER*/
);
//$objPdo = new PDO('mysql:host=localhost;dbname=redeworking', 'root','123456');
//var_dump($objPdo );
//exit;    	

echo("mysql:host={$host}; port={$port}; dbname={$name}<br>");

try {
    $dbh = new PDO("mysql:host={$host}; port={$port}; dbname={$name}", $user, $pass,$opcoes);//new PDO($dsn, $user, $password);
    echo('Connection OK.');
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
//exit; 
   	
    }

    public function indexAction()
    {
    }


}

