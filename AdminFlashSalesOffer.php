<?php
include_once _PS_MODULE_DIR_ . 'flashsales/backend/classes/FlashSalesOffer.php';

class AdminFlashSalesOffer extends AdminTab
{
	private $_module = 'flashsales';
	
	public function __construct()
	{
		$this->table = 'flashsales_offer';
		$this->className	= 'FlashSalesOffer';
		$this->identifier = 'id_' . strtolower($this->table);

		$this->lang			 = true;
		$this->add			 = true;
		$this->edit			 = true;
		$this->delete		 = true;
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
		$this->_where		 = 'AND a.`date_end` > CURRENT_DATE()';
		//$this->_group		 = '';
		//$this->_having	 = '';
		//$this->_filter	 = '';
		//$this->_orderBy	 = '';
		//$this->_orderWay = 'ASC';

		parent::__construct();
	}

	public function displayForm($token = NULL)
	{
		global $currentIndex, $link, $cookie;
		parent::displayForm();

		if (!($obj = $this->loadObject(true)))
			return;
		$active = $this->getFieldValue($obj, 'active');
		$id_lang = (int)$cookie->id_lang;
		$all_products = FlashSalesOffer::getAllProducts($id_lang, 0, 'ALL', 'id_product', 'ASC', $obj->id);
		$all_images =  FlashSalesOffer::getAllImages($id_lang, 0, 'ALL', 'id_product', 'ASC', $obj->id);
		$currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
		echo '<link rel="stylesheet" href="../modules/' . strtolower($this->_module) . '/backend/css/' . strtolower($this->_module) . '.backend.admin.style.css">';
		echo '<script src="../modules/' . strtolower($this->_module) . '/backend/js/' . strtolower($this->_module) . '.backend.admin.script.js"></script>';
		echo '<script type="text/javascript">';
		echo '	Prestashop.flashsales.backend.nb_images = ' . Configuration::get('FS_NB_PICTURES') . ';';
		echo '	Prestashop.flashsales.backend.too_much_images = "' . $this->l('You can only select') . ' ' . Configuration::get('FS_NB_PICTURES') . ' ' . $this->l('images') . '";';
		echo '	Prestashop.flashsales.backend.too_less_images = "' . $this->l('You have to select') . ' ' . Configuration::get('FS_NB_PICTURES') . ' ' . $this->l('images') . '";';
		echo '</script>';
		echo '
		<form action="'.$currentIndex.'&submitAdd'.$this->table.'=1&token='.($token!=NULL ? $token : $this->token).'" method="post" enctype="multipart/form-data">
		'.($obj->id ? '<input type="hidden" name="id_'.$this->table.'" value="'.$obj->id.'" />' : '').'
			<fieldset><legend><img src="../img/admin/tab-categories.gif" />'.$this->l('Flash sales offer').'</legend>
				<label>'.$this->l('Name:').' </label>
				<div class="margin-form translatable">';
		foreach ($this->_languages as $language)
			echo '
					<div class="lang_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultFormLanguage ? 'block' : 'none').'; float: left;">
						<input type="text" style="width: 260px" name="name_'.$language['id_lang'].'" id="name_'.$language['id_lang'].'" value="'.htmlentities($this->getFieldValue($obj, 'name', (int)($language['id_lang'])), ENT_COMPAT, 'UTF-8').'" '.((!$obj->id) ? ' onkeyup="copy2friendlyURL();"' : '').' /><sup> *</sup>
						<span class="hint" name="help_box">'.$this->l('Invalid characters:').' <>;=#{}<span class="hint-pointer">&nbsp;</span></span>
					</div>';
		echo '<p class="clear"></p>
				</div>';
			echo '<label>'.$this->l('Flash sales category:').' </label>
					<div class="margin-form">
						<select name="id_flashsales_category">';
			$categories = FlashSalesCategory::getCategories((int)($cookie->id_lang), false);
			FlashSalesCategory::recurseFlashSalesCategory($categories, $categories[0][1], 1, $this->getFieldValue($obj, 'id_flashsales_category'));
			echo '
						</select>
					</div>';
		// VIDEO
		if(Configuration::get('FS_USE_VIDEO'))
		{
			echo '<label>'.$this->l('Youtube video code:').' </label>
						<div class="margin-form">
							<input type="text" name="video" id="video" value="'.$this->getFieldValue($obj, 'video').'" />
							<span class="hint" name="help_box">'.$this->l('Enter the Youtube video code:').' G231a7b8RVAAAAAAAABg<span class="hint-pointer">&nbsp;</span></span>
						</div>';
			echo '<label>'.$this->l('Forward video:').' </label>
					<div class="margin-form">
						<input type="radio" name="video_forward" id="video_forward_on" value="1" '.($this->getFieldValue($obj, 'video_forward') ? 'checked="checked" ' : '').'/>
						<label class="t" for="video_forward_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
						<input type="radio" name="video_forward" id="video_forward_off" value="0" '.(!$this->getFieldValue($obj, 'video_forward') ? 'checked="checked" ' : '').'/>
						<label class="t" for="video_forward_off"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
					</div>';
		}
		// DATE START
		includeDatepicker(array('date_start'), false);
		echo '		
				<label>'.$this->l('Start:').' </label>
				<div class="margin-form">
					<input type="text" size="20" id="date_start" name="date_start" value="'.($this->getFieldValue($obj, 'date_start') ? htmlentities($this->getFieldValue($obj, 'date_start'), ENT_COMPAT, 'UTF-8') : date('Y-m-d', Configuration::get('FS_NEXT_PERIOD'))).'" /> <sup>*</sup>
					<p class="clear">'.$this->l('Start date from which offer can be displayed').'<br />'.$this->l('Format: YYYY-MM-DD').'</p>
				</div>';
				
		// SUMMARY
		echo '<label>'.$this->l('Summary:').' </label>
					<div class="margin-form translatable">';
		foreach ($this->_languages as $language)
		echo '
						<div class="lang_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultFormLanguage ? 'block' : 'none').'; float: left;">
							<textarea name="description_short_'.$language['id_lang'].'" rows="5" cols="50">'.htmlentities($this->getFieldValue($obj, 'description_short', (int)($language['id_lang'])), ENT_COMPAT, 'UTF-8').'</textarea><sup> *</sup>
						</div>';
		echo '	<p class="clear"></p>
					</div>';
		// DESCRIPTION
		echo '<label>'.$this->l('Description:').' </label>
					<div class="margin-form translatable">';
		foreach ($this->_languages as $language)
		echo '
						<div class="lang_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultFormLanguage ? 'block' : 'none').'; float: left;">
							<textarea name="description_'.$language['id_lang'].'" rows="10" cols="50">'.htmlentities($this->getFieldValue($obj, 'description', (int)($language['id_lang'])), ENT_COMPAT, 'UTF-8').'</textarea><sup> *</sup>
						</div>';
		echo '	<p class="clear"></p>
					</div>';
		
		echo '<fieldset style="font-size: 1em">
						<legend><img src="../img/admin/cart.gif">'. $this->l('Products') .'</legend>';
		echo '	<table>
							<tbody>
								<tr></tr>
								<tr>
									<td>
										<table id="flashsales_product" class="table tableDnD" cellpadding="0" cellspacing="0">
											<thead>
												<tr class="nodrag nodrop">
													<th>
														<input type="checkbox" name="checkme" class="noborder" onclick="checkDelBoxes(this.form, \'flashsales_productBox[]\', this.checked)">
													</th>
													<th>'.$this->l('ID') .'</th>
													<th>'.$this->l('Picture').'</th>
													<th width="540">'.$this->l('Name').'</th>
													<th>'.$this->l('Price').'</th>
													<th>'.$this->l('Quantity').'</th>
													<th>'.$this->l('Status').'</th>
												</tr>
											</thead>
											<tbody>';
											foreach($all_products AS $k => $product)
											{
												// Image
												$image = Db::getInstance()->getRow('
													SELECT id_image
													FROM '._DB_PREFIX_.'image
													WHERE id_product = '.(int)($product['id_product']).' AND cover = 1'
												);
												if (isset($image['id_image']))
												{
													$target = _PS_TMP_IMG_DIR_.'product_mini_'.(int)($product['id_product']).(isset($product['product_attribute_id']) ? '_'.(int)($product['product_attribute_id']) : '').'.jpg';
													if (file_exists($target))
														$products[$k]['image_size'] = getimagesize($target);
													$imageObj = new Image($image['id_image']);
												}
												
												echo '<tr'.((isset($image['id_image']) AND isset($products[$k]['image_size'])) ? ' height="'.($products[$k]['image_size'][1] + 7).'"' : '').'>';
												echo '	<td class="center">';
												if ($this->delete AND (!isset($this->_listSkipDelete) OR !in_array($id, $this->_listSkipDelete)))
												echo '		<input type="checkbox" name="flashsales_productBox[]" class="flashsales_productBox" value="'.$product['id_product'].'" class="noborder" '. (isset($product['flashsales_checked']) && $product['flashsales_checked']  ? 'checked="checked"' : '' ) .' />';
												echo '</td>';
												echo '	<td>' . $product['id_product'] . '</td>';
												echo '<td align="center">'.(isset($image['id_image']) ? cacheImage(_PS_IMG_DIR_.'p/'.$imageObj->getExistingImgPath().'.jpg', 'product_mini_flashsales_'.(int)($product['id_product']).(isset($product['id_product_attribute']) ? '_'.(int)($product['id_product_attribute']) : '').'.jpg', 80, 'jpg') : '--').'</td>';
												echo '<td>'.$product['name'].'</td>';
												echo '<td>' . Tools::displayPrice($product['price'], $currency, false) . '</td>';
												echo '<td>'.$product['quantity'].'</td>';
												echo '<td><img src="../img/admin/'.($product['active'] ? 'enabled.gif' : 'disabled.gif').'"</td>';
												echo '</tr>';
											}
		echo '						</tbody>
										</table>
									<td>
								</tr>
							</tbody>
						</table>';
		echo '</fieldset>';
		echo '	<p class="clear"></p>';
		// OFFER IMAGES
		echo '<fieldset style="font-size: 1em">
						<legend><img src="../img/admin/picture.gif">'. $this->l('Offer images') .'</legend>
						<p>'. $this->l('Select') . ' ' . Configuration::get('FS_NB_PICTURES', 3) . ' ' . $this->l('images you want to display in your offer.').'</p>
						<ul id="offer_images_container">';
						if($all_images)
						{
							foreach($all_images AS $image)
							{
								$imageObj = new Image($image['id_image']);
								echo '<li class="flashsales_offer_image" id="flashsales_offer_image_' . $image['id_product'] . '">';
								echo cacheImage(_PS_IMG_DIR_.'p/'.$imageObj->getExistingImgPath().'.jpg', 'product_mini_flashsales_'.(int)($image['id_product']).(isset($image['id_product_attribute']) ? '_'.(int)($image['id_product_attribute']) : '').'.jpg', 80, 'jpg');
								echo '<input type="checkbox" class="checkbox_offer_image" name="flashsales_offer_image[]" value="' . $image['id_image'] . '" '. (isset($image['checked']) && $image['checked'] ? 'checked="checked"' : '') .' />';
								echo '</li>';
								
							}
						}
		echo 		'</ul>';
		echo '</fieldset>';
		echo '	<p class="clear"></p>';
		// SEO
		echo '<fieldset style="font-size: 1em">
						<legend><img src="../img/admin/metatags.gif"> '.$this->l('SEO').'</legend>';
		echo '<label>'.$this->l('Meta title:').' </label>
			<div class="margin-form translatable">';
	foreach ($this->_languages as $language)
		echo '
				<div class="lang_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultFormLanguage ? 'block' : 'none').'; float: left;">
					<input type="text" name="meta_title_'.$language['id_lang'].'" id="meta_title_'.$language['id_lang'].'" value="'.htmlentities($this->getFieldValue($obj, 'meta_title', (int)($language['id_lang'])), ENT_COMPAT, 'UTF-8').'" />
					<span class="hint" name="help_box">'.$this->l('Forbidden characters:').' <>;=#{}<span class="hint-pointer">&nbsp;</span></span>
				</div>';
	echo '	<p class="clear"></p>
			</div>
			<label>'.$this->l('Meta description:').' </label>
			<div class="margin-form translatable">';
	foreach ($this->_languages as $language)
		echo '<div class="lang_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultFormLanguage ? 'block' : 'none').'; float: left;">
					<input type="text" name="meta_description_'.$language['id_lang'].'" id="meta_description_'.$language['id_lang'].'" value="'.htmlentities($this->getFieldValue($obj, 'meta_description', (int)($language['id_lang'])), ENT_COMPAT, 'UTF-8').'" />
					<span class="hint" name="help_box">'.$this->l('Forbidden characters:').' <>;=#{}<span class="hint-pointer">&nbsp;</span></span>
			</div>';
	echo '	<p class="clear"></p>
			</div>
			<label>'.$this->l('Meta keywords:').' </label>
			<div class="margin-form translatable">';
	foreach ($this->_languages as $language)
		echo '
				<div class="lang_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultFormLanguage ? 'block' : 'none').'; float: left;">
					<input type="text" name="meta_keywords_'.$language['id_lang'].'" id="meta_keywords_'.$language['id_lang'].'" value="'.htmlentities($this->getFieldValue($obj, 'meta_keywords', (int)($language['id_lang'])), ENT_COMPAT, 'UTF-8').'" />
					<span class="hint" name="help_box">'.$this->l('Forbidden characters:').' <>;=#{}<span class="hint-pointer">&nbsp;</span></span>
				</div>';
	echo '	<p class="clear"></p>
			</div>
			<label>'.$this->l('Friendly URL:').' </label>
			<div class="margin-form translatable">';
	foreach ($this->_languages as $language)
		echo '<div class="lang_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultFormLanguage ? 'block' : 'none').'; float: left;">
					<input type="text" name="link_rewrite_'.$language['id_lang'].'" id="link_rewrite_'.$language['id_lang'].'" value="'.htmlentities($this->getFieldValue($obj, 'link_rewrite', (int)($language['id_lang'])), ENT_COMPAT, 'UTF-8').'" onkeyup="this.value = str2url(this.value);" /><sup> *</sup>
					<span class="hint" name="help_box">'.$this->l('Only letters and the minus (-) character are allowed').'<span class="hint-pointer">&nbsp;</span></span>
				</div>';
	echo '	<p class="clear"></p>
			</div>';
	echo '</fieldset>';
	echo '	<p class="clear"></p>';
	echo '<label>'.$this->l('Displayed:').' </label>
			<div class="margin-form">
				<input type="radio" name="active" id="active_on" value="1" '.($active ? 'checked="checked" ' : '').'/>
				<label class="t" for="active_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				<input type="radio" name="active" id="active_off" value="0" '.(!$active ? 'checked="checked" ' : '').'/>
				<label class="t" for="active_off"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
			</div>
			<div class="center">
				<input type="submit" class="button" name="submitAdd'.$this->table.'" value="'.$this->l('Save').'"/>
			</div>
			<div class="small"><sup>*</sup> '.$this->l('Required field').'</div>';
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
		echo '<a href="'.$currentIndex.'&id_flashsales_category='.$id_flashsales_category.'&add'.$this->table.'&token='.Tools::getAdminTokenLite('AdminFlashSalesContent').'"><img src="../img/admin/add.gif" border="0" /> '.$this->l('Add a new flash sales').'</a>';
		echo'<div style="margin:10px;">';
		$this->displayList($token);
		echo '</div>';
	}
	
	public function postProcess()
	{
		global $cookie, $link, $currentIndex;

		// ADD / EDIT
		if(Tools::isSubmit('submitAddflashsales_offer'))
		{
			//die(print_r($_POST));
			parent::validateRules();

			$id_flashsales_offer = (int)(Tools::getValue('id_flashsales_offer'));

			if(!isset($_POST['flashsales_productBox']) || empty($_POST['flashsales_productBox']))
				$this->_errors[] = Tools::displayError('You need to select products which you want in your offer');

			if(!isset($_POST['flashsales_offer_image']) || empty($_POST['flashsales_offer_image']))
				$this->_errors[] = Tools::displayError('You need to select the default picture of the offer');
			elseif(isset($_POST['flashsales_offer_image']) && !empty($_POST['flashsales_offer_image']) && count($_POST['flashsales_offer_image']) != Configuration::get('FS_NB_PICTURES'))
				$this->_errors[] = Tools::displayError('You have to select') . ' ' . Configuration::get('FS_NB_PICTURES') . ' ' . Tools::displayError('images');
			elseif(strtotime(Tools::getValue('date_start')) < strtotime(date('Y-m-d')) + Configuration::get('FS_TIME_BETWEEN_PERIOD'))
				$this->_errors[] = Tools::displayError('The date cannot be set to today or previous time');
			elseif(FlashSalesOffer::getNumberOffersForTheDay(Tools::getValue('date_start') >= Configuration::get('FS_NB_OFFERS')) && !$id_flashsales_offer)
				$this->_errors[] = Tools::displayError('You cannot add offer for this date, there are too much (max:') . ' ' . Configuration::get('FS_NB_OFFERS') . ')';

			if (!sizeof($this->_errors))
			{
				// ADD NEW ONE
				if (!$id_flashsales_offer)
				{
					$flashsales_offer = new FlashSalesOffer();
					$this->copyFromPost($flashsales_offer, 'flashsales_offer');
					// Date end
					$flashsales_offer->date_end = date('Y-m-d', strtotime(Tools::getValue('date_start')) + Configuration::get('FS_TIME_BETWEEN_PERIOD'));
					if (!$flashsales_offer->add())
						$this->_errors[] = Tools::displayError('An error occurred while creating object.').' <b>'.$this->table.' ('.mysql_error().')</b>';
					else
					{
						// Offer products
						$flashsales_offer_products = Tools::getValue('flashsales_productBox');
						$products = '';
						foreach($flashsales_offer_products AS $k => $id_product)
						{
							$products .= "('" . $flashsales_offer->id . "', '" . $id_product . "', NOW(), NOW())";
							if($k + 1 != count($flashsales_offer_products))
								$products .= ', ';
						}

						// Offer images
						$flashsales_offer_images = Tools::getValue('flashsales_offer_image');
						$images = '';
						foreach($flashsales_offer_images AS $k => $id_image)
						{
							if($k <= Configuration::get('FS_NB_PICTURES') - 1)
							{
								// Prepare query
								$images .= "('" . $flashsales_offer->id . "', '" . $id_image . "', '" . $k . "', NOW(), NOW())";
								if($k + 1 != count($flashsales_offer_images))
									$images .= ', ';
							}
						}

						if(!Db::getInstance()->Execute("INSERT INTO  `" . _DB_PREFIX_ . "flashsales_product` VALUES " . $products))
							$this->_errors[] = Tools::displayError('An error occurred while insert offer products.');
						elseif(!Db::getInstance()->Execute("INSERT INTO  `" . _DB_PREFIX_ . "flashsales_offer_image` VALUES " . $images))
							$this->_errors[] = Tools::displayError('An error occurred while insert offer images.');
						else
							Tools::redirectAdmin($currentIndex.'&id_flashsales_category='.$flashsales_offer->id_flashsales_category.'&conf=3&token='.Tools::getAdminTokenLite('AdminFlashSalesContent'));
					}
				}
				// EDIT
				else
				{
					$flashsales_offer = new FlashSalesOffer($id_flashsales_offer);
					$this->copyFromPost($flashsales_offer, 'flashsales_offer');
					$flashsales_offer->date_end = date('Y-m-d', strtotime(Tools::getValue('date_start')) + Configuration::get('FS_TIME_BETWEEN_PERIOD'));
					if (!$flashsales_offer->update())
						$this->_errors[] = Tools::displayError('An error occurred while updating object.').' <b>'.$this->table.' ('.mysql_error().')</b>';
					else
					{
						// Offer products
						$flashsales_offer_products = Tools::getValue('flashsales_productBox');
						$products = '';
						foreach($flashsales_offer_products AS $k => $id_product)
						{
							$products .= "('" . $flashsales_offer->id . "', '" . $id_product . "', NOW(), NOW())";
							if($k + 1 != count($flashsales_offer_products))
								$products .= ', ';
						}

						// Offer images
						$flashsales_offer_images = Tools::getValue('flashsales_offer_image');
						$images = '';
						foreach($flashsales_offer_images AS $k => $id_image)
						{
							if($k <= Configuration::get('FS_NB_PICTURES') - 1)
							{
								// Prepare query
								$images .= "('" . $flashsales_offer->id . "', '" . $id_image . "', '" . $k . "', NOW(), NOW())";
								if($k + 1 != count($flashsales_offer_images))
									$images .= ', ';
							}
						}
						if(!Db::getInstance()->Execute("DELETE FROM `" . _DB_PREFIX_ . "flashsales_product` WHERE `id_flashsales_offer` = " . $flashsales_offer->id))
							$this->_errors[] = Tools::displayError('An error occurred while deleting offer products.');
						elseif(!Db::getInstance()->Execute("DELETE FROM `" . _DB_PREFIX_ . "flashsales_offer_image` WHERE `id_flashsales_offer` = " . $flashsales_offer->id))
							$this->_errors[] = Tools::displayError('An error occurred while deleting offer images.');
						elseif(!Db::getInstance()->Execute("INSERT INTO  `" . _DB_PREFIX_ . "flashsales_product` VALUES " . $products))
							$this->_errors[] = Tools::displayError('An error occurred while inserting offer products.');
						elseif(!Db::getInstance()->Execute("INSERT INTO  `" . _DB_PREFIX_ . "flashsales_offer_image` VALUES " . $images))
							$this->_errors[] = Tools::displayError('An error occurred while inserting offer images.');
						else
							Tools::redirectAdmin($currentIndex.'&id_flashsales_category='.$flashsales_offer->id_flashsales_category.'&conf=4&token='.Tools::getAdminTokenLite('AdminFlashSalesContent'));
					}
				}
			}
		}
		// DELETE
		elseif (Tools::isSubmit('deleteflashsales_offer'))
		{
			$flashsales_offer = new FlashSalesOffer((int)(Tools::getValue('id_flashsales_offer')));
			$flashsales_offer->cleanPositions($flashsales_offer->id_flashsales_category);
			if (!$flashsales_offer->delete())
				$this->_errors[] = Tools::displayError('An error occurred while deleting object.').' <b>'.$this->table.' ('.mysql_error().')</b>';
			else
			{
				if(!Db::getInstance()->Execute("DELETE FROM `" . _DB_PREFIX_ . "flashsales_product` WHERE `id_flashsales_offer` = " . $flashsales_offer->id))
					$this->_errors[] = Tools::displayError('An error occurred while deleting offer products.');
				elseif(!Db::getInstance()->Execute("DELETE FROM `" . _DB_PREFIX_ . "flashsales_offer_image` WHERE `id_flashsales_offer` = " . $flashsales_offer->id))
					$this->_errors[] = Tools::displayError('An error occurred while deleting offer images.');
				else
					Tools::redirectAdmin($currentIndex.'&id_flashsales_category='.$flashsales_offer->id_flashsales_category.'&conf=1&token='.Tools::getAdminTokenLite('AdminFlashSalesContent'));
			}
		}
		// DELETE MULTIPLE
		elseif (Tools::getValue('submitDel'.$this->table))
		{
			if ($this->tabAccess['delete'] === '1')
			{
				if (isset($_POST[$this->table.'Box']))
				{
					$flashsales_offer = new FlashSalesOffer();
					$result = true;
					$result = $flashsales_offer->deleteSelection(Tools::getValue($this->table.'Box'));
					if ($result)
					{
						foreach(Tools::getValue($this->table.'Box') AS $id)
						{
							if(!Db::getInstance()->Execute("DELETE FROM `" . _DB_PREFIX_ . "flashsales_product` WHERE `id_flashsales_offer` = " . $id))
								$this->_errors[] = Tools::displayError('An error occurred while deleting offer products.');
							elseif(!Db::getInstance()->Execute("DELETE FROM `" . _DB_PREFIX_ . "flashsales_offer_image` WHERE `id_flashsales_offer` = " . $id))
								$this->_errors[] = Tools::displayError('An error occurred while deleting offer images.');
						}

						$flashsales_offer->cleanPositions((int)(Tools::getValue('id_flashsales_category')));
						Tools::redirectAdmin($currentIndex.'&conf=2&token='.Tools::getAdminTokenLite('AdminFlashSalesContent').'&id_flashsales_category='.(int)(Tools::getValue('id_flashsales_category')));
					}
					$this->_errors[] = Tools::displayError('An error occurred while deleting selection.');

				}
				else
					$this->_errors[] = Tools::displayError('You must select at least one element to delete.');
			}
			else
				$this->_errors[] = Tools::displayError('You do not have permission to delete here.');
		}
		// POSITION
		elseif (Tools::getValue('position'))
		{
			if ($this->tabAccess['edit'] !== '1')
				$this->_errors[] = Tools::displayError('You do not have permission to edit here.');
			elseif (!Validate::isLoadedObject($object = $this->loadObject()))
				$this->_errors[] = Tools::displayError('An error occurred while updating status for object.').' <b>'.$this->table.'</b> '.Tools::displayError('(cannot load object)');
			elseif (!$object->updatePosition((int)(Tools::getValue('way')), (int)(Tools::getValue('position'))))
				$this->_errors[] = Tools::displayError('Failed to update the position.');
			else
				Tools::redirectAdmin($currentIndex.'&'.$this->table.'Orderby=position&'.$this->table.'Orderway=asc&conf=4'.(($id_category = (int)(Tools::getValue('id_flashsales_category'))) ? ('&id_flashsales_category='.$id_category) : '').'&token='.Tools::getAdminTokenLite('AdminFlashSalesContent'));
		}
	}
}
?>