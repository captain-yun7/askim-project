<?php
$msg = strip_tags($msg);

if ($go_url) {
	echo '<script type="text/javascript">';
	echo 'alert("'.$msg.'");';
	echo 'location.replace("'.$go_url.'")';
	echo '</script>';
} else {
	echo '<script type="text/javascript">';
	echo 'alert("'.$msg.'");';
	echo 'history.back();';
	echo '</script>';
}
?>
