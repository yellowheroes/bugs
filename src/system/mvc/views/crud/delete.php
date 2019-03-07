<?php
namespace yellowheroes\bugs\system\mvc\views;

use yellowheroes\bugs\system\config as config;
use yellowheroes\bugs\system\mvc\models as models;

//get bug-report data from table 'jimmy' in database bugs
$dbTable = config\Config::TBL_JIMMY;
$selectData = (new models\CrudModel())->selectRecord($dbTable, $id);

//delete a bug-report(record) from table 'jimmy' in database bugs
if (isset($_POST['submit'])) {
    $deleteData = (new models\CrudModel())->deleteRecord($dbTable, $id);
    \header('Location: ' . $crud); // back to 'crud' main view
    exit(); // terminate script delete.php
}
$id = $selectData['id'];
$status = $selectData['status'];
$title = $selectData['title'];
$description = nl2br($selectData['description']);
/*
 * create a form to allow user to confirm or abort bug-report deletion
 * $crud is the path back to bug-monitor main view (used when user aborts deletion)
 */
$form = $bootWrap->form(null, 'Delete bug report', 'POST', "#", "formId", $crud, "Oops, no thanks");
$msg = <<<HEREDOC
Do you really want to delete the following bug report:<br /><br />
bug id: #$id <br />
status: $status <br /><br />
title: $title <br /><br />
description:<br />$description <br />
$form
<br /><br />
HEREDOC;

echo $bootWrap->alert($msg, 'warning', false);