<link href="<?php echo base_url();?>assets/core/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/core/plugins/bootstrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/core/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />   
<!-- <link href="<?php #echo base_url();?>assets/webarch/css/_print.css?_=<?php #echo date('d-m-Y');?>" rel="stylesheet" type="text/css" />    -->
<style>
    body{
        font-family: monospace;
    }
</style>
<div class="container-fluid">
    <!-- <div class="row"> -->
    <title><?php echo $title; ?></title>      
    <div id="print-paper" class="col-md-12">
        <!-- Header -->
        <!--<div id="print-header" class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">   
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <?php #echo $title2;?><br>
                    <a href='#' onclick="window.print();">
                        <?php #echo $title3;?>
                    </a>
                </div>                               
                <div class="col-md-5 col-xs-5 col-xs-5 padding-remove-left">
                  PERIODE : <?php #echo $periode_awal.' sd '.$periode_akhir;?>
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
            if(!empty($contact)){
                echo $contact_alias.' : ' . $contact['contact_name'].'<br>';
                echo 'Alamat : ' . $contact['contact_address'];                
            }
            
            if(!empty($product)){
                echo $product_alias.' : ' . $product['product_code'].', '.$product['product_name'].', '.$product['product_unit'];                
            }         
            if(!empty($branchs)){
                echo 'Cabang : ' . $branchs['branch_name'];                
            }                
        ?>
        </div>
    </div>
      <!-- Content -->
     <div id="print-content" class="col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">

            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="background-color:#eaeaea;">
                        <td class="text-right"><b>No</b></td>
                        <td><b>Tanggal</b></td>
                        <td><b>Nomor</b></td>
                        <td><b>Cabang</b></td>                        
                        <td><b><?php echo $contact_alias; ?></b></td>
                        <td><b>Tipe</b></td>   
                        <td><b>Jenis Kamar</b></td>
                        <td style="text-align:left;"><b>Untuk</b></td>  
                        <td style="text-align:left;"><b>CheckIn</b></td>    
                        <td style="text-align:left;"><b>Kamar</b></td>                                                  
                        <!-- <td style="text-align:right;"><b>Total Nilai</b></td>                                      -->
                        <td style="text-align:right;"><b>Total Terbayar</b></td>     
                        <td style="text-align:left;"><b>Status</b></td>        
                        <td style="text-align:left;"><b>Attachment</b></td>    
                        <td style="text-align:left;"><b>Jml Kendaraan</b></td>
                        <td style="text-align:left;"><b>Biaya Kendaraan</b></td>       
                        <td style="text-align:left;"><b>Catatan</b></td>        
                        <td style="text-align:right;"><b>Sisa Hari</b></td>                                                                                                                     
                    </tr>
                </thead>
            <tbody>
                <?php 
                $num=1;
                $total_trans=0;
                $total_paid=0;                
                foreach($content as $v):
                ?>
                <tr data-order-id="<?php echo $v['order_id'];?>">
                     <td class="text-right"><?php echo $num++; ?></td>
                     <td><?php echo date("d-M-Y", strtotime($v['order_date']));?></td>
                     <td><?php echo $v['order_number'];?></td>                     
                     <td data-branch-id="<?php echo $v['branch_id'];?>"><?php echo $v['branch_name'];?></td>  
                     <td><?php echo $v['order_contact_name'];?></td>  
                     <td>
                        <?php echo $v['order_item_type_2'];?>
                        <?php 
                        if($v['order_item_ref_price_sort'] == 0){
                            #echo 'Bulanan';
                        }else if($v['order_item_ref_price_sort'] == 1){
                            echo '<br>Harian';
                        }else if($v['order_item_ref_price_sort'] == 2){
                            echo '<br>4 Jam';
                        }else if($v['order_item_ref_price_sort'] == 4){
                            echo '<br>2 Jam';
                        }
                     ?>                    
                     </td>                                 
                     <td data-ref-id="<?php echo $v['ref_id'];?>"><?php echo $v['ref_name'];?></td>                                                          
                     <td class="text-left"><?php echo date("d-M-Y, H:i", strtotime($v['order_item_start_date'])).' sd '.date("d-M-Y, H:i", strtotime($v['order_item_end_date']));?></td>
                     <td><?php 
                        if($v['order_item_flag_checkin'] == 0){
                            echo 'Waiting';
                        }else if($v['order_item_flag_checkin'] == 1){
                            echo 'CheckIN '.$v['product_name'];
                        }else if($v['order_item_flag_checkin'] == 2){
                            echo 'CheckOUT '.$v['product_name'];
                        }else if($v['order_item_flag_checkin'] == 4){
                            echo 'Batal';
                        }
                     ?></td>
                     <td data-product-id="<?php echo $v['product_id'];?>"><?php echo $v['product_name'];?></td>                             
                     <!-- <td style="text-align:right;"><?php #echo number_format($v['order_item_price']);?></td>      -->
                     <td style="text-align:right;"><?php echo number_format($v['order_total_paid']);?></td>                                                           
                     <td><?php if($v['order_paid'] == 1){ echo 'Lunas'; };?></td>    
                     <td><?php 
                        if($v['order_files_count'] > 0){
                            echo '<span class="fas fa-files"></span>'.$v['order_files_count'];
                        }else{
                            echo '-';
                        }
                        ;?></td>  
                    <td style="text-align:right;"><?php echo number_format($v['order_vehicle_count']);?></td>     
                    <td style="text-align:right;"><?php echo number_format($v['order_vehicle_cost']);?></td>   
                    <td><?php echo $v['order_item_note'];?></td>      
                    <td><?php echo ($v['order_item_expired_day'] > 0) ? $v['order_item_expired_day'] : '-';?></td>                                               
                 </tr>    
                <?php 
                $total_trans = $total_trans + $v['order_item_price'];                  
                $total_paid = $total_paid + $v['order_total_paid'];                                  
                endforeach;
                ?>      
                <tr>
                    <td colspan="10"><b>Total</b></td>
                    <!-- <td style="text-align: right;"><b><?php #echo number_format($total_trans);?></b></td> -->
                    <td style="text-align: right;"><b><?php echo number_format($total_paid);?></b></td>                                                            
                </tr>
            </tbody>
        </table> 
        </div>
    </div>   

        <!-- Footer -->
        <!--<div id="print-footer" class="col-md-12 col-sm-12 col-xs-12">
          <div>Dicetak :  <?php #echo ucfirst($session['user_data']['user_name']);?> | <?php #echo date("d-m-Y H:i:s");?></div>
      </div> -->     
    <!-- </div>                       -->
  </div>    

</div>