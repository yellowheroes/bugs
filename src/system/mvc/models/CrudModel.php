<?php
namespace yellowheroes\bugs\system\mvc\models;

use yellowheroes\bugs\system\config as config;
use yellowheroes\bugs\system\libs as libs;

/**
 *
 */
class CrudModel extends libs\CoreModel
{
    public function __construct()
    {
        parent::__construct(); // now we have access to $this->pdo, the PDO object in CoreModel
    }

    public function selectAll($dbTable=null, $id=null)
    {
        if (is_object($this->pdo)) {
            $sql = "SELECT * FROM " . $dbTable . " ORDER BY `id` DESC";
            $dbData = (new libs\DbQuery())->doQuery($this->pdo, $sql, $id);
            $this->pdo = null;
            return $dbData;
        }
    }

    public function selectRecord($dbTable, $id=null)
    {
        if (is_object($this->pdo)) {
            $sql = "SELECT * FROM " . $dbTable . " where id = :id";
            $dbData = (new libs\DbQuery())->doQuery($this->pdo, $sql, $id);
            $this->pdo = null;
            return $dbData;
        }
    }

    public function insertRecord($dbTable=null)
    {
        // keep track post values
        $title = $_POST['title'];
        $description = $_POST['description'];

        // validate input
        $valid = true;
        if (empty($title)) {
            $titleError = 'Please enter user name';
            $valid = false;
        }

        if (empty($description)) {
            $descriptionError = 'Please enter password';
            $valid = false;
        }
        // insert row
        if (is_object($this->pdo) && $valid === true) {
            $status = "new";
            $sql = "INSERT INTO " . $dbTable .
                    " (`id`, `status`, `title`, `description`)
                    VALUES (NULL, :status, :title, :description)";
            $insert = (new libs\DbQuery())->doInsert($this->pdo, $sql, $status, $title, $description);
            $this->pdo = null; // kill database connection
            return;
        }
    }

    public function updateRecord($dbTable, $id)
    {
        // keep track post values
        $title = $_POST['title'];
        $description = $_POST['description'];
        $status = $_POST['status'];

        // validate input
        $valid = true;
        if (empty($title)) {
            $titleError = 'Please enter user title';
            $valid = false;
        }

        if (empty($description)) {
            $descriptionError = 'Please enter description';
            $valid = false;
        }

        if (empty($status)) {
            $statusError = 'Please enter status';
            $valid = false;
        }

        // update row
        if (is_object($this->pdo) && $valid === true) {
            $sql = "UPDATE " . $dbTable . " SET `status` = :status, `title` = :title, `description` = :description WHERE `id` = :id";
            $update = (new libs\DbQuery())->doUpdate($this->pdo, $sql, $id, $status, $title, $description);
            $this->pdo = null;
            return;
        }
    }

    public function deleteRecord($dbTable, $id)
    {
        if (is_object($this->pdo)) {
            $sql = "DELETE FROM " . $dbTable . " WHERE id = :id";
            $delete = (new libs\DbQuery())->doDelete($this->pdo, $sql, $id);
            $this->pdo = null;
            return;
        }
    }
}
