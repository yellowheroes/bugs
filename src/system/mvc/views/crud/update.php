<?php
namespace yellowheroes\bugs\system\mvc\views;

use yellowheroes\bugs\system\config as config;
use yellowheroes\bugs\system\mvc\models as models;

/*
 * retrieve bug-report data from your project's bug-table
 * define your bug-database and bug-table in system/config/Config.php
 */
$dbTable  = config\Config::TBL_BUGS;
$selectData = (new models\CrudModel())->selectRecord($dbTable, $id);

if (isset($_POST['submit'])) {
    $updateData = (new models\CrudModel())->updateRecord($dbTable, $id);
    \header('Location: ' . $crud);
    exit(); // terminate script update.php
}

echo $bootWrap->alert('Update Bug Report', 'primary', false);

$inputFields = [
    ['select', 'status', 'status', $selectData['status'], '', 'status', [$selectData['status'], 'new', 'accepted', 'in progress', 'awaiting validation', 'fixed']],
    ['select', 'severity', 'severity', $selectData['severity'], '', 'severity', [$selectData['severity'], 'cosmetic', 'minor', 'major', 'critical', 'suggestion']],
    ['text', 'title', 'title', $selectData['title'], '', 'title', ['required']],
    ['textarea', 'description', 'description', $selectData['description'], '', 'description', ['required']]
];
$form = $bootWrap->form($inputFields, 'Save bug report');

/* verify bug-report status and determine if it is eligible for update*/
$updateAllowed = ($selectData['status'] !== 'fixed') ? true : false;
if($updateAllowed) {
    echo $form;
} else {
    $msg = <<<HEREDOC
Warning <br />
Bug reports with status 'fixed' CAN NOT be updated. <br />
If you have admin credentials, you can change bug report status to allow for update changes.
HEREDOC;
    echo $bootWrap->alert($msg, 'danger', false);
}


