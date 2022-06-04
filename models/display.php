<?php
require_once ROOT_PATH . "config/db_connection.php";

class display extends DB_CONNECTION
{

    public function getDisplays(){
        /* Get all displays*/
        $query = <<<'SQL'
                SELECT * FROM displays;
            SQL;

        /* fetchAll is used to return multiple rows */
        return $this->run($query)->fetchAll();
    }

    public function getDisplayById($id){
        /* Get specific display by id*/
        $query = <<<'SQL'
                SELECT * FROM displays where id = ?;
            SQL;

        /* fetch is used to return single row */
        return $this->run($query, $id)->fetch();
    }

    public function addDisplay($name){
        /* Insert/add a new display in the database */
        $query = <<<'SQL'
                INSERT INTO displays (name)
                VALUES (?);
            SQL;

        return $this->run($query, $name);
    }

    public function updateDisplay($id, $name){
        /* Updates a display in the database by id */
        $query = <<<'SQL'
                UPDATE displays
                SET name = ?
                WHERE id = ?;
            SQL;
        /* @return mixed Returns a single column from the next row of a result set or FALSE if there are no more rows.*/
        return $this->run($query, [$name, $id])->rowCount();
    }

    public function deleteDisplay($id){
        $chinook_db = new DB_CONNECTION();
        $connection = $chinook_db->pdo;
        if($connection){
            try{
                /* Deletes an display in the database by id*/

                /* Before deleting the display we first have to deal with the albums and the tracks from the displays.
                    As such we start with the smallest and backtrack, thing being track -> album -> display due to db constraints */

                // first we make a general select over the display, their albums and their tracks.

                $query = <<<'SQL'
                        DELETE FROM displays
                            WHERE id = ?
                        SQL;
                $Del_exec = $connection->prepare($query);
                $Del_exec->execute([$id]);

                $Del_exec = Null;
                return "display with id: ".$id." deleted";

            } catch (Exception $e){
                return "display could not be deleted... Error: ". $e;
            }
        } else {
            return "Database connection failed";
        }
    }
}