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
		$this->edit			 = true;
		$this->delete		 = true;
		$this->view			 = false;
		$this->duplicate = false;

		$this->_category = AdminFlashSalesContent::getCurrentFlashSalesCategory();

		$this->identifiersDnd = array('id_flashsales_offer' => 'id_flashsales_offer', 'id_flashsales_category' => 'id_flashsales_category_to_move');
		$this->fieldsDisplay = array(
			'id_' . strtolower($this->table)		=> array('title' => $this->l('ID'), 'align' => 'center', 'width' => 25),
			'name' => array('title' => $this->l('Name'), 'width' => 460, 'filter_key' => 'b!name'),
			'date_start' => array('title' => $this->l('Date start'), 'width' => 35, 'align' => 'right', 'type' => 'date', 'filter_key' => 'a!date_start'),
			'date_end' => array('title' => $this->l('Date end'), 'width' => 35, 'align' => 'right', 'type' => 'date', 'filter_key' => 'a!date_end'),
			'position' => array('title' => $this->l('Position'), 'width' => 40,'filter_key' => 'position', 'align' => 'center'),
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
		$currentIndex .= '&flashsales_old=1';
		/* Show the content of the table */
		$this->displayListContent($token);

		/* Close list table and submit button */
		$this->displayListFooter($token);
	}

	public function displayListHeader($token = NULL)
	{
		global $currentIndex, $cookie;
		$isCms = false;
		if (preg_match('/cms/Ui', $this->identifier))
			$isCms = true;
		$id_cat = Tools::getValue('id_'.($isCms ? 'cms_' : '').'category');

		if (!isset($token) OR empty($token))
			$token = $this->token;

		/* Determine total page number */
		/*$totalPages = ceil($this->_listTotal / Tools::getValue('pagination', (isset($cookie->{$this->table.'_pagination'}) ? $cookie->{$this->table.'_pagination'} : $this->_pagination[0])));
		if (!$totalPages) $totalPages = 1;

		echo '<a name="'.$this->table.'">&nbsp;</a>';
		echo '<form method="post" action="'.$currentIndex;
		if (Tools::getIsset($this->identifier))
			echo '&'.$this->identifier.'='.(int)(Tools::getValue($this->identifier));
		echo '&token='.$token;
		if (Tools::getIsset($this->table.'Orderby'))
			echo '&'.$this->table.'Orderby='.urlencode($this->_orderBy).'&'.$this->table.'Orderway='.urlencode(strtolower($this->_orderWay));
		echo '#'.$this->table.'" class="form">
		<input type="hidden" id="submitFilter'.$this->table.'" name="submitFilter'.$this->table.'" value="0">
		<table>
			<tr>
				<td style="vertical-align: bottom;">
					<span style="float: left;">'; */

		/* Determine current page number */
		/* $page = (int)(Tools::getValue('submitFilter'.$this->table));
		if (!$page) $page = 1;
		if ($page > 1)
			echo '
						<input type="image" src="../img/admin/list-prev2.gif" onclick="getE(\'submitFilter'.$this->table.'\').value=1"/>
						&nbsp; <input type="image" src="../img/admin/list-prev.gif" onclick="getE(\'submitFilter'.$this->table.'\').value='.($page - 1).'"/> ';
		echo $this->l('Page').' <b>'.$page.'</b> / '.$totalPages;
		if ($page < $totalPages)
			echo '
						<input type="image" src="../img/admin/list-next.gif" onclick="getE(\'submitFilter'.$this->table.'\').value='.($page + 1).'"/>
						 &nbsp;<input type="image" src="../img/admin/list-next2.gif" onclick="getE(\'submitFilter'.$this->table.'\').value='.$totalPages.'"/>';
		echo '			| '.$this->l('Display').'
						<select name="pagination">'; */
		/* Choose number of results per page */
		/*$selectedPagination = Tools::getValue('pagination', (isset($cookie->{$this->table.'_pagination'}) ? $cookie->{$this->table.'_pagination'} : NULL));
		foreach ($this->_pagination AS $value)
			echo '<option value="'.(int)($value).'"'.($selectedPagination == $value ? ' selected="selected"' : (($selectedPagination == NULL && $value == $this->_pagination[1]) ? ' selected="selected2"' : '')).'>'.(int)($value).'</option>';
		echo '
						</select>
						/ '.(int)($this->_listTotal).' '.$this->l('result(s)').'
					</span>
					<span style="float: right;">
						<input type="submit" name="submitReset'.$this->table.'" value="'.$this->l('Reset').'" class="button" />
						<input type="submit" id="submitFilterButton_'.$this->table.'" name="submitFilter" value="'.$this->l('Filter').'" class="button" />
					</span>
					<span class="clear"></span>
				</td>
			</tr>
			<tr>
				<td>';
				*/
		/* Display column names and arrows for ordering (ASC, DESC) */
		if (array_key_exists($this->identifier,$this->identifiersDnd) AND $this->_orderBy == 'position')
		{
			echo '
			<script type="text/javascript" src="../js/jquery/jquery.tablednd_0_5.js"></script>
			<script type="text/javascript">
				var token = \''.($token!=NULL ? $token : $this->token).'\';
				var come_from = \''.$this->table.'\';
				var alternate = \''.($this->_orderWay == 'DESC' ? '1' : '0' ).'\';
			</script>
			<script type="text/javascript" src="../js/admin-dnd.js"></script>
			';
		}
		echo '<table'.(array_key_exists($this->identifier,$this->identifiersDnd) ? ' id="'.(((int)(Tools::getValue($this->identifiersDnd[$this->identifier], 1))) ? substr($this->identifier,3,strlen($this->identifier)) : '').'"' : '' ).' class="table'.((array_key_exists($this->identifier,$this->identifiersDnd) AND ($this->_orderBy != 'position 'AND $this->_orderWay != 'DESC')) ? ' tableDnD'  : '' ).'" cellpadding="0" cellspacing="0">
			<thead>
				<tr class="nodrag nodrop">
					<th>';
		if ($this->delete)
			echo '		<input type="checkbox" name="checkme" class="noborder" onclick="checkDelBoxes(this.form, \''.$this->table.'Box[]\', this.checked)" />';
		echo '		</th>';
		foreach ($this->fieldsDisplay AS $key => $params)
		{
			echo '	<th '.(isset($params['widthColumn']) ? 'style="width: '.$params['widthColumn'].'px"' : '').'>'.$params['title'];
			/*if (!isset($params['orderby']) OR $params['orderby'])
			{
				// Cleaning links
				if (Tools::getValue($this->table.'Orderby') && Tools::getValue($this->table.'Orderway'))
					$currentIndex = preg_replace('/&'.$this->table.'Orderby=([a-z _]*)&'.$this->table.'Orderway=([a-z]*)/i', '', $currentIndex);
				echo '	<br />
						<a href="'.$currentIndex.'&'.$this->identifier.'='.$id_cat.'&'.$this->table.'Orderby='.urlencode($key).'&'.$this->table.'Orderway=desc&token='.$token.'"><img border="0" src="../img/admin/down'.((isset($this->_orderBy) AND ($key == $this->_orderBy) AND ($this->_orderWay == 'DESC')) ? '_d' : '').'.gif" /></a>
						<a href="'.$currentIndex.'&'.$this->identifier.'='.$id_cat.'&'.$this->table.'Orderby='.urlencode($key).'&'.$this->table.'Orderway=asc&token='.$token.'"><img border="0" src="../img/admin/up'.((isset($this->_orderBy) AND ($key == $this->_orderBy) AND ($this->_orderWay == 'ASC')) ? '_d' : '').'.gif" /></a>';
			} */
			echo '	</th>';
		}

		/* Check if object can be modified, deleted or detailed */
		if ($this->edit OR $this->delete OR ($this->view AND $this->view !== 'noActionColumn'))
			echo '	<th style="width: 52px">'.$this->l('Actions').'</th>';
		echo '	</tr>';
		/*echo '
				<tr class="nodrag nodrop" style="height: 35px;">
					<td class="center">';
		if ($this->delete)
			echo '		--';
		echo '		</td>';

		/* Javascript hack in order to catch ENTER keypress event */
		/* $keyPress = 'onkeypress="formSubmit(event, \'submitFilterButton_'.$this->table.'\');"';

		/* Filters (input, select, date or bool) */
		/*foreach ($this->fieldsDisplay AS $key => $params)
		{
			$width = (isset($params['width']) ? ' style="width: '.(int)($params['width']).'px;"' : '');
			echo '<td'.(isset($params['align']) ? ' class="'.$params['align'].'"' : '').'>';
			if (!isset($params['type']))
				$params['type'] = 'text';

			$value = Tools::getValue($this->table.'Filter_'.(array_key_exists('filter_key', $params) ? $params['filter_key'] : $key));
			if (isset($params['search']) AND !$params['search'])
			{
				echo '--</td>';
				continue;
			}
			switch ($params['type'])
			{
				case 'bool':
					echo '
					<select name="'.$this->table.'Filter_'.$key.'">
						<option value="">--</option>
						<option value="1"'.($value == 1 ? ' selected="selected"' : '').'>'.$this->l('Yes').'</option>
						<option value="0"'.(($value == 0 AND $value != '') ? ' selected="selected"' : '').'>'.$this->l('No').'</option>
					</select>';
					break;

				case 'date':
				case 'datetime':
					if (is_string($value))
						$value = unserialize($value);
					if (!Validate::isCleanHtml($value[0]) OR !Validate::isCleanHtml($value[1]))
						$value = '';
					$name = $this->table.'Filter_'.(isset($params['filter_key']) ? $params['filter_key'] : $key);
					$nameId = str_replace('!', '__', $name);
					includeDatepicker(array($nameId.'_0', $nameId.'_1'));
					echo $this->l('From').' <input type="text" id="'.$nameId.'_0" name="'.$name.'[0]" value="'.(isset($value[0]) ? $value[0] : '').'"'.$width.' '.$keyPress.' /><br />
					'.$this->l('To').' <input type="text" id="'.$nameId.'_1" name="'.$name.'[1]" value="'.(isset($value[1]) ? $value[1] : '').'"'.$width.' '.$keyPress.' />';
					break;

				case 'select':

					if (isset($params['filter_key']))
					{
						echo '<select onchange="$(\'#submitFilter'.$this->table.'\').focus();$(\'#submitFilter'.$this->table.'\').click();" name="'.$this->table.'Filter_'.$params['filter_key'].'" '.(isset($params['width']) ? 'style="width: '.$params['width'].'px"' : '').'>
								<option value=""'.(($value == 0 AND $value != '') ? ' selected="selected"' : '').'>--</option>';
						if (isset($params['select']) AND is_array($params['select']))
							foreach ($params['select'] AS $optionValue => $optionDisplay)
							{
								echo '<option value="'.$optionValue.'"'.((isset($_POST[$this->table.'Filter_'.$params['filter_key']]) AND Tools::getValue($this->table.'Filter_'.$params['filter_key']) == $optionValue AND Tools::getValue($this->table.'Filter_'.$params['filter_key']) != '') ? ' selected="selected"' : '').'>'.$optionDisplay.'</option>';
								}
						echo '</select>';
						break;
					}

				case 'text':
				default:
					if (!Validate::isCleanHtml($value))
							$value = '';
					echo '<input type="text" name="'.$this->table.'Filter_'.(isset($params['filter_key']) ? $params['filter_key'] : $key).'" value="'.htmlentities($value, ENT_COMPAT, 'UTF-8').'"'.$width.' '.$keyPress.' />';
			}
			echo '</td>';
		}

		if ($this->edit OR $this->delete OR ($this->view AND $this->view !== 'noActionColumn'))
			echo '<td class="center">--</td>';

		echo '</tr>'; */
			echo'</thead>';
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