<!DOCTYPE html>
<html>
    <?php include 'header.html'?>
    <body>
<?php
require 'vendor/autoload.php';
require_once 'class.php';
require 'functions.php';

$db = new myMongo();
$error = false;
if (isset($_GET['reg'])){
    if ($db->Reg($_GET['reg'])) {
        echo "Регистрация завершена.";
    }
}
if (isset($_GET['regsend'])){
    if($_GET['regsend'] == 1){
        echo "Письмо с регистрацией было отправленно вам на указанную почту";
        //echo $_POST["email"];
        //var_dump(isset($_POST["login"]));
    }
}
foreach ($_POST as $key=>$value) {
    if ($value == "" && ($key != "password" && $key != "password2")) {
        $error = true;
    }
}
echo "<div id='error'>";
if ($db->checkAccount($_POST["login"],null,$_POST["email"])) {
    echo "Пользователь с таким логином или email уже существует</br>";

}
elseif ($error && (count($_POST)>0)) {
    echo "Не все поля заполнены</br>";
    $error = true;
}
elseif (isset($_POST["email"]) && ($_POST["email"] != "") && !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    echo "Адрес email указан не верно</br>";
        $error = true;
}
elseif (isset($_POST["password"]) && isset($_POST["password2"]) && $_POST["password"] == "" && $_POST["password2"] == "") {
        echo "Не задан пароль</br>";
        $error = true;
}
elseif ($_POST["password"] != $_POST["password2"]) {
        echo "Не одинаковые пароли</br>";
        $error = true;
}
elseif (!$error && (count($_POST)>0)) {
    echo "no error";
    $regToken = md5(uniqid(rand(),1));
    $db->Reg($regToken, $_POST["login"], $_POST["disName"], $_POST["email"], $_POST["password"]);
    $sending = smtpmailer($_POST["email"], "registration@butlerigor.ru", "Registration Bot", "Регистрация нового пользователя ".$_POST["login"] , $regToken);
    unset($_POST);
    header("Location: ?regsend=1");
    }
echo "</div>";

    ?>
        <h2>Регистрация</h2>
        <p>Введите свой логин и пароль</p>
        <form method="post" action="">
        <div >Логин:</div> <input type="text" name="login" value="<?=$_POST["login"];?>" /><br/>
        <div >Отображаемое имя:</div> <input type="text" name="disName" value="<?=$_POST["disName"];?>"/><br/>
        <div >E-mail:</div> <input type="text" name="email" value="<?=$_POST["email"];?>"/><br/>
        <div >Пароль:</div> <input type="password" name="password" /><br/>
        <div >Повторите пароль:</div> <input type="password" name="password2" /><br/>
        <input type="submit" value="Регистрация" />
        <br/><br/><a href="/index.php">На главную</a>
        </form>
    </body>
</html>