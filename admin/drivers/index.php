<?php if($_settings->chk_flashdata('success')): ?>
<script>
alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Apprehension Records</h3>
        <div class="card-tools">
            <a href="?page=drivers/manage_driver" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>
                Create New</a>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="container-fluid">
                <table class="table table-hover table-stripped">
                    <colgroup>
                        <col width="5%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="2   0%">
                        <col width="5%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>License ID</th>
                            <th>Firstname</th>
                            <th>Middlename</th>
                            <th>Lastname</th>
                            <th>Suffix</th>
                            <th>Address</th>
                            <th>License Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $qry = $conn->query("SELECT * FROM `drivers_list` ORDER BY unix_timestamp(date_created) DESC");
                        while ($row = $qry->fetch_assoc()):
                            // Fetch data directly from the `drivers_list` table
                            $license_type = $row['license_type'] ?? "N/A"; // Use 'license_type' column from `drivers_list`
                            $address = $row['address'] ?? ""; // Use 'address' column from `drivers_list`
                            $firstname = $row['firstname'] ?? ""; // Use 'firstname' column from `drivers_list`
                            $middlename = $row['middlename'] ?? ""; // Use 'middlename' column from `drivers_list`
                            $lastname = $row['lastname'] ?? ""; // Use 'lastname' column from `drivers_list`
                            $suffix = $row['suffix'] ?? ""; // Use 'suffix' column from `drivers_list`
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo $row['license_id_no'] ?></td>
                            <td><span class="mr-2"><a href="javascript:void(0)"
                                        class="view_details badge badge-dark text-light"
                                        data-id="<?php echo $row['id'] ?>"> <i class="fa fa-eye"></i></a></span>
                                <?php echo $firstname ?></td>

                            <td><?php echo $middlename ?></td>
                            <td><?php echo $lastname ?></td>
                            <td><?php echo $suffix ?></td>
                            <td><?php echo $address ?></td>
                            <td><?php echo $row['license_type'] ?></td>

                            <td>
                                <div style="display: inline;" align="center">

                                    <a class="btn btn-outline-primary"
                                        href="?page=drivers/manage_driver&id=<?php echo $row['id'] ?>">
                                        <span class="fa fa-edit"></span>
                                    </a>
                                    <!-- <a class="btn btn-outline-danger delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
										<span class="fa fa-trash"></span>
									</a> -->

                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.delete_data').click(function() {
        _conf("Are you sure to delete this driver permanently?", "delete_driver", [$(this).attr(
            'data-id')])
    })
    $('.view_details').click(function() {
        uni_modal("<i class='fa fa-id-card'></i> Driver's Information", "drivers/view_details.php?id=" +
            $(this).attr('data-id'), 'large')
    })
    $('.table').dataTable({
        columnDefs: [{
            orderable: false,
            targets: [3, 4]
        }]
    });
})

function delete_driver($id) {
    start_loader();
    $.ajax({
        url: _base_url_ + "classes/Master.php?f=delete_driver",
        method: "POST",
        data: {
            id: $id
        },
        dataType: "json",
        error: err => {
            console.log(err)
            alert_toast("An error occured.", 'error');
            end_loader();
        },
        success: function(resp) {
            if (typeof resp == 'object' && resp.status == 'success') {
                location.reload();
            } else {
                alert_toast("An error occured.", 'error');
                end_loader();
            }
        }
    })
}
</script>