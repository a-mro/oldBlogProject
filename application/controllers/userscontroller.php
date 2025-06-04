<?php
session_start();

class UsersController extends Controller
{

    public function index()
    {
        $this->_view->set('title', 'Login');
        return $this->_view->output();
    }

    public function register()
    {
        $this->_view->set('title', 'Register');
        return $this->_view->output();
    }


    public function login()
    {

        if (!isset($_POST['loginSubmit'])) {
            header('Location: /users/index');
        }


        $userName = isset($_POST['username']) ? trim($_POST['username']) : "";
        $password = isset($_POST['password']) ? trim($_POST['password']) : "";


        try {

            $user = new UsersModel();
            $user->setUserName($userName);
            $user->setPassword($password);


            $login = $user->log();

            if (!empty($login)) {
                $this->_setView('result');
                $this->_view->set('logresult', 'Welcome back ' . $userName);
                $this->_view->set('resType', 'Login:');
                $_SESSION["username"] = $login['username'];
                $_SESSION["user_id"] = $login['user_id'];


            } else {
                $this->_setView('result');
                $this->_view->set('logresult', 'Invalid Username or Password');
                $this->_view->set('resType', 'Login:');
            }
        } catch (Exception $e) {
            $this->_setView('result');
            $this->_view->set('error', 'Login failure!');
            $this->_view->set('loginerror', $e->getMessage());
        }
        $login = false;
        return $this->_view->output();


    }

    public function logout()
    {


        session_destroy();
        $this->_setView('result');
        $this->_view->set('logresult', 'Goodbye!');
        $this->_view->set('resType', 'Log out');
        return $this->_view->output();

    }

    public static function _userExists($username)
    {

        $user = new UsersModel();
        $user->setUsername($username);

        $exists = $user->userExists();

        if (empty($exists)) {
            return false;
        } else {
            return true;
        }

    }

    public static function _emailExists($email)
    {

        $user = new UsersModel();
        $user->setEmail($email);

        $exists = $user->emailExists();

        if (empty($exists)) {
            return false;
        } else {
            return true;
        }

    }

    public function registerPost()
    {

        if (!isset($_POST['registerFormSubmit'])) {
            header('Location: /users/index');
        }

        $errors = array();
        $check = true;


        $firstName = isset($_POST['name']) ? trim($_POST['name']) : null;
        $lastName = isset($_POST['last_name']) ? trim($_POST['last_name']) : null;
        $email = isset($_POST['email']) ? trim($_POST['email']) : "";
        $username = isset($_POST['username']) ? trim($_POST['username']) : "";
        $password = isset($_POST['password']) ? trim($_POST['password']) : null;

        if (empty($firstName)) {
            $check = false;
            array_push($errors, "First name is required!");
        }

        if (empty($lastName)) {
            $check = false;
            array_push($errors, "Last name is required!");
        }

        if (empty($email)) {
            $check = false;
            array_push($errors, "E-mail is required!");
        } else {
            if (UsersController::_emailExists($email) == true) {
                $check = false;
                array_push($errors, "E-mail already registered!");
            } else {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $check = false;
                    array_push($errors, "Invalid E-mail!");
                }
            }
        }

        if (empty($username)) {
            $check = false;
            array_push($errors, "You need to choose a username!");
        } else {
            if (UsersController::_userExists($username) == true) {
                $check = false;
                array_push($errors, "Username already in use!");
            }
        }

        if (!$check) {
            $this->_setView('result');
            $this->_view->set('title', 'Invalid form data!');
            $this->_view->set('errors', $errors);
            $this->_view->set('resType', 'Register:');
            return $this->_view->output();
        }


        try {

            $user = new UsersModel();
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setPassword($password);
            $user->saveUser();

            $this->_setView('result');
            $this->_view->set('title', 'Register success!');
            $this->_view->set('resType', '');

            $data = array(
                'userName' => $username,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'password' => $password
            );

            $this->_view->set('userData', $data);

        } catch (Exception $e) {
            $this->_setView('result');
            $this->_view->set('title', 'There was an error saving the data!');
            $this->_view->set('formData', $_POST);
            $this->_view->set('saveError', $e->getMessage());
        }

        return $this->_view->output();

    }


}