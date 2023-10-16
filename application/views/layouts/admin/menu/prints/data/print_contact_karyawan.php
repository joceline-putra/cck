<!doctype html>
<html lang="en">

    <!-- Mirrored from templates.g5plus.net/homeid/single-property-6.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 22 Jan 2021 07:25:27 GMT -->
    <!-- Added by HTTrack -->
    <meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
    <head>
        <meta charset="utf-8">
        <meta property="og:site_name" content="">	
        <meta property="og:url" content="<?php echo $url; ?>">
        <meta property="og:title" content="<?php echo $title; ?>">
        <meta property="og:description" content="<?php echo $description; ?>">
        <meta property="og:author" content="<?php echo $author; ?>">        
        <meta property="og:type" content="article">
        <meta property="og:image" content="<?php echo $image; ?>">        
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/webarch/plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />   
        <link href="<?php echo base_url();?>assets/webarch/css/_print.css?_=<?php echo date('d-m-Y');?>" rel="stylesheet" type="text/css" />   
    </head>
    <body>
        <style>
            body{
                font-family: monospace;
            }
            thead > tr{
                font-weight:800;
                background-color:#eaeaea;
            }
        </style>
        <div class="container-fluid">
            <title><?php echo $title; ?></title>      
            <div id="print-paper" class="col-md-20" style="">
            <div class="col-md-12 col-xs-12">
                <div class="col-md-2 col-sm-2 col-xs-2" style="padding-left:0px;">
                    <img src="<?php echo $image;?>" style="width:150px;" class="img-responsive">
                </div>
                <div class="col-md-10 col-sm-10 col-xs-10" style="padding-left:0px;">
                    <a href='#' onclick="window.print();">
                        <?php echo $title; ?>
                    </a>
                    <?php 

                        if(!empty($flag)){
                            echo '<br>Status : ' . $flag;                
                        }else{
                            echo '<br>Status : Semua';
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
                                <td class="text-right">No</td>
                                <!-- <td>Gambar</td> -->
                                <td>Kode <?php echo $type_name;?></td>
                                <td>Nama <?php echo $type_name;?></td>              
                                <td>Telepon</td>
                                <td>Alamat</td>   
                                <td>Perusahaan</td>
                                <td>Email</td>      
                                <td style="text-align:left;">Catatan</td>                                
                                <td style="text-align:left;">Status</td>
                                <td style="text-align:left;">Nomor Identitas</td>                                                                
                                <!-- <td style="text-align:right;">Limit Piutang</td>        -->
                                <?php if($show_price == 1){?>                             
                                <!-- <td style="text-align:right;">Piutang Berjalan</td>
                                <td style="text-align:right;">Piutang Terbayar</td>
                                <td style="text-align:right;">Sisa Piutang</td> -->
                                <?php } ?>                                                                
                            </tr>
                        </thead>
                    <tbody>
                        <?php 
                        $num=1;
                        $total_qty = 0;
                        $total_trans=0;
                        foreach($content as $v):
                        
                            // if(floatval($v['contact_receivable_balance']) > 0){
                            //     $balance = number_format($v['contact_receivable_balance'],2,'.',',');
                            // }else{
                            //     $balance = '-';
                            // }

                            // if($show_price == 1){ //Root / Director
                            //     $running = floatVal($v['contact_receivable_running'] > 0) ? number_format($v['contact_receivable_running']) : '-';
                            //     $paid = floatVal($v['contact_receivable_paid'] > 0) ? number_format($v['contact_receivable_paid']) : '-';
                            //     $balance = floatVal($v['contact_receivable_balance'] > 0) ? number_format($v['contact_receivable_balance']) : '-';                          
                            // }else{
                            //     $running = '';
                            //     $paid = '-';
                            //     $balance = '-';         
                            // }
                        ?>
                        <tr data-trans-id="<?php echo $v['contact_id'];?>">
                            <td class="text-right"><?php echo $num++; ?></td>
                            <!-- <td> -->
                                <?php 
                                // foreach($v['image'] as $r => $e){
                                //     echo '<img src="'.site_url().$e['product_item_image'].'" class="img-responsive" style="width:250px">';
                                // }
                                // echo '<img src="'.$v['product_image'].'" class="img-responsive" style="width:250px">';
                                ?>
                            <!-- </td> -->     
                            <td><?php echo $v['contact_code'];?></td>  
                            <td><?php echo $v['contact_name'];?></td>  
                            <td style="text-align:left;"><?php echo $v['contact_phone_1'];?></td> 
                            <td style="text-align:left;"><?php echo $v['contact_address'];?></td>                              
                            <td style="text-align:left;"><?php echo $v['contact_company'];?></td> 
                            <td style="text-align:left;"><?php echo $v['contact_email_1'];?></td>                                                                                                   
                            <td style="text-align:left;"><?php echo $v['contact_note'];?></td>
                            <td style="text-align:left;"><?php echo $v['contact_flag_name'];?></td>
                            <td style="text-align:left;"><?php echo $v['contact_identity_number'];?></td>                            
                            <!-- <td style="text-align:right;"><?php #echo floatVal($v['contact_receivable_limit'] > 0) ? number_format($v['contact_receivable_limit']) : '-';?></td>                                                           -->
                            <?php if($show_price == 1){ ?>
                            <!-- <td style="text-align:right;"><?php #echo $running;?></td> 
                            <td style="text-align:right;"><?php #echo $paid; ?></td>    
                            <td style="text-align:right;"><?php #echo $balance; ?></td> -->
                            <?php } ?>                         
                        </tr>    
                        <?php 
                        // $total_trans = $total_trans + $v['product_price_sell'];                  
                        endforeach;
                        ?>      
                        <!-- <tr>
                            <td colspan="10"><b>Total</b></td>
                            <td style="text-align: right;"><b><?php #echo number_format($total_trans);?></b></td>                                        
                        </tr> -->
                    </tbody>
                </table> 
                <!-- </div> -->
            <!-- </div> -->

                <!-- Footer -->
                <!--<div id="print-footer" class="col-md-12 col-sm-12 col-xs-12">
                <div>Dicetak :  <?php #echo ucfirst($session['user_data']['user_name']);?> | <?php #echo date("d-m-Y H:i:s");?></div>
            </div> -->                           
        </div>    
        </div>
    </body>
</html>