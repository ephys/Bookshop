<?php
if(!isset($_SESSION['user_id']))
	header('Location: '.WEBSITE_ROOT);
?>
<div id="double_book">
	<div id="book_right">
		<div class="bookText">
			<h2><?=Language::translate('MENU_STATS')?></h2>
			<table id="booklist">
			</table>
		</div>
	</div>
	<div id="book_left">
		<div class="bookText">

		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$.ajax({
			type: "POST",
			url: "<?=WEBSITE_ROOT?>assets/php/bookEdit.php",
			data: { action: 3 },
			success: function(json){ 
				json = $.parseJSON(json);
				for(i in json) {
					$("#booklist").append('<tr><td><p><a class="button load" book_id="'+json[i]['id']+'" href="#"><?=Language::translate('BOOK_LOAD')?></a></p></td><td>'+((json[i]['title'] != "")?json[i]['title']:"<?=Language::translate('BOOK_UNTITLED')?>")+'</td><td>'+json[i]['author']+'</td></tr>');
				}
			}
		});
	});

	$('#book_right').delegate('.load', 'click', function() {
		var id = $(this).attr('book_id');
		$.ajax({
			type: "POST",
			url: "<?=WEBSITE_ROOT?>assets/php/bookEdit.php",
			data: { action: 4, id: id },
			success: function(json){
				json = $.parseJSON(json);

				var percent = 0;
				if(json['rate_negative']-json['rate_positive'] == 0)
					percent = 50;
				else
					percent = -(json['rate_positive']/(json['rate_negative']-json['rate_positive']))*100;

				console.log(percent);

				var html = '<h2>'+json['title']+'</h2>';
				html += '<p>ID: '+id+'</p>';
				html += '<p><?=Language::translate("VIEWS")?>: '+json['view']+'</p><p><?=Language::translate("DOWNLOADS")?>: '+json['downloads']+'</p>';
				html += '<h3><?=Language::translate("LIKES")?>: '+percent+'%</h3>';
				html += '<div class="likebar green" style="width: '+percent*4+'px">'+json['rate_positive']+'+</div><div class="likebar red" style="width:'+(100-percent)*4+'px">'+json['rate_negative']+'</div>';
				html += "<h3><?=Language::translate('READERS')?></h3>";

				if(json['countries'][0] == undefined) {
					html += '<p><?=Language::translate("GENERIC_EMPTY")?></p>';
				} else {
					for(var i in json['countries']) {
						var percent = json['countries'][i]["count"]/json['view']*100;
						html += '<p>'+json['countries'][i]["name"]+': '+parseFloat(percent).toFixed(2)+'%</p>';
					}
				}

				$("#book_left .bookText").html(html);
			}
		});
		return false;
	});
</script>