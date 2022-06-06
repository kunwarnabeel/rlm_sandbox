<style>
  table td {
    position: relative;
    padding: 35px 5px!important
  }
  table th{
    background-color: #0f6c90;
    color:#fff;
  }

  .table_input {
    position: absolute;
    display: block;
    top:0;
    left:0;
    margin: 0;
    height: 100%;
    width: 100%;
    border: none;
    padding: 10px;
    box-sizing: border-box;
  }
  .marginBottom
  {
    margin-bottom: 20px;
  }
  .bad{
    background-color: <?php echo $metrics->color_bad?>;
    padding: 2px 25px;
  }
  .concern{
   background-color: <?php echo $metrics->color_concern?>; 
   padding: 2px 15px;
 }
 .good{
  background-color: <?php echo $metrics->color_good?>;
  padding: 2px 15px;
}
.limit_box{
  width: 16%
}
</style>
<div class="col-md-12 col-lg-12 box-shadow table-div">
  <?php
  $attributes = ['class' => '', 'id' => 'metrics_setup','novalidate='=>'true','accept-charset'=>'utf-8'];
  echo form_open($form_action, $attributes);
  ?>
  <div class="form-header">Metrics setup  </div>
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th class="col-md-2 text-center">Color</th>
            <th class="col-md-5  text-center">Range %</th>
            <th class="col-md-5 text-center">Label</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <input class="table_input label_color limit  text-center" name="color_good" type="text" placeholder="#99ff33" value="<?php echo $metrics->color_good?>" style="background-color: <?php echo $metrics->color_good?>">
            </td>
            <td>
              lower limit: <input type="number" id="lower_good" class="limit limit_box" name="lower_good" min="5" max="150" placeholder="50" value="<?php echo $metrics->lower_good*100 ?>"> 
              <span style="padding-left: 55px">
                upper limit: <input type="number" id="upper_good" class="limit limit_box" name="upper_good" min="5" max="150" placeholder="80" value="<?php echo $metrics->upper_good*100 ?>">
              </span>
            </td>
            <td>
              <input class="table_input limit  text-center" name="desc_good" type="text" value="<?php echo $metrics->desc_good?>" placeholder="Top range">
            </td>
          </tr><!--good range-->
          <tr>
            <td>
              <input class="table_input label_color limit text-center" name="color_concern" type="text" placeholder="#99ff33" value="<?php echo $metrics->color_concern?>" style="background-color: <?php echo $metrics->color_concern?>">
            </td>
            <td>
              lower limit: <input type="number" id="lower_concern" class="limit limit_box" name="lower_concern" min="5" max="150" placeholder="50" value="<?php echo $metrics->lower_concern*100 ?>"> 
              <span style="padding-left: 55px">
                upper limit: <input type="number" id="upper_concern" class="limit limit_box" name="upper_concern" min="5" max="150" placeholder="80" value="<?php echo $metrics->upper_concern*100 ?>">
              </span>
            </td>
            <td>
              <input class="table_input limit text-center" name="desc_concern" type="text" value="<?php echo $metrics->desc_concern?>" placeholder="Concern range">
            </td>
          </tr><!--concern range-->
          <tr>
            <td>
              <input class="table_input label_color limit text-center" name="color_bad" type="text" placeholder="#99ff33" value="<?php echo $metrics->color_bad?>" style="background-color: <?php echo $metrics->color_bad?>">
            </td>
            <td>
              lower limit: <span id="lower_bad"> < <?php echo $metrics->lower_concern*100;?></span>
              <span style="padding-left: 75px">
                upper limit: <span id="upper_bad"> > <?php echo $metrics->upper_concern*100;?></span>
              </span>
            </td>
            <td>
              <input class="table_input limit  text-center" name="desc_bad" type="text" value="<?php echo $metrics->desc_bad?>" placeholder="Bad range">
            </td>
          </tr><!--bad range-->
        </tbody>
      </table>
      <input type="submit" id="metrics_save" name="send" disabled class="btn btn-primary pull-right marginBottom" value="Save" >
    </div>
  </div>
  <div class="row">
    <div class="col-md-10 col-md-offset-2 marginBottom">
      <h5 class="" style="font-size: 12px;">
        <span style="padding-right: <?php echo ($metrics->lower_concern*445);?>px"><span>0</span></span>
        <span style="padding-right: <?php echo (($metrics->lower_good - $metrics->lower_concern)*445);?>px"> <span><?php echo $metrics->lower_concern?></span></span>

        <span style="padding-right: <?php echo (($metrics->upper_good - $metrics->lower_good) *445);?>px"><span ><?php echo $metrics->lower_good?></span></span>

        <span id="upper_good_range" style="padding-right: <?php echo ($metrics->upper_concern - $metrics->upper_good) *445;?>px"><span ><?php echo $metrics->upper_good?></span></span>

        <span id="upper_concern_range" style="padding-right: <?php echo $metrics->lower_concern - $metrics->lower_good *445;?>px"><span ><?php echo $metrics->upper_concern?></span></span>
        <span style="padding-left: 25px;font-size: 16px"> &infin;</span>
      </h5>

      <h4 class="">
        <span class="bad" style="padding-right: <?php echo ($metrics->lower_concern*450);?>px"></span>
        <span id="lower_concern_range" style="padding-right: <?php echo (($metrics->lower_good - $metrics->lower_concern)*450);?>px" class="concern"></span>
        <span id="lower_good_range" style="padding-right: <?php echo (($metrics->upper_good - $metrics->lower_good)*450);?>px" class="good"></span>

        <span id="upper_concern_range" style="padding-right: <?php echo (($metrics->upper_concern - $metrics->upper_good) *450);?>px" class="concern"></span>
        <span class="bad"></span>
      </h4>
    </div>
  <div class="col-md-2">
  </div>
  </div>
  <?php echo form_close(); ?>
</div>

<script>

  $('.limit').on('change keyup',function(e){
    e.preventDefault();
    var lower_good = parseInt($('#lower_good').val());
    var upper_good = parseInt($('#upper_good').val());
    var lower_concern = parseInt($('#lower_concern').val());
    var upper_concern = parseInt($('#upper_concern').val());
    if((lower_good > lower_concern && upper_good < upper_concern) && (lower_concern <upper_concern && lower_good < upper_good))
    {
      $('#metrics_save').removeAttr('disabled');
      $('#lower_bad').text(' > '+lower_concern);
      $('#upper_bad').text(' < '+upper_concern);
    }
    else
    {
     $('#metrics_save').prop('disabled','disabled'); 
   }

 })

  $('.label_color').on('change keyup',function(){
    console.log($(this).val());
    $(this).css('background-color',$(this).val())
  })
</script>