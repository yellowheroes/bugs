<?php
namespace yellowheroes\bugs\system\mvc\views;

use yellowheroes\bugs\system\mvc\models as models;
use yellowheroes\bugs\system\libs as libs;

/**
 * Bootwrap::form($inputFields = [], $submitDisplay = 'submit', $method = 'POST', $action = "#")
 * @param inputFields       array containing for each field an array with ['type', 'name', 'id', 'value', 'placeholder', options[]]]
 */
$inputFields = [
                ['text', 'username', 'username', "", 'enter user name', 'your username', ['required']],
                ['password', 'password', 'password', "", 'password', 'your password', ['required']]
               ];
$form = $bootWrap->form($inputFields, 'Sign In');
echo $form;

if (isset($_POST['submit'])) {
    /**
     * $credentials will receive from LoginModel::checkCredetials() an array:
     * - case failure:      $credentials[0]==false(boolean),
     *                      $credentials[1]=='some error message'
     *                      
     * - case success:      $credentials[0]==true(boolean)
     *                      $credentials[1]=='success message'
     *                      $credentials[2]==the complete user record
     * 
     *                      array(5) {
     *                      ["username"]=>
     *                      string(4) "piet"
     *                      ["password"]=>
     *                      string(60) "$2y$10$kJfis1P8IhvTQRapC5lCZ.4O//KFU/B7FcpRv62v1zW.dHMWCiAjG"
     *                      ["usertype"]=>
     *                      string(6) "normal"
     *                      ["email"]=>
     *                      string(14) "piet@gmail.com"
     *                      ["timestamp"]=>
     *                      string(22) "2018_March_13_18_45_36"
     *                      }
     * 
     */
    $credentials = (new models\LoginModel())->checkCredentials();
    if ($credentials[0] === true) {
        \header("Location: " . $index); // redirect user to index/index, credentials are OK
        exit(); // terminate script mvc/views/login/index.php
    } else {
        $msg = $credentials[1];
        $alert = (new libs\BootWrap())->alert($msg, 'warning');
        echo $alert;
    }
}
?>