<?php
namespace yellowheroes\bugs\system\mvc\views;

use yellowheroes\bugs\system\config as config;
use yellowheroes\bugs\system\libs as libs;

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

/*
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
$root = $config->path['root'];
$index = $config->path['index'];    // home
$contact = $config->path['contact'];    // contact
$dashboard = $config->path['dashboard'];    // admin
$register = $config->path['register']; // add a new user account
$deregister = $config->path['deregister']; // remove an existing user account
$login = $config->path['login'];
$logout = $config->path['logout'];

/**
 * the CRUD functionality for bugs management
 */
$crud = $config->path['crud'];

$bootSwatchTheme = config\Config::BOOTSWATCH_THEME;
$bootSwatchCss = "https://maxcdn.bootstrapcdn.com/bootswatch/4.0.0/" . $bootSwatchTheme . "/bootstrap.min.css";

$fontAwesomeCss = <<<HEREDOC
https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous
HEREDOC;

$superCss = $css . '/super.css';
$stickyFooter = $css . '/sticky-footer-navbar.css';
$scrollToTopCss = $css .'/scroll-to-top.css';
/*
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

/*
 * Javascript / jQuery (plugins)
 * - Highlightjs: syntax highlighting
 * - Bootstrap tooltips
 * - Bootstrap dropdowns
 */

/* we prefer Highlight.js over Prism */
$syntaxHighlight = <<<HEREDOC
<!-- START - Use Prism for syntax higlighting -->
        <script src="https://cdn.rawgit.com/zenorocha/clipboard.js/v1.7.1/dist/clipboard.min.js"></script>
        <script src="$javascript/prism.js"></script>
        <link rel="stylesheet" type="text/css" href="$css/prism.css">
        <!-- STOP - Use Prism for syntax higlighting -->\n
HEREDOC;

$highlightJs = <<<HEREDOC
<!-- START - Use Highlight.js for syntax higlighting -->
<link rel="stylesheet" href="$css/highlightjs_styles/atom_one_dark.css">
<script src="$javascript/highlight.pack.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<!-- STOP - Use Highlight.js for syntax higlighting -->\n
HEREDOC;


/*
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

/* smooth scroll to anchors in content */
$listOffset = 0; // we do NOT want scrolling in list-view
$articleOffset = -150; // we DO want scrolling in article-view
$offset = (isset($id)) ? $articleOffset : $listOffset; // if user selected an article, allow anchor scrolling.
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
      // The optional number (1000) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body, textarea').animate({
        scrollTop: $(hash).offset().top $offset
      }, 1000, function(){
        // Add hash (#) to URL when done scrolling (default click behavior)
        //window.location.hash = hash;
      });
    } // End if
  });
});
</script>
HEREDOC;

$listAnchors = <<<HEREDOC
<script>
$(window).scrollTo("250px");
</script>
HEREDOC;

/* if we have a blog-archive list view
 * i.e. no article with a $id was selected,
 * then do not activate anchor scrolling
 * because it causes bug #0001:
 * confirmation dialog scrolls out of view
 * when user selects 'delete blog article' from list.
 */
$anchors = (isset($id)) ? $anchors : $listAnchors; // use $anchors scrolling in single article view, use $listAnchors in blog-list-view.

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

/*
 * Other: set favicon
 */
$logoImage = $images . '/yh_logo.png';
$favicon = <<<HEREDOC
<link rel="icon" type="image/png" href="$logoImage" sizes="16x16" />
HEREDOC;

/*
 * generate the html <head> </head> block
 */
$bootWrap = new libs\BootWrap();
$setJs = $bootWrap->setJs([$highlightJs, $toolTips, $dropDowns, $anchors, $scrollToTop]);
$setOther = $bootWrap->setOther($favicon);
$tabTitle = config\Config::ORGNAME;
/*
 * $headHtml : all html content that sits between <head> ... </head> tags
 */
$headHtml = $bootWrap->head($tabTitle, $styleSheets);
echo $headHtml;

/*
 *                                      IMPORTANT
 * 
 *                          --- DETERMINE USER LOG-STATUS ---
 * 
 * We use class SessionManager to keep track of log-status - we currently have 3 user types:
 * - admin  = highest level privileges (ALL)
 * - editor = can view/edit public/non-public content
 * - guest  = visitor, is not a registered user, can only view publicly available content
 * 
 * Access Privileges:
 * 1. navigation bar composition depends on user type (access privileges)
 * 2. restricted-access view-pages we double-check if user has clearance (user can always insert a route manually)
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

/*
 * set appropriate navigation buttons dependent on the user type: guest / editor / admin
 * 
 * re. admin account:
 * we insert an array to create a 'dashboard' dropdown menu
 * a divider (grey horizontal ruler) is inserted with ''=>'' as $key=>$value (see below)
 */
$guest = ['home' => $index, 'contact' => $contact, 'login' => $login]; // guest only has access to 'shared' blog
$editor = ['home' => $index, 'bugs' => $crud, 'contact' => $contact, 'logout' => $logout];
$admin = ['home' => $index, 'bugs' => $crud, 'contact' => $contact,
          'admin' => ['register new user' => $register,
                      'remove existing user' => $deregister,
                      'hr1' => '',
                      'dashboard' => $dashboard],
                      'logout' => $logout
];

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

/*
 * set breadcrumbs (prepend variables with: 'bc')
 * we can retrieve $viewsDir and $page here as they are
 * arguments in each controller invoking:
 * $this->view->render($viewsDir, $page, $id);
 */
$bcViewsDir = ($viewsDir !== 'index') ? (($viewsDir !== 'crud') ? $viewsDir : 'bugs') : 'home'; // because we call index 'home' and crud 'bugs'

$bcPage = ($page !== 'index') ? $page : '';
// we do not show view 'blog/template' ('template.php' is the view through which we funnel all blogs)
// but show actual blog-name ($param1 from CoreView::render()). 
$bcPage = ($page !== 'template') ? ($bcPage !== '' ? $page : '') : $param1;
$location = ($bcPage !== '') ? $bcViewsDir . ' / ' . $bcPage : $bcViewsDir;
$toolTip[1] = 'your are currently on page: ' . $location;

/*
 * generate the navbar html
 */
$org = config\Config::ORGNAME;
$logoHref = $root;
$logo = [$org, $logoImage, $logoHref];
/** we only want search functionality for the blog list overview ($id is only set if a specific article is rendered) */
if ($bcViewsDir === 'blog' && !isset($id)) {
    $search = true;
} else {
    $search = false;
}
$activeNav = $bcViewsDir;
$navBar = $bootWrap->navBar($navItems, $activeNav, null, 'primary', 'sm', 'dark', 'dark', 'top', $logo, $userName, $toolTip, $location, $search = false);

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

/*
 * check if user was redirected due to insufficient access privileges:
 * - access_denied (e.g. a guest trying to enter an admin or editor area)
 */
$redirect = $session->get('access_denied');
if ($redirect) {
$redirectLocation = explode('_', $redirect);
$redirectViewDir = $redirectLocation[0] ?? '';
$redirectViewPage = $redirectLocation[1] ?? '';
$msg = "You were redirected - your access privileges are insufficient to visit location: " . $redirectViewDir . "/" . $redirectViewPage;
echo $bootWrap->alert($msg, 'info');
$session->remove('access_denied'); // unset key 'access_denied'
}

?>