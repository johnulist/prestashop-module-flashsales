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
			$this->flashsalesoffer = new FlashSalesOffer($id_flashsales_offer, self::$cookie->id_lang, 'fully');

		if (!Validate::isLoadedObject($this->flashsalesoffer) || $this->flashsalesoffer->date_start != date('Y-m-d', Configuration::get('FS_CURRENT_PERIOD')))
		{
			header('HTTP/1.1 404 Not Found');
			header('Status: 404 Not Found');
		}
		else
			$this->canonicalRedirection();

		parent::preProcess();
	}

	public function canonicalRedirection()
	{
		// Automatically redirect to the canonical URL if the current in is the right one
		// $_SERVER['HTTP_HOST'] must be replaced by the real canonical domain
		if (Validate::isLoadedObject($this->flashsalesoffer) && strtoupper($_SERVER['REQUEST_METHOD']) == 'GET')
		{
			$canonicalURL = FlashSalesOffer::getOfferLink($this->flashsalesoffer);
			if (!preg_match('/^'.Tools::pRegexp($canonicalURL, '/').'([&?].*)?$/', Tools::getProtocol().$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']))
			{
				header('HTTP/1.0 301 Moved');
				if (defined('_PS_MODE_DEV_') AND _PS_MODE_DEV_)
					die('[Debug] This page has moved<br />Please use the following URL instead: <a href="'.$canonicalURL.'">'.$canonicalURL.'</a>');
				Tools::redirectLink($canonicalURL);
			}
		}
	}

	public function process()
	{
		global $cart, $currency;
		parent::process();

		if (Validate::isLoadedObject($this->flashsalesoffer) && $this->flashsalesoffer->date_start == date('Y-m-d', Configuration::get('FS_CURRENT_PERIOD')))
		{
			$id_customer = (isset(self::$cookie->id_customer) AND self::$cookie->id_customer) ? (int)(self::$cookie->id_customer) : 0;
			$id_group = $id_customer ? (int)(Customer::getDefaultGroupId($id_customer)) : _PS_DEFAULT_CUSTOMER_GROUP_;
    	
			self::$smarty->assign('flashsalesoffer', $this->flashsalesoffer);
			self::$smarty->assign('flashsalesoffer_others', FlashSalesOffer::getOthersOffersForTheDay(date('Y-m-d', Configuration::get('FS_CURRENT_PERIOD')), (int)self::$cookie->id_lang, $this->flashsalesoffer->id));
    	
			$taxes = array();
			$no_taxes = array();
			$groups_reduction = array();

			foreach($this->flashsalesoffer->products AS $product)
			{
				$taxes[$product['product']->id] 		= (float)(Tax::getProductTaxRate((int)($product['product']->id), $cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
				$no_taxes[$product['product']->id] 	=  Tax::excludeTaxeOption() OR !Tax::getProductTaxRate((int)($product['product']->id), $cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
    	
				$group_reduction = GroupReduction::getValueForProduct($product['product']->id, $id_group);
				if ($group_reduction == 0)
					$group_reduction = Group::getReduction((int)self::$cookie->id_customer) / 100;
				$groups_reduction[$product['product']->id] = (1 - $group_reduction);
			}
			$ecotax_rate = (float) Tax::getProductEcotaxRate($cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
			self::$smarty->assign('taxes_rate', $taxes);
			self::$smarty->assign('no_taxes', $no_taxes);
			self::$smarty->assign('groups_reduction', $groups_reduction);
    	
			self::$smarty->assign(array(
				'display_qties' => (int)Configuration::get('PS_DISPLAY_QTIES'),
				'currencySign' => $currency->sign,
				'ecotaxTax_rate' => $ecotax_rate,
				'currencyRate' => $currency->conversion_rate,
				'currencyFormat' => $currency->format,
				'currencyBlank' => $currency->blank,
			));
		}
		else
		{
			header('HTTP/1.1 404 Not Found');
			header('Status: 404 Not Found');
			$this->errors[] = Tools::displayError('Offer not found');
		}
	}

	public function displayHeader()
	{
		if (!Validate::isLoadedObject($this->flashsalesoffer) || $this->flashsalesoffer->date_start != date('Y-m-d', Configuration::get('FS_CURRENT_PERIOD')))
		{
			header('HTTP/1.1 404 Not Found');
			header('Status: 404 Not Found');
		}
		else
		{
			self::$smarty->assign('flashsalesoffer_name', $this->flashsalesoffer->name);
			self::$smarty->assign('flashsalesoffer_description', $this->flashsalesoffer->description);
			self::$smarty->assign('flashsalesoffer_image', $this->flashsalesoffer->images[0]['imgIds']);
			self::$smarty->assign('flashsalesoffer_price', $this->flashsalesoffer->prices['min_price']);
		}
		parent::displayHeader();
	}
	public function displayContent()
	{
		parent::displayContent();
		self::$smarty->display(_PS_THEME_DIR_. $this->tpl_file);
	}
}
?>