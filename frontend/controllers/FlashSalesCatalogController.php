<?php
include_once _PS_MODULE_DIR_ . 'flashsales/backend/classes/FlashSalesOffer.php';
include_once _PS_MODULE_DIR_ . 'flashsales/backend/classes/FlashSalesCategory.php';
class FlashSalesCatalogControllerCore extends FrontController
{
	public $php_self = 'flashsalescatalog.php';
	public $tpl_file = 'flashsalescatalog.tpl';

	public function preProcess()
	{
		$oldOffers = false;
		$searchOffer = false;

		if(isset($_GET['old']) || isset($_POST['old']))
			$oldOffers = true;

		if(isset($_GET['category']) || isset($_POST['category']))
			$id_category = (int)Tools::getValue('category');
		else
			$id_category = 0;

		$this->canonicalRedirection();

		parent::preProcess();

		// Email
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
		self::$smarty->assign('categories', $categories);
		self::$smarty->assign('current_category', $id_category);
		
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
			if($oldOffers)
				$sql .= ' AND fo.`date_end` <= \'' . date('Y-m-d') . '\'';
			else
				$sql .= ' AND fo.`date_start` <= \'' . date('Y-m-d') . '\'';
			if($id_category)
				$sql .= ' AND fo.`id_flashsales_category` = ' . (int)$id_category;

			$results = Db::getInstance()->ExecuteS($sql);
			$offers = array();
			foreach($results AS $result)
				$offers[] = new FlashSalesOffer($result['id_flashsales_offer'], (int)self::$cookie->id_lang, 'normal');

			self::$smarty->assign('offers', $offers);
		}
		elseif($oldOffers)
			$offers = self::$smarty->assign('offers', FlashSalesOffer::getOffersBeforeTheDay(date('Y-m-d'), (int)(self::$cookie->id_lang), $id_category));
		else
			$offers = self::$smarty->assign('offers', FlashSalesOffer::getOffersBeforeTheDay(date('Y-m-d'), (int)(self::$cookie->id_lang), $id_category, true));

		$this->pagination(count($offers));
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
		self::$smarty->assign('current_period', date('Y-m-d', Configuration::get('FS_CURRENT_PERIOD')));
		self::$smarty->display(_PS_THEME_DIR_. $this->tpl_file);
	}

	public function pagination($nbOffers = 8)
	{
		if (!self::$initialized)
			$this->init();
		$offersPerPage = (int)(Configuration::get('FS_OFFERS_PER_PAGE'));
		$nArray = $offersPerPage != 8 ? array($offersPerPage, 8, 20, 50) : array(8, 20, 50);
		// Clean duplicate values
		$nArray = array_unique($nArray);
		asort($nArray);
		$this->n = abs((int)(Tools::getValue('n', ((isset(self::$cookie->nb_item_per_page) AND self::$cookie->nb_item_per_page >= 8) ? self::$cookie->nb_item_per_page : $offersPerPage))));
		
		$this->p = abs((int)(Tools::getValue('p', 1)));

		$current_url = Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']);
		//delete parameter page
		$current_url = preg_replace('/(\?)?(&amp;)?p=\d+/', '$1', $current_url);

		$range = 2; /* how many pages around page selected */

		if ($this->p < 0)
			$this->p = 0;

		if (isset(self::$cookie->nb_item_per_page) AND $this->n != self::$cookie->nb_item_per_page AND in_array($this->n, $nArray))
			self::$cookie->nb_item_per_page = $this->n;

		if ($this->p > ($nbOffers / $this->n))
			$this->p = ceil($nbOffers / $this->n);
		$pages_nb = ceil($nbOffers / (int)($this->n));

		$start = (int)($this->p - $range);
		if ($start < 1)
			$start = 1;
		$stop = (int)($this->p + $range);
		if ($stop > $pages_nb)
			$stop = (int)($pages_nb);
		self::$smarty->assign('nb_offers', $nbOffers);
		$pagination_infos = array(
			'offers_per_page' => $offersPerPage,
			'pages_nb' => $pages_nb,
			'p' => $this->p,
			'n' => $this->n,
			'nArray' => $nArray,
			'range' => $range,
			'start' => $start,
			'stop' => $stop,
			'current_url' => $current_url
		);
		self::$smarty->assign($pagination_infos);
	}
}
?>