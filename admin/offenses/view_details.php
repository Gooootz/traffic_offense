<?php
require_once ('../../config.php');
$total_amount = 0;

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT r.*,d.license_id_no, d.name as driver from `offense_list1` r inner join `drivers_list` d on r.driver_id = d.id where r.id = '{$_GET['id']}' ");
    if ($conn->error) {
        echo $conn->error . "\n";
        echo "SELECT r.*,d.license_id_no, d.name as driver from `offense_list1` r inner join `drivers_list` d on r.driver_id = d.id where r.id = '{$_GET['id']}' ";
    }
    $qry2 = $conn->query("SELECT i.*,o.code,o.offensename, i.fine from `offense_items` i inner join `offenses` o on i.offense_id = o.id where i.driver_offense_id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
    $offense_arr = array();
    if ($qry2->num_rows > 0) {
        while ($row = $qry2->fetch_assoc()) {
            $offense_arr[] = $row;
        }
    }
}
?>


<div class="container-fluid">
    <div class="w-100 d-flex justify-content-end mb-2">
        <button class="btn btn-flat btn-sm btn-default bg-lightblue" type="button" id="print"><i
                class="fa fa-print"></i> Print</button>
        <button class="btn btn-flat btn-sm btn-default bg-black" data-dismiss="modal"><i class="fa fa-times"></i>
            Close</button>
    </div>
    <div class="border border-dark px-2 py-2" id="print_out">

        <style>
            img#cimg {
                height: 100%;
                width: 100%;
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

            .custom-background {
                background-color: #f2b955;
            }

            .custom-background1 {
                background-color: #c8f255;
            }

            .skyblue {
                background-color: skyblue;
            }

            .custom-border {
                border: 2px solid #000;
            }
        </style>

        <table class="table border-0">

            <tr class='border-0'>
                <td width="80%" class='border-0 align-bottom'>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex px-2 w-max-100">
                                <label class="float-left w-auto whitespace-nowrap">DATE: </label>
                                <p class="col-md-auto w-100">
                                    <b><?php echo date("l, F j, Y", strtotime($date_created)) ?></b>
                                </p>
                            </div>

                            <div class="d-flex">
                                <div class="d-flex flex-column mr-auto ">
                                    <div class="d-flex">
                                        <label class="float-left w-auto whitespace-nowrap px-2 skyblue">TOP NO: </label>
                                        <p class="col-md-auto w-100"><b><?php echo $ticket_no ?></b></p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column ml-4">
                                    <div class="d-flex">
                                        <label class="float-left w-auto whitespace-nowrap px-2 skyblue">Date of Release:
                                        </label>
                                        <p class="col-md-auto w-auto">
                                            <?php
                                            if ($status == 0) {
                                                echo "<b>Pending</b>";
                                            } else {
                                                echo "<b>" . date("d-M-y", strtotime($date_released)) . "</b>";
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>


                            <div class="d-flex px-2 w-max-100 custom-background">
                                <label class="float-left w-auto whitespace-nowrap">NAME: </label>
                                <p class="col-md-auto w-100"><b><?php echo $driver ?></b></p>
                            </div>

                            <label class="float-left w-auto whitespace-nowrap px-2 custom-background1">ADDRESS :</label>
                            <div class="d-flex w-max-100">
                                <p class="col-md-auto w-100 custom-background"><b><?php echo $address2 ?></b></p>
                            </div>

                            <div class="d-flex px-2">
                                <div class="d-flex flex-grow-1">
                                    <label class="w-auto whitespace-nowrap ">VEHICLE: </label>
                                    <p class="col-md-auto w-100 "><b><?php echo $type_of_vehicle ?></b></p>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <p class="w-100"><b><?php echo $ownership_classification ?></b></p>
                                </div>
                            </div>

                            <label class="float-left w-auto whitespace-nowrap px-2">OR No </label>
                            <div class="d-flex w-max-100">
                                <p class="border-bottom border-dark w-25"><b>: <?php echo $or_number ?></b></p>
                            </div>

                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <?php
        // Determine if the form is in update mode
        $isUpdateMode = !empty($id);

        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $or_number = $_POST['or_number'];
            $or_amount = isset($_POST['discountAmount']) ? (float) $_POST['discountAmount'] : 0;

            // Convert total_amount to a float (assuming total_amount is being set somewhere in your code)
            $total_amount = isset($total_amount) ? (float) $total_amount : 0;

            // Validate or_number
            if (empty($or_number)) {
                echo "<script>alert('OR number cannot be empty.');</script>";
            } elseif (!is_numeric($or_number)) {
                echo "<script>alert('OR number must be a number.');</script>";
            } else {
                // Check for duplicate OR number
                $or_number = mysqli_real_escape_string($conn, $or_number); // Sanitize input
                $checkDuplicateSql = "SELECT COUNT(*) AS count FROM `offense_list1` WHERE `or_number` = '{$or_number}'";
                $result = mysqli_query($conn, $checkDuplicateSql);
                $row = mysqli_fetch_assoc($result);

                if ($row['count'] > 0) {
                    echo "<script>alert('Duplicate OR number found.');</script>";
                } else {
                    // Check if or_amount is greater than total_amount
                    if ($or_amount > $total_amount) {
                        echo "<script>alert('The discount amount cannot be greater than the total amount.');</script>";
                    } else {
                        // Perform subtraction safely
                        $totalAmount = $total_amount - $or_amount;

                        if ($isUpdateMode) {
                            $updateSql = "UPDATE `offense_list1` SET `or_number` = '{$or_number}', `or_amount` = '{$or_amount}', `totalAmount` = '{$totalAmount}', `status` = 1 WHERE `id` = '{$id}'";

                            // Execute the update query (Assuming you have a connection $conn)
                            if (mysqli_query($conn, $updateSql)) {
                                echo "<script>alert('Record updated successfully');</script>";
                                echo "<script>window.location.href='" . base_url . "admin/?page=offenses';</script>";
                                exit; // Ensure no further code is executed after the redirect
                            } else {
                                echo "Error updating record: " . mysqli_error($conn);
                            }
                        }
                    }
                }
            }
        }

        // Set default values for the fields
        $or_number = isset($or_number) ? $or_number : '';
        $discountAmount = isset($or_amount) ? $or_amount : 0;
        ?>
        <hr class='bg-dark border-dark'>
        <table class='table table-stripped px-4'>
            <thead>
                <tr>
                    <th>Violation/s</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($offense_arr) > 0): ?>
                    <?php foreach ($offense_arr as $offense): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($offense['offensename'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class='text-right'>₱ <?php echo number_format($offense['fine'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($status == 1): ?>
                        <tr>
                            <td>
                                <b>Subtotal</b>
                            </td>
                            <td class="text-right">
                                <b>₱ <?php echo number_format($total_amount, 2); ?></b>
                            </td>
                        </tr>
                        <tr>

                            <td>
                                <b>Discount</b>
                            </td>
                            <td class="text-right">
                                <b>₱ <?php echo number_format((float) $discountAmount, 2); ?></b>
                            </td>
                        </tr>
                    <?php endif; ?>

                <?php else: ?>
                    <tr>
                        <td class="text-center" colspan="2">No Record.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <?php if ($status == 1): ?>
                    <tr>
                        <th>Total</th>
                        <th colspan="2" class="text-right" style="color:red">₱
                            <?php echo number_format($totalAmount, 2); ?>
                        </th>
                    </tr>
                <?php else: ?>
                    <tr>
                        <th>Total</th>
                        <th colspan="2" class="text-right" style="color:red">₱
                            <?php echo number_format($total_amount, 2); ?>
                        </th>
                    </tr>
                <?php endif; ?>
            </tfoot>
        </table>


        <hr class="bg-dark border-dark">
        <b>Remarks:</b>
        <p><?php echo $remarks ?></p>
    </div>
</div>

<script>
    $(function () {
        $('#print').click(function () {
            start_loader();
            var _h = $('head').clone();
            var _p = $('#print_out').clone();
            var _el = $('<div>');
            _p.prepend('<div class="d-flex mb-3 w-100 align-items-center justify-content-center">' +
                '<div class="px-2">' +
                '<p class="text-center"><b><?php echo $_settings->info('name') ?></b></p>' +
                '<h5 class="text-center">Traffic Offense Ticket</h5>' +
                '</div>' +
                '</div><hr/>');
            _el.append(_h);
            _el.append('<style>html, body, .wrapper {min-height: unset !important;}#print_out{width:50% !important;}</style>');
            _el.append(_p);


            // Calculate screen center
            var width = 1500;
            var height = 1000;
            var left = (screen.width / 2) - (width / 2);
            var top = (screen.height / 2) - (height / 2);

            var nw = window.open("", "_blank", "width=" + width + ",height=" + height + ",top=" + top + ",left=" + left);
            nw.document.write(_el.html());
            nw.document.close();
            setTimeout(() => {
                nw.print();
                setTimeout(() => {
                    nw.close();
                    end_loader();
                }, 300);
            }, 500);
        });
    });
</script>