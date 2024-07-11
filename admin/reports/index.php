<?php if ($_settings->chk_flashdata('success')): ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
    </script>
<?php endif; ?>
<?php
$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : date("Y-m-d");
$date_start = isset($_GET['date_start']) ? $_GET['date_start'] : date("Y-m-d", strtotime('-3 days'));

?>
<style>
    td {
        border-bottom: solid black 1px !important;
    }

    th {
        border-bottom: solid black 1px !important;
    }
</style>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 id="report" class="card-title">Reports</h3>
        <!-- <div class="card-tools">
            <a href="?page=offenses/manage_record" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
        </div> -->

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
                        <label for="fillVio" class="control-label">Violation Filter</label><br>
                        <select name="fillVio" id="fillVio" class="form-control select2">
                            <option value="All">All</option>
                            <?php
                            $qryFilter = $conn->query("SELECT DISTINCT offense_id, of.offensename AS offenseName
                                                                FROM offense_items ot
                                                                INNER JOIN `offenses` of ON ot.offense_id = of.id");
                            while ($row = $qryFilter->fetch_assoc()):
                                ?>
                                <option value="<?php echo $row["offense_id"]; ?>" <?php if (isset($_GET["fill"]) && $_GET["fill"] == $row["offense_id"]) {
                                       echo "selected";
                                   } ?>>
                                    <?php echo $row["offenseName"]; ?>

                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>




                </div>
                <div class="col-6 row align-items-end pb-1 justify-content-end">
                    <div class="w-100">
                        <div class="form-group d-flex justify-content-end">
                            <button class="btn btn-outline-info ml-1" type="button" id="filter"><i
                                    class="fa fa-filter"></i> Filter</button>
                            <button class="btn btn-outline-success ml-1" type="button" id="print"><i
                                    class="fa fa-print"></i> Print</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid" id="print_out">
                <table class="table table-hover table-striped tablesorter" id="myTable">
                    <colgroup>
                        <col width="1%"> <!-- # -->
                        <col width="5%"><!-- Date -->
                        <col width="3%"><!-- TOP -->
                        <col width="10%"><!-- Name -->
                        <col width="5%"><!-- Address -->
                        <col width="5%"><!-- Vehicle -->
                        <col width="5%"><!-- Classification -->
                        <col width="5%"><!-- Violations -->
                        <col width="5%"><!-- V Fee -->
                        <col width="5%"><!-- Discount -->
                        <col width="5%"><!-- Total -->
                        <col width="5%"><!-- OR -->
                        <col width="5%"><!-- Date Released -->
                        <col width="5%"><!-- T.E.-->
                        <col width="5%"><!-- Status -->
                        <col width="10%"><!-- Remarks -->
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>DATE</th>
                            <th>TOP#</th>
                            <th>NAME</th>
                            <th>ADDRESS</th>
                            <th>VEHICLE</th>
                            <th>CLASS</th>
                            <th>VIOLATION</th>
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

                        if ($_GET["fill"] == 'All') {
                            $qry = $conn->query("   SELECT 
                                            r.*,
                                            d.license_id_no, 
                                            d.name AS driver,
                                            ot.offense_id AS offenseNum,
                                            ot.fine AS offenseFine,
                                            of.offensename AS offenseName
                                            
                                        FROM 
                                            `offense_list1` r 
                                        INNER JOIN 
                                            `drivers_list` d ON r.driver_id = d.id
                                        
                                        INNER JOIN
                                            `offense_items` ot ON r.id = ot.driver_offense_id
                                        
                                        INNER JOIN
                                            `offenses` of ON ot.offense_id = of.id
                                       
                                        WHERE 
                                        DATE(r.date_created) BETWEEN '{$date_start}' AND '{$date_end}' 

                                         GROUP BY
                                        r.ticket_no

                                        ORDER BY 
                                            UNIX_TIMESTAMP(r.date_created) DESC
                ");
                        } else {

                            $a = $_GET["fill"];
                            $qry = $conn->query("   SELECT 
                    r.*,
                    d.license_id_no, 
                    d.name AS driver,
                    ot.offense_id AS offenseNum,
                    ot.fine AS offenseFine,
                    of.offensename AS offenseName
                    
                FROM 
                    `offense_list1` r 
                INNER JOIN 
                    `drivers_list` d ON r.driver_id = d.id
                
                INNER JOIN
                    `offense_items` ot ON r.id = ot.driver_offense_id
                
                INNER JOIN
                    `offenses` of ON ot.offense_id = of.id
               
                WHERE 
                ot.offense_id = '$a' AND DATE(r.date_created) BETWEEN '{$date_start}' AND '{$date_end}' 

                 GROUP BY
                r.ticket_no

                ORDER BY 
                    UNIX_TIMESTAMP(r.date_created) DESC
                ");
                        }

                        while ($row = $qry->fetch_assoc()):

                            $qry2 = $conn->query("  SELECT 
                                                i.*,o.code,o.offensename 
                                            FROM 
                                                `offense_items` i 
                                            INNER JOIN 
                                                `offenses` o on i.offense_id = o.id 
                                            WHERE 
                                                i.driver_offense_id = '{$row['id']}'
                    ");

                            $offense_arr = array();
                            if ($qry->num_rows > 0) {
                                while ($inner_row = $qry2->fetch_assoc()) { // Changed variable name to $inner_row
                                    $offense_arr[] = $inner_row;
                                }
                            }


                            ?>



                            <!-- Julu -->

                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td>
                                    <?php echo date("Y-m-d H:i A", strtotime($row['date_created'])) ?>
                                </td>
                                <td style="color: red;"><?php echo $row['ticket_no'] ?></td>
                                <td><?php echo $row['driver'] ?></td>
                                <td><?php echo $row['address2'] ?></td>
                                <td><?php echo $row['type_of_vehicle'] ?></td>
                                <td><?php echo $row['ownership_classification'] ?>
                                </td>
                                <td>
                                    <?php foreach ($offense_arr as $inner_row): ?>
                                        <?php echo $inner_row['offensename'] ?><br>
                                    <?php endforeach; ?>

                                </td>
                                <td>
                                    <?php foreach ($offense_arr as $inner_row): ?>
                                        ₱ <?php echo number_format((float) $inner_row['fine'], 2) ?><br>
                                    <?php endforeach; ?>
                                    <!-- ₱ <?php echo number_format((float) $row['offenseFine'], 2) ?> -->
                                </td>

                                <td>
                                    ₱ <?php echo number_format((float) $row['or_amount'], 2) ?>
                                </td>

                                <td>
                                    ₱ <?php
                                    // Calculate the total fine
                                    $totalFine = 0;
                                    foreach ($offense_arr as $inner_row) {
                                        $totalFine += (float) $inner_row['fine'];
                                    }

                                    // Calculate the difference between total fine and or_amount
                                    $orAmount = (float) $row['or_amount'];
                                    $difference = $totalFine - $orAmount;

                                    // Display the formatted difference
                                    echo number_format($difference, 2);
                                    ?>

                                </td>

                                <td><?php echo $row['or_number'] ?></td>
                                <td>
                                    <?php echo (new DateTime($row['date_released']))->format('M, d, Y. H:i A'); ?>
                                </td>
                                <td><?php echo $row['officer'] ?></td>
                                <td class="text-center">
                                    <?php if ($row['status'] == 1): ?>
                                        <span class="badge badge-success">Paid</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $row['remarks'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if ($qry->num_rows <= 0): ?>
                            <tr>
                                <th class="text-center" colspan='16'> No Records.</th>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap CSS -->
<!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->

<!-- tablesorter theme default CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.default.min.css"
    rel="stylesheet">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- tablesorter JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>


<script>

    $(document).ready(function () {


        $('#filter').click(function () {
            location.replace("./?page=reports&fill=" + $('#fillVio').val() + "&date_start=" + ($('#date_start').val()) + "&date_end=" + ($('#date_end').val()));
        });

        $('#fillVio').change(function () {
            location.replace("./?page=reports&fill=" + $('#fillVio').val() + "&date_start=" + $('#date_start').val() + "&date_end=" + $('#date_end').val());
        });

        // Initialize DataTables with custom column definitions
        // $('.table').dataTable({
        //     columnDefs: [{ orderable: false, targets: [15, 9, 8, 10] }]
    });
    $('#fillVio').select2({
        allowClear: true
    });
    $("#myTable").tablesorter({
        headers: {
            15: { sorter: false },
            9: { sorter: false },
            8: { sorter: false },
            10: { sorter: false }
        }
    });
    $('#print').click(function () {
        start_loader()
        var _h = $('head').clone()
        var _p = $('#print_out').clone();

        // Directly modify the cloned _p element
        _p.css({
            'font-size': '12px'  // Base font size for the cloned content
        });
        _p.find('h5').css({
            'font-size': '1.25em',
            'margin-bottom': '0'
        });
        _p.find('h6').css({
            'font-size': '1em',
            'margin-bottom': '0'
        });
        _p.find('.table').css({
            'font-size': '0.9em'
        });
        _p.find('.table th, .table td').css({
            'padding': '0.5em'
        });
        _p.find('hr').css({
            'margin': '0.5em 0'
        });

        var _el = $('<div>')
        _el.append(_h)
        _el.append('<style>' +
            'html, body, .wrapper { min-height: unset !important; }' +
            '@page { size: landscape; }' +
            'td { border-bottom: solid black 1px !important; }' +
            'th { border-bottom: solid black 1px !important; }' +
            '</style>')

        var rdate = "";
        if ('<?php echo $date_start ?>' == '<?php echo $date_end ?>')
            rdate = "<?php echo date("M d, Y", strtotime($date_start)) ?>";
        else
            rdate = "<?php echo date("M d, Y", strtotime($date_start)) ?> - <?php echo date("M d, Y", strtotime($date_end)) ?>";

        _p.prepend('<div class="d-flex mb-3 align-items-center">' +
            '<img class="" src="<?php echo validate_image($_settings->info('logo')) ?>" width="50px" height="50px"/>' +
            '<div class="px-2 flex-grow-1">' +
            '<h5 class="text-center"><?php echo $_settings->info('offensename') ?></h5>' +
            '<h6 class="text-center">Traffic Offense Reports</h6>' +
            '<h6 class="text-center">' + rdate + '</h6>' +
            '</div>' +
            '</div><hr/>'
        );

        _el.append(_p)
        var nw = window.open("", "_blank", "width=1200,height=1000")
        nw.document.write(_el.html())
        nw.document.close()
        setTimeout(() => {
            nw.print()
            setTimeout(() => {
                nw.close()
                end_loader()
            }, 300);
        }, 500);
    });


</script>