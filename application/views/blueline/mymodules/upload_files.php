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
.orders{display: none}
.sales{display: none}
.activebtn{
background-color: #1178ed;
color: #fff;
pointer-events: none
}
.activebtn:focus{
color: #fff;
}
</style>
<div id="progressBar"><small id="progress">100%</small></div>
<div class="col-md-12 col-lg-12 box-shadow table-div">
    <div class="form-header">Upload File </div>
    <div class="row">
<div class="btn-group marginBottom col-md-4 col-md-offset-4" role="group" aria-label="Basic example">
  <button id="forecast" type="button" class="btn btn-secondary sf_btn togglebtn activebtn">Salesforce</button>
  <button id="orders" type="button" class="btn btn-secondary orders_btn togglebtn">Orders</button>
  <button id="sales" type="button" class="btn btn-secondary sales_btn togglebtn">Sales</button>
</div>
</div>
    <?php
    $attributes = ['class' => 'forecast', 'id' => $sf_action,'novalidate='=>'true','accept-charset'=>'utf-8'];
    echo form_open_multipart($sf_action, $attributes);
    ?>
    <!-- <div class="form-header">Upload Salesforce File </div> -->
    <div class="csvbtngroupdiv">
        <small style="color:red"><b> * The file size should not exceed 10 MB in a CSV format and filename should contain FORECAST keyword. </b> </small>
        <div class="form-group">
            <label for="csv_file">Upload Items <small>(<?php if(!empty($sf_last_uploaded)) echo 'Latest file uplaoded in '.$sf_last_uploaded;?>)</small></label>
            <div>
                <input class="form-control uploadFile" placeholder="Choose File" disabled="disabled"/>
                <div class="fileUpload btn btn-primary">
                    <span><i class="icon dripicons-upload"></i><span class="hidden-xs"> Select..</span></span>
                    <input type="file" name="csv_file"  class="upload uploadBtn" accept=".csv" />
                </div>
            </div>

        </div>

        <a href='<?php echo base_url()."assets/blueline/CSV_Samples/Sample_SALES_VOLUME_FORECAST.csv"; ?>'  class="label label-success label-important tt" title="" data-original-title="Download"> Download Sample CSV  </a>

        <a type="button"  class="btn btn-primary pull-right import_csv_btn" disabled="disabled" >Import CSV</a>
    </div> 

<?php echo form_close(); ?>
<!--salesforce file upload-->

    <?php
    $attributes = ['class' => 'orders', 'id' => $order_action,'novalidate='=>'true','accept-charset'=>'utf-8'];
    echo form_open_multipart($order_action, $attributes);
    ?>
    <!-- <div class="form-header">Upload Orders File </div> -->
    <div class="csvbtngroupdiv">
        <small style="color:red"><b> * The file size should not exceed 10 MB in a CSV format and filename should contain ORDERSACTUAL keyword. </b> </small>
        <div class="form-group">
            <label for="csv_file">Upload Items <small>(<?php if(!empty($order_last_uploaded)) echo 'Latest file uplaoded in '.$order_last_uploaded;?>)</small></label>
            <div>
                <input class="form-control uploadFile" placeholder="Choose File" disabled="disabled"/>
                <div class="fileUpload btn btn-primary">
                    <span><i class="icon dripicons-upload"></i><span class="hidden-xs"> Select..</span></span>
                    <input type="file" name="csv_file"  class="upload uploadBtn" accept=".csv" />
                </div>
            </div>

        </div>

        <a href='<?php echo base_url()."assets/blueline/CSV_Samples/Sample_OrdersActual_custom_orders.csv"; ?>'  class="label label-success label-important tt" title="" data-original-title="Download"> Download Sample CSV  </a>

        <a type="button"  class="btn btn-primary pull-right import_csv_btn" disabled="disabled" >Import CSV</a>
    </div> 
<?php echo form_close(); ?><!--order file-->

    <?php
    $attributes = ['class' => 'sales', 'id' => $sales_action,'novalidate='=>'true','accept-charset'=>'utf-8'];
    echo form_open_multipart($sales_action, $attributes);
    ?>
    <!-- <div class="form-header">Upload Sales File </div> -->
    <div class="csvbtngroupdiv">
        <small style="color:red"><b> * The file size should not exceed 10 MB in a CSV format and filename should contain SALESACTUAL keyword. </b> </small>
        <div class="form-group">
            <label for="csv_file">Upload Items <small>(<?php if(!empty($sales_last_uploaded)) echo 'Latest file uplaoded in '.$sales_last_uploaded;?>)</small></label>
            <div>
                <input class="form-control uploadFile" placeholder="Choose File" disabled="disabled"/>
                <div class="fileUpload btn btn-primary">
                    <span><i class="icon dripicons-upload"></i><span class="hidden-xs"> Select..</span></span>
                    <input type="file" name="csv_file"  class="upload uploadBtn" accept=".csv" />
                </div>
            </div>

        </div>

        <a href='<?php echo base_url()."assets/blueline/CSV_Samples/Sample_SalesActual_custom_sales.csv"; ?>'  class="label label-success label-important tt" title="" data-original-title="Download"> Download Sample CSV  </a>

        <a type="button"  class="btn btn-primary pull-right import_csv_btn" disabled="disabled" >Import CSV</a>
    </div> 

<?php echo form_close(); ?>
</div><!--sales file upload-->


    <div id="AreaOfModal">
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close closeModal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            </div>
            <div class="modal-body">
               <div id="dvCSV"></div>

           </div>
           <div class="modal-footer">
             <!--<button type="submit" class="btn btn-primary" id="btncopyCSVdata" value="Proceed" disabled="disabled" >Proceed </button>--> 
             <button type="button" class="btn btn-default closeModal" data-dismiss="modal">Close</button>

         </div>
     </div>
 </div>
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
$(document).on("change", '.uploadBtn', function (e) {
               var value = $( this ).val();
                 $(this).closest('form').find(".uploadFile").val(value);
            });
    $(function()
    {
        var pbar = $('#progressBar'), currentProgress = 0;
        function trackUploadProgress(e)
        {
            currentProgress = 0;
            $(pbar).width('0%');
            if(e.lengthComputable)
            {
                currentProgress = (e.loaded / e.total) * 100; // Amount uploaded in percent
                $(pbar).width(currentProgress+'%');
                //$(progress).css({"margin-left":currentProgress-2+'%'});

                if( currentProgress == 100 )
                    console.log('Progress : 100%');
            }
        }

        function uploadFile(thisForm)
        {
            var formdata = new FormData($(thisForm)[0]);
            var ajaxURL = $(thisForm).attr('id');
            $.ajax(
            {   
                url:"<?php echo base_url(); ?>"+ajaxURL,
                type:'post',
                data:formdata,
                xhr: function()
                {
                        // Custom XMLHttpRequest
                        var appXhr = $.ajaxSettings.xhr();

                        // Check if upload property exists, if "yes" then upload progress can be tracked otherwise "not"
                        if(appXhr.upload)
                        {
                            // Attach a function to handle the progress of the upload
                            appXhr.upload.addEventListener('progress',trackUploadProgress, false);
                        }
                        return appXhr;
                    },
                    success:function(data){
                        $('.loader').hide();
                        if(data == 'success'){
                            var filename = $(thisForm).find('input[type=file]')[0].files[0].name;
                            $("#dvCSV").html('<div class="alert alert-success"> File: <b>'+filename+'</b> imported successfully!</div>');
                            setTimeout(function(){ 
                             $('#myModal').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                             $('#import_csv_btn').html('IMPORT CSV');
                             $('#import_csv_btn').attr('disabled', true);
                             $("form")[0].reset();

                             $('#progressBar').css("background-color","#0eca25");
                             $('#progress').show();
                         }, 2600);
                        }
                        else if(data == 'headerError'){
                            $("#dvCSV").html('<div class="alert alert-danger"> <strong>Error!</strong> Incorrect headers, please review the CSV file OR download the sample CSV.</div>');
                            setTimeout(function(){ 
                             $('#myModal').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                             $('#import_csv_btn').html('IMPORT CSV');
                             $('#import_csv_btn').attr('disabled', true);
                             $("form")[0].reset();
                         }, 2600);
                        }
                        else if(data == 'wrongfile')
                        {
                             $("#dvCSV").html('<div class="alert alert-danger"> <strong>Error!</strong> Wrong file, File should contain appropriate name and format.</div>');
                            setTimeout(function(){ 
                             $('#myModal').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                             $('#import_csv_btn').html('IMPORT CSV');
                             $('#import_csv_btn').attr('disabled', true);
                             $("form")[0].reset();
                         }, 2600);
                        }
                        else{
                            $("#dvCSV").html(data);
                            setTimeout(function(){ 
                             $('#myModal').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                             $('#import_csv_btn').html('IMPORT CSV');
                             $('#import_csv_btn').attr('disabled', true);
                             $("form")[0].reset();
                         }, 2600);
                        }
                    },
                    error:function(){
                        $('#progress').hide();
                    },
                    contentType:false,
                    processData: false
                })
        }

        $('.import_csv_btn').on('click',function(e)
        {   
            e.preventDefault();
            var ErrorCount = 0;
            $uploadBtn = $(this).closest('form').find(".uploadBtn").val();
            if($uploadBtn!="") 
            {
                e.preventDefault();
                $(pbar).width(0).addClass('active');
                var ext = $uploadBtn.split('.').pop().toLowerCase();
                if (ext == 'csv') {   
                    $(this).html('Importing...');
                    $(this).attr('disabled', true);
                    $(".loader").show();
                    uploadFile($(this).closest('form'));
                }
                else{
                    alert('File name or type is wrong');
                    $('#progress').hide();
                }
            }
        });
    })



</script>
<script>
    jQuery(document).on('change', '.uploadBtn', function() {
       if($(this).val()!="")
       {
        if(this.files[0].size > 10000000){
            alert("File size should be less than 10MB");
            $(this).closest('form').reset();
        }
        else{
            $(this).closest('form').find(".import_csv_btn").attr('disabled',false);
        }        
    }   
});
</script>


<script type="text/javascript">
    $(window).load(function() {
        $(".loader").fadeOut("slow");
    });
    $('.togglebtn').on('click',function(){
        $('.togglebtn').removeClass('activebtn');
        $(this).addClass('activebtn')
        $('form').hide();
        $('.'+ $(this).attr('id')).show();
    })
</script>