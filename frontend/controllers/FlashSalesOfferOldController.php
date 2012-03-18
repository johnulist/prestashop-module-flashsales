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

	public function process()
	{
		parent::process();

		self::$smarty->assign('offers', FlashSalesOffer::getOffersBeforeTheDay(date('Y-m-d'), (int)(self::$cookie->id_lang)));
	}

	public function setMedia()
	{
		parent::setMedia();
		Tools::addCSS(_THEME_CSS_DIR_.'flashsalesoffer.css');
		Tools::addJS(_THEME_JS_DIR_.'flashsalesoffer.js');
	}

	public function displayContent()
	{
		parent::displayContent();
		self::$smarty->display(_PS_THEME_DIR_. $this->tpl_file);
	}
}
?>