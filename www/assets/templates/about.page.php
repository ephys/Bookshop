			<div id="double_book">
				<div id="book_right">
					<div class="bookText">
						<?=Language::translate('ABOUT')?>
						<!-- <div id="mc_network"></div> -->
					</div>
				</div>
				<div id="book_left">
					<div class="bookText"><?php
						if(!isset($_SESSION['username']))
						{
							echo '<h2>'.Language::translate('LOG_IN').'</h2>
									<form action="javascript:void(0);" style="text-align: center;">
										<input placeholder="'.Language::translate('USERNAME').'" id="login_username" type="text"/>
										<input placeholder="'.Language::translate('PASSWORD').'" id="login_password" type="password"/>
										<button data-loading-text="Loading..." id="login_btn">Connexion</button>
									</form>
									<p id="logininfos">'.Language::translate('LOGIN_INFO').'</p>';
							 ?><script type="text/javascript">
							$("#login_btn").click(function() {
								$("#login_btn").button('loading');
								$.ajax({
									type: "POST",
									url: "<?=PAGE_RELATIVE?>../assets/php/login.php",
									data: { username: $("#login_username").val(), password: $("#login_password").val() },
									success: function(json){
										json = $.parseJSON(json);
										if(!json['error'])
											location.reload();
										else {
											$("#logininfos").fadeOut(300, function () {
												$("#logininfos").html(json[0]);
												$("#logininfos").fadeIn(300);
											});
										}
										$("#login_btn").button('reset');
									}
								});
							});
							
							$("input").keypress(function(e){
								if(e.which == 13){
									$("#login_btn").click();
								}
							});
						</script>
						<hr/>
						<?php
						}
						echo Language::translate('MOD_INFO_HEAD');
						?>
						<p>
							<a class="button half" href="download/"><?=Language::translate('DOWNLOAD')?></a>
							<a class="button half" href="mod/"><?=Language::translate('INFOS')?></a>
						</p>
					</div>
				</div>
			</div>
			<!-- MC_NETWORK
			<script type="text/javascript" src="http://mcnetwork.fr.nf/assets/js/mc_network.js"></script>
			<script type="text/javascript">
				for (var name in mc_projects)
				{
					$("#mc_network").append("<a href=\""+mc_projects[name]+"\"><img class=\"project\" alt=\""+name+"\" title=\""+name+"\" src=\""+mc_network_url+"assets/img/"+name+".png\"/></a>");
				}
			</script>
			END OF MC_NETWORK -->