<!DOCTYPE html>
<html>
    <?php include 'header.html'?>
    <body>
<?php
require 'vendor/autoload.php';
require_once 'class.php';
$db = new myMongo();
$error = false;
if (isset($_POST)) {
    foreach ($_POST as $key=>$value) {
        if ($value == "" && ($key != "password" && $key != "password2")) {
            $error = true;
        }
    }
    echo "<div id='error'>";
    if ($db->checkAccount($_POST["login"])) echo "Такой логин уже существует</br>";
    if ($error && (count($_POST)>0)) echo "Не все поля заполнены</br>";
    if (isset($_POST["email"]) && ($_POST["email"] != "") && !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) echo "Адрес email указан не верно</br>";
    if (isset($_POST["password"]) && isset($_POST["password2"]) && $_POST["password"] == "" && $_POST["password2"] == "") echo "Не задан пароль</br>";
    if ($_POST["password"] != $_POST["password2"]) echo "Не одинаковые пароли</br>";
    echo "</div>";
}
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