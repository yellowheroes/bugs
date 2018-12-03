<?php
namespace yellowheroes\projectname\system\mvc\views;

use yellowheroes\projectname\system\config as config;
use yellowheroes\projectname\system\libs as libs;

/**
 * start a secure session (or continue with started session)
 */
$session = new libs\SessionManager();
$session->start();

/**
 * get access to a config object
 * and get the paths ('true')
 */
$config = new config\Config(true);

/**
 * get access to all flat file databases
 * by instantiating the CoreModel
 */
$db = new libs\CoreModel();
$usersDb = $db->usersDb;        // connection to flat-file database '_users'
$sessionDb = $db->sessionDb;    // connection to flat-file database 'session'
$settingsDb = $db->settingsDb;  // connection to flat-file database 'settings'

//paths to assets/resources - used in hrefs
$css = $config->path['css'];
$javascript = $config->path['javascript'];
$images = $config->path['images'];

//paths to view-dirs and view-pages - used in hrefs
$index = $config->path['index'];    // home
$blog = $config->path['blog'];      // blog
$quill = $config->path['quill'];    // quill
$edit = $config->path['editarticle'];
$delete = $config->path['deletearticle'];
$store = $config->path['storearticle'];

$chat = $config->path['chat']; // chat box
$storechat = $config->path['storechat'];
$updatechat = $config->path['updatechat'];

$contact = $config->path['contact'];    // contact
$dashboard = $config->path['dashboard'];    // admin
$register = $config->path['register']; // add a new user account
$deregister = $config->path['deregister']; // remove an existing user account
$createblog = $config->path['createblog'];
$deleteblog = $config->path['deleteblog'];
$login = $config->path['login'];
$logout = $config->path['logout'];

/**
 * careful some of these variables are used by CkEditor now (e.g. $delete)
 */
//$crud = $config->path['crud'];
//$create = $config->path['create'];
//$delete = $config->path['delete'];
//$read = $config->path['read'];
//$update = $config->path['update'];

$root = $config->path['root'];

/**
 * render html head-block Bootstrap compliant
 * - CSS
 * - Javascript / jQuery (libs/plugins)
 * - Other
 */
/**
 * CSS
 *
 * Bootswatch themes -  there are currently 21 themes
 *                      user type 'admin' can select to change theme in dashboard
 *                      the theme is recorded in a session variable
 *                      the default theme is recorded in a constant in config\Config::BOOTSWATCH_THEME
 */
$bootSwatchTheme = config\Config::BOOTSWATCH_THEME;
$bootSwatchCss = "https://maxcdn.bootstrapcdn.com/bootswatch/4.0.0/" . $bootSwatchTheme . "/bootstrap.min.css";
//$bootstrapCustomizeCss = $css . '/customize-bootstrap.css';
$fontAwesomeCss = <<<HEREDOC
https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous
HEREDOC;

$superCss = $css . '/super.css';
$stickyFooter = $css . '/sticky-footer-navbar.css';
$scrollToTopCss = $css .'/scroll-to-top.css';
    /**
 * We use Google Font API to allow for setting a font with in-line css on DOM elements (see e.g. view-page blog/index.php)
 * To request multiple font families, separate the names with a pipe character (|). Use a + sign if space in name.
 * For example, to request the fonts Tangerine, Inconsolata, and Droid Sans:
 * https://fonts.googleapis.com/css?family=Tangerine|Inconsolata|Droid+Sans
 * the more fonts you request, the slower the page-loads will be...
 */
$googleFontFamily = config\Config::FONT_FAMILY;
$googleFontWeight = config\Config::FONT_WEIGHT;
$blogFontSize = "16px";
$googleFontsCss = "https://fonts.googleapis.com/css?family={$googleFontFamily}:{$googleFontWeight}";
$styleSheets = [$fontAwesomeCss, $googleFontsCss, $stickyFooter, $bootSwatchCss, $superCss, $scrollToTopCss];

/**
 * Javascript / jQuery (plugins)
 * - Prism: syntax highlighting (we still use Prism in Repository, but should change to higlight.js - as used in Quill Editor)
 * - Quill: rich text editor
 * - Bootstrap tooltips
 * - $chatRefresh - refesh the chat box every 2 or 3 seconds (this script is only invoked in VIEW chat/index.php)
 */

/*
 * use xhr AJAX polling
 */
$chatRefresh = <<<HEREDOC
<script>
        function chatUpdate() {
            var xhr = new XMLHttpRequest();
            var updatechat = "$updatechat";
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    if(xhr.responseText != '') { /* empty response is sent back if no new chats were posted */
                    document.getElementById('chatbox').innerHTML = xhr.responseText;
                    }
                }
            }
            xhr.open('GET', updatechat, true);
            xhr.send();
        }
        setInterval(function () {
            chatUpdate()
        }, 3000)
    </script>
HEREDOC;
$chatRefresh = ($viewsDir === 'chat') ? $chatRefresh : ''; // only invoke chat refresh script in VIEW chat/index.php


$syntaxHiglight = <<<HEREDOC
<!-- START - Use Prism for syntax higlighting code in our repository -->
        <script src="https://cdn.rawgit.com/zenorocha/clipboard.js/v1.7.1/dist/clipboard.min.js"></script>
        <script src="$javascript/prism.js"></script>
        <link rel="stylesheet" type="text/css" href="$css/prism.css">
        <!-- STOP - Use Prism for syntax higlighting code in our repository -->\n
HEREDOC;

$highlightJsTheme = config\Config::HIGHLIGHTJS_THEME;
$textEditor = <<<HEREDOC
<!-- START - ckeditor -->
        <script src="$javascript/ckeditor/ckeditor.js"></script>
		<link href="$javascript/ckeditor/plugins/codesnippet/lib/highlight/styles/atom-one-dark.css" rel="stylesheet">
        <script src="$javascript/ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js"></script>
        <script>hljs.initHighlightingOnLoad();</script>
HEREDOC;

/**
 * enable Bootstrap tooltips
 * use following in your <a> or <button> or <div> or <span> element to enable: 
 * data-toggle="tooltip" data-placement="auto" title="whatever text you wanna show"
 */
$toolTips = <<<HEREDOC
        <!-- START - enable Bootstrap tooltips -->
        <script>
        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        });
        </script>
        <!-- STOP - enable Bootstrap tooltips -->\n
HEREDOC;

$dropDowns = <<<HEREDOC
        <!-- START - enable Bootstrap dropdowns -->
        <script>
        $(function () {
            $('.dropdown-toggle').dropdown()
         });
        </script>
        <!-- STOP - enable Bootstrap dropdowns -->\n
HEREDOC;

/* smooth scroll to anchors in articles */
$anchors = <<<HEREDOC
<script>
$(document).ready(function(){
  // Add smooth scrolling to all links
  $("a").on('click', function(event) {

    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {
      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;

      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (3800) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body, textarea').animate({
        scrollTop: $(hash).offset().top - 150
      }, 3800, function(){
   
        // Add hash (#) to URL when done scrolling (default click behavior)
        //window.location.hash = hash;
      });
    } // End if
  });
});
</script>
HEREDOC;

/* a scroll to top button appears in bottom-right corner screen if we scroll down */
$scrollToTopButton = <<<HEREDOC
<a href="#" id="scroll" style="display: none;"><span></span></a> <!-- empty scroll-to-top target -->
HEREDOC;
/* the scroll-to-top jquery animation */
$scrollToTop = <<<HEREDOC
<script>
$(document).ready(function(){ 
    $(window).scroll(function(){ 
        if ($(this).scrollTop() > 100) { 
            $('#scroll').fadeIn(); 
        } else { 
            $('#scroll').fadeOut(); 
        } 
    }); 
    $('#scroll').click(function(){ 
        $("html, body").animate({ scrollTop: 0 }, 600); 
        return false; 
    }); 
});
</script>
HEREDOC;

/**
 * Other: set favicon
 */
$logoImage = $images . '/yh_logo.png';
$favicon = <<<HEREDOC
<link rel="icon" type="image/png" href="$logoImage" sizes="16x16" />
HEREDOC;

/**
 * generate the html <head> </head> block
 */
$bootWrap = new libs\BootWrap();
$setJs = $bootWrap->setJs([$textEditor, $toolTips, $dropDowns, $anchors, $scrollToTop, $chatRefresh]);
$setOther = $bootWrap->setOther($favicon);
$tabTitle = 'yellow heroes';
/**
 * @var $headHtml all html content that sits between <head> ... </head> tags
 */
$headHtml = $bootWrap->head($tabTitle, $styleSheets);
echo $headHtml;

/**
 *                      IMPORTANT
 * 
 *              --- DETERMINE USER LOG-STATUS ---
 * 
 * We use class SessionManager to keep track of log-status - we currently have 3 user types:
 * - admin  = highest level privileges (ALL)
 * - editor = can view/edit public/non-public content
 * - guest  = visitor, is not a registered user, can only view publicly available content
 * 
 * Access Privileges:
 * 1. navigation bar composition depends on user type (access privileges)
 * 2. restricted-access view-pages we double-check if user has clearance (client can always insert a route manually)
 * 
 * Log-Status is stored in $_SESSION['log-status']:   
 * ["log-status"]=>
 *                  array(3) {
 *                  ["loggedin"]=>bool(false)
 *                  ["username"]=>string(5) "guest"
 *                  ["usertype"]=>string(5) "guest"
 *                  }
 */
$logStatus = $session->get('log-status'); // returns array with 3 elements: BOOLEAN 'loggedin', STRING 'username' and STRING 'usertype'

/**
 * set appropriate navigation buttons dependent on the user type: guest / editor / admin
 * 
 * re. admin account:
 * we insert an array to create a 'dashboard' dropdown menu
 * a divider (grey horizontal ruler) is inserted with ''=>'' as $key=>$value (see below)
 */
$guest = ['home' => $index, 'contact' => $contact, 'login' => $login]; // guest access
$editor = ['home' => $index, 'contact' => $contact, 'logout' => $logout];
$admin = ['home' => $index, 'contact' => $contact,
        'admin' => ['register new user' => $register,
                    'remove existing user' => $deregister, 'hr1' => '',
                    'empty' => $dashboard,
                    'empty' => $dashboard, 'hr2' => '',
                    'dashboard' => $dashboard],
        'logout' => $logout];

$toolTip = [];
if ($logStatus['loggedin']) {
    if ($logStatus['usertype'] === 'admin') {
        $navItems = $admin;
        $userType = 'admin';
        $userName = $logStatus['username'];
        $toolTip[0] = 'you have top level access priviliges';
    } else {
        $navItems = $editor;
        $userType = 'editor';
        $userName = $logStatus['username'];
        $toolTip[0] = 'you have public and private content access and editor priviliges';
    }
} else {
    $navItems = $guest;
    $userType = 'guest';
    $userName = 'guest';
    $toolTip[0] = 'you have access to public content only - you must be registered to login';
}

/**
 * set breadcrumbs (prepend variables with: 'bc')
 * we can retrieve $viewsDir and $page here as it is set as first argument in each controller invoking
 * $this->view->render($viewsDir, $page, $id);
 */
$bcViewsDir = ($viewsDir !== 'index') ? $viewsDir : 'home'; // because we call index 'home'
$bcPage = ($page !== 'index') ? $page : '';
// we do not show view 'blog/template' ('template.php' is the view through which we funnel all blogs)
// but show actual blog-name ($param1 from CoreView::render()). 
$bcPage = ($page !== 'template') ? ($bcPage !== '' ? $page : '') : $param1;
$location = ($bcPage !== '') ? $bcViewsDir . ' / ' . $bcPage : $bcViewsDir;
$toolTip[1] = 'your are currently on page: ' . $location;

/**
 * generate the navbar html
 */
$logoHref = $root;
$logo = ['Yellow Heroes', $logoImage, $logoHref];
/** we only want search functionality for the blog list overview ($id is only set if a specific article is rendered) */
if ($bcViewsDir === 'blog' && !isset($id)) {
    $search = true;
} else {
    $search = false;
}
$activeNav = $bcViewsDir;
$navBar = $bootWrap->navBarEnhanced($navItems, $activeNav, null, 'primary', 'sm', 'dark', 'dark', 'top', $logo, $userName, $toolTip, $location, $search = false);

?>
<!-- render nav-bar in <header></header> -->
<body>
    <header>
        <?php echo $navBar; ?>
    </header>

<!-- begin page content - determine container type 'container' or 'container-fluid'===full width -->
<?php
$container = <<<HEREDOC
<main role="main" class="container">\n
HEREDOC;

$containerFluid = <<<HEREDOC
<main role="main" class="container-fluid">\n
HEREDOC;

echo $container;

echo $scrollToTopButton; // automatically appears on all pages with content exceeding screen height

/**
* check if user was redirected due to insufficient access privileges:
* - access_denied (e.g. a guest trying to enter an admin or editor area)
*/
$redirect = $session->get('access_denied');
if ($redirect) {
$redirectLocation = explode('_', $redirect);
$redirectViewDir = $redirectLocation[0] ?? '';
$redirectViewPage = $redirectLocation[1] ?? '';
$msg = "You were redirected - your access privileges are insufficient to visit location: " . $redirectViewDir . "/" . $redirectViewPage;
echo $bootWrap->alert('info', $msg);
$session->remove('access_denied'); // unset key 'access_denied'
}

?>