var cookieManager = function() {
	// console.log(document.cookie);
};

cookieManager.prototype.get_tagged = function(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if(c.indexOf(nameEQ) === 0)
			return c.substring(nameEQ.length,c.length);
	}
	return null;
};

cookieManager.prototype.setCookie = function(name, value, expire) {
	var expires;
	if (expire) {
		var date = new Date();
		date.setTime(date.getTime()+(expire*24*60*60*1000));
		expires = "; expires="+date.toGMTString();
	} else
		expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
};