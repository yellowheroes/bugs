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
$msg = 'Bug Monitor - Project ' . $dbTable;
echo $bootWrap->alert($msg, 'warning', false);
?>

<!-- Page Content -->
<div class="row style='border: 1px solid blue;'">
    <div class="col">
        <a href="<?php echo $create; ?>" class="btn btn-primary">New Bug Report</a>
    </div>
</div>
<br/>
<!-- header -->
<div class="row"> <!-- start header row -->
    <div class="col align-text-bottom">ID</div>
    <div class="col text-center">Status</div>
    <div class="col text-center">Title</div>
    <div class="col"></div> <!-- we need three empty col's to align the header with the content -->
    <div class="col"></div> <!-- we need three empty col's to align the header with the content -->
    <div class="col"></div> <!-- we need three empty col's to align the header with the content -->
</div> <!-- end header row -->

<?php
/* content */
foreach ($selectData as $row) {
    echo "<div class='row'>";
            echo '<div class="col" style="border: 1px solid #FFC000;">' . $row['id'] . '</div>';
            echo '<div class="col" style="border: 1px solid #FFC000;">' . $row['status'] . '</div>';
            echo '<div class="col" style="border: 1px solid #FFC000;">' . $row['title'] . '</div>';

            echo '<div class="col" style="border: 1px solid #FFC000;">' . "<a href='" . $read . "/read/" . $row['id'] . "'>Read</a>" . '</div>';
            echo '<div class="col" style="border: 1px solid #FFC000;">' . "<a href='" . $update . "/update/" . $row['id'] . "'>Update</a>" . '</div>';
            echo '<div class="col" style="border: 1px solid #FFC000;">' . "<a href='" . $delete . "/delete/" . $row['id'] . "'>Delete</a>" . '</div>';
    echo '</div>';
}
/* end content */
?>
