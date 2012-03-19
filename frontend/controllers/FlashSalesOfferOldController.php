<?php
class FlashSalesOfferOldControllerCore extends FrontController
{
	public $php_self = 'flashsalesofferold.php';
	public $tpl_file = 'flashsalesofferold.tpl';

	public function preProcess()
	{
		$this->canonicalRedirection();

		parent::preProcess();
		
		if (Tools::isSubmit('SubmitMailAlert'))
		{
			$id_flashsales_offer = (int)(Tools::getValue('id_flashsales_offer'));
			$insert = false;

			if(!empty($id_flashsales_offer))
			{
				if (!self::$cookie->isLogged())
				{
					$customer_email = Tools::getValue('customer_email');
					
					if (empty($customer_email) || !Validate::isEmail($customer_email) || $customer_email == 'your@email.com')
					{
						// Empty email
						$this->errors[] = Tools::displayError('Your e-mail address is invalid');
					}
					else
					{
						$id_customer = (int)Db::getInstance()->getValue('SELECT id_customer FROM '._DB_PREFIX_.'customer WHERE email=\''.pSQL($customer_email).'\' AND is_guest=0');
						if (!Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'flashsales_offer_mailalert` WHERE `id_customer` = '.(int)($id_customer).' AND `customer_email` = \''.pSQL($customer_email).'\' AND `id_flashsales_offer` = '.(int)($id_flashsales_offer)))
							$insert = true;
						else
						{
							// Already in DB.
							$this->errors[] = Tools::displayError('You\'re already register for this offer.');
						}
					}
				}
				else
				{
					$id_customer = (int)($cookie->id_customer);
					$customer_email = Db::getInstance()->getValue('SELECT email FROM '._DB_PREFIX_.'customer WHERE id_customer=\''.(int)($id_customer).'\' AND is_guest=0');
					$insert = true;
				}

				if($insert)
				{
					Db::getInstance()->Execute('REPLACE INTO `'._DB_PREFIX_.'flashsales_offer_mailalert` (`id_flashsales_offer`, `id_customer`, `customer_email`) VALUES ('.(int)($id_flashsales_offer).', '.(int)($id_customer).', \''.pSQL($customer_email).'\'');
					self::$smarty->assign('mailalert_confirm', true);
				}
			}
		}
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