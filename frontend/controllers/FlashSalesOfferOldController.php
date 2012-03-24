<?php
include_once _PS_MODULE_DIR_ . 'flashsales/backend/classes/FlashSalesOffer.php';
include_once _PS_MODULE_DIR_ . 'flashsales/backend/classes/FlashSalesCategory.php';
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
					Db::getInstance()->Execute('REPLACE INTO `'._DB_PREFIX_.'flashsales_offer_mailalert` (`id_flashsales_offer`, `id_customer`, `customer_email`, `date_add`, `date_upd`) VALUES ('.(int)($id_flashsales_offer).', '.(int)($id_customer).', \''.pSQL($customer_email).'\', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP())');
					self::$smarty->assign('mailalert_confirm', true);
				}
			}
		}

		// Categories
		$categories = FlashSalesCategory::getCategories((int)(self::$cookie->id_lang), false, false);
		if(isset($_GET['category']))
		{
			$id_category = (int)Tools::getValue('category');
			self::$smarty->assign('offers', FlashSalesOffer::getOffersBeforeTheDay(date('Y-m-d'), (int)(self::$cookie->id_lang), $id_category));
			self::$smarty->assign('categories', $categories);
			self::$smarty->assign('current_category', $id_category);
		}
		else
		{
			self::$smarty->assign('offers', FlashSalesOffer::getOffersBeforeTheDay(date('Y-m-d'), (int)(self::$cookie->id_lang)));
			self::$smarty->assign('categories', $categories);
			self::$smarty->assign('current_category', 0);
		}
		
		// Search
		if (Tools::isSubmit('SubmitOfferSearch'))
		{
			$search_text = Tools::getValue('search_text');
			$expr = Search::sanitize($search_text, (int)self::$cookie->id_lang);
			$sql = 'SELECT DISTINCT(fo.`id_flashsales_offer`)
			FROM `'._DB_PREFIX_.'flashsales_offer_lang` fol
			INNER JOIN `'._DB_PREFIX_.'flashsales_offer` fo ON (fo.`id_flashsales_offer` = fol.`id_flashsales_offer` AND fol.`id_lang` = '.(int)(self::$cookie->id_lang).')
			WHERE (`name` LIKE  \'%' . pSQL($expr) . '%\' OR `description` LIKE  \'%' . pSQL($expr) . '%\' OR `description_short` LIKE  \'%' . pSQL($expr) . '%\')
			AND fo.`active` = 1';
			if(Tools::getValue('search_type') == 1)
				$sql .= ' AND fo.`date_start` = \'' . date('Y-m-d') . '\'';
			else
				$sql .= ' AND fo.`date_end` <= \'' . date('Y-m-d') . '\'';
			$results = Db::getInstance()->ExecuteS($sql);
			$offers = array();
			foreach($results AS $result)
				$offers[] = new FlashSalesOffer($result['id_flashsales_offer'], (int)self::$cookie->id_lang, 'normal');

			self::$smarty->assign('offers', $offers);
		}
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
		self::$smarty->assign('pictofferSize', Image::getSize('pictoffer'));
		self::$smarty->display(_PS_THEME_DIR_. $this->tpl_file);
	}
}
?>