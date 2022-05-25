<?php
require_once ROOT_PATH . "config/db_connection.php";

class meeting extends DB_CONNECTION
{

    public function getMeetings(){
        /* Get all meetings*/
        $query = <<<'SQL'
                SELECT * FROM meetings;
            SQL;

        /* fetchAll is used to return multiple rows */
        return $this->run($query)->fetchAll();
    }

    public function getMeetingById($id){
        /* Get specific Meeting by id*/
        $query = <<<'SQL'
                SELECT * FROM meetings where id = ?;
            SQL;

        /* fetch is used to return single row */
        return $this->run($query, $id)->fetch();
    }

    public function addMeeting($fk_user_id, $fk_room_id, $title, $start_time, $end_time, $description){
        /* Insert/add a new meeting in the database */
        $query = <<<'SQL'
                INSERT INTO meetings (fk_user_id, fk_room_id, title, start_time, end_time, description)
                VALUES (?);
            SQL;

        return $this->run($query, $fk_user_id, $fk_room_id, $title, $start_time, $end_time, $description);
    }

    public function updateMeeting($id, $fk_user_id, $fk_room_id, $title, $start_time, $end_time, $description){
        /* Updates a meeting in the database by id */
        $query = <<<'SQL'
                UPDATE meetings
                SET fk_user_id = ?, 
                    fk_room_id = ?,
                    title = ?, 
                    start_time = ?, 
                    end_time = ?, 
                    description = ?
                WHERE id = ?;
            SQL;
        /* @return mixed Returns a single column from the next row of a result set or FALSE if there are no more rows.*/
        return $this->run($query, [$fk_user_id, $fk_room_id, $title, $start_time, $end_time, $description, $id])->rowCount();
    }

    public function deleteMeeting($id){
        // $chinook_db = new DB_CONNECTION();
        // $connection = $chinook_db->pdo;
        // if($connection){
        //     try{
        //         /* Deletes an meeting in the database by id*/

        //         /* Before deleting the meeting we first have to deal with the albums and the tracks from the meetings.
        //             As such we start with the smallest and backtrack, thing being track -> album -> meeting due to db constraints */

        //         // first we make a general select over the meeting, their albums and their tracks.

        //         $query = <<<'SQL'
        //         SELECT AlbumId FROM album
        //             WHERE meetingId = ?
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
        //                     WHERE meetingId = ?
        //                 SQL;
        //                 $Del_exec = $connection->prepare($query);
        //                 $Del_exec->execute([$id]);

        //         $query = <<<'SQL'
        //                 DELETE FROM meeting
        //                     WHERE meetingId = ?
        //                 SQL;
        //         $Del_exec = $connection->prepare($query);
        //         $Del_exec->execute([$id]);

        //         $Del_exec = Null;
        //         return "meeting with id: ".$id." deleted";

        //     } catch (Exception $e){
        //         return "meeting could not be deleted... Error: ". $e;
        //     }
        // } else {
        //     return "Database connection failed";
        // }
    }
}