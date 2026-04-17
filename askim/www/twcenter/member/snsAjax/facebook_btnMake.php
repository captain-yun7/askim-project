<?php
	if($_POST['status'] == "connected"){
		echo "  fetchUserDetail() ";
	} else {
		echo "	FB.login(function(response) {
					fetchUserDetail();
				}, {scope: 'email,public_profile'});";
	}
?>