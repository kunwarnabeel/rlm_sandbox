<div id="row">
    <div class="col-md-9 col-lg-12">
        <div class="box-shadow">

        <div class="table-head" style="height: 70px">
            <div class="col-md-8">
            Warning Log
            </div>
            <div class="col-md-4">
            </div>
        </div>

        <div class="table-div responsive padding-top-xs">

            <table id="item_tbl" class="item_tbl table" cellspacing="0" cellpadding="0">
                <thead>
                    <th> Date</th>

                    <th> Release Date</th>

                    <th> Customer</th>

                    <th> Release No.</th>
                    <th> Customer No.</th>

                    <th> Plant No.</th>

                    <th> Cust Part No.</th>

                    <th> SWS Part No.</th>
                    <th> Warning</th>
                    <th> Index</th>
                    <th> Status</th>
                    
                    <!-- <th> User Notes</th>-->
                    <th> <?=$this->lang->line('application_action');?></th> 
                    <!-- <th> <?=$this->lang->line('application_review');?></th>  -->
                </thead>
                <tbody>
                    <?php foreach($warnings as $row){?>
                    <tr>
                        <td>
                            <?php   
                            $attributes = array('class' => '', 'autocomplete' => 'off');
                            echo form_open_multipart($form_action, $attributes); 
                            ?>
                            <?php echo $row['log_date']?></td>
                        <td><?php echo $row['release_date']?></td>
                        <td><?php echo $row['customer_name']?></td>
                        <td><?php echo $row['release_num']?></td>
                        <td><?php echo $row['customer_num']?></td>
                        <td><?php echo $row['plant_num']?></td>
                        <td><?php echo $row['part_num']?></td>
                        <td><?php echo $row['sws_part_num']?></td>
                        <td><?php echo $row['warning']?></td>
                        <td><?php echo $row['delta']?></td>
                        <td><?php echo $row['status']?></td>
                        <!-- <td><input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                            <textarea rows="3" cols="50" class="limit limit_box" name="user_notes" placeholder="User Notes"><?php echo $row['user_note'] ?></textarea></td> -->
                        <td>
                            <a href="<?=base_url()?>Review_Warning/reviewWarning/<?=$row['rlm_key'];?>" 
                            class="<?php if($row['status'] == 'Closed') echo 'disable'; else echo 'enable'?>-view">Review</a>
                            
                            <!-- data-toggle="modal" data-target="#reviewWarningModal" -->
                        <!-- <td class="option" width="8%">
                            <input class="status_val" type="hidden" name="status" value="<?=$row['status'];?>"/>
                            <input class="stc_status" type="checkbox" data-toggle="toggle" 
                                <?php if($row['status'] == 'Closed') echo 'checked'.' disabled';?>
                                data-size="mini"/>
                            <input type='submit' 
                                <?php if($row['status'] == 'Closed') echo 'disabled';?>  
                                name='send' class='btn btn-primary' value='Save'/>
                                <?php echo form_close(); ?>
                        </td> -->
                    </tr>
                        <?php }?>                    
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<div id="reviewWarningModal" class="modal fade" data-easein="flipXIn" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="reviewWarningModalLabel" aria-hidden="true">
    
</div>
<script type="text/javascript">
    $(window).load(function() {
        $(".loader").fadeOut("slow");
    });
    $(document).ready(function() {
        $('.stc_status').on('click',function(e){
            if ($(this).is(':checked')) {
                $(this).closest('td').find('.status_val').val('Closed');                
            }
            else{
                    $(this).closest('td').find('.status_val').val('Open');
            }
        })
    $('.item_tbl thead tr').clone(true).appendTo( '.item_tbl thead' );
    $('.item_tbl thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();

        if(title.trim()!="Action")
        {
            $(this).html('<input type="text" class="    " placeholder="Search" />');
        }else{
            $(this).html('');
        }
     
            $('input', this ).on('keyup change', function () {
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
                $('#search_item_btn').attr('disabled','') ;              
            } );

    } );

    var table = $('.item_tbl').DataTable( {
        orderCellsTop: true,
        fixedHeader: true,
        lengthMenu: [[10, 25, 50, 100,-1], [10, 25, 50,100, "All"]],
        pageLength: defaulnumberofpages,
        fixedHeader: true,
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