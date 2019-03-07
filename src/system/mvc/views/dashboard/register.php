<?php
namespace yellowheroes\bugs\system\mvc\views;

use yellowheroes\bugs\system\config as config;
use yellowheroes\bugs\system\mvc\models as models;
use yellowheroes\bugs\system\libs as libs;

/**
 * check if user is logged-in and has appropriate (admin) priviliges
 * - if not, redirect user back to src/app/index
 * 
 * @param $userType     - the variable in header.php that contains userType: admin, editor or guest(visitor) 
 */
if ($userType !== 'admin') {
    /**
     * header.php reads $_SESSION['access_denied'] to determine if user was redirected
     * and, if so, shows an alert to inform user her credentials were insufficient
     * to visit e.g. 'dashboard/index'.
     */
    $session->set('access_denied', 'dashboard_register_php'); 
    header('Location: ' . $index);
    exit();   
}
/**
 * BootWrap::form($inputFields = [], $submitDisplay = 'submit', $method = 'POST', $action = "#")
 * - $submitDisplay: set to false(boolean) to generate form without submit button
 * - $action: set to false(boolean) to not have a 'action' attribute at all (which is allowed from HTML5)
 *            e.g. you want to stay on the same page, ensure NO refresh, and use e.g. Ajax to send data to server.
 * - $method can be "POST" (default) or "GET"
 *  
 * $inputFields: ['type', 'name', 'id', 'value', 'placeholder', options[]]
 * type: the type of input field (e.g. a text field or password field, or type select-box, or type file or radio or select box)
 * options[] - e.g. set 'required' on a field, or define select-items, or set check on a radio button.
 */
/**
 * $inputFields[]
 *   [0]      [1]    [2]    [3]         [4]         [5]       [6]
 * ['type', 'name', 'id', 'value', 'placeholder', 'label', options[]]   //added 'label' on 2018/07/03
 */

$inputFields = [
                ['text', 'username', 'username', "", 'enter user name', 'Your username', ['required']],
                ['password', 'password', 'password', "", 'enter password', 'Your password', ['required']],
                ['email', 'email', 'email', "", 'enter email address', 'Your email', null],
                ['select', 'usertype', 'usertype', "", 'select a user type', 'User type', ['editor', 'admin']]
               ];
$form = $bootWrap->form($inputFields, 'Register User');
echo $form;

if(isset($_POST['submit'])) {
    $registration = (new models\LoginModel())->registerUser();
    if ($registration[0] !== false) {
        $msg = $registration[1];
        $alert = (new libs\BootWrap())->alert($msg, 'success');
        echo $alert;
    } elseif ($registration[0] === false) {
        $msg = $registration[1];
        $alert = (new libs\BootWrap())->alert($msg, 'warning');
        echo $alert;
    }
}
?>