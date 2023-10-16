<!doctype html>
<html lang="en">

    <!-- Mirrored from templates.g5plus.net/homeid/single-property-6.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 22 Jan 2021 07:25:27 GMT -->
    <!-- Added by HTTrack -->
    <meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
    <head>
        <meta charset="utf-8">
        <meta property="og:site_name" content="BESTPRO">	
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
                        if(!empty($group)){
                            echo '<br>Group : ' . $group;                
                        }else{
                            echo '<br>Group : Semua';
                        }

                        if(!empty($group_sub)){
                            echo '<br>Group Sub : ' . $group_sub;                
                        }else{
                            echo '<br>Group Sub : Semua';
                        }

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
                                <td class="text-center">No</td>
                                <!-- <td>Gambar</td> -->
                                <td>Kode Akun</td>
                                <td>Nama Akun</td>                                
                                <td>Group</td>
                                <td>Group Sub</td>
                                <td style="text-align:left;">Status</td>  
                            </tr>
                        </thead>
                    <tbody>
                        <?php 
                        $num=1;
                        foreach($content as $v):
                        
                            if(intval($v['account_flag']) == 0){
                                $flag_name = 'Nonaktif';
                            }else if(intval($v['account_flag']) == 1){
                                $flag_name = 'Aktif';
                            }else if(intval($v['account_flag']) == 4){
                                $flag_name = 'Terhapus';
                            }else {
                                $flag_name = 'Error';
                            }

                            if(intval($v['account_group']) == 1){
                                $group_name = 'Asset';
                            }else if(intval($v['account_group']) == 2){
                                $group_name = 'Liabilitas';
                            }else if(intval($v['account_group']) == 3){
                                $group_name = 'Ekuitas';
                            }else if(intval($v['account_group']) == 4){
                                $group_name = 'Pendapatan';
                            }else if(intval($v['account_group']) == 5){
                                $group_name = 'Biaya';
                            }else {
                                $group_name = 'Error';
                            }
                            // if($show_price == 1){ //Root / Director
                            //     $price_buy = number_format($v['product_price_buy']);
                            //     $price_sell = number_format($v['product_price_sell']);
                            // }else{
                            //     $price_buy = '-';
                            //     $price_sell = '-';
                            // }
                        ?>
                        <tr data-trans-id="<?php echo $v['account_id'];?>">
                            <td class="text-center"><?php echo $num++; ?></td>
                            <td><?php echo substr($v['account_code'],0,30);?></td>  
                            <td><?php echo substr($v['account_name'],0,30);?></td>  
                            <td style="text-align:left;"><?php echo $group_name;?></td>
                            <td style="text-align:left;"><?php echo $v['account_group_sub_name'];?></td>
                            <td style="text-align:left;"><?php echo $flag_name;?></td>             
                        </tr>    
                        <?php                 
                        endforeach;
                        ?>      
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