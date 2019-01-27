<?php
require 'vendor/autoload.php';
require_once 'class.php';
require 'functions.php';

$db2 = new myMongo();

$res = $db2->Reg("7db54b8e3defa207fb9b23575155d522");
var_dump($res);
?>