<?php
if(
	(file_exists('../wallet') || mkdir('../wallet')) &&
	(file_exists('../wallet/index.php') || copy('wallet.php', '../wallet/index.php')) &&
	(file_exists('../wallet/wallets') || mkdir('../wallet/wallets')) &&
	(file_exists('../wallet/wallets/.htaccess') || copy('wallets.htaccess', '../wallet/wallets/.htaccess'))
	) {
	echo 'ok';
} else {
	echo 'could not create wallet dir';
}
