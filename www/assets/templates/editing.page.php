<?php
if(!isset($_SESSION['user_id']))
	header('Location: '.WEBSITE_ROOT);
?>
<form class="form-horizontal" id="bookEditorForm">
	<input type="hidden" name="action" value="2"/>
	<div id="double_book">
		<div id="book_right">
			<div class="bookText">
			</div>
		</div>
		<div id="book_left">
			<div class="bookText" id="page_preview">
			</div>
			<div class="bookText" id="page_options">
			</div>
		</div>
	</div>
</form>
<script type="text/javascript" src="assets/js/jquery.zclip.js"></script>
<script type="text/javascript" src="assets/js/jquery.autosize.js"></script>
<script type="text/javascript" src="assets/js/editing.page.js"></script>
<script type="text/javascript">
	var bookEditor = new BookEditor(parseInt(<?=isset($_GET['book'])?$_GET['book']:-1?>), "#book_right .bookText");

	// load a book + display editor.
	pagesCount = 0;



	function bindzClip() {
		$("#btn_copy").zclip({
			path:"<?=WEBSITE_ROOT?>assets/misc/ZeroClipboard.swf",
			copy: $("#command").val(),
			beforeCopy: function() {
				$("#btn_copy").html("<?=Language::translate('COPY')?>");
			},
			afterCopy:function() {
				$("#btn_copy").html("<?=Language::translate('COPIED')?>");
			}
		});
	}

	$('body').delegate('#btn_addpage', 'click', function(e) {
		e.preventDefault();
		$("#pages").append("<div class=\"controls\"><textarea name=\"pages[]\" type=\"text\" class=\"input-xlarge pagebox\" placeholder=\"Page "+(pagesCount)+"\" id=\"pagebox_"+(pagesCount++)+"\"></textarea></div>");
		setSize();
		return false;
	});

	$('body').delegate('.pagebox', 'focus', function(key) {
		updatePreview(this);
	});
	$('body').delegate('.pagebox', 'click', function(key) {
		updatePreview(this);
	});
	$('body').delegate('.pagebox', 'keydown', function(key) {
		if(key.ctrlKey) {
			switch(key.keyCode) {
				case 83: // s
					// key.preventDefault();
					$('#bookEditorForm').submit();
					return false;
				case 80: // p
					// key.preventDefault();
					$("#btn_addpage").click();
					return false;
			}
		}
		if(key.keyCode != 8 || $(this).prop("selectionStart") != 0 || $(this).prop("selectionEnd") != 0)
			return;
		var id = $(this).attr("id").split("_");
		var prevbox = "#pagebox_"+(--id[1]);
		
		if($(prevbox).length != 0)
		{
			$(prevbox).focus();
			$(prevbox).prop("selectionStart", $(prevbox).val().length);
			$(prevbox).prop("selectionEnd", $(prevbox).val().length);
		}

		updatePreview(this);
	 });
	
	$('body').delegate('.pagebox', 'keyup', function(key) {
		updatePreview(this);
	});

	$('body').delegate('#title_input', 'keyup', function(key) {
		updatePreview()
	});

	function updatePreview(self) {
		var text = $(self).val();
		if(self !== undefined)
			$("#book_left .bookText #preview").html('<p class="book_preview">'+parseMCSyntax(htmlsanitise(text))+'</p>');

		$("#charleft").html(255-charCount);
		$("#title").html(htmlsanitise($("#title_input").val()));
	}

	$('body').delegate('.pagebox', 'keypress', function(key) {
		if($(this).val().length >= 255) {
			var id = $(this).attr("id").split("_");
			
			var nextbox = "#pagebox_"+(++id[1]);
			if($(nextbox).length == 0)
			{
				$("#btn_addpage").click();
				var nextbox = "#pagebox_"+(id[1]);
			}
			
			$(nextbox).focus();
			if($(nextbox).val().length < 256) {
				$(nextbox).val(String.fromCharCode(key.charCode)+$(nextbox).val());
				$(nextbox).prop("selectionStart", 1);
				$(nextbox).prop("selectionEnd", 1);
			}
			else {
				$(nextbox).prop("selectionStart", 0);
				$(nextbox).prop("selectionEnd", 0);
			}

			return false;
		}
		updatePreview(this);
	});
	
	$('body').delegate('#bookEditorForm', 'submit', function() {
		$("#error").slideUp(300);

		$.ajax({
			type: "POST",
			url: "<?=WEBSITE_ROOT?>assets/php/bookEdit.php",
			data: $("#bookEditorForm").serialize(),
			success: function(res){
				console.log(res);
				$("#error").slideDown(300);
			}
		});
		return false;
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

	function setSize() {
		$('textarea').autosize();
	}
</script>