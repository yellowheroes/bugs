<?php
namespace yellowheroes\jimmy\system\libs;

use Flintstone\Flintstone;

/**
 * We utilize Flintstone - http://www.xeweb.net/flintstone/
 * an open-source flat-file database library
 * for our 'database' transactions
 * 
 * usage examples: http://www.xeweb.net/flintstone/examples/
 * documentation: http://www.xeweb.net/flintstone/documentation/
 * 
 * Options can be the following:
 * dir                  The directory where the database files are stored
 *                      (this should be somewhere that is not web accessible) e.g. /path/to/database/
 *                      Default: current working directory
 * ext                  The database file extension to use
 *                      Default: .dat
 * gzip                 Use gzip to compress the database
 *                      Default: false
 * cache                Whether to cache get() results for faster data retrieval
 *                      Default: true
 * formatter            The formatter class used to encode/decode data
 *                      Default: null
 * swap_memory_limit    The amount of memory to use before writing to a temporary file
 *                      Default: 2097152
 * 
 * Flintstone has the following public methods:
 * get(string $key)                 = Retrieve data for the key name. Returns false if it does not exist.
 * set(string $key, mixed $data)    = Set data for the key name. Data can be a string, integer, float or array. Will throw an exception if fails to set.
 * delete(string $key)              = Delete the key name. Will throw an exception if fails to delete.
 * flush()                          = Empty the database. Will throw an exception if fails to flush.
 * getKeys()                        = Returns an array of all of the keys in the database.
 * getAll()                         = Returns all data in the database.
 * 
 * Set keys example
 * $users->set('bob', ['email' => 'bob@site.com', 'password' => '123456']);
 * 
 * $settings->set('site_offline', 1);
 * 
 * Retrieve keys example
 * $user = $users->get('bob');
 * echo 'Bob, your email is ' . $user['email'];
 * 
 * $offline = $settings->get('site_offline');
 * if ($offline == 1) {
 */
class FlatFileDb
{

    /** properties */
    public $options = [];   // see comment above
    public $db = null;      // the database connection, use $db->set()... or $db->get()...

    
    
    /** methods */

    /**
     * set directory path to store flat file database in directory 'flatdb'
     * client sets the sub directory $dbDir: e.g. 'blog' for blog-articles, or 'chat' for chats
     * if client does not set a sub-directory, then the file is saved in dir 'flatdb'.
     *
     *  *** make sure you have created the database sub-dir before setting/using it here ***
     *
     * set extension of the database files to '.yel'
     */
    public function setOptions($dbDir = null, $dbExt = 'yel')
    {
        $root = dirname(__DIR__, 3);
        $path = '/src/system/flatdb/'; // default location where db files are saved
        $path = ($dbDir === null) ? $root . $path : $root . $path . $dbDir;
        //echo $dbDir;

        $this->options['dir'] = $path;
        $this->options['ext'] = $dbExt;
    }

    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * 
     * @param string $dbName
     * @param string $dbDir         client can set a database sub-directory under 'flatdb' (e.g. 'blog' or 'chat')
     * @return Flintstone object
     */
    public function connect($dbName = 'datastore', $dbDir = null)
    {
        if (!isset($this->options['dir'])) {
            $this->setOptions($dbDir); // set a target database directory where db file should be saved
        }

        $options = $this->getOptions();
        $this->db = new Flintstone($dbName, $options);
        
        return $this->db;
    }
}
