<div id="double_book">
	<div id="book_right">
		<div class="bookText">
			<?=Language::translate('MOD_INFO_HEAD')?>
			<p><a class="button" href="download/"><?=Language::translate('DOWNLOAD')?></a>
			<?php
				if(isset($_SESSION['username']))
					echo '<a class="button" href="http://mcnetwork.fr.nf/">'.Language::translate('GEN_API').'</a></p>';
				else
					echo '</p>'.Language::translate('GEN_API_UNREGISTERED');
			?>
			<?=Language::translate('COMMANDS_USE_INFO')?>
		</div>
	</div>
	<div id="book_left">
		<div class="bookText">
			<?=Language::translate('MOD_USE_INFO')?>
			<?=Language::translate('MOD_GUI_USE_INFO')?>
			<img src="assets/img/crafts/bookstore.png" alt="bookstore craft recipe" title="Bookstore Recipe">
			<?=Language::translate('PLUGIN_USE_INFO')?>
		</div>
	</div>
</div>