<?php if($_settings->chk_flashdata('success')): ?>
<script>
alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
    <div class="card-header">

        <h3 class="card-title">List of Officers</h3>
        <?php if($_settings->userdata('type') == 1): ?>
        <div class="card-tools">
            <a href="?page=officers/manage_officers" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>
                Create New</a>
        </div>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="container-fluid">
                <table class="table table-hover table-stripped">

                    <?php if($_settings->userdata('type') == 1): ?>
                    <colgroup>
                        <col width="1%">
                        <col width="10%">
                        <col width="20%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="25%">
                        <col width="20%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <?php else: ?>
                    <colgroup>
                        <col width="1%">
                        <col width="10%">
                        <col width="20%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="20%">
                        <col width="25%">
                    </colgroup>
                    <?php endif; ?>

                    <colgroup>

                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Officer ID</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Suffix</th>
                            <th>Address</th>
                            <th>Contact Number</th>
                            <th style="text-align: center;">Status</th>
                            <!-- <th>License Type</th> -->
                            <?php if($_settings->userdata('type') == 1): ?>

                            <th style="text-align: center;">Action</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT * FROM `officers_list` ORDER BY UNIX_TIMESTAMP(date_created) DESC");
                        while($row = $qry->fetch_assoc()):
                            // Fetch data directly from officers_list
                            $permanent_address = $row['permanent_address'];
                            $contact = $row['contact'];
                            $firstname = $row['firstname'];
                            $middlename = $row['middlename'];
                            $lastname = $row['lastname'];
                            $suffix = $row['suffix'];
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo $row['officer_id_no'] ?></td>
                            <td>
                                <span class="mr-2">
                                    <a href="javascript:void(0)" class="view_details badge badge-dark text-light"
                                        data-id="<?php echo $row['id'] ?>">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </span>
                                <?php echo $firstname ?>
                            </td>
                            <td><?php echo $middlename ?></td>
                            <td><?php echo $lastname ?></td>
                            <td><?php echo $suffix ?></td>
                            <td><?php echo $permanent_address ?></td> <!-- Updated to use fetched permanent_address -->
                            <td><?php echo $contact ?></td>
                            <td style="text-align: center;">
                                <?php if (isset($row['status']) && $row['status'] == 1): ?>
                                <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                <span class="badge badge-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <?php if($_settings->userdata('type') == 1): ?>
                            <td style="text-align: center;">
                                <div style="display: inline;" align="center">
                                    <a class="btn btn-outline-primary"
                                        href="?page=officers/manage_officers&id=<?php echo $row['id'] ?>">
                                        <span class="fa fa-edit"></span>
                                    </a>
                                    <!-- <a class="btn btn-outline-danger delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                    <span class="fa fa-trash"></span>
                </a> -->
                                </div>
                            </td>
                            <?php endif; ?>
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
        _conf("Are you sure to delete this officer permanently?", "delete_officer", [$(this).attr(
            'data-id')])
    })
    $('.view_details').click(function() {
        uni_modal("<i class='fa fa-id-card'></i> Officers's Information",
            "officers/view_details.php?id=" + $(this).attr('data-id'), 'large')
    })
    $('.table').dataTable({
        columnDefs: [{
            orderable: false,
            targets: [3, 4]
        }]
    });
})

function delete_officer($id) {
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