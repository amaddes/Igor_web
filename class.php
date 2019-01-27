<?php
require 'vendor/autoload.php';

class myMongo {  
        
    public function __construct() {
        try {
            $client = new MongoDB\Client("mongodb://localhost:27017");
            $this->db = $client->clients;
        } catch (MongoConnectionException $e) {
            return 'Error connecting to MongoDB server';
           } catch (MongoException $e) {
            return 'Error: ' . $e->getMessage();
           }
    }

    public function getUnit($account, $unitType) {
        $account_id = iterator_to_array($this->db->accounts->findOne(array('login' => $account)))['_id']->__toString();
        $criteria = array(
        'accaunt_id' => $account_id
        );
        $resultDev = iterator_to_array($this->db->device->find($criteria));
        $resultSta = iterator_to_array($this->db->station->find($criteria));
        if ($unitType == 'dev') 
            return $resultDev;
        else if ($unitType == 'sta') 
            return $resultSta;
        else
            return null;
    }

    public function Reg($regToken, $login = null, $disName = null, $email = null, $password = null)
    {
        if (isset($login) && isset($disName) && isset($email) && isset($password)) {
                $newUser = array (
                    "createdAt" => new MongoDB\BSON\UTCDateTime(),
                    "token" => $regToken, 
                    "login" => $login, 
                    "disName" => $disName, 
                    "email" => $email, 
                    "password" => $password,
                );
            
                $this->db->registration->insertOne($newUser);
                return true;
            }
        else {
            $criteria = array (
                'token' => $regToken,
            );
            $result = $this->db->registration->findOne($criteria);
            if ($result != null) {
                $user = array (
                    'login' => $result->login,
                    'password' => $result->password,
                    'display_name' => $result->disName,
                    'email' => $result->email,
                );
                $this->db->accounts->insertOne($user);
                
                return true;
                }
                else {
                return false;
                };
        }
    }
    
    public function checkAccount($login, $password = null, $email = null)
    {
        $criteria = array(
            'login' => $login,
            );
        $pasCriteria = array(
            'password'=> $password
        );

        $emailCriteria = array(
            'email' => $email
        );
        if ($password  != null) $criteria+=$pasCriteria;
        $result = $this->db->accounts->findOne($criteria);
        if ($email != null) {
            $accoutRes = $this->db->registration->findOne($criteria);
            $emailRes = $this->db->registration->findOne($emailCriteria);
            $email2Res = $this->db->accounts->findOne($emailCriteria);
            if ($regresult != null) {
                return  true;
            }
            else {
            return false;
            };
        }
        if ($result != null) {
            return  true;
        }
        else {
            return false;
        };
        
    }



    public function setSessionId($login, $session_id) {
        $criteria = array(
            'login' => $login,
            ); 
        $result = $this->db->accounts->updateOne($criteria,['$set'=>['session_id'=>$session_id]]);
        return $result;
    }

    public function getUserBySession($session_id) {
        $criteria = array(
            'session_id' => $session_id,
            ); 
        $result = $this->db->accounts->findOne($criteria);
        if ($result) {
        return iterator_to_array($result);
        }
        else return "nousers";
    }
}

class AuthClass {
     /**
     * Проверяет, авторизован пользователь или нет
     * Возвращает true если авторизован, иначе false
     * @return boolean 
     */
    private $cookieLife = 600;
    
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
            $check = new myMongo();
            $result = $check->checkAccount($login, $password);
        if ($result) { //Если логин и пароль введены правильно
            $better_token = md5(uniqid(rand(),1));
            setcookie("session_id",$better_token,time()+$this->cookieLife);
            $check->setSessionId($login, $better_token);
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