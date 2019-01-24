<!DOCTYPE html>
<html>
<?php include 'header.html'?>
    <body>
   <?php
require 'vendor/autoload.php';
require_once 'class.php';

session_start(); //Запускаем сессии
/** 
 * Класс для авторизации
 */ 


$auth = new AuthClass();
$db = new myMongo();
setcookie("session_id","123");

if (isset($_POST["login"]) && isset($_POST["password"])) { //Если логин и пароль были отправлены
    if (!$auth->auth($_POST["login"], $_POST["password"])) { //Если логин и пароль введен не правильно
        echo "<h2 style=\"color:red;\">Логин и пароль введен не правильно!</h2>";
    }
}

if (isset($_GET["is_exit"])) { //Если нажата кнопка выхода
    if ($_GET["is_exit"] == 1) {
        $delUser = $db->getUserBySession($_COOKIE["session_id"]);
        unset($_COOKIE["session_id"]);
        $db->setSessionId($delUser,"");
        $auth->out(); //Выходим
        header("Location: ?is_exit=0"); //Редирект после выхода
    }
}
if (isset($_COOKIE["session_id"]) && $auth->isAuth()) { // Если пользователь авторизован, приветствуем:
    header("Location: http://butlerigor.ru/main.php");
} 

if (isset($_COOKIE["session_id"]) && $auth->isAuth()) { // Если пользователь авторизован,но куки уже сдохли(сессия устарела):
    $auth->out();
    echo "<h2 style=\"color:red;\">Ваша сессия устарела!</h2>";
} 

if (!$auth->isAuth()) { //Если не авторизован, показываем форму ввода логина и пароля
?>
    <h2>Дворецкий Игорь</h2>
    <p>Введите свой логин и пароль</p>
    <form method="post" action="">
    Логин: <input type="text" name="login" value="<?php echo (isset($_POST["login"])) ? $_POST["login"] : null; // Заполняем поле по умолчанию ?>" /><br/>
    Пароль: <input type="password" name="password" value="" /><br/>
    <input type="submit" value="Войти" />
    <br/><br/><a href="/registration.php">Регистрация</a>
</form>
<?php } ?>
    </body>
</html>