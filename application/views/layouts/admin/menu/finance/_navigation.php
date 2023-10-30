		<ul class="nav nav-tabs" role="tablist">
			<!--
			<li class="hide" data-name="statistic">
				<a href="<?php #echo base_url('finance');?>">
					<span class="fas fa-file"></span> Statistik
				</a>
			</li> -->
			<?php 
			foreach($session['user_data']['menu_access'] as $i):

				if($i['menu_group_id'] == 43){
					foreach($i['sub_menu'] as $v){
						if($v['user_menu_flag'] == 1){
							$menu_icon = !empty($v['menu_icon']) ? $v['menu_icon'] : 'fas fa-file';
							echo '
								<li class="" data-name="'.$v['menu_link'].'">
									<a href="'.base_url($v['menu_link']).'">
										<span class="'.$menu_icon.'"></span> '.$v['menu_name'].'
									</a>
								</li>                
							';          
						}
					}
				}
			endforeach;
			?>			
			<!--
			<li class="" data-name="layouts/admin/menu/finance/cost_out">
				<a href="<?php echo base_url('finance/cost_out');?>"><span class="fas fa-file-alt"></span>&nbsp;Biaya</a>
			</li>
			<li class="" data-name="layouts/admin/menu/finance/general_journal">
				<a href="<?php echo base_url('finance/general_journal');?>"><span class="fas fa-file-alt"></span>&nbsp;Jurnal Umum</a>
			</li>
			<li class="hide" data-name="layouts/admin/menu/finance/cash_out">
				<a href="<?php echo base_url('finance/cash_out');?>"><span class="fas fa-file-alt"></span>&nbsp;Kirim Uang</a>
			</li>
			<li class="" data-name="layouts/admin/menu/finance/cash_in">
				<a href="<?php echo base_url('finance/cash_in');?>"><span class="fas fa-file-alt"></span>&nbsp;Terima Uang</a>
			</li>
			<li class="" data-name="layouts/admin/menu/finance/bank_statement">
				<a href="<?php echo base_url('finance/bank_statement');?>"><span class="fas fa-file-alt"></span>&nbsp;Transfer Uang</a>
			</li>-->                     
		</ul>