<?php
require_once('../config.php');

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $user = $conn->query("SELECT * FROM users WHERE id ='{$_GET['id']}'");
    foreach ($user->fetch_array() as $k => $v) {
        $meta[$k] = $v;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // Check if the username already exists (excluding the current user if editing)
    $check = $conn->query("SELECT * FROM users WHERE username = '$username' AND id != '$id'");
    if ($check->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Username already exists']);
        exit;
    } else {
        // Prepare query
        if ($id > 0) {
            // Update existing user
            $qry = "UPDATE users SET firstname = '{$_POST['firstname']}', middleinitial = '{$_POST['middleinitial']}', lastname = '{$_POST['lastname']}', status = '{$_POST['status']}', position = '{$_POST['position']}', username = '$username', type = '{$_POST['type']}'" . (isset($_FILES['img']) ? ", avatar = '{$fname}'" : '') . " WHERE id = '$id'";
        } else {
            // Insert new user
            $qry = "INSERT INTO users (firstname, middleinitial, lastname, status, position, username, type" . (isset($_FILES['img']) ? ", avatar" : '') . ") VALUES ('{$_POST['firstname']}', '{$_POST['middleinitial']}', '{$_POST['lastname']}', '{$_POST['status']}', '{$_POST['position']}', '$username', '{$_POST['type']}'" . (isset($_FILES['img']) ? ", '{$fname}'" : '') . ")";
        }

        $save = $conn->query($qry);
        if ($save) {
            // Redirect to the user list page after successful update or insert
            echo json_encode(['status' => 'success', 'redirect' => './?page=user/list']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save user']);
        }
        exit;
    }
}
?>

<?php if ($_settings->chk_flashdata('success')): ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
    </script>
<?php elseif ($_settings->chk_flashdata('error')): ?>
    <script>
        $('#msg').html('<div class="alert alert-danger"><?php echo $_settings->flashdata('error') ?></div>');
    </script>
<?php endif; ?>

<div class="card card-outline card-primary">
	<div class="card-body">
		<div class="container-fluid">
			<div id="msg"></div>
			<form action="" id="manage-user">
				<div class="row">
					<div class="col-6" >
						<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : '' ?>">
						<div class="form-group">
							<label for="name">First Name</label>
							<input type="text" name="firstname" id="firstname" class="form-control"
								value="<?php echo isset($meta['firstname']) ? $meta['firstname'] : '' ?>" required>
						</div>
						<div class="form-group">
							<label for="name">Middle Initial</label>
							<input type="text" name="middleinitial" id="middleinitial" class="form-control"
								value="<?php echo isset($meta['middleinitial']) ? $meta['middleinitial'] : '' ?>" required>
						</div>
						<div class="form-group">
							<label for="name">Last Name</label>
							<input type="text" name="lastname" id="lastname" class="form-control"
								value="<?php echo isset($meta['lastname']) ? $meta['lastname'] : '' ?>" required>
						</div>
						<div class="form-group">
							<label for="name">Suffix</label>
							<input type="text" name="suffix" id="suffix" class="form-control"
								value="<?php echo isset($meta['suffix']) ? $meta['suffix'] : '' ?>">
						</div>
						<div class="form-group">
							<label for="position">Status</label>
							<select name="status" id="status" class="custom-select" required>
								<option value="1" <?php echo (isset($meta['status']) && $meta['status'] == '1') ? 'selected' : '' ?>>Active</option>
								<option value="0" <?php echo (isset($meta['status']) && $meta['status'] == '0') ? 'selected' : '' ?>>Inactive</option>
								
							</select>
						</div>
						<div class="form-group">
							<label for="name">Position</label>
							<input type="text" name="position" id="position" class="form-control"
								value="<?php echo isset($meta['position']) ? $meta['position'] : '' ?>" required>
						</div>
					</div>
				
					<div class="col-6">
						<div class="form-group">
							<label for="username">Username</label>
							<input type="text" name="username" id="username" class="form-control"
								value="<?php echo isset($meta['username']) ? $meta['username'] : '' ?>" required
								autocomplete="off">
						</div>
						<div class="form-group">
							<label for="password">Password</label>
							<input type="password" name="password" id="password" class="form-control" value=""
								autocomplete="off" <?php echo isset($meta['id']) ? "" : 'required' ?>>
							<?php if (isset($_GET['id'])): ?>
								<small><i>Leave this blank if you don't want to change the password.</i></small>
							<?php endif; ?>
						</div>
						<div class="form-group">
							<label for="type">User Type</label>
							<select name="type" id="type" class="custom-select">
								<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : '' ?>>
									Administrator</option>
								<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected' : '' ?>>Staff
								</option>
							</select>
						</div>
						<div class="form-group">
							<label for="" class="control-label">Avatar</label>
							<div class="custom-file">
								<input type="file" class="custom-file-input rounded-circle" id="customFile" name="img"
									onchange="displayImg(this,$(this))">
								<label class="custom-file-label" for="customFile">Choose file</label>
							</div>
						</div>
						<div class="form-group  d-flex justify-content-center">
							<img src="<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] : '') ?>" alt=""
								id="cimg" class="img-fluid img-thumbnail">
						</div>
					</div>

					
				</div>
				
			</form>
		</div>
	</div>
	<div class="card-footer">
		<div class="col-md-12">
			<div class="row">
				<?php if (isset($meta['id'])): ?>
					<!-- Update User Button -->
					<button class="btn btn-sm btn-primary mr-2" id="update-button" form="manage-user">
						Update User
					</button>
				<?php else: ?>
					<!-- Add User Button -->
					<button class="btn btn-sm btn-success mr-2" id="add-button" form="manage-user">
						Add User
					</button>
				<?php endif; ?>
				<!-- Cancel Button -->
				<a class="btn btn-sm btn-secondary" href="./?page=user/list">Cancel</a>
			</div>
		</div>
	</div>


</div>
<style>
	img#cimg {
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>

<script>
function displayImg(input, _this) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#cimg').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$('#manage-user').submit(function (e) {
    e.preventDefault();
    start_loader();  // Start loader at the beginning

    var userId = $('input[name="id"]').val();  // Get the user ID if it exists
    var action = userId ? 'update' : 'add';    // Determine if we're updating or adding

    $.ajax({
        url: _base_url_ + 'classes/Users.php?f=savee',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        success: function (resp) {
            try {
                var data = JSON.parse(resp);
                if (data.status === 'success') {
                    var customDescription = action === 'update' ? 'Updated User Details' : 'Added a New User';
                    
                    alert_toast("User details successfully " + (action === 'update' ? "updated." : "added."), 'success');

                    // Log activity based on the action
                    logActivity(action, userId || data.user_id, customDescription);

                    // Redirect after successful action
                    location.href = './?page=user/list';
                } else if (data.status === 'error') {
                    $('#msg').html('<div class="alert alert-danger">' + data.message + '</div>');
                }
            } catch (e) {
                console.error("Invalid JSON:", resp);
                $('#msg').html('<div class="alert alert-danger">An unexpected error occurred: ' + resp + '</div>');
            }
            end_loader();  // Ensure loader stops
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Error occurred: ' + textStatus, errorThrown);
            $('#msg').html('<div class="alert alert-danger">An unexpected error occurred. Please try again.</div>');
            end_loader();  // Stop loader on error as well
        }
    });
});

function logActivity(action, userId, description) {
    $.ajax({
        url: _base_url_ + "classes/Master.php",
        type: 'POST',
        data: {
            f: action === 'update' ? 'updateuserr_activity' : 'adduser_activity',  // Function to call based on action
            id: userId,  // Use the user ID from the form or response
            description: description  // Custom description based on action
        },
        success: function(response) {
            console.log('Activity logged successfully.');
        },
        error: function(xhr, status, error) {
            console.error('Error logging activity:', error);
        }
    });
}
</script>
