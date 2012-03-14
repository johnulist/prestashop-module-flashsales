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
		//$this->products = self::getProducts($id_lang, 0, 'ALL', 'id_product', 'ASC', $id_flashsales_offer);
		//$this->images = self::getImages($id_lang, 0, 'ALL', 'id_product', 'ASC', $id_flashsales_offer);
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

	public function getProducts($id_lang, $id_category = false)
	{
		
	}

	public static function getAllProducts($id_lang, $start, $limit, $orderBy, $orderWay, $id_flashsales_offer = false, $id_category = false, $only_active = false)
	{
		$all_products = Product::getProducts($id_lang, $start, $limit, $orderBy, $orderWay, $id_category = false, $only_active = false);

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

	public static function getAllImages($id_lang, $start, $limit, $orderBy, $orderWay, $id_flashsales_offer = false, $id_category = false, $only_active = false)
	{
		if($id_flashsales_offer)
			$flashsales_images = Db::getInstance()->ExecuteS('SELECT foi.`id_image` FROM `'._DB_PREFIX_.'flashsales_offer_image` foi WHERE foi.`id_flashsales_offer` = ' . (int)$id_flashsales_offer);

		$all_products = self::getAllProducts($id_lang, $start, $limit, $orderBy, $orderWay, $id_flashsales_offer = false, $id_category = false, $only_active = false);
		$images = array();
		foreach($all_products AS $fproduct)
		{
			$product = new Product($fproduct['id_product'], $id_lang);
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
		$images = self::distinctMultiDimensionalArray($images, 'id_image', true);
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

		return $images;
	}

	public static function distinctMultiDimensionalArray($array, $keySearch, $overwrite = false, $exception = array())
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