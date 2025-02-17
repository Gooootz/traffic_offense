<?php if ($_settings->chk_flashdata('success')): ?>
<script>
alert_toast("<?php echo $_settings->flashdata('success'); ?>", 'success');
</script>
<?php endif; ?>

<?php
// Execute the query to get the offenses and their ranks
$getOffense = $conn->query("SELECT
    ol.id,
    dl.`lastname`,
    o.offensename,
    ot.fine,
    ROW_NUMBER() OVER (PARTITION BY dl.`lastname`, o.offensename ORDER BY ol.date_created) AS offense_rank 
FROM
    offense_list1 ol
    INNER JOIN drivers_list dl ON ol.driver_id = dl.id
    INNER JOIN offense_items ot ON ol.id = ot.driver_offense_id
    INNER JOIN offenses o ON ot.offense_id = o.id 
ORDER BY
    ol.date_created DESC");

// Fetch the fines and store them in an associative array
$fines = [];
$getFine = $conn->query("SELECT id, offensename, fine, fine2, fine3 FROM offenses");

while ($row2 = $getFine->fetch_assoc()) {
    $fines[$row2['offensename']] = $row2;
}

// Process the offenses and update the fines
while ($row = $getOffense->fetch_assoc()) {
    if (isset($fines[$row['offensename']])) {
        $fineData = $fines[$row['offensename']];
        
        // Determine the fine based on the offense rank
        $newFine = null;
        if ($row['offense_rank'] == 1 && isset($fineData['fine'])) {
            $newFine = $fineData['fine'];
        } elseif ($row['offense_rank'] == 2 && isset($fineData['fine2'])) {
            $newFine = $fineData['fine2'];
        } elseif ($row['offense_rank'] > 2 && isset($fineData['fine3'])) {
            $newFine = $fineData['fine3'];
        }

        // Update fine only if a valid value is found
        if ($newFine !== null) {
            $conn->query("UPDATE offense_items SET fine = '{$newFine}' WHERE driver_offense_id = '{$row['id']}' AND offense_id = '{$fineData['id']}'");
        }
    }
}
?>



<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">List of Offense Records</h3>
        <div class="card-tools">
            <a href="?page=offenses/manage_record" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>
                Create New</a>
        </div>

    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="container-fluid">
                <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search...">

                <table class="table table-hover table-striped">
                    <colgroup>
                        <col width="3%">
                        <col width="10%">
                        <col width="7%">
                        <col width="15%">
                        <col width="35%">
                        <col width="15%">
                        <col width="3%">
                        <col width="15%">
                    </colgroup>

                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Date</th>
                            <th>Ticket No.</th>
                            <th>Driver</th>
                            <th>Offense</th>
                            <th>Officer</th>
                            <th>Status</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php
                        $i = 1;
                        $qry = $conn->query("SELECT 
                                                ol.id, 
                                                ol.date_created, 
                                                ol.ticket_no, 
                                                dl.lastname AS driver_lastname, 
                                                dl.firstname AS driver_firstname, 
                                                dl.middlename AS driver_middlename, 
                                                dl.suffix AS driver_suffix, 
                                                o.offensename, 
                                                ol.officer, 
                                                ol.`status`,
                                                CONCAT(ofc.firstname, ' ', 
                                                    IFNULL(ofc.middlename, ''), ' ', 
                                                    ofc.lastname, ' ', 
                                                    IFNULL(ofc.suffix, '')) AS officername
                                            FROM offense_list1 ol
                                            INNER JOIN drivers_list dl ON ol.driver_id = dl.id
                                            INNER JOIN offense_items ot ON ol.id = ot.driver_offense_id 
                                            INNER JOIN offenses o ON ot.offense_id = o.id
                                            INNER JOIN officers_list ofc ON ol.officer = ofc.officer_id_no
                                            GROUP BY ol.ticket_no
                                            ORDER BY ol.date_created DESC");

                        while ($row = $qry->fetch_assoc()):
                        ?>

                        <tr>
                            <!-- # -->
                            <td class="text-center"><?php echo $i++; ?></td>
                            <!-- DateTime -->
                            <td><?php echo date("m/d/Y h:i:s A", strtotime($row['date_created'])) ?></td>
                            <!-- TicketNo -->
                            <td><?php echo $row['ticket_no'] ?></td>
                            <!-- Drivers Name -->
                            <td>
                                <?php 
                                    echo trim($row['driver_firstname'] . ' ' . $row['driver_middlename'] . ' ' . $row['driver_lastname'] . ' ' . $row['driver_suffix']);
                                ?>
                            </td>
                            <!-- Offense -->
                            <td>
                                <?php
                                $ofrank = $conn->query("SELECT ol.id, o.offensename,
                                    ROW_NUMBER() OVER(PARTITION BY dl.lastname, o.offensename ORDER BY ol.date_created) AS offense_rank
                                    FROM offense_list1 ol
                                    INNER JOIN drivers_list dl ON ol.driver_id = dl.id
                                    INNER JOIN offense_items ot ON ol.id = ot.driver_offense_id 
                                    INNER JOIN offenses o ON ot.offense_id = o.id
                                    ORDER BY ol.date_created DESC");

                                while ($ofrankrow = $ofrank->fetch_assoc()) {
                                    if ($row['id'] == $ofrankrow['id']) {
                                        echo $ofrankrow['offensename'];
                                        echo " <strong>(";
                                        if ($ofrankrow['offense_rank'] == 1) {
                                            echo "1st offense";
                                        } else if ($ofrankrow['offense_rank'] == 2) {
                                            echo "2nd offense";
                                        } else if ($ofrankrow['offense_rank'] == 3) {
                                            echo "3rd offense";
                                        } else {
                                            echo $ofrankrow['offense_rank'] . "th offense";
                                        }
                                        echo ")</strong><br>";
                                    }
                                }
                                ?>
                            </td>
                            <!-- Officer Name -->
                            <td><?php echo $row['officername'] ?></td>
                            <!-- Status -->
                            <td>
                                <?php if (isset($row['status']) && $row['status'] == 1): ?>
                                <span class="badge badge-success">Paid</span>
                                <?php else: ?>
                                <span class="badge badge-secondary">Pending</span>
                                <?php endif; ?>
                            </td>
                            <!-- Action -->
                            <td style="text-align: center; vertical-align: middle;">
                                <div style="display: inline-block;">
                                    <a class="btn btn-outline-primary"
                                        href="?page=offenses/manage_record&id=<?php echo $row['id'] ?>">
                                        <span class="fa fa-edit"></span>
                                    </a>
                                    <a class="btn btn-outline-secondary view_details" href="javascript:void(0)"
                                        data-id="<?php echo hash("sha256", $row['id']) ?>"
                                        data-ofns="<?php echo $row['id'] ?>" data-num="<?php echo $row['id'] ?>">
                                        <span class="fa fa-print"></span>
                                    </a>
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
// Function for deleting offense record
function delete_offense($id) {
    start_loader();
    $.ajax({
        url: _base_url_ + "classes/Master.php?f=delete_offense_record",
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
                // Reload the page to reflect changes
                location.reload();
            } else {
                alert_toast("An error occured.", 'error');
                end_loader();
            }
        }
    })
}
document.getElementById('searchInput').addEventListener('keyup', function() {
    var filter = this.value.toLowerCase();
    var rows = document.querySelectorAll('#tableBody tr');

    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        row.style.display = text.indexOf(filter) > -1 ? '' : 'none';
    });
});


$(document).ready(function() {
    // Click event listener for deleting offense record
    $('.delete_data').click(function() {
        _conf("Are you sure to delete this offense record permanently?", "delete_offense", [$(this)
            .attr('data-id')
        ])
    });

    // Click event listener for updating the status of the offense record
    $('.paid_data').click(function() {
        _conf("Are you sure that this offense record is Paid?", "paid_offense", [$(this).data('id')]);
    });

    // Click event listener for updating the status of the offense record
    $('.unpaid_data').click(function() {
        _conf("Are you sure that this offense record is Not Paid?", "unpaid_offense", [$(this).data(
            'id')]);
    });

    // Click event listener for viewing offense details
    $('.view_details').click(function() {
        uni_modal(
            "<i class='fa fa-ticket'></i> Driver's Offense Ticket Details",
            "offenses/view_details.php?id=" + $(this).attr('data-id') + "&ofns=" + $(this).attr(
                'data-ofns') + "&k=" + $(this).attr('data-num'),
            ''
        );
    });




    // Initialize DataTable
    $('.table').dataTable({
        columnDefs: [{
            orderable: false,
            targets: [1, 4, 7, 8]
        }]
    });


});
</script>