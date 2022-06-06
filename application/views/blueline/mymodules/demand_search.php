<style type="text/css">
    #table_wrap{
            position: absolute;
    top: 50px;
    left: 0px;
    width: 100%;
    height: 600px;
    background: #fff;
    z-index: 1;
    }
    #table_wrap .loading {
  height: 0;
  width: 0;
  padding: 15px;
  border: 6px solid #ccc;
  border-right-color: #888;
  border-radius: 22px;
  -webkit-animation: rotate 1s infinite linear;
  /* left, top and position just for the demo! */
  position: absolute;
  left: 50%;
  top: 50%;
}

@-webkit-keyframes rotate {
  /* 100% keyframe for  clockwise. 
     use 0% instead for anticlockwise */
  100% {
    -webkit-transform: rotate(360deg);
  }
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
<div class="col-md-12 col-lg-12 box-shadow table-div">
  <div class="table-head" style="height: 70px">
            <div class="col-md-8">
            <b><?= $title?></b> Demand Search
            </div>
            <div class="col-md-4">
                <a style="margin-top:30px" id="export_search" class="pull-right btn btn-primary">Export</a>
            </div>
    </div>
  <div class="row margin-top-xs" >
                    <div class="btn-group margin-right-3 margin-left-md">
                        <div class="form-group">
                            <label for="search_Start_date">Start Date </label>
                            <input id="search_Start_date" type="text" name="search_Start_date" class="datepicker-linked form-control" value="<?php  if(isset($_GET['start_date'])) { echo$_GET['start_date']; }else{ echo date('Y-m-01'); } ?>"  />
                        </div>
                    </div>
                    <div class="btn-group margin-right-3 ">
                        <div class="form-group">
                            <label for="search_end_date">End Date </label>
                            <input id="search_end_date" type="text" name="search_end_date" class="datepicker-linked form-control" value="<?php echo date('Y-m-31',strtotime('+1 month')); ?>"  />
                            <input type="hidden" id="apply_filer" name="apply_filer"  value="<?php if(isset($_GET['end_date'])) { echo "1"; }else{ echo "0"; } ?>" >
                        </div>
                    </div>
                    <div class="btn-group margin-right-3 ">
                        <div class="form-group no-border" style=" border: none; padding-top:10px;">
                            <button type="button" class="btn btn-danger"  id="remove_filter"> Remove Filter</button>
                            <button type="button" class="btn btn-primary"  id="apply_filter"> Apply Filter</button>
                        </div>
                    </div>
                </div>
  <div class="row">
  	<div class="table-div responsive padding-top-xs">
        <div id="table_wrap" class="text-center">
            <div class="loading"></div>
            <span>Data loading please wait</span>
        </div>
			<table id="payment_tbl" class="payment_tbl table" cellspacing="0" cellpadding="0">

				<thead>


                    <!-- <th class="backgrey"> Type</th> -->
                    <th class="backgrey"> Customer</th>
                    <th class="backgrey"> Customer #</th>

                    <th class="backgrey"> Item Number</th>

                    <th class="backgrey"> Category 1-10(use space as a seperator)</th>
                    <th class="backgrey"> Data Date</th>

				</thead>
				<tbody>
	 				<?php foreach ($items as $row): ?>
					<tr>
                        <!-- <td><?=$row['type']?></td> -->
                        <td></td>
                        <td></td>
						<td><?php echo $row['item_number'] ?></td>
						<td><?php echo $row['cat1'];
                        if(!empty($row['cat2'])) echo ' - '.$row['cat2'];
                        if(!empty($row['cat3'])) echo ' - '.$row['cat3'];
                        if(!empty($row['cat4'])) echo ' - '.$row['cat4'];
                        if(!empty($row['cat5'])) echo ' - '.$row['cat5'];
                        if(!empty($row['cat6'])) echo ' - '.$row['cat6'];
                        if(!empty($row['cat7'])) echo ' - '.$row['cat7'];
                        if(!empty($row['cat8'])) echo ' - '.$row['cat8'];
                        if(!empty($row['cat9'])) echo ' - '.$row['cat9'];
                        if(!empty($row['cat10'])) echo ' - '.$row['cat10'];
                        ?>
                        </td>
                        <td><?=$row['period']?></td>
					</tr>
					<?php endforeach ?>
				</tbody>
				

			</table>
		</div>
  </div>
</div>







<script type="text/javascript">
    
$(document).ready(function() {

    var searchArray = [];
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
                    searchArray = [];
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );

    } );
    function monthDiff(d1, d2) {
    var months;
    months = (d2.getFullYear() - d1.getFullYear()) * 12;
    months -= d1.getMonth() + 1;
    months += d2.getMonth();
    months +=1;
    return months <= 0 ? 0 : months;
}
var reset = false;
    var oldMin = $('#search_Start_date').val();
    var oldMax = $('#search_end_date').val();
    var firstFlag = true;
    $.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {        
        var m_names = new Array("Jan", "Feb", "Mar", 
"Apr", "May", "Jun", "Jul", "Aug", "Sep", 
"Oct", "Nov", "Dec");
        var min = $('#search_Start_date').val();
        var max = $('#search_end_date').val();
        if((oldMin == min && oldMax==max) && firstFlag == true){
            temp = [data[1],data[2],data[4]];
            searchArray.push(temp);
            return true;
        }
        var formattedDate1 = new Date(min);
        var m1 =  formattedDate1.getMonth();
        var y1 = formattedDate1.getFullYear();

        var formattedDate2 = new Date(max);
        var m2 =  formattedDate2.getMonth();
        var y2 = formattedDate2.getFullYear();
        var startdate = m_names[m1] + " " + y1;
        var enddate = m_names[m2] + " " + y2;
        var month_diff = monthDiff(formattedDate1, formattedDate2);
        var dates_between = [];
        if(month_diff == 0 || reset==true){
            if(reset == false){
                alert('please select correct date range');
                reset = true;
            }
            temp = [data[1],data[2],data[4]];
            searchArray.push(temp);
            return true}
        for($i=1;$i<=month_diff;$i++){
            var year =y1;
            var month = m1 + ($i+1);
            if(month>12)
            {
                year = y1+1;
            }
            if(month<13)
                dates_between.push(m_names[(month-1)] + " " + year);
                else    
                    dates_between.push(m_names[(month-13)] + " " + year);
        }
        dates_between.push(m_names[m1] + " " + y1);
        var data_date = data[4] || 0; // use data for the age column
        if($.inArray(data_date, dates_between)!=-1 || reset==true)
        {
            temp = [data[1],data[2],data[4]];
            searchArray.push(temp);
            return true;
        }
        return false;
    }
);

    var table = $('.payment_tbl').DataTable( {
        orderCellsTop: true,
        fixedHeader: true,
        lengthMenu: [[10, 25, 50, 100,-1], [10, 25, 50,100, "All"]],
        pageLength: defaulnumberofpages,
    } );

    $('#apply_filter').on('click', function() {
        searchArray = [];
        reset = false;
        firstFlag = false;
        table.draw();
    } );
    $('#remove_filter').on('click',function(){
        reset = true;
        table.draw();
    })

$('#export_search').on('click',function(){    
    tableLength = table.$('tr', {"filter":"applied"}).length;
    if(tableLength === 0 || searchArray.length === 0){
        alert('no search result to export')
    }
    else{
    var jsonString = JSON.stringify(searchArray);
   $.ajax({
        type: "POST",
        url: "<?=site_url().$export_url?>",
        data: {'data' : jsonString}, 
        cache: false,

        success: function(data){
            if(data == 'success'){
                window.open('<?=site_url()?>demand_search/export_csv');
            }   
            else{
                alert('something went wrong');
            }
        }
    });
   }
})
} )

$(window).load(function(){
    setTimeout(function(){ $('#table_wrap').hide();
}, 2600);
})

</script>

<style type="text/css">
    thead input {
        width: 100%;
    }
    thead tr td{
        width: 11%;
    }

</style>