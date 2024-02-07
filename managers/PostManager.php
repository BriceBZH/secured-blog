<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class PostManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findLatest() : array {
        $query = $this->db->prepare('SELECT * FROM posts GROUP BY posts.id ORDER BY created_at LIMIT 4');
        $parameters = [
            "id" => $id,
        ];
        $query->execute($parameters);
        $postDB = $query->fetch(PDO::FETCH_ASSOC);

        if($postDB) {
            $userManager = new UserManager();
            $user = $userManager->findById($postDB["author"]);
            $post = new Post($postDB["title"], $postDB["except"], $postDB["content"], $user, $postDB["createdAt"], $category);
            $post->setId($postDB["id"]);

            return $psot;
        }

        return null;
    }

    public function findOne(int $id) : ?Post {
        $query = $this->db->prepare('SELECT * FROM posts WHERE id = :id');
        $parameters = [
            "id" => $id,
        ];
        $query->execute($parameters);
        $postDB = $query->fetch(PDO::FETCH_ASSOC);

        if($postDB) {
            $userManager = new UserManager();
            $user = $userManager->findById($postDB["author"]);
            $post = new Post($postDB["title"], $postDB["except"], $postDB["content"], $user, $postDB["createdAt"], $category);
            $post->setId($postDB["id"]);

            return $psot;
        }

        return null;
    }

    public function findByCategory(int $categoryId) : array {

    }
}