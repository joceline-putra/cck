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
            if(!empty($account)){
                echo 'Akun : ['.$account['account_code'].' ] '.$account['account_name'].'<br>';
            }
        ?>
        </div>
    </div>
      <!-- Content -->
     <!-- <div id="print-content" class="col-md-12 col-sm-12 col-xs-12">-->
        <!--<div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">-->

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <td class="text-center">#</td>
                        <td>Tanggal</td>
                        <td>Akun Perkiraan</td>
                        <td>Keterangan</td>                     
                        <td style="text-align:right;">Debit</td>     
                        <td style="text-align:right;">Kredit</td>                     
                    </tr>
                </thead>
            <tbody>
                <?php 
                $last = '';
                $background_color = '#f5f5f5';
                $hide = '';
                foreach($content as $v):
                    if(strlen($v['journal_number']) > 1){
                        $url = '<a href="'.$v['url'].'" target="_blank">'.$v['journal_number'].'</a>';
                    }else if(strlen($v['trans_number']) > 1){
                        $url = '<a href="'.$v['url'].'" target="_blank">'.$v['trans_number'].'</a>';
                    }else{
                        $url = '<a href="'.$v['url'].'"></a>';
                    }

                    $account_name = floatval($v['debit'] > 0) ? $v['account_name'] : '&nbsp;&nbsp;&nbsp;&nbsp;'.$v['account_name'];
                    if($last !== $v['journal_group_session']){
                        $num = 1;

                        if($v['journal_group_session'] == 'TOTAL'){
                            $background_color = 'e0dada';
                            $hide='hide';
                        }

                        if(intval($v['account_id']) > 0){
                    ?>
                    <tr class="<?php echo $hide; ?>">
                        <td colspan="7" style="background-color: <?php echo $background_color;?>;">
                            <?php echo $v['type_name'].' Rp. '.number_format($v['balance'],2,'.',',').' => '.$url.', @'.$v['contact_name'];?>
                        </td>
                    </tr>
                    <tr data-trans-id="<?php echo $v['journal_item_id'];?>">
                        <td class="text-center"></td>
                        <td><?php echo $v['journal_item_date_format'];?></td>
                        <td><?php echo $account_name;?></td>                     
                        <td><?php echo $v['journal_item_note'];?></td>  
                        <td class="text-right"><?php echo number_format($v['debit']);?></td>                                                          
                        <td class="text-right"><?php echo number_format($v['credit']);?></td>                                        
                    </tr>    
                    <?php
                        }else{ 
                            // if($v['journal_group_session'] == 'TOTAL'){
                            ?>
                    <tr data-trans-id="" style="background-color: <?php echo $background_color;?>;">
                        <td class="text-left" colspan="4"><b>TOTAL</b></td>
                        <td class="text-right"><b><?php echo number_format($v['debit']);?></b></td>                                                          
                        <td class="text-right"><b><?php echo number_format($v['credit']);?></b></td>                                        
                    </tr>        
                    <?php   
                            // }                    
                        }
                    $last = $v['journal_group_session']; 
                    }else{ 
                        // if(intval($v['account_id']) > 0){
                        ?>
                        <tr data-trans-id="<?php echo $v['journal_item_id'];?>">
                            <td class="text-center"></td>
                            <td><?php echo $v['journal_item_date_format'];?></td>
                            <td><?php echo $account_name;?></td>                     
                            <td><?php echo $v['journal_item_note'];?></td>  
                            <td class="text-right"><?php echo number_format($v['debit']);?></td>                                                          
                            <td class="text-right"><?php echo number_format($v['credit']);?></td>                                        
                        </tr> 
                    <?php 
                        // }
                    }                                   
                endforeach;
                ?>      
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