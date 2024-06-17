<?php

class BlogController extends AbstractController
{ 
    public function home() : void
    {
        $postManager = new PostManager();
        $categoryManager = new CategoryManager(); 
        $posts = $postManager->findLatest();
        $categories = $categoryManager->findAll();
        $this->render("home", [ "posts" => $posts, "categories" => $categories]);
    }

    public function category(int $categoryId) : void
    {
        $data = [];
        $categoryManager = new CategoryManager();
        $postManager = new PostManager();
        $categoryManager = new CategoryManager();
        $category = $categoryManager->findOne($categoryId);     
        $posts = $postManager->findByCategory($categoryId);
        $categories = $categoryManager->findAll();
        if($category !== null) {
            $this->render("category", ["category" => $category, "posts" => $posts, "categories" => $categories]);
        } else {
            $this->redirect("index.php");
        }
    }

    public function post(int $postId) : void
    {
        $postManager = new PostManager();
        $categoryManager = new CategoryManager();
        $commentManager = new CommentManager();
        $post = $postManager->findOne($postId);
        $categories = $categoryManager->findAll();
        $comments = $commentManager->findByPost($postId);
        if($post !== null) {
            $this->render("post", ["post" => $post, "categories" => $categories, "comments" => $comments]);
        } else {
            $this->redirect("index.php");
        }
    }

    public function checkComment() : void
    {
        $tokenCSRF = new CSRFTokenManager();
        $verifToken = $tokenCSRF->validateCSRFToken($_POST['csrf-token']);
        if($verifToken) {
            if(isset($_POST['comment'])) {
                $userManager = new UserManager();
                $postManager = new PostManager();
                $commentManager = new CommentManager();
                $user = $userManager->findById($_SESSION['id']);            
                $post = $postManager->findOne($_GET['post']);     
                $comment = new Comment(htmlspecialchars($_POST['comment']), $user, $post);             
                $commentManager->create($comment);
                $this->redirect("index.php?route=post&post_id={$_GET['post']}");
            } else {
                $this->redirect("index.php?route=comment");
            }
        } else {
            $this->redirect("index.php?route=comment");
        }
        
    }

    public function comment() : void
    {
        $this->render("add-comment", []);
    }
}