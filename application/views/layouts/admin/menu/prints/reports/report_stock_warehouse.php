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
            <img src="<?php echo $branch_logo;?>" style="width:150px;" class="img-responsive">
        </div>
        <div class="col-md-10 col-sm-10 col-xs-10" style="padding-left:0px;">
        <a href='#' onclick="window.print();">
            <?php echo $title; ?>
        </a>
        <br>
        Periode : <?php echo $periode; ?>
        <br>
        <?php 
            if(!empty($location)){
                echo 'Gudang : ' . $location['location_name'].'<br>';
            }

            if(!empty($product)){
                echo $product_alias.' : [ ' . $product['product_name'] . ' ] ' .$product['product_name'].'<br>';
                echo 'Satuan : ' .$product['product_unit'].'<br>';                
            }     
            
            if(!empty($category)){
                echo 'Kategori '.$product_alias.' : ' . $category['category_name'].'<br>';
            }                   
        ?>
        </div>
    </div>
      <!-- Content -->
     <!-- <div id="print-content" class="col-md-12 col-sm-12 col-xs-12">-->
        <!--<div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">-->

            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="background-color:#eaeaea;">
                        <td style="text-align:right;"><b>No</b></td>
                        <td><b>Kode <?php echo $product_alias; ?></b></td>
                        <td><b>Nama <?php echo $product_alias; ?></b></td>
                        <td><b>Kategori <?php echo $product_alias; ?></b></td>                             
                        <td style="text-align:right;"><b>Stok</b></td>                     
                        <td style="text-align:left;"><b>Satuan</b></td>     
                    </tr>
                </thead>
            <tbody>
                <?php 
                $num=1;
                // $total_trans=0;
                foreach($content as $v):
                ?>
                <tr data-trans-id="<?php echo $v['product_id'];?>">
                     <td style="text-align:right;"><?php echo $num++; ?></td>
                     <td><?php echo !empty($v['product_code']) ? $v['product_code'] : '-';?></td>
                     <td><?php echo $v['product_name'];?></td>
                     <td><?php echo !empty($v['category_id']) ? $v['category_name'] : '-';?></td>                                                                     
                     <td class="text-right"><?php echo number_format($v['balance'],2,'.',',');?></td>                                                                               
                     <td style="text-align:left;"><?php echo $v['product_unit'];?></td>                                      
                 </tr>    
                <?php 
                // $total_trans = $total_trans + $v['trans_item_sell_total'];                  
                endforeach;
                ?>      
                <!-- <tr> -->
                    <!-- <td colspan="4"><b>Total</b></td> -->
                    <!-- /*<td style="text-align: right;"><b><?php echo number_format($total_trans);?></b></td>                                        */ -->
                <!-- </tr> -->
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