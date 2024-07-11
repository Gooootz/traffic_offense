<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * FROM `offense_list1` WHERE id = '{$_GET['id']}' ");
    if ($qry && $qry->num_rows > 0) {
        $row = $qry->fetch_assoc();
        foreach ($row as $k => $v) {
            $$k = stripslashes($v);
        }
    }
}
?>

<?php
function get_vehicle_options($conn, $selected_vehicle = '')
{
    $options = '';
    $vehicle_query = $conn->query("SELECT * FROM `vehicles` WHERE `status` = 1 ORDER BY `name` ASC");
    while ($row = $vehicle_query->fetch_assoc()) {
        $selected = ($selected_vehicle == $row['name']) ? 'selected' : '';
        $options .= '<option value="' . $row['name'] . '" ' . $selected . '>' . ucwords($row['name']) . '</option>';
    }
    return $options;
}
?>

<?php
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
    .uploaded_img {
        width: 150px;
        height: 135px;
        object-fit: scale-down;
        object-position: center center;
    }

    .img-panel {
        width: 170px;
    }

    .nav-link.clicked {
        background-color: primary;
        /* Set your primary color here */
    }

    .div1 {
        float: right;
    }

    .div2 {
        clear: right;
    }
</style>
<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($id) ? "Update " : "Create New " ?> Offense Record</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <input type="checkbox" name="driver-classification" id="new-driver-checkbox">
            <label for="driver-classification">Add New Driver</label>
        </div>

        <!-- Adding new driver form -->
        <form action="driver-form" id="driver-form">

            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <div class="row">
                <div class="col-6">
                    <div class="form-group" id="new-driver-fields" style="display: none; ">
                        <label for="lastname" class="control-label">Last Name</label>
                        <input type="text" class="form-control form" required name="lastname"
                            value="<?php echo isset($lastname) ? $lastname : '' ?>" placeholder="(e.g. Delacruz)">
                    </div>
                    <div class="form-group" id="new-driver-fields" style="display: none; ">
                        <label for="firstname" class="control-label">First Name</label>
                        <input type="text" class="form-control form" required name="firstname"
                            value="<?php echo isset($firstname) ? $firstname : '' ?>" placeholder="(e.g. Juan)">
                    </div>
                    <div class="form-group" id="new-driver-fields" style="display: none; ">
                        <label for="middlename" class="control-label">Middle Name</label>
                        <input type="text" class="form-control form" required name="middlename"
                            value="<?php echo isset($middlename) ? $middlename : '' ?>" placeholder="(e.g. Santos)">
                    </div>
                    <div class="form-group" id="new-driver-fields" style="display: none; ">
                        <label for="address" class="control-label">Address</label>
                        <input type="text" class="form-control form" required name="address"
                            value="<?php echo isset($address) ? $address : '' ?>" placeholder="(e.g. Solano)">
                    </div>
                    <div class="div1 form-group float-right " id="new-driver-fields" style="display: none; ">
                        <button class="btn btn-flat btn-primary" form="driver-form" id="save-driver-btn">Save
                            Driver</button>
                    </div>
                    <div class="div2"></div>
                </div>
                <div class="col-6">
                    <div class="form-group" id="new-driver-fields" style="display: none; ">
                        <label for="license_type" class="control-label">Gender</label>
                        <select name="gender" id="gender" class="custom-select select2" required>
                            <option value=""></option>
                            <option value="Male" <?php echo (isset($gender) && $gender == 'Male') ? 'selected' : '' ?>>
                                Male</option>
                            <option value="Female" <?php echo (isset($gender) && $gender == 'Female') ? 'selected' : '' ?>>
                                Female</option>
                        </select>
                    </div>
                    <div class="form-group" id="new-driver-fields" style="display: none;">
                        <label for="license_id_no" class="control-label">License No.</label>
                        <br>
                        <input type="text" maxlength="50" class="form-control form" id="license_id_no"
                            name="license_id_no" value="<?php echo isset($license_id_no) ? $license_id_no : '' ?>"
                            placeholder="(optional)">
                        <!-- <label>No License</label>
                        <input type="checkbox" name="license_type" id="no_license" value="No-License"> -->
                    </div>
                    <div class="form-group" id="new-driver-fields" style="display: none;">
                        <label for="license_type" class="control-label">License Type</label>
                        <select name="license_type" id="license_type" class="custom-select select2" required>
                            <option value=""></option>
                            <option value="No-license" <?php echo (isset($license_type) && $license_type == 'No-license') ? 'selected' : '' ?>>No License</option>
                            <option value="Student" <?php echo (isset($license_type) && $license_type == 'Student') ? 'selected' : '' ?>>Student</option>
                            <option value="Non-Professional" <?php echo (isset($license_type) && $license_type == 'Non-Professional') ? 'selected' : '' ?>>Non-Professional</option>
                            <option value="Professional" <?php echo (isset($license_type) && $license_type == 'Professional') ? 'selected' : '' ?>>Professional</option>
                        </select>
                    </div>
                </div>
            </div>


        </form>

        <form action="" id="offense-form">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <div class="row">
                <div class="col-6">




                    <div class="form-group">
                        <label class="control-label" for="driver_id"><strong>Driver's Name</strong></label>

                        <select name="driver_id" id="driver_id" class="custom-select select2" required>
                            <option value="" disabled selected>Please select a driver</option>
                            <!-- Added option for prompting user -->
                            <?php
                            $driver = $conn->query("SELECT * FROM `drivers_list` ORDER BY `name` ASC");
                            while ($row = $driver->fetch_assoc()):
                                ?>
                                <option value="<?php echo $row['id'] ?>" <?php echo (isset($driver_id) && $driver_id == $row['id']) ? 'selected' : '' ?>>
                                    [<?php echo $row['license_id_no'] ?>] <?php echo ucwords($row['name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label class="control-label" for="address1"><strong>Place</strong></label>

                        <select name="address1" id="barangay-select" class="custom-select select2" required>
                            <option <?php echo (isset($address1) && $address1 == 'Solano') ? 'selected' : '' ?>>Solano
                            </option>
                            <option <?php echo (isset($address1) && $address1 == 'Aggub') ? 'selected' : '' ?>>Aggub
                            </option>
                            <option <?php echo (isset($address1) && $address1 == 'Bagahabag') ? 'selected' : '' ?>>
                                Bagahabag</option>
                            <option <?php echo (isset($address1) && $address1 == 'Bangaan') ? 'selected' : '' ?>>Bangaan
                            </option>
                            <option <?php echo (isset($address1) && $address1 == 'Bangar') ? 'selected' : '' ?>>Bangar
                            </option>
                            <option <?php echo (isset($address1) && $address1 == 'Bascaran') ? 'selected' : '' ?>>Bascaran
                            </option>
                            <option <?php echo (isset($address1) && $address1 == 'Communal') ? 'selected' : '' ?>>Communal
                            </option>
                            <option <?php echo (isset($address1) && $address1 == 'Concepcion') ? 'selected' : '' ?>>
                                Concepcion (Calalabangan)</option>
                            <option <?php echo (isset($address1) && $address1 == 'Curifang') ? 'selected' : '' ?>>Curifang
                                (Sinafal)</option>
                            <option <?php echo (isset($address1) && $address1 == 'Dadap') ? 'selected' : '' ?>>Dadap
                            </option>
                            <option <?php echo (isset($address1) && $address1 == 'Lactawan') ? 'selected' : '' ?>>Lactawan
                            </option>
                            <option <?php echo (isset($address1) && $address1 == 'Osmeña') ? 'selected' : '' ?>>Osmeña
                                (Urban)</option>
                            <option <?php echo (isset($address1) && $address1 == 'Pilar D. Galima') ? 'selected' : '' ?>>
                                Pilar D. Galima</option>
                            <option <?php echo (isset($address1) && $address1 == 'Poblacion North') ? 'selected' : '' ?>>
                                Poblacion North (Urban)</option>
                            <option <?php echo (isset($address1) && $address1 == 'Poblacion South') ? 'selected' : '' ?>>
                                Poblacion South (Urban)</option>
                            <option <?php echo (isset($address1) && $address1 == 'Quezon') ? 'selected' : '' ?>>Quezon
                                (Urban)</option>
                            <option <?php echo (isset($address1) && $address1 == 'Quirino') ? 'selected' : '' ?>>Quirino
                                (Urban)</option>
                            <option <?php echo (isset($address1) && $address1 == 'Roxas') ? 'selected' : '' ?>>Roxas
                                (Urban)</option>
                            <option <?php echo (isset($address1) && $address1 == 'San Juan') ? 'selected' : '' ?>>San Juan
                            </option>
                            <option <?php echo (isset($address1) && $address1 == 'San Luis') ? 'selected' : '' ?>>San Luis
                            </option>
                            <option <?php echo (isset($address1) && $address1 == 'Tucal') ? 'selected' : '' ?>>Tucal
                            </option>
                            <option <?php echo (isset($address1) && $address1 == 'Uddiawan') ? 'selected' : '' ?>>Uddiawan
                            </option>
                            <option <?php echo (isset($address1) && $address1 == 'Wacal') ? 'selected' : '' ?>>Wacal
                            </option>
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <label class="control-label" for="permit_no" required><strong>Permit No.</strong></label>
                        <input type="text" class="form-control" name="permit_no" id="permit_no"
                            value="<?php echo isset($permit_no) ? $permit_no : '' ?>" oninput="allowOnlyNumbers(this)"
                            placeholder="(optional)">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="mvplate_no" required><strong>MVPlate No.</strong></label>
                        <input type="text" class="form-control" name="mvplate_no" id="mvplate_no"
                            value="<?php echo isset($mvplate_no) ? $mvplate_no : '' ?>" placeholder="(optional)">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="name_of_owner" required><strong>Name of Owner</strong></label>
                        <input type="text" class="form-control" name="name_of_owner" id="name_of_owner"
                            value="<?php echo isset($name_of_owner) ? $name_of_owner : '' ?>" required
                            placeholder="(e.g. Juan Delacruz)">
                    </div>
                    <div>
                        <label class="control-label" for="address2" required><strong>Address</strong></label>
                        <input type="text" class="form-control" name="address2" id="address2"
                            value="<?php echo isset($address2) ? $address2 : '' ?>" required
                            placeholder="(e.g. Solano)">
                    </div>
                    <br>
                    <div class="form-group">
                        <label class="control-label" for="officer_name"><strong>Apprehending Officer</strong></label>

                        <select name="officer" id="officer" class="custom-select select2" required>
                            <option value=""></option>
                            <?php
                            $oofficer = $conn->query("SELECT * FROM `officers_list` ORDER BY `officer_name` ASC");
                            while ($row = $oofficer->fetch_assoc()):
                                ?>
                                <option value="<?php echo $row['officer_name'] ?>" <?php echo (isset($officer) && $officer == $row['officer_name']) ? 'selected' : '' ?>>

                                    [<?php echo $row['officer_id_no'] ?>] <?php echo ucwords($row['officer_name']) ?>
                                </option>
                                <?php
                            endwhile;
                            ?>
                        </select>


                    </div>

                </div>
                <br>
                <div class="col-6">

                    <div class="form-group">
                        <label class="control-label" for="ticket_no"><strong>Ticket No.</strong></label>
                        <input type="text" class="form-control" name="ticket_no" id="ticket_no"
                            value="<?php echo isset($ticket_no) ? $ticket_no : '' ?>" required
                            placeholder="(e.g. 000000)">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="body_no"><strong>Permit/Body No.</strong></label>
                        <input type="text" class="form-control" name="body_no" id="body_no"
                            value="<?php echo isset($body_no) ? $body_no : '' ?>" oninput="allowOnlyNumbers(this)"
                            placeholder="(For Hire Sidecar Number)">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="status"><strong>Status</strong></label>
                        <?php if ($_settings->userdata('type') == 1): ?>
                            <select name="status" id="status" class="custom-select" <?php echo ($_settings->userdata('type') == 2) ? 'disabled' : '' ?>required>
                                <option value="0" <?php echo (isset($status) && $status == '0') ? 'selected' : '' ?>>Pending
                                </option>
                                <option value="1" <?php echo (isset($status) && $status == '1') ? 'selected' : '' ?>>Paid
                                </option>
                            </select>
                        <?php else: ?>
                            <select name="status" id="status" class="custom-select" <?php echo ($_settings->userdata('type') == 2) ? 'disabled' : '' ?>required disabled>
                                <option value="0" <?php echo (isset($status) && $status == '0') ? 'selected' : '' ?>>Pending
                                </option>
                                <option value="1" <?php echo (isset($status) && $status == '1') ? 'selected' : '' ?>>Paid
                                </option>
                            </select>
                        <?php endif; ?>

                    </div>
                    <div class="form-group">
                        <label class="control-label" for="date_created"><strong>Date and Time</strong></label>
                        <input type="datetime-local" class="form-control" name="date_created" id="date_created"
                            value="<?php echo isset($date_created) ? date("Y-m-d\\TH:i", strtotime($date_created)) : date("Y-m-d\\TH:i") ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="make"><strong>Make</strong></label>
                        <input type="text" class="form-control" name="make" id="make"
                            value="<?php echo isset($make) ? $make : '' ?>" required placeholder="(e.g. Honda)">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="type_of_vehicle"><strong>Type of Vehicle</strong></label>

                        <select name="type_of_vehicle" id="type_of_vehicle" class="custom-select select2" required>
                            <option value=""></option>
                            <?php echo get_vehicle_options($conn, $type_of_vehicle); ?>
                        </select>
                    </div>
                    <br>
                    <!-- Ownership Classification -->
                    <div class="form-group">
                        <label for="for_hire"><b>Ownership Classification</b></label>
                        <br>
                        <label>
                            For Hire:
                            <input type="radio" name="ownership_classification" id="for_hire" value="FOR HIRE" <?php echo (isset($ownership_classification) && $ownership_classification == 'FOR HIRE') ? 'checked' : '' ?> required>
                        </label>
                        <br>
                        <label>
                            Private:
                            <input type="radio" name="ownership_classification" id="private" value="PRIVATE" <?php echo (isset($ownership_classification) && $ownership_classification == 'PRIVATE') ? 'checked' : '' ?> required>
                        </label>
                        <div class="invalid-feedback">Please select one option.</div>
                        <!-- Message for validation prompt -->
                    </div>


                </div>

            </div>
            <hr>

            <div class="row">
                <div class="col-6">
                    <h5 class='border-bottom border-light'><b>Offense List</b></h5>
                    <div class="row">
                        <div class="col-auto float-left">
                            <div class="form-group">
                                <label class="control-label" for="offense_id">Offense</label>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                                <select id="offense_id" class="custom-select select2">
                                    <option value=""></option>
                                    <?php
                                    $driver = $conn->query("SELECT * FROM `offenses` WHERE `status` = 1 ORDER BY `offensename` ASC");
                                    while ($row = $driver->fetch_assoc()):
                                        ?>
                                        <option value="<?php echo $row['id'] ?>" data-fine="<?php echo $row['fine'] ?>"
                                            data-code="<?php echo $row['code'] ?>"
                                            data-name="<?php echo $row['offensename'] ?>">
                                            [<?php echo $row['code'] ?>] <?php echo ucwords($row['offensename']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <button type="button" class="btn btn-outline-primary" id="add_to_list"><i
                                        class="fa fa-plus"></i> Add to List</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-stripped table-hover" id="fine-list">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Offense</th>
                                <th>Fine</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($id)):
                                // $olist = $conn->query("SELECT i.*,o.code,o.name FROM `offense_items` i inner join `offenses` o on i.offense_id = o.id where i.='{$id}' ");
                                $olist = $conn->query("SELECT i.*,o.code,o.offensename FROM `offense_items` i inner join `offenses` o on i.offense_id = o.id where i.driver_offense_id ='{$id}' ");

                                while ($row = $olist->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td><?php echo $row['code'] ?>
                                            <input type="hidden" name="offense_id[]" value="<?php echo $row['offense_id'] ?>">
                                            <input type="hidden" name="fine[]" value="<?php echo $row['fine'] ?>">
                                        </td>
                                        <td><?php echo $row['offensename'] ?></td>
                                        <td class="fine text-right"><?php echo number_format($row['fine'], 2) ?></td>
                                        <td>
                                            <button class="btn  btn-sm btn-default text-danger" type="button"
                                                onclick="rem_item($(this))"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                            <?php if (!isset($id) || (isset($olist) && $olist->num_rows <= 0)): ?>
                                <tr id='td-none'>
                                    <th colspan="4" class="text-center">No Offense Listed Yet.</th>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-center">Total</th>
                                <th colspan="2" class="text-right" id="total_amount">
                                    <?php echo isset($total_amount) ? number_format($total_amount, 2) : '0.00' ?>
                                </th>
                                <th><input type="hidden" name="total_amount"
                                        value="<?php echo isset($total_amount) ? $total_amount : 0 ?>"></th>
                            </tr>


                        </tfoot>
                    </table>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="remarks" class="control-label">Remarks</label>
                        <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="3"
                            style="resize:none !important"><?php echo isset($remarks) ? $remarks : '' ?></textarea>
                    </div>
                </div>
            </div>
        </form>


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

        <form method="POST" action="" id="discount-form">
            <div class="row">
                <div class="col">
                    <table class="col-6">
                        <tr style="<?php echo $isUpdateMode ? '' : 'display: none;'; ?>">
                            <th class='text-center' colspan="2">OR Number</th>
                            <th class="text-right">
                                <input type="text" name="or_number" id="orNumber" class="form-control"
                                    value="<?php echo htmlspecialchars($or_number); ?>" oninput="allowOnlyNumbers(this)"
                                    required>
                            </th>
                        </tr>
                        <tr style="<?php echo $isUpdateMode ? '' : 'display: none;'; ?>">
                            <th class='text-center' colspan="2">Discount</th>
                            <th class="text-right">
                                <input type="text" name="discountAmount" id="discountAmount"
                                    class="form-control text-right" value="<?php echo htmlspecialchars($or_amount); ?>"
                                    oninput="allowOnlyNumbers(this)">
                            </th>
                        </tr>
                        <tr style="<?php echo $isUpdateMode ? '' : 'display: none;'; ?>">
                            <th class='text-center' colspan="2">Total Amount</th>
                            <th>
                                <input type="text" name="total_amount1" id="total_amount1"
                                    class="form-control text-right"
                                    value="<?php echo isset($or_amount) && $or_amount == 0 ? $total_amount : $totalAmount; ?>"
                                    placeholder="click calculate" disabled>
                            </th>
                            <td class="pl-1">
                                <button type="button" class="btn btn-outline-primary" id="saveOrButton">Save OR</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </form>


    </div>
    <div class="card-footer">
        <button class="btn btn-outline-primary" form="offense-form" id="save-btn">Save</button>
        <a type="button" class="btn btn-outline-secondary" href="?page=offenses">Cancel</a>
    </div>
</div>
<script>
    function allowOnlyNumbers(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
    }
    document.getElementById('driver_id').addEventListener('change', function () {
        if (this.value === '') {
            alert('Please select a driver');
        }
    });
    document.getElementById('saveOrButton').addEventListener('click', function (event) {
        var confirmSave = confirm('Are you sure you want to save the OR?');
        if (confirmSave) {
            // Submit the form
            var form = document.getElementById('discount-form');
            form.submit();
        }
    });
    function allowOnlyNumbers(input) {
        // Function to allow only numbers in the input field
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    // JavaScript to show/hide the driver form fields and button based on checkbox state
    document.getElementById('new-driver-checkbox').addEventListener('change', function () {
        var newDriverFields = document.querySelectorAll('#new-driver-fields');
        var saveDriverBtn = document.getElementById('save-driver-btn');
        if (this.checked) {
            Array.from(newDriverFields).forEach(function (field) {
                field.style.display = 'block';
            });
            saveDriverBtn.style.display = 'block';
        } else {
            Array.from(newDriverFields).forEach(function (field) {
                field.style.display = 'none';
            });
            saveDriverBtn.style.display = 'none';
        }
    });

    function checkDriverSelection() {
        var selectedValue = document.getElementById('driver_id').value;
        if (selectedValue === '') {
            alert('Please select a driver');
        }
    }
    // Function to allow only numbers in the textboxes
    function allowOnlyNumbers(element) {
        element.value = element.value.replace(/[^0-9]/g, '');
    }

    function rem_item(_this) {
        _this.closest('tr').remove()
        calculate_total();
    }
    function calculate_total() {
        var total = 0;
        $('#fine-list input[name="fine[]"]').each(function () {
            var fine = $(this).val()
            total += parseFloat(fine)
        })
        $('#total_amount').text(parseFloat(total).toLocaleString('en-US'))
        $('input[name="total_amount"]').val(parseFloat(total))
    }



    $(document).ready(function () {
        $('#offense_id').select2({
            placeholder: "Select an offense",
            allowClear: true
        });
        // Click event listener for updating the status of the offense record
        $('.paid_data').click(function () {
            _conf("Are you sure that this offense record is Paid?", "paid_offense", [$(this).data('id')]);
        });

        // Function to handle checkbox change event
        $('#no_license').change(function () {
            if ($(this).prop('checked')) {
                $('input[name="license_id_no"]').prop('readonly', true).val('');
                $('#license_type').prop('disabled', true).prop('readonly', true).val('No-license').trigger('change'); // Disable, make readonly, and set license type dropdown to "No License"
            } else {
                $('input[name="license_id_no"]').prop('readonly', false);
                $('#license_type').prop('disabled', false).prop('readonly', false).val('').trigger('change'); // Enable and clear license type dropdown selection
            }
        });


        // Function to handle license type change event
        $('#license_type').change(function () {
            if ($(this).val() === '---') {
                alert('Please select a license type.'); // Alert the user to select a license type
                $(this).val('Non-Professional').trigger('change');
            }

            if ($(this).val() === 'No-license') {
                // Clear the license ID number textbox
                $('input[name="license_id_no"]').val('');
            }
        });
        // Initialize Select2
        $('.select2').select2({
            placeholder: "Please Select Driver here",
            width: "relative"
        })

        $('#barangay-select').select2({
            placeholder: 'Please Select Barangay here',
            width: "relative",
            allowClear: true
        });

        $('#type_of_vehicle').select2({
            placeholder: 'Select Vehicle Type',
            width: "relative",
            allowClear: true
        });
        $('#license_type').select2({
            placeholder: 'Select License Type',
            width: "relative",
            allowClear: true
        });
        $('#gender').select2({
            placeholder: 'Select Gender',
            width: "relative",
            allowClear: true
        });
        // Click event listener for viewing offense details
        $('.view_details').click(function () {
            uni_modal("<i class='fa fa-ticket'></i> Driver's Offense Ticket Details", "offenses/view_details.php?id=" + $(this).attr('data-id'), 'mid-large');
        });
        $('#select-box').select2();


        $('#add_to_list').click(function () {
            var selectedOffense = $('#offense_id').val();
            if (!selectedOffense) {
                alert('Please select an offense before adding it to the list.');
                return;
            }

            var offense_id = $('#offense_id').val();
            // Check if the selected offense is already listed
            var exists = false;
            $('#fine-list input[name="offense_id[]"]').each(function () {
                if ($(this).val() == offense_id) {
                    exists = true;
                    return false; // Exit the loop if a duplicate offense is found
                }
            });
            if (exists) {
                alert("This offense is already listed.");
                return; // Exit the function if a duplicate offense is found
            }

            var fine = $('#offense_id option[value="' + offense_id + '"]').attr('data-fine');
            var offense = $('#offense_id option[value="' + offense_id + '"]').attr('data-name');
            var code = $('#offense_id option[value="' + offense_id + '"]').attr('data-code');
            var tr = $("<tr>");
            tr.append('<td>' + code + '<input type="hidden" name="offense_id[]" value="' + offense_id + '"><input type="hidden" name="fine[]" value="' + fine + '"></td>');
            tr.append('<td>' + offense + '</td>');
            tr.append('<td class="text-right">' + (parseFloat(fine).toLocaleString('en-US')) + '</td>');
            tr.append('<td><button class="btn  btn-sm btn-default text-danger" type="button" onclick="rem_item($(this))"><i class="fa fa-times"></i></button></td>');
            $('#fine-list tbody').append(tr);
            if ($('#td-none').length > 0)
                $('#td-none').remove();
            calculate_total();
            $('#offense_id').val('').trigger('change');
        });

        $('#offense-form').submit(function (e) {
            e.preventDefault();
            var _this = $(this);
            $('.err-msg').remove();
            start_loader();
            if ($('[name="offense_id[]"]').length <= 0) {
                alert_toast('Please add at least 1 offense item first', 'warning');
                end_loader();
                return false;
            }
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_offense_record",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: function (err) {
                    console.log(err);
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function (resp) {
                    if (typeof resp === 'object' && resp.status === 'success') {
                        // Display success message from flash data
                        alert_toast(resp.message, 'success');

                        // Hide loader and redirect after a short delay
                        setTimeout(function () {
                            end_loader();
                            location.href = "./?page=offenses";
                        }, 500);
                    } else if (resp.status === 'failed' && !!resp.msg) {
                        // Display error message from response
                        var el = $('<div>').addClass("alert alert-danger err-msg").text(resp.msg);
                        _this.prepend(el);
                        el.show('slow');
                        $("html, body").animate({ scrollTop: _this.closest('.card').offset().top }, "fast");
                        end_loader();
                    } else {
                        // Handle other errors
                        alert_toast("An error occurred", 'error');
                        end_loader();
                        console.log(resp);
                    }
                }
            });
        });

        $('#driver-form').submit(function (e) {
            e.preventDefault();
            var _this = $(this);
            $('.err-msg').remove();
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_driver",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                dataType: 'json',
                error: function (err) {
                    console.log(err);
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function (resp) {
                    if (typeof resp === 'object' && resp.status === 'success') {
                        // Hide new driver checkbox and related fields
                        $('#new-driver-checkbox').prop('checked', false).change(); // Uncheck the checkbox and trigger change event
                        $('#license_id_no, #license_type, input[name="lastname"], #save-driver-btn').closest('.form-group').hide(); // Hide related fields
                        alert_toast('Driver saved successfully', 'success');
                        alert('You can now Select the Driver.');
                        location.href = "./?page=offenses/manage_record";
                    } else if (resp.status === 'failed' && !!resp.msg) {
                        var el = $('<div>').addClass("alert alert-danger err-msg").text(resp.msg);
                        _this.prepend(el);
                        el.show('slow');
                        $("html, body").animate({
                            scrollTop: _this.closest('.card').offset().top
                        }, "fast");
                        end_loader();
                    } else {
                        alert_toast("An error occurred", 'error');
                        end_loader();
                        console.log(resp);
                    }
                }

            });
        });

    })

</script>