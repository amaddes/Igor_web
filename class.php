<?php
require 'vendor/autoload.php';

class myMongo {
        
}
class AuthClass {
     /**
     * Проверяет, авторизован пользователь или нет
     * Возвращает true если авторизован, иначе false
     * @return boolean 
     */
    public function isAuth() {
        if (isset($_SESSION["is_auth"])) { //Если сессия существует
            return $_SESSION["is_auth"]; //Возвращаем значение переменной сессии is_auth (хранит true если авторизован, false если не авторизован)
        }
        else return false; //Пользователь не авторизован, т.к. переменная is_auth не создана
    }
    
    /**
     * Авторизация пользователя
     * @param string $login
     * @param string $password 
     */
    public function auth($login, $password) {
        try {
            // open connection to MongoDB server
            $client = new MongoDB\Client("mongodb://localhost:27017");
            
             // access collection
            $collection = $client->clients->accounts;
            $criteria = array(
            'login' => $login,
            'password'=> $password
            );       
            $result = $collection->findOne($criteria);
        } catch (MongoConnectionException $e) {
            die('Error connecting to MongoDB server');
           } catch (MongoException $e) {
            die('Error: ' . $e->getMessage());
        }
        if ($result != null) { //Если логин и пароль введены правильно
            $_SESSION["is_auth"] = true; //Делаем пользователя авторизованным
            $_SESSION["login"] = $login; //Записываем в сессию логин пользователя
            return true;
        }
        else { //Логин и пароль не подошел
            $_SESSION["is_auth"] = false;
            return false; 
        }
    }
    
    /**
     * Метод возвращает логин авторизованного пользователя 
     */
    public function getLogin() {
        if ($this->isAuth()) { //Если пользователь авторизован
            return $_SESSION["login"]; //Возвращаем логин, который записан в сессию
        }
    }
    
    
    public function out() {
        $_SESSION = array(); //Очищаем сессию
        session_destroy(); //Уничтожаем
    }
}