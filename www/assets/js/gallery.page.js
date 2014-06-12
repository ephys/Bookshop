var BookLoader = function(api_url, default_page, default_book) {
	this.api_url = api_url;
	this.books = [];
	this.pages = [];
	this.cookies = new cookieManager();
	this.default_page = default_page;

	this.curBook = { id: default_book, page: default_page };

	if(typeof(this.curBook.id) == 'number')
		this.loadBook(this.curBook.id, true);
};

BookLoader.prototype.loadBook = function(bookID, fullsize, callback) {
	if(fullsize === undefined)
		fullsize = false;

	this.curBook.id = bookID;
	if(this.books[bookID] !== undefined) {
		this.renderBookContainer(fullsize);
		if(typeof(callback) === 'function')
			callback();
	} else {
		var self = this;
		$.post(this.api_url, { method: "loadbook", id: encodeURIComponent(bookID) }, function(data) {
			try {
				data = $.parseJSON(data);

				if(data.error === undefined) {
					data.pages = parseMCSyntax_recursive(htmlsanitise_array(data.pages));
					self.books[bookID] = data;
					self.renderBookContainer(fullsize);
				} else {
					console.log(data.error);
				}

				if(typeof(callback) === 'function')
					callback();
			} catch(e) {
				console.log(e);
			}
		});
	}
};

BookLoader.prototype.loadBooklist = function(sentData, callback) {
	this.curBook.id = null;

	var self = this;
	$.post(this.api_url, sentData, function(data) {
		try {
			data = $.parseJSON(data);

			if(data['error'] === undefined) {
				var html;
				switch(sentData['method']) {
					case 'authorBooks':  html = "<h2>"+sentData['username']+"</h2>"; break;
					case 'titleList':    html = "<h2>"+sentData['title']+"</h2>"; break;
					case 'lastestBooks': html = "<h2>"+_LANGUAGE['GALLERY_LASTEST']+"</h2>"; break;
					case 'bestBooks':    html = "<h2>"+_LANGUAGE['GALLERY_BESTVOTES']+"</h2>"; break;
					case 'randomBooks':  html = "<h2>"+_LANGUAGE['GALLERY_RANDOM']+"</h2>"; break;
					default: html = "<h2>Error</h2>"; console.log("No method "+sentData['method']); break;
				}

				if(data[0] !== undefined) {
					html += "<table id=\"book_list\"><tbody>";
					for(var i in data) {
						if(isNaN(i))
							continue;

						html += "<tr><td><button book=\""+data[i].id+"\" class=\"btn_loadbook small\">"+_LANGUAGE['BOOK_LOAD_PREVIEW']+"</button> <button book=\""+data[i].id+"\" class=\"btn_loadbook_full small\">"+_LANGUAGE['BOOK_LOAD']+"</button></td><td>"+data[i].title+"</td><td>"+data[i].date+"</td>";
					}
					html += "</tbody></table>";
				} else {
					html += "<p>"+_LANGUAGE['PROFILE_EMPTY']+"</p>";
				}
				$("#book_left .bookText").html(html);
			}

			$('.btn_loadbook').click(function() {
				$book = $(this);
				$book.button('loading');
				self.loadBook($book.attr('book'), false, function() {
					$book.button('reset');
				});
			});

			$('.btn_loadbook_full').click(function() {
				$book = $(this);
				$book.button('loading');
				self.loadBook($book.attr('book'), true, function() {
					$book.button('reset');
				});
			});
		} catch(e) {
			console.log(sentData, data, e);
			$("#book_left .bookText").html("<h2>ERROR</h2>");
		}
	}).fail(function(e) {
		html = "<h2>Error</h2><p>"+e.status+" "+e.statusText+"</p>";
		$("#book_left .bookText").html(html);
	}).always(function() {
		if(callback !== undefined)
			callback();
	});
};

BookLoader.prototype.searchNeedle = function(requestType, username, callback) {
	$.post(this.api_url, { method: requestType, needle: username }, function(data) {
		try {
			if(typeof(callback) == 'function')
				callback($.parseJSON(data));
			else
				return $.parseJSON(data);
		} catch(e) {
			console.log(e);
			if(typeof(callback) == 'function')
				callback([]);
			else
				return [];
		}
	});
};

BookLoader.prototype.renderPage = function(save) {
	var navigationL = (this.pages[this.curBook.page-2] !== undefined)?'<a class="book_navig_left" href="#"></a>':'<span class="book_navig_left"></span>';
	var navigationR = (this.pages[this.curBook.page+2] !== undefined)?'<a class="book_navig_right" href="#"></a>':'<span class="book_navig_right"></span>';
	var pageR = (this.pages[this.curBook.page+1] !== undefined)?this.pages[this.curBook.page+1]:"";

	$("#book_left .bookText").html(pageR+navigationR+'<span class="book_footer">Page '+(this.curBook.page+1)+'</span>');
	$("#book_right .bookText").html(this.pages[this.curBook.page]+navigationL+'<span class="book_footer">Page '+this.curBook.page+'</span>');

	if(save)
		history.pushState(this.curBook, "Bookshop", "gallery/book/"+this.curBook.id+"/page/"+this.curBook.page+"/");

	var self = this;

	if(this.curBook.page === 0) {
		this.bindzClip();

		$("#vote_y").click(function() {
			self.vote(true);
		});

		$("#vote_n").click(function() {
			self.vote(false);
		});
	}

	$('a.book_navig_left').click(function(e) {
		e.preventDefault();
		self.navigate('left');

		return false;
	});

	$('a.book_navig_right').click(function(e) {
		e.preventDefault();
		self.navigate('right');

		return false;
	});
};

BookLoader.prototype.goto = function(page) {
	if(this.curBook.id === null)
		return;

	this.curBook.page = page;
	this.renderPage(false);
};

BookLoader.prototype.navigate = function(direction) {
	if(this.curBook.id === null)
		return;

	switch(direction) {
		case 'right':
			if(this.pages[this.curBook.page+2] !== undefined)
				this.curBook.page += 2;
			else
				return;
			break;
		case 'left':
			if(this.curBook.page-2 >= 0)
				this.curBook.page -= 2;
			else
				return;
			break;
	}

	this.renderPage(true);
};

BookLoader.prototype.renderBookContainer = function(fullsize) {
	if(typeof(this.books[this.curBook.id]) === undefined)
		return;

	var data = this.books[this.curBook.id];
	var html;

	if(data.pages !== undefined) {
		html = '<h2>'+data.title+'<small> @'+data.author+'</small></h2>';
		html += '<p>'+data.date+' ['+data.pages.length+' page(s)]</p>';

		if(this.cookies.get_tagged('bookshop_bookvotes') === null || !inArray(this.cookies.get_tagged('bookshop_bookvotes').split(','), data['id']))
			html += '<span id="vote_btn" book="'+data['id']+'"><button id="vote_y">+</button> <button id="vote_n">-</button></span> ';
		else {
			html += this.books[this.curBook.id].rate_positive + ' / ' + this.books[this.curBook.id].rate_negative;
		}
		html += '<p class="inline"><a class="button help" msg="'+_LANGUAGE['HELP_DL_BUTTON']+'" href="mod/">Help</a></p>';
		html += ' <button id="btn_copy">'+_LANGUAGE['COPY']+'</button> <input id="command" type="text" readonly="readonly" value="/bookshop dl '+data.id+'" /> id: '+data.id;

		this.pages = [];

		for(var i in data.pages) {
			this.pages[i] = '<p class="book_preview">'+data.pages[i]+'</p>';
		}

		if(fullsize) {
			this.pages[0] = html + this.pages[0];
			this.navigate(this.default_page);
		} else {
			html += '<p>'+this.pages[0]+'</p>';
			if(this.pages.length > 1)
				html += "<p>[...]</p>";

			$("#book_right .bookText").html(html);
			this.bindzClip();
		}
	} else {
		html = '<h2>'+_LANGUAGE['BOOK_UNTITLED']+'</h2>';
		html += '<a href="gallery/">-->'+_LANGUAGE['MENU_GALLERY']+'</a></p>';
		$("#book_right .bookText").html(html);
	}
};

// TODO: WEBSITE_ROOT
BookLoader.prototype.bindzClip = function() {
	$("#btn_copy").zclip({
		path: "assets/misc/ZeroClipboard.swf",
		copy: $("#command").val(),
		beforeCopy: function() {
			$("#btn_copy").html(_LANGUAGE['COPY']);
		},
		afterCopy:function() {
			$("#btn_copy").html(_LANGUAGE['COPIED']);
		}
	});
};

BookLoader.prototype.vote = function(bPositive) {
	if(bPositive)
		this.books[this.curBook.id].rate_positive++;
	else
		this.books[this.curBook.id].rate_negative--;

	var self = this;
	$("#vote_btn").fadeOut(300, function() {
		$(this).html(self.books[self.curBook.id].rate_positive + ' / ' + self.books[self.curBook.id].rate_negative).fadeIn(300);
	});

	$.post(this.api_url, { method: "vote", id: this.curBook.id, isPositive: encodeURIComponent(bPositive) });

	this.cookies.setCookie("bookshop_bookvotes", this.cookies.get_tagged("bookshop_bookvotes")+","+this.curBook.id, 365);
};