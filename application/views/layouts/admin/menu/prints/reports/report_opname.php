<link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/webarch/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />   
<link href="<?php echo base_url();?>assets/webarch/css/_print.css?_=<?php echo date('d-m-Y');?>" rel="stylesheet" type="text/css" />   
<style>
    body{
        font-family: monospace;
    }
</style>
<div class="container-fluid">
    <title>Laporan Opname</title>      
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

    <div class="col-md-12">
        <div class="col-md-2 col-sm-12" style="padding-left:0px;">
            <img src="<?php echo site_url('assets/webarch/img/logo/foodpedia_ori.png');?>" style="width:150px;" class="img-responsive">
        </div>
        <div class="col-md-10 col-sm-12" style="padding-left:0px;">
        <a href='#' onclick="window.print();">
            Laporan Opname
        </a>
        <br>
        Periode : <?php echo $periode; ?>
        <br><br>
        </div>
    </div>
      <!-- Content -->
     <!-- <div id="print-content" class="col-md-12 col-sm-12 col-xs-12">-->
        <!--<div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">-->

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                     <td>No</td>
                     <td>Tanggal</td>
                     <td>Nomor</td>
                     <td>Bahan</td>
                     <td class="hide" style="text-align: right;">Total</td>
                     <td>User</td>                     
                    </tr>
            </thead>
            <tbody>
                <?php 
                $num=1;
                $subtotal=0;
                foreach($content as $v):
                
                ?>
                <tr>
                     <td><?php echo $num++; ?></td>
                     <td><?php echo $v['order_date'];?></td>
                     <td><?php echo $v['order_number'];?></td>   
                     <td>
                         <?php 
                            foreach($v['order_item'] AS $i):
                                echo $i['product_name'].' = '.$i['order_item_qty'].' '.$i['order_item_unit'].'<br>';
                            endforeach;

                         ?>
                     </td>                 
                     <td class="hide" style="text-align:right;"><?php echo number_format($v['order_subtotal']);?></td>
                     <td><?php echo $v['user_username'];?></td>                     
                 </tr>    
                 <?php 
                 $subtotal = $subtotal + $v['order_subtotal'];
                 endforeach;
                 ?>      
                <!--
                 <tr>
                     <td colspan="5"><b>Total</b></td>
                     <td style="text-align: right;"><b><?php echo number_format($subtotal);?></b></td>
                 </tr>
                -->
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