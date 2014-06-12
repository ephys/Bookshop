		</div>
	</div>
	<div id="sidebar">
		<p>Menu</p>
		<div id="innersidebar">
			<a href=""><img src="assets/img/logo_text.png" alt="Bookshop Logo" title="Bookshop Logo"></a>
			<ul class="menu">
				<li><a href="about/"><?=Language::translate('MENU_INDEX')?></a></li>
				<li><a href="gallery/"><?=Language::translate('MENU_GALLERY')?></a></li>
				<?php if(isset($_SESSION['username']))
				{ ?><li><a href="creations/"><?=Language::translate('MENU_PROFILE')?></a></li>
				<li><a href="stats/"><?=Language::translate('MENU_STATS')?></a></li>
				<li><a href="assets/php/disconnect.php"><?=Language::translate('MENU_DISCONNECT')?></a></li><? } ?>
			</ul>
			<ul class="menu">
				<li onclick="$('#paypal_donation').submit();">
					<a href="#">
					<?=Language::translate('MENU_DONATION')?>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal_donation" style="display:none;"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="6J5TRJ27MEG9A"></form>
					</a>
				</li>
				<li><a href="http://mcnetwork.fr.nf/documents/"><?=Language::translate('MENU_MENTIONS')?></a></li>
			</ul>
		</div>
		<footer><p>Copyright &copy; <a href="http://ephys.fr.nf/">Ephys</a> 2012 - <?=date('Y')?>, <a forceReload="true" href="<?=PAGE_RELATIVE?>l/fr_FR/">Français</a> - <a forceReload="true" href="<?=PAGE_RELATIVE?>l/en_EN/">English</a></p></footer>
	</div>
	<script type="text/javascript">
		$("body").delegate('a', 'click', function(e) {
			if(($(this).attr('target') === undefined) && ($(this).attr('forceReload') === undefined) && (this.href.indexOf('bookshop') !== -1)) {
				// chargement ajax de la page demandée
				$("#loader").show();
				$("#innercorps").load(this.href, function() {
					$("#loader").hide();
				});

				// ajout de la page à l'historique: state = url de la nouvelle page
				history.pushState(this.href, "Bookshop", this.href);

				e.preventDefault();
			}
		});

		// détection d'une navigation dans l'historique
		window.addEventListener('popstate', function(event) {
			if(event.state !== null) {
				$("#innercorps").load(event.state);
			}

			event.preventDefault();
		});

		// modification des données de l'historique sur la page actuelle (state = url de la page)
		history.replaceState(document.location.href, document.title, document.location.href);
	</script>
	<script type="text/javascript">
		var uvOptions = {};
		(function() {
			var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
			uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/heOnU8ErR3aicaCm1E1vCQ.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
		})();
	</script>
	<script id="_wauo0p">
		var _wau = _wau || [];
		_wau.push(["map", "02xupvl4lybm", "o0p", "420", "210", "natural", "star-blue"]);
		(function() {var s=document.createElement("script"); s.async=true;
			s.src="http://widgets.amung.us/map.js";
			document.getElementsByTagName("head")[0].appendChild(s);
		})();
	</script>
</body>
</html>