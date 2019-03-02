<?php
namespace yellowheroes\jimmy\system\mvc\views;

use yellowheroes\jimmy\system\config as config;
use yellowheroes\jimmy\system\libs as libs;

/**
 * check if user is logged-in and has appropriate (admin) priviliges
 * - if not, redirect user back to src/app/index
 * 
 * @var $userType     - the variable in header.php that contains userType: admin, editor or guest(visitor) 
 */
if ($userType !== 'admin') {
    /**
     * header.php reads $_SESSION['access_denied'] to determine if user was redirected
     * and, if so, shows an alert to inform user her credentials were insufficient
     * to visit e.g. 'dashboard/index'.
     */
    $session->set('access_denied', 'dashboard_index_php'); 
    \header('Location: ' . $index);
    exit();   
}

/**
 * header
 * function alert($type = 'info', $msg = null, $zIndex = false, $dismiss = true)
 */
$msg = 'Dashboard';
echo "<div class='row' style='margin-left: -15px; font-size: 1.5em;'>";
echo $bootWrap->alert($msg, 'primary', false, null); // dismiss==false - alert is not dismissable, it's a header in this case
echo "</div>";

/**
 * create a select list for Bootswatch theme selection
 */
$themes = ["cerulean", "cosmo", "cyborg", "darkly", "flatly", "journal",
            "litera", "lumen", "lux", "materia", "minty", "pulse", "sandstone",
            "simplex", "sketchy", "slate", "solar", "spacelab", "superhero", "united", "yeti"];
/** $inputFields array that holds an array for each input-field:
 *  [
 *      ['type', 'name', 'id', 'value', 'placeholder', options[]]
 *  ];
 */
$currentTheme = config\Config::BOOTSWATCH_THEME;

array_unshift($themes, $currentTheme); // push the current theme to the start of the themes array - to render it at top of select list
/**
 * $inputFields[]
 *   [0]      [1]    [2]    [3]         [4]         [5]       [6]
 * ['type', 'name', 'id', 'value', 'placeholder', 'label', options[]]   /** added 'label' on 2018/07/03 */

$inputFields = [
    ['select', 'theme', 'theme', '', '', 'Your current theme', $themes]
];
//form($inputFields = [], $submitDisplay = 'submit', $method = 'POST', $action = "#", $formId = "formId", $backHref = false, $confirmationDialog = [false, true])
$themeSelect = $bootWrap->form($inputFields, "Change theme", "post", false, "selectBootSwatchTheme");
echo $themeSelect; // $_POST['theme'] will hold the selected theme
/** default form submit button has field-name 'submit' */
if(isset($_POST['submit'])) {
    /**
     * we decided to overwrite the value in the config file (Config.php)for BootSwatch theme
     */
    $configLocation = dirname(__DIR__, 3); // we need to go up 3 levels up from current directory
    $configFile = $configLocation . '/config/Config.php';
    $configContents = file($configFile); // file() returns an array with each element holding a line of our config.php file

    foreach($configContents as $key => $value) {
        $needle = "const BOOTSWATCH_THEME";
        /** strpos — Find the position of the first occurrence of a substring in a string */
        if(strpos($value, $needle) !== false) {
            $configContents[$key] = "\t" . "const BOOTSWATCH_THEME = '" . $_POST['theme'] . "';\n"; // set the newly selected theme
            $json = json_encode($configContents); // stringify the PHP array to JSON format
            $newConfigContents = json_decode($json); // we have a string back in PHP
            file_put_contents($configFile, $newConfigContents); // write the complete file with the new 'theme' setting to disk as Config.php
            break;
        }
    }
}


/**
 * test the new form BootWrap - works, 2018/07/02
 */
$blogNames = ['code', 'rose', 'something'];
$inputFields = [
    ['select', 'blogname', 'blogname', "", '', $blogNames]
];
/** set the key => value pairs for parameters array we use for BootWrap::newForm() in random order */
$formParams = [
    'inputFields' => $inputFields,
    'submitDisplay' => 'delete',
    'confirmationDialog' => true
];

//echo $bootWrap->newForm($formParams);

//public function form($inputFields = [], $submitDisplay = 'submit', $method = 'POST', $action = "#", $formId = "formId", $backHref = false, $confirmationDialog = false)
/**
 * end test
 */

?>