<?php
$book = isset($_GET['book'])?$_GET['book']:'null';
$page = isset($_GET['page'])?(int)$_GET['page']:0;
$page = ($page < 0)?0:($page%2 != 0)?($page-1):$page;
?>
<div id="double_book">
	<div id="book_right">
		<div class="bookText">
			<h2><?=Language::translate('MENU_GALLERY')?></h2>
			<form id="loadBooks" autocomplete="off">
				<input name="username" style="width: 95%;" class="input-xlarge" type="text" placeholder="<?=Language::translate('USERNAME')?>">
				<input name="title" style="width: 95%;" class="input-xlarge" type="text" placeholder="<?=Language::translate('BOOK_TITLE')?>">
				<input class="half" type="submit" data-loading-text="Loading..." value="<?=Language::translate('PROFILE_LOAD')?>">
				<button class="half loadList" id="randomBooks" data-loading-text="Loading..."><?=Language::translate('GALLERY_RANDOM')?></button>
				<button class="half loadList" id="lastestBooks" data-loading-text="Loading..."><?=Language::translate('GALLERY_LASTEST')?></button>
				<button class="half loadList" id="bestBooks" data-loading-text="Loading..."><?=Language::translate('GALLERY_BESTVOTES')?></button>
			</form>
		</div>
	</div>
	<div id="book_left"><div class="bookText"></div></div>
</div>
<script type="text/javascript" src="assets/js/jquery.zclip.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-typeahead.min.js"></script>
<script type="text/javascript" src="assets/js/cookies.js"></script>
<script type="text/javascript" src="assets/js/gallery.page.js"></script>
<script type="text/javascript">
	var bookLoader = new BookLoader('<?=API_ROOT?>', <?=$page?>, <?=$book?>);

	$("button.loadList").click(function(event) {
		var self = this;
		$(self).button('loading');
		bookLoader.loadBooklist({method: $(self).attr('id')}, function() {
			$(self).button('reset');
		});
		
		event.preventDefault();
	});

	$("form#loadBooks").submit(function(event) {
		var data = $("#loadBooks").serializeObject();
		if($("#loadBooks input[name=\"username\"]").val() != '')
			data['method'] = "authorBooks";
		else if($("#loadBooks input[name=\"title\"]").val() != '')
			data['method'] = "titleList";
		else
			return false;

		$(this).children('input[type=submit]').button('loading');
		bookLoader.loadBooklist(data, function() {
			$("#loadBooks").children('input[type=submit]').button('reset');
		});

		event.preventDefault();
	});

	var searchTimeout = null;
	$('#loadBooks input[name="username"], #loadBooks input[name="title"]').keydown(function() {
		clearTimeout(searchTimeout);
	});

	$('#loadBooks input[name="username"]').keyup(function(e) {
		if(e.keyCode === undefined || e.keyCode == 13 || e.keyCode == 37 || e.keyCode == 38 || e.keyCode == 39 || e.keyCode == 40)
			return true;

		clearTimeout(searchTimeout);

		if($('#loadBooks input[name="username"]').val())
			searchTimeout = setTimeout(function() {
				bookLoader.searchNeedle('searchAuthor', $('#loadBooks input[name="username"]').val(), function(userlist) {
					var autocomplete = $('#loadBooks input[name="username"]').typeahead();
					autocomplete.data('typeahead').source = userlist;

					// todo: understand why I wrote those 2 lines
					$('#loadBooks input[name="username"]').keypress();
					$('#loadBooks input[name="username"]').keyup();
				});
			}, 200);

		if($(this).val())
			$('#loadBooks input[name="title"]').css('opacity', '0.3').attr("disabled", "disabled");
		else
			$('#loadBooks input[name="title"]').css('opacity', '1.0').removeAttr("disabled");
	});

	$('#loadBooks input[name="title"]').keyup(function(e) {
		if(e.keyCode === undefined || e.keyCode == 13 || e.keyCode == 37 || e.keyCode == 38 || e.keyCode == 39 || e.keyCode == 40)
			return true;

		clearTimeout(searchTimeout);

		if($('#loadBooks input[name="title"]').val())
			searchTimeout = setTimeout(function() {
				bookLoader.searchNeedle('searchTitle', $('#loadBooks input[name="title"]').val(), function(titlelist) {
					var autocomplete = $('#loadBooks input[name="title"]').typeahead();
					autocomplete.data('typeahead').source = titlelist;

					// todo: understand why I wrote those 2 lines
					$('#loadBooks input[name="title"]').keypress();
					$('#loadBooks input[name="title"]').keyup();
				})
			}, 200);

		if($(this).val())
			$('#loadBooks input[name="username"]').css('opacity', '0.3').attr("disabled", "disabled");
		else
			$('#loadBooks input[name="username"]').css('opacity', '1.0').removeAttr("disabled");
	});

	$('body').keyup(function(e) {
		if(e.keyCode == 39) {
			bookLoader.navigate('right');
		} else if(e.keyCode == 37) {
			bookLoader.navigate('left');
		}
	});

	var randomLettersInterval = setInterval(changeRandomLetters, 100);

	function changeRandomLetters() {
		$(".randomLetters").each(function() {
			var length = $(this).text().length;
			var text = '';
			for(var i = 0; i < length; i++) {
				text += String.fromCharCode(Math.round(Math.random()*190)+190);
			}
			$(this).text(text);
		});
	}

	window.addEventListener('popstate', function(event) {
		if(event.state !== null && typeof(event.state) == 'object') {
			bookLoader.goto(event.state.page);
		} else if(event.state !== null) {
			$("#innercorps").load(event.state);
		}

		event.preventDefault();
	});
</script>