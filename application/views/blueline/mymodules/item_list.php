<div id="row">
    <div class="col-md-9 col-lg-12">
        <div class="box-shadow">

        <div class="table-head" style="height: 70px">
            <div class="col-md-8">
            Item List
            </div>
            <div class="col-md-4">
                <a style="margin-top:30px" href="<?=site_url()?>item_setup/exportall" class="btn btn-primary pull-right" >
                            Export All
                        </a>
            </div>
        </div>

        <div class="table-div responsive padding-top-xs">

            <table id="item_tbl" class="item_tbl table" cellspacing="0" cellpadding="0">
                <thead>
                    <th> Item Number</th>

                    <th> Description</th>

                    <th> Category 1</th>

                    <th> Category 2</th>

                    <th> Category 3</th>

                    <th> Category 4</th>

                    <th> Category 5</th>
                    <th> Category 6</th>
                    <th> Category 7</th>
                    <th> Category 8</th>
                    <th> Category 9</th>
                    <th> Category 10</th>
                    

                    <th> <?=$this->lang->line('application_action');?></th>
                </thead>
                <tbody>
                    <?php foreach($items as $row){?>
                        <tr>
                        <td><?php echo $row['item_number']?></td>
                        <td><?php echo $row['item_desc']?></td>
                        <td><?php echo $row['cat1']?></td>
                        <td><?php echo $row['cat2']?></td>
                        <td><?php echo $row['cat3']?></td>
                        <td><?php echo $row['cat4']?></td>
                        <td><?php echo $row['cat5']?></td>
                        <td><?php echo $row['cat6']?></td>
                        <td><?php echo $row['cat7']?></td>
                        <td><?php echo $row['cat8']?></td>
                        <td><?php echo $row['cat9']?></td>
                        <td><?php echo $row['cat10']?></td>
                        <td class="option" width="8%">
                        <button type="button" class="btn-option delete po" data-toggle="popover" data-placement="left" data-content="<a class='btn btn-danger po-delete ajax-silent' href='<?=base_url()?>item_setup/item_delete/<?=$row['item_number'];?>'><?=$this->lang->line('application_yes_im_sure');?></a> <button class='btn po-close'><?=$this->lang->line('application_no');?></button> <input type='hidden' name='td-id' class='id' value='<?=$row['item_number'];?>'>"
                         data-original-title="<b><?=$this->lang->line('application_really_delete');?></b>">
                            <i class="icon dripicons-cross"></i>
                        </button>
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
    //     var tableData = [];
    //         $.fn.dataTable.ext.search.push(
    // function( settings, data, dataIndex ) {
    //     tableData.push(data);
    // });
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