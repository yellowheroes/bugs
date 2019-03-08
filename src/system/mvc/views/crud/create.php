<?php
namespace yellowheroes\bugs\system\mvc\views;

use yellowheroes\bugs\system\config as config;
use yellowheroes\bugs\system\mvc\models as models;

//paths to links - used in header or hrefs
$crud = (new config\Config(true))->path['crud'];

//database bugs, table 'jimmy'
$dbTable = config\Config::TBL_JIMMY;
if (isset($_POST['submit'])) {
    $insertData = (new models\CrudModel())->insertRecord($dbTable);
    \header("Location: " . $crud);
    exit(); // terminate script create.php
}

/* ['type', 'name', 'id', 'value', 'placeholder', 'label', options[]] */
$inputFields = [
    ['select', 'severity', 'severity', '', '', 'bug report severity', ['cosmetic', 'minor', 'major', 'critical', 'suggestion']],
    ['text', 'title', 'title', "", 'enter title', 'bug report title', ['required']],
    ['textarea', 'description', 'description', "", 'description', 'description', ['required']]
];
$form = $bootWrap->form($inputFields, 'Submit bug report');
echo $form;
