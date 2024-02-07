<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class BlogController extends AbstractController
{
    public function home() : void
    {
        $data = [];
        $postManager = new PostManager();
        $data['posts'] = $postManager->findLatest();
        $categoryManager = new CategoryManager();
        $data['categories'] = $categoryManager->findAll();
        $this->render("home", [$data]);
    }

    public function category(int $categoryId) : void
    {
        $data = [];
        $categoryManager = new CategoryManager();
        $data['category'] = $categoryManager->findOne($categoryId);
        $postManager = new PostManager();
        $data['posts'] = $postManager->findByCategory($categoryId);
        $categoryManager = new CategoryManager();
        $data['categories'] = $categoryManager->findAll();
        if($data) {
            $this->render("category", [$data]);
        } else {
            $this->redirect("index.php");
        }
    }

    public function post(int $postId) : void
    {
        $data = [];
        $postManager = new PostManager();
        $data['post'] = $postManager->findOne($postId);
        $categoryManager = new CategoryManager();
        $data['categories'] = $categoryManager->findAll();
        if($data) {
            $this->render("post", [$data]);
        } else {
            $this->redirect("index.php");
        }
    }

    public function checkComment() : void
    {
        $this->redirect("index.php?route=post&post_id={$_POST["post_id"]}");
    }
}