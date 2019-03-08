<?php
namespace yellowheroes\bugs\system\mvc\views;

use yellowheroes\bugs\system\config as config;
use yellowheroes\bugs\system\mvc\models as models;

//paths to links - used in hrefs
/*
$root       = (new config\Config())->path['root'];
$crud       = (new config\Config())->path['crud'];
$create     = (new config\Config())->path['create'];
$read       = (new config\Config())->path['read'];
$update     = (new config\Config())->path['update'];
$delete     = (new config\Config())->path['delete'];
*/

//get bug-report data from table 'jimmy' in database bugs
$dbTable  = config\Config::TBL_JIMMY;
$selectData = (new models\CrudModel())->selectRecord($dbTable, $id);

if (isset($_POST['submit'])) {
    $updateData = (new models\CrudModel())->updateRecord($dbTable, $id);
    \header('Location: ' . $crud);
    exit(); // terminate script update.php
}

echo $bootWrap->alert('Update Bug Report', 'primary', false);

/* ['type', 'name', 'id', 'value', 'placeholder', 'label', options[]] */
$inputFields = [
    ['select', 'status', 'status', $selectData['status'], '', 'status', [$selectData['status'], 'new', 'accepted', 'in progress', 'awaiting validation', 'fixed']],
    ['select', 'severity', 'severity', $selectData['severity'], '', 'severity', [$selectData['severity'], 'cosmetic', 'minor', 'major', 'critical', 'suggestion']],
    ['text', 'title', 'title', $selectData['title'], '', 'title', ['required']],
    ['textarea', 'description', 'description', $selectData['description'], '', 'description', ['required']]
];
$form = $bootWrap->form($inputFields, 'Save bug report');

/* verify bug-report status and if it is eligible for update*/
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


