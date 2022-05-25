<?php
require_once ROOT_PATH . "config/db_connection.php";


class admin extends DB_CONNECTION {

    function validateAdmin($password)
    {
        // Get user data
        $query = <<<'SQL'
            SELECT Password FROM admin;
        SQL;
        /* fetchAll is used to return multiple rows */
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return false;
        }

        $row = $stmt->fetch();

        // Check the password

        return (password_verify($password, $row['Password']));

    }

}