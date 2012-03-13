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
		$fields['video_forward'] = (int)($this->video);
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
	
	public static function getProducts($id_lang, $start, $limit, $orderBy, $orderWay, $id_flashsales_offer = false, $id_category = false, $only_active = false)
	{
		$all_products = Product::getProducts($id_lang, $start, $limit, $orderBy, $orderWay, $id_category = false, $only_active = false);

		if($id_flashsales_offer)
		{
			$flashsales_products = Db::getInstance()->ExecuteS('SELECT fp.`id_product` FROM `'._DB_PREFIX_.'flashsales_product` fp WHERE fp.`id_flashsales_offer` = ' . (int)$id_flashsales_offer);
			//die(print_r($flashsales_products));
			foreach($all_products AS &$product)
			{
				if(self::_rec_in_array($product['id_product'], $flashsales_products))
					$product['checked'] = 1;
				else
					$product['checked'] = 0;
			}
		}

		return $all_products;
	}

	public static function getImages($id_flashsales_offer)
	{
		if(!$id_flashsales_offer)
			return null;
		$flashsales_products	= Db::getInstance()->ExecuteS('SELECT fp.`id_product` FROM `'._DB_PREFIX_.'flashsales_product` fp WHERE fp.`id_flashsales_offer` = ' . (int)$id_flashsales_offer);
		$flashsales_images		= Db::getInstance()->ExecuteS('SELECT foi.`id_image` FROM `'._DB_PREFIX_.'flashsales_offer_image` foi WHERE foi.`id_flashsales_offer` = ' . (int)$id_flashsales_offer);

		if(empty($flashsales_products) || empty($flashsales_images))
			return null;
		$images = array();
		foreach($flashsales_products AS $product)
		{
			$image = Db::getInstance()->getRow('
				SELECT id_image
				FROM '._DB_PREFIX_.'image
				WHERE id_product = '.(int)($product['id_product']).' AND cover = 1'
			);

			$images[] = array(
				'id_product' => $product['id_product'],
				'id_image' => $image['id_image'],
				'checked' => (self::_rec_in_array($image['id_image'], $flashsales_images) ? 1 : 0)
			);
		}
		
		return $images;
	}

	private static function _rec_in_array($needle, $haystack, $alsokeys=false)
	{
		if(!is_array($haystack))
			return false;

		if(in_array($needle, $haystack) || ($alsokeys && in_array($needle, array_keys($haystack))))
			return true;
		else
		{
			foreach($haystack AS $element)
				$ret = self::_rec_in_array($needle, $element, $alsokeys);
		}

		return $ret;
	}
}
?>