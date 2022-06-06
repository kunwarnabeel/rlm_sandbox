<div id="row">
    <div class="col-md-9 col-lg-12">
        <div class="box-shadow">

        <div class="table-head" style="height: 70px">
            <div class="col-md-8">
            Import Log
            </div>
            <div class="col-md-2">
                <a style="margin-top:10px" href="<?=site_url()?>activities/export_all" class="btn btn-primary pull-right" >
                            Export All
                        </a>
            </div>
            <div class="col-md-2">
                <a style="margin-top:10px" href="<?=site_url()?>activities/export_rejected" class="btn btn-primary pull-right" >
                            Export Rejected
                        </a>
            </div>
        </div>

        <div class="table-div responsive padding-top-xs">

            <table id="item_tbl" class="item_tbl table" cellspacing="0" cellpadding="0">
                <thead>
                    <th> Import Status</th>
                    <th> Import date</th>

                    <th> Release Date</th>

                    <th> Customer</th>

                    <th> Release No.</th>

                    <th> Plant No.</th>

                    <th> Cust Part No.</th>

                    <th> SWS Part No.</th>
                    <th> Message</th>
                    
                </thead>
                <tbody>
                    <?php foreach($activities as $row){?>
                        <tr>
                        <td><?php echo $row['activity_status']?></td>
                        <td><?php echo $row['activity_date']?></td>
                        <td><?php echo $row['release_date']?></td>
                        <td><?php echo $row['customer_name']?></td>
                        <td><?php echo $row['release_num']?></td>
                        <td><?php echo $row['plant_num']?></td>
                        <td><?php echo $row['part_num']?></td>
                        <td><?php echo $row['sws_part_num']?></td>
                        <td><?php echo $row['message']?></td>
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
        lengthMenu: [[50, 100, 150, 200,-1], [50, 100, 150,200, "All"]],
        pageLength: 50,
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