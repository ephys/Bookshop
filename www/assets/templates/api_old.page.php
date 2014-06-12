<div id="book"><div class="bookText">
	<h2>Bookshop API <small>documentation</small></h2>
	<p><b>IMPORTANT: This documentation is obsolete, it concerns the API prior to it's v3 and NO SUPPORT will be given</b></p>
	<p><b>Check out <a href="api">the new api documentation</a> instead</b></p>
	<p><i>How to use the api ?</i></p>
	<p>Depending on what you wish to do, you'll have send different http requests to the server.</p>
	<p>The API files are located in <b>http://api.bookshop.fr.nf/</b></p>
	<p>The API folder contains two files:</p>
	<p>* private.php
		<br/>* public.php</p>
	<p>Retrocompatibility note:
		<br/>* api.php redirects to public.php
		<br/>* upload.php redirects to private.php
		<br/><b>Using these names is DEPRECATED</b></p>
	<p>The queries must be sent using the GET method.</p>
	<p>Warning ! Be sure to only send the necessary for your request.<br/>
		If you don't, only the request with the highest (0 being the highest) priority will be executed</p>
	<p>Every query will return a JSON array</p>
	<hr/>
	<p><i>Editing a book</i></p>
	<p>Used file: <b>private.php</b></p>
	<p>Priority: <b>1</b></p>
	<p>Parameters:
		<br/>* "id" -> the id of the book you wish to edit
		<br/>* "username" -> the username associed with the account where it should be uploaded
		<br/>* "token" -> the account API token used to identify the owner
		<br/>* "data" -> a json encoded array containing:
		<br/>* * "pages" -> an array containing the book pages
		<br/>* * "title" -> a string containing the book title
		<br/>* * "author" -> a string containing the book author's name</p>
	<p>Return:
		<br/>* In case of success, true associated to the "success" key
		<br/>* In case of faillure, an error message associated to the "error" key</p>
	<hr/>
	<p><i>Uploading a book</i></p>
	<p>Used file: <b>private.php</b></p>
	<p>Priority: <b>2</b></p>
	<p>Parameters: 
		<br/>* "username" -> the username associed with the account where it should be uploaded
		<br/>* "token" -> the account API token used to identify the owner
		<br/>* "data" -> a json encoded array containing:
		<br/>* * "pages" -> an array containing the book pages
		<br/>* * "title" -> a string containing the book title
		<br/>* * "author" -> a string containing the book author's name</p>
	<p>Return:
		<br/>* In case of success, the id of the book associated to the "success" key
		<br/>* In case of faillure, an error message associated to the "error" key</p>
	<hr/>
	<p><i>Deleting a book</i></p>
	<p>Used file: <b>private.php</b></p>
	<p>Priority: <b>3</b></p>
	<p>Parameters:
		<br/>* "id" -> the id of the book you wish to edit
		<br/>* "username" -> the username associed with the account where it should be uploaded
		<br/>* "token" -> the account API token used to identify the owner</p>
	<p>Return:
		<br/>* In case of success, true associated to the "success" key
		<br/>* In case of faillure, an error message associated to the "error" key</p>
	<hr/>
	<p><i>Listing an author's work</i></p>
	<p>Warning ! This list the book's author work, not the book owner.</p>
	<p>Used file: <b>public.php</b></p>
	<p>Priority: <b>0</b></p>
	<p>Parameters:
		<br/>* "user" -> the author's username</p>
	<p>Return:
		<br/>* An array containing the books titles (as the value) associated with their ID (as the key)</p>
	<hr/>
	<p><i>Loading lastest books</i></p>
	<p>Used file: <b>public.php</b></p>
	<p>Priority: <b>2</b></p>
	<p>Parameters:
		<br/>* "lastest" -> whatever it contains, it will work</p>
	<p>Return:
		<br/>* An array containing the books titles (as the value) associated with their ID (as the key)</p>
	<hr/>
	<p><i>Downloading a book</i></p>
	<p>Used file: <b>public.php</b></p>
	<p>Priority: <b>3</b></p>
	<p>Parameters:
		<br/>* "id" -> the id of the book</p>
	<p>Return:
		<br/>* "date" -> last edit of the book
		<br/>* "id" -> the id of the book in our database
		<br/>* "title" -> the book title
		<br/>* "author" -> the book author
		<br/>* "pages" -> An array containing the book pages</p>
	<hr/>
	<p><i>Searching for an author's username</i></p>
	<p>Used file: <b>public.php</b></p>
	<p>Priority: <b>4</b></p>
	<p>Parameters:
		<br/>* "search_u" -> the author's username pattern</p>
	<p>Return:
		<br/>* the usernames matching the pattern</p>
	<hr/>
	<p><i>Searching for a title</i></p>
	<p>Used file: <b>public.php</b></p>
	<p>Priority: <b>5</b></p>
	<p>Parameters:
		<br/>* "title" -> the book title pattern</p>
	<p>Return:
		<br/>* the titles matching the pattern associated with their id</p>
	<hr/>
	<p><i>Loading best books</i></p>
	<p>Used file: <b>public.php</b></p>
	<p>Priority: <b>6</b></p>
	<p>Parameters:
		<br/>* "best" -> whatever it contains, it will work. Just need to be set</p>
	<p>Return:
		<br/>* An array containing the books titles (as the value) associated with their ID (as the key)</p>
	<hr/>
	<p><i>Loading books randomly</i></p>
	<p>Used file: <b>public.php</b></p>
	<p>Priority: <b>7</b></p>
	<p>Parameters:
		<br/>* "random" -> whatever it contains, it will work. Just need to be set</p>
	<p>Return:
		<br/>* An array containing the books titles (as the value) associated with their ID (as the key)</p>
</div></div>