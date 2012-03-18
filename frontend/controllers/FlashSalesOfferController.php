<?php
class FlashSalesOfferControllerCore extends FrontController
{
	public $php_self = 'flashsalesoffer.php';
	public $tpl_file = 'flashsalesoffer.tpl';
	protected $flashsalesoffer;
	
	public function preProcess()
	{
		if ($id_flashsalesoffer = (int)Tools::getValue('id_flashsalesoffer'))
			$this->flashsalesoffer = new FlashSalesOffer($id_flashsalesoffer, self::$cookie->id_lang);

		if (!Validate::isLoadedObject($this->flashsalesoffer))
		{
			header('HTTP/1.1 404 Not Found');
			header('Status: 404 Not Found');
		}
		else
			$this->canonicalRedirection();

		parent::preProcess();
	}
	
	public function process()
	{
		parent::process();
		
		self::$smarty->assign('offers', FlashSalesOffer::getOffersForTheDay(date('Y-m-d'), (int)(self::$cookie->id_lang)));
	}

	public function displayContent()
	{
		parent::displayContent();
		self::$smarty->display(_PS_THEME_DIR_. $this->tpl_file);
	}
}
?>