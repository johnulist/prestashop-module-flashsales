<?php

class FlashSalesCategory extends ObjectModel
{
	public $id;
	public $id_flashsales_category;
	public $id_parent;
	public $level_depth;
	public $active = 1;
	public $position;

	public $name;
	public $description;
	public $link_rewrite;
	public $meta_title;
	public $meta_keywords;
	public $meta_description;

	public $date_add;
	public $date_upd;

	protected $tables = array ('flashsales_category', 'flashsales_category_lang');

	protected 	$fieldsRequired = array('id_parent', 'active');
	protected 	$fieldsSize = array('id_parent' => 10, 'active' => 1);
	protected 	$fieldsValidate = array('active' => 'isBool', 'id_parent' => 'isUnsignedInt');
	protected 	$fieldsRequiredLang = array('name', 'link_rewrite');
	protected 	$fieldsSizeLang = array('name' => 64, 'link_rewrite' => 64, 'meta_title' => 128, 'meta_description' => 255, 'meta_keywords' => 255);
	protected 	$fieldsValidateLang = array(
		'name' => 'isCatalogName',
		'link_rewrite' => 'isLinkRewrite',
		'description' => 'isCleanHtml',
		'meta_title' => 'isGenericName',
		'meta_description' => 'isGenericName',
		'meta_keywords' => 'isGenericName'
	);

	protected 	$table = 'flashsales_category';
	protected 	$identifier = 'id_flashsales_category';

	public function __construct($id_flashsales_category = NULL, $id_lang = NULL)
	{
		parent::__construct($id_flashsales_category, $id_lang);
	}

	public function getFields()
	{
		parent::validateFields();
		if (isset($this->id))
			$fields['id_flashsales_category'] = (int)($this->id);
		$fields['active'] = (int)($this->active);
		$fields['id_parent'] = (int)($this->id_parent);
		$fields['level_depth'] = (int)($this->level_depth);
		$fields['position'] = (int)($this->position);
		$fields['date_add'] = pSQL($this->date_add);
		$fields['date_upd'] = pSQL($this->date_upd);
		return $fields;
	}

	/**
	  * Check then return multilingual fields for database interaction
	  *
	  * @return array Multilingual fields
	  */
	public function getTranslationsFieldsChild()
	{
		parent::validateFieldsLang();
		return parent::getTranslationsFields(array('name', 'description', 'link_rewrite', 'meta_title', 'meta_keywords', 'meta_description'));
	}

	public function getName($id_lang = NULL)
	{
		if (!$id_lang)
		{
			global $cookie;

			if (isset($this->name[$cookie->id_lang]))
				$id_lang = $cookie->id_lang;
			else
				$id_lang = (int)(Configuration::get('PS_LANG_DEFAULT'));
		}
		return isset($this->name[$id_lang]) ? $this->name[$id_lang] : '';
	}

	public static function hideFlashSalesCategoryPosition($name)
	{
		return preg_replace('/^[0-9]+\./', '', $name);
	}

	public static function getCategories($id_lang, $active = true, $order = true)
	{
	 	if (!Validate::isBool($active))
	 		die(Tools::displayError());

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
		SELECT *
		FROM `'._DB_PREFIX_.'flashsales_category` c
		LEFT JOIN `'._DB_PREFIX_.'flashsales_category_lang` cl ON c.`id_flashsales_category` = cl.`id_flashsales_category`
		WHERE `id_lang` = '.(int)($id_lang).'
		'.($active ? 'AND `active` = 1' : '').'
		ORDER BY `name` ASC');

		if (!$order)
			return $result;

		$categories = array();
		foreach ($result AS $row)
			$categories[$row['id_parent']][$row['id_flashsales_category']]['infos'] = $row;
		return $categories;
	}

	public static function recurseFlashSalesCategory($categories, $current, $id_flashsales_category = 1, $id_selected = 1, $is_html = 0)
	{
		$html = '<option value="'.$id_flashsales_category.'"'.(($id_selected == $id_flashsales_category) ? ' selected="selected"' : '').'>'.
		str_repeat('&nbsp;', $current['infos']['level_depth'] * 5).self::hideFlashSalesCategoryPosition(stripslashes($current['infos']['name'])).'</option>';
		if ($is_html == 0)
			echo $html;
		if (isset($categories[$id_flashsales_category]))
			foreach (array_keys($categories[$id_flashsales_category]) AS $key)
				$html .= self::recurseFlashSalesCategory($categories, $categories[$id_flashsales_category][$key], $key, $id_selected, $is_html);
		return $html;
	}
}

?>