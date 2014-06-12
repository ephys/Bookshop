<div id="double_book">
	<div id="book_right">
		<div class="bookText">
			<h2>Bookshop: Mod</h2>
			<p><a href="mod/#mod">Informations</a> - <a href="http://twitter.com/ephyspotato/"><?=Language::translate('BOOK_AUTHOR')?></a> - <a href="https://github.com/Ephys/Bookshop_mod">Github</a></p>
			<ul>
				<?php
				$mods = array_reverse(scandir(ROOT."assets/misc/archives/mod/"));

				$lastMinecraft = null;
				foreach($mods as $mod) {
					if(pathinfo($mod, PATHINFO_EXTENSION) !== 'zip')
						continue;

					$infos = explode('_', substr($mod, 0, strlen($mod)-4));

					if($lastMinecraft != $infos[3]) {
						echo '<h3>Minecraft '.$infos[3].'</h3>';
						$lastMinecraft = $infos[3];
					}

					if(!isset($infos[4])) {
						echo '<li><a target="_blank" href="http://adf.ly/558995/'.WEBSITE_ROOT.'assets/php/download.php?type=mod&ref='.$infos[1].'&mc='.$lastMinecraft.'">Bookshop '.$infos[1].'</a> - <a target="_blank" href="http://adf.ly/558995/'.WEBSITE_ROOT.'assets/php/download.php?type=mod&src=true&ref='.$infos[1].'&mc='.$lastMinecraft.'">Source</a>'.(file_exists(ROOT."assets/misc/archives/mod/bookshop_".$infos[1].".changelog")?' - <a target="_blank" href="assets/misc/archives/mod/bookshop_'.$infos[1].'.changelog">changelog</a>':'').'</li>';
					}
				}
				?>
			</ul>
		</div>
	</div>
	<div id="book_left">
		<div class="bookText" id="gui">
			<h2>Mod Extension - GUI</h2>
			<p><a href="mod/#gui">Informations</a> - <a href="http://twitter.com/EphysPotato/"><?=Language::translate('BOOK_AUTHOR')?></a> - <a href="https://github.com/Ephys/Bookshop_mod">Github</a></p>
			<ul>
				<?php
				$mods = scandir("assets/misc/archives/mod_gui/");

				$lastMinecraft = -1;
				foreach(array_reverse($mods) as $mod) {
					if($mod == '.' || $mod == '..' || $mod == '.htaccess')
						continue;
					$infos = explode('_', substr($mod, 0, strlen($mod)-4));

					if($lastMinecraft != $infos[3]) {
						echo '<h3>Minecraft '.$infos[3].'</h3>';
						$lastMinecraft = $infos[3];
					}
					if(!isset($infos[4]))
						echo '<li><a target="_blank" href="http://adf.ly/558995/'.WEBSITE_ROOT.'assets/php/download.php?type=mod_gui&ref='.$infos[1].'&mc='.$lastMinecraft.'">Bookshop GUI '.$infos[1].'</a> - <a target="_blank" href="http://adf.ly/558995/'.WEBSITE_ROOT.'assets/php/download.php?type=mod_gui&src=true&ref='.$infos[1].'&mc='.$lastMinecraft.'">Source</a></li>';
				}
				?>
			</ul>
			<p><a class="book_navig_left" id="showPlugin" href="#"></a></p>
		</div>
		<div class="bookText" id="plugin">
			<h2>Bookshop: Plugin</h2>
			<p><a href="mod/#plugin">Informations</a> - <a href="http://twitter.com/ralmn45/"><?=Language::translate('BOOK_AUTHOR')?></a> - <a href="https://github.com/ralmn/BookShopPlugin">Github</a></p>
			<ul>
				<?php
				$mods = scandir("assets/misc/archives/plugin/");

				$lastMinecraft = -1;
				foreach(array_reverse($mods) as $mod) {
					if($mod == '.' || $mod == '..' || $mod == '.htaccess')
						continue;
					$infos = explode('_', substr($mod, 0, strlen($mod)-4));

					if($lastMinecraft != $infos[3]) {
						echo '<h3>Minecraft '.$infos[3].'</h3>';
						$lastMinecraft = $infos[3];
					}
					if(!isset($infos[4]))
						echo '<li><a target="_blank" href="http://adf.ly/558995/'.WEBSITE_ROOT.'assets/php/download.php?type=plugin&ref='.$infos[1].'&mc='.$lastMinecraft.'">Bookshop '.$infos[1].'</a></li>';
				}
				?>
			</ul>
			<p><a class="book_navig_left" id="showMod" href="#"></a></p>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#plugin").hide();
				$("a#showMod").click(function (e) {
					e.preventDefault();
					$("#book_left .bookText#gui").show();
					$("#book_left .bookText#plugin").hide();
					return false;
				});

				$("a#showPlugin").click(function (e) {
					e.preventDefault();
					$("#book_left .bookText#gui").hide();
					$("#book_left .bookText#plugin").show();
					return false;
				});
			});
		</script>
	</div>
</div>