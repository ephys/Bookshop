<?php
if(!isset($_SESSION['user_id'])) {
	header('Location: '.WEBSITE_ROOT.'about/');
	exit;
}
?>
<div id="book">
	<div class="bookText">
		<h2>Vos créations</h2>
		<table id="booklist">
			<tr>
				<td></td>
				<td><button id="btn_add" data-loading-text="Uploading..."><?=Language::translate('UPLOADER_NEW')?></button></td>
				<td><div class="file"><strong><?=Language::translate('UPLOADER_EMPTY')?></strong><div id="newFile"></div></div><p id="upload_error"></p></td>
			</tr>
		</table>
	</div>
</div>
<script type="text/javascript" src="assets/js/fileuploader.js"></script>
<script type="text/javascript">
	// onload: diplay the book list.
	$(document).ready(function() {
		$.ajax({
			type: "POST",
			url: "<?=WEBSITE_ROOT?>assets/php/bookEdit.php",
			data: "action=3",
			success: function(html){ 
				html = $.parseJSON(html);
				addBooks(html);
			}
		});
	});

	// affect buttons on the books to their respective functions
	$('body').delegate('.btn_delete', 'click', function() {
		deleteBook(this);
	});

	// display books in the main list
	function addBooks(books) {
		for(i in books) {
			$("#booklist").append('<tr><td><button book="'+books[i]['id']+'" class="btn_delete"><?=Language::translate('BOOK_DELETE')?></button></td><td><p><a class="button help" href="editing/book/'+books[i]['id']+'/"><?=Language::translate('BOOK_EDIT')?></a></p></td><td>'+((books[i]['title'] != "")?books[i]['title']:"<?=Language::translate('BOOK_UNTITLED')?>")+'</td><td>'+books[i]['author']+'</td><td>'+(parseInt(books[i]['bPublic'])?(parseInt(books[i]['bIndexed'])?"<?=Language::translate('SECURITY_PUBLIC')?>":"<?=Language::translate('SECURITY_UNINDEXED')?>"):"<?=Language::translate('SECURITY_PRIVATE')?>")+'</td><td>'+books[i]['date']+'</td></tr>');
		}
	}

	// delete books from the main list + remove from DB
	function deleteBook(book) {
		if(!confirm("<?=Language::translate('BOOK_DELETE_CONFIRM')?>"))
			return false;

		$.ajax({
			type: "POST",
			url: "<?=WEBSITE_ROOT?>assets/php/bookEdit.php",
			data: "action=0&id="+encodeURIComponent($(book).attr('book')),
			success: function(){ 
				$(book).parent().parent().fadeOut(300);
			}
		});
	}
	
	// Upload de .dat
	var file_input = false;
	$('#btn_add').click(function() {
		if(file_input)
		{
			uploader._onInputChange(file_input);
			$('#btn_add').button('loading');
			$("#upload_error").slideUp(300);
		}
		else
		{
			$.ajax({
				type: "POST",
				url: "<?=WEBSITE_ROOT?>assets/php/upload.php",
				data: "emptyBook=1",
				success: function(data){
					var books = jQuery.parseJSON(data);
					addBooks(books);
				}
			});
		}
	});
	
	var uploader = new qq.FileUploader({
		element: document.getElementById('newFile'),
		debug: false,
		multiple: false,
		action: '<?=WEBSITE_ROOT?>assets/php/upload.php',
		onComplete: function(id, fileName, response2) {
			console.log(responseText);
			var books = jQuery.parseJSON(responseText);
			if(books['error']) {
				$("#upload_error").html(books[0]);
				$("#upload_error").slideDown(300);
			} else {
				uploader._button._options.onChange(false);
				addBooks(books);
			}
			$('#btn_add').button('reset');
		}
	});

	uploader._button._options.onChange = function(input) {
		file_input = input;
		
		if($(input).val())
		{
			$("#newFile").parent().children("strong").html($(input).val());
			$("#btn_add").html("<?=Language::translate('UPLOADER_SUBMIT')?>");
		}
		else
		{
			$("#newFile").parent().children("strong").html('No file');
			$("#btn_add").html("<?=Language::translate('UPLOADER_NEW')?>");
		}
		return false; 
	}
</script>
