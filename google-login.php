<?php
require 'vendor/autoload.php';
require 'config.php';

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope("email");
$client->addScope("profile");

$login_url = $client->createAuthUrl();
?>

<a href="<?php echo $login_url; ?>">Login with Google</a>
