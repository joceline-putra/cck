<ul class="nav nav-tabs" role="tablist">
    <!-- <li class="" data-name="asset/statistic">
        <a href="<?php #echo base_url('asset/statistic'); ?>">
            <span class="fas fa-file"></span> Statistik
        </a>
    </li> -->
    <li class="" data-name="layouts/admin/menu/asset/index">
        <a href="<?php echo base_url('asset'); ?>">
            <span class="fas fa-file-alt"></span> Aset
        </a>
    </li>
    <!-- <li class="" data-name="asset/buy">
        <a href="<?php #echo base_url('asset/buy'); ?>">
            <span class="fas fa-file-alt"></span> Beli Aset
        </a>
    </li> -->
    <li class="" data-name="asset/sell">
        <a href="<?php echo base_url('asset/sell'); ?>">
            <span class="fas fa-file-alt"></span> Jual Aset
        </a>
    </li>
    <li class="" data-name="asset/depreciation">
        <a href="<?php echo base_url('asset/depreciation'); ?>">
            <span class="fas fa-file-alt"></span> Penyusutan
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