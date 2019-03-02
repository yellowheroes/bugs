<?php
namespace yellowheroes\projectname\system\config;

/**
 * DEVELOPMENT ENVIRONMENT
 */

class Config
{
    public $root;
    public $path = [];
    
    /** session configuration */
    public $sessionConfig = [];

    /* your company / organisation / brand */
    const ORGNAME = "Yellow Heroes";            // set your company (brand) or organisation name
    
    /* production environment */
    const PRODUCTION = false; // set to true in PRODUCTION environment
    const DOMAIN = 'www.yellowheroes.com'; // use this in PRODUCTION to set the domain
    const APPFOLDER = ''; // the folder where the application is saved on the shared-host server - if ROOT, leave empty ''.

    /*
     * PROJECTNAME is used in class Router to construct the proper namespace string.
     * it must be equal to the project-name set in composer.json
     *  psr-4 autoloader: "yellowheroes\\projectname\\": "src/"
     *  as this is used in all namespaces.
     */
    const PROJECTNAME = 'projectname'; // make sure project-name === project-name used in composer.json autoloader setting.

    /** login e-mail verification */
    const EMAILVERIF = false;                   // set to true if you want to use e-mail login verification check
    
    /** mvc */
    const DFLT_CONTROLLER = 'Index';
    const DFLT_METHOD = 'index';
    const DFLT_MODEL = 'Index';
    
    /** flat-file (FF) database file names (they get extension .yel by default) */
    const FF_session = 'session';       // file: session.yel - any data that lasts a session
    const FF_settings = 'settings';     // file: settings.yel - any settings data
    const FF_users = '_users';           // file: _users.yel - database with user credentials

    /**
     * Bootswatch themes (21): cerulean, cosmo, cyborg, darkly, flatly, journal, litera, lumen, lux, materia, minty,
     * pulse, sandstone, simplex, sketchy, slate, solar, spacelab, superhero, united, yeti
     */
	const BOOTSWATCH_THEME = 'slate';

    /**
    * Google font API - https://developers.google.com/fonts/docs/getting_started
     * find all font-families at: https://fonts.google.com/
     * use any font-family by referencing them in a DOM element (<div> / <p> / other):
     * <div style="font-family: 'Font Name', serif;">Your text</div>
     */
    // cool fonts: Roboto, Montserrat, Raleway, PT+sans (use + for space)
    const FONT_FAMILY = 'Montserrat'; 
    const FONT_WEIGHT = '400'; // super light==100 ...normal==400... super bold == 900
    
    /**
     * navigation button colors
     */
    const TXTCOLOR_NORMAL_NAV = '#FFFFFF;'; // this setting currently only works with BootWrap 'dropDown()' and 'navButton()' (in e.g. navBarEnhanced() )
    const TXTCOLOR_ACTIVE_NAV = '#FFC000;'; // this setting currently only works with BootWrap 'dropDown()' and 'navButton()' (in e.g. navBarEnhanced() )
    
    /** highlight.js theme */
    //const HIGHLIGHTJS_THEME = 'github';
    //const HIGHLIGHTJS_THEME = 'darkula';
    const HIGHLIGHTJS_THEME = 'atom-one-dark';
    
    public function __construct($getPaths = false)
    {
        if($getPaths === true) {
            $this->getPaths();
        }
    }

    public function getPaths()
    {
        $root = $this->getRoot();
        
        if(self::PRODUCTION !== false) {
        $appFolder = (self::APPFOLDER !== '') ? self::APPFOLDER . "/" : '';
        } else {
            $appFolder = ''; /** in development environment appFolder set to '' */
        }
         /**
         * UPDATE:  2018/04/09
         * we removed 'app' from all the 'path's and also removed 'src' from the $root
         * we added src/app/ to the rewrite rule
         * and set the RewriteBase to: '/'
         */
        $this->path = [
            'root' => $root,
            'index' => $root . 'index',
            'blog' => $root . 'blog',
            'archive' => $root . 'blog/archive',
            'crud' => $root . 'crud',
            'create' => $root . 'crud/create',
            'read' => $root . 'crud/read',
            'update' => $root . 'crud/update',
            'delete' => $root . 'crud/delete',
            'contact' => $root . 'contact',
            'login' => $root . 'login',
            'logout' => $root . 'login/logout',
            'dashboard' => $root . 'dashboard',
            'register' => $root . 'dashboard/register',     // register a new user
            'deregister' => $root . 'dashboard/deregister', // remove an existing user account
            'createblog' => $root . 'dashboard/createblog',
            'deleteblog' => $root . 'dashboard/deleteblog',
            'quill' => $root . 'quill',
            'editarticle' => $root . 'quill/edit',
            'deletearticle' => $root . 'quill/delete',
            'storearticle' => $root . 'quill/store',
            'chat' => $root . 'chat',
            'storechat' => $root . 'chat/store',
            'updatechat' => $root . 'chat/update',
            
            /**
             * $appFolder is the folder where the application is stored on the shared-hosting server
             */
            'assets' => $root . $appFolder . 'src/system/assets',                   // important - all 'slugs' with pattern .*/src/system/.* will not be rewritten 
            'css' => $root . $appFolder . 'src/system/assets/css',                  // important - all 'slugs' with pattern .*/src/system/.* will not be rewritten
            'javascript' => $root . $appFolder . 'src/system/assets/javascript',    // important - all 'slugs' with pattern .*/src/system/.* will not be rewritten
            'images' => $root . $appFolder . 'src/system/assets/images',            // important - all 'slugs' with pattern .*/src/system/.* will not be rewritten
            'flatdb' => $appFolder . 'src/system/flatdb',                    // this is the path used to unlink quill (editor) database files (unlink() path cannot be a URL - see BlogModel::deleteArchive()
            'ssechat' => $root . $appFolder . 'src/system/assets/sse'
        ];
    }

    private function getRoot()
    {
        /**
         * may contain tainted data
         * $_SERVER['PHP_SELF'] and $_SERVER['SERVER_NAME'] are UNSAFE, DO NOT USE
         * 
         * The $_SERVER['HTTP_HOST'] and $_SERVER['SERVER_NAME'] variables can be changed by client by sending a different Host header when accessing the site:
         * curl -H "Host: notyourdomain.com" http://yoursite.com/
         * 
         * these $_SERVER variables can be set by the user by sending (hack) values in the headers of the request
         * i.e. PHP_SELF can be set to /index.php/"><script>alert(1)</script>/ by a CLIENT. It's often used for XSS Attacks
         * 
         * SAFE ALTERNATIVES:
         * UNSAFE- $_SERVER['PHP_SELF'] can be replaced with SAFE - $_SERVER['SCRIPT_NAME']
         * 
         * We allow use of 'unsafe' or tainted variables (e.g. $_SERVER['SERVER_NAME']) in DEVELOPMENT (i.e. on our local server for testing)
         * We do not allow these to be used in 'PRODUCTION' environment and we thus replace with hard-coded constants (e.g. 'www.yellowheroes.com as domain). 
         * 
         */
        /**
         * if $environment evaluates to 'true' (i.e. 'PRODUCTION'), then use: 'https' and the hard-coded const DOMAIN
         */
        $environment = (self::PRODUCTION) ? 'PRODUCTION' : 'DEVELOPMENT';
        $requestScheme = ($environment === 'PRODUCTION') ? 'https' : $_SERVER['REQUEST_SCHEME'];
        $domainName = ($environment === 'PRODUCTION') ? self::DOMAIN : $_SERVER['SERVER_NAME'];
        $scriptName = explode('/', $_SERVER['SCRIPT_NAME']); // explode so we can cut-off at the correct directory level, e.g. 'src', array element [4] or [5] depending on local dir

        if(self::PRODUCTION) {
            $root = $requestScheme . "://" . $domainName . "/"; // e.g. https://www.yellowheroes.com/
        } else {
            $root = $requestScheme . "://" . $domainName . "/" .
                    $scriptName[1] . "/" . $scriptName[2] . "/" .
                    $scriptName[3] . "/"; // . $scriptName[4] . "/"; // e.g. 127.0.0.1/edsa-coding/PROJECTS/development/MVCbaseflat/
        }

        return $root;
    }

    public function getSessionConfig(): array
    {
        $secure = (self::PRODUCTION === false) ? false : true;
        
        $this->sessionConfig = [
            'cookie_httponly' => true, // XSS defense
            'use_strict_mode' => true, // session fixation defense
            'use_cookies' => true,
            'use_only_cookies' => true, // no URLs
            'cookie_secure' => $secure, // HTTPS only - only to be used in PRODUCTION
            //'cookie_domain' => '', // careful - if set incorrect 'domain' session stops working properly
            'cookie_lifetime' => 0, // untill browser closed
            'use_trans_sid' => false
        ];

        return $this->sessionConfig;
    }
}