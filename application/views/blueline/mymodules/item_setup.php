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
    echo form_open_multipart($upload_action, $attributes);
    ?>
    <input id="working_indexes" type="hidden" name="working_indexes" value=""/>
    <div class="form-header">Item setup  </div>
    <div class="csvbtngroupdiv">
        <small style="color:red"><b> * The file size should not exceed 1 MB in a CSV format. </b> </small>
        <div class="form-group">
            <label for="csv_file">Upload Items</label>
            <div>
                <input id="uploadFile" class="form-control uploadFile" placeholder="Choose File" disabled="disabled"/>
                <div class="fileUpload btn btn-primary">
                    <span><i class="icon dripicons-upload"></i><span class="hidden-xs"> Select..</span></span>
                    <input id="uploadBtn" type="file" name="csv_file"  class="upload" accept=".csv" />
                </div>
            </div>

        </div>

        <a href='<?php echo base_url()."assets/blueline/CSV_Samples/Sample_csv_items.csv"; ?>'  class="label label-success label-important tt" title="" data-original-title="Download"> Download Sample CSV  </a>

        <a type="button"  class="btn btn-info" id="import_csv_btn" disabled="disabled" >Import CSV</a>
    </div> 


    <div id="AreaOfModal">
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

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
<?php echo form_close(); ?>
</div>


<div class="col-md-12 col-lg-12 table-div box-shadow">
    <?php
    $attributes = ['class' => '', 'id'=>'insert_data'];
    echo form_open($form_action, $attributes);
    ?>
    <div class="form-header">
        Manually add items 
    </div>
    <!-- single row ending after notes filed  -->
    <div class="row">
        <div class="col-md-4">
            <div class="marginBottom">
                <div class="form-group fieldWithError">
                    <label title="Please add the part number">
                        Item Number *
                    </label>
                    <input type="text" name="item_num" id="item_num" value="" class="form-control" placeholder=""> 
                </div>
                <span class="fieldError">This field is required</span>
            </div>
        </div>

        <div class="col-md-8">
            <div class="marginBottom">
                <div class="form-group fieldWithError">
                    <label title="Please add the part description">
                        Item Description *
                    </label>
                    <input type="text" name="item_desc" id="item_desc" value="" class="form-control" placeholder="">
                </div>
                <span class="fieldError">This field is required</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label title="Please add category">
                    Category 1
                </label>
                <input type="text" name="cat_1" id="cat_1" value="" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label title="Please add category">
                    Category 2
                </label>
                <input type="text" name="cat_2" id="cat_2" value="" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label title="Please add category">
                    Category 3
                </label>
                <input type="text" name="cat_3" id="cat_3" value="" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label title="Please add category">
                    Category 4
                </label>
                <input type="text" name="cat_4" id="cat_4" value="" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label title="Please add category">
                    Category 5
                </label>
                <input type="text" name="cat_5" id="cat_5" value="" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label title="Please add category">
                    Category 6
                </label>
                <input type="text" name="cat_6" id="cat_6" value="" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label title="Please add category">
                    Category 7
                </label>
                <input type="text" name="cat_7" id="cat_7" value="" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label title="Please add category">
                    Category 8
                </label>
                <input type="text" name="cat_8" id="cat_8" value="" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label title="Please add category">
                    Category 9
                </label>
                <input type="text" name="cat_9" id="cat_9" value="" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label title="Please add category">
                    Category 10
                </label>
                <input type="text" name="cat_10" id="cat_10" value="" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-md-12">
            <hr>
            <a class="btn btn-primary itemsubmitbtn" >Save</a>
        </div>
    </div>
    <?php echo form_close(); ?>
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

        function uploadFile()
        {
            var formdata = new FormData($('#import_csv')[0]);
            $.ajax(
            {   
                url:"<?php echo base_url(); ?>item_setup/import_data",
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
                            var filename = $('input[type=file]')[0].files[0].name;
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
                            var filename = $('input[type=file]')[0].files[0].name;
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

        $('#import_csv_btn').on('click',function(e)
        {   
            e.preventDefault();            
            if($("#uploadBtn").val()!="") 
            {
                e.preventDefault();
                $(pbar).width(0).addClass('active');
                var ext = $('#uploadBtn').val().split('.').pop().toLowerCase();
                var ErrorCount = 0;
                if (ext == 'csv') {
                    
                                $('#import_csv_btn').html('Importing...');
                                $('#import_csv_btn').attr('disabled', true);
                                $(".loader").show();
                                uploadFile();
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

    function validateItem(){
        var temp = $('#item_num').val();
        if(temp.length == 8 && $.isNumeric(temp)){
            return true;
        }
        return false;
    }

    $('#item_num').on('keyup change',function(e){
        e.preventDefault();
        var temp = validateItem();
        if(temp){
            $(this).closest('.marginBottom').find('.fieldError').text('This field is required')
            $(this).closest('.marginBottom').find('.fieldError').hide()
        }
        else{
            $(this).closest('.marginBottom').find('.fieldError').text('8 digit numeric value allowed')
            $(this).closest('.marginBottom').find('.fieldError').show()
        }
    });
    $('.itemsubmitbtn').on('click',function(e){
        e.preventDefault();
        var temp = validateItem();
        if($('#item_num').val() == "")
        {
            $('#item_num').closest('.marginBottom').find('.fieldError').show()
        }
        else if($('#item_desc').val() == "")
        {
            $('#item_desc').closest('.marginBottom').find('.fieldError').show()
        }
        else if(temp)
        {
            $('#insert_data').submit();
        }

    })

</script>