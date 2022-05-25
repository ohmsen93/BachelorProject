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
    function addNewUser($google_account_id, $firstName, $lastName, $email, $password): bool
    {

        // Check if the user already exists
        $query = <<<'SQL'
            SELECT COUNT(*) AS total FROM users WHERE email = ?;
        SQL;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$email]);
        if ($stmt->fetch()['total'] > 0) {
            return false;
        }

        // Insert the user
        $password = password_hash($password, PASSWORD_DEFAULT);

        $query = <<<'SQL'
            INSERT INTO users (google_account_id, firstName, lastName, email, password) VALUES (?, ?, ?, ?, ?);
        SQL;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$google_account_id, $firstName, $lastName, $email, $password]);

        $this->disconnect();

        return true;
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
    function updateUser($email, $firstName, $lastName, $password)
    {

        $passwordChange = (trim($newPassword) !== '');

        $query = <<<'SQL'
            UPDATE users
            SET firstName = ?,
                lastName = ?
        SQL;

        if ($passwordChange) {
            if ($this->validateuser($email, $password)) {
                $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $query .= ', password = ?';
            } else {
                return false;
            }
        }
        $query .= ' WHERE email = ?;';
        /* debug($query); */
        $stmt = $this->pdo->prepare($query);
        if ($passwordChange) {
            $stmt->execute([$firstName, $lastName, $email]);
        } else {
            $stmt->execute([$firstName, $lastName, $email]);
        }


        return true;
    }

    /**
     * Validates a user login
     *
     * @param   user's email
     * @param   user's password
     * @return  true if the password is correct, false if it is not or if the user does not exist
     */
    function validateUser($email, $password)
    {
        // Get user data
        $query = <<<'SQL'
            SELECT id, google_account_id, firstName, lastName, email, password FROM users WHERE email = ?;
        SQL;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$email]);
        if ($stmt->rowCount() === 0) {
            return false;
        }

        $row = $stmt->fetch();

        $this->id = $row['id'];
        $this->google_account_id = $row['google_account_id'];
        $this->firstName = $row['firstName'];
        $this->lastName = $row['lastName'];
        $this->email = $email;

        // Check the password

        return (password_verify($password, $row['password']));

    }

    function getUserById($id){
        /* Get specific user by id*/
        $query = <<<'SQL'
                SELECT * FROM users where id = ?;
            SQL;

        /* fetch is used to return single row */
        return $this->run($query, $id)->fetch();
    }
}