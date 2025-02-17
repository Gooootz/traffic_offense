<?php
require_once ('../config.php');
class Master extends DBConnection
{
	private $settings;
	protected $permitted_chars; // Declare the property as protected
	public function __construct()
	{
		global $_settings;
		$this->settings = $_settings;
		$this->permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	function capture_err()
	{
		if (!$this->conn->error)
			return false;
		else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
		}
	}

	function print_activity()
    {
        // Fetch user information from the session
        $user = $_SESSION['userdata']; // Retrieve the current logged-in user's information

        // Default user values if not found
        if (!$user) {
            $user = array('firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown', 'type' => 0);
        }

        // Determine the description
        $description = 'Printed TOP';

        // Determine the user type label based on the type value
        $user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';

        // Log the activity
        $sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) 
                VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Apprehension Record', '{$description}')";
        $result = $this->conn->query($sql);
    }

	

	function export_activity()
    {
        // Fetch user information from the session
        $user = $_SESSION['userdata']; // Retrieve the current logged-in user's information

        // Default user values if not found
        if (!$user) {
            $user = array('firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown', 'type' => 0);
        }

        // Determine the description
        $description = 'Export Reports';

        // Determine the user type label based on the type value
        $user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';

        // Log the activity
        $sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) 
                VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Reports', '{$description}')";
        $result = $this->conn->query($sql);
    }

	function settings_activity()
    {
        // Fetch user information from the session
        $user = $_SESSION['userdata']; // Retrieve the current logged-in user's information

        // Default user values if not found
        if (!$user) {
            $user = array('firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown', 'type' => 0);
        }

        // Determine the description
        $description = 'Updated Settings';

        // Determine the user type label based on the type value
        $user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';

        // Log the activity
        $sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) 
                VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Settings', '{$description}')";
        $result = $this->conn->query($sql);
    }

	function adduser_activity($id = null, $description = '') {
		// Fetch user information from the session
		$user = isset($_SESSION['userdata']) ? $_SESSION['userdata'] : null;

		// Default user values if not found
		if (!$user) {
			$user = array(
				'firstname' => 'Unknown', 
				'lastname' => 'User', 
				'username' => 'Unknown', 
				'type' => 0
			);
		}

		// Use the custom description if provided, otherwise default to 'Updated User Details'
		$description = 'Added A New User';

		// Log the received $id and $description for debugging purposes
		error_log("User activity log ID: " . $id);
		error_log("User activity description: " . $description);

		// Determine the user type label
		$user_type_label = ($user['type'] == 1) ? 'Admin' : 'Staff';

		// Log the activity in the database
		$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) 
				VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'User List', '{$description}')";

		// Execute the query and handle the result
		if ($this->conn->query($sql)) {
			return true;
		} else {
			return false;
		}
	}

	function updateuserr_activity($id = null, $description = '') {
		// Fetch user information from the session
		$user = isset($_SESSION['userdata']) ? $_SESSION['userdata'] : null;

		// Default user values if not found
		if (!$user) {
			$user = array(
				'firstname' => 'Unknown', 
				'lastname' => 'User', 
				'username' => 'Unknown', 
				'type' => 0
			);
		}

		// Use the custom description if provided, otherwise default to 'Updated User Details'
		$description = 'Updated User Details';

		// Log the received $id and $description for debugging purposes
		error_log("User activity log ID: " . $id);
		error_log("User activity description: " . $description);

		// Determine the user type label
		$user_type_label = ($user['type'] == 1) ? 'Admin' : 'Staff';

		// Log the activity in the database
		$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) 
				VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'User List', '{$description}')";

		// Execute the query and handle the result
		if ($this->conn->query($sql)) {
			return true;
		} else {
			return false;
		}
	}





	function updateuser_activity()
    {
        // Fetch user information from the session
        $user = $_SESSION['userdata']; // Retrieve the current logged-in user's information

        // Default user values if not found
        if (!$user) {
            $user = array('firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown', 'type' => 0);
        }

        // Determine the description based on whether it's a new offense or an update
		$description = 'Updated Account Details';

        // Determine the user type label based on the type value
        $user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';

        // Log the activity
        $sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) 
                VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Account', '{$description}')";
        $result = $this->conn->query($sql);
    }

	function printreport_activity()
    {
        // Fetch user information from the session
        $user = $_SESSION['userdata']; // Retrieve the current logged-in user's information

        // Default user values if not found
        if (!$user) {
            $user = array('firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown', 'type' => 0);
        }

        // Determine the description
        $description = 'Printed Reports';

        // Determine the user type label based on the type value
        $user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';

        // Log the activity
        $sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) 
                VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Reports', '{$description}')";
        $result = $this->conn->query($sql);
    }


	function handle_request()
    {
        $action = $_POST['f'] ?? '';
        if ($action === 'print_activity') {
            $this->print_activity();
            echo 'Print Activity logged successfully.';
            return;
        }
		if ($action === 'export_activity') {
            $this->export_activity();
            echo 'Export Activity logged successfully.';
            return;
        }
		if ($action === 'settings_activity') {
            $this->settings_activity();
            echo 'Settings Activity logged successfully.';
            return;
        }
		if ($action === 'adduser_activity') {
            $this->adduser_activity();
            echo 'User Activity logged successfully.';
            return;
        }
		if ($action === 'updateuser_activity') {
            $this->updateuser_activity();
            echo 'User Update Activity logged successfully.';
            return;
        }
		if ($action === 'updateuserr_activity') {
            $this->updateuserr_activity();
            echo 'User Update Activity logged successfully.';
            return;
        }
		if ($action === 'printreport_activity') {
            $this->printreport_activity();
            echo 'Print Report Activity logged successfully.';
            return;
        }

        // Handle other actions here
    }

	function save_offense()
{
    // Check if POST data exists and assign default values if not
    $code = isset($_POST['code']) ? $this->conn->real_escape_string($_POST['code']) : '';
    $offensename = isset($_POST['offensename']) ? $this->conn->real_escape_string($_POST['offensename']) : '';
    $description = isset($_POST['description']) ? addslashes(htmlentities($_POST['description'])) : '';
    $fine = isset($_POST['fine']) ? floatval($_POST['fine']) : 0;
    $fine2 = isset($_POST['fine2']) ? floatval($_POST['fine2']) : 0;
    $fine3 = isset($_POST['fine3']) ? floatval($_POST['fine3']) : 0;
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // Construct data string for SQL query
    $data = "";
    foreach ($_POST as $k => $v) {
        if (!in_array($k, array('id', 'description'))) {
            if (!empty($data)) {
                $data .= ",";
            }
            $data .= "`{$k}`='" . $this->conn->real_escape_string($v) . "'";
        }
    }
    if (!empty($description)) {
        if (!empty($data)) {
            $data .= ",";
        }
        $data .= "`description`='{$description}'";
    }

    // Check for duplicate offense code and name
    $check_code = $this->conn->query("SELECT * FROM `offenses` WHERE `code` = '{$code}' " . ($id ? " AND id != {$id}" : ""))->num_rows;
    $check_name = $this->conn->query("SELECT * FROM `offenses` WHERE `offensename` = '{$offensename}' " . ($id ? " AND id != {$id}" : ""))->num_rows;

    if ($check_code > 0) {
        $resp['status'] = 'failed';
        $resp['msg'] = "Offense code already exists.";
        return json_encode($resp);
    }

    if ($check_name > 0) {
        $resp['status'] = 'failed';
        $resp['msg'] = "Offense name already exists.";
        return json_encode($resp);
    }

    // Save the offense
    if (empty($id)) {
        $sql = "INSERT INTO `offenses` SET {$data}";
    } else {
        $sql = "UPDATE `offenses` SET {$data} WHERE id = '{$id}'";
    }
    $save = $this->conn->query($sql);

    if ($save) {
        $resp['status'] = 'success';
        $message = empty($id) ? "New Offense successfully saved." : "Offense successfully updated.";
        $this->settings->set_flashdata('success', $message);

        // Log the activity
        $user = $_SESSION['userdata'] ?? ['firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown', 'type' => 1];
        $user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';
        $description = empty($id) ? 'Added New Offense' : 'Updated Offense';
        $log_sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (NOW(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Offense List', '{$description}')";
        $this->conn->query($log_sql);
    } else {
        $resp['status'] = 'failed';
        $resp['msg'] = $this->conn->error . "[{$sql}]";
    }

    return json_encode($resp);
}



	function delete_offense()
	{
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `offenses` where id = '{$id}'");

		// Fetch user information from the session
		$user = $_SESSION['userdata']; // Retrieve the current logged-in user's information

		// Default user values if not found
		if (!$user) {
			$user = array('firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown');
		}

		// Determine the description based on whether it's a new offense or an update
		$description = 'Deleted An Offense';

		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "offense successfully deleted.");

			// Determine the user type label based on the type value
			$user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Apprehension Record', '{$description}')";

			// Execute the SQL query
			$result = $this->conn->query($sql);
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function generate_string($input, $strength = 10)
	{

		$input_length = strlen($input);
		$random_string = '';
		for ($i = 0; $i < $strength; $i++) {
			$random_character = $input[mt_rand(0, $input_length - 1)];
			$random_string .= $random_character;
		}

		return $random_string;
	}
	function upload_files()
	{
		extract($_POST);
		$data = "";
		if (empty($upload_code)) {
			while (true) {
				$code = $this->generate_string($this->permitted_chars);
				$chk = $this->conn->query("SELECT * FROM `uploads` where dir_code ='{$code}' ")->num_rows;
				if ($chk <= 0) {
					$upload_code = $code;
					$resp['upload_code'] = $upload_code;
					break;
				}
			}
		}

		if (!is_dir(base_app . 'uploads/blog_uploads/' . $upload_code))
			mkdir(base_app . 'uploads/blog_uploads/' . $upload_code);
		$dir = 'uploads/blog_uploads/' . $upload_code . '/';
		$images = array();
		for ($i = 0; $i < count($_FILES['img']['tmp_name']); $i++) {
			if (!empty($_FILES['img']['tmp_name'][$i])) {
				$fname = $dir . (time()) . '_' . $_FILES['img']['name'][$i];
				$f = 0;
				while (true) {
					$f++;
					if (is_file(base_app . $fname)) {
						$fname = $f . "_" . $fname;
					} else {
						break;
					}
				}
				$move = move_uploaded_file($_FILES['img']['tmp_name'][$i], base_app . $fname);
				if ($move) {
					$this->conn->query("INSERT INTO `uploads` (dir_code,user_id,file_path)VALUES('{$upload_code}','{$this->settings->userdata('id')}','{$fname}')");
					$this->capture_err();
					$images[] = $fname;
				}
			}
		}
		$resp['images'] = $images;
		$resp['status'] = 'success';
		return json_encode($resp);
	}
	function save_driver()
{
    foreach ($_POST as $k => $v) {
        $_POST[$k] = addslashes($v);
    }
    extract($_POST);

    if (!isset($license_id_no) || empty($license_id_no)) {
        $license_id_no = '';
    }

    // Check for duplicate license ID, but allow duplicates for "No License ID"
    if ($license_id_no !== '') {
        $chk = $this->conn->query("SELECT * FROM `drivers_list` WHERE license_id_no = '{$license_id_no}' " . ($id > 0 ? " AND id != '{$id}' " : ""))->num_rows;
        if ($chk > 0) {
            return json_encode(['status' => 'failed', 'msg' => "License ID already exists in the database. Please review and try again."]);
        }
    }

    if (empty($id)) {
        $sql1 = "INSERT INTO `drivers_list` (lastname, middlename, firstname, suffix, address, gender, license_type, license_id_no) VALUES
                ('{$lastname}', '{$middlename}', '{$firstname}', '{$suffix}', '{$address}', '{$gender}', '{$license_type}', '{$license_id_no}')";
        $save1 = $this->conn->query($sql1);
        $driver_id = $this->conn->insert_id;
    } else {
        $sql1 = "UPDATE `drivers_list` SET lastname = '{$lastname}', middlename = '{$middlename}', firstname = '{$firstname}', suffix = '{$suffix}', address = '{$address}', gender = '{$gender}', license_type = '{$license_type}', license_id_no = '{$license_id_no}' WHERE id = '{$id}'";
        $save1 = $this->conn->query($sql1);
    }

    if ($save1) {
        $user = $_SESSION['userdata'] ?? ['firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown'];
        $user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';
        $description = empty($id) ? 'Added New Driver' : 'Updated Driver Details';
        $sql_log = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Driver Management', '{$description}')";
        $this->conn->query($sql_log);
        
        return json_encode(['status' => 'success', 'msg' => empty($id) ? "New Driver successfully saved." : "Driver Details successfully updated."]);
    } else {
        return json_encode(['status' => 'failed', 'err' => $this->conn->error . "[{$sql1}]"]);
    }
}



	function delete_driver()
	{
		extract($_POST);
		$qry = $this->conn->query("SELECT * FROM `drivers_meta` where driver_id = '{$id}'");
		while ($row = $qry->fetch_assoc()) {
			${$row['meta_field']} = $row['meta_value'];
		}
		$del = $this->conn->query("DELETE FROM `drivers_list` where id = '{$id}'");
		
		// Fetch user information from the session
		$user = $_SESSION['userdata']; // Retrieve the current logged-in user's information
		// Default user values if not found
		if (!$user) {
			$user = array('firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown');
		}

		// Determine the description based on whether it's a new offense or an 
		$description = 'Deleted a Driver';

		$this->capture_err();
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Driver's Info successfully deleted.");
			// Determine the user type label based on the type value
			$user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Delete Driver', '{$description}')";

			// Execute the SQL query
			$result = $this->conn->query($sql);
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}

	function save_officer() {
    foreach ($_POST as $k => $v) {
        $_POST[$k] = addslashes($v);
    }
    extract($_POST);
    $chk = $this->conn->query("SELECT * FROM `officers_list` WHERE officer_id_no = '{$officer_id_no}' " . ($id > 0 ? " AND id!= '{$id}' " : ""))->num_rows;

    $user = $_SESSION['userdata'] ?? ['firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown'];
    $description = empty($id) ? 'Added New Officer' : 'Updated Officer Details';

    if ($chk > 0) {
        return json_encode(['status' => 'failed', 'msg' => "Officers ID already exists in the database. Please review and try again."]);
    }

    if (empty($id)) {
        $sql = "INSERT INTO `officers_list` (officer_id_no, lastname, firstname, middlename, suffix, dob, present_address, permanent_address, civil_status, nationality, status, contact, image_path) VALUES 
                ('{$officer_id_no}', '{$lastname}', '{$firstname}', '{$middlename}', '{$suffix}', '{$dob}', '{$present_address}', '{$permanent_address}', '{$civil_status}', '{$nationality}', '{$status}', '{$contact}', '{$image_path}')";
    } else {
        $sql = "UPDATE `officers_list` SET officer_id_no = '{$officer_id_no}', lastname = '{$lastname}', firstname = '{$firstname}', middlename = '{$middlename}', suffix = '{$suffix}', dob = '{$dob}', present_address = '{$present_address}', permanent_address = '{$permanent_address}', civil_status = '{$civil_status}', nationality = '{$nationality}', status = '{$status}', contact = '{$contact}', image_path = '{$image_path}' WHERE id = '{$id}'";
    }

    $save = $this->conn->query($sql);
    if ($save) {
        $resp['status'] = 'success';
        $this->settings->set_flashdata('success', empty($id) ? "New Officer successfully saved." : "Officer Details successfully updated.");
        
        $officer_id = empty($id) ? $this->conn->insert_id : $id;
        $dir = 'uploads/officers/';
        if (!is_dir(base_app . $dir)) mkdir(base_app . $dir);

        if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])) {
            $fname = $dir . $officer_id . '.' . pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
            if (move_uploaded_file($_FILES['img']['tmp_name'], base_app . $fname)) {
                $this->conn->query("UPDATE `officers_list` SET image_path = '{$fname}' WHERE id = '{$officer_id}'");
                if (!empty($image_path) && is_file(base_app . $image_path)) unlink(base_app . $image_path);
            }
        }

        $user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';
        $this->conn->query("INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Officer List', '{$description}')");
    } else {
        $resp['status'] = 'failed';
        $resp['err'] = $this->conn->error . "[{$sql}]";
    }
    return json_encode($resp);
}


	function delete_officer()
	{
		extract($_POST);
		$qry = $this->conn->query("SELECT * FROM `officers_meta` where officer_id = '{$id}'");
		
		// Fetch user information from the session
		$user = $_SESSION['userdata']; // Retrieve the current logged-in user's information
		// Default user values if not found
		if (!$user) {
			$user = array('firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown');
		}

		// Determine the description based on whether it's a new offense or an 
		$description = 'Removed an Officer';

		while ($row = $qry->fetch_assoc()) {
			${$row['meta_field']} = $row['meta_value'];
		}
		$del = $this->conn->query("DELETE FROM `officers_list` where id = '{$id}'");
		$this->capture_err();
		if ($del) {
			$resp['status'] = 'success';
			if (is_file(base_app . $image_path))
				unlink((base_app . $image_path));
			$this->settings->set_flashdata('success', "Officers's Info successfully deleted.");

			// Determine the user type label based on the type value
			$user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Delete Officer', '{$description}')";

			// Execute the SQL query
			$result = $this->conn->query($sql);

		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	
	function delete_img()
	{
		extract($_POST);
		if (is_file(base_app . $path)) {
			if (unlink(base_app . $path)) {
				$del = $this->conn->query("DELETE FROM `uploads` where file_path = '{$path}'");
				$resp['status'] = 'success';
			} else {
				$resp['status'] = 'failed';
				$resp['error'] = 'failed to delete ' . $path;
			}
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = 'Unkown ' . $path . ' path';
		}
		return json_encode($resp);
	}

function save_offense_record()
{
    extract($_POST);
    $data = "";
    foreach ($_POST as $k => $v) {
        if (!in_array($k, ['id', 'fine', 'offense_id'])) {
            if (is_array($v)) {
                $v = array_map([$this->conn, 'real_escape_string'], $v);
                $v = implode(',', $v);
            } else {
                $v = $this->conn->real_escape_string($v);
            }
            if (!empty($data))
                $data .= ", ";
            $data .= " `{$k}`='{$v}' ";
        }
    }

    // Ensure ticket_no is set
    if (!isset($ticket_no)) {
        return json_encode(['status' => 'failed', 'msg' => 'Ticket No. is required.']);
    }

    // Check for duplicate ticket_no
    $chk = $this->conn->query("SELECT * FROM `offense_list1` WHERE ticket_no = '" . $this->conn->real_escape_string($ticket_no) . "'" . 
        (!empty($id) ? " AND id != '" . $this->conn->real_escape_string($id) . "'" : ""))->num_rows;

    if ($this->capture_err())
        return $this->capture_err();

    if ($chk > 0) {
        return json_encode(['status' => 'failed', 'msg' => "Offense Ticket No. already exists. Please review and try again."]);
    }

    $sql = empty($id) 
        ? "INSERT INTO `offense_list1` SET {$data}" 
        : "UPDATE `offense_list1` SET {$data} WHERE id = '" . $this->conn->real_escape_string($id) . "'";

    // Log the SQL query for debugging
    error_log("SQL Query: " . $sql);

    if (!$this->conn->query($sql)) {
        return json_encode(['status' => 'failed', 'msg' => 'Database error: ' . $this->conn->error]);
    }

    $driver_offense_id = empty($id) ? $this->conn->insert_id : $id;

    // Log the offense_id for debugging
    error_log("Offense ID: " . $driver_offense_id);

    // Ensure the offense_id exists in the offense_list1 table
    $chk_offense_id = $this->conn->query("SELECT * FROM `offense_list1` WHERE id = '{$driver_offense_id}'")->num_rows;
    if ($chk_offense_id <= 0) {
        return json_encode(['status' => 'failed', 'msg' => 'Offense ID does not exist in offense_list1 table.']);
    }

    // Delete existing offense_items for this offense_id
    if (!$this->conn->query("DELETE FROM `offense_items` WHERE `driver_offense_id` = '{$driver_offense_id}'")) {
        return json_encode(['status' => 'failed', 'msg' => 'Failed to clear existing offense items.']);
    }

    // Fetch user information from session
    $user = $_SESSION['userdata'] ?? ['firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown', 'type' => 0];
    $description = empty($id) ? 'Added Offense Record' : 'Updated Offense Record';

    // Insert offense items
    $data = "";
    if (!empty($offense_id) && is_array($offense_id)) {
        foreach ($_POST['offense_id'] as $k => $v) {
            $fine_val = isset($_POST['fine'][$k]) ? $this->conn->real_escape_string($_POST['fine'][$k]) : 0;
            $offense_id_val = $this->conn->real_escape_string($v);

            if (!empty($data)) $data .= ", ";
            $data .= "('{$driver_offense_id}', '{$offense_id_val}', '{$fine_val}', NOW())";
        }

        if (!$this->conn->query("INSERT INTO `offense_items` (`driver_offense_id`, `offense_id`, `fine`, `date_created`) VALUES {$data}")) {
            return json_encode(['status' => 'failed', 'msg' => 'Failed to insert offense items.']);
        }
    }

    // Log action
    $user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';
    $log_sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) 
                VALUES (NOW(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Apprehension Record', '{$description}')";

    if (!$this->conn->query($log_sql)) {
        return json_encode(['status' => 'failed', 'msg' => 'Failed to log activity.']);
    }

    $this->settings->set_flashdata('success', empty($id) ? "New Offense Record successfully saved." : "Offense Record successfully updated.");
    return json_encode(['status' => 'success', 'id' => $driver_offense_id]);
}

	function delete_offense_record()
	{
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `offense_list1` where id = '{$id}'");
		// Fetch user information from the session
		$user = $_SESSION['userdata']; // Retrieve the current logged-in user's information
		// Default user values if not found
		if (!$user) {
			$user = array('firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown');
		}
		// Determine the description based on whether it's a new offense or an
		$description = 'Deleted Offense Record';

		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Offense Record successfully deleted.");
			
			// Determine the user type label based on the type value
			$user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Delete Offense Record', '{$description}')";

			// Execute the SQL query
			$result = $this->conn->query($sql);
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}

	function paid_offense_record()
	{
		$resp = array(); // Initialize response array
		$id = $_POST['id']; // Get the ID from the POST data

		// Prepare and execute the update query for offense_list1 table
		$stmt1 = $this->conn->prepare("UPDATE `offense_list1` SET `status` = '1', `date_released` = NOW() WHERE `id` = ?");

		$stmt1->bind_param("i", $id); // Bind the parameter
		$paid1 = $stmt1->execute();

		// Prepare and execute the update query for offense_items table
		$stmt2 = $this->conn->prepare("UPDATE `offense_items` SET `status` = '1' WHERE `driver_id` = ?");
		$stmt2->bind_param("i", $id); // Bind the parameter
		$paid2 = $stmt2->execute();


		// Fetch user information from the session
		$user = $_SESSION['userdata']; // Retrieve the current logged-in user's information
		// Default user values if not found
		if (!$user) {
			$user = array('firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown');
		}

		// Determine the description based on whether it's a new offense or an 
		$description = empty($id) ? 'Marked Offense Record as Paid' : 'Updated Pending Offense Record as Paid';

		// Check if both updates were successful
		if ($paid1 && $paid2) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Offense Record successfully marked as paid.");
			
			// Determine the user type label based on the type value
			$user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Status', '{$description}')";


			// Execute the SQL query
			$result = $this->conn->query($sql);
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $stmt1->error . ' / ' . $stmt2->error;
		}

		// Return JSON-encoded response
		return json_encode($resp);
	}

	function unpaid_offense_record()
	{
		$resp = array(); // Initialize response array
		$id = $_POST['id']; // Get the ID from the POST data

		// Prepare and execute the update query for offense_list1 table
		$stmt1 = $this->conn->prepare("UPDATE `offense_list1` SET `status` = '0', `date_released` = TIMESTAMP('1970-01-01 00:00:00') WHERE `id` = ?");

		$stmt1->bind_param("i", $id); // Bind the parameter
		$paid1 = $stmt1->execute();

		// Fetch user information from the session
		$user = $_SESSION['userdata']; // Retrieve the current logged-in user's information
		// Default user values if not found
		if (!$user) {
			$user = array('firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown');
		}

		// Determine the description based on whether it's a new offense or an 
		$description = empty($id) ? 'Marked Offense Record as Unpaid' : 'Updated Pending Offense Record as Unpaid';

		// Prepare and execute the update query for offense_items table
		$stmt2 = $this->conn->prepare("UPDATE `offense_items` SET `status` = '0' WHERE `driver_id` = ?");
		$stmt2->bind_param("i", $id); // Bind the parameter
		$paid2 = $stmt2->execute();

		// Check if both updates were successful
		if ($paid1 && $paid2) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Offense Record successfully marked as paid.");

			// Determine the user type label based on the type value
			$user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Status', '{$description}')";

			// Execute the SQL query
			$result = $this->conn->query($sql);
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $stmt1->error . ' / ' . $stmt2->error;
		}

		// Return JSON-encoded response
		return json_encode($resp);
	}

	function save_vehicle()
	{
		// Extract POST data
		extract($_POST);

		// Initialize data variable
		$data = "";

		// Build data string
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				if (!empty($data))
					$data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}

		// Check if the name already exists
		$check_query = "SELECT * FROM `vehicles` WHERE `name` = '{$name}' " . (!empty($id) ? " AND id != {$id}" : "");
		$check_result = $this->conn->query($check_query);
		if (!$check_result) {
			$resp['status'] = 'failed';
			$resp['msg'] = $this->conn->error;
			return json_encode($resp);
		}
		if ($check_result->num_rows > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Type of Vehicle already exists.";
			return json_encode($resp);
		}

		// Perform INSERT or UPDATE operation
		if (empty($id)) {
			$sql = "INSERT INTO `vehicles` SET {$data}";
		} else {
			$sql = "UPDATE `vehicles` SET {$data} WHERE id = '{$id}'";
		}
		$save = $this->conn->query($sql);

		// Fetch user information from the session
		$user = $_SESSION['userdata']; // Retrieve the current logged-in user's information
		// Default user values if not found
		if (!$user) {
			$user = array('firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown');
		}

		// Determine the description based on whether it's a new offense or an 
		$description = empty($id) ? 'Added a new Vehicle' : 'Updated a Vehicle';

		// Check if the operation was successful
		if ($save) {
			$resp['status'] = 'success';
			if (empty($id)) {
				$this->settings->set_flashdata('success', "New Type of Vehicle successfully saved.");
			} else {
				$this->settings->set_flashdata('success', "Type of Vehicle successfully updated.");
			}

			// Determine the user type label based on the type value
			$user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Vehicle List', '{$description}')";

			// Execute the SQL query
			$result = $this->conn->query($sql);
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . " [{$sql}]";
		}
		return json_encode($resp);
	}

	function delete_vehicle()
	{
		// Extract POST data
		extract($_POST);

		// Perform delete operation
		$del = $this->conn->query("DELETE FROM `vehicles` WHERE id = '{$id}'");

		// Fetch user information from the session
		$user = $_SESSION['userdata']; // Retrieve the current logged-in user's information
		// Default user values if not found
		if (!$user) {
			$user = array('firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown');
		}

		// Determine the description based on whether it's a new offense or an
		$description = 'Removed a Vehicle';

		// Check if the operation was successful
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Type of Vehicle successfully deleted.");


			// Determine the user type label based on the type value
			$user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Vehicle List', '{$description}')";

			// Execute the SQL query
			$result = $this->conn->query($sql);

		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}


	function save_discount()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'fine', 'offense_id'))) {
				$v = addslashes($v);
				if (!empty($data))
					$data .= ", ";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$chk = $this->conn->query("SELECT * FROM `offense_list1` where  ticket_no = '{$ticket_no}' " . (($id > 0) ? " and id!= '{$id}' " : ""))->num_rows;


		$this->capture_err();


		$save = $this->conn->query($sql);
		$this->capture_err();
		$driver_offense_id = empty($id) ? $this->conn->insert_id : $id;
		$this->conn->query("DELETE FROM `offense_items` where `driver_id` = '{$driver_id}'");
		$this->capture_err();

		// Fetch user information from the session
		$user = $_SESSION['userdata']; // Retrieve the current logged-in user's information
		// Default user values if not found
		if (!$user) {
			$user = array('firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown');
		}

		// Determine the description based on whether it's a new offense or an 
		$description = 'Updated Offense Record';

		if ($save) {
			if (empty($id))
				$this->settings->set_flashdata('success', " New Offense Record successfully saved.");
			else
				$this->settings->set_flashdata('success', " Offense Record successfully updated.");
			$resp['status'] = 'success';
			$resp['id'] = $driver_offense_id;

			// Determine the user type label based on the type value
			$user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'OR Discount', '{$description}')";

			// Execute the SQL query
			$result = $this->conn->query($sql);
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		return json_encode($resp);
	}


}


$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_offense':
		echo $Master->save_offense();
		break;
	case 'delete_offense':
		echo $Master->delete_offense();
		break;
	case 'save_vehicle':
		echo $Master->save_vehicle();
		break;
	case 'delete_vehicle':
		echo $Master->delete_vehicle();
		break;
	case 'upload_files':
		echo $Master->upload_files();
		break;
	case 'save_driver':
		echo $Master->save_driver();
		break;
	case 'save_officer':
		echo $Master->save_officer();
		break;
	case 'delete_driver':
		echo $Master->delete_driver();
		break;

	case 'save_offense_record':
		echo $Master->save_offense_record();
		break;
	case 'delete_offense_record':
		echo $Master->delete_offense_record();
		break;
	case 'paid_offense_record':
		echo $Master->paid_offense_record();
		break;
	case 'unpaid_offense_record':
		echo $Master->unpaid_offense_record();
		break;
	case 'delete_img':
		echo $Master->delete_img();
		break;
	default:
		$Master->handle_request();
		// echo $sysset->index();
		break;
}