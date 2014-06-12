/* 13 lignes
 * 255 caractères max
 * 20 char/ligne
 */

var BookEditor = function(id, editor_div, debug_div) {
	this.id = id;
	this.editor = editor_div;
	this.debug = debug_div;
	this.book = null;
	this.pageCount = 0;

	this.security = 0;

	this.load();
};

BookEditor.prototype.load = function() {
	var self = this;
	$.ajax({
		type: "POST",
		url: "assets/php/bookEdit.php",
		data: { action: 1, id: this.id },
		success: function(data) {
			try {
				self.book = $.parseJSON(data);

				if(self.book.error === true) {
					throw new Exception("server error");
				}

				self.security = ((data.bPublic === 1)?((data.bIndexed === 1)?2:0):1);

				self.renderEditor();
			} catch(e) {
				self.displayError("An error occured !");
				console.log(e, data);
			}
		}
	});
};

BookEditor.prototype.renderEditor = function() {
	console.log(this.book);
	var html = '<input type="hidden" name="id" value="'+this.id+'"/>';
	html += '<input type="text" placeholder="'+_LANGUAGE.BOOK_TITLE+'" value="'+this.book.title+'" id="title_input" name="title"> @ <input type="text" placeholder="'+_LANGUAGE.BOOK_AUTHOR+'" value="'+this.book.author+'" name="author"> <button id="bookCtrl">Options</button>';
	$(this.editor).html(html);
};

BookEditor.prototype.displayError = function(error) {
	$(this.editor).html("erreur: "+error);
};