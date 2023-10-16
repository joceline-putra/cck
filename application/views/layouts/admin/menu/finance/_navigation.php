		<ul class="nav nav-tabs" role="tablist">
			<!--
			<li class="hide" data-name="statistic">
				<a href="<?php #echo base_url('finance');?>">
					<span class="fas fa-file"></span> Statistik
				</a>
			</li> -->
			<?php
			$ng = $session['user_data']['menu_access'];
			foreach($ng as $b){
				if($b['menu_group_id'] == 43){
					foreach($b['sub_menu'] as $n){
						$navigation_url = base_url() . $n['menu_link'];
						if($n['user_menu_flag'] == 1){
							echo '<li class="" data-name="' . $n['menu_link'] . '">';
								echo '<a href="' . $navigation_url . '">';
									echo '<span class="'.$n['menu_icon'].'"></span>&nbsp;' . $n['menu_name'];
								echo '</a>';
							echo '</li>';            
						}
					}            
				}
			}
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