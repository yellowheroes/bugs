<?php
namespace yellowheroes\projectname\system\mvc\views;

use yellowheroes\projectname\system\config as config;
use yellowheroes\projectname\system\mvc\models as models;
use yellowheroes\projectname\system\libs as libs;

/**
 * check if user is logged-in and has appropriate (admin) priviliges
 * - if not, redirect user back to src/app/index
 * 
 * @var $userType     - the variable in header.php that contains userType: admin, editor or guest(visitor) 
 */
if ($userType !== 'admin') {
    /**
     * header.php reads $_SESSION['access_denied'] to determine if user was redirected
     * and, if so, shows an alert to inform user her credentials were insufficient
     * to visit e.g. 'dashboard/deleteblog'.
     */
    $session->set('access_denied', 'dashboard_deleteblog_php'); // set key, value===location
    \header('Location: ' . $index);
    exit();
}

/**
 *                                title / header
 * function alert($type = 'info', $msg = null, $zIndex = false, $dismiss = true)
 */
$msg = 'Delete a Blog Archive';
echo "<div class='row' style='margin-left: -15px; font-size: 1.5em;'>";
echo $bootWrap->alert('primary', $msg, false, false); // alert is not dismissable, it's a title/header in this case
echo "</div>";

/**
 *                          delete a blog
 * form with one select box
 * 1. all available blog-archive names (the database names)
 * 
 */
$model = new models\BlogModel();
$archiveMetaData = $model->getArchiveMetaData(); // BlogModel extends CoreModel, so we can use CoreModel::getArchiveMetaData() by inheritance
/** $blogName = key, $metaData is values: blog-type and timestamp */
foreach ($archiveMetaData as $blogName => $metaData) {
    $blogNames[] = $blogName;
}
/**
 * $inputFields[]
 *   [0]      [1]    [2]    [3]         [4]         [5]       [6]
 * ['type', 'name', 'id', 'value', 'placeholder', 'label', options[]]   /** added 'label' on 2018/07/03 */

$inputFields = [
    ['select', 'blogname', 'blogname', "", '', 'Blog name', $blogNames]
];
//$confirmationDialog[true, false] means we want a confirmationDialog(true) and a button (false) - if want a href text, set second bool to true as well.
$form = $bootWrap->form($inputFields, 'delete', $method = 'POST', $action = "#", $formId = "formId", $backHref = false, $confirmationDialog = [true, false]); // we substitute the 'submit' button with a BootWrap::confirmationDialog() button
echo $form;

// the submit button 'name'-field of the confirmation dialog is 'confirm'
if (isset($_POST['confirm'])) {
        $deleteBlog = $model->deleteArchive($_POST['blogname']); // BlogModel::deleteArchive()
        if ($deleteBlog !== false) {
            $msg = "Successfully deleted blog!";
            $alert = $bootWrap->alert('success', $msg);
            echo $alert;
        } elseif ($deleteBlog === false) {
            $msg = "We could not delete the blog archive, please try again...";
            $alert = $bootWrap->alert('warning', $msg);
            echo $alert;
        }
}

?>