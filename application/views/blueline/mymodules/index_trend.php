<div class="col-md-12 col-lg-12 box-shadow table-div">

  <div class="table-head" style="height: 70px">
            <div class="col-md-8">
            Accuracy Review / <b> Index Trend
            </div>
            <div class="col-md-4">
                <a style="margin-top:30px" id="metrics_key" class="pull-right btn btn-primary">Metrics Key</a>
            </div>
    </div>
  <div class="row">
  	<div class="table-div responsive padding-top-xs">

			<table id="payment_tbl" class="payment_tbl table" cellspacing="0" cellpadding="0">

				<thead>


                    <th class="backgrey"> Part Number</th>
                    
                    <th class="backgrey"> Description</th>

                    <th class="backgrey"> Category 1</th>

                    <th class="backgrey"> Category 2</th>

                    <th class="backgrey"> Category 3</th>

                    <th class="backgrey"> Category 4</th>

                    <th class="backgrey"> Category 5</th>
                    <th class="backgrey"> Category 6</th>
                    <th class="backgrey"> Category 7</th>
                    <th class="backgrey"> Category 8</th>
                    <th class="backgrey"> Category 9</th>
                    <th class="backgrey"> Category 10</th>
                    

					<th class="text-center">Trend</th>

				</thead>
				<tbody>
					<?php foreach ($items  as $row): ?>
					<tr>
						<td><?php echo $row->item_number ?></td>
                        <td><?php echo $row->item_desc ?></td>
						<td><?php echo $row->cat1 ?></td>
						<td><?php echo $row->cat2 ?></td>
						<td><?php echo $row->cat3 ?></td>
						<td><?php echo $row->cat4 ?></td>
						<td><?php echo $row->cat5 ?></td>
                        <td><?php echo $row->cat6 ?></td>
                        <td><?php echo $row->cat7 ?></td>
                        <td><?php echo $row->cat8 ?></td>
                        <td><?php echo $row->cat9 ?></td>
                        <td><?php echo $row->cat10 ?></td>
						<td width="13%"><a href="<?=base_url()?>accuracy_review/trends/<?=$row->item_number;?>" class="btn btn-primary" data-toggle="mainmodal">
                            View Trend
                        </a></td>
					</tr>
					<?php endforeach ?>
				</tbody>
				

			</table>
		</div>
  </div>
</div>

    <div id="AreaOfModal">
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            </div>
            <div class="modal-body">
             <div id="dvCSV">
               <img class="img img-responsive" src='<?php echo base_url()."assets/metricsKey.png"?>' />
             </div>

         </div>
         <div class="modal-footer">
           <!--<button type="submit" class="btn btn-primary" id="btncopyCSVdata" value="Proceed" disabled="disabled" >Proceed </button>--> 
           <button type="button" class="btn btn-default closeModal" data-dismiss="modal">Close</button>

       </div>
   </div>
</div>
</div>
</div>
<style type="text/css">
    .modal-dialog{
    width: 840px !important;
  }
	.backgrey{
        background: #fafafb;
    }
    #progressBar
    {
        width:0px;
        height:5px;
        background-color:#F44336;
        display:none;
    }

    #progressBar.active
    {
        display:block;
        transition: 3s linear width;
        -webkit-transition: 3s linear width;
        -moz-transition: 3s linear width;
        -o-transition: 3s linear width;
        -ms-transition: 3s linear width;
    }

</style>





<script type="text/javascript">
    
$(document).ready(function() {

  $('#metrics_key').on('click',function(e){
    e.preventDefault();
    $('#myModal').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
  })

$('input[type=radio][name=customRadio]').change(function() {
    if (this.value == 'all') {
        $('.mainbody').show();        
    }
    else if (this.value == 'global') {
        $('.mainbody').hide();   
    }
});

    $('.payment_tbl thead tr').clone(true).appendTo( '.payment_tbl thead' );
    $('.payment_tbl thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();


        if(title.trim()!="Trend")
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
            } );

    } );

    var table = $('.payment_tbl').DataTable( {
        orderCellsTop: true,
        fixedHeader: true,
        lengthMenu: [[10, 25, 50, 100,-1], [10, 25, 50,100, "All"]],
        pageLength: defaulnumberofpages,
    } );
    
} )



</script>

<style type="text/css">
    thead input {
        width: 100%;
    }
    thead tr td{
        width: 11%;
    }

</style>