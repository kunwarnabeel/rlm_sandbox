<div id="row">
    <div class="col-md-9 col-lg-12">
        <div class="box-shadow">

        <div class="table-head" style="height: 70px">
            <div class="col-md-12">
            File Mapping By Customer
            </div>
            <!-- <div class="col-md-4">
                <a style="margin-top:30px" href="<?=site_url()?>item_setup/exportall" class="btn btn-primary pull-right" >
                            Export All
                        </a>
            </div> -->
        </div>

        <div class="table-div responsive padding-top-xs">

            <table id="item_tbl" class="item_tbl table" cellspacing="0" cellpadding="0">
                <thead>
                    <th> Customer Number</th>

                    <th> Customer Name</th>
                    <th> File Name</th>

                    <th> Plant Number</th>

                    <th> Part Number</th>
                    <th> Vendor Code</th>

                    <th> Receive Quantity</th>

                    <th> Receive ASN ID</th>

                    <th> CUM. Receive Quantity</th>
                    <th> Release Number</th>
                    <th> Release Date</th>
                    <th> Past Due</th>
                    <th> Date Format</th>

                    <th> <?=$this->lang->line('application_action');?></th>
                </thead>
                <tbody>
                    <?php foreach($mappings as $row){?>
                        
                        <tr>
                        <td>
                            <?php   
                            $attributes = array('class' => '', 'autocomplete' => 'off');
                            echo form_open_multipart($form_action, $attributes); 
                            ?>
                            <?php echo $row['customer_number']?></td>
                        <td><?php echo $row['customer_name']?></td>
                        <td>
                            <input type="text" class="limit limit_box" name="file_name"placeholder="File Name" value="<?php echo $row['file_name'] ?>">
                        </td>
                        <td>
                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                            <input type="text" class="limit limit_box" name="plant_num"placeholder="Plant Number" value="<?php echo $row['plant_num'] ?>">
                        </td>
                        <td>
                            <input type="text" class="limit limit_box" name="part_num"placeholder="Part Number" value="<?php echo $row['part_num'] ?>">
                        </td>
                        <td>
                            <input type="text" class="limit limit_box" name="vendor_code"placeholder="Vendor Code" value="<?php echo $row['vendor_code'] ?>">
                        </td>
                        <td>
                            <input type="text" class="limit limit_box" name="rec_qty"placeholder="Received Quantity" value="<?php echo $row['rec_qty'] ?>">
                        </td>
                        <td>
                            <input type="text" class="limit limit_box" name="rec_asn_id"placeholder="Last RCV ASN" value="<?php echo $row['rec_asn_id'] ?>">
                        </td>
                        <td>
                            <input type="text" class="limit limit_box" name="cum_rec_qty"placeholder="CUM Receive Quantity" value="<?php echo $row['cum_rec_qty'] ?>">
                        </td>
                        <td>
                            <input type="text" class="limit limit_box" name="rel_num"placeholder="Release Number" value="<?php echo $row['rel_num'] ?>">
                            </td>
                        <td>
                            <input type="text" class="limit limit_box" name="rel_date"placeholder="Release Date" value="<?php echo $row['rel_date'] ?>">
                        </td>
                        <td>
                            <input type="text" class="limit limit_box" name="past_due"placeholder="Past Due" value="<?php echo $row['past_due'] ?>">
                        </td>
                        <td>
                            <input type="text" class="limit limit_box" name="date_format"placeholder="Date Format" value="<?php echo $row['date_format'] ?>">
                        </td>
                        <td class="option" width="8%">
                        <input type='submit' name='send' class='btn btn-primary' value='Save'/>
                        <?php echo form_close(); ?>
                    </td>
                        </tr>
                        <?php }?>                    
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    $(window).load(function() {
        $(".loader").fadeOut("slow");
    });
    $(document).ready(function() {

    var table = $('.item_tbl').DataTable( {
        orderCellsTop: true,
        fixedHeader: true,
        lengthMenu: [[10, 25, 50, 100,-1], [10, 25, 50,100, "All"]],
        pageLength: defaulnumberofpages,
        fixedHeader: true,
        // scrollCollapse: true,
        // scrollY:        "340px",
        // scrollX:        true,
        fixedHeader: {
            header: true,
            footer: true
        }
    } );

    $('#search_item_btn').on('click',function(){
        table.draw();
        console.log(tableData)
    })
} )

</script>