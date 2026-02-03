<?php

use PragmaRX\Google2FA\Google2FA;

$secret = 'ENZKFDYGI56XW5OC6P3OIFKJ6M6WWDB7';
$google2fa = new Google2FA();
echo $google2fa->getCurrentOtp($secret);

