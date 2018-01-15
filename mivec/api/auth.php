<?php
$_token = Mage::app()->getRequest()->getParam('token');

auth($_token);

function auth($_token , $_msg = "Access Denied")
{
	//global $_SESSION;
	if (!$_token || __TOKEN__ != $_token) {
		die($_msg);
	} else {
		$_SESSION['token'] = __TOKEN__;
		return true;
	}
}