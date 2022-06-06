<style type="text/css">
	.table-bordered th{
    background: #0f6c90;
    color: #fff;
}
</style>
<div class="row">
	<div class="col-md-8 col-md-offset-2">
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th>Time</th>
						<th>Salesforce <small>(<?php if(!empty($sf_last_uploaded)) echo $sf_last_uploaded;?>)</small></th>
						<th>Orders <small>(<?php if(!empty($order_last_uploaded)) echo $order_last_uploaded;?>)</small></th>
						<th>Sales <small>(<?php if(!empty($sales_last_uploaded)) echo $sales_last_uploaded;?>)</small></th>
					</tr>
					<tr>
						<th>6M 1</th>
						<td><?php echo $sf_data['sf_6M1'];?></td>
						<td><?php echo $orders_data['order_6M1'];?></td>
						<td><?php echo $sales_data['sales_6M1'];?></td>
					</tr>
					<tr>
						<th>6M 2</th>
						<td><?php echo $sf_data['sf_6M2'];?></td>
						<td><?php echo $orders_data['order_6M2'];?></td>
						<td><?php echo $sales_data['sales_6M2'];?></td>
					</tr>
					<tr>
						<th>6M 3</th>
						<td><?php echo $sf_data['sf_6M3'];?></td>
						<td><?php echo $orders_data['order_6M3'];?></td>
						<td><?php echo $sales_data['sales_6M3'];?></td>
					</tr>
					<tr>
						<th>6M 4</th>
						<td><?php echo $sf_data['sf_6M4'];?></td>
						<td><?php echo $orders_data['order_6M4'];?></td>
						<td><?php echo $sales_data['sales_6M4'];?></td>
					</tr>
					<tr>
						<th>6M 5</th>
						<td><?php echo $sf_data['sf_6M5'];?></td>
						<td><?php echo $orders_data['order_6M5'];?></td>
						<td><?php echo $sales_data['sales_6M5'];?></td>
					</tr>
					<tr>
						<th>12M</th>
						<td><?php echo $sf_data['sf_12M'];?></td>
						<td><?php echo $orders_data['order_12M'];?></td>
						<td><?php echo $sales_data['sales_12M'];?></td>
					</tr>
					<tr>
						<th>18M</th>
						<td><?php echo $sf_data['sf_18M'];?></td>
						<td><?php echo $orders_data['order_18M'];?></td>
						<td><?php echo $sales_data['sales_18M'];?></td>
					</tr>
					
				</tbody>
			</table>
	</div>
</div>
	<div class="modal-footer">
		<a class="btn" data-dismiss="modal">Close</a>
	</div>
