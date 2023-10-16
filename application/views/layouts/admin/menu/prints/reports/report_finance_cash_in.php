<link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/webarch/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />   
<link href="<?php echo base_url();?>assets/webarch/css/_print.css?_=<?php echo date('d-m-Y');?>" rel="stylesheet" type="text/css" />   
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
            if(!empty($account)){
                echo 'Akun : [ '.$account['account_code'].' ] '.$account['account_name'].'<br>';
            }
            echo "Filter : Hanya menampilkan ".$result['start']." hingga ".$result['limit']." dari ".$result['total']." total data yang ada";
        ?>
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
                        <td>Transaksi</td>
                        <td>Nomor</td>
                        <td>Akun Perkiraan</td>
                        <td>Keterangan</td>                         
                        <td style="text-align:right;">Total</td>    
                    </tr>
                </thead>
            <tbody>
                <?php 
                $num=1;
                $total_debit=0;        
                foreach($content as $v):
                        // $document_number = !empty($v['journal_number']) ? $v['journal_number'] : $v['trans_number'];
                        if(strlen($v['journal_number']) > 1){
                            $url = '<a href="'.$v['url'].'" target="_blank">'.$v['journal_number'].'</a>';
                        }else if(strlen($v['trans_number']) > 1){
                            $url = '<a href="'.$v['url'].'" target="_blank">'.$v['trans_number'].'</a>';
                        }else{
                            $url = '<a href="'.$v['url'].'"></a>';
                        }                  
                        
                        if(strlen($v['contact_name']) > 1){
                            $url .= ' - '.$v['contact_name'];
                        }
                ?>
                    <tr data-trans-id="<?php echo $v['journal_item_id'];?>">
                        <td class="text-center"><?php echo $num++; ?></td>
                        <td><?php echo $v['journal_item_date_format'];?></td>
                        <td><?php echo $v['type_name'];?></td>                     
                        <td><?php echo $url;?></td>               
                        <td><?php echo $v['account_code'].' '.$v['account_name'];?></td> 
                        <td><?php echo $v['journal_item_note'];?></td>                                                           
                        <td class="text-right"><?php echo number_format($v['debit']);?></td>                                       
                    </tr>    
                <?php 
                $total_debit = $total_debit + $v['debit'];                                                 
                endforeach;
                ?>      
                <tr>
                    <td colspan="6"><b>Total</b></td>
                    <td style="text-align: right;"><b><?php echo number_format($total_debit);?></b></td>
                </tr>
                <tr>
                    <td colspan="7"><?php echo "Hanya menampilkan ".$result['start']." hingga ".$result['limit']." dari ".$result['total']." total data yang ada";?></td>
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