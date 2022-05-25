<?php
/**
 * Encapsulates a connection to the database 
 * 
 * @author  Arturo Mora-Rioja
 * @version 1.0 August 2020
 */
    class DB_CONNECTION {


        public PDO $pdo;

        /**
         * Opens a connection to the database
         */
        public function __construct() {
            require_once "db_info.php";

            $constant = 'constant';

            $dsn = 'mysql:host='. DBhost .';dbname='. DBname .';charset=utf8';
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            try {
                $this->pdo = @new PDO($dsn, DBusername, DBpassword, $options);
            } catch (\PDOException $e) {

                echo 'Connection unsuccessful';

                die('Connection unsuccessful: ' . $e->getMessage());
                exit();
            }
        }

        /**
         * function to prepare and execute our sql query
         */
        public function run($sql, $args = NULL): bool|PDOStatement
        {
            // Prepare the statement
            $stmt = $this->pdo->prepare($sql);

            // No arguments to take care of, go ahead
            if (!$args)
            {
                return $this->pdo->query($sql);
            }

            // Check if args is already an array, in cases with multiple args it is.
            // if it is, don't convert it, that would result in an array in an array.
            if (is_array($args)) {
                $stmt->execute($args);
            }
            else {
                $stmt->execute([$args]);
            }

            return $stmt;
        }

        /**
         * Closes a connection to the database
         */
        public function disconnect() {
            $this->pdo = null;
        }
    }
?>