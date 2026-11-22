<?php
namespace App\Service;

use App\Service\Database;
use PDO;

class UserService{
    public function __construct(private Database $database){}

    public function checkUser(string $username, string $password){
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT 1 FROM user_table WHERE username = ? AND passwrd = ?");
        $result = $stmt->execute([$username, $password]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // In the case that credential doesnt respect what in the databse
        if(!$user){
            return false;
        }
        
        // In any other case (True case)
        return true;
    }
}