<!DOCTYPE html>
<html>
    <?php include 'header.html'?>
    <body>
<?php 
require 'vendor/autoload.php';
require_once 'class.php';
$db = new myMongo();
$session_id = $_COOKIE["session_id"];
$user = $db->getUserBySession($session_id);

if ($session_id !="" && ($user !="nousers")) {
    $disName = $user['display_name'];
    echo "Здравствуйте, " .$disName."</br>";
    echo 'Зарегистрированные устройства управления: </br>';
    foreach ($db->getUnit($user['login'], 'dev') as $dev) {
        print_r($dev['name']);
        echo '</br>';
    }
    echo 'Зарегистрированные устройства "Дворецкий": </br>';
    foreach ($db->getUnit($user['login'], 'sta') as $sta) {
        print_r($sta['name']);
        echo '</br>';
    }
echo "<br/><br/><a href='http://butlerigor.ru?is_exit=1'>Выйти</a>"; //Показываем кнопку выхода
}
else {
    header("Location: http://butlerigor.ru/index.php");
}
?> 
    </body>
</html>
