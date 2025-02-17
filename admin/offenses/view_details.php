<?php
require_once('../../config.php');
$total_amount = 0;
$id = hash('sha256', $_GET['k']);

if ($id = $_GET['id']) {
    if (isset($_GET['k']) && $_GET['k'] > 0) {
        // Fetch the specific offense data and driver details
        $qry = $conn->query("SELECT r.*, d.license_id_no, CONCAT(d.firstname, ' ', d.middlename, ' ', d.lastname, ' ', d.suffix) AS driver, d.id AS driver_id FROM `offense_list1` r INNER JOIN `drivers_list` d ON r.driver_id = d.id WHERE r.id = '{$_GET['k']}'");
        if ($conn->error) {
            echo $conn->error . "\n";
            echo "SELECT r.*, d.license_id_no, d.name AS driver, d.id AS driver_id FROM `offense_list1` r INNER JOIN `drivers_list` d ON r.driver_id = d.id WHERE r.id = '{$_GET['k']}'";
        }

        if ($qry->num_rows > 0) {
            $driver_data = $qry->fetch_assoc();
            foreach ($driver_data as $k => $v) {
                $$k = $v;
            }

            // Use the concatenated driver name directly from the query result
            $driver_name = $driver_data['driver'];

        }

        // Initialize $offense_arr before using it
        $offense_arr = [];

        // Get all offenses for the driver ordered by the date of offense
        $driver_id = $driver_data['driver_id'];
        $qry_offenses = $conn->query("SELECT id FROM `offense_list1` WHERE driver_id = '$driver_id' ORDER BY `date_created`");
        $offenses = [];
        while ($row = $qry_offenses->fetch_assoc()) {
            $offenses[] = $row['id'];
        }

        // Get the position of the current offense in the list of offenses
        $offense_position = array_search($_GET['k'], $offenses) + 1;

        // Function to convert numbers to ordinal indicators
        function ordinal($number)
        {
            $suffixes = ["th", "st", "nd", "rd"];
            $value = $number % 100;
            if ($value >= 11 && $value <= 13) {
                return $number . "th";
            }
            return $number . ($suffixes[$value % 10] ?? $suffixes[0]);
        }

        // Display the offenses and their ordinal position
        // foreach ($offense_arr as $offense) {
        //     echo "Offense: " . htmlspecialchars($offense['offensename'], ENT_QUOTES, 'UTF-8') . "<br>";
        //     echo "This is the " . ordinal($offense_position) . " offense for driver " . htmlspecialchars($driver_name, ENT_QUOTES, 'UTF-8') . ".<br>";
        // }
    }
}
?>






<div class="container-fluid">
    <div class=" d-flex justify-content-end mb-2">
        <button class="btn btn-flat btn-sm btn-default bg-lightblue" type="button" id="print"><i
                class="fa fa-print"></i> Print</button>
        <button class="btn btn-flat btn-sm btn-default bg-black" data-dismiss="modal"><i class="fa fa-times"></i>
            Close</button>
    </div>

    <div style="margin-right: 10px;" id="print_out">

        <style>
        img#cimg {
            height: 100%;
            width: 100%;
            /* object-fit: scale-down; */
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

        .print-only {
            display: none !important;
        }

        .print-violations {
            width: 100%;
        }

        .print-column {
            width: 100%;
        }

        @media print {
            .print-only {
                display: block !important;
            }

            .print-violations {
                width: 50%;
            }

            .print-column {
                width: 50%;
            }
        }

        td.hakdog {

            padding: -7;
            /* border: 1px solid black; */
        }
        </style>
        <table class="">

            <tr class=''>
                <td width="100%" class=''>
                    <div class="row">
                        <div class="print-column">
                            <div class="d-flex px-1 w-max-100">
                                <label class="float-left w-auto whitespace-nowrap">DATE: </label>
                                <p class="col-md-auto w-100">
                                    <b><?php echo date("l, F j, Y"); ?></b>
                                </p>
                            </div>

                            <div class="d-flex">
                                <div class="d-flex flex-column mr-auto ">
                                    <div class="d-flex">
                                        <label class="float-left w-auto whitespace-nowrap px-1 skyblue">TOP NO: </label>
                                        <p class="col-md-auto w-100"><b><?php echo $ticket_no ?></b></p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column ml-4">
                                    <div class="d-flex">
                                        <label class="float-left w-auto whitespace-nowrap px-1 skyblue">
                                            APPREHENDED:
                                        </label>
                                        <p class="col-md-auto w-100">
                                            <b><?php echo date("n/j/y h:ia", strtotime($date_created)) ?></b>
                                        </p>
                                        <!-- <p class="col-md-auto w-auto">
                                            <?php
                                            if ($status == 0) {
                                                echo "<b>Pending</b>";
                                            } else {
                                                echo "<b>" . date("M d, Y h:i A", strtotime($date_created)) . "</b>";
                                            }
                                            ?>
                                        </p> -->
                                    </div>
                                </div>
                            </div>


                            <div class="d-flex px-1 w-max-100 custom-background">
                                <label class="float-left w-auto whitespace-nowrap">NAME: </label>
                                <p class="col-md-auto w-100"><b><?php echo $driver_name ?></b></p>
                            </div>

                            <label class="float-left w-auto whitespace-nowrap px-1 custom-background1">ADDRESS :</label>
                            <div class="d-flex w-max-100">
                                <p class="col-md-auto w-100 custom-background"><b><?php echo $address2 ?></b></p>
                            </div>

                            <div class="d-flex px-1">
                                <div class="d-flex flex-grow-1">
                                    <label class="w-auto whitespace-nowrap ">VEHICLE: </label>
                                    <p class="col-md-auto w-100 "><b><?php echo $type_of_vehicle ?></b></p>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <p class="w-100"><b><?php echo $ownership_classification ?></b></p>
                                </div>
                            </div>

                            <label class="float-left w-auto whitespace-nowrap px-1">OR No </label>
                            <div class="d-flex w-max-100">
                                <p class=""><b>: <?php echo $or_number ?></b></p>
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
        $ofrank = $conn->query("SELECT ol.id,o.offensename, ot.fine,
        ROW_NUMBER() OVER(PARTITION BY firstname, offensename ORDER BY date_created) AS offense_rank
        FROM offense_list1 ol
        INNER JOIN drivers_list dl ON ol.driver_id = dl.id
        INNER JOIN offense_items ot ON ol.id = ot.driver_offense_id 
        INNER JOIN offenses o ON ot.offense_id = o.id
        ORDER BY ol.date_created DESC");

        while ($ofrankrow = $ofrank->fetch_assoc()) {
            if ($id == $ofrankrow['id']) {

                $offense_arr[] = $ofrankrow;
            }
        }

        // Get details of the current offense
        $qry2 = $conn->query("SELECT i.*, o.code, o.offensename, i.fine FROM `offense_items` i INNER JOIN `offenses` o ON i.offense_id = o.id WHERE i.driver_offense_id = '{$_GET['k']}' ");
        if ($qry2->num_rows > 0) {
            while ($row = $qry2->fetch_assoc()) {
                // $offense_arr[] = $row;
            }
        }

        // Set default values for the fields
        $or_number = isset($or_number) ? $or_number : '';
        $discountAmount = isset($or_amount) ? $or_amount : 0;
        ?>
        <br>
        <table class='table print-violations'>
            <thead>
                <tr>
                    <th>VIOLATION/S</th>
                    <th style="text-align: right;">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($offense_arr) > 0): ?>
                <?php foreach ($offense_arr as $offense): ?>
                <tr>
                    <td class='hakdog' style="">
                        <?php echo htmlspecialchars($offense['offensename'], ENT_QUOTES, 'UTF-8'); ?>
                        (<?php echo ordinal($offense['offense_rank']) ?> offense)
                    </td>
                    <td class='text-right hakdog'>₱ <?php echo number_format($offense['fine']); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if ($status == 1): ?>
                <tr>
                    <!-- <td class='hakdog'>
                                <b>Subtotals</b>
                            </td> -->
                    <th>
                        Subtotals
                    </th>
                    <td class="text-right hakdog">
                        <b>₱ <?php echo number_format($total_amount); ?></b>
                    </td>
                </tr>
                <tr>

                    <!-- <td class='hakdog'>
                                <b>Discount</b>
                            </td> -->
                    <th>
                        Discount
                    </th>
                    <td class="text-right hakdog">
                        <b>₱ <?php echo number_format((float) $discountAmount); ?></b>
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
                    <td colspan="" class="text-right hakdog" style="color:red">
                        <b>
                            ₱<?php echo number_format($totalAmount); ?>
                        </b>

                    </td>
                </tr>
                <?php else: ?>
                <tr>
                    <th>Total</th>
                    <td colspan="3" class="text-right hakdog" style="color:red">
                        <b>
                            ₱
                            <?php $totalFine = 0;
                            foreach ($offense_arr as $offense) {
                                $totalFine += $offense['fine'];
                            }
                            echo number_format($totalFine);
                            ?>
                        </b>
                    </td>
                </tr>
                <?php endif; ?>
            </tfoot>
        </table>
        <div class="print-only w-50">
            <center>
                <table>
                    <tr>
                        <th>
                            <p style="margin-top: 30px; margin-right: 5px;">
                                ASSESSED BY:
                            </p>
                        </th>
                        <td class="text-left">
                            <p style="line-height: 2px; margin-top: 30px;">
                                ______________________________
                            </p>
                            <p class="fw-bolder">
                                <?php echo ucwords($_settings->userdata('firstname') . ' ' . $_settings->userdata('middleinitial') . ' ' . $_settings->userdata('lastname')) ?>
                            </p>
                            <p class="fs-8 fst-italic" style="line-height: 2px; font-size: 12px;">
                                <?php echo ucwords($_settings->userdata('position')) ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <p style="margin-top: 20px; margin-right: 5px;">
                                VALIDATED BY:
                            </p>
                        </th>
                        <td class="text-left">
                            <p style="line-height: 2px; margin-top: 20px;">
                                ______________________________
                            </p>
                            <p class="fw-bolder">
                                NOEL A. SOPONGCO
                            </p>
                            <p class="fs-8 fst-italic" style="line-height: 2px; font-size: 12px; margin-bottom: 20px;">
                                Licensing Officer III
                            </p>
                        </td>
                    </tr>
                </table>
            </center>
        </div>


    </div>

</div>
</center>
<script>
$(function() {
    $('#print').click(function() {
        // Log the activity using AJAX
        $.ajax({
            url: "classes/Master.php", // URL to your PHP script
            type: 'POST',
            data: {
                f: 'print_activity' // Parameter to identify the action in PHP
            },
            success: function(response) {
                console.log('Activity logged successfully.');
            },
            error: function(xhr, status, error) {
                console.error('Error logging activity:', error);
            }
        });

        // start_loader(); // Ensure this function is defined or remove it if not needed

        var _h = $('head').clone();
        var _p = $('#print_out').clone();
        var _el = $('<div>');

        // Add header content
        _p.prepend(`
            <div class="d-flex w-50 align-items-center justify-content-center">
                <div class="px-2">
                    <p class="text-center">REPUBLIC OF THE PHILIPPINES</p>
                    <p class="text-center">Province of Nueva Vizcaya</p>
                    <h5 class="text-center"><b>MUNICIPALITY OF SOLANO</b></h5>
                    <h5 class="text-center skyblue" style="width: 4.25in;"><b>ORDER OF PAYMENT</b></h5>
                </div>
            </div>
        `);

        // Append styles to enforce paper size and formatting
        _el.append(_h);
        _el.append(`
            <style>
                @media print {
                    @page {
                        size: 4.25in 8.25in; /* Set paper size to 4.25 x 8.25 inches */
                    }
                    html, body {
                        margin: 0 !important;
                        padding: 0 !important;
                        width: 4.25in !important;
                        height: 8.25in !important;
                        font-size: 12px;
                    }
                    #print_out {
                        display: flex;
                        flex-direction: column;
                        justify-content: flex-start;
                        align-items: stretch;
                        width: 100%;
                        height: 100%;
                        // box-sizing: border-box;
                        background-color: white;
                    }
                    .d-flex {
                        display: flex !important;
                    }
                    .text-center {
                        text-align: center !important;
                    }
                    .px-2 {
                        padding: 0.5rem !important;
                    }
                }
            </style>
        `);
        _el.append(_p);

        // Create a new window for printing to avoid disrupting the current page
        var printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.write(_el.html());
        printWindow.document.write('<script>window.print(); window.close();<\/script>');
        printWindow.document.close();
    });
});
</script>