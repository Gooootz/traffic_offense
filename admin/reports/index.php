<?php if ($_settings->chk_flashdata('success')): ?>
<script>
alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success');
</script>
<?php endif; ?>

<?php
$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : date("Y-m-d");
$date_start = isset($_GET['date_start']) ? $_GET['date_start'] : date("Y-m-d", strtotime('-3 days'));
?>

<style>
td,
th {
    border-bottom: solid black 1px !important;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h1 id="report" class="card-title">Reports</h1>
    </div>
    <div class="card-body">
        <div class="">
            <div class="row">
                <div class="col-2">
                    <div class="form-group">
                        <label for="date_start" class="control-label">Date Start</label>
                        <input type="date" class="form-control" id="date_start"
                            value="<?php echo date("Y-m-d", strtotime($date_start)); ?>">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="date_end" class="control-label">Date End</label>
                        <input type="date" class="form-control" id="date_end"
                            value="<?php echo date("Y-m-d", strtotime($date_end)); ?>">
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-group">
                        <label for="fillVio" class="control-label">Filter by Offense</label>
                        <select name="fillVio" id="fillVio" class="form-control select2">
                            <option value="All"
                                <?php if (!isset($_GET["fill"]) || $_GET["fill"] == 'All') echo "selected"; ?>>All
                            </option>
                            <?php
                            $qryFilter = $conn->query("SELECT DISTINCT offense_id, of.offensename AS offenseName FROM offense_items ot INNER JOIN `offenses` of ON ot.offense_id = of.id");
                            while ($row = $qryFilter->fetch_assoc()):
                                ?>
                            <option value="<?php echo $row["offense_id"]; ?>"
                                <?php if (isset($_GET["fill"]) && $_GET["fill"] == $row["offense_id"]) echo "selected"; ?>>
                                <?php echo $row["offenseName"]; ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="statusFilter" class="control-label">Filter by Status</label>
                        <select name="statusFilter" id="statusFilter" class="form-control select2">
                            <option value="All"
                                <?php if (!isset($_GET["statusFilter"]) || $_GET["statusFilter"] == 'All') echo "selected"; ?>>
                                All</option>
                            <option value="Paid"
                                <?php if (isset($_GET["statusFilter"]) && $_GET["statusFilter"] == 'Paid') echo "selected"; ?>>
                                Paid</option>
                            <option value="Pending"
                                <?php if (isset($_GET["statusFilter"]) && $_GET["statusFilter"] == 'Pending') echo "selected"; ?>>
                                Pending</option>
                        </select>
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-group">
                        <label for="confiscatedFilter" class="control-label">Filter by Confiscated Items</label>
                        <select name="confiscatedFilter" id="confiscatedFilter" class="form-control select2">
                            <option value="All"
                                <?php if (!isset($_GET["confiscatedFilter"]) || $_GET["confiscatedFilter"] === 'All') echo "selected"; ?>>
                                All</option>
                            <?php
                            // Query to get distinct confiscated items from the database
                            $sql = "SELECT DISTINCT confiscated_items FROM offense_list1 ORDER BY confiscated_items ASC";
                            $result = mysqli_query($conn, $sql);

                            // Check if the query ran successfully
                            if (!$result) {
                                echo "<option value=''>Error fetching data</option>";
                            } else {
                                // If there are results, loop through them and create options
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $item = $row['confiscated_items'];
                                        // Ensure the item is sanitized for display
                                        $sanitizedItem = htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
                                        // Check if the item matches the selected filter
                                        $selected = (isset($_GET["confiscatedFilter"]) && $_GET["confiscatedFilter"] === $sanitizedItem) ? "selected" : "";
                                        echo "<option value=\"$sanitizedItem\" $selected>$sanitizedItem</option>";
                                    }
                                } else {
                                    echo "<option value='None' disabled>No confiscated items found</option>";
                                }
                            }
                            ?>
                        </select>


                    </div>
                </div>

                <div class="col-2 row align-items-end pb-1 justify-content-end">
                    <div class="w-100">
                        <div class="form-group d-flex justify-content-end">
                            <button class="btn btn-outline-info ml-1" type="button" id="filter"><i
                                    class="fa fa-filter"></i> Filter</button>
                            <button class="btn btn-outline-success ml-1" type="button" id="print"><i
                                    class="fa fa-print"></i> Print</button>
                            <button class="btn btn-outline-success ml-1" type="button" onclick="exportToExcel()"><i
                                    class="fa fa-file-excel"></i> Export</button>
                        </div>
                    </div>
                </div>



            </div>

            <div class="container-fluid" id="print_out">
                <table class="table table-hover table-striped tablesorter" id="myTable">
                    <colgroup>
                        <col width="1%">
                        <col width="15%">
                        <col width="5%">
                        <col width="10%">
                        <col width="5%">
                        <col width="5%">
                        <col width="5%">
                        <col width="10%">
                        <col width="10%">
                        <col width="7%">
                        <col width="7%">
                        <col width="7%">
                        <col width="2%">
                        <col width="5%">
                        <col width="2%">
                        <col width="5%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>DATE</th>
                            <th>TOP#</th>
                            <th>NAME</th>
                            <th>PLACE</th>
                            <th>VEHICLE</th>
                            <th>CLASS</th>
                            <th>VIOLATION</th>
                            <th>CONFISCATED ITEMS</th>
                            <th>FEE</th>
                            <th>DISCOUNT</th>
                            <th>TOTAL</th>
                            <th>OR#</th>
                            <th>DATE RELEASED</th>
                            <th>ENFORCER</th>
                            <th>STATUS</th>
                            <th>REMARKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $filter = isset($_GET["fill"]) ? $_GET["fill"] : 'All';
                        $statusFilter = isset($_GET["statusFilter"]) ? $_GET["statusFilter"] : 'All';
                        $confiscatedFilter = isset($_GET["confiscatedFilter"]) ? $_GET["confiscatedFilter"] : 'All';

                        // Prepare the SQL query
                        $sql = "SELECT
                            ol.id,
                            ol.date_created,
                            ol.ticket_no,
                            dl.firstname,
                            dl.middlename,
                            dl.lastname,
                            dl.suffix,
                            ol.address2 AS place,
                            ol.type_of_vehicle AS vehicle,
                            ol.confiscated_items AS confiscated,
                            ol.ownership_classification AS class,
                            o.offensename,
                            ol.total_amount,
                            ol.or_amount AS discount,
                            ol.totalAmount AS total,
                            ol.or_number,
                            ol.date_released_or,
                            CONCAT(ofc.firstname, ' ', IFNULL(ofc.middlename, ''), ' ', ofc.lastname, ' ', IFNULL(ofc.suffix, '')) AS officername,
                            ol.`status`,
                            ol.remarks
                        FROM
                            offense_list1 ol
                        INNER JOIN
                            drivers_list dl ON ol.driver_id = dl.id
                        INNER JOIN
                            offense_items ot ON ol.id = ot.driver_offense_id
                        INNER JOIN
                            offenses o ON ot.offense_id = o.id
                        INNER JOIN
                            officers_list ofc ON ol.officer = ofc.officer_id_no
                        WHERE 
                            DATE(ol.date_created) BETWEEN ? AND ?";



                        // Filter by offense
                        if ($filter != 'All') {
                            $sql .= " AND ot.offense_id = ?";
                        }

                        // Filter by status
                        if ($statusFilter == 'Paid') {
                            $sql .= " AND ol.`status` = 1";
                        } elseif ($statusFilter == 'Pending') {
                            $sql .= " AND ol.`status` = 0";
                        }

                        // Filter by confiscated items
                        if ($confiscatedFilter != 'All') {
                            $sql .= " AND ol.confiscated_items LIKE ?";
                        }

                        $sql .= " GROUP BY ol.ticket_no ORDER BY ol.date_created DESC";

                        // Prepare the statement
                        $stmt = $conn->prepare($sql);

                        // Bind parameters
                        if ($filter != 'All' && $confiscatedFilter != 'All') {
                            $stmt->bind_param("ssss", $date_start, $date_end, $filter, $confiscatedFilter);
                        } elseif ($filter != 'All') {
                            $stmt->bind_param("sss", $date_start, $date_end, $filter);
                        } elseif ($confiscatedFilter != 'All') {
                            $stmt->bind_param("sss", $date_start, $date_end, $confiscatedFilter);
                        } else {
                            $stmt->bind_param("ss", $date_start, $date_end);
                        }

                        // Execute the statement
                        $stmt->execute();

                        // Get the result
                        $qry = $stmt->get_result();

                        while ($row = $qry->fetch_assoc()):
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo date("M, d, Y. h:i A", strtotime($row['date_created'])); ?></td>
                            <td style="color: red;"><?php echo $row['ticket_no']; ?></td>
                            <td><?php echo $row['firstname'].' '.$row['middlename'].' '.$row['lastname'].' '.$row['suffix']; ?>
                            </td>
                            <td><?php echo $row['place']; ?></td>
                            <td><?php echo $row['vehicle']; ?></td>
                            <td><?php echo $row['class']; ?></td>
                            <td>
                                <?php
                                $ofrank = $conn->query("SELECT 
                                                            ol.id, 
                                                            o.offensename, 
                                                            ROW_NUMBER() OVER(PARTITION BY firstname, offensename ORDER BY date_created) AS offense_rank
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
                                            echo "1st offense)</strong>";
                                        } elseif ($ofrankrow['offense_rank'] == 2) {
                                            echo "2nd offense)</strong>";
                                        } else if ($ofrankrow['offense_rank'] <= 3) {
                                            echo "3rd offense)</strong>";
                                        }
                                        echo "<br>";
                                    }
                                }
                                ?>
                            </td>
                            <td><?php echo $row['confiscated']; ?></td>
                            <td>₱<?php echo number_format($row['total_amount'], 2); ?></td>
                            <td>₱<?php echo number_format($row['discount'], 2); ?></td>
                            <td>₱<?php 
                                // Set total to 0 if status is Pending
                                $total = $row['status'] == 1 ? ($row['discount'] == 0 ? $row['total_amount'] : $row['total']) : 0;
                                echo number_format($total, 2); 
                            ?></td>
                            <td style="color: green;"><?php echo $row['or_number']; ?></td>
                            <td> <?php 
                                // Check if the record is pending or the date_released_or is empty/invalid
                                if ($row['status'] == 0 || empty($row['date_released_or']) || $row['date_released_or'] == '0000-00-00') {
                                    echo "N/A";
                                } else {
                                    echo date("M, d, Y", strtotime($row['date_released_or']));
                                }
                                ?>
                            </td>
                            <td><?php echo $row['officername']; ?></td>
                            <td>
                                <?php if ($row['status'] == 1): ?>
                                <span class="bg-gradient-success px-3 rounded-pill">Paid</span>
                                <?php else: ?>
                                <span class="bg-gradient-warning px-3 rounded-pill">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['remarks']; ?></td>
                        </tr>

                        <?php endwhile; ?>
                        <?php if ($qry->num_rows <= 0): ?>
                        <tr>
                            <th class="text-center" colspan='17'> No Records.</th>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Please Select Here',
        width: '100%'
    });

    function applyFilters() {
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        var fillVio = $('#fillVio').val();
        var statusFilter = $('#statusFilter').val();
        var confiscatedFilter = $('#confiscatedFilter').val();

        location.href = "./?page=reports&date_start=" + date_start + "&date_end=" + date_end + "&fill=" +
            fillVio + "&statusFilter=" + statusFilter + "&confiscatedFilter=" + confiscatedFilter;
    }

    // Automatically refresh table when filter options change
    $('#fillVio, #statusFilter, #confiscatedFilter').on('change', function() {
        applyFilters();
    });

    $('#filter').click(function() {
        applyFilters();
    });

    $('#print').click(function() {
        start_loader(); // Show loading indicator

        // Log the activity using AJAX
        $.ajax({
            url: _base_url_ + "classes/Master.php", // URL to your PHP script
            type: 'POST',
            data: {
                f: 'printreport_activity' // Parameter to identify the action in PHP
            },
            success: function(response) {
                console.log('Activity logged successfully.');

                // Proceed with the printing logic after logging activity
                printContent();
            },
            error: function(xhr, status, error) {
                console.error('Error logging activity:', error);
                $('#msg').html(
                    '<div class="alert alert-danger">An error occurred while logging the activity.</div>'
                );
                end_loader(); // Hide loading indicator
            }
        });

        function printContent() {
            var width_mm = 297; // A4 width in mm (landscape)
            var height_mm = 210; // A4 height in mm (landscape)
            var pixelWidth = width_mm * 96 / 25.4;
            var pixelHeight = height_mm * 96 / 25.4;
            var left = (window.screen.width / 2) - (pixelWidth / 2);
            var top = (window.screen.height / 2) - (pixelHeight / 2);

            var nw = window.open(
                '',
                '_blank',
                `width=${Math.round(pixelWidth)},height=${Math.round(pixelHeight)},top=${Math.round(top)},left=${Math.round(left)}`
            );

            // Clone the print content
            var _p = $('#print_out').clone();

            // Create a container for the print content
            var _el = $('<div>');

            // Add custom header
            var header = `
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h2>LGU Solano</h2>
                        <h3>Traffic Violation Report</h3>
                        <p><strong>Date Range:</strong> ${$('#date_start').val()} to ${$('#date_end').val()}</p>
                    </div>
                `;
            _el.append(header);

            // Append the cloned table content
            _el.append(_p);

            // Add custom footer
            var footer = `
                    <div style="text-align: center; margin-top: 20px;">
                        <p>Generated by the Traffic Violation Management System</p>
                        <p>© 2024 LGU Solano. All rights reserved.</p>
                    </div>
                `;
            _el.append(footer);

            // Copy the CSS styles to the new window
            var styles = '';
            for (var i = 0; i < document.styleSheets.length; i++) {
                try {
                    if (document.styleSheets[i].href) {
                        styles += '<link rel="stylesheet" type="text/css" href="' + document
                            .styleSheets[i].href + '">';
                    } else {
                        var cssRules = document.styleSheets[i].cssRules || document.styleSheets[i]
                            .rules;
                        if (cssRules) {
                            styles += '<style>';
                            for (var j = 0; j < cssRules.length; j++) {
                                styles += cssRules[j].cssText;
                            }
                            styles += '</style>';
                        }
                    }
                } catch (e) {
                    console.warn("Error accessing CSS rules: ", e);
                }
            }

            // Add landscape orientation CSS and scaling
            styles += `
                    <style>
                        @page {
                            size: landscape;
                            margin: 10mm;
                        }
                        body {
                            font-size: 10pt;
                            -webkit-print-color-adjust: exact;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                        }
                        table, th, td {
                            border: 1px solid black;
                            font-size: 8pt;
                        }
                        th, td {
                            padding: 4px;
                            text-align: left;
                            word-wrap: break-word;
                        }
                        #print_out {
                            zoom: 90%; /* Scale the content to fit */
                        }
                    </style>
                `;

            // Write the content and styles to the new window and print
            nw.document.open();
            nw.document.write('<html><head>' + styles + '</head><body>' + _el.html() +
                '</body></html>');
            nw.document.close();
            nw.focus();

            // Print and close the window
            nw.print();
            setTimeout(function() {
                nw.close();
                end_loader(); // Hide loading indicator
            }, 500);
        }
    });
});

function exportToExcel() {

    // Log the activity using AJAX
    $.ajax({
        url: _base_url_ + "classes/Master.php", // URL to your PHP script
        type: 'POST',
        data: {
            f: 'export_activity' // Parameter to identify the action in PHP
        },
        success: function(response) {
            console.log('Activity logged successfully.');
        },
        error: function(xhr, status, error) {
            console.error('Error logging activity:', error);
        }
    });

    // Create a new workbook
    var wb = XLSX.utils.book_new();
    wb.Props = {
        Title: "Traffic Violation Report",
        Subject: "Report",
        Author: "LGU Solano",
        CreatedDate: new Date()
    };

    // Get table data
    var ws_data = [];
    var table = document.getElementById('myTable');
    var headers = [];
    var colWidths = [];

    // Extract headers
    table.querySelectorAll('thead th').forEach(function(th, index) {
        headers.push(th.innerText);
        colWidths[index] = th.innerText.length; // Initialize column widths based on header length
    });
    ws_data.push(headers);

    // Extract rows
    table.querySelectorAll('tbody tr').forEach(function(tr) {
        var row = [];
        tr.querySelectorAll('td').forEach(function(td, index) {
            var cellText = td.innerText;
            row.push(cellText);
            if (!colWidths[index] || cellText.length > colWidths[index]) {
                colWidths[index] = cellText.length; // Update column width if necessary
            }
        });
        ws_data.push(row);
    });

    // Create worksheet from table data
    var ws = XLSX.utils.aoa_to_sheet(ws_data);

    // Set column widths based on the longest text in each column
    ws['!cols'] = colWidths.map(width => ({
        width: width + 2
    })); // Add 2 for padding

    XLSX.utils.book_append_sheet(wb, ws, "Report");

    // Export the workbook
    XLSX.writeFile(wb, 'TRVMS_Report.xlsx');
}
</script>