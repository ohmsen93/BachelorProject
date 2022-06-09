<?php
require_once ROOT_PATH . "config/db_connection.php";

class room extends DB_CONNECTION
{

    public function getRooms(){
        /* Get all rooms*/
        $query = <<<'SQL'
                SELECT * FROM rooms;
            SQL;

        /* fetchAll is used to return multiple rows */
        return $this->run($query)->fetchAll();
    }

    public function getRoomById($id){
        /* Get specific room by id*/
        $query = <<<'SQL'
                SELECT * FROM rooms where id = ?;
            SQL;

        /* fetch is used to return single row */
        return $this->run($query, $id)->fetch();
    }

    public function addRoom($name, $calendarid, $calendarurl){
        /* Insert/add a new room in the database */
        $query = <<<'SQL'
                INSERT INTO rooms (name, calendarId, calendarURL)
                VALUES (?, ?, ?);
            SQL;

        return $this->run($query, [$name, $calendarid, $calendarurl]);
    }

    public function updateRoom($id, $name, $calendarid, $calendarurl){
        /* Updates a room in the database by id */
        $query = <<<'SQL'
                UPDATE rooms
                SET name = ?, calendarId = ?, calendarURL = ?
                WHERE id = ?;
            SQL;
        /* @return mixed Returns a single column from the next row of a result set or FALSE if there are no more rows.*/
        return $this->run($query, [$name, $calendarid, $calendarurl, $id])->rowCount();
    }

    public function deleteRoom($id){
        $chinook_db = new DB_CONNECTION();
        $connection = $chinook_db->pdo;
        if($connection){
            try{
                /* Deletes an room in the database by id*/

                $query = <<<'SQL'
                        DELETE FROM rooms
                            WHERE id = ?
                        SQL;
                $Del_exec = $connection->prepare($query);
                $Del_exec->execute([$id]);

                $Del_exec = Null;
                return "room with id: ".$id." deleted";

            } catch (Exception $e){
                return "room could not be deleted... Error: ". $e;
            }
        } else {
            return "Database connection failed";
        }
    }
}