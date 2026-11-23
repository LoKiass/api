<?php

use PhpParser\Node\Expr\Cast\Void_;

class UserModel{
    private int $id_user;
    private string $nom;
    private string $email;
    private string $prenom;
    private string $password;

    public function __construct(){}

    // Setter and getter
    public function set_iduser(int $t_iduser) : void{
        $this->id_user = $t_iduser;
    }
    public function set_nom(string $t_nom) : void{
        $this->nom = $t_nom;
    }
    public function set_prenom(string $t_prenom) : void{
        $this->prenom = $t_prenom;
    }
    public function set_email(string $t_email) : void{
        $this->email = $t_email;
    }
    public function set_password(string $t_password) : void{
        $this->password = $t_password;
    }

    public function get_iduser() : int{
        return $this->id_user;
    }
    public function get_nom() : string{
        return $this->nom;
    }
    public function get_prenom() : string{
        return $this->prenom;
    }
    public function get_email() : string{
        return $this->email;
    }
    public function get_password() : string{
        return $this->password;
    }

    public static function fromArray() : array{
        
    }
}