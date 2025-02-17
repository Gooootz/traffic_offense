<?php
// Check if the 'id' parameter is set and valid
if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
    $offense_id = intval($_GET['id']); // Sanitize the input as an integer

    // Query the database for the specified offense ID
    $qry = $conn->query("SELECT *, `date_created` AS Datetime FROM `offense_list1` WHERE id = '{$offense_id}'");

    if ($qry && $qry->num_rows > 0) {
        $row = $qry->fetch_assoc();

        // Dynamically create variables for each column in the result
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
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
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
<!-- <.?php
    $secretIDphp = 1;
    ?>

<script>
    function sendSelection() {
        var selectElement = document.getElementById('driver_id');
        var selectedValue = selectElement.value;
        var d = <.?php $secretIDphp; ?>
        document.getElementById('secretID').value = selectedValue;
        if( d = 1){
            alert('Driver Selected');
        }
    }
</script> -->




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
                        <input type="text" class="form-control form" name="middlename"
                            value="<?php echo isset($middlename) ? $middlename : '' ?>" placeholder="(e.g. Santos)">
                    </div>
                    <div class="form-group" id="new-driver-fields" style="display: none; ">
                        <label for="suffix" class="control-label">Suffix</label>
                        <input type="text" class="form-control form" name="suffix"
                            value="<?php echo isset($suffix) ? $suffix : '' ?>" placeholder="(e.g. Jr.)">
                    </div>

                    <div class="div1 form-group float-right " id="new-driver-fields" style="display: none; ">
                        <button class="btn btn-flat btn-primary" form="driver-form" id="save-driver-btn">Save
                            Driver</button>
                    </div>
                    <div class="div2"></div>
                </div>
                <div class="col-6">
                    <div class="form-group" id="new-driver-fields" style="display: none; ">
                        <label for="address" class="control-label">Address</label>
                        <input type="text" class="form-control form" required name="address"
                            value="<?php echo isset($address) ? $address : '' ?>" placeholder="(e.g. Solano)">
                    </div>
                    <div class="form-group" id="new-driver-fields" style="display: none; ">
                        <label for="license_type" class="control-label">Gender</label>
                        <select name="gender" id="gender" class="custom-select select2" required>
                            <option value=""></option>
                            <option value="Male" <?php echo (isset($gender) && $gender == 'Male') ? 'selected' : '' ?>>
                                Male</option>
                            <option value="Female"
                                <?php echo (isset($gender) && $gender == 'Female') ? 'selected' : '' ?>>
                                Female</option>
                        </select>
                    </div>
                    <div class="form-group" id="new-driver-fields" style="display: none;">
                        <label for="license_id_no" class="control-label">License No.</label>
                        <br>
                        <input type="text" maxlength="50" class="form-control form" id="license_id_no"
                            name="license_id_no" value="<?php echo isset($license_id_no) ? $license_id_no : '' ?>"
                            placeholder="(optional)">
                        <!-- <label>Unlicensed</label>
                        <input type="checkbox" name="license_type" id="no_license" value="Unlicensed"> -->
                    </div>
                    <div class="form-group" id="new-driver-fields" style="display: none;">
                        <label for="license_type" class="control-label">License Type</label>
                        <select name="license_type" id="license_type" class="custom-select select2" required>
                            <option value=""></option>
                            <option value="Unlicensed"
                                <?php echo (isset($license_type) && $license_type == 'Unlicensed') ? 'selected' : '' ?>>
                                Unlicensed</option>
                            <option value="Student"
                                <?php echo (isset($license_type) && $license_type == 'Student') ? 'selected' : '' ?>>
                                Student</option>
                            <option value="Non-Professional"
                                <?php echo (isset($license_type) && $license_type == 'Non-Professional') ? 'selected' : '' ?>>
                                Non-Professional</option>
                            <option value="Professional"
                                <?php echo (isset($license_type) && $license_type == 'Professional') ? 'selected' : '' ?>>
                                Professional</option>
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
                        <label class="control-label" for="ticket_no"><strong>Ticket No.</strong></label>
                        <input type="text" class="form-control" name="ticket_no" id="ticket_no"
                            value="<?php echo isset($ticket_no) ? $ticket_no : '' ?>" required
                            placeholder="(e.g. 000000)">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="driver_id"><strong>Driver's Name</strong></label>
                        <select name="driver_id" id="driver_id" class="custom-select select2" required>
                            <option value="" disabled selected>Please select a driver</option>
                            <?php
                    $driver = $conn->query("SELECT * FROM `drivers_list` ORDER BY `id` ASC");
                    while ($row = $driver->fetch_assoc()):
                        $selected = (isset($driver_id) && $driver_id == $row['id']) ? 'selected' : ''; // Check if this driver is the selected one
                    ?>
                            <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
                                <?php echo !empty($row['license_id_no']) ? $row['license_id_no'] . ' - ' : ''; ?>
                                <?php echo ucwords($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname'] . ' ' . $row['suffix']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
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
                            <option <?php echo (isset($address1) && $address1 == 'Bascaran') ? 'selected' : '' ?>>
                                Bascaran</option>
                            <option <?php echo (isset($address1) && $address1 == 'Communal') ? 'selected' : '' ?>>
                                Communal</option>
                            <option <?php echo (isset($address1) && $address1 == 'Concepcion') ? 'selected' : '' ?>>
                                Concepcion (Calalabangan)</option>
                            <option <?php echo (isset($address1) && $address1 == 'Curifang') ? 'selected' : '' ?>>
                                Curifang (Sinafal)</option>
                            <option <?php echo (isset($address1) && $address1 == 'Dadap') ? 'selected' : '' ?>>Dadap
                            </option>
                            <option <?php echo (isset($address1) && $address1 == 'Lactawan') ? 'selected' : '' ?>>
                                Lactawan</option>
                            <option <?php echo (isset($address1) && $address1 == 'Osmeña') ? 'selected' : '' ?>>Osmeña
                                (Urban)</option>
                            <option
                                <?php echo (isset($address1) && $address1 == 'Pilar D. Galima') ? 'selected' : '' ?>>
                                Pilar D. Galima</option>
                            <option
                                <?php echo (isset($address1) && $address1 == 'Poblacion North') ? 'selected' : '' ?>>
                                Poblacion North (Urban)</option>
                            <option
                                <?php echo (isset($address1) && $address1 == 'Poblacion South') ? 'selected' : '' ?>>
                                Poblacion South (Urban)</option>
                            <option <?php echo (isset($address1) && $address1 == 'Quezon') ? 'selected' : '' ?>>Quezon
                                (Urban)</option>
                            <option <?php echo (isset($address1) && $address1 == 'Quirino') ? 'selected' : '' ?>>Quirino
                                (Urban)</option>
                            <option <?php echo (isset($address1) && $address1 == 'Roxas') ? 'selected' : '' ?>>Roxas
                                (Urban)</option>
                            <option <?php echo (isset($address1) && $address1 == 'San Juan') ? 'selected' : '' ?>>San
                                Juan</option>
                            <option <?php echo (isset($address1) && $address1 == 'San Luis') ? 'selected' : '' ?>>San
                                Luis</option>
                            <option <?php echo (isset($address1) && $address1 == 'Tucal') ? 'selected' : '' ?>>Tucal
                            </option>
                            <option <?php echo (isset($address1) && $address1 == 'Uddiawan') ? 'selected' : '' ?>>
                                Uddiawan</option>
                            <option <?php echo (isset($address1) && $address1 == 'Wacal') ? 'selected' : '' ?>>Wacal
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="permit_no"><strong>Engine/Chassis No.</strong></label>
                        <input type="text" class="form-control" name="permit_no" id="permit_no"
                            value="<?php echo isset($permit_no) ? $permit_no : '' ?>" placeholder="(optional)">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="body_no"><strong>Permit/Body No.</strong></label>
                        <input type="text" class="form-control" name="body_no" id="body_no"
                            value="<?php echo isset($body_no) ? $body_no : '' ?>"
                            placeholder="(For Hire Sidecar Number)">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="mvplate_no"><strong>MVPlate No.</strong></label>
                        <input type="text" class="form-control" name="mvplate_no" id="mvplate_no"
                            value="<?php echo isset($mvplate_no) ? $mvplate_no : '' ?>" placeholder="(optional)">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="officer"><strong>Apprehending Officer</strong></label>
                        <select name="officer" id="officer" class="custom-select select2" required>
                            <option value="" disabled selected>Please select an officer</option>
                            <?php
                    $oofficer = $conn->query("SELECT * FROM `officers_list` WHERE `status` = 1 ORDER BY `id` ASC");
                    while ($row = $oofficer->fetch_assoc()):
                        $selected = (isset($officer) && $officer == $row['officer_id_no']) ? 'selected' : ''; // Check if this officer is the selected one
                    ?>
                            <option value="<?php echo $row['officer_id_no']; ?>" <?php echo $selected; ?>>
                                [<?php echo $row['officer_id_no']; ?>]
                                <?php echo ucwords($row['lastname'] . ' ' . $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['suffix']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label class="control-label" for="name_of_owner"><strong>Name of Owner</strong></label>
                        <input type="text" class="form-control" name="name_of_owner" id="name_of_owner"
                            value="<?php echo isset($name_of_owner) ? $name_of_owner : '' ?>" required
                            placeholder="(e.g. Juan Delacruz)">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="address2"><strong>Address</strong></label>
                        <input type="text" class="form-control" name="address2" id="address2"
                            value="<?php echo isset($address2) ? $address2 : '' ?>" required
                            placeholder="(e.g. Solano)">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="status"><strong>Status</strong></label>
                        <?php if ($_settings->userdata('type') == 1): ?>
                        <select name="status" id="status" class="custom-select" required>
                            <option value="0" <?php echo (isset($status) && $status == '0') ? 'selected' : '' ?>>Pending
                            </option>
                            <option value="1" <?php echo (isset($status) && $status == '1') ? 'selected' : '' ?>>Paid
                            </option>
                        </select>
                        <?php else: ?>
                        <select name="status" id="status" class="custom-select" required disabled>
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
                            value="<?php echo isset($Datetime) ? $Datetime : '' ?>" required>
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

                    <div class="form-group" id="other_input_group">
                        <label class="control-label" for="confiscated_items_input"><strong>Confiscated
                                Item/s</strong></label>
                        <input type="text" class="form-control" name="confiscated_items" id="confiscated_items_input"
                            placeholder="Specify the item"
                            value="<?php echo isset($confiscated_items) ? htmlspecialchars($confiscated_items) : ''; ?>" />
                    </div>

                    <?php
            if (isset($id)) {
                $query = "SELECT ownership_classification FROM `offense_list1` WHERE id = {$id}";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $ownership_classification = $row['ownership_classification'];
                } else {
                    $ownership_classification = ''; // Default value if no record found
                }
            } else {
                $ownership_classification = ''; // Default value if $id is not set
            }
            ?>
                    <div class="form-group">
                        <label for="ownership_classification"><b>Ownership Classification</b></label>
                        <label class="m-3">
                            <input type="checkbox" name="ownership_classification[]" id="for_hire" value="FOR HIRE"
                                <?php echo (isset($ownership_classification) && $ownership_classification == 'FOR HIRE') ? 'checked' : '' ?>>
                            For Hire
                        </label>
                        <label class="m-3">
                            <input type="checkbox" name="ownership_classification[]" id="private" value="PRIVATE"
                                <?php echo (isset($ownership_classification) && $ownership_classification == 'PRIVATE') ? 'checked' : '' ?>>
                            Private
                        </label>
                        <div class="invalid-feedback">Please select one option.</div>
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
                                <th style="text-align: right;">Fine</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                    if (isset($id)):
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
                        <?php
                $isUpdateMode = isset($_GET['id']) && !empty($_GET['id']); // Set to true if 'id' is present
                ?>
                        <tfoot style="<?php echo $isUpdateMode ? 'display: none;' : ''; ?>">
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
                        <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="9"
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
            $total_amount = isset($_POST['total_amount']) ? (float) $_POST['total_amount'] : 0;
            $final_amount = isset($_POST['total_amount1']) ? (float) $_POST['total_amount1'] : $total_amount;
            $or_amount = isset($_POST['or_amount']) ? (float)$_POST['or_amount'] : 0; // Automatically calculate OR Amount
            $action = isset($_POST['action']) ? $_POST['action'] : 'save'; // Get the action from the hidden input field

            // Validate or_number
            if (empty($or_number)) {
                echo "<script>alert('OR number cannot be empty.');
                window.location.href = '?page=offenses';    
                </script>";
                
            } elseif (!is_numeric($or_number)) {
                echo "<script>alert('OR number must be a number.');</script>";
            } else {
                $or_number = mysqli_real_escape_string($conn, $or_number); // Sanitize input

                // Check for duplicate OR number
                $checkDuplicateSql = "SELECT COUNT(*) AS count FROM `offense_list1` WHERE `or_number` = '{$or_number}'";
                if ($isUpdateMode) {
                    $checkDuplicateSql .= " AND `id` != '{$id}'";
                }

                $result = mysqli_query($conn, $checkDuplicateSql);
                $row = mysqli_fetch_assoc($result);

                if ($row['count'] > 0) {
                    echo "<script>
                            alert('Duplicate OR number found.');
                            window.location.href = './?page=offenses';
                        </script>";
                    exit;
                }

                // Current datetime
                $currentDatetime = date('Y-m-d H:i:s');

                // Insert or update the record
                if ($isUpdateMode) {
                    $updateSql = "UPDATE `offense_list1` 
                        SET `or_number` = '{$or_number}', 
                            `or_amount` = '{$or_amount}', 
                            `totalAmount` = '{$final_amount}', 
                            `status` = " . ($action === 'paid' ? 1 : 0) . ", 
                            `date_released_or` = '{$currentDatetime}'
                        WHERE `id` = '{$id}'";
                    mysqli_query($conn, $updateSql) or die("Error updating record: " . mysqli_error($conn));
                } else {
                    $insertSql = "INSERT INTO `offense_list1` (`or_number`, `or_amount`, `totalAmount`, `status`, `date_created_or`) 
                        VALUES ('{$or_number}', '{$or_amount}', '{$final_amount}', " . ($action === 'paid' ? 1 : 0) . ", '{$currentDatetime}')";
                    mysqli_query($conn, $insertSql) or die("Error creating record: " . mysqli_error($conn));
                }

                // Log the activity
                $user = isset($_SESSION['userdata']) ? $_SESSION['userdata'] : array('firstname' => 'Unknown', 'lastname' => 'User', 'username' => 'Unknown');
                $user_type_label = $user['type'] == 1 ? 'Admin' : 'Staff';
                $description = $isUpdateMode ? 'Updated Apprehension Record' : 'Added Discount';
                $log_sql = "INSERT INTO `activity_log` (`date_created`, `user`, `action`, `description`) 
                            VALUES (NOW(), '{$user['firstname']} {$user['lastname']} ({$user_type_label})', 'Add Offense Record', '{$description}')";
                mysqli_query($conn, $log_sql) or die("Error logging activity: " . mysqli_error($conn));

                // Redirect
                echo "<script>window.location.href='" . base_url . "admin/?page=offenses';</script>";
                exit;
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
                            <th class="text-center" colspan="2">OR Number</th>
                            <th class="text-right">
                                <input type="text" name="or_number" id="orNumber" class="form-control"
                                    value="<?php echo htmlspecialchars($or_number); ?>" oninput="allowOnlyNumbers(this)"
                                    required>
                            </th>
                        </tr>
                        <tr style="<?php echo $isUpdateMode ? '' : 'display: none;'; ?>">
                            <th class="text-center" colspan="2">Original Amount</th>
                            <th>
                                <input type="text" name="total_amount" id="originalAmount"
                                    class="form-control text-right"
                                    value="<?php echo isset($total_amount) ? $total_amount : 0; ?>" disabled>
                            </th>
                        </tr>
                        <tr style="<?php echo $isUpdateMode ? '' : 'display: none;'; ?>">
                            <th class="text-center" colspan="2" id="discountHeader">Discount Amount</th>
                            <th>
                                <input type="text" id="orAmountDisplay" class="form-control text-right" value="0.00"
                                    disabled>
                                <input type="hidden" id="orAmountHidden" name="or_amount" value="0.00">

                            </th>
                        </tr>
                        <tr style="<?php echo $isUpdateMode ? '' : 'display: none;'; ?>">
                            <th class="text-center" colspan="2">Amount Paid</th>
                            <th>
                                <input type="number" name="total_amount1" id="finalAmount"
                                    class="form-control text-right"
                                    value="<?php echo isset($totalAmount) ? $totalAmount : ''; ?>" required
                                    oninput="calculateORAmount()">




                            </th>
                        </tr>

                        <tr style="<?php echo $isUpdateMode ? '' : 'display: none;'; ?>">
                            <td colspan="12" class="pl-1 text-sm-end">
                                <input type="hidden" name="action" id="actionInput" value="save">
                                <button type="submit" class="btn btn-outline-primary" id="saveOrButton"
                                    style="<?php echo $isUpdateMode ? '' : 'display: none;'; ?>">Paid</button>
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
    <!-- The Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Form Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body-content">
                    <!-- Form data details will be displayed here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    // document.getElementById('date_created').addEventListener('change', function() {
    //     const selectedDateTime = this.value;
    //     console.log('Selected Date and Time:', selectedDateTime);
    //     // You can add additional logic here, such as validation or updating other fields
    // });

    function calculateORAmount() {
        const originalAmount = parseFloat(document.getElementById('originalAmount').value) || 0;
        const finalAmount = parseFloat(document.getElementById('finalAmount').value) || 0;

        // Calculate the OR amount
        const orAmount = originalAmount - finalAmount;
        document.getElementById('orAmountDisplay').value = orAmount.toFixed(2);
        document.getElementById('orAmountHidden').value = orAmount.toFixed(2);

        // Calculate the discount percentage
        let discountPercentage = 0;
        if (originalAmount > 0) {
            discountPercentage = ((orAmount / originalAmount) * 100).toFixed(2);
        }

        // Update the discount header text
        const discountHeader = document.getElementById('discountHeader');
        discountHeader.textContent = `Discount Amount (${discountPercentage}%)`;
    }


    // Run the calculation on page load to initialize the percentage display
    document.addEventListener('DOMContentLoaded', calculateORAmount);

    document.addEventListener('DOMContentLoaded', function() {
        const selectElement = document.getElementById('confiscated_items_select');
        const otherInputGroup = document.getElementById('other_input_group');
        const otherInput = document.getElementById('confiscated_items_input');

        if (selectElement && otherInputGroup && otherInput) {
            selectElement.addEventListener('change', function() {
                if (this.value === 'Others') {
                    otherInputGroup.style.display = 'block';
                    otherInput.required = true;
                } else {
                    otherInputGroup.style.display = 'none';
                    otherInput.required = false;
                    otherInput.value = '';
                }
            });
        }
    });



    // JavaScript to ensure only one checkbox is checked at a time
    const checkboxes = document.querySelectorAll('input[name="ownership_classification[]"]');
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('click', function() {
            checkboxes.forEach((cb) => {
                if (cb !== this) {
                    cb.checked = false;
                }
            });
        });
    });

    function allowOnlyNumbers(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
    }
    document.addEventListener('change', function(event) {
        if (event.target && event.target.id === 'driver_id') {
            if (event.target.value === '') {
                alert('Please select a driver');
            }
        }
    });


    document.getElementById('saveOrButton').addEventListener('click', function(event) {
        var confirmSave = confirm('Are you sure you want to mark the OR as Paid?');
        if (confirmSave) {
            document.getElementById('actionInput').value = 'paid';
            var form = document.getElementById('discount-form');
            form.submit();
        }
    });


    function allowOnlyNumbers(input) {
        // Function to allow only numbers in the input field
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    // JavaScript to show/hide the driver form fields and button based on checkbox state
    document.getElementById('new-driver-checkbox').addEventListener('change', function() {
        var newDriverFields = document.querySelectorAll('#new-driver-fields');
        var saveDriverBtn = document.getElementById('save-driver-btn');
        if (this.checked) {
            Array.from(newDriverFields).forEach(function(field) {
                field.style.display = 'block';
            });
            saveDriverBtn.style.display = 'block';
        } else {
            Array.from(newDriverFields).forEach(function(field) {
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
        $('#fine-list input[name="fine[]"]').each(function() {
            var fine = $(this).val()
            total += parseFloat(fine)
        })
        $('#total_amount').text(parseFloat(total).toLocaleString('en-US'))
        $('input[name="total_amount"]').val(parseFloat(total))
    }

    // Wait for the document to be ready
    document.addEventListener('DOMContentLoaded', function() {
        // Get the total amount from the PHP variable output into the value of the input field
        const totalAmountInput = document.getElementById('amount');
        const discountAmountInput = document.getElementById('discountAmount');
        const discountPercentageElement = document.getElementById(
            'discount_percentage'); // Select the p tag for percentage

        // Convert the value of the input field to a number (it will be a string initially)
        const total_amount = parseFloat(totalAmountInput.value) || 0;

        // console.log("Total Amount from PHP: ", total_amount); // Log total_amount for debugging

        // Function to calculate the discount percentage
        function calculateDiscountPercentage() {
            const discountAmount = parseFloat(discountAmountInput.value) || 0;

            // Log the discount amount and total amount for debugging
            // console.log("Discount Amount:", discountAmount);
            // console.log("Total Amount:", total_amount);  // Use total_amount from the input field or PHP value

            if (total_amount > 0) {
                const discountPercentage = (discountAmount / total_amount) * 100;
                discountPercentageElement.textContent = discountPercentage.toFixed(2) + '%'; // Update the text
            }
        }

        // Attach event listeners to recalculate on input change
        discountAmountInput.addEventListener('input', calculateDiscountPercentage);
        totalAmountInput.addEventListener('input', calculateDiscountPercentage);

        // Initial calculation if values are already set
        calculateDiscountPercentage();
    });


    $(document).ready(function() {


        // Handle the change event for the dropdown
        $('#confiscated_items_select').change(function() {
            const selectedValue = $(this).val(); // Get the selected value

            if (selectedValue === 'Others') {
                // Show the "Specify Other Item" input field
                $('#other_input_group').show();
                $('#confiscated_items_input').prop('required', true); // Make the input required
            } else {
                // Hide the "Specify Other Item" input field
                $('#other_input_group').hide();
                $('#confiscated_items_input').prop('required', false).val(''); // Clear the input value
            }
        });

        // Trigger the change event on page load to handle pre-selected values
        $('#confiscated_items_select').trigger('change');


        $('#offense_id').select2({
            placeholder: "Select an offense",
            allowClear: true
        });
        // Click event listener for updating the status of the offense record
        $('.paid_data').click(function() {
            _conf("Are you sure that this offense record is Paid?", "paid_offense", [$(this).data(
                'id')]);
        });

        // Function to handle checkbox change event
        $('#no_license').change(function() {
            if ($(this).prop('checked')) {
                $('input[name="license_id_no"]').prop('readonly', true).val('');
                $('#license_type').prop('disabled', true).prop('readonly', true).val('Unlicensed')
                    .trigger(
                        'change'
                    ); // Disable, make readonly, and set license type dropdown to "Unlicensed"
            } else {
                $('input[name="license_id_no"]').prop('readonly', false);
                $('#license_type').prop('disabled', false).prop('readonly', false).val('').trigger(
                    'change'); // Enable and clear license type dropdown selection
            }
        });


        // Function to handle license type change event
        $('#license_type').change(function() {
            if ($(this).val() === 'Unlicensed') {
                // Clear and disable license number field
                $('#license_id_no').val('').prop('disabled', true);
            } else {
                // Enable the license number field if another option is selected
                $('#license_id_no').prop('disabled', false);
            }
        });

        // Trigger change event on page load to ensure proper state is set
        $('#license_type').trigger('change');
        // Initialize Select2
        $('.select2').select2({
            placeholder: "Please Select Driver here",
            width: "relative",

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
        $('#confiscated_items').select2({
            placeholder: 'Select Confiscated Items',
            width: "relative",
            allowClear: true
        });
        // Click event listener for viewing offense details
        $('.view_details').click(function() {
            uni_modal("<i class='fa fa-ticket'></i> Driver's Offense Ticket Details",
                "offenses/view_details.php?id=" + $(this).attr('data-id'), 'mid-large');
        });
        $('#select-box').select2();


        $('#add_to_list').click(function() {
            var selectedOffense = $('#offense_id').val();
            if (!selectedOffense) {
                alert('Please select an offense before adding it to the list.');
                return;
            }

            var offense_id = $('#offense_id').val();
            // Check if the selected offense is already listed
            var exists = false;
            $('#fine-list input[name="offense_id[]"]').each(function() {
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
            tr.append('<td>' + code + '<input type="hidden" name="offense_id[]" value="' + offense_id +
                '"><input type="hidden" name="fine[]" value="' + fine + '"></td>');
            tr.append('<td>' + offense + '</td>');
            tr.append('<td class="text-right">' + (parseFloat(fine).toLocaleString('en-US')) + '</td>');
            tr.append(
                '<td><button class="btn  btn-sm btn-default text-danger" type="button" onclick="rem_item($(this))"><i class="fa fa-times"></i></button></td>'
            );
            $('#fine-list tbody').append(tr);
            if ($('#td-none').length > 0)
                $('#td-none').remove();
            calculate_total();
            $('#offense_id').val('').trigger('change');
        });
        //tama ba to
        $('#offense-form').submit(function(e) {
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
                error: function(err) {
                    console.log(err);
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function(resp) {
                    if (typeof resp === 'object' && resp.status === 'success') {
                        // Display success message from flash data
                        alert_toast(resp.message, 'success');

                        // Hide loader and redirect after a short delay
                        setTimeout(function() {
                            end_loader();
                            location.href = "./?page=offenses";
                        }, 500);
                    } else if (resp.status === 'failed' && !!resp.msg) {
                        // Display error message from response
                        var el = $('<div>').addClass("alert alert-danger err-msg").text(resp
                            .msg);
                        _this.prepend(el);
                        el.show('slow');
                        $("html, body").animate({
                            scrollTop: _this.closest('.card').offset().top
                        }, "fast");
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

        $('#driver-form').submit(function(e) {
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
                error: function(err) {
                    console.log(err);
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function(resp) {
                    if (typeof resp === 'object' && resp.status === 'success') {
                        // Hide new driver checkbox and related fields
                        $('#new-driver-checkbox').prop('checked', false)
                            .change(); // Uncheck the checkbox and trigger change event
                        $('#license_id_no, #license_type, input[name="lastname"], #save-driver-btn')
                            .closest('.form-group').hide(); // Hide related fields
                        alert_toast('Driver saved successfully', 'success');
                        alert('You can now Select the Driver.');
                        location.href = "./?page=offenses/manage_record";
                    } else if (resp.status === 'failed' && !!resp.msg) {
                        var el = $('<div>').addClass("alert alert-danger err-msg").text(resp
                            .msg);
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

    });
    </script>