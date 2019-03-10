<?php
namespace yellowheroes\bugs\system\mvc\views;

use yellowheroes\bugs\system\config as config;
use yellowheroes\bugs\system\mvc\models as models;

/*
 * retrieve all bug-report data from your project's bug-table
 * define your bug-database and bug-table in system/config/Config.php
 */
$dbTable = config\Config::TBL_BUGS;
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
            $status = $row['status'];
            $severity = $row['severity'];
            $statusColor = statusColor($status); // get the proper text-color for each bug report's status
            $severityColor = severityColor($severity); // get the proper text-color for each bug report's severity
            echo '<div class="col-1" style="border: 1px solid #FFC000;">' . $row['id'] . '</div>';
            echo "<div class='col-2 text-center " . $statusColor . "' style='border: 1px solid #FFC000;'>" . $row['status'] . '</div>';
            echo "<div class='col-2 text-center " . $severityColor . "' style='border: 1px solid #FFC000;'>" . $row['severity'] . '</div>';
            echo '<div class="col-4" style="border: 1px solid #FFC000;">' . $row['title'] . '</div>';

            echo '<div class="col text-center" style="border: 1px solid #FFC000;">' . "<a href='" . $read . "/" . $row['id'] . "'>Read</a>" . '</div>';
            echo '<div class="col text-center" style="border: 1px solid #FFC000;">' . "<a href='" . $update . "/" . $row['id'] . "'>Update</a>" . '</div>';
            echo '<div class="col text-center" style="border: 1px solid #FFC000;">' . "<a href='" . $delete . "/" . $row['id'] . "'>Delete</a>" . '</div>';
    echo '</div>';
}
/* end content */

function statusColor($status = null)
{
    $textColor = "";

    switch($status) {
        case "new":
            $textColor = "text-danger";
            break;
        case "accepted":
            $textColor = ""; // default (clear grey) font color
            break;
        case "in progress":
            $textColor = "text-secondary";
            break;
        case "awaiting validation":
            $textColor = "text-info";
            break;
        case "fixed":
            $textColor = "text-success";
            break;
    }
    return $textColor;
}

function severityColor($severity = null)
{
    $textColor = "";

    switch($severity) {
        case "cosmetic":
            $textColor = "text-secondary";
            break;
        case "minor":
            $textColor = "text-info";
            break;
        case "major":
            $textColor = "text-warning";
            break;
        case "critical":
            $textColor = "text-danger";
            break;
        case "suggestion":
            $textColor = "";// default (clear grey) font color
            break;
    }
    return $textColor;
}
?>
