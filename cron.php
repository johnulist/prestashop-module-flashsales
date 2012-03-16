<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/flashsales.php');

if (isset($_GET['secure_key']) && isset($_GET['action']))
{
	$secureKey = Configuration::get('FS_SECURE_KEY');
	if (!empty($secureKey) && $secureKey === $_GET['secure_key'] && !empty($_GET['action']))
	{
		$flashsales = new FlashSales();
		$flashsales->cronTask(Tools::getValue('action'));
	}
}