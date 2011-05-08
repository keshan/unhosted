<?php

require_once('config.php');

if(isset($_GET['q'])) {
	$userName = explode('@', $_GET['q']);
	$userName = $userName[0];
	if(substr($userName, 0, 5) == 'acct:') {//still not sure if the 'acct:' prefix is SHOULD or MUST in lrrd - accepting with and without.
		$userName = substr($userName, 5);
	}
	header('Content-Type: application/xml+xrd');
	//header('Content-Type: text/xml');

	echo "<?xml version='1.0' encoding='UTF-8'?>\n";
	echo "<XRD xmlns='http://docs.oasis-open.org/ns/xri/xrd-1.0'\n"; 
	echo "      xmlns:hm='http://host-meta.net/xrd/1.0'>\n";
	echo "  <hm:Host xmlns='http://host-meta.net/xrd/1.0'>" . UnhostedSettings::domain . "</hm:Host>\n";
	echo "  <Link rel='http://unhosted.org/spec/dav/0.1'\n";
	echo "      href='http://" . UnhostedSettings::domain . "/'>\n";
	echo "  </Link>\n";
	echo "  <Link rel='http://apinamespace.org/atom' type='application/atomsvc+xml' href='http://" . UnhostedSettings::domain . "/atom/$userName.xml'>\n";
	echo "    <Property type='http://apinamespace.org/atom/username'>$userName</Property>\n";
	echo "  </Link>\n";
	echo "</XRD>\n";
}
