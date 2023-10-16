<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="description" content="Webpage description goes here" />
  <meta charset="utf-8">
  <title><?php echo $title; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="">
  <link href="<?php echo base_url();?>assets/webarch/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
</head>

<body>
  
<div class="container">
  <div class="row">
  	<div class="col-md-4 col-sm-12 col-xs-12">
  		<!-- HEADER -->
  		<div class="col-md-12 col-sm-12 col-xs-12">
        <p style="text-align:center;">
          Foodpedia Banyumanik<br>
          Jl. Banyumanik Nomor 2
        </p>
        <p style="border-top:1px dotted gray;">
          <div class="col-xs-6">
            <?php echo date("d M Y", strtotime($header['order_date']));?><br>
            <?php echo $header['order_number'];?>
            </div>
          <div class="col-xs-6">
      			<?php echo $header['contact_name'];?><br>
      			<?php echo $header['ref_name'];?>
          </div>
        </p>
  		</div>

		<!-- CONTENT -->
  		<div class="col-md-12 col-sm-12 col-xs-12">
 	 		<table class="table table-bordered">
 	 			<thead>
 	 				<tr>
 	 					<th>Qty</th>
 	 					<th>Item</th>
 	 				</tr>
 	 			</thead>
 	 			<tbody>
 	 				<?php
 	 				foreach($content as $v){

 	 					if(!empty($v['order_item_note'])){
 	 						$order_note = '<br><b><i>'.$v['order_item_note'].'</i></b>';
 	 					}else{
 	 						$order_note = '-';
 	 					}
 	 				?>
 	 				<tr>
 	 					<td><?php echo $v['order_item_qty'];?>&nbsp;Pcs</td> 	 					
 	 					<td>
 	 						<?php echo $v['product_name'];?><br>
 	 						<?php echo $order_note;?>
 	 					</td>
 	 				</tr>
 	 				<?php 
 	 				}
 	 				?>
 	 			</tbody>
 	 		</table>
  		</div>  	

		<!-- FOOTER -->
  		<div class="col-md-12 col-sm-12 col-xs-12">
  			<p></p>
  		</div>  			
  	</div>
  </div>
</div>

<script>
</script>
</body>
</html>