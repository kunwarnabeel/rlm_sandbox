
<?php

$attributes = ['class' => '', 'id' => 'user_form'];

?>




    <div style="display:none">
    </div>
      <?php 
    
$attributes = ['class' => '', 'id' => 'user_form','novalidate='=>'true','accept-charset'=>'utf-8'];

echo form_open_multipart($form_action, $attributes);?> 
    <input type="hidden" name="action" id="action" value="<?=$action?>">
   
<?php if(isset($userToAdd) && $userToAdd->id != $this->user->id) {?>
       <div class="form-group filled">
        <label>
           User Type
        </label>
        <select name="type" id="type" style="width: 100%; display: none;" class="chosen-select">
            <option value="Administrator" 
            <?php 
            if(isset($userToAdd) && $userToAdd->type=='Administrator') {
           echo 'selected="true"';} ?> >Administrator</option>
            <option value="Agent"  <?php 
            if(isset($userToAdd) && $userToAdd->type=='Agent') {
           echo 'selected="true"';} ?>   >Agent</option>
        </select>
    </div> 
<?php }else{?>
  <input type="hidden" name="type" value="<?php if(isset($userToAdd)){ echo $userToAdd->type;}?>">
<?php }?>


    <div class="form-group">
        <label for="personname">Person Name *</label>
        <input id="personname" type="text" name="personname" class="required form-control" value="<?php if (isset($userToAdd)) {
           echo $userToAdd->personname;

        } ?>" required="required">
    </div>


       <div class="form-group">
        <label for="email">Email *</label>
        <input id="email" type="email"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" name="email" class="required email form-control" value="<?php if (isset($userToAdd)) {
            echo $userToAdd->email;
        } ?>" required="required">
    </div>



<?php 
        $ShowPassword='';
    if($action=='update'): 
        $ShowPassword='style=display:none;';
        ?>
        
    <div class="form-group filled"  >
        <label for="status">Change Password </label>
        <select name="" id="WanttoChangePassword" style="width: 100%; display: none;" class="chosen-select">    <option value="No" >  No </option>
            <option value="Yes" > Yes</option>
            
        </select>
    </div>

<?php endif ?>
    
    <div class="password_Div">
    <small  style="color:red" ><b>* Password must have minimum 6 characters </b> </small>
    <div class="form-group">
        <label for="password">Password *</label>
        <input id="password" type="password" name="password" class="form-control" minlength="6" value=""  required="">
    </div>
  </div>

    <div class="form-group password_Div"  >
        <label for="password">Confirm Password *</label>
        <input id="confirm_password" type="password" name="confirm_password" class="form-control" value="" data-match="#password" required="">
    </div>
    

<?php if(isset($userToAdd) && $userToAdd->id != $this->user->id) {?>
    <div class="form-group filled">
        <label for="status">Status</label>
        <select name="status" style="width: 100%; display: none;" class="chosen-select">
            <option value="active"    <?php  if(isset($userToAdd) && $userToAdd->status=='active' ){echo 'selected="selected"';} ?>   >Active</option>
            <option value="inactive"  <?php  if(isset($userToAdd) && ($userToAdd->status=='inactive' || $userToAdd->status=='deleted') ){echo 'selected="selected"';} ?>   >Inactive</option>
        </select>
    </div>
    <?php }else{?>
  <input type="hidden" name="status" value="<?php if(isset($userToAdd)){ echo $userToAdd->status;}?>">
<?php }?>


    <div class="modal-footer">
        <input type="submit" name="send" class="btn btn-primary" value="Save" >
        <a class="btn" data-dismiss="modal">Close</a>
    </div>

</form>

<script src="<?php echo base_url()?>assets/blueline/js/plugins/select2.min.js?>"></script> 
<link rel="stylesheet" href="<?php echo base_url()?>assets/blueline/css/plugins/select2.min.css?>"/>
<script type="text/javascript">
    $(document).ready(function() {
      
   $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});










   if($('#action').val()=='update'){
    $('#confirm_password').removeAttr('required');
    $('#password').removeAttr('required');
    $('.password_Div').hide();
    $("#personname").focus();
     $("#personname").focusout();  
   }


   if($('#action').val()=='insert'){
   // alert("here");
    $('.checkbox').attr('checked',true);
    }
    
     $('#WanttoChangePassword').on('change', function (e) {
         var optionSelected = $("#WanttoChangePassword option:selected", this);
    var valueSelected = this.value;
   // alert("yes"+valueSelected);
          if( valueSelected=='Yes')
            {   
               $('.password_Div').show(); 
               $('#password').attr('required','required');
               $('#confirm_password').attr('required','required');
            }
        else{
                $('.password_Div').hide(); 
                $('#confirm_password').removeAttr('required');
                $('#password').removeAttr('required');
            }
});



  // password validation

     $("#password").keyup(function(){
  if($("#confirm_password").val()=="" &&  $("#password").val()!=""){
    $("#save_user").addClass("disabled");

  }else{

  }
});


$("#password").change(function(){
    
  if($("#password").val()!=""){
    $("#confirm_password").attr("required","required");
      $("#confirm_password").addClass("required");   
  }else{
 $("#confirm_password").attr("required","required");
      $("#confirm_password").removeClass("required");  
  }
});
   





} ) // ended jquery 
</script>       


