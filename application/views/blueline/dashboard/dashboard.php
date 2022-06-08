<style>
    @media (max-width: 767px){
        .content-area {padding: 0;}
        .row.mainnavbar {
            margin-bottom: 0px;
            margin-right: 0px;
        }
    }
</style>

<div class="grid">
    <div class="grid__col-md-10 dashboard-header">
        <h1>
            Hi Administrator, Welcome back to RLM Portal!
        </h1>
    </div>    
    <div class="grid__col-md-2 dashboard-header hidden-xs">
    </div>
    <div class="grid__col-sm-12 grid__col-md-12 grid__col-lg-12 grid__col--bleed">
        <div class="grid grid--align-content-start">
            <div class="grid__col-12 update-panel">
                <div class="tile-base box-shadow">
                </div>
            </div>
            <!-- This section will be visible to Administrator -->
            <!-- This section will be visible to Administrator -->
            <div class="grid__col-4 grid__col-xs-4 grid__col-sm-4 grid__col-md-4 grid__col-lg-4">
                <div class="tile-base box-shadow">
                    <div class="tile-icon hidden-md hidden-xs"><i class="ion-ios-cloud-upload"></i>
                    </div>
                    <div class="tile-small-header">Aptiv Data Import</div>
                    <div class="tile-body">
                        <select class="aptivReleaseDate">
                            <option disabled selected>Select Release Date</option>
                            <?php
                            $i=-1;$j=-6;
                            if(date('D')=="Mon"){
                                $i=0;$j=-5;
                            }
                            for($i;$i>$j;$i--){
                            ?>
                            <option><?= date('Ymd',strtotime($i.' monday'))?></option>
                        <?php } ?>
                        </select>
                        <a href = "<?=site_url('rlm_data/import_data?customer_number=A1234')?>" class="btn btn-primary">Import</a>
                    </div>
                    <div class="tile-bottom">
                        <div class="progress tile-progress tile-progress--orange">

                        </div>
                    </div>
                </div>
            </div>
            <div class="grid__col-4 grid__col-xs-4 grid__col-sm-4 grid__col-md-4 grid__col-lg-4">
                <div class="tile-base box-shadow">
                    <div class="tile-icon hidden-md hidden-xs"><i class="ion-ios-cloud-upload"></i>
                    </div>
                    <div class="tile-small-header">Lear data import</div>
                    <div class="tile-body">
                        <select class="learReleaseDate">
                            <option disabled selected>Select Release Date</option>
                            <?php
                            $i=-1;$j=-7;
                            if(date('D')=="Mon"){
                                $i=0;$j=-6;
                            }
                            for($i;$i>$j;$i--){
                            ?>
                            <option><?= date('Ymd',strtotime($i.' monday'))?></option>
                        <?php } ?>
                        </select>
                        <a href = "<?=site_url('rlm_data/import_data?customer_number=L2222')?>" class="btn btn-primary">Import</a></div>
                    <div class="tile-bottom">
                        <div class="progress tile-progress tile-progress--orange">
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid__col-4 grid__col-xs-4 grid__col-sm-4 grid__col-md-4 grid__col-lg-4">
                <div class="tile-base box-shadow">
                    <div class="tile-icon hidden-md hidden-xs"><i class="ion-ios-cloud-upload"></i>
                    </div>
                    <div class="tile-small-header">Yazaki data import</div>
                    <div class="tile-body">
                        <select class="yazakiReleaseDate">
                            <option disabled selected>Select Release Date</option>
                            <?php
                            $i=-1;$j=-6;
                            if(date('D')=="Mon"){
                                $i=0;$j=-5;
                            }
                            for($i;$i>$j;$i--){
                            ?>
                            <option><?= date('Ymd',strtotime($i.' monday'))?></option>
                        <?php } ?>
                        </select>
                        <a href = "<?=site_url('rlm_data/import_data?customer_number=Y1234')?>" class="btn btn-primary">Import</a></div>
                    <div class="tile-bottom">
                        <div class="progress tile-progress tile-progress--orange">
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid__col-4 grid__col-xs-4 grid__col-sm-4 grid__col-md-4 grid__col-lg-4">
                <div class="tile-base box-shadow">
                    <div class="tile-icon hidden-md hidden-xs"><i class="ion-ios-cloud-upload"></i>
                    </div>
                    <div class="tile-small-header">Send Warning</div>
                    <div class="tile-body">
                        <select class="warningReleaseDate">
                            <option disabled selected>Select Release Date</option>
                            <?php
                            $i=-1;$j=-6;
                            if(date('D')=="Mon"){
                                $i=0;$j=-5;
                            }
                            for($i;$i>$j;$i--){
                            ?>
                            <option><?= date('Ymd',strtotime($i.' monday'))?></option>
                        <?php } ?>
                        </select>
                        <a href = "<?=site_url('rlm_data/weekly_warning')?>" class="btn btn-primary">Import</a></div>
                    <div class="tile-bottom">
                        <div class="progress tile-progress tile-progress--orange">
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid__col-4 grid__col-xs-4 grid__col-sm-4 grid__col-md-4 grid__col-lg-4">
                <div class="tile-base box-shadow">
                    <div class="tile-icon hidden-md hidden-xs"><i class="ion-ios-cloud-upload"></i>
                    </div>
                    <div class="tile-small-header">Open Order Import</div>
                    <div class="tile-body"><a href = "<?=site_url('Open_order/import_data')?>" class="btn btn-primary">Import</a></div>
                    <div class="tile-bottom">
                        <div class="progress tile-progress tile-progress--orange">
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid__col-4 grid__col-xs-4 grid__col-sm-4 grid__col-md-4 grid__col-lg-4">
                <div class="tile-base box-shadow">
                    <div class="tile-icon hidden-md hidden-xs"><i class="ion-ios-cloud-upload"></i>
                    </div>
                    <div class="tile-small-header">SWS Intransit Import</div>
                    <div class="tile-body"><a href = "<?=site_url('Sws_intransit/import_data')?>" class="btn btn-primary">Import</a></div>
                    <div class="tile-bottom">
                        <div class="progress tile-progress tile-progress--orange">
                            
                        </div>
                    </div>
                </div>
            </div>

           
            <div class="grid__col-4 grid__col-xs-4 grid__col-sm-4 grid__col-md-4 grid__col-lg-4">
                <div class="tile-base box-shadow tile-with-icon">
                    <div class="tile-icon hidden-md hidden-xs"><i class="ion-document"></i></div>
                    <div class="tile-small-header">
                        Total Items
                    </div>
                    <div class="tile-body">
                        <div class="number">
                            <!-- <a href="<?= site_url('item_setup/item_list');?>"><?= $items;?></a> -->
                            123
                        </div>
                    </div>
                    <div class="tile-bottom">
                        <div class="progress tile-progress tt">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('.aptivReleaseDate').on('change',function(){
            var date = $(this).val();
            var link = '<?=site_url()."rlm_data/import_data?customer_number=A1234&date="?>'+date;
            $(this).closest('div').find('a').attr('href',link);
        })
        $('.learReleaseDate').on('change',function(){
            var date = $(this).val();
            var link = '<?=site_url()."rlm_data/import_data?customer_number=L2222&date="?>'+date;
            $(this).closest('div').find('a').attr('href',link);
        })
        $('.yazakiReleaseDate').on('change',function(){
            var date = $(this).val();
            var link = '<?=site_url()."rlm_data/import_data?customer_number=Y1234&date="?>'+date;
            $(this).closest('div').find('a').attr('href',link);
        })
        $('.warningReleaseDate').on('change',function(){
            var date = $(this).val();
            var link = '<?=site_url()."rlm_data/weekly_warning?date="?>'+date;
            $(this).closest('div').find('a').attr('href',link);
        })
    </script>