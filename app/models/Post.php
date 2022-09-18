<?php

class Post extends Database
{
    private $db;
    private $table = 'posts';

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getPosts()
    {
        $this->db->query("SELECT *,
                        posts.id as postId,
                        users.id as userId,
                        posts.created_at as post_created_at
                         FROM $this->table
                         INNER JOIN users
                         ON posts.user_id = users.id
                         ORDER BY posts.created_at");

        $results = $this->db->resultSet();

        return $results;
    }

    public function addPost($data)
    {
        $this->db->query("INSERT INTO $this->table (title, user_id, body) VALUES (:title, :user_id, :body)");

        //Bind Values
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':body', $data['body']);


        //Execute
        if ($this->db->execute()) {
            return true;
        }

        return false;
    }
}
