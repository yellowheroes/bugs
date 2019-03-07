<?php
namespace yellowheroes\bugs\system\libs;

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
    /**
     * @doInsert
     * example - a valid INSERT query:
     * INSERT INTO `jimmy` (`id`, `new`, `accepted`, `in progress`, `awaiting validation`, `fixed`) VALUES (NULL, 'some bug report', '', '', '', '');
     */
    public function doInsert($connection, $sql, $status = "", $title = "", $description = "")
    {
        if (is_object($connection)) {
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':title', $title);
            $stmt->bindValue(':description', $description);

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
    public function doUpdate($connection, $sql, $id = "", $status = "", $title = "", $description = "")
    {
        if (is_object($connection)) {
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // prepare sql and bind parameters
            // use backticks ` when referencing a column heading with spaces in MySQL table, e.g. first <space> name, or last <space> name
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':id', $id); // id
            $stmt->bindParam(':status', $status); // status
            $stmt->bindParam(':title', $title); // title
            $stmt->bindValue(':description', $description); // accepted
            // update a row
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
            $stmt->bindParam(':id', $id);

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
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $connection = null;
            return $data;
        }
    }
}
