if((file_exists("../identity") || mkdir("../identity"))
	&& (copy("src/webfinger.php", "../identity/webfinger.php"))
	&& file_put_contents("../.well-known/host-meta", "<?xml version='1.0' encoding='UTF-8'?>\n"
	."<XRD xmlns='http://docs.oasis-open.org/ns/xri/xrd-1.0' \n"
	."\t\txmlns:hm='http://host-meta.net/xrd/1.0'>\n"
	."\t<hm:Host xmlns='http://host-meta.net/xrd/1.0'>$domain</hm:Host>\n"
 	."\t<Link rel='lrdd' \n"
 	."\t\ttemplate='http://$domain/unhosted/webfinger.php?q={uri}'>\n"
 	."\t\t<Title>Resource Descriptor</Title>\n"
 	."\t</Link>\n"
 	."\t<Link rel='register'\n" 
 	."\t\ttemplate='http://$domain/unhosted/register.php?user_name={uri}&amp;redirect_url={redirect_url}'>\n"
 	."\t\t<Title>Resource Descriptor</Title>\n"
 	."\t</Link>\n"
	."</XRD>\n")) {
	echo 'ok';
} else {
	echo 'could not write host-meta';
}

