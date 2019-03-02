<?php
namespace yellowheroes\jimmy\system\libs;

use \PDO;

/**
 * Query an SQL database
 *
 * @author    Yellow Heroes <admin@yellowheroes.com>
 * @license   MIT License
 * @copyright Yellow Heroes, 2017
 * @since     2017/November/23
 */
class DbQuery
{

    public $queryResultArray = [];

    public function doVerifyLogin($connection, $sql, $user = null)
    {
        if (is_object($connection)) {
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':param1', $user);
            //$stmt->bindParam(':param2', $passWord);

            $stmt->execute();
            /** $data === false in case the given credentials are wrong/non-existant */
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $connection = null;
            if ($data !== false) {
                return $data;
            } else {
                return false;
            }
        }
    }

    /**
     * @doInsert
     *
     */
    public function doInsert($connection, $sql, $arg1 = null, $arg2 = null, $arg3 = null)
    {
        if (is_object($connection)) {
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':param1', $arg1);
            $stmt->bindParam(':param2', $arg2);
            //$stmt->bindParam(':param3', $arg3);
            // insert a row
            $stmt->execute();
            $connection = null;
            return;
        }
    }

    /**
     * @doUpdate (normally happens on reference 'id' in database, i.e. WHERE 'id' = :param1)
     *
     */
    public function doUpdate($connection, $sql, $arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null, $arg5 = null)
    {
        if (is_object($connection)) {
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // prepare sql and bind parameters
            // use backticks ` when referencing a column heading with spaces in MySQL table, e.g. first <space> name, or last <space> name
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':param1', $arg1);
            $stmt->bindValue(':param2', $arg2);
            $stmt->bindValue(':param3', $arg3);
            //$stmt->bindValue(':param4', $arg4);
            //$stmt->bindValue(':param5', $arg5);
            // insert a row
            $stmt->execute();
            $connection = null;
            return;
        }
    }

    /**
     * @function doDelete
     *
     */
    public function doDelete($connection, $sql, $id = null)
    {
        if (is_object($connection)) {
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':param1', $id);

            // delete a row
            $stmt->execute();
            $connection = null;
            return;
        }
    }

    /**
     * @function doQuery
     */
    public function doQuery($connection, $sql, $id = null)
    {
        if (is_object($connection) && $id === null) {
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $connection->query($sql);

            /* fetch(PDO::FETCH_ASSOC) to retrieve database data
              one row at a time is put in queryResultArray
             */
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->queryResultArray[] = $row;
            }
            $connection = null;
            return $this->queryResultArray;
        }

        if (is_object($connection) && $id !== null) {
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':param1', $id);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $connection = null;
            return $data;
        }
    }
}
