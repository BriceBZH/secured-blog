<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class CommentManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findByPost(int $postId) : array {
        $query = $this->db->prepare('SELECT * FROM comments WHERE post_id = :postId');
        $parameters = [
            "postId" => $postId
        ];
        $query->execute($parameters);
        $commentDB = $query->fetch(PDO::FETCH_ASSOC);

        if($commentDB) {
            $userManager = new UserManager();
            $user = $userManager->findById($commentDB["user_id"]);
            $postManager = new UserManager();
            $post = $postManager->findOne($commentDB["post_id"]);
            $comment = new Comment($commentDB['content'], $user, $post);
            $comment->setId($commentDB["id"]);

            return $comment;
        }

        return null;
    }

    public function create(Comment $comment) : void {
        $query = $this->db->prepare('INSERT INTO comments (content, user_id, post_id) VALUES (:content, :userId, :postId)');
        $parameters = [
            "content" => $user->getContent(),
            "userId" => $user->getUser(),
            "postId" => $user->getPost(),
        ];

        $query->execute($parameters);

        $comment->setId($this->db->lastInsertId());
    }
}