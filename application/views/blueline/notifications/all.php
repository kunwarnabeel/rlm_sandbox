<div class="col-sm-12  col-md-12 main"> 
	<div class="stdpad stdpad--auto-height box-shadow"><div class="table-head">Notifications</div>
                        <div class="table-div">
                            <table class="table notify_table"  id="notify_table" rel="" cellspacing="0" cellpadding="0">
                            <thead>
                            <th id="col_id">id</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Action</th>
                             </thead>
                             <tbody>
                             <?php foreach ($notification as $notification_data): ?>
                                    <?php $bgcolor='#ffffff'; // white
                                        if($notification_data->read_status==0)
                                            $bgcolor='#c5d9f1';
                                    ?>
                                    <tr id="<?= $notification_data->id ?>" <?= "style=background-color:$bgcolor"?>>
                                        <td><?= $notification_data->id ?></td>
                                        <td><i class="icon dripicons-star" style="opacity: 0.2;"></i> <?= $notification_data->status_message ?> </td>
                                        <td>  <small class="">   <?= substr($notification_data->created_date, 0,10);?>  @
                                                <?php $date =$notification_data->created_date; echo date('h:i:s a', strtotime($date));  ?> </small> </td>
                                        <td class="option" width="8%" style="text-align: left;">
                                            <?php
                                           
                                            if(substr($notification_data->status_message,0,8)=="New RFQ "){ ?>
                                            <a href="<?=site_url()?>rfqLog/ShowLogRFQ/<?=$notification_data->rfq_id.'?notification='.$notification_data->id ?> " >
                                                <span class="label label-info" > View </span>
                                            </a>
                                            <?php }else { ?>

                                            <a href="<?=site_url()?>openrfq/showrfqReadOnly/<?=$notification_data->rfq_id.'?notification='.$notification_data->id ?> " >
                                                <span class="label label-info" > View </span>
                                            <?php }?>

                                     </tr>


                             <?php endforeach ?>
                            
                            </tbody></table>
                                                        
                            </div>
                    </div>


    <script type="text/javascript">

        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('.notify_table thead tr').clone(true).appendTo( '.notify_table thead' );
            $('.notify_table thead tr:eq(1) th').each( function (i) {

                var title = $(this).text();
                if(title!="Action")
                {
                    $(this).html('<input type="text" class="	" placeholder="Search ' + title + '" />');
                }else{
                    $(this).html('');
                }
                $('input', this).on('keyup change', function () {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            } );
            var table = $('.notify_table').DataTable( {
                orderCellsTop: true,
                fixedHeader: true,
                lengthMenu: [[10, 25, 50, 100,-1], [10, 25, 50,100, "All"]],
                pageLength: defaulnumberofpages,
                "order": [[ 0, "desc" ]],
            } );

        } ) // ended jquery


    </script>



    <style type="text/css">

        thead input {
            width: 100%;
        }
        #col_id{
            width:40px;
        }

    </style>