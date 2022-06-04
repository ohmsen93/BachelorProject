<?php
require_once ROOT_PATH . "config/db_connection.php";


class user extends DB_CONNECTION {

    /* Validate Data */
    public int $id;

    /* Required Data */
    public string $google_account_id;
    public string $firstName;
    public string $lastName;
    public string $email;


    public function getUsers(){
        /* Get all Users*/
        $query = <<<'SQL'
                SELECT * FROM Users;
            SQL;

        /* fetchAll is used to return multiple rows */
        return $this->run($query)->fetchAll();
    }

    /**
     * Inserts a new user
     *
     * Required
     * @param   $google_account_id "google_account_id of the user"
     * @param   $firstName "firstname of the user"
     * @param   $lastName "lastname of the user"
     * @param   $email "email of the user"
     * @param   $password "password of the user"
     *
     * Optional

     */
    function addNewUser($firstName, $lastName, $email, $token):bool
    {   
        try{
            // Check if the user already exists
            $query = <<<'SQL'
            SELECT COUNT(*) AS total FROM users WHERE email = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$email]);
            if ($stmt->fetch()['total'] > 0) {
            return false;
            }

            //Begin the insert transaction

            $this->pdo->beginTransaction();

            // Insert into google_account
            $query = <<<'SQL'
            INSERT INTO google_accounts (token, refresh_token) VALUES (?, ?);
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$token->access_token, $token->id_token]);


            //Get the google_account_id
            $query = <<<'SQL'
                SELECT id FROM google_accounts WHERE refresh_token = ?;
            SQL;

            /* fetch is used to return single row */
            $google_account_id = $this->run($query, $token->id_token)->fetchObject();


            // Insert the user

            $query = <<<'SQL'
            INSERT INTO users (google_account_id, firstName, lastName, email) VALUES (?, ?, ?, ?);
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$google_account_id->id, $firstName, $lastName, $email]);

            $this->pdo->commit();

            return true;

        } catch(Exception $e){
            // Print the error Message
            echo $e->getMessage();

            // Rollback the transaction
            $this->pdo->rollBack();
        }
    }

    /**
     * Updates a user
     *
     * Required
     * @param   $firstName "firstname of the user"
     * @param   $lastName "lastname of the user"
     * @param   $email "email of the user"
     * @param   $password "password of the user"
     *
     * Optional

     */
    function updateUser($email, $firstName, $lastName, $role_id)
    {


        $query = <<<'SQL'
            UPDATE users
            SET firstName = ?,
                lastName = ?,
                fk_role_id = ?
        SQL;

        $query .= ' WHERE email = ?;';
        /* debug($query); */
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$firstName, $lastName, $role_id, $email]);
        
        return true;
    }


    function getUserByEmail($email){
        /* Get specific user by email*/
        $query = <<<'SQL'
                SELECT * FROM users a, google_accounts b where a.google_account_id = b.id AND a.email = ?;
            SQL;

        /* fetch is used to return single row */
        return $this->run($query, $email)->fetch();
    }
}