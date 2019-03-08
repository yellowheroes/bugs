<?php
namespace yellowheroes\bugs\system\mvc\views;

use yellowheroes\bugs\system\config as config;
use yellowheroes\bugs\system\mvc\models as models;

/*
 * retrieve bug-report data from your project's bug-table
 * define your bug-database and bug-table in system/config/Config.php
 */
$dbTable = config\Config::TBL_BUGS;
$selectData = (new models\CrudModel())->selectRecord($dbTable, $id);

/* delete a bug-report(record) from your project's bug-table */
if (isset($_POST['submit'])) {
    $deleteData = (new models\CrudModel())->deleteRecord($dbTable, $id);
    \header('Location: ' . $crud); // back to 'crud' main view (bug-monitor)
    exit(); // terminate script delete.php
}
$id = $selectData['id'];
$status = $selectData['status'];
$severity = $selectData['severity'];
$title = ucfirst($selectData['title']);
$description = nl2br($selectData['description']); // use nl2br to return string with <br /> or <br> inserted before all newlines (\r\n, \n\r, \n and \r).

/*
 * create a form to allow user to confirm or abort bug-report deletion
 * $crud is the path back to bug-monitor main view (used when user aborts deletion)
 */
$form = $bootWrap->form(null, 'Delete bug report', 'POST', "#", "formId", $crud, "Oops, no thanks");
$warningMsg = "Do you really want to delete the following bug report?";
$warning = $bootWrap->alert($warningMsg, 'warning', false);
$msg = <<<HEREDOC
$warning <br />
bug id: #$id <br />
status: $status <br />
severity: $severity <br /><br />
<hr>
<p class="font-weight-bold">$title</p>
<p>$description</p> <br />
$form
<br /><br />
HEREDOC;

echo $bootWrap->alert($msg, 'primary', false);