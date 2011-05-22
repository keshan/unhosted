<?php
if(
	mkdir('../wallet') &&
	copy('wallet.php', '../wallet/index.php') &&
	mkdir('../wallet/wallets') &&
	copy('wallets.htaccess', '../wallet/wallets/.htaccess')
	) {
	echo 'OK';
} else {
	echo 'could not create wallet dir';
}
