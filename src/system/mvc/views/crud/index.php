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

/* tooltips */
$statusToolTip = <<<HEREDOC
The bug status shows where a bug lives in its lifecycle:<br />
<strong>new</strong> (reported),<br />
<strong>accepted</strong> (the issue can be reproduced),<br />
<strong>in progress </strong>(someone is working on the issue),<br />
<strong>awaiting validation </strong>(a solution is filed for approval)<br />
<strong>fixed </strong>(the solution is approved and incorporated in the code base)
HEREDOC;
$severityToolTip = <<<HEREDOC
Depending on how serious the bug is, this indicator ranges from:<br />
<strong>suggestion </strong>(a new feature, some improvement),<br />
<strong>cosmetic </strong>(e.g. UI enhancements),<br />
<strong>minor </strong>(the bug affects a minor feature of the app),<br />
<strong>major </strong>(the bug affects a major feature of the app)<br />
<strong>critical </strong>(the bug crashes the app, or causes loss of data)
HEREDOC;
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
    <div class="col-1 bg-primary">Created</div>
    <div class="col-2 bg-primary text-center" data-toggle="tooltip" data-html="true" data-placement="top" title="<?php echo $statusToolTip; ?>">Status</div>
    <div class="col-2 bg-primary text-center" data-toggle="tooltip" data-html="true" data-placement="top" title="<?php echo $severityToolTip; ?>">Severity</div>
    <div class="col-3 bg-primary text-center">Title</div>
    <div class="col bg-primary"></div> <!-- we need three empty col's to align the header with the content -->
    <div class="col bg-primary"></div> <!-- we need three empty col's to align the header with the content -->
    <div class="col bg-primary"></div> <!-- we need three empty col's to align the header with the content -->
</div> <!-- end header row -->

<?php
/* content */
foreach ($selectData as $row) {
    echo "<div class='row'>";
            $created = date('Y-m-d', strtotime($row['created'])); // only show Y-m-d, not time
            $status = $row['status'];
            $severity = $row['severity'];
            $statusColor = statusColor($status); // get the proper text-color for each bug report's status
            $severityColor = severityColor($severity); // get the proper text-color for each bug report's severity
            echo '<div class="col-1" style="border: 1px solid #FFC000;">' . $row['id'] . '</div>';
            echo '<div class="col-1 text-center" style="border: 1px solid #FFC000;">' . $created . '</div>';
            echo "<div class='col-2 text-center " . $statusColor . "' style='border: 1px solid #FFC000;'>" . $status . '</div>';
            echo "<div class='col-2 text-center " . $severityColor . "' style='border: 1px solid #FFC000;'>" . $severity . '</div>';
            echo '<div class="col-3 text-white" style="border: 1px solid #FFC000;">' . $row['title'] . '</div>';

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
            $textColor = ""; // default (clear grey) font color
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
            $textColor = "text-secondary";
            break;
    }
    return $textColor;
}
?>
