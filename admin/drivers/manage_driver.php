<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `drivers_list` where id = '{$_GET['id']}' ");
    // $qry2 = $conn->query("SELECT * from `drivers_meta` where driver_id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}
?>

<style>
img#cimg {
    height: 25vh;
    width: 15vw;
    object-fit: scale-down;
    object-position: center center;
}
</style>
<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($id) ? "Update " : "Create New " ?> Driver</h3>
    </div>
    <div class="card-body">
        <!-- Adding new driver form -->
        <form action="driver-form" id="driver-form">

            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="lastname" class="control-label">Last Name</label>
                        <input type="text" class="form-control form" required name="lastname"
                            value="<?php echo isset($lastname) ? $lastname : '' ?>" placeholder="(e.g. Delacruz)">
                    </div>
                    <div class="form-group">
                        <label for="firstname" class="control-label">First Name</label>
                        <input type="text" class="form-control form" required name="firstname"
                            value="<?php echo isset($firstname) ? $firstname : '' ?>" placeholder="(e.g. Juan)">
                    </div>
                    <div class="form-group">
                        <label for="middlename" class="control-label">Middle Name</label>
                        <input type="text" class="form-control form" required name="middlename"
                            value="<?php echo isset($middlename) ? $middlename : '' ?>" placeholder="(e.g. Santos)">
                    </div>
                    <div class="form-group">
                        <label for="suffix" class="control-label">Suffix</label>
                        <input type="text" class="form-control form" name="suffix"
                            value="<?php echo isset($suffix) ? $suffix : '' ?>" placeholder="(e.g. Jr.)">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="address" class="control-label">Address</label>
                        <input type="text" class="form-control form" required name="address"
                            value="<?php echo isset($address) ? $address : '' ?>" placeholder="(e.g. Solano)">
                    </div>
                    <div class="form-group">
                        <label for="license_type" class="control-label">Gender</label>
                        <select name="gender" id="gender" class="custom-select select2" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="Male" <?php echo (isset($gender) && $gender == 'Male') ? 'selected' : '' ?>>
                                Male</option>
                            <option value="Female"
                                <?php echo (isset($gender) && $gender == 'Female') ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="form-group" id="new-driver-fields">
                        <label for="license_id_no" class="control-label">License No.</label>
                        <br>
                        <input type="text" maxlength="50" class="form-control form" id="license_id_no"
                            name="license_id_no" value="<?php echo isset($license_id_no) ? $license_id_no : '' ?>"
                            placeholder="(optional)">
                        <!-- <label>No License</label>
                        <input type="checkbox" name="license_type" id="no_license" value="Unlicensed"> -->
                    </div>
                    <div class="form-group" id="new-driver-fields">
                        <label for="license_type" class="control-label">License Type</label>
                        <select name="license_type" id="license_type" class="custom-select select2" required>
                            <option value="" disabled selected>Please Select License Type</option>
                            <option value="Unlicensed"
                                <?php echo (isset($license_type) && $license_type == 'Unlicensed') ? 'selected' : '' ?>>
                                Unlicensed</option>
                            <option value="Student"
                                <?php echo (isset($license_type) && $license_type == 'Student') ? 'selected' : '' ?>>
                                Student</option>
                            <option value="Non-Professional"
                                <?php echo (isset($license_type) && $license_type == 'Non-Professional') ? 'selected' : '' ?>>
                                Non-Professional</option>
                            <option value="Professional"
                                <?php echo (isset($license_type) && $license_type == 'Professional') ? 'selected' : '' ?>>
                                Professional</option>
                        </select>
                    </div>
                </div>
            </div>


        </form>
    </div>
    <div class="card-footer">
        <button class="btn btn-flat btn-primary" form="driver-form">Save</button>
        <a class="btn btn-flat btn-default" href="?page=drivers">Cancel</a>
    </div>
</div>
<script>
function displayImg(input, _this) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#cimg').attr('src', e.target.result);
            _this.siblings('.custom-file-label').html(input.files[0].name);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).ready(function() {
    // Function to handle license type change event
    $('#license_type').change(function() {
        if ($(this).val() === 'Unlicensed') {
            // Clear and disable license number field
            $('#license_id_no').val('').prop('disabled', true);
        } else {
            // Enable the license number field if another option is selected
            $('#license_id_no').prop('disabled', false);
        }
    });

    // Trigger change event on page load to ensure proper state is set
    $('#license_type').trigger('change');

    $('#driver-form').submit(function(e) {
        e.preventDefault();
        var _this = $(this);
        $('.err-msg').remove();
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=save_driver",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            dataType: 'json',
            error: function(err) {
                console.log(err);
                alert_toast("An error occurred", 'error');
                end_loader();
            },
            success: function(resp) {
                if (typeof resp === 'object' && resp.status === 'success') {
                    location.href = "./?page=drivers";
                } else if (resp.status === 'failed' && !!resp.msg) {
                    var el = $('<div>').addClass("alert alert-danger err-msg").text(resp
                        .msg);
                    _this.prepend(el);
                    el.show('slow');
                    $("html, body").animate({
                        scrollTop: _this.closest('.card').offset().top
                    }, "fast");
                    end_loader();
                } else {
                    alert_toast("An error occurred", 'error');
                    end_loader();
                    console.log(resp);
                }
            }
        });
    });
});
</script>