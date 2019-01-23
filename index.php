<!DOCTYPE html>
<html>
  <head>
    <title>Дворецкий Игорь</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/dopstyle.css" rel="stylesheet" media="screen">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
   <?php
require 'vendor/autoload.php';
require_once 'class.php';

session_start(); //Запускаем сессии
/** 
 * Класс для авторизации
 */ 


$auth = new AuthClass();

if (isset($_POST["login"]) && isset($_POST["password"])) { //Если логин и пароль были отправлены
    if (!$auth->auth($_POST["login"], $_POST["password"])) { //Если логин и пароль введен не правильно
        echo "<h2 style=\"color:red;\">Логин и пароль введен не правильно!</h2>";
    }
}

if (isset($_GET["is_exit"])) { //Если нажата кнопка выхода
    if ($_GET["is_exit"] == 1) {
        $auth->out(); //Выходим
        header("Location: ?is_exit=0"); //Редирект после выхода
    }
}
?>

<?php if ($auth->isAuth()) { // Если пользователь авторизован, приветствуем:  
    echo "Здравствуйте, " . $auth->getLogin() ;
    echo 'Зарегистрированные устройства управления: </br>';
    $device = new myMongo();

    foreach ($device->getUnit($auth->getLogin(), 'dev') as $dev) {
        print_r($dev['name']);
        echo '</br>';
    }
    echo 'Зарегистрированные устройства "Дворецкий": </br>';
    foreach ($device->getUnit($auth->getLogin(), 'sta') as $sta) {
        print_r($sta['name']);
        echo '</br>';
    }
    echo "<br/><br/><a href=\"?is_exit=1\">Выйти</a>"; //Показываем кнопку выхода

} 
else { //Если не авторизован, показываем форму ввода логина и пароля
?>
    <h2>Дворецкий Игорь</h2>
    <p>Введите свой логин и пароль</p>
    <form method="post" action="">
    Логин: <input type="text" name="login" value="<?php echo (isset($_POST["login"])) ? $_POST["login"] : null; // Заполняем поле по умолчанию ?>" /><br/>
    Пароль: <input type="password" name="password" value="" /><br/>
    <input type="submit" value="Войти" />
</form>
<?php } ?>
</body>
</html>