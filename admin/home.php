<hr class="bg-light">


<div>
	<div class="app-content pt-3 p-md-3 p-lg-4">
		<div class="container-xl">

			<div class="app-card alert alert-dismissible shadow-sm mb-4 " role="alert"
				style="border-left: solid #181870;">
				<div class="inner">
					<div class="app-card-body p-3 p-lg-4">
						<h3 class="mb-3">Welcome,
							<?php echo ucwords($_settings->userdata('firstname') . ' ' . $_settings->userdata('lastname')) ?>!
						</h3>
						<div class="row gx-5 gy-3">
							<div class="col-12 col-lg-9">

								<div>We are delighted to welcome you to the Traffic Rules Violation Management System
									(TRVMS) community. TRVMS is your comprehensive solution for managing, tracking, and
									resolving traffic rule violations efficiently and effectively.</div>
							</div><!--//col-->
							<div class="col-12 col-lg-3">
								<a class="btn app-btn-primary"
									href="https://themes.3rdwavemedia.com/bootstrap-templates/admin-dashboard/portal-free-bootstrap-admin-dashboard-template-for-developers/"><svg
										width="1em" height="1em" viewBox="0 0 16 16"
										class="bi bi-file-earmark-arrow-down me-2" fill="currentColor"
										xmlns="http://www.w3.org/2000/svg">
										<path
											d="M4 0h5.5v1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h1V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2z" />
										<path d="M9.5 3V0L14 4.5h-3A1.5 1.5 0 0 1 9.5 3z" />
										<path fill-rule="evenodd"
											d="M8 6a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 10.293V6.5A.5.5 0 0 1 8 6z" />
									</svg>Free Download</a>
							</div><!--//col-->
						</div><!--//row-->
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div><!--//app-card-body-->
				</div><!--//inner-->
			</div><!--//app-card-->

			<?php
			// Connection parameters for the current database
			$currentDbHost = 'localhost';
			$currentDbUser = 'root';
			$currentDbPass = '';
			$currentDbName = 'traffic_offense_db';

			// Connection parameters for the previous database
			$previousDbHost = 'localhost';
			$previousDbUser = 'root';
			$previousDbPass = '';
			$previousDbName = 'traffic_offense2';

			// Create connection to the current database
			$conn = new mysqli($currentDbHost, $currentDbUser, $currentDbPass, $currentDbName);

			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			// Retrieve the current total drivers
			$currentTotalOffenseItemsQuery = $conn->query("SELECT COUNT(*) as total FROM `offense_items`");
			$currentTotalOffenseItems = $currentTotalOffenseItemsQuery->fetch_assoc()['total'];


			// Retrieve the current total drivers
			$currentTotalDriversQuery = $conn->query("SELECT COUNT(*) as total FROM `drivers_list`");
			$currentTotalDrivers = $currentTotalDriversQuery->fetch_assoc()['total'];


			// Retrieve the current total active offenses
			$currentTotalOffenseQuery = $conn->query("SELECT COUNT(*) as total FROM `offenses` where status = 1");
			$currentTotalOffense = $currentTotalOffenseQuery->fetch_assoc()['total'];



			// Retrieve the current total offenses
			$currentTotalOffensesQuery = $conn->query("SELECT COUNT(*) as total FROM `offense_list1`");
			$currentTotalOffenses = $currentTotalOffensesQuery->fetch_assoc()['total'];

			// Retrieve the total pending offenses from the current database
			$currentPendingOffensesQuery = $conn->query("SELECT COUNT(*) as total FROM `offense_list1` WHERE status = 0");
			$currentPendingOffenses = $currentPendingOffensesQuery->fetch_assoc()['total'];

			// Create connection to the previous database
			$previousConn = new mysqli($previousDbHost, $previousDbUser, $previousDbPass, $previousDbName);

			// Check connection
			if ($previousConn->connect_error) {
				die("Connection failed: " . $previousConn->connect_error);
			}

			// Retrieve the previous total offenses
			$previousTotalOffensesQuery = $previousConn->query("SELECT COUNT(*) as total FROM `offense_list1`");
			$previousTotalOffenses = $previousTotalOffensesQuery->fetch_assoc()['total'];

			// Calculate percentage change
			if ($previousTotalOffenses > 0) {
				$percentageChange = (($currentTotalOffenses - $previousTotalOffenses) / $previousTotalOffenses) * 100;
			} else {
				$percentageChange = 0;
			}

			$percentageChange = number_format($percentageChange, 2); // Format the percentage to 2 decimal places
			
			// Close the previous database connection
			$previousConn->close();
			?>

			<div class="row g-4 mb-4">
				<div class="col-6 col-lg-3">
					<div class="app-card app-card-stat shadow-sm h-100">
						<div class="app-card-body p-3 p-lg-4">
							<h4 class="stats-type mb-1">Total Offenses</h4>
							<div class="stats-figure">
								<?php echo number_format($currentTotalOffenses); ?>
							</div>
							<div
								class="stats-meta <?php echo $percentageChange >= 0 ? 'text-primary' : 'text-danger'; ?>">
								<svg width="1em" height="1em" viewBox="0 0 16 16"
									class="bi bi-arrow-<?php echo $percentageChange >= 0 ? 'up' : 'down'; ?>"
									fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd"
										d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" />
								</svg> <?php echo $percentageChange; ?>%
							</div>
						</div><!--//app-card-body-->
						<a class="app-card-link-mask" href="#"></a>
					</div><!--//app-card-->
				</div><!--//col-->

				<div class="col-6 col-lg-3">
					<div class="app-card app-card-stat shadow-sm h-100">
						<div class="app-card-body p-3 p-lg-4">
							<h4 class="stats-type mb-1">Pending Offenses</h4>
							<div class="stats-figure">
								<?php echo number_format($currentPendingOffenses); ?>
							</div>
							<div class="stats-meta text-primary">
								<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-down"
									fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd"
										d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z" />
								</svg> <?php echo $currentPendingOffenses; ?>%
							</div>
						</div><!--//app-card-body-->
						<a class="app-card-link-mask" href="#"></a>
					</div><!--//app-card-->
				</div><!--//col-->
				<div class="col-6 col-lg-3">
					<div class="app-card app-card-stat shadow-sm h-100">
						<div class="app-card-body p-3 p-lg-4">
							<h4 class="stats-type mb-1">Total Drivers</h4>
							<div class="stats-figure"><?php echo $currentTotalDrivers; ?></div>
							<div class="stats-meta text-primary">
								Open</div>
						</div><!--//app-card-body-->
						<a class="app-card-link-mask" href="<?php echo base_url ?>admin/?page=drivers"></a>
					</div><!--//app-card-->
				</div><!--//col-->
				<div class="col-6 col-lg-3">
					<div class="app-card app-card-stat shadow-sm h-100">
						<div class="app-card-body p-3 p-lg-4">
							<h4 class="stats-type mb-1">Active Offenses</h4>
							<div class="stats-figure"><?php echo $currentTotalOffense; ?></div>
							<div class="stats-meta text-primary">Open</div>
						</div><!--//app-card-body-->
						<a class="app-card-link-mask" href="<?php echo base_url ?>admin/?page=maintenance/offenses"></a>
					</div><!--//app-card-->
				</div><!--//col-->
			</div><!--//row-->

			<?php
			// Get the date parameters from the URL, or set default values
			$previousWeek = isset($_GET['previousWeek']) ? $_GET['previousWeek'] : date("Y-m-d", strtotime('-14 days'));
			$currentWeek = isset($_GET['currentWeek']) ? $_GET['currentWeek'] : date("Y-m-d", strtotime('-7 days'));
			$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : date("Y-m-d");


			// CAST(COUNT(oi.offense_id) AS UNSIGNED) AS num_offense_items 
// COUNT(CASE WHEN date_created BETWEEN '$currentWeek' AND '$date_end' THEN 1 END) AS current_week
			// SQL Query to fetch data
			$query = "SELECT address2, 
           CAST(COUNT(ol.address2) AS UNSIGNED) AS current_week,
           COUNT(CASE WHEN date_created BETWEEN '$previousWeek' AND DATE_SUB('$currentWeek', INTERVAL 1 DAY) THEN 1 END) AS previous_week
    FROM `offense_list1` ol
    GROUP BY address2
    ORDER BY address2 ASC
";



			// Fetch data for the line chart
			$qry = $conn->query($query);

			// Check for query errors
			if (!$qry) {
				die("Query failed: " . $conn->error);
			}



			// Reset the result pointer to process rows again
			$qry->data_seek(0);

			$data_labels = [];
			$current_week_data = [];
			$previous_week_data = [];

			// Fetch and process data
			while ($row = $qry->fetch_assoc()) {
				$data_labels[] = $row['address2'];
				$current_week_data[] = (int) $row['current_week'];
				$previous_week_data[] = (int) $row['previous_week'];
			}



			// JSON encode the data
			$data_labels_json = json_encode($data_labels);
			$data_current_week = json_encode($current_week_data);
			$data_previous_week = json_encode($previous_week_data);



			// Fetch data for the bar chart
			$qry2 = $conn->query("
    SELECT o.offensename, 
           CAST(COUNT(oi.offense_id) AS UNSIGNED) AS num_offense_items 
    FROM `offenses` o 
    LEFT JOIN `offense_items` oi ON o.id = oi.offense_id 
    WHERE o.`status` = 1 
    GROUP BY o.id 
    ORDER BY o.code ASC
");

			$data_labels2 = [];
			$data2 = [];

			while ($row = $qry2->fetch_assoc()) {
				$data_labels2[] = $row['offensename'];
				$data2[] = (int) $row['num_offense_items']; // Convert to whole number
			}

			$data_labels2_json = json_encode($data_labels2);
			$data2_json = json_encode($data2);
			?>






			<div class="row g-4 mb-4">
				<div class="col-12 col-lg-12">
					<div class="app-card app-card-chart h-100 shadow-sm">
						<div class="app-card-header p-3">
							<h4 class="app-card-title">Apprehended</h4>
						</div>
						<div class="app-card-body p-3 p-lg-4">
							<div class="chart-container">
								<canvas id="canvas-linechart"></canvas>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-12">
					<div class="app-card app-card-chart h-100 shadow-sm">
						<div class="app-card-header p-3">
							<h4 class="app-card-title">Offenses Statistics</h4>
						</div>
						<div class="app-card-body p-3 p-lg-4">
							<div class="chart-container">
								<canvas id="canvas-barchart"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row g-4 mb-4">
				<div class="col-12 col-lg-12">
					<div class="app-card app-card-stats-table h-100 shadow-sm">
						<div class="app-card-header p-3">

							<div class="row justify-content-between align-items-center">
								<div class="col-auto">
									<h4 class="app-card-title">Offense List</h4>
								</div><!--//col-->
								<div class="col-auto">
									<div class="card-header-action">
										<a href="<?php echo base_url ?>admin/?page=reports&fill=All">View report</a>
									</div><!--//card-header-actions-->
								</div><!--//col-->
							</div><!--//row-->
						</div><!--//app-card-header-->
						<div class="app-card-body p-3 p-lg-4">
							<div class="table-responsive">
								<table class="table table-borderless mb-0">
									<thead>
										<tr>
											<th class="meta">Offense</th>
											<th class="meta stat-cell">Total</th>
										</tr>
									</thead>

									<tbody>
										<?php
										$i = 1;
										$qry = $conn->query("SELECT o.*, COUNT(offense_id) AS num_offense_items 
															FROM `offenses` o 
															LEFT JOIN `offense_items` oi ON o.id = oi.offense_id 
															WHERE o.`status` = 1 
															GROUP BY o.id 
															ORDER BY o.code ASC");



										while ($row = $qry->fetch_assoc()):
											$row['description'] = strip_tags(stripslashes(html_entity_decode($row['description'])));
											?>
											<tr>
												<td>
													<?php echo '[' . $row['code'] . '] - ' . $row['offensename']; ?>
												</td>

												<td class="stat-cell">
													<?php echo $row['num_offense_items']; ?>
												</td>


											</tr>
										<?php endwhile; ?>
									</tbody>

								</table>
							</div><!--//table-responsive-->
						</div><!--//app-card-body-->
					</div><!--//app-card-->
				</div><!--//col-->
			</div><!--//row-->

			<div class="row g-4 mb-4">
				<div class="col-12 col-lg-4">
					<div class="app-card app-card-basic d-flex flex-column align-items-start shadow-sm">
						<div class="app-card-header p-3 border-bottom-0">
							<div class="row align-items-center gx-3">
								<div class="col-auto">
									<div class="app-icon-holder" style="background-color: #DFEFFF;">
										<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-receipt"
											fill="#6BB2F5" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd"
												d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z" />
											<path fill-rule="evenodd"
												d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z" />
										</svg>
									</div><!--//icon-holder-->

								</div><!--//col-->
								<div class="col-auto">
									<h4 class="app-card-title">Ticket</h4>
								</div><!--//col-->
							</div><!--//row-->
						</div><!--//app-card-header-->
						<div class="app-card-body px-4">

							<div class="intro">Receiving an offense ticket typically involves various steps and legal
								implications, which can vary depending on the jurisdiction and the nature of the
								offense.</div>
						</div><!--//app-card-body-->
						<div class="app-card-footer p-4 mt-auto">
							<a class="btn app-btn-secondary"
								href="<?php echo base_url ?>admin/?page=offenses/manage_record">Create New</a>
						</div><!--//app-card-footer-->
					</div><!--//app-card-->
				</div><!--//col-->
				<div class="col-12 col-lg-4">
					<div class="app-card app-card-basic d-flex flex-column align-items-start shadow-sm">
						<div class="app-card-header p-3 border-bottom-0">
							<div class="row align-items-center gx-3">
								<div class="col-auto">
									<div class="app-icon-holder" style="background-color: #DFEFFF;">
										<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-code-square"
											fill="#6BB2F5" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd"
												d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
											<path fill-rule="evenodd"
												d="M6.854 4.646a.5.5 0 0 1 0 .708L4.207 8l2.647 2.646a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 0 1 .708 0zm2.292 0a.5.5 0 0 0 0 .708L11.793 8l-2.647 2.646a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708 0z" />
										</svg>
									</div><!--//icon-holder-->

								</div><!--//col-->
								<div class="col-auto">
									<h4 class="app-card-title">Driver</h4>
								</div><!--//col-->
							</div><!--//row-->
						</div><!--//app-card-header-->
						<div class="app-card-body px-4">

							<div class="intro">The role of a driver encompasses a broad range of responsibilities and
								requires various skills, knowledge, and adherence to legal and safety standards.</div>
						</div><!--//app-card-body-->
						<div class="app-card-footer p-4 mt-auto">
							<a class="btn app-btn-secondary" href="?page=drivers/manage_driver">Create New</a>
						</div><!--//app-card-footer-->
					</div><!--//app-card-->
				</div><!--//col-->
				<div class="col-12 col-lg-4">
					<div class="app-card app-card-basic d-flex flex-column align-items-start shadow-sm">
						<div class="app-card-header p-3 border-bottom-0">
							<div class="row align-items-center gx-3">
								<div class="col-auto">
									<div class="app-icon-holder" style="background-color: #DFEFFF;">
										<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-tools"
											fill="#6BB2F5" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd"
												d="M0 1l1-1 3.081 2.2a1 1 0 0 1 .419.815v.07a1 1 0 0 0 .293.708L10.5 9.5l.914-.305a1 1 0 0 1 1.023.242l3.356 3.356a1 1 0 0 1 0 1.414l-1.586 1.586a1 1 0 0 1-1.414 0l-3.356-3.356a1 1 0 0 1-.242-1.023L9.5 10.5 3.793 4.793a1 1 0 0 0-.707-.293h-.071a1 1 0 0 1-.814-.419L0 1zm11.354 9.646a.5.5 0 0 0-.708.708l3 3a.5.5 0 0 0 .708-.708l-3-3z" />
											<path fill-rule="evenodd"
												d="M15.898 2.223a3.003 3.003 0 0 1-3.679 3.674L5.878 12.15a3 3 0 1 1-2.027-2.027l6.252-6.341A3 3 0 0 1 13.778.1l-2.142 2.142L12 4l1.757.364 2.141-2.141zm-13.37 9.019L3.001 11l.471.242.529.026.287.445.445.287.026.529L5 13l-.242.471-.026.529-.445.287-.287.445-.529.026L3 15l-.471-.242L2 14.732l-.287-.445L1.268 14l-.026-.529L1 13l.242-.471.026-.529.445-.287.287-.445.529-.026z" />
										</svg>
									</div><!--//icon-holder-->

								</div><!--//col-->
								<div class="col-auto">
									<h4 class="app-card-title">Offense</h4>
								</div><!--//col-->
							</div><!--//row-->
						</div><!--//app-card-header-->
						<div class="app-card-body px-4">

							<div class="intro">Traffic offenses are typically governed by state motor vehicle codes that
								define offenses ranging from minor infractions to severe violations.</div>
						</div><!--//app-card-body-->
						<div class="app-card-footer p-4 mt-auto">
							<?php if ($_settings->userdata('type') == 1): ?>
								<a class="btn app-btn-secondary" href="?page=maintenance/manage_offense">Create New</a>
							<?php endif; ?>

						</div><!--//app-card-footer-->
					</div><!--//app-card-->
				</div><!--//col-->
			</div><!--//row-->

		</div><!--//container-fluid-->
	</div><!--//app-content-->


</div><!--//app-wrapper-->





<script src="assets/plugins/popper.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

<!-- Charts JS -->
<script src="assets/plugins/chart.js/chart.min.js"></script>

<!-- <script src="assets/js/index-charts.js"></script>  -->

<!-- Page Specific JS -->
<script src="assets/js/app.js"></script>



<script>
	// Line chart initialization
	var ctx = document.getElementById('canvas-linechart').getContext('2d');
	var lineChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: <?php echo $data_labels_json; ?>,
			datasets: [{
				label: 'Apprehended',
				data: <?php echo $data_current_week; ?>,
				fill: false,
				borderColor: '#191970',
				tension: 0.1
			}
			]
		},
		options: {
			scales: {
				y: {
					type: 'linear',
					ticks: {
						stepSize: 1,
						precision: 0
					}
				}
			},
			legend: {
				display: true,
				position: 'top'
			},
			title: {
				display: true,
				text: 'Line Chart'
			},
			tooltips: {
				enabled: true,
				mode: 'nearest'
			},
			animation: {
				duration: 1000, // Animation duration in milliseconds
				easing: 'easeInOutQuad' // Easing function for animation
			},
			layout: {
				padding: {
					left: 20,
					right: 20,
					top: 20,
					bottom: 20
				}
			},
			responsive: true
		}
	});

	// Bar chart initialization
	var ctx1 = document.getElementById('canvas-barchart').getContext('2d');
	var barChart = new Chart(ctx1, {
		type: 'bar',
		data: {
			labels: <?php echo $data_labels2_json; ?>,
			datasets: [{
				label: "Offenses",
				backgroundColor: '#191970', // Adjust color as needed
				borderColor: '#191970', // Adjust color as needed
				borderWidth: 1,
				maxBarThickness: 16,
				data: <?php echo $data2_json; ?>,
			},],
		},
		options: {
			scales: {
				y: {
					type: 'linear',
					ticks: {
						stepSize: 1,
						precision: 0
					}
				}
			},
			legend: {
				display: true,
				position: 'top'
			},
			title: {
				display: true,
				text: 'Bar Chart'
			},
			tooltips: {
				enabled: true,
				mode: 'nearest'
			},
			animation: {
				duration: 1000, // Animation duration in milliseconds
				easing: 'easeInOutQuad' // Easing function for animation
			},
			layout: {
				padding: {
					left: 20,
					right: 20,
					top: 20,
					bottom: 20
				}
			},
			responsive: true
		}
	});
</script>