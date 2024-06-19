<?php
require_once './modal/users.php';
require_once './helper/session_helpers.php';

class User {
    private $userModel;

    public function __construct() {
        $this->userModel = new Users;
    }

    public function register() {
        // Process form
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'firstname' => trim($_POST['firstname']),
                'lastname' => trim($_POST['lastname']),
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'cpassword' => trim($_POST['cpassword']),
                'photo' => $_FILES['avatar']
            ];

            // Validate inputs
            if (empty($data['firstname'])) {
                flash("register", "Please enter your first name");
                redirect("signup.php");
            }
            if (empty($data['lastname'])) {
                flash("register", "Please enter your last name");
                redirect("signup.php");
            }
            if (!preg_match("/^[a-zA-Z0-9]*$/", $data['username'])) {
                flash("register", "Invalid username, Please enter your username");
                redirect("signup.php");
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                flash("register", "Invalid email");
                redirect("signup.php");
            }
            if (empty($data['password'])) {
                flash("register", "Please enter a password");
                redirect("signup.php");
            } elseif (strlen($data['password']) < 8) {
                flash("register", "Password must be at least 8 characters");
                redirect("signup.php");
            }
            if (empty($data['cpassword'])) {
                flash("register", "Please confirm your password");
                redirect("signup.php");
            } elseif ($data['password'] !== $data['cpassword']) {
                flash("register", "Passwords do not match");
                redirect("signup.php");
            }
            if (empty($data['photo']['name'])) {
                flash("register", "Please upload a photo");
                redirect("signup.php");
            }

            // User with the same email or username already exists
            if ($this->userModel->findUserByEmailOrUsername($data['email'], $data['username'])) {
                flash("register", "Username or email already taken");
                redirect("signup.php");
            }

            if (!flash("register")) {
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Handle file upload
                $time = time(); // Make each image name unique using the timestamp
                $fileName = $time . basename($data['photo']['name']);
                $targetDir = "../image/";
                $targetFile = $targetDir . $fileName;
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Check file type
                $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($imageFileType, $validExtensions)) {
                    flash("register", "Only JPG, JPEG, PNG, and GIF files are allowed");
                    redirect("signup.php");
                }

                // Check if file already exists
                if (file_exists($targetFile)) {
                    flash("register", "File already exists");
                    redirect("signup.php");
                }

                // Check file size (limit to 2MB)
                if ($data['photo']['size'] > 2000000) {
                    flash("register", "File size should be less than 2MB");
                    redirect("signup.php");
                }

                if (!flash("register")) {
                    if (move_uploaded_file($data['photo']['tmp_name'], $targetFile)) {
                        $data['photo_name'] = $fileName;
                        $data['is_admin'] = 0;
                        if ($this->userModel->register($data)) {
                            flash("register", "Registration successful", 'alert__message success');
                            redirect("signing.php");
                        } else {
                            flash("register", "Registration failed");
                            redirect("signup.php");
                        }
                    } else {
                        flash("register", "Failed to upload photo");
                        redirect("signup.php");
                    }
                } else {
                    flash("register", "Something went wrong");
                    redirect("signup.php");
                }
            } else {
                flash("register", "Something went wrong!!");
                redirect("signup.php");
            }
        }
    }

    public function login(){
         // sanitize Post data
         $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        //  init data
        $data= [
            'username_email' => trim($_POST['username_email']),
            'password' => trim($_POST['password'])
        ];

        if(!$data['username_email'] || !$data['password']){
            flash("login", "Please fill out all input");
            redirect("signing.php");
        }

        if($this->userModel->findUserByEmailOrUsername($data['username_email'], $data['username_email'])){
            // user found
            $loggedInUser = $this->userModel->login($data['username_email'], $data['password']);

            if($loggedInUser){
                // create session
                $this->createUserSession($loggedInUser);

            }else{
                flash("login", "Password Incorrect");
                redirect("signing.php");
            }
        }else{
            flash("login", "No user found");
                redirect("signing.php");
        }
    }

    public function createUserSession($user){
        $_SESSION['uid'] = $user->user_id;
        $_SESSION['username'] = $user->username;
        $_SESSION['email'] = $user->email;
        $_SESSION['user-is-admin'] = $user->is_admin == 1;
        redirect("admin/");
    }

    public function getUserAvatar() {
        if (isset($_SESSION['uid'])) {
            return $this->userModel->getUserAvatarById($_SESSION['uid']);
        }
        return null;
    }
}

$init = new User;

// Ensuring user is sending a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch ($_POST['type']) {
        case 'register':
            $init->register();
            break;
        case 'login':
            $init->login();
            break;
        
        default:
            redirect("index.php");
            break;
    }
}
?>
