<?php

namespace yellowheroes\jimmy\system\mvc\models;

use yellowheroes\jimmy\system\config as config;
use yellowheroes\jimmy\system\libs as libs;

class LoginModel extends libs\CoreModel
{

    const REGISTER_SUCCESS = "New user registered successfully!";
    const REGISTER_FAILURE = "User name already exists, please choose another name";
    const REGISTER_INVALID = "Invalid user name: allowed characters - alphanumeric(abc-123), underscores(_), dashes(-), but no spaces";
    const DEREGISTER_SUCCESS = "User account successfully removed";
    const DEREGISTER_FAILURE = "User account could not be removed";
    const LOGIN_SUCCESS = "User name and password validated and authenticated";
    const LOGIN_FAILURE_USERNAME = "User name not found";
    const LOGIN_FAILURE_PASSWORD = "Incorrect password";
    const LOGIN_INVALID = self::REGISTER_INVALID;

    function __construct()
    {
        parent::__construct();  // get access to flat-file database connections and SessionManager object
    }

    /**
     * $this->usersDb is the connection to the _users.yel database
     *
     * this connection is established in parent class CoreModel.php
     * The _users database (system/flatdb/_users.yel) holds all registered users in the following format:
     *
     * the key('admin' in this case) is the user's unique username
     * admin=a:5:{
     *              s:8:"username";s:5:"admin";
     *              s:8:"password";s:60:"$2y$10$Z.PhRTw82SHVfs.e/q8pbOBhz5nGXla/RIVAxEqVqekpI/QTUrkDi";
     *              s:8:"usertype";s:5:"admin";
     *              s:5:"email";s:22:"admin@yellowheroes.com";
     *              s:9:"timestamp";s:22:"2018_March_14_22_33_09";
     *              }
     */
    public function registerUser()
    {
        $userName = $_POST['username'];
        $passWord = $_POST['password'];
        $email = (isset($_POST['email'])) ? $_POST['email'] : 'N/A';
        $userType = $_POST['usertype'];

        $msg = [];
        $validUserName = $this->validateUserName($userName);
        /** test if $userName is valid - i.e. not empty and no invalid characters */
        if ($validUserName) {
            $verifyUserName = $this->usersDb->get($userName);
            /** test if $userName is unique - i.e. it won't be found in database '_users', and get($userName) returns false */
            if ($verifyUserName === false) {
                $hashedPassWord = (new libs\PassWord())->hashPassWord($passWord);
                $timeStamp = \date("Y_F_d_H_i_s");
                $this->usersDb->set($userName, ['username' => $userName, 'password' => $hashedPassWord, 'usertype' => $userType, 'email' => $email, 'timestamp' => $timeStamp]);
                /** $userName is unique and valid */
                $msg[0] = true;
                $msg[1] = self::REGISTER_SUCCESS;
                return $msg;
            } else {
                $msg[0] = false;
                $msg[1] = self::REGISTER_FAILURE;
                return $msg;
            }
        } else {
            $msg[0] = false;
            $msg[1] = self::REGISTER_INVALID;
            return $msg;
        }
    }

    public function deregisterUser()
    {
        $userName = (isset($_POST['username']) && !empty($_POST['username'])) ? $_POST['username'] : false;

        $msg = [];
        if ($userName !== false) {
            $this->usersDb->delete($userName);
            $msg[0] = true;
            $msg[1] = self::DEREGISTER_SUCCESS;
            return $msg;
        } else {
            $msg[0] = false;
            $msg[1] = self::DEREGISTER_FAILURE;
            return $msg;
        }
    }

    public function getUsers()
    {
        $userRecords = $this->usersDb->getKeys();
        return $userRecords;
    }

    /**
     * $this->usersDb is the connection to the user database that is established in CoreModel.php
     */
    public function checkCredentials()
    {
        $userName = $_POST['username'];
        $passWord = $_POST['password'];

        $msg = [];
        $validUserName = $this->validateUserName($userName);
        if ($validUserName) {
            $userRecord = $this->usersDb->get($userName); // userName is valid, but does it exist in db - $userRecord = false if it doesn't exist in db
        } else {
            $msg[0] = false;
            $msg[1] = self::LOGIN_INVALID;
            return $msg;
        }

        if ($userRecord) {
            // yes, username is valid and it exists in the database, now check the password
            $verifyPassWord = (new libs\PassWord())->verifyPassWord($passWord, $userRecord['password']);
            if ($verifyPassWord) {
                $msg[0] = true;
                $msg[1] = self::LOGIN_SUCCESS;
                $userType = $userRecord['usertype'];
                $logTime = \date("Y_F_d_H_i_s");

                /**
                 * Username and password are correct
                 *
                 * we utilise the SessionManager to store user logged-status
                 * because user logs in, we immediately regenerate session id (use: $session->start(0); )
                 *
                 * use:
                 * $this->session to utilise SessionManager functionality
                 * $this->session->migrate(0); set '0' to regenerate id directly for successful login (escalation of access privileges)
                 */
                $this->session->migrate(0); // escalation of access privileges - '0' === regenerate session ID immediately
                $key = 'log-status';
                $value = ['loggedin' => true, 'username' => $userName, 'usertype' => $userType];
                $this->session->set($key, $value);

                if (config\Config::EMAILVERIF) { // constant EMAILVERIF - if set to true, invoke e-mail verification
                    $to = 'robertkuin@gmail.com';
                    $subject = 'login verification';
                    $msg = 'please click link to verify your login';
                    $this->emailVerif($to, $subject, $msg);
                }
                return $msg;
            } else {
                $msg[0] = false;
                $msg[1] = self::LOGIN_FAILURE_PASSWORD;
                return $msg;
            }
        } else {
            $msg[0] = false;
            $msg[1] = self::LOGIN_FAILURE_USERNAME;
            return $msg;
        }
    }

    /**
     * use e-mail verification for login
     *
     * @param array headers     the FROM: header must be, and is default set by class Email to 'admin@yellowheroes.com'
     */
    public function emailVerif($to = null, $subject = null, $msg = null, $headers = [])
    {
        $email = (new libs\Email())->send($to, $subject, $msg, $headers);
    }

    /**
     * We copied this validation code from Flintstone::Validation.php
     * it tests if $userName is empty or contains invalid characters
     *
     * the regex requires the $userName to match:
     * - any word character (\w) - alphanumeric character (alphabet, numbers, underscores)
     * - a dash (-)
     * - 1 or more times
     *
     * @param type $userName
     * @return boolean
     */
    public function validateUserName($userName)
    {
        if (empty($userName) || !preg_match('/^[\w-]+$/', $userName)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * logout user - destroy the session
     */
    public function logout()
    {
        /** log out user */
        $session = new libs\SessionManager();
        $session->start(); // if already started, use the running session
        $session->clear(); // clears complete $_SESSION array             
        //delete session cookie
        $session->deleteCookie();

        //finally destroy session
        \session_destroy();

        /** redirect user to src/app/index */
        $index = (new config\Config(true))->path['index'];
        \header('Location: ' . $index);
        exit();
        /** terminate current script */
    }

    /**
     * source: https://stackoverflow.com/questions/1846202/php-how-to-generate-a-random-unique-alphanumeric-string
     */
    public function uniqId($length = 10)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet);

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max - 1)];
        }

        return $token;
    }
}
