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
            <img src="<?php echo base_url('upload/branch/default_logo.png');?>" style="width:150px;" class="img-responsive">
        </div>
        <div class="col-md-10 col-sm-10 col-xs-10" style="padding-left:0px;">
        <a href='#' onclick="window.print();">
            <?php echo $title; ?>
        </a>
        <br>
        Periode : <?php echo $periode; ?>
        </div>
    </div>
      <!-- Content -->
     <!-- <div id="print-content" class="col-md-12 col-sm-12 col-xs-12">-->
        <!--<div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">-->

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <td class="text-center">No</td>
                        <td>Tanggal</td>
                        <td>Cabang</td>
                        <td>Nomor</td>
                        <td>Keterangan</td>                     
                        <td style="text-align:right;">Total</td>      
                    </tr>
                </thead>
            <tbody>
                <?php 
                $num=1;
                $total_debit= 0;
                foreach($content as $v):
                    $ptotal = !empty($v['journal_item_debit']) ? $v['journal_item_debit'] : 0;                        
                    echo '<tr>';
                        echo '<td>'.$num++.'</td>';
                        echo '<td>'.date("d-M-Y, H:i",strtotime($v['journal_date'])).'</td>';                        
                        echo '<td>'.$v['branch_name'].'</td>';
                        echo '<td>'.$v['journal_number'].'</td>';                        
                        echo '<td>'.ucfirst(strtolower($v['journal_note'])).'</td>';
                        echo '<td style="text-align:right;">'.number_format($ptotal).'</td>';
                    echo '</tr>';                                          
                    $total_debit = $total_debit + $v['journal_item_debit'];    
                endforeach;
                ?>  
                <tr>
                    <td colspan="5"><b>Total</b></td>
                    <td style="text-align: right;"><b><?php echo number_format($total_debit);?></b></td>             
                </tr>
            </tbody>
        </table> 
        <!-- </div> -->
    <!-- </div>    -->

        <!-- Footer -->
        <!--<div id="print-footer" class="col-md-12 col-sm-12 col-xs-12">
          <div>Dicetak :  <?php echo ucfirst($session['user_data']['user_name']);?> | <?php echo date("d-m-Y H:i:s");?></div>
      </div> -->                           
  </div>    

</div>