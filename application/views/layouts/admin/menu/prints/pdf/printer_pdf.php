<?php
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $header['title'];?></title>
		<!-- <link href="<?php #echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/> -->
        <style>
            /* @media print{ */
                body{
                    font-family:Tahoma, Verdana, Segoe, sans-serif;
                }
                table{
                    width:100%;
                }
                table > tr:nth-child(1) > td{
                    border-bottom:1px solid red;
                }

            /* } */
        </style>
    </head>
	<body>
		<table id="table_header">
			<tr>
				<td style="width:7cm;"><b>Kepada</b></td>
				<td><b>Invoice</b></td>
				<td><b>Dari</b></td>
			</tr>
			<tr>
				<td>Bpk John</td>
				<td>INV-2301-001</td>
				<td>Toko Alam</td>
			</tr>
		</table>
		<table id="table_content" style="margin-top:10px;">
			<tr>
				<td style="border-top:1px solid black;border-bottom:1px solid black;text-align:center;"><b>No</b></td>
				<td style="border-top:1px solid black;border-bottom:1px solid black;"><b>Produk</b></td>
				<td style="border-top:1px solid black;border-bottom:1px solid black;text-align:right;"><b>Qty</b></td>
				<td style="border-top:1px solid black;border-bottom:1px solid black;"><b>Satuan</b></td>
				<td style="border-top:1px solid black;border-bottom:1px solid black;text-align:right;"><b>Harga</b></td>				
			</tr>
			<?php 
			$num = 1;
			$total = 0;
			foreach($content as $v):
				echo '<tr>';
					echo '<td style="text-align:center;">'.$num++.'</td>';
					echo '<td>'.$v['product_name'].'</td>';
					echo '<td style="text-align:right;">'.$v['trans_item_out_qty'].'</td>';
					echo '<td>'.$v['product_unit'].'</td>';
					echo '<td style="text-align:right;">'.number_format($v['trans_item_sell_price'],2,'.',',').'</td>';										
				echo '</tr>';
				$total = $total + $v['trans_item_sell_price'];
			endforeach;
				echo '<tr>';
					echo '<td colspan="4" style="border-top:1px solid black;"><b>Total</b></td>';
					echo '<td style="border-top:1px solid black;text-align:right;"><b>'.number_format($total,2,'.',',').'</b></td>';					
				echo '</tr>';
			?>
		</table>
		<table id="table_information" style="margin-top:20px;">
			<tr>
				<td style="border-bottom:1px solid black;width:50%;"><b>Keterangan</b></td>
				<td style="border-bottom:1px solid black;width:50%;"><b>Informasi</b></td>
			</tr>
			<tr>
				<td style="word-wrap:initial;">- Harga sudah termasuk Ppn</td>
				<td><i>Pembayaran dapat dilakukan di rekening Berikut ini yang sudah dituliskan</i></td>
			</tr>
		</table>
		<table id="table_footer" style="margin-top:20px;">
			<tr>
				<td style="border-top:1px solid black;text-align:center;"><b>Creator</b></td>
				<td style="border-top:1px solid black;text-align:center;"><b>Approval</b></td>
				<td style="border-top:1px solid black;text-align:center;"><b>Warehouse</b></td>
				<td style="border-top:1px solid black;text-align:center;"><b>Customer</b></td>								
			</tr>
			<tr>
				<td style="border-bottom:1px solid black;text-align:center;height:2cm;width:4cm;">Bpk John<br><?php echo date("d-M-Y, H:i");?></td>
				<td style="border-bottom:1px solid black;text-align:center;width:4cm;">Toko Alam<br><b>Approve</b><br><?php echo date("d-M-Y, H:i");?></td>
				<td style="border-bottom:1px solid black;text-align:center;width:4cm;">Toko Alam</td>								
				<td style="border-bottom:1px solid black;text-align:center;width:4cm;">Toko Alam</td>
			</tr>
		</table>				
	</body>
</html>