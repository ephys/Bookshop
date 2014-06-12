function repeat(pattern, count) {
	if (count < 1) return '';
	var result = '';
	while (count > 0) {
		if (count & 1) result += pattern;
		count >>= 1, pattern += pattern;
	}
	return result;
}

function checkCD() {
	return repeat('../', window.location.href.split('/').length-1);
}

// infobulle 
// function getMousePosition(e) {
// 	return (e.pageX)?{ x:e.pageX, y:e.pageY }:{ x: (e.clientX + document.documentElement.scrollLeft + document.body.scrollLeft), y: (e.clientY + document.documentElement.scrollTop + document.body.scrollTop) }; 
// }

// document.onmousemove = function(e) {
// 	if(e === undefined)
// 		e = event;
// 	var mouse = getMousePosition(e);
// 	$("#help").css('top', mouse.y+10);
// 	$("#help").css('left', mouse.x+10);
// };

// $('body').delegate('.help', 'mouseover', function() {
// 	if($(this).attr("msg") !== undefined)
// 	{
// 		$("#help").html($(this).attr("msg"));
// 		$("#help").show();
// 	}
// });

$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

$('body').delegate('.help', 'mouseout', function() {
	$("#help").hide();
});

var kkeys = [], konami = "38,38,40,40,37,39,37,39,66,65";
$(document).keydown(function(e) {
	kkeys.push(e.keyCode);
	if ( kkeys.toString().indexOf( konami ) >= 0 ) {
		kkeys = [];
		location.href="http://bookshop.fr.nf/gallery/book/43";
	}
});

function replaceAll(match, pattern, str) {
	if(str === null || str === undefined)
		return;
	while(str.match(match) !== null) {
		str = str.replace(match, pattern);
	}
	return str;
}

function htmlsanitise(str) {
	if(str === null || str === undefined)
		return;
	// replace /n by <br>
	// replace < by &gt;, > by &lt;
	str = replaceAll('<', '&lt;', str);
	str = replaceAll('>', '&gt;', str);
	str = replaceAll("\n", '<br>', str);
	str = str.replace(/(https|ftp|http):\/\/([a-zA-Z0-9-_\-\/\?\=\\.]*)/g, '<a target="_blank" href="$1:\/\/$2">$1:\/\/$2</a>');
	str = str.replace(/(#......)/g, '<span style="color: $1">$1</span>');
	return str;
}

function htmlsanitise_array(ar) {
	for(var i in ar) {
		ar[i] = htmlsanitise(ar[i]);
	}
	return ar;
}

function parseMCSyntax_recursive(ar) {
	for(var i in ar) {
		ar[i] = parseMCSyntax(ar[i]);
	}
	return ar;
}

function inArray(array, p_val) {
    var l = array.length;
    for(var i = 0; i < l; i++) {
        if(array[i] == p_val) {
            return true;
        }
    }
    return false;
}

var mcTextSyntax = [];
var mcColorSyntax = [];
mcColorSyntax['§0'] = '<span style="color: #000">';
mcColorSyntax['§1'] = '<span style="color: #00A">';
mcColorSyntax['§2'] = '<span style="color: #0A0">';
mcColorSyntax['§3'] = '<span style="color: #0AA">';
mcColorSyntax['§4'] = '<span style="color: #A00">';
mcColorSyntax['§5'] = '<span style="color: #A0A">';
mcColorSyntax['§6'] = '<span style="color: #FA0">';
mcColorSyntax['§7'] = '<span style="color: #AAA">';
mcColorSyntax['§8'] = '<span style="color: #555">';
mcColorSyntax['§9'] = '<span style="color: #55F">';
mcColorSyntax['§a'] = '<span style="color: #5F5">';
mcColorSyntax['§b'] = '<span style="color: #5FF">';
mcColorSyntax['§c'] = '<span style="color: #F55">';
mcColorSyntax['§d'] = '<span style="color: #F5F">';
mcColorSyntax['§e'] = '<span style="color: #FF5">';
mcColorSyntax['§f'] = '<span style="color: #FFF">';
mcTextSyntax['§l'] = { open: '<b>', close: '</b>' };
mcTextSyntax['§n'] = { open: '<u>', close: '</u>' };
mcTextSyntax['§m'] = { open: '<s>', close: '</s>' };
mcTextSyntax['§o'] = { open: '<i>', close: '</i>' };
mcTextSyntax['§k'] = { open: '<span class="randomLetters">', close: '</span>' };

function parseMCSyntax(str) {
	var i, j, k;
	for(i in mcColorSyntax) {
		str = str.replace(new RegExp(i, "g"), '§r'+i);
	}
	var phrases = str.split('§r');

	for(i in phrases) {
		var counter = Array();

		for(j in mcTextSyntax) {
			counter[j] = 0;
			while(phrases[i].match(j) !== null) {
				phrases[i] = phrases[i].replace(j, mcTextSyntax[j].open);
				counter[j]++;
			}

			for(k = 0; k < counter[j]; k++) {
				phrases[i] += mcTextSyntax[j].close;
			}
		}
	}

	str = phrases.join('');

	function nbspReplace() {
		str = "";
		for(var j = 0; j < arguments[0].length; j++) {
			str += " ";
		}
		// console.log(arguments);
		return str;
	}

	str = str.replace(/[ ]{2,}/g, nbspReplace);

	for(j in mcColorSyntax) {
		var colorCounter = 0;
		while(str.match(j) !== null) {
			str = str.replace(j, mcColorSyntax[j]);
			colorCounter++;
		}

		for(k = 0; k < colorCounter; k++) {
			str += '</span>';
		}
	}

	return str;
}

function str_split (string, split_length) {
  // http://kevin.vanzonneveld.net
  // +     original by: Martijn Wieringa
  // +     improved by: Brett Zamir (http://brett-zamir.me)
  // +     bugfixed by: Onno Marsman
  // +      revised by: Theriault
  // +        input by: Bjorn Roesbeke (http://www.bjornroesbeke.be/)
  // +      revised by: Rafał Kukawski (http://blog.kukawski.pl/)
  // *       example 1: str_split('Hello Friend', 3);
  // *       returns 1: ['Hel', 'lo ', 'Fri', 'end']
  if (split_length === null) {
    split_length = 1;
  }
  if (string === null || split_length < 1) {
    return false;
  }
  string += '';
  var chunks = [],
    pos = 0,
    len = string.length;
  while (pos < len) {
    chunks.push(string.slice(pos, pos += split_length));
  }

  return chunks;
}