<?php $attributes = ['class' => 'form-signin box-shadow', 'role' => 'form', 'id' => 'login']; ?>
<?=form_open('login', $attributes)?>
        <div class="logo"><img src="<?=base_url()?><?php if ($core_settings->login_logo == '') {
    echo $core_settings->invoice_logo;
} else {
    echo $core_settings->login_logo;
}?>" alt="<?=$core_settings->company;?>">
  <h3 style="font-weight: 700;color: #9cbdd8"><span style="color:#074a82;">S</span>ales <span style="color:#074a82;">F</span>orecast Evaluation <span style="color:#074a82;">Portal</span></h3>
</div>
        <?php if ($error == 'true') {
    $message = explode(':', $message)?>
            <div id="error">
              <?=$message[1]?>
            </div>
        <?php
} ?>
        
          <div class="form-group">
            <label for="username"><?=$this->lang->line('application_email');?></label>
            <input type="username" class="form-control" id="username" name="username" placeholder="<?=$this->lang->line('application_enter_your_email');?>" />
          </div>
          <div class="form-group">
            <label for="password"><?=$this->lang->line('application_password');?></label>
            <input type="password" class="form-control" id="password" name="password" placeholder="<?=$this->lang->line('application_enter_your_password');?>" />
          </div>

          <input 
              type="submit" 
              id="recaptcha-submit"
              value="<?=$this->lang->line('application_login');?>" 
              class="btn btn-primary fadeoutOnClick"
          />
          <div class="forgotpassword"><a href="<?=site_url('forgotpass');?>"><?=$this->lang->line('application_forgot_password');?></a></div>
           
          </div>
          

<?=form_close()?>

