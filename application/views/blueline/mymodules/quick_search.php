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
            <!-- <b><?= $title?></b>  -->
            Quick Search
            </div>
            <div class="col-md-4">
                <!-- <a style="margin-top:30px" id="export_search" class="pull-right btn btn-primary">Export</a> -->
            </div>
    </div>
  <div class="row margin-top-xs quicksearchform" >
                    <div class="btn-group margin-right-3 margin-left-md">
                        <div class="form-group">
                            <label for="search_Start_date">Select Plant Number </label>
                            <select name="plant_num" id="plant_num" style="width: 250px;" class="chosen-select">
                                    <option selected='true'  value=""> Any </option>
                                    <?php foreach($plants as $data):?>
                                        <option value="<?= $data->plant_num?>"><?php echo $data->plant_num; ?></option>
                                    <?php endforeach ?>
                                </select>
                        </div>
                    </div>
                    <div class="btn-group margin-right-3 margin-left-md">
                        <div class="form-group">
                            <label for="search_Start_date">Select Part Number </label>
                            <select name="part_num" id="part_num" style="width: 250px;" class="chosen-select">
                                    <option selected='true'  value=""> Any </option>
                                    <?php foreach($parts as $data):?>
                                        <option value="<?= $data->part_num?>"><?php echo $data->part_num; ?></option>
                                    <?php endforeach ?>
                                </select>
                        </div>
                    </div>
                    <div class="btn-group margin-right-3 margin-left-md">
                        <div class="form-group">
                            <label for="search_Start_date">Select Import Date </label>
                            <select name="import_date" id="import_date" style="width: 250px;" class="chosen-select">
                                    <option selected='true'  value=""> Select Date </option>
                                    <?php foreach($import_dates as $data):?>
                                        <option value="<?= $data->import_date?>"><?php echo $data->import_date; ?></option>
                                    <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="btn-group margin-right-3 margin-left-md">
                        <div class="form-group">
                            <label for="search_Start_date">Select Lead </label>
                            <select name="lead_time" id="lead_time" style="width: 250px;" class="chosen-select">
                                    <option selected='true'  value="4"> 4 weeks </option>
                                    <option  value="6"> 6 weeks </option>
                                    <option  value="8"> 8 weeks </option>
                                    <!-- <?php foreach($rel_dates as $data):?>
                                        <option value="<?= $data->rel_date?>"><?php echo $data->rel_date; ?></option>
                                    <?php endforeach ?> -->
                                </select>
                        </div>
                    </div>
                    <div class="btn-group margin-right-3  margin-left-md">
                        <div class="form-group no-border" style=" border: none; padding-top:10px;">
                            <button type="button" class="btn btn-danger"  id="remove_filter"> Reset Search</button>
                            <button type="button" class="btn btn-primary"  id="apply_filter"> Run</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php
                        $attributes = ['class' => '', 'id' => 'export_release','novalidate='=>'true','accept-charset'=>'utf-8'];
                        echo form_open($form_action, $attributes);
                        ?>
                    <div class="btn-group margin-right-3 margin-left-md">
                        <div class="form-group">
                            <label for="search_Start_date">Select Customer for Release </label>
                            <select name="customer_num" id="customer_num" style="width: 250px;" class="chosen-select">
                                    <?php foreach($customers as $data):?>
                                        <option value="<?= $data->customer_num?>"><?php echo $data->customer_name; ?></option>
                                    <?php endforeach ?>
                                </select>
                        </div>
                    </div>
                    <div class="btn-group margin-right-3  margin-left-md">
                        <div class="form-group no-border" style=" border: none; padding-top:10px;">
                            <button type="submit" class="btn btn-primary"> Export</button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
  <div class="row">
    <div class="table-div responsive padding-top-xs">
        <!--<div id="table_wrap" class="text-center">
            <div class="loading"></div>
            <span>Data loading please wait</span>
        </div>-->
            <table id="payment_tbl" class="payment_tbl table" cellspacing="0" cellpadding="0">

                <thead>
                </thead>
                <tbody>
                </tbody>
                

            </table>
        </div>
  </div>
</div>







<script type="text/javascript">
    
$(document).ready(function() {

    $('#apply_filter').on('click', function(e) {
                e.preventDefault();
        var part_num = $(this).closest('.quicksearchform').find('#part_num').val();
        var plant_num = $(this).closest('.quicksearchform').find('#plant_num').val();
        var import_date = $(this).closest('.quicksearchform').find('#import_date').val();
        var lead_time = $(this).closest('.quicksearchform').find('#lead_time').val();
        if(import_date == '' || plant_num == '' || lead_time == ''){
            alert('All fields are required');
            return false;}
            var postData = {part_num:part_num,plant_num:plant_num,import_date:import_date,lead_time:lead_time};

        $.ajax({
            url:"<?php echo base_url(); ?>quick_search/generate_report",
            method:"POST",
            data: postData,
           
           success:function(data)
           {
            console.log(data)
            html = '';
            periodsKey = (data.data.length)-1;
            headarr = [];
            headerindex = 0;
            lastval = 0;
            firstval = '';
            $.each(data.data,function(key,row){
                if(key<periodsKey){                
                    html+= '<tr><td>'+row["rel_num"]+'</td>';
                    html+= '<td>'+row["rlm_type"]+'</td>';
                        $.each(data.data[periodsKey][row["rlmkey"]],function(colkey,colrow){
                                html+= '<td>'+colrow+'</td>';
                                if(headerindex <= lead_time)
                                headarr[headerindex++]=colkey;        
                        })
                        if(firstval == '')
                            firstval = data.data[periodsKey][row["rlmkey"]][import_date];
                        currentval = data.data[periodsKey][row["rlmkey"]][import_date];
                        console.log(currentval)
                        RollDelta = 0;
                        baseDelta = 0;
                    if(key!=0){
                        RollDelta=(currentval - lastval);
                        baseDelta = (currentval - firstval)
                    }
                    else{
                        RollDelta = 0;
                    }
                    lastval = currentval;
                        console.log(lastval)
                    html+='<td>'+RollDelta+'</td>';
                    html+='<td>'+baseDelta+'</td>';
                    html+= '</tr>';
                }
            })
            headhtml = '';
            headhtml+='<tr><td>Rel Number</td>';
            headhtml+='<td>Rel Type</td>';
            console.log(headarr)
            $.each(headarr,function(key,row){
                headhtml+='<td>'+row+'</td>';
            })

            headhtml+='<td>Rolling Delta</td>';
            headhtml+='<td>Baseline Delta</td></tr>';
            $('#payment_tbl').find('thead').html('');
            $('#payment_tbl').find('thead').html(headhtml);
            $('#payment_tbl').find('tbody').html('');            
            $('#payment_tbl').find('tbody').html(html);
            }
            });     
    });
    $('#remove_filter').on('click',function(){
        window.location.reload();
    })
function search(nameKey, myArray){
    for (var i=0; i < myArray.length; i++) {
        if (myArray[i].name === nameKey) {
            return myArray[i];
        }
    }
}

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