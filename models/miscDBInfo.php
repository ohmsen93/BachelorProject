<?php
require_once ROOT_PATH . "config/db_connection.php";

class miscDbInfo extends DB_CONNECTION
{
    // Class for grabbing misc info from db

    public function getRoles(){
        /* Get all rooms*/
                $query = <<<'SQL'
                SELECT * FROM roles;
            SQL;

        /* fetchAll is used to return multiple rows */
        return $this->run($query)->fetchAll();
    }

}