<div id="book">
	<div class="bookText">
		<h2>Bookshop API <small>documentation</small></h2>
		<p><b>IMPORTANT: This documentation is for the API v3 and following</b></p>
		<p>for the api prior to v3 (deprecated), check out <a href="api_old">the old api documentation</a> instead</b></p>
		<h4><i>How to use the api ?</i></h4>
		<p>You need to send an http request (GET or POST) to http://api.bookshop.fr.nf/ with the name of the functions (param "method") and the params (the name of the param) as args.</p>
		<p>Exemple: to call the method "authorBooks", I'll make one of the following request:</p>
		<p>GET http://api.bookshop.fr.nf/?method=authorBooks&amp;username=Ephys</p>
		<p>POST http://api.bookshop.fr.nf/?method=authorBooks&amp;username=Ephys</p>
		<h4><i>Available Methods</i></h4>
		<div class="phpdoc">
		<?php
			require_once ROOT.'assets/php/inc/api.class.php';
			$class = new ReflectionClass('API');
			echo $class->getDocComment();
			foreach($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
				if(($phpdoc = $method->getDocComment()) !== false) {
					echo '<p class="method">'.(($method->isDeprecated())?'<b>DEPRECATED</b> ':'').'method: <i>'.$method->name.'</i>';
					$optParams = '';
					foreach($method->getParameters() as $param) {
						if($param->isOptional())
							$optParams .= '<br>&nbsp;- '.$param->getName().' <small>('.$param->getDefaultValue().')</small>';
					}
					if($optParams !== '')
						echo '<br>Optional parameters: '.$optParams;

					echo '<br>'.parsePHPDoc($phpdoc).'</p>';
				}
			}

			function parsePHPDoc($doc) {
				$doc = preg_replace("#(.)#", '<span class="monospaced_char">$1</span>', $doc);
				// $doc = '<span class="monospaced_char">'.implode('</span><span class="monospaced_char">', str_split($doc)).'</span>';
				$doc = preg_replace("#@(\w*)#iSu", '<span class="tag">@$1</span>$2', $doc);
				$doc = preg_replace("#\\\$(\w*)#iSu", '<span class="var">\$$1</span>$2', $doc);
				$doc = nl2br($doc);
				return $doc;
			}
		?>
		</div>
	</div>
</div>