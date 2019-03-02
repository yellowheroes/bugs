<?php
namespace yellowheroes\jimmy\system\libs;

use \PDO;

/**
* Class Connect
*
* @author    Yellow Heroes <oranje@mail.com>
* @license   MIT License
* @copyright Yellow Heroes, 2017
* @since    2017/november/14
*/

class DbConnect
{
    /**
    * a PDO object the client can access to run queries
    *
    * @var PDO
    */
    public $pdo;

    /**
    * Connect to a MySQL database (MySQL, SQLite...) through PDO
    *
    * @param string $type - 'mysql' or 'sqlite'
    * @param string $dbName - database name
    * @param string $host - host name
    * @param string $user - user name
    * @param string $passWord - password
    * @return void
    */

    /** we set defaults: type 'mysql', host '127.0.0.1', user 'root', passWord ''
    * so we only have to set database name
    */
    public function __construct($dbName=null, $type='mysql', $host='127.0.0.1', $user='root', $passWord=null)
    {
        switch ($type) {
            case "mysql":
            if (!empty($host) && !empty($user) && isset($passWord)) {
                $dsn = $type . ':dbname=' . $dbName . ';host=' . $host;
            } else {
                echo 'error connecting to database';
                die();
            }
            break;

            case "sqlite":
            if (!empty($dbName)) {
                // $type:$dbName
                // sqlite:/somedir/databases/mydb.sq3
                $dsn = $type . ':' . $dbName;
            } else {
                echo 'error connecting to database';
                die();
            }
            break;
        }

        try {
            $this->pdo = new PDO($dsn, $user, $passWord);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
}
