<?php
include_once _PS_MODULE_DIR_ . 'flashsales/backend/classes/FlashSalesOffer.php';
//include_once _PS_MODULE_DIR_ . 'module/backend/classes/FlashSalesCategory.php';
//include_once _PS_MODULE_DIR_ . 'module/backend/classes/FlashSalesProduct.php';
//include_once _PS_MODULE_DIR_ . 'module/backend/classes/FlashSalesProductImage.php';
//include_once _PS_MODULE_DIR_ . 'module/backend/classes/FlashSalesMailAlert.php';

class AdminFlashSalesOfTheWeek extends AdminTab
{
	private $_module = 'flashsales';
	
	public function __construct()
	{
		$this->table = 'flashsales_offer';
		$this->className	= 'FlashSalesOffer';
		$this->identifier = 'id_' . strtolower($this->table);

		$this->lang			 = true;
		$this->add			 = false;
		$this->edit			 = false;
		$this->delete		 = false;
		$this->view			 = false;
		$this->duplicate = false;

		$this->fieldsDisplay = array(
			'id_' . strtolower($this->table)		=> array('title' => $this->l('ID'), 'align' => 'center', 'width' => 25),
			'name' => array('title' => $this->l('Name'), 'width' => 280, 'filter_key' => 'b!name'),
			'default' => array('title' => $this->l('Default'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false),
			'date_start' => array('title' => $this->l('Date start'), 'width' => 35, 'align' => 'right', 'type' => 'date', 'filter_key' => 'a!date_start'),
			'date_end' => array('title' => $this->l('Date end'), 'width' => 35, 'align' => 'right', 'type' => 'date', 'filter_key' => 'a!date_end'),
			'position' => array('title' => $this->l('Position'), 'width' => 40,'filter_key' => 'position', 'align' => 'center', 'position' => 'position'),
			'active' => array('title' => $this->l('Enabled'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false)
		);

		parent::__construct();
	}

	public function displayForm($token = NULL)
	{
		global $currentIndex, $link, $cookie;
		parent::displayForm();

		if (!($obj = $this->loadObject(true)))
			return;

		echo '<link rel="stylesheet" href="../backend/css/' . strtolower($this->_module) . '.backend.admin.style.css">';
		echo '<script src="../backend/js/' . strtolower($this->_module) . '.backend.admin.script.js"></script>';
	}

	public function displayList($token = NULL)
	{
		global $currentIndex;
		
		/* Display list header (filtering, pagination and column names) */
		$this->displayListHeader($token);
		if (!sizeof($this->_list))
			echo '<tr><td class="center" colspan="'.(sizeof($this->fieldsDisplay) + 2).'">'.$this->l('No items found').'</td></tr>';

		/* Show the content of the table */
		$this->displayListContent($token);

		/* Close list table and submit button */
		$this->displayListFooter($token);
	}

	public function display($token = NULL)
	{
		global $currentIndex, $cookie;

		if (($id_flashsales_category = (int)Tools::getValue('id_flashsales_category')))
			$currentIndex .= '&id_flashsales_category='.$id_flashsales_category;
		$this->getList((int)($cookie->id_lang), !$cookie->__get($this->table.'Orderby') ? 'position' : NULL, !$cookie->__get($this->table.'Orderway') ? 'ASC' : NULL);
		//$this->getList((int)($cookie->id_lang));
		if (!$id_flashsales_category)
			$id_flashsales_category = 1;
		if($this->add === true)
			echo '<a href="'.$currentIndex.'&id_flashsales_category='.$id_flashsales_category.'&add'.$this->table.'&token='.Tools::getAdminTokenLite('AdminFlashSalesContent').'"><img src="../img/admin/add.gif" border="0" /> '.$this->l('Add a new flash sales').'</a>';
		echo '<div style="margin:10px;">';
		$this->displayList($token);
		echo '</div>';
	}
}
?>