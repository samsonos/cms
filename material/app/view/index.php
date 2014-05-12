<!-- Top section -->
<section class="top clearfix">

	<!-- Header block -->
	<?php if(isv('cmsnav_Name')):?>
		<h1 class="icon2 icon_16x16 left icon-structure">
			<!--  <a href="<?php url_base('structure', 'cmsnav_id')?>" title="Перейти к редактированию ЭСС"></a>-->
			<?php v('cmsnav_Name'); ?>
			&rArr;
		</h1>		
	<?php endif?>
	<h1 class="icon2 icon_16x16 left icon-material" title="Таблица материалов">Материалы:</h1>
	<!-- End of Header block -->	
	
	<!-- Table search block -->
	<form class="search" method="POST">
		<input name="search" id="search" placeholder="Укажите поисковый запрос..." value="<?php iv('search')?>">
	</form>	
	<!-- End of Table search block -->	
	
	<!-- Pager block -->
<?php if(isv('pager_html')):?>	
	<ul class="table-pager">
		<li>Отображать:</li>
		<?php v('pager_html')?>		
	</ul>
<?php endif?>	
	<!-- End of Pager block -->
	
</section>	
<!-- End of Top section -->

<!-- Content section -->
<section id="content">
	<?php v('table_html'); ?>
</section>
<!-- End of Content section -->		
