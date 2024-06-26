<?php

class UserManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findByEmail(string $email) : ?User {
        $query = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $parameters = [
            "email" => $email
        ];
        $query->execute($parameters);
        $userDB = $query->fetch(PDO::FETCH_ASSOC);

        if($userDB) {
            $user = new User($userDB['username'], $userDB['email'], $userDB['password'], $userDB['role']);
            $user->setId($userDB["id"]);

            return $user;
        }

        return null;
    }

    public function findById(int $id) : ?User {
        $query = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $userDB = $query->fetch(PDO::FETCH_ASSOC);

        if($userDB) {
            $user = new User($userDB['username'], $userDB['email'], $userDB['password'], $userDB['role']);
            $user->setId($userDB['id']);

            return $user;
        }

        return null;
    }

    public function create(User $user) : void {
        $currentDateTime = date('Y-m-d H:i:s');

        $query = $this->db->prepare('INSERT INTO users (username, email, password, role, created_at) VALUES (:username, :email, :password, :role, :createdAt)');
        $parameters = [
            "username" => $user->getUsername(),
            "email" => $user->getEmail(),
            "password" => $user->getPassword(),
            "role" => $user->getRole(),
            "createdAt" => $currentDateTime,
        ];

        $query->execute($parameters);

        $user->setId($this->db->lastInsertId());
    }
}