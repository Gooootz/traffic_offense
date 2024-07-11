<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Vehicles</h3>
		<?php if($_settings->userdata('type') == 1): ?>
		<div class="card-tools">
			<a href="?page=vehicle/manage_vehicle" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div>
		<?php endif; ?>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped table-hover">
			<?php if($_settings->userdata('type') == 1): ?>
				<colgroup>
					<col width="1%">
					<col width="10%">
					<col width="50%">
					<col width="5%">
					<col width="10%">
				</colgroup>
			<?php else: ?>
				<colgroup>
					<col width="1%">
					<col width="50%">
					<col width="5%">
					<col width="10%">
				</colgroup>
			<?php endif; ?>
				<thead>
					<tr>
						<th>#</th>
						<th>Date Created</th>
						<th>Name</th>
						<th>Status</th>
						<?php if($_settings->userdata('type') == 1): ?>
						<th style="text-align: center;" >Action</th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT * from `vehicles` order by `name` asc ");
						while($row = $qry->fetch_assoc()):
                            $row['description'] = strip_tags(stripslashes(html_entity_decode($row['name'])));
					?>
						<tr title="<?php echo $row['description'] ?>">
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td><?php echo $row['name'] ?></td>
							<td class="text-center">
                                <?php if($row['status'] == 1): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
							<?php if($_settings->userdata('type') == 1): ?>
							
							<td style="text-align: center;" >
								<div style="display: inline;" align="center" >
								
									<a class="btn btn-outline-primary" href="?page=vehicle/manage_vehicle&id=<?php echo $row['id'] ?>">
										<span class="fa fa-edit"></span>
									</a>
									<!-- <a class="btn btn-outline-danger delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
										<span class="fa fa-trash"></span>
									</a> -->
									
								</div>
							</td>
							<?php endif; ?>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Type of Vehicle permanently?","delete_vehicle",[$(this).attr('data-id')])
		})
		$('.table').dataTable();
	})
	function delete_vehicle($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_vehicle",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>