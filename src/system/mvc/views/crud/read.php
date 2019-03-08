<?php

namespace yellowheroes\bugs\system\mvc\views;

use yellowheroes\bugs\system\config as config;
use yellowheroes\bugs\system\mvc\models as models;

//paths to links - used in hrefs
$root = (new config\Config(true))->path['root'];
$crud = (new config\Config(true))->path['crud'];
$create = (new config\Config(true))->path['create'];
$read = (new config\Config(true))->path['read'];
$update = (new config\Config(true))->path['update'];
$delete = (new config\Config(true))->path['delete'];

//get bug-report data from table 'jimmy' in database bugs
$dbTable = config\Config::TBL_JIMMY;
$selectData = (new models\CrudModel())->selectRecord($dbTable, $id);

echo "<div class='row'>";
echo "<div class='col'>" . "<a href='" . $crud . "'>Back to Bug Monitor</a>" . '</div>';
echo "</div>";
echo '<br />';
echo "<hr style='border: 1px solid #FFC000;'>";
echo "<div class='row'>";
echo "<div class='col'>" . "bug id: #" . $selectData['id'] . '</div>';
echo '</div>';
echo "<div class='row'>";
echo "<div class='col'>" . "status: " . $selectData['status'] . '</div>';
echo '</div>';
echo "<div class='row'>";
echo "<div class='col'>" . "severity: " . $selectData['severity'] . '</div>';
echo '</div>';
echo "<hr>";
echo '<br /><br />';
echo "<div class='row'>";
echo "<div class='col font-weight-bold'>" . ucfirst($selectData['title']) . '</div>';
echo '</div>';
echo '<br />';
echo "<div class='row'>";
echo "<div class='col'>" . nl2br($selectData['description']) . '</div>';
echo '</div>';
echo '<br />';
echo "<hr style='border: 1px solid #FFC000'>";
echo '<br />';
echo "<div class='row' style='margin-bottom: 100px;'>";
echo "<div class='col'>" . "<a href='" . $update . "/" . $selectData['id'] . "'>Update bug report</a>" . '</div>';
echo "<div class='col'>" . "<a href='" . $delete . "/" . $selectData['id'] . "'>Delete bug report</a>" . '</div>';
echo '</div>';