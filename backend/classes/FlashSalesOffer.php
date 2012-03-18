<?php
class FlashSalesOffer extends ObjectModel
{
	public $id;
	public $id_flashsales_category;
	public $default;
	public $active = 1;
	public $video;
	public $position;
	public $video_forward = 0;
	public $date_start;
	public $date_end;

	public $name;
	public $description;
	public $description_short;
	public $link_rewrite;
	public $meta_title;
	public $meta_keywords;
	public $meta_description;

	public $date_add;
	public $date_upd;

	public $images;
	public $products;

	protected $table = 'flashsales_offer';
	protected $identifier = 'id_flashsales_offer';

	protected $fieldsRequired = array('active', 'date_start');
	protected $fieldsRequiredLang = array('name', 'description', 'description_short', 'link_rewrite');
	protected $fieldsSize = array('default' => 1, 'video' => 11);
	protected $fieldsSizeLang = array('name' => 128, 'link_rewrite' => 128, 'meta_title' => 128, 'meta_keywords' => 255, 'meta_description' => 255);

	protected $fieldsValidate = array(
		'id_flashsales_offer' => 'isUnsignedId',
		'id_flashsales_category' => 'isUnsignedId',
		'default' => 'isBool',
		'active' => 'isBool',
		'video' => 'isString',
		'video_forward' => 'isBool',
	);

	protected $fieldsValidateLang = array(
		'name' => 'isCatalogName',
		'description' => 'isString',
		'description_short' => 'isString',
		'link_rewrite' => 'isLinkRewrite',
		'meta_title' => 'isGenericName',
		'meta_keywords' => 'isGenericName',
		'meta_description' => 'isGenericName'
	);


	public function __construct($id_flashsales_offer = NULL, $id_lang = NULL)
	{
		parent::__construct($id_flashsales_offer, $id_lang);
		if(!$id_lang)
			$id_lang = Configuration::get('PS_LANG_DEFAULT');
		if($this->id)
		{
			$this->products = $this->getProducts($id_lang);
			$this->images		= $this->getImages($id_lang);
		}
	}
	public function getFields()
	{
		parent::validateFields();
		if (isset($this->id))
			$fields['id_flashsales_offer'] = (int)($this->id);
		$fields['id_flashsales_category'] = (int)($this->id_flashsales_category);
		$fields['default'] = (int)($this->default);
		$fields['active'] = (int)($this->active);
		$fields['position'] = (int)($this->position);
		$fields['video'] = pSQL($this->video);
		$fields['video_forward'] = (int)($this->video_forward);
		$fields['date_start'] = pSQL($this->date_start);
		$fields['date_end'] = pSQL($this->date_end);

		$fields['date_add']	 = pSQL($this->date_add);
		$fields['date_upd']	 = pSQL($this->date_upd);

		return $fields;
	}

	public function getTranslationsFieldsChild()
	{
		self::validateFieldsLang();

		$fieldsArray = array('name', 'description', 'description_short', 'link_rewrite', 'meta_title', 'meta_keywords', 'meta_description');
		$fields = array();
		$languages = Language::getLanguages(false);
		$defaultLanguage = Configuration::get('PS_LANG_DEFAULT');
		foreach ($languages as $language)
		{
			$fields[$language['id_lang']]['id_lang'] = $language['id_lang'];
			$fields[$language['id_lang']][$this->identifier] = (int)($this->id);
			$fields[$language['id_lang']]['name'] = (isset($this->name[$language['id_lang']])) ? pSQL($this->name[$language['id_lang']], true) : '';
			$fields[$language['id_lang']]['description'] = (isset($this->description[$language['id_lang']])) ? pSQL($this->description[$language['id_lang']], true) : '';
			$fields[$language['id_lang']]['description_short'] = (isset($this->description_short[$language['id_lang']])) ? pSQL($this->description_short[$language['id_lang']], true) : '';
			$fields[$language['id_lang']]['link_rewrite'] = (isset($this->link_rewrite[$language['id_lang']])) ? pSQL($this->link_rewrite[$language['id_lang']], true) : '';
			$fields[$language['id_lang']]['meta_title'] = (isset($this->meta_title[$language['id_lang']])) ? pSQL($this->meta_title[$language['id_lang']], true) : '';
			$fields[$language['id_lang']]['meta_keywords'] = (isset($this->meta_keywords[$language['id_lang']])) ? pSQL($this->meta_keywords[$language['id_lang']], true) : '';
			$fields[$language['id_lang']]['meta_description'] = (isset($this->meta_description[$language['id_lang']])) ? pSQL($this->meta_description[$language['id_lang']], true) : '';

			foreach ($fieldsArray as $field)
			{
				if (!Validate::isTableOrIdentifier($field))
					die(Tools::displayError());

				/* Check fields validity */
				if (isset($this->{$field}[$language['id_lang']]) AND !empty($this->{$field}[$language['id_lang']]))
					$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$language['id_lang']]);
				elseif (in_array($field, $this->fieldsRequiredLang))
				{
					if ($this->{$field} != '')
						$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$defaultLanguage]);
				}
				else
					$fields[$language['id_lang']][$field] = '';
			}
		}
		return $fields;
	}

	public function add($autodate = true, $nullValues = false)
	{ 
		$this->position = self::getLastPosition((int)$this->id_flashsales_category);
		return parent::add($autodate, true); 
	}

	public function update($nullValues = false)
	{
		if (parent::update($nullValues))
			return $this->cleanPositions($this->id_flashsales_category);
		return false;
	}

	public function delete()
	{
		if (parent::delete())
			return $this->cleanPositions($this->id_flashsales_category);
		return false;
	}

	public function getProducts($id_lang)
	{
		$results = Db::getInstance()->ExecuteS('SELECT `id_product` FROM `'._DB_PREFIX_.'flashsales_product` WHERE `id_flashsales_offer` = ' . $this->id);
		$products = array();
		foreach($results AS $result)
			$products[] = new Product($result['id_product'], false, $id_lang);

		return $products;
	}

	public function getImages($id_lang)
	{
		$results = Db::getInstance()->ExecuteS('SELECT `id_image` FROM `'._DB_PREFIX_.'flashsales_offer_image` WHERE `id_flashsales_offer` = ' . $this->id);
		$images = array();
		foreach($results AS $result)
			$images[] = $result['id_image'];

		return $images;
	}

	public static function getAllProducts($id_lang, $start, $limit, $orderBy, $orderWay, $id_flashsales_offer = false, $checked = false, $id_category = false, $only_active = false)
	{
		$all_products = Product::getProducts($id_lang, $start, $limit, $orderBy, $orderWay, $id_category = false, $only_active = false);

		if($checked)
		{
			foreach($all_products AS &$product)
			{
				foreach($checked AS $check)
				{
					if($check == $product['id_product'])
						$product['flashsales_checked'] = 1;
				}
			}
			return $all_products;
		}

		if($id_flashsales_offer)
		{
			$flashsales_products = Db::getInstance()->ExecuteS('SELECT fp.`id_product` FROM `'._DB_PREFIX_.'flashsales_product` fp WHERE fp.`id_flashsales_offer` = ' . (int)$id_flashsales_offer);
			if(!empty($flashsales_products))
			{
				foreach($all_products AS &$product)
				{
					foreach($flashsales_products AS $fproduct)
					{
						if($fproduct['id_product'] == $product['id_product'])
							$product['flashsales_checked'] = 1;
					}
				}
			}
		}

		return $all_products;
	}

	public static function getAllImages($id_lang, $start, $limit, $orderBy, $orderWay, $id_flashsales_offer = false, $checked, $id_category = false, $only_active = false)
	{
		if($id_flashsales_offer)
			$flashsales_images = Db::getInstance()->ExecuteS('SELECT foi.`id_image` FROM `'._DB_PREFIX_.'flashsales_offer_image` foi WHERE foi.`id_flashsales_offer` = ' . (int)$id_flashsales_offer);

		$all_products = self::getAllProducts($id_lang, $start, $limit, $orderBy, $orderWay, $id_flashsales_offer, false, $id_category, $only_active);
		$images = array();
		foreach($all_products AS $fproduct)
		{
			$product = new Product($fproduct['id_product'], false, $id_lang);
			$fimages = $product->getCombinationImages($id_lang);
			if(!empty($fimages))
			{
				foreach($fimages AS $fimage)
				{
					$images[] = array(
						'id_product' => $product->id,
						'id_image'	 => $fimage[0]['id_image'],
						'legend' => $fimage[0]['legend'],
						'id_product_attribute' => $fimage[0]['id_product_attribute']
					);
				}
			}
			else
			{
				$image = $product->getCover($product->id);

				$images[] = array(
					'id_product' => $product->id,
					'id_image'	 => $image['id_image'],
					'legend' => $product->name
				);
			}
		}
		$images = self::_distinctMultiDimensionalArray($images, 'id_image', true);
		if(isset($flashsales_images))
		{
			foreach($flashsales_images AS $fimage)
			{
				foreach($images AS &$image)
				{
					if($image['id_image'] == $fimage['id_image'])
						$image['checked'] = 1;
				}
			}
		}
		elseif(isset($checked) && is_array($checked))
		{
			foreach($checked AS $check)
			{
				foreach($images AS &$image)
				{
					if($image['id_image'] == $check)
						$image['checked'] = 1;
				}
			}
		}

		return $images;
	}

	public function extendSelection($selection)
	{
		if (!is_array($selection) OR !Validate::isTableOrIdentifier($this->identifier) OR !Validate::isTableOrIdentifier($this->table))
			die(Tools::displayError());
		$result = true;
		$this->id = (int)($selection[0]);
		$date_end = $this->date_end;
		$nbOffersForTheDay = self::getNumberOffersForTheDay($date_end); // 1
		$nbOffersToExtend	 = count($selection); // 1
		$nbOffers = Configuration::get('FS_NB_OFFERS'); // 4
		$nbOffersPossible = $nbOffers - $nbOffersForTheDay; // 3

		if($nbOffersToExtend > $nbOffersPossible)
		{
			// DELETE OFFERS
			$sql = '
			DELETE fo2, fol, foi, fp, fom
			FROM (SELECT `id_flashsales_offer` FROM `'._DB_PREFIX_.'flashsales_offer`
						WHERE `date_start` = \''. $date_end .'\'
						ORDER BY `position` DESC
						LIMIT ' . $nbOffersToExtend .') AS fo
			LEFT JOIN `'._DB_PREFIX_.'flashsales_offer` fo2 ON (fo2.`id_flashsales_offer` = fo.`id_flashsales_offer`)
			LEFT JOIN `'._DB_PREFIX_.'flashsales_offer_lang` fol ON (fol.`id_flashsales_offer` = fo.`id_flashsales_offer`)
			LEFT JOIN `'._DB_PREFIX_.'flashsales_offer_image` foi ON (foi.`id_flashsales_offer` = fo.`id_flashsales_offer`)
			LEFT JOIN `'._DB_PREFIX_.'flashsales_product` fp ON (fp.`id_flashsales_offer` = fo.`id_flashsales_offer`)
			LEFT JOIN `'._DB_PREFIX_.'flashsales_offer_mailalert` fom ON (fom.`id_flashsales_offer` = fo.`id_flashsales_offer`)';
			
			if(!Db::getInstance()->Execute($sql))
				$result = false;
		}

		if($result)
		{
			// EXTEND OFFERS
			$errors = array();
			foreach ($selection AS $id)
			{
				$this->id = (int)($id);
				if(strtotime($this->date_end) - strtotime($this->date_start) + Configuration::get('FS_TIME_BETWEEN_PERIOD') > 2 * Configuration::get('FS_TIME_BETWEEN_PERIOD'))
					$errors[] = Tools::displayError('Cannot extend offer') . ' "' . $this->id .'"' . Tools::displayError(': it\'s already extend');
				else
				{
					$this->date_end = date('Y-m-d', strtotime($this->date_end) + Configuration::get('FS_TIME_BETWEEN_PERIOD'));
					if(!$this->update())
						$result = false;
				}
			}
			if(!empty($errors))
				return $errors;
		}
		return $result;
	}

	public function updatePosition($way, $position)
	{
		if (!$res = Db::getInstance()->ExecuteS('
			SELECT fo.`id_flashsales_offer`, fo.`position`, fo.`id_flashsales_category` 
			FROM `'._DB_PREFIX_.'flashsales_offer` fo
			WHERE fo.`id_flashsales_category` = '.(int)$this->id_flashsales_category.' 
			ORDER BY fo.`position` ASC'
		))
			return false;
		
		foreach ($res AS $flashsales)
			if ((int)($flashsales['id_flashsales_offer']) == (int)($this->id))
				$movedFlashsales = $flashsales;
		
		if (!isset($movedFlashsales) || !isset($position))
			return false;
		
		// < and > statements rather than BETWEEN operator
		// since BETWEEN is treated differently according to databases
		return (Db::getInstance()->Execute('
			UPDATE `'._DB_PREFIX_.'flashsales_offer`
			SET `position`= `position` '.($way ? '- 1' : '+ 1').'
			WHERE `position` 
			'.($way 
				? '> '.(int)($movedFlashsales['position']).' AND `position` <= '.(int)($position)
				: '< '.(int)($movedFlashsales['position']).' AND `position` >= '.(int)($position)).'
			AND `id_flashsales_category`='.(int)($movedFlashsales['id_flashsales_category']))
		AND Db::getInstance()->Execute('
			UPDATE `'._DB_PREFIX_.'flashsales_offer`
			SET `position` = '.(int)($position).'
			WHERE `id_flashsales_offer` = '.(int)($movedFlashsales['id_flashsales_offer']).'
			AND `id_flashsales_category`='.(int)($movedFlashsales['id_flashsales_category'])));
	}

	public static function updateDefault($id_flashsales_offer, $date_start)
	{
		$result = Db::getInstance()->ExecuteS('
		SELECT `id_flashsales_offer`
		FROM `'._DB_PREFIX_.'flashsales_offer`
		WHERE `date_start` = \''.$date_start.'\'
		AND `id_flashsales_offer` != '.(int)($id_flashsales_offer).'
		ORDER BY `position`');

		$sizeof = sizeof($result);
		for ($i = 0; $i < $sizeof; ++$i){
				$sql = '
				UPDATE `'._DB_PREFIX_.'flashsales_offer`
				SET `default` = 0
				WHERE `date_start` = \''.$date_start.'\'
				AND `id_flashsales_offer` = '.(int)($result[$i]['id_flashsales_offer']);
				Db::getInstance()->Execute($sql);
			}
		return true;
	}

	public static function cleanDefault($id_flashsales_offer)
	{
		if(is_array($id_flashsales_offer))
			$id_flashsales_offer_temp = $id_flashsales_offer[0];
		else
			$id_flashsales_offer_temp = $id_flashsales_offer;

		$date_start = Db::getInstance()->getRow('
		SELECT `date_start`
		FROM `'._DB_PREFIX_.'flashsales_offer`
		WHERE `id_flashsales_offer` = '.(int)$id_flashsales_offer_temp);
		$date_start = $date_start['date_start'];

		if(is_array($id_flashsales_offer))
			$id_flashsales_offer = implode(', ', $id_flashsales_offer);

		$default = Db::getInstance()->getRow('
		SELECT COUNT(`id_flashsales_offer`) AS `default`
		FROM `'._DB_PREFIX_.'flashsales_offer`
		WHERE `date_start` = \''.$date_start.'\'
		AND `default` = 1
		AND `id_flashsales_offer` NOT IN ('. $id_flashsales_offer .')');
		$default = $default['default'];
		if(!$default)
		{
			$sql = '
				UPDATE `'._DB_PREFIX_.'flashsales_offer`
				SET `default` = 1
				WHERE `date_start` = \''.$date_start.'\'
				AND `id_flashsales_offer` NOT IN ('. $id_flashsales_offer .')
				ORDER BY `position`
				LIMIT 1';
			Db::getInstance()->Execute($sql);
		}
		return true;
	}

	public static function cleanPositions($id_category)
	{
		$result = Db::getInstance()->ExecuteS('
		SELECT `id_flashsales_offer`
		FROM `'._DB_PREFIX_.'flashsales_offer`
		WHERE `id_flashsales_category` = '.(int)($id_category).'
		ORDER BY `position`');
		$sizeof = sizeof($result);
		for ($i = 0; $i < $sizeof; ++$i){
				$sql = '
				UPDATE `'._DB_PREFIX_.'flashsales_offer`
				SET `position` = '.(int)($i).'
				WHERE `id_flashsales_category` = '.(int)($id_category).'
				AND `id_flashsales_offer` = '.(int)($result[$i]['id_flashsales_offer']);
				Db::getInstance()->Execute($sql);
			}
		return true;
	}

	public static function getLastPosition($id_category)
	{
		return (Db::getInstance()->getValue('SELECT MAX(position)+1 FROM `'._DB_PREFIX_.'flashsales_offer` WHERE `id_flashsales_category` = '.(int)($id_category)));
	}

	public static function getOffersForTheDay($date_start, $id_lang)
	{
		$results = Db::getInstance()->ExecuteS('SELECT `id_flashsales_offer`
			FROM `'._DB_PREFIX_.'flashsales_offer`
			WHERE `date_start` = \'' . $date_start . '\'
			AND `active` = 1');
		$offers = array();
		foreach($results AS $result)
			$offers[] = new FlashSalesOffer($result['id_flashsales_offer'], $id_lang);

		return $offers;
	}

	public static function getOffersBeforeTheDay($date_start, $id_lang)
	{
		$results = Db::getInstance()->ExecuteS('SELECT `id_flashsales_offer`
			FROM `'._DB_PREFIX_.'flashsales_offer`
			WHERE `date_end` <= \'' . $date_start . '\'
			AND `active` = 1');
		$offers = array();
		foreach($results AS $result)
			$offers[] = new FlashSalesOffer($result['id_flashsales_offer'], $id_lang);

		return $offers;
	}

	public static function getNumberOffersForTheDay($date_start)
	{
		$result = Db::getInstance()->getRow('SELECT COUNT(f.`id_flashsales_offer`) AS `number` FROM `'._DB_PREFIX_.'flashsales_offer` f WHERE f.`date_start` = \'' . $date_start . '\'');
		return $result['number'];
	}

	private static function _distinctMultiDimensionalArray($array, $keySearch, $overwrite = false, $exception = array())
	{
		// Check if it's an array
		if( !is_array($array) )
			return false;

		$result = array();

		foreach ( $array as $entry ) 
		{
			// If email doesn't exist
			if ( !isset($result[$entry[$keySearch]]) ) 
				$result[$entry[$keySearch]] = $entry;
			else 
			{
				// If email exist
				foreach ( $entry as $key => $value ) 
				{
					if( !empty($value) )
					{
						// If not empty value and this value is different from before and you don't want to overwrite values
						// Or you want to overwrite values except some keys
						if( ( !empty( $result[$entry[$keySearch]][$key] ) 
									&& $result[$entry[$keySearch]][$key] != $value 
									&& $overwrite == false )
							||
								( $overwrite == true 
									&& in_array($key, $exception) ) )
							$result[$entry[$keySearch]][$key] = $result[$entry[$keySearch]][$key] . ', ' . $value;
						else
							$result[$entry[$keySearch]][$key] = $value;
					}
				}
			}
		}
		$result = array_values($result);

		return $result;
	}
}
?>