<!DOCTYPE html>
<html lang="en">

    <head>
        <meta name="description" content="Webpage description goes here" />
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="">
        <link href="<?php echo base_url(); ?>assets/webarch/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    </head>
    <style>
        @import url('https://fonts.cdnfonts.com/css/dot-matrix');
        body{
            /* font-family: monospace; */
            font-family: 'Dot Matrix', sans-serif!important;
        }
        .title{
            font-weight: 800;
            text-transform: uppercase;
            text-align: left;
        }
        .mariska > thead > tr > th {
            padding-top: 0px;
            padding-bottom: 0px;
        }
        .mariska > tbody > tr > td {
            padding-top: 0px;
            padding-bottom: 0px;
        }    
        .no-border > tbody > tr > td{
            border-top:0px;
        }   
    </style>
    <body>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-sm-12 col-xs-12" style="">
                    <!-- HEADER -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <p>
                        <div class="col-xs-3">
                            <img src="<?php echo $branch_logo; ?>" class="img-responsive" style="width: 134px;">
                        </div>
                        <div class="col-xs-6">
                            <p style="text-align:center;">
                                <b><?php echo strtoupper($result['branch']['branch_name']); ?></b><br>
                                <?php echo $result['branch']['branch_address']; ?><br>
                                Tel:<?php echo $result['branch']['branch_phone_1']; ?>, 
                                Email:<?php echo $result['branch']['branch_email_1']; ?>          
                            </p>
                        </div>
                        <div class="col-xs-3 text-right">
                            <b onclick="window.print();" style="cursor:pointer;"><?php echo $title; ?></b><br>
                            <?php echo $header['trans_number']; ?>
                        </div>          
                        </p>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:4px;">
                        <p>
                        <div class="col-xs-7" style="padding-left:0;">
                            <b>Customer:</b><br>
                            <?php echo $header['contact_name']; ?><br>
                            <?php echo $header['contact_address']; ?><br>
                            <?php echo $header['contact_phone_1']; ?><br>
                            <?php echo $header['contact_email_1']; ?><br>                                              
                        </div>
                        <div class="col-xs-5">
                            <table class="mariska table no-border" style="margin-bottom: 0px;">
                                <tr><td>Tanggal</td><td class="text-right"><?php echo date("d-M-Y", strtotime($header['trans_date'])); ?></td></tr>
                                <tr><td>Tanggal Jth Tempo</td><td class="text-right"><?php echo date("d-M-Y", strtotime($header['trans_date_due'])); ?></td></tr>
                                <!-- <tr><td>Sales</td><td>:</td></tr> -->
                                <tr><td>Nomor PO Customer</td><td class="text-right"><?php echo $header['trans_ref_number']; ?></td></tr>
                                <tr><td>Plat Nomor</td><td class="text-right"><?php echo $header['trans_vehicle_plate_number']; ?></td></tr>              
                                <tr><td>Pembayaran</td><td class="text-right"><?php echo ($header['contact_termin'] == 0) ? ' Cash (COD)' : ' ' . $header['contact_termin'] . ' Hari'; ?></td></tr>
                                <tr><td>Salesman</td><td class="text-right"><?php echo $header['sales_fullname']; ?></td></tr>                                       
                            </table>
                        </div>          
                        </p>
                    </div>      

                    <!-- CONTENT -->
                    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:0px;">
                        <table class="mariska table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-left">Produk</th>
                                    <th class="text-right">Qty</th>
                                    <th class="text-right">Harga</th>
                                    <th class="text-right">Diskon</th>            
                                    <th class="text-left">Pajak</th>            
                                    <th class="text-right">Total</th>            
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $num = 1;
                                $total_sub = 0;
                                $total_ppn = 0;
                                $total_discount = 0;
                                $total_discount_row = 0;
                                $total_grand = 0;
                                $total_voucher = ($header['trans_voucher'] > 0) ? $header['trans_voucher'] : 0;
                                foreach ($content as $v) {
                                    $ppn = 0;

                                    //Trans Note
                                    $trans_note = '-';
                                    if (!empty($v['trans_item_note'])) {
                                        $trans_note = '<br><b><i>' . $v['trans_item_note'] . '</i></b>';
                                    }

                                    //Pajak
                                    $price_qty = ($v['trans_item_sell_price'] * $v['trans_item_out_qty']) - $v['trans_item_discount'];
                                    $ppn_label = $v['trans_item_ppn'] == 1 ? 'P' : 'N';
                                    if ($v['trans_item_ppn'] == 1) {
                                        $ppn = $price_qty * ($v['trans_item_ppn_value'] / 100);
                                        // $price_qty = ($v['trans_item_in_price']*$v['trans_item_in_qty']) + $ppn;
                                    }

                                    $total_sub = $total_sub + $price_qty;
                                    $total_ppn = $total_ppn + $ppn;
                                    $total_discount_row = $total_discount_row + $v['trans_item_discount'];
                                    echo '<tr>';
                                    echo '<td class="text-center">' . $num++ . '</td>';
                                    echo '<td>' . $v['product_name'] . '</td>';
                                    echo '<td style="text-align:right;">' . number_format($v['trans_item_out_qty'], 2, '.', ',') . ' ' . $v['trans_item_unit'] . '</td>';
                                    echo '<td style="text-align:right;">' . number_format($v['trans_item_sell_price'], 2, '.', ',') . '</td>';
                                    echo '<td style="text-align:right;">' . number_format($v['trans_item_discount'], 2, '.', ',') . '</td>';
                                    echo '<td style="text-align:left;">' . $ppn_label . '</td>';
                                    echo '<td style="text-align:right;">' . number_format($price_qty, 2, '.', ',') . '</td>';
                                    echo '</tr>';
                                }

                                // $total_grand = $total_sub + $total_ppn + $total_discount;
                                $total_discount = $header['trans_discount'];
                                $total_grand = (($total_sub + $total_ppn) - $total_discount) + $total_discount_row;
                                $total_grand = $total_grand - $total_voucher;
                                // $total_grand = (($total_sub + $total_ppn) - $total_discount) + $total_discount_row;            
                                if ((floatval($header['trans_total_ppn']) > 0) or floatval($header['trans_discount']) > 0) {
                                    echo '<tr>';
                                    echo '<td colspan="5"></td>';
                                    echo '<td style="text-align:right"><b>Subtotal</b></td>';
                                    echo '<td style="text-align:right">' . number_format($total_sub, 2, '.', ',') . '</td>';
                                    echo '</tr>';
                                }
                                if (floatval($header['trans_total_ppn']) > 0) {
                                    echo '<tr>';
                                    echo '<td colspan="5"></td>';
                                    echo '<td style="text-align:right"><b>Ppn</b></td>';
                                    echo '<td style="text-align:right">' . number_format($total_ppn, 2, '.', ',') . '</td>';
                                    echo '</tr>';
                                }
                                if (floatval($header['trans_discount']) > 0) {
                                    echo '<tr>';
                                    echo '<td colspan="5"></td>';
                                    echo '<td style="text-align:right"><b>Diskon Nota</b></td>';
                                    echo '<td style="text-align:right">' . number_format($total_discount, 2, '.', ',') . '</td>';
                                    echo '</tr>';
                                }
                                if (floatval($header['trans_voucher']) > 0) {
                                    echo '<tr>';
                                    echo '<td colspan="5"></td>';
                                    echo '<td style="text-align:right"><b>Voucher</b></td>';
                                    echo '<td style="text-align:right"> -' . number_format($total_voucher, 2, '.', ',') . '</td>';
                                    echo '</tr>';
                                }                                   
                                // echo '<tr>';
                                //   echo '<td colspan="4"></td>';
                                //   echo '<td style="text-align:right"><b>Total Discount</b></td>';
                                //   echo '<td style="text-align:right">'.number_format($total_discount,2,'.',',').'</td>';
                                // echo '</tr>';   
                                echo '<tr>';
                                echo '<td colspan="5"></td>';
                                echo '<td style="text-align:right"><b>Grand Total</b></td>';
                                echo '<td style="text-align:right">' . number_format($total_grand, 2, '.', ',') . '</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td colspan="6">Terbilang: ' . $say_number . '</td>';
                                echo '</tr>';
                                ?>
                            </tbody>
                        </table>
                    </div>  
                    <div class="col-md-12 col-xs-12" style="">
                        Keterangan:<?php echo $header['trans_note']; ?>
                        <br>
                    </div>
                    <div class="col-md-12 col-xs-12">
                        <h5><b>Riwayat Pelunasan Piutang</b></h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-left">Tgl</th>
                                    <th class="text-left">Keterangan</th>
                                    <th class="text-right">Debit</th>
                                    <th class="text-right">Credit</th>
                                    <th class="text-right">Balance</th>    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($result['trans_history']) > 0) {
                                    foreach ($result['trans_history'] as $v):
                                        ?>
                                        <tr>
                                            <td class="text-left"><?php echo date('d-M-Y, H:i', strtotime($v['temp_date'])); ?></td>
                                            <td class="text-left">
                                                <?php echo $v['temp_number']; ?><br>
                                                <?php echo $v['temp_note']; ?><br>                    
                                            </td>
                                            <td class="text-right"><?php echo number_format($v['temp_debit'], 2, '.', ','); ?></td>
                                            <td class="text-right"><?php echo number_format($v['temp_credit'], 2, '.', ','); ?></td>
                                            <td class="text-right"><?php echo number_format($v['temp_balance'], 2, '.', ','); ?></td>                                    
                                        </tr>
                                        <?php
                                    endforeach;
                                } else {
                                    ?>
                                    <tr><td colspan="5">Tidak ada riwayat pembayaran</td></tr>
                                <?php }
                                ?>
                            </tbody>
                        </table>
                    </div>      
                </div>
            </div>
        </div>

        <script>
        </script>
    </body>
</html>