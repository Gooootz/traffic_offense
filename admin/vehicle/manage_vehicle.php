<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    // Prevent SQL injection using prepared statements
    $stmt = $conn->prepare("SELECT * FROM `vehicles` WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a record exists and fetch data
    if($result->num_rows > 0){
        $vehicle_data = $result->fetch_assoc();
        foreach($vehicle_data as $k => $v){
            $$k = $v;
        }
    }
}
?>
<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($id) ? "Update " : "Create a New " ?> Vehicle Type</h3>
    </div>
    <div class="card-body">
        <form action="" id="vehicle-form">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
            <div class="form-group col-6">
                <label for="name" class="control-label">Type of Vehicle</label>
                <input name="name" id="name" type="text" class="form-control form" value="<?php echo isset($name) ? $name : ''; ?>"/required>
            </div>
            <div class="form-group col-4">
                <label for="status" class="control-label">Status</label>
                <select name="status" id="status" class="custom-select select">
                    <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            
        </form>
    </div>
    <div class="card-footer">
        <button class="btn btn-flat btn-primary" form="vehicle-form">Save</button>
        <a class="btn btn-flat btn-default" href="?page=vehicle">Cancel</a>
    </div>
</div>
<script>
$(document).ready(function(){
    $('#vehicle-form').submit(function(e){
        e.preventDefault();
        var _this = $(this);
        $('.err-msg').remove();
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=save_vehicle",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            dataType: 'json',
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                alert_toast("An error occurred", 'error');
                end_loader();
            },
            success: function(resp) {
                if(resp.status == 'success') {
                    location.href = "./?page=vehicle";
                } else if(resp.status == 'failed' && !!resp.msg) {
                    var el = $('<div>');
                    el.addClass("alert alert-danger err-msg").text(resp.msg);
                    _this.prepend(el);
                    el.show('slow');
                    $("html, body").animate({ scrollTop: _this.closest('.card').offset().top }, "fast");
                    end_loader();
                } else {
                    alert_toast("An error occurred", 'error');
                    end_loader();
                    console.log(resp);
                }
            }
        });
    });

    $('.summernote').summernote({
        height: '30vh',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ol', 'ul', 'paragraph', 'height']],
            ['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
        ]
    });
});
</script>
