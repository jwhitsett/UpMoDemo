
function XHRLoader(s_host, f_callback, other) {
	var host = s_host;
	var loader =  (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	var callback = f_callback;
	var data = "";
	var responseHeaders = "";
	
	this.getData = function() {
		return data;
	}
	
	this.getResponseHeaders = function() {
		return responseHeaders;
	}
	
	loader.onreadystatechange = function() {
		if (loader.readyState === 4) {
			responseHeaders = loader.getAllResponseHeaders();
			data = loader.responseText;
			if (callback != undefined && data != undefined && data != "") callback(JSON.parse(data), other);
		}
		if (loader.readyState === 3) {
			responseHeaders = loader.getAllResponseHeaders();
			data = loader.responseText;
		}
	}
	
	this.load = function(method, url, params, callbackFunction) {
		callback = callbackFunction;
		loader.open(method, url + "?" + params, true);
		loader.send(null);
	}

}

function ConnectionsCache() {
	var connections = new Array();
	
	this.getConnection = function(name) {
		return connections[name];
	}
	this.setConnection = function (name, newConnection) {
		connections[name] = newConnection;
	}
}

