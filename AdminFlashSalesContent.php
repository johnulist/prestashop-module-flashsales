<?php
include_once(PS_ADMIN_DIR.'/../classes/AdminTab.php');
include_once _PS_MODULE_DIR_ . 'flashsales/backend/classes/FlashSalesOffer.php';
include_once _PS_MODULE_DIR_ . 'flashsales/backend/classes/FlashSalesCategory.php';
include_once(_PS_MODULE_DIR_ . 'flashsales/AdminFlashSalesCategories.php');
include_once(_PS_MODULE_DIR_ . 'flashsales/AdminFlashSalesOffer.php');
include_once(_PS_MODULE_DIR_ . 'flashsales/AdminFlashSalesOfTheWeek.php');

class AdminFlashSalesContent extends AdminTab
{
	private $adminFlashSalesCategories;
	private $adminFlashSalesOffer;
	private $adminFlashSalesOfTheWeek;

	private static $_category;

	public function __construct()
	{
		/* Get current category */
		$id_flashsales_category = (int)(Tools::getValue('id_flashsales_category', Tools::getValue('id_flashsales_category_parent', 1)));
		self::$_category = new FlashSalesCategory($id_flashsales_category);
		if (!Validate::isLoadedObject(self::$_category))
			die('Category cannot be loaded');

		$this->table = array('flashsales_category', 'flashsales_offer');
		$this->adminFlashSalesCategories = new adminFlashSalesCategories();
		$this->adminFlashSalesOffer = new adminFlashSalesOffer();
		$this->adminFlashSalesOfTheWeek = new adminFlashSalesOfTheWeek();

		parent::__construct();
	}

	public static function getCurrentFlashSalesCategory()
	{
		return self::$_category;
	}

	public function viewAccess($disable = false)
	{
		$result = parent::viewAccess($disable);
		$this->adminFlashSalesCategories->tabAccess = $this->tabAccess;
		$this->adminFlashSalesOffer->tabAccess = $this->tabAccess;
		$this->adminFlashSalesOfTheWeek->tabAccess = $this->tabAccess;

		return $result;
	}

	public function postProcess()
	{
		if (Tools::isSubmit('submitDelflashsales_offer') OR Tools::isSubmit('submitAddflashsales_offer') OR isset($_GET['deleteflashsales_offer']) OR Tools::isSubmit('viewflashsales_offer') OR (Tools::isSubmit('statusflashsales_offer') AND Tools::isSubmit('id_flashsales_offer')) OR (Tools::isSubmit('position') AND !Tools::isSubmit('id_flashsales_category_to_move')))
			$this->adminFlashSalesOffer->postProcess();
		if (Tools::isSubmit('submitDelflashsales_category') OR Tools::isSubmit('submitAddflashsales_categoryAndBackToParent') OR Tools::isSubmit('submitAddflashsales_category') OR isset($_GET['deleteflashsales_category']) OR (Tools::isSubmit('statusflashsales_category') AND Tools::isSubmit('id_flashsales_category')) OR (Tools::isSubmit('position') AND Tools::isSubmit('id_flashsales_category_to_move')))
			$this->adminFlashSalesCategories->postProcess();
	}

	public function displayErrors()
	{
		parent::displayErrors();
		$this->adminFlashSalesOffer->displayErrors();
		$this->adminFlashSalesCategories->displayErrors();
	}

	public function display()
	{
		global $currentIndex;

		if (((Tools::isSubmit('submitAddflashsales_category') OR Tools::isSubmit('submitAddflashsales_categoryAndStay')) AND sizeof($this->adminFlashSalesCategories->_errors)) OR isset($_GET['updateflashsales_category']) OR isset($_GET['addflashsales_category']))
		{
			$this->adminFlashSalesCategories->displayForm($this->token);
			echo '<br /><br /><a href="'.$currentIndex.'&token='.$this->token.'"><img src="../img/admin/arrow2.gif" /> '.$this->l('Back to list').'</a><br />';
			
		}
		elseif ((Tools::isSubmit('submitAddflashsales_offer') AND sizeof($this->adminFlashSalesOffer->_errors)) OR isset($_GET['updateflashsales_offer']) OR isset($_GET['addflashsales_offer']))
		{
			$this->adminFlashSalesOffer->displayForm($this->token);
			echo '<br /><br /><a href="'.$currentIndex.'&token='.$this->token.'"><img src="../img/admin/arrow2.gif" /> '.$this->l('Back to list').'</a><br />';
		}
		else
		{
		$id_flashsales_category = (int)(Tools::getValue('id_flashsales_category'));
		if (!$id_flashsales_category)
			$id_flashsales_category = 1;
		$flashsales_tabs = array('flashsales_category', 'flashsales_offer');
		// Cleaning links
		$catBarIndex = $currentIndex;
		foreach ($flashsales_tabs AS $tab)
			if (Tools::getValue($tab.'Orderby') && Tools::getValue($tab.'Orderway')) 
				$catBarIndex = preg_replace('/&'.$tab.'Orderby=([a-z _]*)&'.$tab.'Orderway=([a-z]*)/i', '', $currentIndex);
		echo '<h2>' . $this->l('Flash sales of the week') . '</h2>';
		$this->adminFlashSalesOfTheWeek->display($this->token);
		echo '<div class="cat_bar"><span style="color: #3C8534;">'.$this->l('Current flash sales category').' :</span>&nbsp;&nbsp;&nbsp;'.getPath($catBarIndex, $id_flashsales_category,'','','flashsales_offer').'</div>';
		echo '<h2>'.$this->l('Categories').'</h2>';
		$this->adminFlashSalesCategories->display($this->token);
		echo '<div style="margin:10px">&nbsp;</div>';
		echo '<h2>'.$this->l('Flashsales in this category').'</h2>';
		$this->adminFlashSalesOffer->display($this->token);
		}
		
	}
}

?>