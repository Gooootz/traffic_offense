
<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		$this->permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	
	function save_offense(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','description'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(isset($_POST['description'])){
			if(!empty($data)) $data .=",";
			$data .= " `description`='".addslashes(htmlentities($description))."' ";
		}
		$check = $this->conn->query("SELECT * FROM `offenses` where `code` = '{$code}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		
		// Fetch user information
		$user_query = $this->conn->query("SELECT * FROM users");
		$user = $user_query->fetch_assoc();
		
		// Determine the description based on whether it's a new offense or an update
		$description = empty($id) ? 'added new offense' : 'updated offense';
		
		if($this->capture_err())
			return $this->capture_err();
		
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Offense code already exists.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `offenses` SET {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `offenses` SET {$data} WHERE id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		
		if ($save) {
			$resp['status'] = 'success';
			if (empty($id)) {
				$this->settings->set_flashdata('success', "New Offense successfully saved.");
			} else {
				$this->settings->set_flashdata('success', "Offense successfully updated.");
			}
		
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user['username']})', 'offense', '{$description}')";
		
			// Execute the SQL query
			$result = $this->conn->query($sql);
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		
		return json_encode($resp);
	}
	
	function delete_offense(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `offenses` where id = '{$id}'");
		$user_query = $this->conn->query("SELECT * FROM users");
		$user = $user_query->fetch_assoc();
		
		// Determine the description based on whether it's a new offense or an update
		$description = 'deleted an offense';

		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"offense successfully deleted.");
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user['username']})', 'add offense', '{$description}')";
		
			// Execute the SQL query
			$result = $this->conn->query($sql);
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function generate_string($input, $strength = 10) {
		
		$input_length = strlen($input);
		$random_string = '';
		for($i = 0; $i < $strength; $i++) {
			$random_character = $input[mt_rand(0, $input_length - 1)];
			$random_string .= $random_character;
		}
	 
		return $random_string;
	}
	function upload_files(){
		extract($_POST);
		$data = "";
		if(empty($upload_code)){
			while(true){
				$code = $this->generate_string($this->permitted_chars);
				$chk = $this->conn->query("SELECT * FROM `uploads` where dir_code ='{$code}' ")->num_rows;
				if($chk <= 0){
					$upload_code = $code;
					$resp['upload_code'] =$upload_code;
					break;
				}
			}
		}

		if(!is_dir(base_app.'uploads/blog_uploads/'.$upload_code))
			mkdir(base_app.'uploads/blog_uploads/'.$upload_code);
		$dir = 'uploads/blog_uploads/'.$upload_code.'/';
		$images = array();
		for($i = 0;$i < count($_FILES['img']['tmp_name']); $i++){
			if(!empty($_FILES['img']['tmp_name'][$i])){
				$fname = $dir.(time()).'_'.$_FILES['img']['name'][$i];
				$f = 0;
				while(true){
					$f++;
					if(is_file(base_app.$fname)){
						$fname = $f."_".$fname;
					}else{
						break;
					}
				}
				$move = move_uploaded_file($_FILES['img']['tmp_name'][$i],base_app.$fname);
				if($move){
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
	function save_driver(){
		foreach($_POST as $k =>$v){
			$_POST[$k] = addslashes($v);
		}
		extract($_POST);
		$name = ucwords($lastname.', '.$firstname.' '.$middlename);
		$chk = $this->conn->query("SELECT * FROM `drivers_list` where  license_id_no = '{$license_id_no}' ".($id>0? " and id!= '{$id}' " : ""))->num_rows;

		// Fetch user information
		$user_query = $this->conn->query("SELECT * FROM users");
		$user = $user_query->fetch_assoc();

		// Determine the description based on whether it's a new offense or an 
		$description = empty($id) ? 'Added new driver' : 'Updated driver';

		$this->capture_err();
		
		if (!empty($license_id_no)) {
			$chk = $this->conn->query("SELECT * FROM `drivers_list` WHERE license_id_no = '{$license_id_no}' ".($id>0? " AND id != '{$id}' " : ""))->num_rows;
			$this->capture_err();
			if ($chk > 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "License ID already exists in the database. Please review and try again.";
				return json_encode($resp);
				exit;
			}
		}

		if(empty($id))
			$sql1 = "INSERT INTO `drivers_list` set `name` = '{$name}', license_id_no = '{$license_id_no}' ";
		else
			$sql1 = "UPDATE `drivers_list` set `name` = '{$name}', license_id_no = '{$license_id_no}' where id = '{$id}' ";
		
		$save1 = $this->conn->query($sql1);
		$this->capture_err();
		$driver_id = empty($id) ? $this->conn->insert_id : $id ;
		$this->conn->query("DELETE FROM `drivers_meta` where driver_id = '{$driver_id}' ");
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = addslashes($v);
				$data .= " ('{$driver_id}','{$k}','{$v}') ";
			}
		}
		$data .= ",('{$driver_id}','driver_id','{$driver_id}')";

		
		$sql = "INSERT INTO `drivers_meta` (`driver_id`,`meta_field`,`meta_value`) VALUES {$data} ";
		$save = $this->conn->query($sql);
		$this->capture_err();
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Driver successfully saved.");
			else
				$this->settings->set_flashdata('success',"Driver Details successfully updated.");
			$id = empty($id) ? $this->conn->insert_id : $id;
			$dir = 'uploads/drivers/';
			if(!is_dir(base_app.$dir))
				mkdir(base_app.$dir);
			if(isset($_FILES['img'])){
				if(!empty($_FILES['img']['tmp_name'])){
					$fname = $dir.$driver_id.".".(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
					$move =  move_uploaded_file($_FILES['img']['tmp_name'],base_app.$fname);
					if($move){
						$this->conn->query("INSERT INTO `drivers_meta` set `meta_value` = '{$fname}', driver_id = '{$driver_id}',`meta_field` = 'image_path' ");
						if(!empty($image_path) && is_file(base_app.$image_path))
							unlink(base_app.$image_path);
					}
				}
			}
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user['username']})', 'Add Driver', '{$description}')";
					
			// Execute the SQL query
			$result = $this->conn->query($sql);
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_driver(){
		extract($_POST);
		$qry = $this->conn->query("SELECT * FROM `drivers_meta` where driver_id = '{$id}'");
		while($row=$qry->fetch_assoc()){
			${$row['meta_field']} = $row['meta_value'];
		}
		$del = $this->conn->query("DELETE FROM `drivers_list` where id = '{$id}'");
		// Fetch user information
		$user_query = $this->conn->query("SELECT * FROM users");
		$user = $user_query->fetch_assoc();

		// Determine the description based on whether it's a new offense or an 
		$description = 'Deleted a driver';

		$this->capture_err();
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Driver's Info successfully deleted.");
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user['username']})', 'Delete Driver', '{$description}')";
					
			// Execute the SQL query
			$result = $this->conn->query($sql);
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}

	function save_officer(){
		foreach($_POST as $k =>$v){
			$_POST[$k] = addslashes($v);
		}
		extract($_POST);
		$officer_name = ucwords($lastname.', '.$firstname.' '.$middlename);
		$chk = $this->conn->query("SELECT * FROM `officers_list` where  officer_id_no = '{$officer_id_no}' ".($id>0? " and id!= '{$id}' " : ""))->num_rows;// Fetch user information
		$user_query = $this->conn->query("SELECT * FROM users");
		$user = $user_query->fetch_assoc();
		
		// Determine the description based on whether it's a new offense or an 
		$description = empty($id) ? 'added new officer' : 'updated officer';
		
		$this->capture_err();
		if($chk > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Officers ID already exist in the database. Please review and try again.";
			return json_encode($resp);
			exit;
		}
		if(empty($id))
			$sql1 = "INSERT INTO `officers_list` set `officer_name` = '{$officer_name}', officer_id_no = '{$officer_id_no}' ";
		else
			$sql1 = "UPDATE `officers_list` set `officer_name` = '{$officer_name}', officer_id_no = '{$officer_id_no}' where id = '{$id}' ";
		
		$save1 = $this->conn->query($sql1);
		$this->capture_err();
		$officer_id = empty($id) ? $this->conn->insert_id : $id ;
		$this->conn->query("DELETE FROM `officers_meta` where officer_id = '{$officer_id}' ");
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = addslashes($v);
				$data .= " ('{$officer_id}','{$k}','{$v}') ";
			}
		}
		$data .= ",('{$officer_id}','officer_id','{$officer_id}')";

		
		$sql = "INSERT INTO `officers_meta` (`officer_id`,`meta_field`,`meta_value`) VALUES {$data} ";
		$save = $this->conn->query($sql);
		$this->capture_err();
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Officer successfully saved.");
			else
				$this->settings->set_flashdata('success',"Officer Details successfully updated.");
			$id = empty($id) ? $this->conn->insert_id : $id;
			$dir = 'uploads/officers/';
			if(!is_dir(base_app.$dir))
				mkdir(base_app.$dir);
			if(isset($_FILES['img'])){
				if(!empty($_FILES['img']['tmp_name'])){
					$fname = $dir.$officer_id.".".(pathinfo($_FILES['img']['officer_name'], PATHINFO_EXTENSION));
					$move =  move_uploaded_file($_FILES['img']['tmp_name'],base_app.$fname);
					if($move){
						$this->conn->query("INSERT INTO `officers_meta` set `meta_value` = '{$fname}', officer_id = '{$officer_id}',`meta_field` = 'image_path' ");
						if(!empty($image_path) && is_file(base_app.$image_path))
							unlink(base_app.$image_path);	
					}
				}
			}
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user['username']})', 'Add Officer', '{$description}')";
					
			// Execute the SQL query
			$result = $this->conn->query($sql);
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_officer(){
		extract($_POST);
		$qry = $this->conn->query("SELECT * FROM `officers_meta` where officer_id = '{$id}'");
		// Fetch user information
		$user_query = $this->conn->query("SELECT * FROM users");
		$user = $user_query->fetch_assoc();

		// Determine the description based on whether it's a new offense or an 
		$description = 'Removed an Officer';

		while($row=$qry->fetch_assoc()){
			${$row['meta_field']} = $row['meta_value'];
		}
		$del = $this->conn->query("DELETE FROM `officers_list` where id = '{$id}'");
		$this->capture_err();
		if($del){
			$resp['status'] = 'success';
			if(is_file(base_app.$image_path))
				unlink((base_app.$image_path));
			$this->settings->set_flashdata('success',"Officers's Info successfully deleted.");
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user['username']})', 'Delete Officer', '{$description}')";
					
			// Execute the SQL query
			$result = $this->conn->query($sql);

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function delete_img(){
		extract($_POST);
		if(is_file(base_app.$path)){
			if(unlink(base_app.$path)){
				$del = $this->conn->query("DELETE FROM `uploads` where file_path = '{$path}'");
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = 'failed to delete '.$path;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = 'Unkown '.$path.' path';
		}
		return json_encode($resp);
	}
	function save_offense_record(){
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'fine', 'offense_id'))) {
				$v = addslashes($v);
				if (!empty($data)) $data .= ", ";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$chk = $this->conn->query("SELECT * FROM `offense_list1` where  ticket_no = '{$ticket_no}' ".(($id>0)? " and id!= '{$id}' " : ""))->num_rows;
		

		$this->capture_err();
		if($chk > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Offense Ticker No. already exist in the database. Please review and try again.";
			return json_encode($resp);
			exit;
		}

		if(empty($id)){
			$sql = "INSERT INTO `offense_list1` set {$data} ";
		}else{
			$sql = "UPDATE `offense_list1` set {$data} where id = '{$id}' ";
			
		}
		$save = $this->conn->query($sql);
		$this->capture_err();
		$driver_offense_id = empty($id) ? $this->conn->insert_id : $id;
		$this->conn->query("DELETE FROM `offense_items` where `driver_offense_id` = '{$driver_offense_id}'");
		$this->capture_err();
		// Fetch user information
		$user_query = $this->conn->query("SELECT * FROM users");
		$user = $user_query->fetch_assoc();

		// Determine the description based on whether it's a new offense or an 
		$description = empty($id) ? 'Added Offense Record' : 'Updated Offense Record';
		$data = "";
		foreach($offense_id as $k => $v){
			if(!empty($data)) $data .= ", ";
			$data .= "('{$driver_offense_id}','{$v}','{$fine[$k]}','{$status}','{$date_created}')";
		}
		$save2= $this->conn->query("INSERT INTO `offense_items` (`driver_offense_id`,`offense_id`,`fine`,`status`,`date_created`) VALUES {$data}");
		$this->capture_err();
		if($save && $save2){
			if(empty($id))
				$this->settings->set_flashdata('success'," New Offense Record successfully saved.");
			else
				$this->settings->set_flashdata('success'," Offense Record successfully updated.");
			$resp['status'] = 'success';
			$resp['id'] = $driver_offense_id;
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user['username']})', 'Add Offense Record', '{$description}')";
					
			// Execute the SQL query
			$result = $this->conn->query($sql);
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}	

	
	function delete_offense_record(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `offense_list1` where id = '{$id}'");
		// Fetch user information
		$user_query = $this->conn->query("SELECT * FROM users");
		$user = $user_query->fetch_assoc();
		// Determine the description based on whether it's a new offense or an
		$description = 'Deleted Offense Record';

		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Offense Record successfully deleted.");
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user['username']})', 'Delete Offense Record', '{$description}')";
					
			// Execute the SQL query
			$result = $this->conn->query($sql);
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}

	function paid_offense_record() {
		$resp = array(); // Initialize response array
		$id = $_POST['id']; // Get the ID from the POST data
	
		// Prepare and execute the update query for offense_list1 table
		$stmt1 = $this->conn->prepare("UPDATE `offense_list1` SET `status` = '1', `date_released` = NOW() WHERE `id` = ?");

		$stmt1->bind_param("i", $id); // Bind the parameter
		$paid1 = $stmt1->execute();
	
		// Prepare and execute the update query for offense_items table
		$stmt2 = $this->conn->prepare("UPDATE `offense_items` SET `status` = '1' WHERE `driver_offense_id` = ?");
		$stmt2->bind_param("i", $id); // Bind the parameter
		$paid2 = $stmt2->execute();
		// Fetch user information
		$user_query = $this->conn->query("SELECT * FROM users");
		$user = $user_query->fetch_assoc();

		// Determine the description based on whether it's a new offense or an 
		$description = empty($id) ? 'Marked Offense Record as Paid' : 'Updated Pending Offense Record as Paid';

		// Check if both updates were successful
		if ($paid1 && $paid2) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Offense Record successfully marked as paid.");
			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user['username']})', 'Status', '{$description}')";
			

			// Execute the SQL query
			$result = $this->conn->query($sql);
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $stmt1->error . ' / ' . $stmt2->error;
		}
	
		// Return JSON-encoded response
		return json_encode($resp);
	}

	function unpaid_offense_record() {
		$resp = array(); // Initialize response array
		$id = $_POST['id']; // Get the ID from the POST data
	
		// Prepare and execute the update query for offense_list1 table
		$stmt1 = $this->conn->prepare("UPDATE `offense_list1` SET `status` = '0', `date_released` = TIMESTAMP('1970-01-01 00:00:00') WHERE `id` = ?");

		$stmt1->bind_param("i", $id); // Bind the parameter
		$paid1 = $stmt1->execute();

		// Fetch user information
		$user_query = $this->conn->query("SELECT * FROM users");
		$user = $user_query->fetch_assoc();

		// Determine the description based on whether it's a new offense or an 
		$description = empty($id) ? 'Marked Offense Record as Unpaid' : 'Updated Pending Offense Record as Unpaid';
	
		// Prepare and execute the update query for offense_items table
		$stmt2 = $this->conn->prepare("UPDATE `offense_items` SET `status` = '0' WHERE `driver_offense_id` = ?");
		$stmt2->bind_param("i", $id); // Bind the parameter
		$paid2 = $stmt2->execute();
	
		// Check if both updates were successful
		if ($paid1 && $paid2) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Offense Record successfully marked as paid.");

			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user['username']})', 'Status', '{$description}')";
					
			// Execute the SQL query
			$result = $this->conn->query($sql);
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $stmt1->error . ' / ' . $stmt2->error;
		}
	
		// Return JSON-encoded response
		return json_encode($resp);
	}
	
	function save_vehicle(){
		// Extract POST data
		extract($_POST);
	
		// Initialize data variable
		$data = "";
	
		// Build data string
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id'))){
				if(!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
	
		// Check if the name already exists
		$check_query = "SELECT * FROM `vehicles` WHERE `name` = '{$name}' " . (!empty($id) ? " AND id != {$id}" : "");
		$check_result = $this->conn->query($check_query);
		if(!$check_result){
			$resp['status'] = 'failed';
			$resp['msg'] = $this->conn->error;
			return json_encode($resp);
		}
		if($check_result->num_rows > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Type of Vehicle already exists.";
			return json_encode($resp);
		}
	
		// Perform INSERT or UPDATE operation
		if(empty($id)){
			$sql = "INSERT INTO `vehicles` SET {$data}";
		}else{
			$sql = "UPDATE `vehicles` SET {$data} WHERE id = '{$id}'";
		}
		$save = $this->conn->query($sql);

		// Fetch user information
		$user_query = $this->conn->query("SELECT * FROM users");
		$user = $user_query->fetch_assoc();

		// Determine the description based on whether it's a new offense or an 
		$description = empty($id) ? 'Added a new Vehicle' : 'Updated a Vehicle';
	
		// Check if the operation was successful
		if($save){
			$resp['status'] = 'success';
			if(empty($id)){
				$this->settings->set_flashdata('success', "New Type of Vehicle successfully saved.");
			}else{
				$this->settings->set_flashdata('success', "Type of Vehicle successfully updated.");
			}

			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user['username']})', 'Vehicle', '{$description}')";
					
			// Execute the SQL query
			$result = $this->conn->query($sql);
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . " [{$sql}]";
		}
		return json_encode($resp);
	}
	
	function delete_vehicle(){
		// Extract POST data
		extract($_POST);
	
		// Perform delete operation
		$del = $this->conn->query("DELETE FROM `vehicles` WHERE id = '{$id}'");

		// Fetch user information
		$user_query = $this->conn->query("SELECT * FROM users");
		$user = $user_query->fetch_assoc();

		// Determine the description based on whether it's a new offense or an
		$description = 'Removed a Vehicle';
	
		// Check if the operation was successful
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Type of Vehicle successfully deleted.");

			// Prepare the SQL query
			$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user['username']})', 'Vehicle', '{$description}')";
					
			// Execute the SQL query
			$result = $this->conn->query($sql);

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}


		function save_discount(){
			extract($_POST);
			$data = "";
			foreach ($_POST as $k => $v) {
				if (!in_array($k, array('id', 'fine', 'offense_id'))) {
					$v = addslashes($v);
					if (!empty($data)) $data .= ", ";
					$data .= " `{$k}`='{$v}' ";
				}
			}
			$chk = $this->conn->query("SELECT * FROM `offense_list1` where  ticket_no = '{$ticket_no}' ".(($id>0)? " and id!= '{$id}' " : ""))->num_rows;
			

			$this->capture_err();
			

			$save = $this->conn->query($sql);
			$this->capture_err();
			$driver_offense_id = empty($id) ? $this->conn->insert_id : $id;
			$this->conn->query("DELETE FROM `offense_items` where `driver_offense_id` = '{$driver_offense_id}'");
			$this->capture_err();
			// Fetch user information
			$user_query = $this->conn->query("SELECT * FROM users");
			$user = $user_query->fetch_assoc();

			// Determine the description based on whether it's a new offense or an 
			$description = 'Updated Offense Record';
			
			if($save){
				if(empty($id))
					$this->settings->set_flashdata('success'," New Offense Record successfully saved.");
				else
					$this->settings->set_flashdata('success'," Offense Record successfully updated.");
				$resp['status'] = 'success';
				$resp['id'] = $driver_offense_id;
				// Prepare the SQL query
				$sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) VALUES (now(), '{$user['firstname']} {$user['lastname']} ({$user['username']})', 'Discount', '{$description}')";
						
				// Execute the SQL query
				$result = $this->conn->query($sql);
			}else{
				$resp['status'] = 'failed';
				$resp['err'] = $this->conn->error."[{$sql}]";
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
		// echo $sysset->index();
		break;
}