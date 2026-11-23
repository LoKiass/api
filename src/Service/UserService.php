<?php
namespace App\Service;

use App\Service\Database;
use PDO;

class UserService{
    public function __construct(private Database $database){}

    // This function verify the credentiale from the controller to the database, return a bolean
    public function checkUser(string $username, string $password){
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT 1 FROM user_table WHERE username = ? AND passwrd = ?");
        $stmt->execute([$username, $password]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // In the case that credential doesnt respect what in the databse
        if(!$user){
            return false;
        }
        
        // In any other case (True case)
        return true;
    }
    
    // In the case one user want to create a acount, we need to verify if his email, or other are already linked to a account
    public function checkIfAccountIsAlreadyLinked(string $email, string $username) : bool{
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT 1 FROM user_table WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);

        $email = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$email){
            return false;
        }
        return true;
    }

    // This function is the result of checking if email and username already exists, we can now create is account with the credential given
    public function createUser(string $email, string $username, string $password) : bool{
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("INSERT INTO user_table (email, username, passwrd) VALUES (?, ?, ?)");
        $result =  $stmt->execute([$email, $username, $password]);

        if ($result){
            return true;
        }
        return false;
    }
}