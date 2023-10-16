<link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/webarch/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />   
<link href="<?php echo base_url();?>assets/webarch/css/_print.css?_=<?php echo date('d-m-Y');?>" rel="stylesheet" type="text/css" />   
<style>
    body{
        font-family: monospace;
    }
    a:hover{
        text-decoration: none;
        font-weight: 800;
    }
    a:active{
        text-decoration: none;
        font-weight: 800;
    }  
    a:focus{
        text-decoration: none;
        font-weight: 800;
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
                		<td rowspan="2" colspan="1">Akun Perkiraan</td>  
                		<td colspan="2" style="text-align:center;">Saldo Awal</td>
                		<td colspan="2" style="text-align:center;">Pergerakan</td>
                		<td colspan="2" style="text-align:center;">Saldo Akhir</td>  
                		<td colspan="2" style="text-align:center;">Laba Rugi</td>
                		<td colspan="2" style="text-align:center;">Neraca</td>                  		              		                		                  		              		
                	</tr>
                    <tr>          
                        <td style="text-align:center;">Debit</td>     
                        <td style="text-align:center;">Kredit</td>   
                        <td style="text-align:center;">Debit</td>     
                        <td style="text-align:center;">Kredit</td>   
                        <td style="text-align:center;">Debit</td>     
                        <td style="text-align:center;">Kredit</td> 
                        <td style="text-align:center;">Debit</td>     
                        <td style="text-align:center;">Kredit</td>   
                        <td style="text-align:center;">Debit</td>     
                        <td style="text-align:center;">Kredit</td>                                                                                             
                    </tr>
                </thead>
            <tbody>
                <?php 
                $last = '';
                foreach($content as $v):
                    if(intval($v['account_id']) > 0){
                    	$account_name = '&nbsp;&nbsp;&nbsp;[ '.$v['account_code'].' ] '.$v['account_name'];
                	}else{
                    	$account_name = '<b>'.$v['account_name'].'</b>';
                	} 

                        if(intval($v['account_id']) > 0){
                            $colspan='';
                            $hide='';
                            $start_debit = number_format($v['start_debit']);                                                          
                            $start_credit = number_format($v['start_credit']);
                            $movement_debit = number_format($v['movement_debit']);                                                          
                            $movement_credit = number_format($v['movement_credit']);
                            $end_debit = number_format($v['end_debit']);                                                  
                            $end_credit = number_format($v['end_credit']);
                            $profit_loss_debit = number_format($v['profit_loss_debit']);                                                          
                            $profit_loss_credit = number_format($v['profit_loss_credit']);
                            $balance_debit = number_format($v['balance_debit']);                                                    
                            $balance_credit = number_format($v['balance_credit']);
                        }else{
                            $colspan=11;
                            $hide='hide';
                            $start_debit = '';                                                          
                            $start_credit = '';
                            $movement_debit = '';                                                          
                            $movement_credit = '';
                            $end_debit = '';                                                  
                            $end_credit = '';
                            $profit_loss_debit = '';                                                          
                            $profit_loss_credit = '';
                            $balance_debit = '';                                                    
                            $balance_credit = '';

                            if($account_name == '<b>Total</b>'){
                                $hide='';
                                $colspan=1;
                                $start_debit        = '<b>'.number_format($v['start_debit']).'</b>';                                                          
                                $start_credit       = '<b>'.number_format($v['start_credit']).'</b>';
                                $movement_debit     = '<b>'.number_format($v['movement_debit']).'</b>';                                                          
                                $movement_credit    = '<b>'.number_format($v['movement_credit']).'</b>';
                                $end_debit          = '<b>'.number_format($v['end_debit']).'</b>';                                                  
                                $end_credit         = '<b>'.number_format($v['end_credit']).'</b>';
                                $profit_loss_debit  = '<b>'.number_format($v['profit_loss_debit']).'</b>';                                                          
                                $profit_loss_credit = '<b>'.number_format($v['profit_loss_credit']).'</b>';
                                $balance_debit      = '<b>'.number_format($v['balance_debit']).'</b>';                                                    
                                $balance_credit     = '<b>'.number_format($v['balance_credit']).'</b>';
                            }
                        }
                        ?>
	                    <tr data-trans-id="<?php echo $v['temp_id'];?>">
                            <?php if($v['account_id'] > 0){?>
                                <td colspan="<?php echo $colspan;?>"><a href="#" class="btn_print_ledger" data-account-id="<?php echo $v['account_id'];?>"><?php echo $account_name;?></td>        
                            <?php }else{ ?>
	                            <td colspan="<?php echo $colspan;?>"><?php echo $account_name;?></td>
                            <?php }?>
	                        <td class="text-right <?php echo $hide;?>"><?php echo $start_debit;?></td>                                                          
	                        <td class="text-right <?php echo $hide;?>"><?php echo $start_credit;?></td>
	                        <td class="text-right <?php echo $hide;?>"><?php echo $movement_debit;?></td>                                                          
	                        <td class="text-right <?php echo $hide;?>"><?php echo $movement_credit;?></td>
	                        <td class="text-right <?php echo $hide;?>"><?php echo $end_debit;?></td>                                                          
	                        <td class="text-right <?php echo $hide;?>"><?php echo $end_credit;?></td> 
	                        <td class="text-right <?php echo $hide;?>"><?php echo $profit_loss_debit;?></td>                                                          
	                        <td class="text-right <?php echo $hide;?>"><?php echo $profit_loss_credit;?></td> 	 
	                        <td class="text-right <?php echo $hide;?>"><?php echo $balance_debit;?></td>                                                          
	                        <td class="text-right <?php echo $hide;?>"><?php echo $balance_credit;?></td> 	                                                                                                                                      
	                    </tr>                   	
                    <?php                                                                                    
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
<script src="<?php echo base_url();?>assets/core/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>  
<script>
    $(document).ready(function() {   
        let ledger_url = "<?= $print_ledger_url; ?>";
        let ledger_date = "<?= $print_date_period; ?>"; 
        let ledger_param = "<?= $print_parameter; ?>";                

        $(".btn_print_ledger").on("click",function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log($(this));
            var id = $(this).attr('data-account-id');
            /* hint zz_ajax */
            window.open(ledger_url+'/'+ledger_date+'/'+id+''+ledger_param,'_blank'); 
        });
    });
</script>