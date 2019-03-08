<?php
namespace yellowheroes\bugs\system\mvc\views;

use yellowheroes\bugs\system\config as config;
use yellowheroes\bugs\system\mvc\models as models;

//paths to links - used in hrefs
$crud = (new config\Config(true))->path['crud'];
$create = (new config\Config(true))->path['create'];
$read = (new config\Config(true))->path['read'];
$update = (new config\Config(true))->path['update'];
$delete = (new config\Config(true))->path['delete'];

$link_logout = $logout;
$show_logout = 'logout';

//get data from table 'jimmy' in database
$dbTable = config\Config::TBL_JIMMY;
$selectData = (new models\CrudModel())->selectAll($dbTable);
$msg = <<<HEREDOC
Bug Monitor <br />
Project: $dbTable
HEREDOC;
/*
 * we need to adjust the alert -15px to the left,
 * to align with the bug-monitor table
 */
echo "<div class='row'>";
echo "<div class='col' style='margin-left: -15px;'>";
echo $bootWrap->alert($msg, 'primary', false);
echo "</div>";
echo "</div>";
?>
<!-- same here, adjust -15px to align with the bug monitor table -->
<div class="row">
    <div class="col" style="margin-left: -15px;">
        <a href="<?php echo $create; ?>" class="btn btn-primary">New Bug Report</a>
    </div>
</div>
<br /><br />
<!-- header -->
<div class="row"> <!-- start header row -->
    <div class="col-1 bg-primary">ID</div>
    <div class="col-2 bg-primary text-center">Status</div>
    <div class="col-2 bg-primary text-center">Severity</div>
    <div class="col-4 bg-primary text-center">Title</div>
    <div class="col bg-primary"></div> <!-- we need three empty col's to align the header with the content -->
    <div class="col bg-primary"></div> <!-- we need three empty col's to align the header with the content -->
    <div class="col bg-primary"></div> <!-- we need three empty col's to align the header with the content -->
</div> <!-- end header row -->

<?php
/* content */
foreach ($selectData as $row) {
    echo "<div class='row'>";
            echo '<div class="col-1" style="border: 1px solid #FFC000;">' . $row['id'] . '</div>';
            echo '<div class="col-2" style="border: 1px solid #FFC000;">' . $row['status'] . '</div>';
            echo '<div class="col-2" style="border: 1px solid #FFC000;">' . $row['severity'] . '</div>';
            echo '<div class="col-4" style="border: 1px solid #FFC000;">' . $row['title'] . '</div>';

            echo '<div class="col" style="border: 1px solid #FFC000;">' . "<a href='" . $read . "/" . $row['id'] . "'>Read</a>" . '</div>';
            echo '<div class="col" style="border: 1px solid #FFC000;">' . "<a href='" . $update . "/" . $row['id'] . "'>Update</a>" . '</div>';
            echo '<div class="col" style="border: 1px solid #FFC000;">' . "<a href='" . $delete . "/" . $row['id'] . "'>Delete</a>" . '</div>';
    echo '</div>';
}
/* end content */
?>
