<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h1 class="card-title" style="font-size: 24px;">Activity Logs</h1>
    </div>

	<div class="card-body">
		<div class="container-fluid">
			<div class="container-fluid">
				<table class="table table-hover table-striped">
					<colgroup>
						<col width="2%">
						<col width="10%">
						<col width="10%">
                        <col width="10%">
						<col width="30%">
					</colgroup>
					<thead>
						<tr>
							<th>#</th>
							<th>Date</th>
							<th>User</th>
                            <th>Type</th>
                            <th>Description</th>
						</tr>
					</thead>
					<tbody>
                        <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT * FROM `activity_log` ORDER BY unix_timestamp(date_created) DESC");
                        while ($row = $qry->fetch_assoc()): ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($row['date_created']); ?></td>
                                <td><?php echo htmlspecialchars($row['user']); ?></td>
                                <td><?php echo htmlspecialchars($row['action']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
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
			_conf("Are you sure to delete this driver permanently?","delete_driver",[$(this).attr('data-id')])
		})
		$('.view_details').click(function(){
			uni_modal("<i class='fa fa-id-card'></i> Driver's Information","drivers/view_details.php?id="+$(this).attr('data-id'),'large')
		})
		$('.table').dataTable({
			columnDefs: [
				{ orderable: false, targets: [3,4] }
			]
		});
	})
	function delete_driver($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_driver",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occurred.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occurred.",'error');
					end_loader();
				}
			}
		})
	}
</script>
