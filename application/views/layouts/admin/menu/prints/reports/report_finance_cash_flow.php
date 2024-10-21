<link href="<?php echo base_url();?>assets/core/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/core/plugins/bootstrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/core/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />   
<link href="<?php echo base_url();?>assets/core/css/_print.css?_=<?php echo date('d-m-Y');?>" rel="stylesheet" type="text/css" />   
<style>
    body{
        font-family: monospace;
    }
    thead > tr > td {
        font-weight: bold;
    }    
</style>
<div class="container-fluid">
    <title><?php echo $title; ?></title>      
    <div id="print-paper" class="col-md-20" style="">
        <!-- Header -->
        <!--<div id="print-header" class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">   
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <?php echo $title2;?><br>
                    <a href='#' onclick="window.print();">
                        <?php echo $title3;?>
                    </a>
                </div>                               
                <div class="col-md-5 col-xs-5 col-xs-5 padding-remove-left">
                  PERIODE : <?php echo $periode_awal.' sd '.$periode_akhir;?>
                </div>
          </div>
      </div>-->

    <div class="col-md-12 col-xs-12">
        <div class="col-md-2 col-sm-2 col-xs-2" style="padding-left:0px;">
            <img src="<?php echo base_url(); ?>upload/branch/default_logo.png" style="" class="img-responsive">
        </div>
        <div class="col-md-10 col-sm-10 col-xs-10" style="padding-left:0px;">
        <a href='#' onclick="window.print();">
            <?php echo $title; ?>
        </a>
        <br>
        Periode : <?php echo $periode; ?>
        <br>
        <?php 
            if(!empty($branch)){
                echo 'Cabang : [ '.$branch['branch_name'].' ] '.$branch['branch_address'].'<br>';
            }else{
                echo 'Cabang : Semua Cabang<br>';
            }

            if(!empty($users)){
                echo 'User : [ '.$users['user_username'].' ] '.$users['user_group_name'].'<br>';
            }else{
                echo 'User : Semua User';
            }            
        ?>
        </div>
    </div>
      <!-- Content -->
     <div id="print-content" class="col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <td>Rincian</td>                   
                        <td style="text-align:right;">Jumlah</td>     
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $num=1;      
                    foreach($content['booking'] as $v):
                        $ototal = !empty($v['order_total']) ? $v['order_total'] : 0;
                        echo '<tr>';
                            echo '<td>Booking</td>';
                            echo '<td style="text-align:right;">'.number_format($ototal).'</td>';
                        echo '</tr>';                                              
                    endforeach;
                    ?>  
                    <?php 
                    $num=1;      
                    foreach($content['paid'] as $v):
                        $ptotal = !empty($v['paid_total']) ? $v['paid_total'] : 0;                        
                        echo '<tr>';
                            echo '<td>&nbsp;&nbsp;&nbsp;- '.ucfirst(strtolower($v['paid_payment_method'])).'</td>';
                            echo '<td style="text-align:right;">'.number_format($ptotal).'</td>';
                        echo '</tr>';                                              
                    endforeach;
                    ?>  
                    <?php 
                    $num=1;      
                    foreach($content['resto'] as $v):
                        $ttotal = !empty($v['trans_total']) ? $v['trans_total'] : 0;
                        echo '<tr>';
                            echo '<td>Resto</td>';
                            echo '<td style="text-align:right;">'.number_format($ttotal).'</td>';
                        echo '</tr>';                                              
                    endforeach;
                    ?>                       
                    <?php 
                    $num=1;      
                    foreach($content['cost'] as $v):
                        $ctotal = !empty($v['journal_item_debit']) ? $v['journal_item_debit'] : 0;                               
                        echo '<tr>';
                            echo '<td>Biaya</td>';
                            echo '<td style="text-align:right;">'.number_format($ctotal).'</td>';
                        echo '</tr>';                                              
                    endforeach;
                    ?>                                      
                    <!-- <tr>
                        <td colspan="5"><b>Total</b></td>
                        <td style="text-align: right;"><b><?php echo number_format($total_debit);?></b></td>
                        <td style="text-align: right;"><b><?php echo number_format($total_credit);?></b></td>                    
                        <td style="text-align: right;"><b></b></td>                    
                    </tr> -->
                </tbody>
            </table>    
        </div>
     </div>      

  </div>    

</div>