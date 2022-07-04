<div id="row">
    <div class="col-md-9 col-lg-12">
        <div class="box-shadow">
            <div class="table-head" style="height: 70px">
                <div class="col-md-12">
                    Detailed Warning Log Aptiv
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

                    <div class="col-md-12">
                        <div class="table-div responsive padding-top-xs">
                            <table id="item_tbl" class="item_tbl table" cellspacing="0" cellpadding="0">
                                <thead>
									<th></th>
									<th>PRIOR</th>
									
                                    <?php
									$releaseDate = "";
									//echo "<pre>"; print_r($periods);
									foreach($periods as $row => $col){
										if(empty($releaseDate)){
											$releaseDate=$row;
										}
										
									?>
                                        <th><?php echo $row?></th>
                                    <?php }?>
                                </thead>
                                <tbody>
								<tr>
									<td></td>
									<td></td>
									<?php 
									foreach($periods as $row => $col){?>
											<td><?php echo $col?></td>
									<?php }?>
								</tr>
									<tr>
									<td></td>
									<td></td>
									<?php 
									$periodKey=1;
									foreach($periods as $row => $col){?>
											<td><?=$Rlm_model->get_period_sum_by_key($rlmkey,$releaseDate,$periodKey++);?></td>
									<?php }?>
								</tr>
									<td>IN TRANSIT</td>
									<td><?php echo $in_transit_num ?></td>
                                
									</tr>
									<tr>
									<td>OPEN ORDER</td>
                                    <?php
                                    $openOrderArr = [];
                                    $swsPartNum = $rlmData[0]['sws_part_num'];
                                    $part_num = $rlmData[0]['part_num'];
                                    $oracleId = $transitData[0]['customer_num'];
                                    $shipToLoc = $transitData[0]['ship_to_location'];
                                    if($in_transit_num){
                                        $in_transit_num = $in_transit_num;
                                    }else{
                                        $in_transit_num = 0;
                                    }
                                    $import_date = date('Ymd',strtotime('Last Monday'));
                                    //echo $import_date;
                                    if(date('D') == "Mon") $import_date = date('Ymd');
                                    $infoData = array();
                                    $query_str="SELECT SUM(`open_qty`) AS open_qty , schd_ship_date FROM `open_orders` WHERE STATUS=1 AND `customer_num`='$oracleId' AND `ship_to_location`='$shipToLoc' AND `item` in ('$swsPartNum','$part_num') GROUP BY `schd_ship_date`";
                                    //echo $query_str."<br/>";
                                    $query=$this->db->query($query_str);
                                    $infoData = $query->result_array();
                                    for($i=0;$i<count($infoData);$i++){
                                      // echo date("Y-m-d", strtotime($import_date))."  -  ".date("Y-m-d", strtotime($infoData[$i]['schd_ship_date']))."<br/>";
                                       if(date("Y-m-d", strtotime($import_date)) > date("Y-m-d", strtotime($infoData[$i]['schd_ship_date']))){
                                            $openOrderArr['back_orders'] += $infoData[$i]['open_qty'];
                                            //$openOrderArr[$import_date] = $infoData[$i]['open_qty'];
                                        }else{
                                            $openOrderArr[$infoData[$i]['schd_ship_date']] = $infoData[$i]['open_qty'];
                                        }
                                    }
                                    //echo "<pre>";
                                    // print_r($infoData);
                                    //print_r($openOrderArr);
                                    $openOrderKeys = array_keys($openOrderArr);
                                   //print_r($openOrderKeys);

                                    ?>
									<td><?php echo $openOrderArr['back_orders'] ?></td>
                                    <?php
                                    $currentRowDate=''; 
                                    foreach($periods as $row => $col){
                                       $currentRowDate = date("Y-m-d", strtotime($row));
                                       $openOrderArrCheckThisDate=0;
                                        for($dateI=0;$dateI<=6;$dateI++){
                                            $dayInc = "+$dateI day";
                                            $checkThisDate = date("Ymd",strtotime(date('Y-m-d', strtotime($currentRowDate.".$dayInc."))));
                                            if(in_array($checkThisDate,$openOrderKeys)){
                                                $openOrderArrCheckThisDate=$openOrderArr[$checkThisDate];
                                                break;
                                            }
                                        }
                                    ?>
                                    <td><?php echo $openOrderArrCheckThisDate ?></td>
                                    <?php } 
                                    $swsInTransitPrior = $openOrderArr['back_orders']+$cum_rec_qty+$in_transit_num;
                                    ?>
									</tr>
									<tr>
									<td>SWS-USA CUM Estimate</td>
                                    <td><?php echo $swsInTransitPrior ?></td>
                                    <?php 
                                    $swsInTransitNew = $swsInTransitPrior;
                                    $swsInTransitNewArr = array();
                                    foreach($periods as $row => $col){
                                        $currentRowDate = date("Y-m-d", strtotime($row));
                                        $swsInTransitNewArr[$row] = $swsInTransitNew;
                                        for($dateI=0;$dateI<=6;$dateI++){
                                            $dayInc = "+$dateI day";
                                            $checkThisDate = date("Ymd",strtotime(date('Y-m-d', strtotime($currentRowDate.".$dayInc."))));
                                            if(in_array($checkThisDate,$openOrderKeys)){
                                                $swsInTransitNew = $swsInTransitNew+$openOrderArr[$checkThisDate];
                                                $swsInTransitNewArr[$checkThisDate] = $swsInTransitNew;
                                                break;
                                            }
                                        }
                                    ?>
                                    <td><?=$swsInTransitNew?></td>
                                    <?php } ?>
									</tr>
									 <tr>
									<td>DELTA</td>
                                    <td></td>
                                    <?php 
                                    //print_r($swsInTransitNewArr);
                                    foreach($periods as $row => $col){
                                        $currentRowDate = date("Y-m-d", strtotime($row));
                                        $delta = $swsInTransitNewArr[$row] - $col;
                                        for($dateI=0;$dateI<=6;$dateI++){
                                            $dayInc = "+$dateI day";
                                            $checkThisDate = date("Ymd",strtotime(date('Y-m-d', strtotime($currentRowDate.".$dayInc."))));
                                            if(in_array($checkThisDate,$openOrderKeys)){
                                                $delta = $swsInTransitNewArr[$checkThisDate] - $col;
                                            }
                                        }
                                    ?>
                                        <td><?php echo $delta; ?></td>
                                   <?php } ?>
									</tr>
									
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="table-div responsive padding-top-xs">
							<div class="marginBottom">
								<div class="form-group fieldWithError">
									<label title="Please add the part description">
										In Transit Number *
									</label>
                                    <?php
                                    $attributes = ['class' => '', 'id'=>'insert_data'];
                                    echo form_open($form_action, $attributes);
                                    $disabled="";
                                    if($status=='close'){
                                        $disabled='disabled';
                                    }
                                    ?>
									<input <?php echo $disabled ?> type="text" name="in_transit_num" id="in_transit_num" value="<?php echo $in_transit_num ?>" class="form-control" placeholder="">
                                   
								</div>
								
							</div>
							<button type="submit" class="btn btn-primary itemsubmitbtn">Save</button>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="table-div responsive padding-top-xs">
							<div class="marginBottom">
								<div class="form-group fieldWithError">
									<label title="Please add the part description">
										User Notes
									</label>
                                    <?php
                                    $attributes = ['class' => '', 'id'=>'insert_data'];
                                    echo form_open($form_action, $attributes);
                                    ?>
									<textarea <?php echo $disabled ?> style="width: 682px; height: 155px;" id="user_note" name="user_note"><?php echo $user_note ?></textarea>
                                   
								</div>
								
							</div>
							<button type="submit" class="btn btn-primary itemsubmitbtn">Save</button>
                            <?php echo form_close(); ?>
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