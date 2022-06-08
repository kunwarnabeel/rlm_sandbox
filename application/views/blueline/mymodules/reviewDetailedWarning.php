<div id="row">
    <div class="col-md-9 col-lg-12">
        <div class="box-shadow">
            <div class="table-head" style="height: 70px">
                <div class="col-md-12">
                    Detailed Warning Log
                </div>
            </div>
            <div class="">
                <div class="table-head col-md-12">
                    Release Information
                </div>
                <div class="table-div responsive padding-top-xs">
                    <table id="item_tbl" class="item_tbl table" cellspacing="0" cellpadding="0">
                        <thead>
                            <th>Release Number</th>
                            <th>Plant </th>
                            <th>Part Number</th>
                            <th>Vendor Code</th>
                            <th>Last RCV QTY</th>
                            <th>Last RCV ASN</th>
                            <th>Last RCV Date</th>
                            <th>Cum RCV Qty</th>
                        </thead>
                        <tbody>
                            <?php 
                            $cum_rec_qty = 0;
                            foreach($rlmData as $row){
                            $cum_rec_qty = $row['cum_rec_qty'];
                            ?>
                                <tr>
                                    <td><?php echo $row['rel_num']?></td>
                                    <td><?php echo $row['plant_num']?></td>
                                    <td><?php echo $row['part_num']?></td>
                                    <td><?php echo $row['vendor_code']?></td>
                                    <td><?php echo $row['rec_qty']?></td>
                                    <td><?php echo $row['rec_asn_id']?></td>
                                    <td><?php echo $row['last_rcv_date']?></td>
                                    <td><?php echo $row['cum_rec_qty']?></td>
                                </tr>
                                <?php }?> 
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-head col-md-12">
                            Demand Information
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="table-div responsive padding-top-xs">
							<div class="marginBottom">
								<div class="form-group fieldWithError">
									<label title="Please add the part description">
										In trnasit Number *
									</label>
                                    <?php
                                    $attributes = ['class' => '', 'id'=>'insert_data'];
                                    echo form_open($form_action, $attributes);
                                    ?>
									<input type="text" name="in_transit_num" id="in_transit_num" value="<?php echo $in_transit_num ?>" class="form-control" placeholder="">
                                   
								</div>
								
							</div>
							<button type="submit" class="btn btn-primary itemsubmitbtn">Save</button>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="table-div responsive padding-top-xs">
                            <table id="item_tbl" class="item_tbl table" cellspacing="0" cellpadding="0">
                                <thead>
									<th></th>
									<th>PRIOR</th>
                                    <?php foreach($periods as $row => $col){?>
                                        <th><?php echo $row?></th>
                                    <?php }?>
                                </thead>
                                <tbody>
									<tr>
									<td></td>
									<td></td>
                                <?php foreach($periods as $row => $col){?>
                                        <td><?php echo $col?></td>
                                    <?php }?>
									</tr>
									<tr>
									<td>IN TRANSIT</td>
									<td><?php echo $in_transit_num ?></td>
                                
									</tr>
									<tr>
									<td>OPEN ORDER</td>
                                    <?php
                                    $openOrderArr = [];
                                    $swsPartNum = $rlmData[0]['sws_part_num'];
                                    $oracleId = $transitData[0]['customer_num'];
                                    $shipToLoc = $transitData[0]['ship_to_location'];
                                    if($in_transit_num){
                                        $in_transit_num = $in_transit_num;
                                    }else{
                                        $in_transit_num = 0;
                                    }
                                    $import_date = date('Ymd',strtotime('Last Monday'));
                                    if(date('D') == "Mon") $import_date = date('Ymd');
                                    $query_str="SELECT SUM(`open_qty`) AS open_qty FROM `open_orders` WHERE `customer_num`='$oracleId' AND `ship_to_location`='$shipToLoc' AND `item`='$swsPartNum' AND `import_date`='$import_date' GROUP BY `schd_ship_date`";
                                    $query=$this->db->query($query_str);
                                    $infoData = $query->result_array();
                                    $swsInTransitPrior = $infoData[0]['open_qty']+$cum_rec_qty+$in_transit_num;
                                    foreach($infoData as $key=>$val){
                                    ?>
                                    <td><?php echo $val['open_qty']?></td>
                                    <?php } ?>
                                    </tr>
                                    <tr>
                                    <td>SWS-USA CUM Estimate</td>
									<td>
                                       <?php echo $swsInTransitPrior ?>  
                                    </td>

                                    <?php 
                                    $swsInTransitNew = $swsInTransitPrior;
                                    for($i=1;$i<count($infoData);$i++){
                                        $swsInTransitNew = $swsInTransitNew+$infoData[$i]['open_qty'];
                                        $swsInTransitNewArr[$i] = $swsInTransitNew;
                                    ?>
                                    <td><?=$swsInTransitNew?></td>
                                    <?php } ?>
									</tr>

                                    <tr>
                                    <td>Delta</td>
                                    <td></td>
                                    <?php 
                                    $swsIndex = 1;
                                    foreach($periods as $row => $col){
                                        if(!$swsInTransitNewArr[$swsIndex])
                                        continue;
                                        $delta = $swsInTransitNewArr[$swsIndex] - $col;
                                        $swsIndex++;
                                    ?>
                                        <td><?php echo $delta; ?></td>
                                    <?php } ?>
                                    </tr>
									
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>    
                
                
            </div>

            <div class="">
                <div class="table-head col-md-12">
                    SWS-USA Intransit Data
                </div>
                <div class="table-div responsive padding-top-xs">
                    <table id="item_tbl" class="item_tbl table" cellspacing="0" cellpadding="0">
                        <thead>
                            <th>CUSTOMER NAME</th>
                            <th>CUSTOMER NUMBER </th>
                            <th>PART NUMBER</th>
                            <th>DESCRIPTION</th>
                            <th>SCHEDULE SHIP DATE</th>
                            <th>ACTUAL SHIP DATE</th>
                            <th>PERIOD</th>
                            <th>QTY DUE</th>
                            <th>QTY SHIPPED</th>
                            <th>QTY OPEN</th>
                        </thead>
                        <tbody>
                            <?php foreach($transitData as $row){?>
                                <tr>
                                    <td><?php echo $row['customer_name']?></td>
                                    <td><?php echo $row['customer_num']?></td>
                                    <td><?php echo $row['part_number']?></td>
                                    <td><?php echo $row['description']?></td>
                                    <td><?php echo $row['schedule_ship_date']?></td>
                                    <td><?php echo $row['actual_ship_date']?></td>
                                    <td><?php echo $row['period']?></td>
                                    <td><?php echo $row['qty_due']?></td>
                                    <td><?php echo $row['qty_shipped']?></td>
                                    <td><?php echo $row['qty_open']?></td>
                                </tr>
                                <?php }?> 
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>