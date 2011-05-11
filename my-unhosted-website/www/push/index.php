<?php
$cb= $_POST['hub_callback']
	.'?hub_mode=subscribe'
	.'\&hub_topic='.$_POST['hub_topic']
	.'\&hub_verify_token='.$_POST['hub_verify_token']
	.'\&hub_challenge=bla'
	.'\&hub_lease_seconds=1234567890';
file_put_contents('/tmp/mich2.log', var_export($_POST, true).$cb);
`curl $cb >> /tmp/mich3.log`;
header('Status: 204 No Content');
