<?php if ($_settings->chk_flashdata('success')): ?>

	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>


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

				<table class="table table-hover table-stripped">

					<colgroup>
						<col width="3%">
						<col width="10%">
						<col width="8%">
						<col width="15%">
						<col width="15%">
						<col width="15%">
						<!-- <col width="5%"> -->
						<col width="3%">
						<col width="5%">
					</colgroup>

					<?php if ($_settings->userdata('type') == 1): ?>
					<?php else: ?>
					<?php endif; ?>
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th>Date</th>
							<th>Ticket No.</th>
							<th>Driver</th>
							<th>Offense</th>
							<th>Officer</th>
							<th>Status</th>
							<!-- <th style="text-align: center;">Paid</th> -->

							<th style="text-align: center;">Action</th>

						</tr>
					</thead>
					<tbody id="tableBody">
						<?php
						$i = 1;
						$qry = $conn->query("SELECT r.*, d.license_id_no, d.name FROM `offense_list1` r INNER JOIN `drivers_list` d ON r.driver_id = d.id ORDER BY unix_timestamp(r.date_created) DESC");
						while ($row = $qry->fetch_assoc()):
							?>
							<tr>
								<!-- # -->
								<td class="text-center"><?php echo $i++; ?></td>
								<!-- DateTime -->
								<td><?php echo date("Y-m-d H:i A", strtotime($row['date_created'])) ?></td>
								<!-- TicketNo -->
								<td><a href="javascript:void(0)" class="view_details"
										data-id="<?php echo $row['id'] ?>"><?php echo $row['ticket_no'] ?></td>
								<!-- Driversname -->
								<td><?php echo $row['name'] ?></td>
								<!-- Offense -->
								<td>
									<?php
									if (isset($row['id'])):
										$id = $row['id']; // Set the $id variable for the offense
										$olist = $conn->query("SELECT i.*, o.code, o.offensename FROM `offense_items` i INNER JOIN `offenses` o ON i.offense_id = o.id WHERE i.driver_offense_id ='{$id}' ");
										$offense_names = []; // Initialize an array to store offense names
										while ($offense_row = $olist->fetch_assoc()):
											$offense_names[] = $offense_row['offensename']; // Store the offense name in the array
										endwhile;
										echo implode("<br>", $offense_names); // Display offense names separated by commas
									endif;
									?>
								</td>
								<!-- Officers' name -->
								<td><?php echo $row['officer'] ?></td>
								<!-- status -->
								<td>
									<?php if (isset($row['status']) && $row['status'] == 1): ?>
										<span class="badge badge-success">Paid</span>
									<?php else: ?>
										<span class="badge badge-secondary">Pending</span>
									<?php endif; ?>
								</td>
								<!-- action -->
								<td style="text-align: center; vertical-align: middle;">
									<div style="display: inline-block;">
										<a class="btn btn-outline-primary"
											href="?page=offenses/manage_record&id=<?php echo $row['id'] ?>">
											<span class="fa fa-edit"></span>
										</a>
										<a class="btn btn-outline-secondary view_details" href="javascript:void(0)"
											data-id="<?php echo $row['id'] ?>">
											<span class="fa fa-print"></span>
										</a>
										<!-- <a class="btn btn-outline-danger delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
								<span class="fa fa-trash"></span>
							</a> -->
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
			data: { id: $id },
			dataType: "json",
			error: err => {
				console.log(err)
				alert_toast("An error occured.", 'error');
				end_loader();
			},
			success: function (resp) {
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
	document.getElementById('searchInput').addEventListener('keyup', function () {
		var filter = this.value.toLowerCase();
		var rows = document.querySelectorAll('#tableBody tr');

		rows.forEach(function (row) {
			var text = row.textContent.toLowerCase();
			row.style.display = text.indexOf(filter) > -1 ? '' : 'none';
		});
	});


	$(document).ready(function () {
		// Click event listener for deleting offense record
		$('.delete_data').click(function () {
			_conf("Are you sure to delete this offense record permanently?", "delete_offense", [$(this).attr('data-id')])
		});

		// Click event listener for updating the status of the offense record
		$('.paid_data').click(function () {
			_conf("Are you sure that this offense record is Paid?", "paid_offense", [$(this).data('id')]);
		});

		// Click event listener for updating the status of the offense record
		$('.unpaid_data').click(function () {
			_conf("Are you sure that this offense record is Not Paid?", "unpaid_offense", [$(this).data('id')]);
		});

		// Click event listener for viewing offense details
		$('.view_details').click(function () {
			uni_modal("<i class='fa fa-ticket'></i> Driver's Offense Ticket Details", "offenses/view_details.php?id=" + $(this).attr('data-id'), 'mid-large');
		});

		// Initialize DataTable
		$('.table').dataTable({
			columnDefs: [{ orderable: false, targets: [1, 4, 7, 8] }]
		});


	});

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