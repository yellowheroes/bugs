<?php

namespace yellowheroes\jimmy\system\mvc\views;

use yellowheroes\jimmy\system\config as config;
use yellowheroes\jimmy\system\mvc\models as models;
use yellowheroes\jimmy\system\libs as libs;

/**
 * check if user is logged-in and has appropriate (admin) priviliges
 * - if not, redirect user back to src/app/index
 *
 * @param $userType - the variable in header.php that contains userType: admin, editor or guest(visitor)
 */
if ($userType !== 'admin') {
    /**
     * header.php reads $_SESSION['access_denied'] to determine if user was redirected
     * and, if so, shows an alert to inform user her credentials were insufficient
     * to visit e.g. 'dashboard/index'.
     */
    $session->set('access_denied', 'dashboard_deregister_php');
    header('Location: ' . $index);
    exit();
}

/**
 *                                title / header
 * function alert($type = 'info', $msg = null, $zIndex = false, $dismiss = true)
 */
$msg = 'Remove a user account';
echo "<div class='row' style='margin-left: -15px; font-size: 1.5em;'>";
echo $bootWrap->alert($msg, 'primary', false, null); // alert is not dismissable, it's a title/header in this case
echo "</div>";

/**
 *                          deregister a user
 * we use the verb 'deregister' to indicate the removal of a registered user from the database
 * following the logic: state=unregistered -> 'register user' -> state=registered -> 'deregister user' -> state=unregistered
 *
 * form with one select box
 * 1. all available user names (source _users.yel flat file database)
 *
 */
$model = new models\LoginModel();
$userNames = $model->getUsers(); // returns all the user names in the database '_users.yel'
/**
 * $inputFields[]
 *   [0]      [1]    [2]    [3]         [4]         [5]       [6]
 * ['type', 'name', 'id', 'value', 'placeholder', 'label', options[]]   /** added 'label' on 2018/07/03 */

$inputFields = [
    ['select', 'username', 'username', "", '', 'User name', $userNames]
];
//$confirmationDialog[true, false] means we want a confirmationDialog(true) and a button (false) - if want a href text, set second bool to true as well.
$form = $bootWrap->form($inputFields, 'delete', $method = 'POST', $action = "#", $formId = "deregisterUser", $backHref = false, $backDisplay = 'Back', $confirmationDialog = [true, false]); // we substitute the 'submit' button with a BootWrap::confirmationDialog() button
echo $form;
// the submit button 'name'-field of the confirmation dialog is 'confirm' by default
if (isset($_POST['confirm'])) {
    $deregistration = (new models\LoginModel())->deregisterUser();
    if ($deregistration[0] !== false) {
        $msg = $deregistration[1];
        $alert = (new libs\BootWrap())->alert($msg, 'success');
        echo $alert;
    } elseif ($deregistration[0] === false) {
        $msg = $deregistration[1];
        $alert = (new libs\BootWrap())->alert($msg, 'warning');
        echo $alert;
    }
}

?>