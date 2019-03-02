<?php
namespace yellowheroes\jimmy\system\mvc\views;

use yellowheroes\jimmy\system\config as config;
use yellowheroes\jimmy\system\mvc\models as models;
use yellowheroes\jimmy\system\libs as libs;

/**
 * check if user is logged-in and has appropriate (admin) priviliges
 * - if not, redirect user back to src/app/index
 * - header.php reads $_SESSION['access_denied'] to determine if user was redirected
 * and, if so, shows an alert to inform user her credentials were insufficient
 * to visit e.g. 'dashboard/createblog'.
 * 
 * @var $userType     - the variable in header.php that contains userType: admin, editor or guest(visitor) 
 */
if ($userType !== 'admin') {
    $session->set('access_denied', 'dashboard_createblog_php'); // set key, value===location
    \header('Location: ' . $index);
    exit();
}

/**
 *                                title / header
 * function alert($type = 'info', $msg = null, $zIndex = false, $dismiss = true)
 */
$msg = 'Create a New Blog Archive';
echo "<div class='row' style='margin-left: -15px; font-size: 1.5em;'>";
echo $bootWrap->alert($msg, 'primary', false, null); // alert is not dismissable, it's a title/header in this case
echo "</div>";

/**
 *                          create a new blog
 * form with two inputs
 * 1. blog-archive name (the database name)
 * 2. shared / private (private blogs can only be accessed by users with editor / admin credentials)
 * 
 * $inputFields       array that holds an array for each input-field: ['type', 'name', 'id', 'value', 'placeholder', options[]]
 */
/**
 * $inputFields[]
 *   [0]      [1]    [2]    [3]         [4]         [5]       [6]
 * ['type', 'name', 'id', 'value', 'placeholder', 'label', options[]]   /** added 'label' on 2018/07/03 */

$inputFields = [
    ['text', 'blogname', 'blogname', "", 'enter blog archive name', 'Blog name', ['required']],
    ['select', 'blogtype', 'blogtype', "", '', 'Blog type', ['shared', 'private']]
];
$form = $bootWrap->form($inputFields, 'New Blog Archive');
echo $form;

if (isset($_POST['submit'])) {
    $createBlog = (new models\BlogModel())->createArchive($_POST['blogname'], $_POST['blogtype']);
    if ($createBlog !== false) {
        $msg = "Successfully created your new blog!";
        $alert = (new libs\BootWrap())->alert($msg, 'success');
        echo $alert;
    } elseif ($createBlog === false) {
        $msg = "That blog already exists, please try again...";
        $alert = (new libs\BootWrap())->alert($msg, 'warning');
        echo $alert;
    }
}

?>