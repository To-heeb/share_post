<?php

class User extends Database
{
    private $db;
    private $table = 'users';

    public function __construct()
    {
        $this->db = new Database;
    }

    //Register new user
    public function register($data)
    {
        $this->db->query("INSERT INTO $this->table (name, email, password) VALUES (:name, :email, :password)");

        //Bind Values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);


        //Execute
        if ($this->db->execute()) {
            return true;
        }

        return false;
    }

    public function login($data)
    {
        $this->db->query("SELECT * FROM $this->table WHERE email = :email");

        //Bind Values  
        $this->db->bind(':email', $data['email']);

        //Execute
        $row = $this->db->single();

        $hashed_passowrd = $row->password;

        if (password_verify($data['password'], $hashed_passowrd)) {
            return $row;
        }
        return false;
    }

    //Find user by email address
    public function findUserByEmail($email)
    {
        $this->db->query("select * from $this->table where email = :email");
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        //Check row
        if ($this->db->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function getuserById($id)
    {
        $this->db->query("select * from $this->table where id = :id");
        $this->db->bind(':id', $id);

        $row = $this->db->single();
        return $row;
    }
}
