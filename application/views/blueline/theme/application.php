<?php 
/**
 * @file        Application View
 * @author      Luxsys <support@freelancecockpit.com>
 * @copyright   By Luxsys (http://www.freelancecockpit.com)
 * @version     3.x.x
 */

$act_uri = $this->uri->segment(1, 0);
$lastsec = $this->uri->total_segments();
$act_uri_submenu = $this->uri->segment($lastsec);
if (!$act_uri) {
  $act_uri = 'dashboard';
}
if (is_numeric($act_uri_submenu)) {
  $lastsec = $lastsec - 1;
  $act_uri_submenu = $this->uri->segment($lastsec);
}
$message_icon = false;
?> 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <meta name="robots" content="none" />
  <link rel="SHORTCUT ICON" href="<?=base_url()?>assets/blueline/img/sws-icon.png"/>
  <title><?=$core_settings->company;?></title> 

  <?php 
  require_once '_partials/fonts.php';
  require_once '_partials/js_vars.php';
  ?>

  <!-- Head CSS and JS -->  
  <script src="<?=base_url()?>assets/blueline/js/plugins/jquery-2.2.4.min.js?ver=<?=$core_settings->version;?>"></script>  

  <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/app.css"/>
  <link rel="stylesheet" href="<?=base_url()?>assets/blueline/css/user.css?ver=<?=$core_settings->version;?>"/> 
  <?=get_theme_colors($core_settings);?>

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="<?=base_url()?>assets/blueline/js/plugins/bootstrap-notify.min.js?ver=<?=$core_settings->version;?>"></script>

    <script>
     var csfrData = {};
     csfrData['<?php echo $this->security->get_csrf_token_name(); ?>']
     = '<?php echo $this->security->get_csrf_hash(); ?>';
   </script>
   <style>
     .sidebar-bg{
      background-color: #0f6c90
    }
    .nav.nav-sidebar>li.active>a{
      background: #e6d937;
    }
    .maindropdown{
      width: 100%;
      background-color: #0f6c90;
    }
  </style>
</head>

<body>
  <div id="mainwrapper" data-turbolinks="false">
    <div class="side">
      <div class="sidebar-bg">
        <div style="position: absolute;bottom: 5px;left: 15px;color:#fff;font-size: 10px;"><span class="nav-text">Copyright © 2022 SWS-USA</span>
        </div>
      </div>
      <div class="sidebar">
        <div class="navbar-header">

          <a class="navbar-brand" href="#"><img src="<?=base_url()?><?=$core_settings->logo;?>" alt="<?=$core_settings->company;?>"></a>
        </div>

        <ul class="nav nav-sidebar">
          <li id="dashboard" class="<?php if ($act_uri == 'dashboard') {
                      echo 'active';
                  } ?>">
            <a href="<?=site_url('dashboard'); ?>">
              <span class="menu-icon">
                <i class="fa icon dripicons-meter"></i>
              </span>
              <span class="nav-text">
                Dashboard
              </span>
            </a> 
          </li>
          <li id="quick_search" class="<?php if ($act_uri_submenu == 'quick_search') {
                      echo 'active';
                  } ?>">
            <a href="<?=site_url('quick_search'); ?>">
              <span class="menu-icon">
                <i class="fa icon dripicons-blog"></i>
              </span>
              <span class="nav-text">
                Quick Search
              </span>
            </a> 
          </li>
          <li id="mapping" class="<?php if ($act_uri_submenu == 'import_mapping') {
                      echo 'active';
                  } ?>">
            <a href="<?=site_url('import_mapping'); ?>">
              <span class="menu-icon">
                <i class="fa icon dripicons-blog"></i>
              </span>
              <span class="nav-text">
                Import columns Mapping
              </span>
            </a> 
          </li>
          <li id="item_list" class="<?php if ($act_uri_submenu == 'report_builder') {
                      echo 'active';
                  } ?>">
            <a href="<?=site_url('report_builder'); ?>">
              <span class="menu-icon">
                <i class="fa icon dripicons-blog"></i>
              </span>
              <span class="nav-text">
                Report Builder
              </span>
            </a> 
          </li>
          <li id="item_setup" class="<?php if ($act_uri == 'plant_part_setup' && $act_uri_submenu != 'item_list') {
                      echo 'active';
                  } ?>">
            <a href="<?=site_url('plant_part_setup'); ?>">
              <span class="menu-icon">
                <i class="fa icon dripicons-plus"></i>
              </span>
              <span class="nav-text">
                Plant - Part Setup
              </span>
            </a> 
          </li>
          <li id="item_list" class="<?php if ($act_uri_submenu == 'warnings') {
                      echo 'active';
                  } ?>">
            <a href="<?=site_url('warnings');?>">
              <span class="menu-icon">
                <i class="fa icon dripicons-blog"></i>
              </span>
              <span class="nav-text">
                Warning Setup
              </span>
            </a> 
          </li>
          <li id="item_list" class="<?php if ($act_uri_submenu == 'warning_log') {
                      echo 'active';
                  } ?>">
            <a href="<?=site_url('warnings/warning_log')?>">
              <span class="menu-icon">
                <i class="fa icon dripicons-blog"></i>
              </span>
              <span class="nav-text">
                Warning Log
              </span>
            </a> 
          </li>
          <li id="item_list" class="<?php if ($act_uri_submenu == 'activities') {
                      echo 'active';
                  } ?>">
            <a href="<?=site_url('activities')?>">
              <span class="menu-icon">
                <i class="fa icon dripicons-blog"></i>
              </span>
              <span class="nav-text">
                Import Log
              </span>
            </a> 
          </li>
          <li class="<?php if ($act_uri_submenu == 'contact_book') {
                      echo 'active';
                  } ?>">
            <a href="<?=site_url('contact_book'); ?>">
              <span class="menu-icon">
                <i class="fa icon dripicons-blog"></i>
              </span>
              <span class="nav-text">
                Contact Book
              </span>
            </a> 
          </li>
          <li class="<?php if ($act_uri_submenu == 'item_list') {
                      echo 'active';
                  } ?>">
            <a href="#">
              <span class="menu-icon">
                <i class="fa icon dripicons-blog"></i>
              </span>
              <span class="nav-text">
                Conditions List
              </span>
            </a> 
          </li>
        </ul>

<script>
          $('ul.nav li.dropdown').hover(function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
}, function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
});
        </script>

      </div>
    </div>

    <div class="content-area" onclick="">
      <div class="row mainnavbar">
        <div class="topbar noselect">        
          <img class="img-circle topbar-userpic" src="<?=base_url()?>assets/blueline/images/userlogo.png?>" height="21px">      
          <span class="topbar__name fc-dropdown--trigger">
            <span class="hidden-xs">Admin</span> <i class="icon dripicons-chevron-down topbar__drop"></i>
          </span>
       </div>       
     </div>

     <?=$yield?>

   </div>
   <!-- Notify -->
   <?php if ($this->session->flashdata('message')) {
    $exp = explode(':', $this->session->flashdata('message'))?>
    <div class="notify <?=$exp[0]?>"><?=$exp[1]?></div>
    <?php
  } ?>
  <div class="ajax-notify"></div>

  <!-- Modal -->
  <div class="modal fade" id="mainModal" data-easein="flipXIn" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="mainModalLabel" aria-hidden="true"></div>

  <!-- Js Files -->  

  <script type="text/javascript" src="<?=base_url()?>assets/blueline/js/app.js?ver=<?=$core_settings->version;?>"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.1/js/dataTables.fixedColumns.min.js"></script>
  <script>
    flatdatepicker(false, langshort);
  </script>

</div> <!-- Mainwrapper end -->



</body>


<script src="<?=base_url()?>assets/blueline/js/plugins/Chart.js?ver=<?=$core_settings->version;?>"></script>
<script src="<?=base_url()?>assets/blueline/js/plugins/analyser.js?ver=<?=$core_settings->version;?>"></script>
<script src="<?=base_url()?>assets/blueline/js/plugins/utils.js?ver=<?=$core_settings->version;?>"></script>


<script>
 var defaulnumberofpages="10";
 if(defaulnumberofpages=="-1")
  defaulnumberofpages=parseInt(defaulnumberofpages);

function download_csv(csv, filename) {
  var csvFile;
  var downloadLink;

    // CSV FILE
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // We have to create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Make sure that the link is not displayed
    downloadLink.style.display = "none";

    // Add the link to your DOM
    document.body.appendChild(downloadLink);

    // Lanzamos
    downloadLink.click();
  }
  
  function export_table_to_csv(html, filename) {
    var csv = [];
    var cnt=1;
    var rows = document.querySelectorAll("table tr");
    
    for (var i = 0; i < rows.length; i++) {
      // condition to not export search text field row
      if(cnt==2)
      {    
        cnt++;     
        continue;

      }    
      var row = [], cols = rows[i].querySelectorAll("td, th");

      for (var j = 0; j < cols.length; j++) 
        row.push(cols[j].innerText);

      csv.push(row.join(",")); 
      cnt++;       
    }

    // Download CSV
    download_csv(csv.join("\n"), filename);
  }


  
</script>

</html>



