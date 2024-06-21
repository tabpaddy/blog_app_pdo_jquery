<?php
require_once '../modal/users.php';
require_once '../util/util.php';
require_once '../helper/session_helpers.php';

class User {
    private $userModel;

    public function __construct() {
        $this->userModel = new Users;
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'firstname' => trim($_POST['firstname']),
                'lastname' => trim($_POST['lastname']),
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'cpassword' => trim($_POST['cpassword']),
                'userrole' => trim($_POST['userrole']),
                'photo' => $_FILES['avatar']
            ];

            if (empty($data['firstname'])) {
                echo json_encode(['status' => 'error', 'message' => 'Please enter your first name', 'data' => $data]);
                return;
            }
            if (empty($data['lastname'])) {
                echo json_encode(['status' => 'error', 'message' => 'Please enter your last name', 'data' => $data]);
                return;
            }
            if (!preg_match("/^[a-zA-Z0-9]*$/", $data['username'])) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid username, Please enter your username', 'data' => $data]);
                return;
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid email', 'data' => $data]);
                return;
            }
            if (empty($data['password'])) {
                echo json_encode(['status' => 'error', 'message' => 'Please enter a password', 'data' => $data]);
                return;
            } elseif (strlen($data['password']) < 8) {
                echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters', 'data' => $data]);
                return;
            }
            if (empty($data['cpassword'])) {
                echo json_encode(['status' => 'error', 'message' => 'Please confirm your password', 'data' => $data]);
                return;
            } elseif ($data['password'] !== $data['cpassword']) {
                echo json_encode(['status' => 'error', 'message' => 'Passwords do not match', 'data' => $data]);
                return;
            }
            if (empty($data['userrole']) && $data['userrole'] !== '0') {
                echo json_encode(['status' => 'error', 'message' => 'Please select a role', 'data' => $data]);
                return;
            }
            if (empty($data['photo']['name'])) {
                echo json_encode(['status' => 'error', 'message' => 'Please upload a photo', 'data' => $data]);
                return;
            }

            if ($this->userModel->findUserByEmailOrUsername($data['email'], $data['username'])) {
                echo json_encode(['status' => 'error', 'message' => 'Username or email already taken', 'data' => $data]);
                return;
            }

            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            $time = time();
            $fileName = $time . basename($data['photo']['name']);
            $targetDir = "../../image/";
            $targetFile = $targetDir . $fileName;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $validExtensions)) {
                echo json_encode(['status' => 'error', 'message' => 'Only JPG, JPEG, PNG, and GIF files are allowed', 'data' => $data]);
                return;
            }

            if (file_exists($targetFile)) {
                echo json_encode(['status' => 'error', 'message' => 'File already exists', 'data' => $data]);
                return;
            }

            if ($data['photo']['size'] > 2000000) {
                echo json_encode(['status' => 'error', 'message' => 'File size should be less than 2MB', 'data' => $data]);
                return;
            }

            if (move_uploaded_file($data['photo']['tmp_name'], $targetFile)) {
                $data['photo_name'] = $fileName;
                if ($this->userModel->register($data)) {
                    echo json_encode(['status' => 'success', 'message' => 'User registered successfully', 'redirect' => 'manage-user.php']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'User registration failed', 'data' => $data]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload photo', 'data' => $data]);
            }
        }
    }

    public function getUsers() {
        if (isset($_SESSION['uid'])) {
            $user = $this->userModel->getAlluser($_SESSION['uid']);
            $output = '';
            if ($user) {
                $output .= '<thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Edit</th>
                    <th>Delete</th>
                    <th>Admin</th>
                </tr>
                </thead>
                <tbody>';

                foreach ($user as $row) {
                    $output .= '<tr>
                    <td>'.$row->firstname.' '.$row->lastname.'</td>
                    <td>'.$row->username.'</td>
                    <td><a href="" data-id="'.$row->user_id.'" class="btn sm userEdit">Edit</a></td>
                    <td><a href="" data-id="'.$row->user_id.'" class="btn sm danger userDelete">Delete</a></td>
                    <td>'.($row->is_admin ? "yes" : "no").'<td/>
                </tr>';
                }

                $output .= '</tbody>';
                echo $output;
            } else {
                echo '<div class="alert__message error">No user found</div>';
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize post
            $_POST = filter_input_array(INPUT_POST, [
                'user_id' => FILTER_SANITIZE_NUMBER_INT,
                'firstname' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                'userrole' => FILTER_SANITIZE_NUMBER_INT,
                'lastname' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
            ]);
    
            $data = [
                'user_id' => trim($_POST['user_id']),
                'firstname' => trim($_POST['firstname']),
                'lastname' => trim($_POST['lastname']),
                'userrole' => trim($_POST['userrole'])
            ];
    
            if (empty($data['firstname'])) {
                echo json_encode(['status' => 'error', 'message' => 'Please enter your first name', 'data' => $data]);
                return;
            }
            if (empty($data['lastname'])) {
                echo json_encode(['status' => 'error', 'message' => 'Please enter your last name', 'data' => $data]);
                return;
            }
            if (empty($data['userrole']) && $data['userrole'] !== '0') {  // Adjust the check to allow for '0'
                echo json_encode(['status' => 'error', 'message' => 'Please select a role', 'data' => $data]);
                return;
            }
            
            if ($this->userModel->update($data)) {
                echo json_encode(['status' => 'success', 'message' => 'User Updated successfully', 'redirect' => 'manage-user.php']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Update failed', 'data' => $data]);
            }       
        }
    }
    

    public function delete() {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    
        if ($user = $this->userModel->getSingleUserById($id)) {
            $user_avatar = $user->avatar;
            $avatar_path = '../../image/' . $user_avatar;
    
            // Delete avatar if it exists
            if (file_exists($avatar_path)) {
                unlink($avatar_path);
            }
    
            // Delete user posts thumbnails if any
            $thumbnails = $this->userModel->getPostThumbnailsByUserId($id);
            if ($thumbnails && is_array($thumbnails)) {
                foreach ($thumbnails as $thumbnail) {
                    $thumbnail_path = '../../image/' . $thumbnail->thumbnail;
                    if (file_exists($thumbnail_path)) {
                        unlink($thumbnail_path);
                    }
                }
            }
    
            // Delete user and their posts
            if ($this->userModel->deleteUser($id) && $this->userModel->deleteUserPosts($id)) {
                echo json_encode(['status' => 'success', 'message' => 'User and related posts deleted successfully', 'redirect' => 'manage-user.php']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error occurred during deletion']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
        }
    }
    

    }


$init = new User;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['addUser'])) {
        $init->register();
    } elseif (isset($_POST['updateUser'])) {
        $init->update();
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['user'])) {
    $init->getUsers();
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['deleteUser'])) {
    $init->delete();
} else {
    redirect('admin/index.php');
}
?>


<?php
// Assuming you have a list of users displayed here
// foreach ($users as $user) {
//     echo "<tr>";
//     echo "<td>{$user->firstname}</td>";
//     echo "<td>{$user->lastname}</td>";
//     echo "<td>{$user->email}</td>";
//     echo "<td><a href='edit-user.php?id={$user->user_id}'>Edit</a></td>";
//     echo "<td><a href='delete-user.php?id={$user->user_id}' class='delete-btn' data-id='{$user->user_id}'>Delete</a></td>";
//     echo "</tr>";
// }
?>
