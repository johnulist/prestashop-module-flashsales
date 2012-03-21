<?php
include_once _PS_MODULE_DIR_ . 'flashsales/backend/classes/FlashSalesOffer.php';
class FlashSalesOfferControllerCore extends FrontController
{
	public $php_self = 'flashsalesoffer.php';
	public $tpl_file = 'flashsalesoffer.tpl';
	protected $flashsalesoffer;
	
	public function preProcess()
	{
		if ($id_flashsales_offer = (int)Tools::getValue('id_flashsales_offer'))
			$this->flashsalesoffer = new FlashSalesOffer($id_flashsales_offer, self::$cookie->id_lang);

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

		self::$smarty->assign('flashsalesoffer', $this->flashsalesoffer);
	}

	public function displayContent()
	{
		parent::displayContent();
		self::$smarty->display(_PS_THEME_DIR_. $this->tpl_file);
	}
}
?>