<?php
require_once ('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `drivers_list` where id = '{$_GET['id']}' ");
    $qry2 = $conn->query("SELECT * from `drivers_meta` where driver_id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
    if ($qry2->num_rows > 0) {
        while ($row = $qry2->fetch_assoc()) {
            ${$row['meta_field']} = $row['meta_value'];
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

    p,
    label {
        margin-bottom: 5px;
    }

    #uni_modal .modal-footer {
        display: none !important;
    }
</style>
<div class="container-fluid">
    <div class="w-100 d-flex justify-content-end mb-2">
        <button class="btn btn-flat btn-sm btn-default bg-lightblue" type="button" id="print"><i
                class="fa fa-print"></i> Print</button>
        <button class="btn btn-flat btn-sm btn-default bg-black" data-dismiss="modal"><i class="fa fa-times"></i>
            Close</button>
    </div>
    <div class="border border-dark px-2 py-2" id="print_out">
        <table class="table">
            <tr class='boder-0'>
                <td width="80%" class='boder-0 align-bottom'>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex w-max-100">
                                <label class="float-left w-auto whitespace-nowrap">License ID:</label>
                                <p class="col-md-auto border-bottom border-dark w-100">
                                    <b><?php echo !empty($license_id_no) ? $license_id_no : 'No License'; ?></b>
                                </p>
                            </div>
                            <div class="d-flex w-max-100">
                                <label class="float-left w-auto whitespace-nowrap">License Type:</label>
                                <p class="col-md-auto border-bottom border-dark w-100">
                                    <b><?php echo $license_type ?></b>
                                </p>
                            </div>
                            <div class="d-flex w-max-100">
                                <label class="float-left w-auto whitespace-nowrap">Name:</label>
                                <p class="col-md-auto border-bottom border-dark w-100"><b><?php echo $name ?></b></p>
                            </div>
                            <div class="d-flex w-max-100">
                                <label class="float-left w-auto whitespace-nowrap">Address:</label>
                                <p class="col-md-auto border-bottom border-dark w-100"><b><?php echo $address ?></b></p>
                            </div>


                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <h4 class="text-center"><b>Offense Records</b></h4>
        <table class='table table-stripped px-4'>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Offense</th>
                    <th>Fine</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query to get offense items with status from offense_list1 table
                $olist = $conn->query("SELECT i.*, o.code, o.offensename, ol.status FROM `offense_items` i INNER JOIN `offenses` o ON i.offense_id = o.id INNER JOIN `offense_list1` ol ON i.driver_offense_id = ol.id WHERE ol.driver_id = '{$driver_id}' ORDER BY unix_timestamp(i.date_created) ASC");

                // Check for query errors
                if ($conn->error) {
                    echo $conn->error . "\n";
                }

                // Check if there are any records
                if ($olist->num_rows > 0):
                    // Loop through the results
                    while ($row = $olist->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo date("M d, Y H:i A", strtotime($row['date_created'])); ?></td>
                            <td><?php echo htmlspecialchars($row['offensename'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>₱ <?php echo number_format($row['fine'], 2); ?></td>
                            <td><?php echo ($row['status'] == 1) ? "Paid" : "Pending"; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <th class="text-center" colspan="4">No Record.</th>
                    </tr>
                <?php endif; ?>
            </tbody>


        </table>
    </div>
</div>

<script>
    $(function () {
        $('#print').click(function () {
            start_loader()
            var _h = $('head').clone()
            var _p = $('#print_out').clone();
            var _el = $('<div>')
            _el.append(_h)
            _el.append('<style>html, body, .wrapper {min-height: unset !important;}</style>')
            _p.prepend('<div class="d-flex mb-3 w-100 align-items-center justify-content-center">' +
                '<img class="mx-4" src="<?php echo validate_image($_settings->info('logo')) ?>" width="50px" height="50px"/>' +
                '<div class="px-2">' +
                '<h3 class="text-center"><?php echo $_settings->info('name') ?></h3>           '+
                '<h3 class="text-center">Driver\'s Information and Traffic Offense Records</h3>' +
                '</div>' +
                '</div><hr/>');
            _el.append(_p)
            var nw = window.open("", "_blank", "width=1200,height=1200")
            nw.document.write(_el.html())
            nw.document.close()
            setTimeout(() => {
                nw.print()
                setTimeout(() => {
                    nw.close()
                    end_loader()
                }, 300);
            }, 500);
        })
    })
</script>