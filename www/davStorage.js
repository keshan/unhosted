
function UnhostedDav_0_1(params) {
	var dav = params;
	function keyToUrl(userAddress, key) {
		var userAddressParts = userAddress.split("@");
		var resource = dav.dataScope;
		var url = dav.davUrl
			+"webdav/"+userAddressParts[1]
			+"/"+userAddressParts[0]
			+"/"+resource
			+"/"+key;
		return url;
	}
	dav.getUserAddress = function() {
		return dav.userAddress;
	};
	dav.get = function(key, cb) {
		$.ajax({
			url: keyToUrl(dav.userAddress, key), 
				//+ '?ts'+new Date ().getTime ()+'=0', //not compatible with owncloud
			//cache: false, //not compatible with owncloud
			dataType: "json",
			success: function(obj){
				obj.success=true;
				cb(obj);
			},
			error: function(xhr) {
				if(xhr.status == 404) {
					cb({success:true, value: null});
				} else {
					cb({success:false, error: xhr.status});
				}
			}
		});
	};
	
	dav.set = function(key, obj, cb) {
		$.ajax({
			url: keyToUrl(dav.userAddress, key),
			type: "PUT",
			headers: {Authorization: "Basic "+Base64.encode(dav.userAddress +':'+ dav.davToken)},
			fields: {withCredentials: "true"},
			data: JSON.stringify(obj),
			success: function(text) {
				try {//this is not necessary for current version of protocol, but might be in future:
					var obj = JSON.parse(text);
					obj.success = true;
					cb(obj);
				} catch(e) {
					cb({success:true});
				}
			},
			error: function(xhr) {
				cb({success:false, error: xhr.status});
			}
		});
	};
	return dav;
}