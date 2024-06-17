<?php

class PostManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findLatest() : array {
        $userManager = new UserManager();
        $categoryManager = new CategoryManager();

        $query = $this->db->prepare('SELECT * FROM posts ORDER BY created_at LIMIT 4');
        $query->execute();
        $postDB = $query->fetchAll(PDO::FETCH_ASSOC);
        $posts = [];
        
        foreach($postDB as $postFor) {
            $user = $userManager->findById($postFor['author']); 
            $categories = $categoryManager->findByPost($postFor['id']);
            $post = new Post($postFor['title'], $postFor['excerpt'], $postFor['content'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $postFor['created_at']));
            $post->setId($postFor['id']);
            $post->setCategories($categories);
            $posts[] = $post;
        }
        
        return $posts;
    }

    public function findOne(int $id) : ?Post {
        $userManager = new UserManager();
        $categoryManager = new CategoryManager();

        $query = $this->db->prepare('SELECT * FROM posts WHERE id = :id');
        $parameters = [
            "id" => $id,
        ];
        $query->execute($parameters);
        $postDB = $query->fetch(PDO::FETCH_ASSOC);
        

        if($postDB) {
            $user = $userManager->findById($postDB['author']);   
            $categories = $categoryManager->findByPost($postDB['id']);
            $post = new Post($postDB['title'], $postDB['excerpt'], $postDB['content'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $postDB['created_at']));
            $post->setId($postDB['id']);
            $post->setCategories($categories);

            return $post;
        }

        return null;
    }

    public function findByCategory(int $categoryId) : array {
        $userManager = new UserManager();
        $categoryManager = new CategoryManager();
        
        $query = $this->db->prepare('SELECT posts.* FROM posts JOIN posts_categories ON posts.id = posts_categories.post_id WHERE category_id = :category_id');
        $parameters = [
            "category_id" => $categoryId,
        ];
        $query->execute($parameters);
        $postDB = $query->fetchAll(PDO::FETCH_ASSOC);
        

        foreach($postDB as $postFor) {
            $user = $userManager->findById($postFor['author']);
            $categories = $categoryManager->findByPost($postFor['id']);
            $post = new Post($postFor['title'], $postFor['excerpt'], $postFor['content'], $user, DateTime::createFromFormat('Y-m-d H:i:s', $postFor['created_at']));
            $post->setId($postFor['id']);
            $post->setCategories($categories);
            $posts[] = $post;
        }
        
        return $posts;
    }
}