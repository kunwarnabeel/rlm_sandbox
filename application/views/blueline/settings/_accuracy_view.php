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
					<th colspan="6" class="text-center">Accuracy (Sales/SF)</th>
				</tr>
				<tr>
					<td>3M</td>
					<td>6M</td>
					<td>9M</td>
					<td>12M</td>
					<td>15M</td>
					<td>18M</td>
				</tr>
				<tr>
					<td style="background: <?=$sales_data['3Mcolor']?>"><b><?php echo $sales_data['3M'];?></b></td>
					<td style="background: <?=$sales_data['6Mcolor']?>"><b><?php echo $sales_data['6M'];?></b></td>
					<td style="background: <?=$sales_data['9Mcolor']?>"><b><?php echo $sales_data['9M'];?></b></td>
					<td style="background: <?=$sales_data['12Mcolor']?>"><b><?php echo $sales_data['12M'];?></b></td>
					<td style="background: <?=$sales_data['15Mcolor']?>"><b><?php echo $sales_data['15M'];?></b></td>
					<td style="background: <?=$sales_data['18Mcolor']?>"><b><?php echo $sales_data['18M'];?></b></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th colspan="6" class="text-center">Accuracy (Orders/SF)</th>
				</tr>
				<tr>
					<td>3M</td>
					<td>6M</td>
					<td>9M</td>
					<td>12M</td>
					<td>15M</td>
					<td>18M</td>
				</tr>
				<tr>
					<td style="background: <?=$orders_data['3Mcolor']?>"><b><?php echo $orders_data['3M'];?></b></td>
					<td style="background: <?=$orders_data['6Mcolor']?>"><b><?php echo $orders_data['6M'];?></b></td>
					<td style="background: <?=$orders_data['9Mcolor']?>"><b><?php echo $orders_data['9M'];?></b></td>
					<td style="background: <?=$orders_data['12Mcolor']?>"><b><?php echo $orders_data['12M'];?></b></td>
					<td style="background: <?=$orders_data['15Mcolor']?>"><b><?php echo $orders_data['15M'];?></b></td>
					<td style="background: <?=$orders_data['18Mcolor']?>"><b><?php echo $orders_data['18M'];?></b></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="modal-footer">
		<a class="btn" data-dismiss="modal">Close</a>
</div>