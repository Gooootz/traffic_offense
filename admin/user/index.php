<?php
$user = $conn->query("SELECT * FROM users WHERE id ='" . $_settings->userdata('id') . "'");
$meta = [];
while ($row = $user->fetch_assoc()) {
    foreach ($row as $k => $v) {
        $meta[$k] = $v;
    }
}
?>
<?php if ($_settings->chk_flashdata('success')): ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success');
    </script>
<?php endif; ?>
<div class="card card-outline card-primary">
    <div class="card-body">
        <div class="container-fluid">
            <div id="msg"></div>
            <form action="" id="manage-user">
                <input type="hidden" name="id" value="<?php echo $_settings->userdata('id') ?>">
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
                           value="<?php echo isset($meta['suffix']) ? $meta['suffix'] : '' ?>" >
                </div>
				<div class="form-group col-6">
                    <label for="position">Position</label>
                    <input type="text" name="position" id="position" class="form-control"
                           value="<?php echo isset($meta['position']) ? $meta['position'] : '' ?>" required>
                </div>
                <div class="form-group col-6">
                    <label for="position">Status: </label>
                    <select name="status" id="status" class="custom-select" required disabled>
						<option value="0" <?php echo (isset($meta['status']) && $meta['status'] == '0') ? 'selected' : '' ?>>Inactive</option>
						<option value="1" <?php echo (isset($meta['status']) && $meta['status'] == '1') ? 'selected' : '' ?>>Active</option>
					</select>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control"
                           value="<?php echo isset($meta['username']) ? $meta['username'] : '' ?>" required
                           autocomplete="off">
                </div>
                <!-- Password Change Section -->
                <div class="form-group">
                    <label for="password">Password</label><br>
                    <button type="button" class="btn btn-outline-info ml-1" data-toggle="modal" data-target="#changePasswordModal">
                        <i class="fa fa-key"></i> Change Password
                    </button>
                    <!-- <small><i>Leave this blank if you don't want to change the password.</i></small> -->
                </div>

                <!-- Password Change Modal -->
				<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label for="oldPassword">Old Password</label>
									<input type="password" name="oldPassword" id="oldPassword" class="form-control" autocomplete="off">
								</div>
								<div class="form-group">
									<label for="newPassword">New Password</label>
									<input type="password" name="newPassword" id="newPassword" class="form-control" autocomplete="off">
								</div>
								<div class="form-group">
									<label for="confirmPassword">Retype New Password</label>
									<input type="password" name="confirmPassword" id="confirmPassword" class="form-control" autocomplete="off">
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-outline-primary" id="savePassword">Save changes</button>
							</div>
						</div>
					</div>
				</div>
                <div class="form-group">
                    <label for="" class="control-label">Avatar</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input rounded-circle" id="customFile" name="img"
                               onchange="displayImg(this,$(this))">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                <div class="form-group d-flex justify-content-center">
                    <img src="<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] : '') ?>" alt=""
                         id="cimg" class="img-fluid img-thumbnail">
                </div>
            </form>
        </div>
    </div>
    <div class="card-footer">
        <div class="col-md-12">
            <div class="row">
                <button class="btn btn-outline-info ml-1" form="manage-user">Update</button>
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

    $('#savePassword').click(function () {
		var oldPassword = $('#oldPassword').val();
		var newPassword = $('#newPassword').val();
		var confirmPassword = $('#confirmPassword').val();

		if (newPassword !== confirmPassword) {
			alert('New passwords do not match!');
			return;
		}

		// AJAX to send oldPassword and newPassword to the server
		$.ajax({
			url: _base_url_ + 'classes/Users.php?f=change_password',
			method: 'POST',
			data: {
				oldPassword: oldPassword,
				newPassword: newPassword
			},
			success: function (response) {
				if (response == 1) {
					alert('Password changed successfully.');
					$('#changePasswordModal').modal('hide');
					setTimeout(function () {
						window.location.href = "<?php echo base_url . '/classes/Login.php?f=logout' ?>";
					}, 1000);
				} else if (response == 0) {
					alert('Old password is incorrect.');
				} else {
					alert('An error occurred.');
				}
			}
		});
	});

    $('#manage-user').submit(function (e) {
    e.preventDefault();
    start_loader();

    $.ajax({
        url: _base_url_ + 'classes/Users.php?f=save',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function (resp) {
            try {
                // Parse the JSON response
                var res = JSON.parse(resp);

                if (res.status === 'success') {
                    if (res.password_changed) {
                        alert_toast("Password changed successfully. Logging out...", 'success');
                        setTimeout(function () {
                            window.location.href = "<?php echo base_url . '/classes/Login.php?f=logout' ?>";
                        }, 2000);
                    } else {
                        alert_toast("User details successfully updated.", 'success');
                        // Ensure user_id is passed and used correctly
                        $.ajax({
                            url: _base_url_ + "classes/Master.php",
                            type: 'POST',
                            data: {
                                f: 'updateuser_activity',
                            },
                            success: function(response) {
                                console.log('Activity logged successfully.');
                            },
                            error: function(xhr, status, error) {
                                console.error('Error logging activity:', error);
                            }
                        });
                        setTimeout(function () {
                            location.reload(); // Refresh the page
                        }, 0);

                        
                    }
                } else if (res.status === 'error') {
                    $('#msg').html('<div class="alert alert-danger">' + res.message + '</div>');
                }
            } catch (e) {
                console.error("Failed to parse response: ", e);
                $('#msg').html('<div class="alert alert-danger">Error updating details. Please try again.</div>');
            }
            end_loader();
        }
    });
});



</script>
