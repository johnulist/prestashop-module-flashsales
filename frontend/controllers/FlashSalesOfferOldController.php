<?php
class FlashSalesOfferOldControllerCore extends FrontController
{
	public $php_self = 'flashsalesofferold.php';
	public $tpl_file = 'flashsalesofferold.tpl';

	public function preProcess()
	{
		$this->canonicalRedirection();

		parent::preProcess();
	}
}
?>