<style>

    #dvCSV table tr:nth-child(1) {
       background-color: #f2f2f2;
       font-weight: bold;
   }

   #dvCSV table tr td {
      padding-left: 5px;
      padding-right: 5px;
      text-align: center;
  }

  #dvCSV  table>tbody>tr>td{
    border-top: 1px solid #eee;
    border:solid 1px;
}

#AreaOfModal .modal-content{
    width:150%;
}
#AreaOfModal .modal{
    position: fixed;
    left: -235px;
}
.import_csv {
    padding: 10px 10px 10px 10px;
    background: #ffffff;
    box-shadow: 0 4px 6px rgba(50,50,93,.11), 0 1px 3px rgba(0,0,0,.08);
}
.csvbtngroupdiv{
    margin-left: 12px;
    margin-right: 12px;
}
#import_csv_btn{
    color: #fff;
    background-color: #0f6c90 !important;
    float: right;
    border-color: #0f6c90;
    margin-bottom: 20px;
}
.table-div{
    padding-bottom: 25px
}
.fieldWithError
{
    margin-bottom: 0px;
}
.marginBottom
{
    margin-bottom: 20px;
}
.fieldError
{
    display: none;
    color: red;
    font-weight: 700;
    font-size: 13px
}
#error_table td{
 border: 1px solid #dce6f1;
}

</style>
<div class="col-md-12 col-lg-12 box-shadow table-div">
    <div id="progressBar"><small id="progress">100%</small></div>
    <?php
    $attributes = ['class' => '', 'id' => 'import_csv','novalidate='=>'true','accept-charset'=>'utf-8'];
    echo form_open_multipart($insert_action, $attributes);
    ?>
    <div class="form-header">Report Template Setup  </div>
        <div class="row">
        <div class="col-md-3">
            <div class="form-group fieldWithError">
                <label title="Please add category">
                    Report Name
                </label>
                <input type="text" name="report_name" required="true" id="report_name" value="" class="form-control" placeholder="">
            </div>
            <span class="fieldError">This field is required</span>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <h4>Set Columns</h4>
        </div>
    </div>
        <div class="row">
        <?php foreach($columns_list as $key=>$row):?>
        <div class="col-md-2">
            <div class="marginBottom">
                <div class="fieldWithError">
                    <input type="checkbox" name="selected_columns[]" value="<?=$key?>" class="custom-control-input selectedcolumn"> <?=$key?>
                </div>
            </div>
        </div>
        <?php endforeach ?>
        <div class="col-md-2">
            <div class="marginBottom">
                <div class="fieldWithError">
                    <input type="checkbox" name="selected_columns[]" value="sws_part_num" class="custom-control-input selectedcolumn"> sws_part_num
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4>Set Conditions</h4>
        </div>
    </div>
    <div id="conditionsec">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group fieldWithError">
                <label title="Please add category">
                    Select field
                </label>
                <select name="condition_field[]" style="width: 246px;height: 20px" class="">
                                    <option selected='true'  value=""> Please Select </option>
                                    <?php foreach($columns_list as $key=>$row):?>
                                        <option value="<?= $key?>"><?php echo $key; ?></option>
                                    <?php endforeach ?>
                                </select>
            </div>
            <span class="fieldError">This field is required</span>
        </div>
        <div class="col-md-3">
            <div class="form-group fieldWithError">
                <label title="Please add category">
                    Select condition
                </label>
                <select name="condition[]" style="width: 246px;height: 20px" class="">
                                    <option selected='true'  value=""> Please Condition </option>
                                    <option value=">">greater than</option>
                                    <option value="=">equals to</option>
                                    <option value="<">less than</option>
                                </select>
            </div>
            <span class="fieldError">This field is required</span>
        </div>

        <div class="col-md-3">
            <div class="marginBottom">
                <div class="form-group fieldWithError">
                    <label title="Please add the part description">
                        Value
                    </label>
                    <input type="text" name="condition_value[]" value="" class="form-control" placeholder="">
                </div>
                <span class="fieldError">This field is required</span>
            </div>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-md-offset-9 col-md-3">
            <a href="#" id="addcondition" class="btn btn-success addcondition">Add condition</a>
        </div>
    </div>
<div class="row">
        <div class="col-md-12">
            <hr>
            <button class="btn btn-primary" >Save</button>
            <!-- <input type="Submit" value="Save" class="btn btn-primary" /> -->
        </div>
    </div>
    <?php echo form_close(); ?>
</div>


<div class="col-md-12 col-lg-12 table-div box-shadow">
    <?php
    $attributes = ['class' => '', 'id'=>'insert_data'];
    echo form_open($form_action, $attributes);
    ?>
    <div class="form-header">
        List of Reports 
    </div>
    <!-- single row ending after notes filed  -->

<div id="row">
    <div class="table-div responsive padding-top-xs">

            <table id="item_tbl" class="item_tbl table" cellspacing="0" cellpadding="0">
                <thead>
                    <th> Report</th>

                    <th> Last Exported Date</th>

                    <th> <?=$this->lang->line('application_action');?></th>
                </thead>
                <tbody>
                    <?php foreach($reports as $row){?>
                        <tr>
                        <td><?php echo $row['report_name']?></td>
                        <td><?php if(!empty($row['last_exported_date']) && $row['last_exported_date'] != Null) echo date('Y-m-d',strtotime($row['last_exported_date'])); else echo $row['last_exported_date'];?></td>
                        <td class="option" width="8%">
                            
                         <a class="btn-option" href='<?=base_url()?>report_builder/delete_template?id=<?=$row['id'];?>'>
                            <i class="icon dripicons-cross"></i>
                        </a>
                        <a class="btn-option" href='<?=base_url()?>report_builder/export?id=<?=$row['id'];?>'>
                            <i class="icon dripicons-download"></i>
                        </a>
                    </td>
                        </tr>
                        <?php }?>                    
                </tbody>
            </table>
        </div>
    </div>

<style>
    thead input {
        width: 100%;
    }
    .import_csv {
        padding: 10px 10px 10px 10px;
        background: #ffffff;
        box-shadow: 0 4px 6px rgba(50,50,93,.11), 0 1px 3px rgba(0,0,0,.08);
    }

    #progress{
        margin-top: 6px;
        margin-left: 98%;
        display: none;
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


<script>

$('body').on('click','.closeModal',function(){
    window.location.reload();
})
    jQuery(document).on('change', '#uploadBtn', function() {

       // alert("yes");
       if($(this).val()!="")
       {
        if(this.files[0].size > 1000000){
            alert("File size should be less than 1MB");
        }
        else{
            $("#import_csv_btn").attr('disabled',false);
        }        
    }   
});
</script>


<script type="text/javascript">
    $(window).load(function() {
        $(".loader").fadeOut("slow");
    });
</script>

<script type="text/javascript">
    $(window).load(function() {
        $(".loader").fadeOut("slow");
    });
    $(document).ready(function() {
    var conditionhtml = $('#conditionsec').html();
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
        pageLength: 10,
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
    $('.addcondition').on('click',function(e){
        e.preventDefault();        
        console.log(conditionhtml)
        $('#conditionsec').append(conditionhtml)
    })
} )

    $('.itemsubmitbtn').on('click',function(e){
        e.preventDefault();
        var customer = $(this).closest('form').find('#customer').val();
        var plant_num = $(this).closest('form').find('#plant_num').val();
        var part_num = $(this).closest('form').find('#part_num').val();
        var sws_part_num = $(this).closest('form').find('#sws_part_num').val();

        if(customer == '' || plant_num == '' || part_num == '' || sws_part_num == ''){
            alert('All fields are required');
            return false;}
            var postData = {customer_num:customer,plant_num:plant_num,part_num:part_num,sws_part_num:sws_part_num};
        $.ajax({
            url:"<?php echo base_url(); ?>plant_part_setup/insert_plant_part",
            method:"POST",
            data: postData,
           // beforeSend:function(){
           //  $('#import_csv_btn').html('Importing...');
           // },
           success:function(data)
           {
            console.log(data)
                if(data.status == "success")
                {
                    window.location.reload();
                }
                else{
                    alert(data.message)
                }
            }
            });     
    })

</script>