<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class AuthController extends AbstractController
{
    public function __construct()
    {
        $lang = $_SESSION["lang"];

        parent::__construct("auth", $lang);
    }

    public function getTranslator() : Translator
    {
        return $this->translator;
    }

    public function login() : void
    {
        $data = [];
        $categoryManager = new CategoryManager();
        $data['categories'] = $categoryManager->findAll();
        $this->render("login", $data);
    }

    public function checkLogin() : void
    {
        //pour commencer on test le tokenCSRF
        $tokenCSRF = new CSRFTokenManager();
        $verifToken = $tokenCSRF->validateCSRFToken($_POST['csrf-token']);
        echo $_POST['csrf-token']." ".$_SESSION['csrf_token'];
        if($verifToken) {
            if(isset($_POST['password']) && isset($_POST['email'])) {
                $userManager = new UserManager();
                $user = $userManager->findByEmail(htmlspecialchars($_POST['email']));
                if($user) { //email connu
                    $isPasswordCorrect = password_verify(htmlspecialchars($_POST['password']), $user->getPassword());
                    if($isPasswordCorrect === true) { //mdp correct
                        $_SESSION["email"] = $user->getEmail();
                        $_SESSION["username"] = $user->getUsername();
                        $_SESSION["role"] = $user->getRole();
                        $_SESSION["id"] = $user->getId();
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
        } else {
            $this->redirect("index.php?route=login");
        }
    }

    public function register() : void
    {
        $data = [];
        $categoryManager = new CategoryManager();
        $data['categories'] = $categoryManager->findAll();
        $this->render("register", $data);
    }

    public function checkRegister() : void
    {
        //pour commencer on test le tokenCSRF
        $tokenCSRF = new CSRFTokenManager();
        $verifToken = $tokenCSRF->validateCSRFToken($_POST['csrf-token']);
        echo $_POST['csrf-token']." ".$_SESSION['csrf_token'];
        if($verifToken) {
            if((isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm-password'])) && $_POST['password'] === $_POST['confirm-password']) {
                $userManager = new UserManager();
                $userDB = $userManager->findByEmail(htmlspecialchars($_POST['email']));
                if(!$userDB) { //user déjà existant avec cet email
                    //on test le mdp (Les mots de passe doivent faire 8 caractères au minimum, avec au moins une majuscule, une minuscule, un chiffre et un caractère spécial)
                    $password_regex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^\w\d\s])(?=.*[a-zA-Z\d\W\S]).{8,}$/";
                    if(preg_match($password_regex, $_POST['password'])) {
                        $user = new User(htmlspecialchars($_POST['username']), htmlspecialchars($_POST['email']), password_hash($_POST['password'], PASSWORD_BCRYPT), "USER", DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));         
                        $userManager->create($user);
                        $this->redirect("index.php");
                    } else {
                        $this->redirect("index.php?route=register");
                    }
                } else {
                    $this->redirect("index.php?route=register");
                }
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

    public function switchLang()
    {
        if($_SESSION["lang"] === "fr")
        {
            $_SESSION["lang"] = "en";
        }
        else
        {
            $_SESSION["lang"] = "fr";
        }

        $this->redirect("index.php");
    }
}