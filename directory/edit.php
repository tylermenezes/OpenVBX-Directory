<?php
	if( OpenVBX::isAdmin() ) {
     	include('admin.php');
	 } else {
	     include('user.php');
	 }
?>