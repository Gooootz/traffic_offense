<?php
require_once '../config.php';
class Login extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;

		parent::__construct();
		ini_set('display_error', 1);
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function index(){
		echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
	}

	public function login() {
    extract($_POST);

    // Fetch user details including status
    $qry = $this->conn->query("SELECT * FROM users WHERE username = '$username'");

    if ($qry->num_rows > 0) {
        $row = $qry->fetch_assoc();

        // Check if the account is inactive
        if ($row['status'] == 0) {
            echo json_encode(array('status' => 'inactive', 'message' => 'Your account is inactive. Please contact the administrator.'));
            return;
        }

        // Verify the password
        if (md5($password) === $row['password']) {
            // Set session or other login success operations
            foreach ($row as $k => $v) {
                if ($k !== 'password') {
                    $this->settings->set_userdata($k, $v);
                }
            }
            $this->settings->set_userdata('login_type', 1);
            echo json_encode(array('status' => 'success', 'redirect' => 'index.php'));
        } else {
            echo json_encode(array('status' => 'incorrect', 'message' => 'Invalid username or password.'));
        }
    } else {
        echo json_encode(array('status' => 'incorrect', 'message' => 'Invalid username or password.'));
    }
}



	
	public function logout(){
		if($this->settings->sess_des()){
			redirect('admin/login.php');
		}
	}
	function login_user(){
		extract($_POST);
		$qry = $this->conn->query("SELECT * from clients where email = '$email' and password = md5('$password') ");
		if($qry->num_rows > 0){
			foreach($qry->fetch_array() as $k => $v){
				$this->settings->set_userdata($k,$v);
			}
			$this->settings->set_userdata('login_type',1);
		$resp['status'] = 'success';
		}else{
		$resp['status'] = 'incorrect';
		}
		if($this->conn->error){
			$resp['status'] = 'failed';
			$resp['_error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
}
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();
switch ($action) {
	case 'login':
		echo $auth->login();
		break;
	case 'login_user':
		echo $auth->login_user();
		break;
	case 'logout':
		echo $auth->logout();
		break;
	default:
		echo $auth->index();
		break;
}

