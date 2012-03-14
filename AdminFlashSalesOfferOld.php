<?php
include_once _PS_MODULE_DIR_ . 'flashsales/backend/classes/FlashSalesOffer.php';

class AdminFlashSalesOfferOld extends AdminTab
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

		$this->_category = AdminFlashSalesContent::getCurrentFlashSalesCategory();

		$this->identifiersDnd = array('id_flashsales_offer' => 'id_flashsales_offer', 'id_flashsales_category' => 'id_flashsales_category_to_move');
		$this->fieldsDisplay = array(
			'id_' . strtolower($this->table)		=> array('title' => $this->l('ID'), 'align' => 'center', 'width' => 25),
			'name' => array('title' => $this->l('Name'), 'width' => 280, 'filter_key' => 'b!name'),
			'date_start' => array('title' => $this->l('Date start'), 'width' => 35, 'align' => 'right', 'type' => 'date', 'filter_key' => 'a!date_start'),
			'date_end' => array('title' => $this->l('Date end'), 'width' => 35, 'align' => 'right', 'type' => 'date', 'filter_key' => 'a!date_end'),
			'position' => array('title' => $this->l('Position'), 'width' => 40,'filter_key' => 'position', 'align' => 'center', 'position' => 'position'),
			'active' => array('title' => $this->l('Enabled'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false)
		);

		//$this->_select	 = '';
		//$this->_join		 = '';
		$this->_where = 'AND a.`date_end` <= CURRENT_DATE()';
		//$this->_group		 = '';
		//$this->_having	 = '';
		//$this->_filter	 = '';
		//$this->_orderBy	 = '';
		//$this->_orderWay = 'ASC';

		parent::__construct();
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

		$this->getList((int)($cookie->id_lang), !$cookie->__get($this->table.'Orderby') ? 'position' : NULL, !$cookie->__get($this->table.'Orderway') ? 'ASC' : NULL);
		echo'<div style="margin:10px;">';
		$this->displayList($token);
		echo '</div>';
	}
}