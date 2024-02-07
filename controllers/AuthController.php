<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class AuthController extends AbstractController
{
    public function login() : void
    {
        $this->render("login", []);
    }

    public function checkLogin() : void
    {
        if(isset($_POST['password']) && isset($_POST['email'])) {
            $userManager = new UserManager();
            $user = $userManager->findByEmail($_POST['email']);
            if($user) { //email connu
                $isPasswordCorrect = password_verify($_POST['password'], $user->getPassword());
                if($isPasswordCorrect === true) { //mdp correct
                    $_SESSION["email"] = $user->getEmail();
                    $_SESSION["username"] = $user->getUsername();
                    $_SESSION["role"] = $user->getRole();
                    $this->redirect("index.php");
                } else {
                    $this->redirect("index.php?route=login");
                }
            } else {
                $this->redirect("index.php?route=login");
            }
        } else {
            $this->redirect("index.php?route=login");
        }
    }

    public function register() : void
    {
        $this->render("register", []);
    }

    public function checkRegister() : void
    {
        if((isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm-password'])) && $_POST['password'] === $_POST['confirm-password']) {
            $userManager = new UserManager();
            $userDB = $userManager->findByEmail($_POST['email']);
            if(!$userDB) { //user déjà existant avec cet email
                $user = new User($_POST['username'], $_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT), "USER", DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));         
                $userManager->create($user);
                $this->redirect("index.php");
            } else {
                $this->redirect("index.php?route=register");
            }
        } else {
            $this->redirect("index.php?route=register");
        }
    }

    public function logout() : void
    {
        session_destroy();

        $this->redirect("index.php");
    }
}