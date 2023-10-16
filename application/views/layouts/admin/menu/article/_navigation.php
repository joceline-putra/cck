		<ul class="nav nav-tabs" role="tablist">
			<li class="" data-name="layouts/admin/menu/article/article">
			  	<a href="<?php echo base_url('article/article');?>">
          			<span class="fas fa-newspaper"></span> Artikel
          		</a>
			</li>
			<li class="" data-name="layouts/admin/menu/article/category_article">
			  	<a href="<?php echo base_url('category/article');?>">
          			<span class="fas fa-filter"></span> Kategori Artikel
          		</a>
			</li>											
			<?php 
			/*
				foreach($navigation as $n):
					$navigation_url = base_url().$n['menu_link']; 
					echo '<li class="" data-name="'.$n['menu_link'].'">';
					  	echo '<a href="'.$navigation_url.'">';
		          			echo '<span class="fas fa-file-alt"></span>&nbsp;'.$n['menu_name'];
		        		echo '</a>';
					echo '</li>';
				endforeach;
			*/
			?>                             
		</ul>