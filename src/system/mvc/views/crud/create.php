<?php
namespace yellowheroes\bugs\system\mvc\views;

use yellowheroes\bugs\system\config as config;
use yellowheroes\bugs\system\mvc\models as models;

/*
 * insert bug-report into your project's bug-table
 * define your bug-database and bug-table in system/config/Config.php
 */
$dbTable = config\Config::TBL_BUGS;
if (isset($_POST['submit'])) {
    $insertData = (new models\CrudModel())->insertRecord($dbTable);
    \header("Location: " . $crud);
    exit(); // terminate script create.php
}

$inputFields = [
    ['select', 'severity', 'severity', '', '', 'bug report severity', ['cosmetic', 'minor', 'major', 'critical', 'suggestion']],
    ['text', 'title', 'title', "", 'enter title', 'bug report title', ['required']],
    ['textarea', 'description', 'description', "", 'description', 'description', ['required']]
];
$form = $bootWrap->form($inputFields, 'Submit bug report');
echo $form;
