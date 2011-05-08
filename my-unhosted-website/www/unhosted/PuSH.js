  /////////////////////////
 // pubsubhubbub (PuSH) //
/////////////////////////

var PuSH = function() {
	var push = {};
	var getHostMeta = function(userAddress, linkRel) {
		//split the userAddress at the "@" symbol:
		var parts = userAddress.split("@");
		if(parts.length == 2) {
			var user = parts[0];
			var domain = parts[1];

			//get the host-meta data for the domain:
			var xhr = new XMLHttpRequest();
			var url = "http://"+domain+"/.well-known/host-meta";
			xhr.open("GET", url, false);	
			//WebFinger spec allows application/xml+xrd as the mime type, but we need it to be text/xml for xhr.responseXML to be non-null:
			xhr.overrideMimeType('text/xml');
			xhr.send();
			if(xhr.status == 200) {
				
				//HACK
				var parser=new DOMParser();
				var responseXML = parser.parseFromString(xhr.responseText, "text/xml");
				//END HACK

				var hostMetaLinks = responseXML.documentElement.getElementsByTagName('Link');
				var i;
				for(i=0; i<hostMetaLinks.length; i++) {
					if(hostMetaLinks[i].attributes.getNamedItem('rel').value == linkRel) {
						return hostMetaLinks[i].attributes.getNamedItem('template').value;
					}
				}
			}
		}
		return null;
	}
	var getAtomSvc = function(userAddress, cb) {
		//get the WebFinger data for the user and extract the uDAVdomain:
		var template = getHostMeta(userAddress, 'lrdd');
		if(template) {
			var xhr = new XMLHttpRequest();
			var url = template.replace(/{uri}/, "acct:"+userAddress, true);
			xhr.open("GET", url, true);
			//WebFinger spec allows application/xml+xrd as the mime type, but we need it to be text/xml for xhr.responseXML to be non-null:
			xhr.overrideMimeType('text/xml');
			xhr.send();
			xhr.onreadystatechange = function() {
				if(xhr.readyState == 4) {
					if(xhr.status == 200) {
				
						//HACK
						var parser=new DOMParser();
						var responseXML = parser.parseFromString(xhr.responseText, "text/xml");
						//END HACK

						var linkElts = responseXML.documentElement.getElementsByTagName('Link');
						var i;
						for(i=0; i < linkElts.length; i++) {
							if(linkElts[i].attributes.getNamedItem('rel').value == "http://apinamespace.org/atom") {
								cb(linkElts[i].attributes.getNamedItem('href').value);
								return;
							}
						}
					}
				}
			}
		}
	}
	var getPostsAtom = function(userAddress, cb) {
		getAtomSvc(userAddress, function(atomSvcUrl) {
			var xhr = new XMLHttpRequest();
			xhr.open("GET", atomSvcUrl, true);
			xhr.overrideMimeType('text/xml');
			xhr.onreadystatechange = function() {
				if(xhr.readyState == 4) {
					if(xhr.status == 200) {
						//HACK
						var parser=new DOMParser();
						var responseXML = parser.parseFromString(xhr.responseText, "text/xml");
						//END HACK

						var collections = responseXML.documentElement.getElementsByTagName('collection');
						var i;
						for(i=0; i < collections.length; i++) {
							var verbElements = collections[i].getElementsByTagName('activity:verb');
							if(verbElements[0] == "http://activitystrea.ms/schema/1.0/post") {
								cb(collections[i].attributes.getNamedItem('href'));
							}
						}
					}
				}
			}
			xhr.send();	
		});
	}
	var getPostsPuSH = function(userAddress, cb) {
		getPostsAtom(userAddress, function(postsAtomUrl) {
			var xhr = new XMLHttpRequest();
			var url = template.replace(/{uri}/, "acct:"+userAddress, true);
			xhr.open("GET", url, true);
			//WebFinger spec allows application/xml+xrd as the mime type, but we need it to be text/xml for xhr.responseXML to be non-null:
			xhr.overrideMimeType('text/xml');
			xhr.send();
			xhr.onreadystatechange = function() {
				if(xhr.readyState == 4) {
					if(xhr.status == 200) {
				
						//HACK
						var parser=new DOMParser();
						var responseXML = parser.parseFromString(xhr.responseText, "text/xml");
						//END HACK

						var linkElts = responseXML.documentElement.getElementsByTagName('Link');
						var i;
						for(i=0; i < linkElts.length; i++) {
							if(linkElts[i].attributes.getNamedItem('rel').value == "hub") {
								cb(linkElts[i].attributes.getNamedItem('href').value);
								return;
							}
						}
					}
				}
			}
		});
	}
	push.connect(userAddress, cb) {
		getPostsPuSH(userAddress, function(postsPuSH) {
			push.url = postsPuSH;
			cb();
		});
	}
	
	return push;
}
PuSH().connect("michielbdejong@identi.ca", function() {alert('hurray!');});
