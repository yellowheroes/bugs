<?php
namespace yellowheroes\bugs\system\libs;

use yellowheroes\bugs\system\config as config;

/**
 * CoreModel connects to all available flat-file databases
 * and starts a session
 *
 */
class CoreModel
{

    // FlatFileDb object
    public $db = null;
    // MySQL database connection - the bug-tracker db */
    public $pdo = null;

    // session manager object
    public $session = null;
    // our flat-file database connections
    public $usersDb = null;     // Flintstone object: database with user credentials
    public $sessionDb = null;     // Flintstone object: database with session data
    public $settingsDb = null;     // Flintstone object: database with settings data

    public function __construct()
    {
        /* start session */
        $this->session = new SessionManager(); // instantiate a session manager object
        $this->session->start(); // start a session

        /* connect to default Flintstone flat-file db's and the MySQL bugs database */
        $this->dbConnect(null, null, true);
    }

    public function dbConnect($dbName = null, $dbDir = null, $setDflts = false)
    {
        $this->db = new FlatFileDb();
        $connection = ($dbName !== null) ? $this->db->connect($dbName, $dbDir) : null;

        /** connect to default flat-file databases only when setDflts === true */
        if ($setDflts === true) {
            $this->usersDb = $this->db->connect(config\Config::FF_users);
            $this->sessionDb = $this->db->connect(config\Config::FF_session);
            $this->settingsDb = $this->db->connect(config\Config::FF_settings);
        }

        /* connect to MySQL database - the bug tracker database */
        $dbName = config\Config::DB_NAME;
        $type   = config\Config::DB_TYPE;
        $host   = config\Config::DB_HOST;
        $user   = config\Config::DB_USER;
        $pass   = config\Config::DB_PASS;

        $connection = new DbConnect($dbName, $type, $host, $user, $pass); // connect to MySQL database (the bug tracker db)
        $this->pdo = $connection->pdo;

    }

    /**
     * the _blogmaster database, _blogmaster.yel, holds the META data of all blog-databases
     * 
     * CoreModel::getArchiveMetaData() returns this META data
     */
    public function getArchiveMetaData()
    {
        $dbDir = 'blog'; // we have all blog-related flat-file-db's in 'flatdb' sub-dir 'blog'
        $dbConnection = $this->dbConnect('_blogmaster', $dbDir); // connect to _blogmaster.yel database - it holds META data for all available blog databases
        $blogArchives = $dbConnection->getKeys('_blogmaster'); // get the META data for all available blog archives - stored in '_blogmaster.yel'

        foreach ($blogArchives as $key => $archive) {
            // $archive is the name of the blog-database, it is also the key of a record in '_blogmaster.yel'
            // each $record is a row, containing: archive-name(key) - blog-type - timestamp
            //
            $record[$archive] = $dbConnection->get($archive); 
        }
        return $record;
    }
}
