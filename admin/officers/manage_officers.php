<?php
if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
    $id = $_GET['id'];
    $qry = $conn->prepare("SELECT * from `officers_list` where id = ?");
    $qry->bind_param("i", $id);
    $qry->execute();
    $result = $qry->get_result();
    
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        foreach($row as $k => $v){
            ${$k} = htmlspecialchars($v); // Sanitize data to prevent XSS
        }
    }
    
    $qry2 = $conn->prepare("SELECT * from `officers_meta` where officer_id = ?");
    $qry2->bind_param("i", $id);
    $qry2->execute();
    $result2 = $qry2->get_result();
    
    if($result2->num_rows > 0){
        while($row = $result2->fetch_assoc()){
            ${$row['meta_field']} = htmlspecialchars($row['meta_value']); // Sanitize data
        }
    }
}
?>

<style>
    img#cimg{
        height: 25vh;
        width: 15vw;
        object-fit: scale-down;
        object-position: center center;
    }
</style>
<div class="card card-outline card-info">
	<div class="card-header">
		<h3 class="card-title"><?php echo isset($id) ? "Update ": "Create New " ?> Driver</h3>
	</div>
	<div class="card-body">
		<form action="" id="officer-form">
			<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
			<div class="row">
				<div class="col-6">
					<div class="form-group">
						<label for="officer_id_no" class="control-label">Officer ID No.</label>
						<br>
						<input type="text" class="form-control form" required name="officer_id_no" value="<?php echo isset($officer_id_no) ? $officer_id_no : '' ?>">
					</div>
					<div class="form-group">
						<label for="lastname" class="control-label">Last Name</label>
						<input type="text" class="form-control form" required name="lastname" value="<?php echo isset($lastname) ? $lastname : '' ?>">
					</div>
					<div class="form-group">
						<label for="firstname" class="control-label">First Name</label>
						<input type="text" class="form-control form" required name="firstname" value="<?php echo isset($firstname) ? $firstname : '' ?>">
					</div>
					<div class="form-group">
						<label for="middlename" class="control-label">Middle Name</label>
						<input type="text" class="form-control form" name="middlename" value="<?php echo isset($middlename) ? $middlename : '' ?>">
					</div>
					<div class="form-group">
						<label for="dob" class="control-label">Date of Birth</label>
						<input type="date" class="form-control form" required name="dob" value="<?php echo isset($dob) ? date("Y-m-d",strtotime($dob)) : '' ?>">
					</div>
					<div class="form-group">
						<label for="present_address" class="control-label">Present Address</label>
						<textarea rows="3" class="form-control" style="resize:none" required name="present_address"><?php echo isset($present_address) ? $present_address : '' ?></textarea>
					</div>
					<div class="form-group">
						<label for="permanent_address" class="control-label">Permanent Address</label>
						<textarea rows="3" class="form-control" style="resize:none" required name="permanent_address"><?php echo isset($permanent_address) ? $permanent_address : '' ?></textarea>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<label for="civil_status" class="control-label">Civil Status</label>
						<select name="civil_status" id="civil_status" class="custom-select select2">
							<option <?php echo (isset($civil_status) && $civil_status == 'Single') ? 'selected' : '' ?>>Single</option>
							<option <?php echo (isset($civil_status) && $civil_status == 'In a Relationship') ? 'selected' : '' ?>>In a Relationship</option>
							<option <?php echo (isset($civil_status) && $civil_status == 'Married') ? 'selected' : '' ?>>Married</option>
							<option <?php echo (isset($civil_status) && $civil_status == 'Divorced') ? 'selected' : '' ?>>Divorced</option>
							<option <?php echo (isset($civil_status) && $civil_status == 'Windowed') ? 'selected' : '' ?>>Windowed</option>
						</select>
					</div>
					<div class="form-group">
						<label for="nationality" class="control-label">Nationality</label>
						<input type="text" class="form-control form" required name="nationality" value="<?php echo isset($nationality) ? $nationality : '' ?>">
					</div>
					<div class="form-group">
						<label for="contact" class="control-label">Contact Number</label>
						<input type="text" maxlength="13" class="form-control form" required name="contact" value="<?php echo isset($contact) ? $contact : '' ?>">
					</div>
					<div class="form-group">
						<label for="" class="control-label">Photo</label>
						<div class="custom-file">
						<input type="hidden" name="image_path" value="<?php echo isset($image_path) ? $image_path : '' ?>">
						<input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))">
						<label class="custom-file-label" for="customFile">Choose file</label>
						</div>
					</div>
					<div class="form-group d-flex justify-content-center">
						<img align="center" src="<?php echo validate_image(isset($image_path) ? $image_path : '') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
					</div>
				</div>
			</div>
			
		</form>
	</div>
	<div class="card-footer">
		<button class="btn btn-flat btn-primary" form="officer-form">Save</button>
		<a class="btn btn-flat btn-default" href="?page=officers">Cancel</a>
	</div>
</div>
<script>
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        	_this.siblings('.custom-file-label').html(input.files[0].name)
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$(document).ready(function(){
		$('#officer-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_officer",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured manage",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.href = "./?page=officers";
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body").animate({ scrollTop: _this.closest('.card').offset().top }, "fast");
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})
	})
</script>