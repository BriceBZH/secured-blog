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
        $query = $this->db->prepare('SELECT posts.id, posts.title, posts.excerpt, posts.content, posts.created_at, posts.author, posts_categories.category_id FROM posts JOIN posts_categories ON posts.id = posts_categories.post_id GROUP BY posts.id ORDER BY created_at LIMIT 4');
        $query->execute();
        $postDB = $query->fetchAll(PDO::FETCH_ASSOC);
        $posts = [];

        foreach($postDB as $postFor) {
            $userManager = new UserManager();
            $user = $userManager->findById($postFor["author"]);
            $categoryManager = new CategoryManager();
            $category = $categoryManager->findOne($postFor["category_id"]);
            $post = new Post($postFor["title"], $postFor["excerpt"], $postFor["content"], $user, DateTime::createFromFormat('Y-m-d H:i:s', $postFor["created_at"]), $category);
            $post->setId($postFor["id"]);
            $posts[] = $post;
        }
        
        return $posts;
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