<?php
require_once('../config.php');

class Users extends DBConnection {
    private $settings;

    public function __construct(){
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
    }

    public function save_users() {
        $data = '';
        $passwordChanged = false;

        // Get posted data
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $username = $_POST['username'];
        $newPassword = isset($_POST['newPassword']) ? $_POST['newPassword'] : '';

        // Build the data string for SQL
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'password', 'newPassword', 'confirmPassword', 'oldPassword'))) {
                if (!empty($data)) $data .= ", ";
                $data .= "{$k} = '{$v}'";
            }
        }

        // Check if a new password is provided
        if (!empty($newPassword)) {
            $password = md5($newPassword); // Hash the new password with MD5
            if (!empty($data)) $data .= ", ";
            $data .= "password = '{$password}'";
            $passwordChanged = true;
        }

        // Handle avatar upload if provided
        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = 'uploads/' . strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], '../' . $fname);
            if ($move) {
                $data .= ", avatar = '{$fname}'";
                if (isset($_SESSION['userdata']['avatar']) && is_file('../' . $_SESSION['userdata']['avatar']) && $_SESSION['userdata']['id'] == $id) {
                    unlink('../' . $_SESSION['userdata']['avatar']);
                }
            }
        }

        // Check if username already exists (excluding the current user)
        $existingUserQuery = $this->conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $existingUserQuery->bind_param("si", $username, $id);
        $existingUserQuery->execute();
        $result = $existingUserQuery->get_result();
        if ($result->num_rows > 0) {
            return json_encode(['status' => 'error', 'message' => 'Username already exists.']);
            exit(); // Ensure no further code execution
        }

        // Insert or update the user record
        if ($id == 0) {
            // Insert new user
            $qry = $this->conn->query("INSERT INTO users SET {$data}");
            if ($qry) {
                $this->settings->set_flashdata('success', 'User details successfully saved.');
                return json_encode(['status' => 'success']);
            } else {
                return json_encode(['status' => 'error', 'message' => 'Failed to save user details.']);
            }
        } else {
            // Update existing user
            $qry = $this->conn->query("UPDATE users SET {$data} WHERE id = {$id}");
            if ($qry) {
                $this->settings->set_flashdata('success', 'User details successfully updated.');
                if ($_SESSION['userdata']['id'] == $id) {
                    foreach ($_POST as $k => $v) {
                        if ($k != 'id' && !in_array($k, array('password', 'newPassword', 'confirmPassword', 'oldPassword'))) {
                            $this->settings->set_userdata($k, $v);
                        }
                    }
                    if (isset($fname) && isset($move)) {
                        $this->settings->set_userdata('avatar', $fname);
                    }
                }
                return json_encode([
                    'status' => 'success',
                    'password_changed' => $passwordChanged
                ]);
            } else {
                return json_encode(['status' => 'error', 'message' => 'Failed to update user details.']);
                exit(); // Ensure no further code execution
            }
        }
    }

    public function change_password() {
        extract($_POST);
        $id = $this->settings->userdata('id');
        $oldPassword = md5($oldPassword);
        $newPassword = md5($newPassword);

        $qry = $this->conn->query("SELECT password FROM users WHERE id = '$id'");
        if ($qry->num_rows > 0) {
            $row = $qry->fetch_assoc();
            $currentPassword = $row['password'];

            if ($currentPassword == $oldPassword) {
                $update = $this->conn->query("UPDATE users SET password = '$newPassword' WHERE id = '$id'");
                if ($update) {
                    return 1; // Success
                } else {
                    return 2; // Update failed
                }
            } else {
                return 0; // Old password is incorrect
            }
        }
    }

   public function save_uusers() {
    try {
        error_log('Starting save_uusers function'); // Debugging log
        
        $data = '';
        $passwordChanged = false;
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0; // Get user ID, 0 if new user
        $username = isset($_POST['username']) ? $this->conn->real_escape_string($_POST['username']) : '';

        error_log('Username: ' . $username); // Debugging log
        
        // Check if username is already in use
        $duplicateCheckQuery = "SELECT id FROM users WHERE username = '{$username}'";
        if (!empty($id)) {
            $duplicateCheckQuery .= " AND id != {$id}"; // Exclude current user if updating
        }
        
        error_log('Running duplicate check query'); // Debugging log
        
        $duplicateCheck = $this->conn->query($duplicateCheckQuery);
        if (!$duplicateCheck) {
            throw new Exception('Error in duplicate check query: ' . $this->conn->error);
        }
        if ($duplicateCheck->num_rows > 0) {
            throw new Exception('Username already exists. Please choose another username.');
        }

        // Build the data string for SQL
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'password'))) {
                if (!empty($data)) $data .= ", ";
                $safe_value = $this->conn->real_escape_string($v); // Escape all values
                $data .= "{$k} = '{$safe_value}'";
            }
        }

        // Check if a new password is provided
        if (!empty($_POST['password'])) {
            $password = md5($_POST['password']); // Hash the new password with MD5
            if (!empty($data)) $data .= ", ";
            $data .= "password = '{$password}'";
            $passwordChanged = true;
        }

        // Handle avatar upload if provided
        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = 'uploads/' . strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], '../' . $fname);
            if ($move) {
                $data .= ", avatar = '{$fname}'";
                if (isset($_SESSION['userdata']['avatar']) && is_file('../' . $_SESSION['userdata']['avatar']) && $_SESSION['userdata']['id'] == $id) {
                    unlink('../' . $_SESSION['userdata']['avatar']);
                }
            }
        }

        // Insert or update user
        if (empty($id)) {
            error_log('Inserting new user'); // Debugging log
            // Insert new user
            $qry = $this->conn->query("INSERT INTO users SET {$data}");
            if (!$qry) {
                throw new Exception('Failed to save user details: ' . $this->conn->error);
            }
            $id = $this->conn->insert_id; // Get the ID of the newly inserted user
            $action = "added"; // For activity logging
        } else {
            error_log('Updating user with ID: ' . $id); // Debugging log
            // Update existing user
            $qry = $this->conn->query("UPDATE users SET {$data} WHERE id = {$id}");
            if (!$qry) {
                throw new Exception('Failed to update user details: ' . $this->conn->error);
            }
            $action = "updated"; // For activity logging
        }

        error_log('User saved successfully'); // Debugging log

        // Return success response with user ID and action type
        echo json_encode([
            'status' => 'success',
            'user_id' => $id, // Return the user ID
            'password_changed' => $passwordChanged,
            'action' => $action // Indicate whether it was an insert or update
        ]);
        exit();

    } catch (Exception $e) {
        error_log('Error: ' . $e->getMessage()); // Debugging log
        // Catch any errors and return as JSON
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
        exit();
    }
}


}

$users = new Users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
    case 'savee':
        echo $users->save_uusers();
        break;
    case 'change_password':
        echo $users->change_password();
        break;
    case 'save':
        echo $users->save_users();
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
        break;
}
