<?php

echo "<pre>";
echo "We think the real ip is: " . getRealIP() . "\n";
echo "\n";
echo "SERVER\n";
print_r($_SERVER);
echo "\n";
echo "ENV\n";
print_r($_ENV);
echo "\n";
echo "</pre>";

function getRealIP() {
	$client_ip = 'unknown';
	if (!empty($_SERVER['REMOTE_ADDR'])) {
		$client_ip = $_SERVER['REMOTE_ADDR'];
	} else if (!empty($_ENV['REMOTE_ADDR'])) {
		$client_ip = $_ENV['REMOTE_ADDR'];
	}
	if ($_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
		$entries = preg_split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);
		reset($entries);
		while (list(, $entry) = each($entries)){
			$entry = trim($entry);
			if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list)){
				$private_ip = array(
					'/^0\./',
					'/^127\.0\.0\.1/',
					'/^192\.168\..*/',
					'/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
					'/^10\..*/'
				);
				$found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
				if ($client_ip != $found_ip){
					$client_ip = $found_ip;
					break;
				}
			}
		}
	}
	return $client_ip;
}
