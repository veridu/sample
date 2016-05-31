<?php

if (!is_file(__DIR__ . '/vendor/autoload.php'))
	die('Please install sample dependencies with \'composer install\'');
//using composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

if (!is_file(__DIR__ . '/../settings.php'))
	die('Please copy ../settings.php.dist to ../settings.php');
//requiring client configuration
require_once __DIR__ . '/../settings.php';

//Instantiate API object
$api = Veridu\API::factory(
	$veridu['client'],
	$veridu['secret'],
	$veridu['version']
);

/*
 * Creates new a read/write Veridu session
 * More info: https://veridu.com/wiki/Session_Resource
 */
$api->session->create(false);

//gets the storage object
$storage = $api->getStorage();
//gets session token
$session = $storage->getSessionToken();

//PHP's session start
session_start();

$_SESSION['nonce'] = bin2hex(openssl_random_pseudo_bytes(10));

?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!-- Loading the jQuery Library (required by Widget Library) -->
		<script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<!-- Loading the Widget Library (more info: https://veridu.com/wiki/Widget_Library) -->
		<script type="text/javascript" src="https://assets.veridu.com/<?=$veridu['version'];?>/sdk/veridu-sso.js"></script>
	</head>
	<body>
		<div id="widget" style="width: 100%; height: 540px"></div>
		<script type="text/javascript">
			$(function () {
				var //Widget instantiation
					veridu = new Veridu({
						client: '<?=$veridu['client'];?>',
						session: '<?=$session?>',
						language: 'en-us',
						country: 'uk',
						version: '<?=$veridu['version'];?>'
					});
				//displaying the sso widget
				//more info: https://veridu.com/wiki/SSO_Widget
				veridu.SSO.widget(
					'http://127.0.0.1:8080/callback.php',
					$('#widget'),
					["facebook","linkedin","paypal","amazon","google"],
					'<?=$_SESSION['nonce'];?>'
				);
			});
		</script>
	</body>
</html>
