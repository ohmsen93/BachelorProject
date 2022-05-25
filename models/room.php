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

    public function addRoom($name){
        /* Insert/add a new room in the database */
        $query = <<<'SQL'
                INSERT INTO rooms (name)
                VALUES (?);
            SQL;

        return $this->run($query, $name);
    }

    public function updateRoom($id, $name){
        /* Updates a room in the database by id */
        $query = <<<'SQL'
                UPDATE rooms
                SET name = ?
                WHERE id = ?;
            SQL;
        /* @return mixed Returns a single column from the next row of a result set or FALSE if there are no more rows.*/
        return $this->run($query, [$name, $id])->rowCount();
    }

    public function deleteRoom($id){
        // $chinook_db = new DB_CONNECTION();
        // $connection = $chinook_db->pdo;
        // if($connection){
        //     try{
        //         /* Deletes an room in the database by id*/

        //         /* Before deleting the room we first have to deal with the albums and the tracks from the rooms.
        //             As such we start with the smallest and backtrack, thing being track -> album -> room due to db constraints */

        //         // first we make a general select over the room, their albums and their tracks.

        //         $query = <<<'SQL'
        //         SELECT AlbumId FROM album
        //             WHERE roomId = ?
        //         SQL;
        //         $album_exec = $connection->prepare($query);
        //         $album_exec->execute([$id]);
        //         $albums = $album_exec->fetchAll();

        //         foreach ($albums as $album){
        //             $query = <<<'SQL'
        //             SELECT TrackId FROM track
        //                 WHERE AlbumId = ?
        //             SQL;
        //             $track_exec = $connection->prepare($query);
        //             $track_exec->execute([$album['AlbumId']]);
        //             $tracks = $track_exec->fetchAll();

        //             foreach($tracks as $track){
        //                 $trackId = $track['TrackId'];

        //                 $query = <<<'SQL'
        //                 DELETE FROM  invoiceline
        //                     WHERE TrackId = ?
        //                 SQL;
        //                 $Del_exec = $connection->prepare($query);
        //                 $Del_exec->execute([$trackId]);

        //                 $query = <<<'SQL'
        //                 DELETE FROM track
        //                     WHERE TrackId = ?
        //                 SQL;
        //                 $Del_exec = $connection->prepare($query);
        //                 $Del_exec->execute([$trackId]);
        //             }
        //         }

        //         $query = <<<'SQL'
        //                 DELETE FROM album
        //                     WHERE roomId = ?
        //                 SQL;
        //                 $Del_exec = $connection->prepare($query);
        //                 $Del_exec->execute([$id]);

        //         $query = <<<'SQL'
        //                 DELETE FROM room
        //                     WHERE roomId = ?
        //                 SQL;
        //         $Del_exec = $connection->prepare($query);
        //         $Del_exec->execute([$id]);

        //         $Del_exec = Null;
        //         return "room with id: ".$id." deleted";

        //     } catch (Exception $e){
        //         return "room could not be deleted... Error: ". $e;
        //     }
        // } else {
        //     return "Database connection failed";
        // }
    }
}