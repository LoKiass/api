<?php
namespace \App\Service;

use App\Service\Database;

class UserService{
    public function __construct(private Database $database){}

    public function checkUser(string $username, string $password){
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT 1 FROM user WHERE username = ? AND password = ?");
        $result = $stmt->execute(['$username, $password']);

        // In the case that credential doesnt respect what in the databse
        if ($result != 1){
            return false;
        }
        
        // In any other case (True case)
        return true;
    }
}