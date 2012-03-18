<?php
if (!defined('_PS_VERSION_'))
	exit;

include_once _PS_MODULE_DIR_ . 'flashsales/backend/classes/FlashSalesOffer.php';

class FlashSales extends Module
{
	public static $cacheDirs;
	public static $cacheFiles;
	public static $moduleName = 'flashsales';

	public function __construct()
	{
		$this->name		 = 'flashsales';
		$this->tab		 = 'front_office_features';
		$this->version = '1.0';
		$this->author	 = 'Pierrick CAEN';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Flash sales');
		$this->description = $this->l('Manage flash sales');

		$this->_tplFile				 = _PS_MODULE_DIR_ . $this->name . '/backend/tpl/' . $this->name . '.backend.configure.tpl';
		$this->_adminClassName = 'AdminFlashSalesContent';
		$this->_idTabParent		= Tab::getIdFromClassName('AdminCatalog');
		$this->_adminTabName	 = array(
			1 => 'Flash sales',
			2 => 'Ventes flash',
			3 => 'Flash sales',
			4 => 'Flash sales',
			5 => 'Flash sales',
		);

		self::$cacheDirs = array(
			_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'tools'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'compile',
			_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'tools'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'cache',
			_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'tools'.DIRECTORY_SEPARATOR.'smarty_v2'.DIRECTORY_SEPARATOR.'cache',
			_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'tools'.DIRECTORY_SEPARATOR.'smarty_v2'.DIRECTORY_SEPARATOR.'cache',
			_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'tools'.DIRECTORY_SEPARATOR.'cache',
			_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'tmp'
		);
		
		self::$cacheFiles = array(
			self::$moduleName . '_home'
		);
		
		$this->_controllers = array(
			1 => array(
				'root_file' => 'flashsalesoffer.php',
				'controller' => 'FlashSalesOfferController.php',
				'tpl_file' => 'flashsalesoffer.tpl'
			),
			2 => array(
				'root_file' => 'flashsalesofferold.php',
				'controller' => 'FlashSalesOfferOldController.php',
				'tpl_file' => 'flashsalesofferold.tpl'
			)
		);

		$this->_tables = array(
			'flashsales_offer' => array(
				'name' => 'flashsales_offer',
				'fields' => array(
					0 => array(
						'name' 			=> 'id_flashsales_category',
						'type' 			=> 'INT',
						'size' 			=> 10,
						'unsigned' 	=> true,
						'null' 			=> false
					),
					1 => array(
						'name' 			=> 'default',
						'type' 			=> 'BOOLEAN',
						'size' 			=> false,
						'unsigned' 	=> false,
						'null' 			=> true
					),
					2 => array(
						'name' 			=> 'active',
						'type' 			=> 'BOOLEAN',
						'size' 			=> false,
						'unsigned' 	=> false,
						'null' 			=> false
					),
					3 => array(
						'name' 			=> 'position',
						'type' 			=> 'INT',
						'size' 			=> 10,
						'unsigned' 	=> true,
						'null' 			=> false
					),
					4 => array(
						'name' 			=> 'video',
						'type' 			=> 'VARCHAR',
						'size' 			=> 11,
						'unsigned' 	=> false,
						'null' 			=> true
					),
					5 => array(
						'name' 			=> 'video_forward',
						'type' 			=> 'BOOLEAN',
						'size' 			=> false,
						'unsigned' 	=> false,
						'null' 			=> false
					),
					6 => array(
						'name' 			=> 'date_start',
						'type' 			=> 'DATE',
						'size' 			=> false,
						'unsigned' 	=> false,
						'null' 			=> false
					),
					7 => array(
						'name' 			=> 'date_end',
						'type' 			=> 'DATE',
						'size' 			=> false,
						'unsigned' 	=> false,
						'null' 			=> false
					)
				),
				'fields_translations' => array(
					0 => array(
						'name' 			=> 'name',
						'type' 			=> 'VARCHAR',
						'size' 			=> 128,
						'unsigned' 	=> false,
						'null' 			=> false
					),
					1 => array(
						'name' 			=> 'description',
						'type' 			=> 'TEXT',
						'size' 			=> false,
						'unsigned' 	=> false,
						'null' 			=> false
					),
					2 => array(
						'name' 			=> 'description_short',
						'type' 			=> 'TEXT',
						'size' 			=> false,
						'unsigned' 	=> false,
						'null' 			=> false
					),
					3 => array(
						'name' 			=> 'link_rewrite',
						'type' 			=> 'VARCHAR',
						'size' 			=> 128,
						'unsigned' 	=> false,
						'null' 			=> false
					),
					4 => array(
						'name' 			=> 'meta_title',
						'type' 			=> 'VARCHAR',
						'size' 			=> 128,
						'unsigned' 	=> false,
						'null' 			=> true
					),
					5 => array(
						'name' 			=> 'meta_keywords',
						'type' 			=> 'VARCHAR',
						'size' 			=> 255,
						'unsigned' 	=> false,
						'null' 			=> true
					),
					6 => array(
						'name' 			=> 'meta_description',
						'type' 			=> 'VARCHAR',
						'size' 			=> 255,
						'unsigned' 	=> false,
						'null' 			=> true
					)
				),
				'identifiers' => false
			),
			'flashsales_category' => array(
				'name' => 'flashsales_category',
				'fields' => array(
					0 => array(
						'name' 			=> 'id_parent',
						'type' 			=> 'INT',
						'size' 			=>	10,
						'unsigned' 	=> true,
						'null' 			=> false
					),
					1 => array(
						'name' 			=> 'level_depth',
						'type' 			=> 'TINYINT',
						'size' 			=> 3,
						'unsigned' 	=> true,
						'null' 			=> false
					),
					2 => array(
						'name' 			=> 'active',
						'type' 			=> 'BOOLEAN',
						'size' 			=> false,
						'unsigned' 	=> false,
						'null' 			=> false
					),
					3 => array(
						'name' 			=> 'position',
						'type' 			=> 'INT',
						'size' 			=> 10,
						'unsigned' 	=> false,
						'null' 			=> false
					)
				),
				'fields_translations' => array(
					0 => array(
						'name' 			=> 'name',
						'type' 			=> 'VARCHAR',
						'size' 			=> 128,
						'unsigned' 	=> false,
						'null' 			=> false
					),
					1 => array(
						'name' 			=> 'description',
						'type' 			=> 'TEXT',
						'size' 			=> false,
						'unsigned' 	=> false,
						'null' 			=> true
					),
					2 => array(
						'name' 			=> 'link_rewrite',
						'type' 			=> 'VARCHAR',
						'size' 			=> 128,
						'unsigned' 	=> false,
						'null' 			=> false
					),
					3 => array(
						'name' 			=> 'meta_title',
						'type' 			=> 'VARCHAR',
						'size' 			=> 128,
						'unsigned' 	=> false,
						'null' 			=> true
					),
					4 => array(
						'name' 			=> 'meta_keywords',
						'type' 			=> 'VARCHAR',
						'size' 			=> 255,
						'unsigned' 	=> false,
						'null' 			=> true
					),
					5 => array(
						'name' 			=> 'meta_description',
						'type' 			=> 'VARCHAR',
						'size' 			=> 255,
						'unsigned' 	=> false,
						'null' 			=> true
					)
				),
				'identifiers' => false
			),
			'flashsales_product' => array(
				'name' => 'flashsales_product',
				'fields' => array(
					0 => array(
						'name' 			=> 'id_flashsales_offer',
						'type' 			=> 'INT',
						'size' 			=>	10,
						'unsigned' 	=> true,
						'null' 			=> false
					),
					1 => array(
						'name' 			=> 'id_product',
						'type' 			=> 'INT',
						'size' 			=>	10,
						'unsigned' 	=> true,
						'null' 			=> false
					)
				),
				'identifiers' => array('id_flashsales_offer', 'id_product')
			),
			'flashsales_offer_image' => array(
				'name' => 'flashsales_offer_image',
				'fields' => array(
					0 => array(
						'name' 			=> 'id_flashsales_offer',
						'type' 			=> 'INT',
						'size' 			=>	10,
						'unsigned' 	=> true,
						'null' 			=> false
					),
					1 => array(
						'name' 			=> 'id_image',
						'type' 			=> 'INT',
						'size' 			=>	10,
						'unsigned' 	=> true,
						'null' 			=> false
					),
					2 => array(
						'name' 			=> 'position',
						'type' 			=> 'INT',
						'size' 			=> 10,
						'unsigned' 	=> false,
						'null' 			=> false
					)
				),
				'identifiers' => array('id_flashsales_offer', 'id_image')
			),
			'flashsales_offer_mailalert' => array(
				'name' => 'flashsales_offer_mailalert',
				'fields' => array(
					0 => array(
						'name' 			=> 'id_flashsales_offer_mailalert',
						'type' 			=> 'INT',
						'size' 			=>	10,
						'unsigned' 	=> true,
						'null' 			=> false
					),
					1 => array(
						'name' 			=> 'id_flashsales_offer',
						'type' 			=> 'INT',
						'size' 			=>	10,
						'unsigned' 	=> true,
						'null' 			=> false
					),
					2 => array(
						'name' 			=> 'id_customer',
						'type' 			=> 'INT',
						'size' 			=>	10,
						'unsigned' 	=> true,
						'null' 			=> true
					),
					3 => array(
						'name' 			=> 'customer_email',
						'type' 			=> 'VARCHAR',
						'size' 			=>	128,
						'unsigned' 	=> false,
						'null' 			=> false
					)
				)
			)
		);

		$this->_abbreviation = 'FS';
		$this->_debugView = true;
		$this->_configs = array(
			0 => array(
				'config_name'		=> $this->_abbreviation . '_NB_OFFERS',
				'name'			=> strtolower($this->name) . '_nb_offers',
				'title'		=> $this->l('Number of offers by period'),
				'type'		=> 'text', // boolean, text, radio, select, checkbox or false
				'validate' => 'isUnsignedId',
				'default' => 4,
				'help'		=> $this->l('provide a number of offers by period')
			),
			1 => array(
				'config_name'		=> $this->_abbreviation . '_NB_PICTURES',
				'name'			=> strtolower($this->name) . '_nb_pictures',
				'title'		=> $this->l('Number of pictures by offer to display'),
				'type'		=> 'text', // boolean, text, radio, select, checkbox or false
				'validate' => 'isUnsignedId',
				'default' => 3,
				'help'		=> $this->l('provide a number of pictures by offer to display')
			),
			2 => array(
				'config_name'		=> $this->_abbreviation . '_USE_VIDEO',
				'name'			=> strtolower($this->name) . '_use_video',
				'title'		=> $this->l('Use video'),
				'type'		=> 'boolean', // boolean, text, radio, select, checkbox or false
				'validate' => 'isBool',
				'default' => 1,
				'help'		=> $this->l('do you want to display video for an offer ?')
			),
			3 => array(
				'config_name'		=> $this->_abbreviation . '_TIME_BETWEEN_PERIOD',
				'name'			=> strtolower($this->name) . '_time_between_period',
				'title'		=> $this->l('Day(s) between each sales period'),
				'type'		=> 'select', // boolean, text, radio, select, checkbox or false
				'options' => array(
					1 => array(
						'name' => $this->l('1 day'),
						'value' => self::_daysToSeconds(1)
					),
					2 => array(
						'name'  => $this->l('2 days'),
						'value' => self::_daysToSeconds(2)
					),
					3 => array(
						'name'	=> $this->l('3 days'),
						'value' => self::_daysToSeconds(3)
					),
					7 => array(
						'name'	=> $this->l('7 days'),
						'value' => self::_daysToSeconds(7)
					)
				),
				'validate' => 'isUnsignedId',
				'default' => self::_daysToSeconds(1)
			),
			4 => array(
				'config_name'		=> $this->_abbreviation . '_TIME_START_DAY',
				'name'			=> strtolower($this->name) . '_time_start_day',
				'title'		=> $this->l('Time start each day'),
				'type'		=> 'time', // boolean, text, radio, select, checkbox or false
				'validate' => 'isUnsignedId',
				'default' => 36000,
				'help'		=> $this->l('set time start')
			),
			5 => array(
				'config_name'		=> $this->_abbreviation . '_NEXT_PERIOD',
				'name'			=> strtolower($this->name) . '_next_period',
				'type'		=> false, // boolean, text, radio, select, checkbox or false
				'default' => strtotime('midnight') + self::_daysToSeconds(1) + 36000
			),
			6 => array(
				'config_name' => $this->_abbreviation . '_SECURE_KEY',
				'name' => strtolower($this->name) . '_secure_key',
				'type'	=> false,
				'default' => strtoupper(Tools::passwdGen(16))
			),
			7 => array(
				'config_name' => $this->_abbreviation . '_CACHE_ID',
				'name' => strtolower($this->name) . '_cache_id',
				'type'	=> false,
				'default' => strtoupper(Tools::passwdGen(10))
			)
		);

		$this->_hooks = array(
			1 => array(
				'name'	=> 'header',
				'insert' => false
			),
			2 => array(
				'name'	=> 'homeFlashSales',
				'title' => 'Home flash sales',
				'description' => '',
				'insert' => true
			)
		);
	}

	public function getContent()
	{
		$output	 = '';
		$output .= $this->_postProcess();

		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		global $smarty;

		foreach($this->_configs as $key => &$config)
		{
			if(!$config['type'])
				unset($this->_configs[$key]);
			else
				$config['value'] = Configuration::get($config['config_name']);
		}

		$smarty->assign('action', Tools::safeOutput($_SERVER['REQUEST_URI']));
		$smarty->assign('display_name', $this->displayName);
		$smarty->assign('module_name', strtolower($this->name));
		$smarty->assign('module_dir', $this->_path);
		$smarty->assign('configs', $this->_configs);
		$smarty->assign('cron_url', Tools::getShopDomain(true, true).__PS_BASE_URI__.'modules/flashsales/cron.php?secure_key='.Configuration::get('FS_SECURE_KEY'));

		$smarty->register_function('twoDigits', array('Flashsales', 'twoDigitsSmarty'));
		$smarty->register_function('secondsToMinutes', array('Flashsales', 'secondsToMinutesSmarty'));

		$cache_id = $compile_id = ($this->_debugView ? Tools::passwdGen(8) : null);
		return $smarty->fetch($this->_tplFile, $cache_id, $compile_id);
	}

	private function _postProcess()
	{
		$output = '';

		if(Tools::isSubmit('submit_' . strtolower($this->name)))
		{
			foreach($this->_configs as $config)
			{
				if($config['type'])
				{
					if($config['type'] == 'image')
					{
						// Upload image
						if (isset($_FILES[$config['name']]) AND isset($_FILES[$config['name']]['tmp_name']) AND !empty($_FILES[$config['name']]['tmp_name']))
						{
							if ($error = checkImage($_FILES[$config['name']], Tools::convertBytes(ini_get('upload_max_filesize'))))
								$errors .= $error;
							else
							{
								if($name = $this->_createPicture($_FILES[$config['name']], $this->_imgPath))
								{
									if(!Configuration::updateValue($config['config_name'], $name))
										return false;
								}
							}
						}
					}
					elseif($config['type'] == 'time')
					{
						$hours = Tools::getValue($config['name'] . '_hours');
						$mins  = Tools::getValue($config['name'] . '_mins');

						$time = self::_hoursToSeconds($hours) + self::_minutesToSeconds($mins);

						if(!Configuration::updateValue($config['config_name'], $time))
							return false;
					}
					else
					{
						if(!Configuration::updateValue($config['config_name'], Tools::getValue($config['name'])))
							return false;
					}
				}
			}

			// Special
			$time = strtotime('midnight') + Configuration::get($this->_abbreviation . '_TIME_BETWEEN_PERIOD') + Configuration::get($this->_abbreviation . '_TIME_START_DAY');
			Configuration::updateValue($this->_abbreviation . '_NEXT_PERIOD', $time);
			$this->_emptyCache(false);
			$output .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Settings updated').'</div>';
		}

		return $output;
	}

	public function cronTask($action)
	{
		switch($action)
		{
			case 1:
				// Update next period
				Configuration::updateValue($this->_abbreviation . '_NEXT_PERIOD', strtotime('midnight') + self::_daysToSeconds(1) + 36000);
				// Check if all offers for the day
				$nextPeriod = date('Y-m-d', Configuration::get($this->_abbreviation . '_NEXT_PERIOD'));
				$nbOffersOfTheDay = FlashsalesOffer::getNumberOffersForTheDay($nextPeriod);
				$nbOffersNeeded = Configuration::get($this->_abbreviation . '_NB_OFFERS');
				if($nbOffersOfTheDay < $nbOffersNeeded)
				{
					// Get offers best demand
					$nbOffersMissing = $nbOffersNeeded - $nbOffersOfTheDay;
					$sql = 'SELECT fo.`id_flashsales_offer`, COUNT(fom.`id_flashsales_offer`) AS `nb_demand`
					FROM `'._DB_PREFIX_.'flashsales_offer` fo
					LEFT JOIN `'._DB_PREFIX_.'flashsales_offer_mailalert` fom ON (fom.`id_flashsales_offer` = fo.`id_flashsales_offer`)
					WHERE fo.`date_start` < CURRENT_DATE()
					ORDER by `nb_demand`
					LIMIT ' . (int)$nbOffersMissing;
					$results = Db::getInstance()->ExecuteS($sql);
					if(empty($results))
					{
						$sql = 'SELECT fo.`id_flashsales_offer`, COUNT(fom.`id_flashsales_offer`) AS `nb_demand`
						FROM `'._DB_PREFIX_.'flashsales_offer` fo
						LEFT JOIN `'._DB_PREFIX_.'flashsales_offer_mailalert` fom ON (fom.`id_flashsales_offer` = fo.`id_flashsales_offer`)
						WHERE fo.`date_start` NOT IN(CURRENT_DATE(), \''. $nextPeriod .'\')
						ORDER by `nb_demand`
						LIMIT ' . (int)$nbOffersMissing;
						$results = Db::getInstance()->ExecuteS($sql);
					}
					if(!empty($results))
					{
						foreach($results AS $result)
						{
							$flashsales_offer = new FlashsalesOffer($result['id_flashsales_offer']);
							$flashsales_offer->date_start = $nextPeriod;
							$flashsales_offer->date_end		= date('Y-m-d', (int)(Configuration::get($this->_abbreviation . '_NEXT_PERIOD') + (int)(Configuration::get($this->_abbreviation . '_TIME_BETWEEN_PERIOD'))));
							$flashsales_offer->update();
						}
					}
				}
				break;
			case 2:
				// CLEAR CACHE
				$this->_emptyCache(false);
				break;
			case 3:
				// CLEAR ALL CACHE
				$this->_emptyCache(true);
				break;
				// Disable offers.
				/*
				if(time() <= Configuration::get($this->_abbreviation . '_NEXT_PERIOD'))
				{
					$nextPeriod = date('Y-m-d', Configuration::get($this->_abbreviation . '_NEXT_PERIOD'));
					$sql = 'UPDATE `'._DB_PREFIX_.'flashsales_offer` fo SET fo.`active` = 0 WHERE fo.`date_end` != \'' . $nextPeriod . '\'';
					Db::getInstance()->Execute($sql);
					// Clear cache ?
				}
				*/
		}
	}

	// ---------------------------
	// --------- HOOKS -----------
	// ---------------------------
	public function hookHeader($params)
	{
		global $smarty, $cookie;
		
		$vars = array(
			'path'		=> $this->_path,
			'id_lang' => (int)$cookie->id_lang,
			'logged'	=> isset($cookie->id_customer) && $cookie->isLogged() ? true : false,
		);

		Tools::addCSS($this->_path . $this->name . '.css', 'all');
		Tools::addJS($this->_path	 . $this->name . '.js');

		foreach($this->_hooks as $hook)
		{
			if($hook['insert'])
				$smarty->assign('HOOK_' . strtoupper($this->name) . '_' . strtoupper($hook['name']), Module::hookExec($hook['name']));
		}

		$smarty->assign('module_header_' . strtolower($this->name), $vars);
	}

	public function hookHomeFlashSales($params)
	{
		global $smarty, $cookie;

		// Cache
		$smartyCacheId = self::$cacheFiles[0] . '|' . Configuration::get('FS_CACHE_ID');
		$templateName = self::$cacheFiles[0] .'.tpl';

		Tools::enableCache();
		$end = strtotime('midnight') + (int)Configuration::get('FS_TIME_START_DAY') + (int)Configuration::get('FS_TIME_BETWEEN_PERIOD');
		$now = strtotime('now');
		$smarty->cache_lifetime = $end - $now;

		if (!$this->isCached($templateName, $smartyCacheId))
		{
			$vars = array(
				'module_name' => strtoupper($this->name),
				'products' => FlashsalesOffer::getOffersForTheDay(date('Y-m-d'), (int)$cookie->id_lang)
			);
			$smarty->assign(strtolower($this->name), $vars);
		}

		$display = $this->display(__FILE__, $templateName, $smartyCacheId);
		Tools::restoreCacheSettings();

		return $display;
	}

	// ---------------------------
	// --- INSTALL / UNINSTALL ---
	// ---------------------------
	public function install()
	{
		parent::install();

		if(!$this->_installTables() || !$this->_installHooks())
			return false;

		if(!$this->_initTables())
			return false;

		if(!$this->_installModuleTab($this->_adminClassName, $this->_adminTabName, $this->_idTabParent))
			return false;

		foreach($this->_hooks as $hook)
		{
			if(!$this->registerHook($hook['name']))
				return false;
		}

		foreach($this->_configs as $config)
		{
			if(!Configuration::updateValue($config['config_name'], $config['default']))
				return false;
		}

		@copy(_PS_MODULE_DIR_ . $this->name . '/logo.gif', _PS_IMG_DIR_ . 't/' . $this->_adminClassName . '.gif');

		return true;
	}

	public function uninstall()
	{
		parent::uninstall();
		
		if(!$this->_uninstallTables() || !$this->_uninstallHooks())
			return false;

		if(!$this->_uninstallModuleTab($this->_adminClassName))
			return false;

		foreach($this->_configs as $config)
		{
			if(!Configuration::deleteByName($config['config_name']))
				return false;
		}

		@unlink(_PS_IMG_DIR_ . 't/' . $this->_adminClassName . '.gif');

		foreach($this->_controllers AS $controller)
		{
			@unlink(_PS_ROOT_DIR_ . $controller['root_file']);
			@unlink(_PS_CONTROLLER_DIR_ . $controller['controller']);
			@unlink(_THEME_DIR_ . $controller['tpl_file']);
		}

		$this->_emptyCache(true);

		return true;
	}

	private function _installTables()
	{
		$database	 = Db::getInstance();
		$charset	 = 'utf8';
		$engine		 = (defined('_MYSQL_ENGINE_') ? _MYSQL_ENGINE_ : 'InnoDB');

		foreach($this->_tables AS $table)
		{
			// Add module table
			$sql  = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $table['name'] . '` (';
			if(!$table['identifiers'])
				$sql .= '`id_'. $table['name'] .'` int(10) unsigned NOT NULL auto_increment, ';

			foreach($table['fields'] AS $field)
			{
				$sql .= '`' . $field['name'] . '` ' . $field['type'] . ($field['size'] ? '(' . $field['size'] . ')' : '') . ($field['unsigned'] ? ' unsigned' : '') . ($field['null'] ? '' : ' NOT NULL') . ', ';
			}

			$sql .= '`date_add` datetime NOT NULL, ';
			$sql .= '`date_upd` datetime NOT NULL, ';

			if($table['identifiers'])
			{
				$sql .= 'PRIMARY KEY (';
				foreach($table['identifiers'] AS $key => $identifier)
				{
					if($key !=  0)
						$sql .= ', `' . $identifier . '`';
					else
						$sql .= '`' . $identifier . '`';
				}
				$sql .= ')';
			}
			else
				$sql .= 'PRIMARY KEY (`id_'. $table['name'] .'`)';

			$sql .= ') ENGINE=' . $engine . ' DEFAULT CHARSET=' . $charset . ';';

			if(!$database->Execute($sql))
				return false;

			// Add module_lang table
			if(isset($table['fields_translations']))
			{
				$sql  = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $table['name'] . '_lang` (';

				if(!$table['identifiers'])
				{
					$sql .= '`id_'. $table['name'] .'` int(10) unsigned NOT NULL,';
					$sql .= '`id_lang` tinyint(2) unsigned NOT NULL,';
				}

				foreach($table['fields_translations'] AS $field)
				{
					$sql .= '`' . $field['name'] . '` ' . $field['type'] . ($field['size'] ? '(' . $field['size'] . ')' : '') . ($field['unsigned'] ? ' unsigned' : '') . ($field['null'] ? '' : ' NOT NULL') . ',';
				}

				$sql .= 'PRIMARY KEY (`id_'. $table['name'] .'`, `id_lang`)';
				$sql .= ') ENGINE=' . $engine . ' DEFAULT CHARSET=' . $charset . ';';

				if(!$database->Execute($sql))
					return false;
			}
		}

		return true;
	}

	private function _uninstallTables()
	{
		foreach($this->_tables AS $table)
		{
			if(!Db::getInstance()->Execute('DROP TABLE `' . _DB_PREFIX_ . $table['name'] .'`'))
				return false;

			if(isset($table['fields_translations']))
			{
				if(!Db::getInstance()->Execute('DROP TABLE `' . _DB_PREFIX_ . $table['name'] .'_lang`'))
					return false;
			}
		}

		return true;
	}

	private function _initTables()
	{
		$sql = "
		INSERT INTO  `" . _DB_PREFIX_ . "flashsales_category` (
		`id_flashsales_category` ,
		`id_parent` ,
		`level_depth` ,
		`active` ,
		`position` ,
		`date_add` ,
		`date_upd`
		)
		VALUES (
		1 ,  '0',  '1',  '1',  '1',  '2012-03-13 00:00:00',  '2012-03-13 00:00:00'
		);";

		if(!Db::getInstance()->Execute($sql))
			return false;

		$sql = "
		INSERT INTO  `" . _DB_PREFIX_ . "flashsales_category_lang` (
		`id_flashsales_category` ,
		`id_lang` ,
		`name` ,
		`description` ,
		`link_rewrite` ,
		`meta_title` ,
		`meta_keywords` ,
		`meta_description`
		)
		VALUES (
		'1',  '1',  'Home', NULL ,  '', NULL , NULL , NULL
		);";

		if(!Db::getInstance()->Execute($sql))
			return false;

		$sql = "
		INSERT INTO  `" . _DB_PREFIX_ . "flashsales_category_lang` (
		`id_flashsales_category` ,
		`id_lang` ,
		`name` ,
		`description` ,
		`link_rewrite` ,
		`meta_title` ,
		`meta_keywords` ,
		`meta_description`
		)
		VALUES (
		'1',  '2',  'Accueil', NULL ,  '', NULL , NULL , NULL
		);";

		if(!Db::getInstance()->Execute($sql))
			return false;

		$sql = "
		INSERT INTO  `" . _DB_PREFIX_ . "flashsales_category_lang` (
		`id_flashsales_category` ,
		`id_lang` ,
		`name` ,
		`description` ,
		`link_rewrite` ,
		`meta_title` ,
		`meta_keywords` ,
		`meta_description`
		)
		VALUES (
		'1',  '3',  'Inicio', NULL ,  '', NULL , NULL , NULL
		);";

		if(!Db::getInstance()->Execute($sql))
			return false;

		$sql = "
		INSERT INTO  `" . _DB_PREFIX_ . "flashsales_category_lang` (
		`id_flashsales_category` ,
		`id_lang` ,
		`name` ,
		`description` ,
		`link_rewrite` ,
		`meta_title` ,
		`meta_keywords` ,
		`meta_description`
		)
		VALUES (
		'1',  '4',  'Start', NULL ,  '', NULL , NULL , NULL
		);";

		if(!Db::getInstance()->Execute($sql))
			return false;

		$sql = "
		INSERT INTO  `" . _DB_PREFIX_ . "flashsales_category_lang` (
		`id_flashsales_category` ,
		`id_lang` ,
		`name` ,
		`description` ,
		`link_rewrite` ,
		`meta_title` ,
		`meta_keywords` ,
		`meta_description`
		)
		VALUES (
		'1',  '5',  'Home page', NULL ,  '', NULL , NULL , NULL
		);";

		if(!Db::getInstance()->Execute($sql))
			return false;

		return true;
	}

	private function _installHooks()
	{
		foreach($this->_hooks as $hook)
		{
			if($hook['insert'])
			{
				$sql = "INSERT INTO `" . _DB_PREFIX_ . "hook` SET `name`= '". $hook['name'] ."', `title`= '". $hook['title'] ."', `description`= '". $hook['description'] ."'";
				if(!DB::getInstance()->Execute($sql))
					return false;
			}
		}

		return true;
	}

	private function _uninstallHooks()
	{
		foreach($this->_hooks as $hook)
		{
			if($hook['insert'])
			{
				if(!DB::getInstance()->Execute("DELETE FROM `" . _DB_PREFIX_ . "hook` WHERE `name` = '" . $hook['name'] . "'"))
					return false;
			}
		}

		return true;
	}

	private function _installModuleTab($className, $name, $idParent)
	{
		$tab = new Tab();

		$tab->class_name = $className;
		$tab->name			 = $name;
		$tab->module		 = $this->name;
		$tab->id_parent	 = $idParent;

		if(!$tab->save())
		{
			$this->_errors[] = Tools::displayError('An error occurred while saving new tab: ') . ' <b>' . $tab->name . ' (' . mysql_error() . ')</b>';
			return false;
		}

		$fields = array(
			'id_profile' => 1,
			'id_tab' 		 => (int)$tab->id,
			'view' 			 => 1,
			'add' 			 => 1,
			'edit' 			 => 1,
			'delete' 		 => 1
		);

		Db::getInstance()->autoExecute(_DB_PREFIX_.'access', $fields, 'INSERT');

		return true;
	}

	private function _uninstallModuleTab($className)
	{
		$idTab = Tab::getIdFromClassName($className);

		if($idTab != 0)
		{
			$tab = new Tab($idTab);
			$tab->delete();

			$fields = array(
				'id_profile' => 1,
				'id_tab' 		 => $idTab,
				'view' 			 => 1,
				'add' 			 => 1,
				'edit' 			 => 1,
				'delete' 		 => 1
			);

			Db::getInstance()->autoExecute(_DB_PREFIX_.'access', $fields, 'DELETE');

			return true;
		}
		else
			return false;
	}

	// ---------------------------
	// --------- TOOLS -----------
	// ---------------------------
	private function _createPicture($file, $path, $action = null, $name = null, $with = null, $height = null)
	{
		$img = PhpThumbFactory::create($file['tmp_name']);

		if(!$name)
			$name = $file['name'];
		else
		{
			$ext = $this->_getFileExtension($file);
			$name .= $ext; 
		}

		if($action)
		{
			switch($action)
			{
				case 'cropFromCenter':
					if(!$width || !$height)
						return false;

					$img->cropFromCenter($width, $height);
					break;
				case 'resize':
					if(!$width || !$height)
						return false;

					$img->resize($width, $height);
					break;
				case 'adaptiveResize':
					if(!$width || !$height)
						return false;

					$img->adaptiveResize($width, $height);
			}
		}
		$fileName = $path . $name;
		
		$img->save($fileName);

		return $name;
	}

	private function _getFileExtension($file)
	{
		return strrchr($file['name'], '.');
	}

	private function _deleteFile($fileName)
	{
		if(file_exists($fileName))
			return unlink($fileName);
		else
			return false;
	}

	/* Time converters */
	private static function _daysToSeconds($time)
	{
		return $time *= 86400;
	}

	private static function _secondsToDays($time)
	{
		return $time /= 86400;
	}

	private static function _minutesToSeconds($time)
	{
		return $time *= 60;
	}

	private static function _hoursToSeconds($time)
	{
		return $time *= 3600;
	}

	private static function _secondsToHours($time)
	{
		 return $time /= 3600;
	}

	private static function _secondsToMinutes($time)
	{
		 return $time /= 60;
	}

	private static function _twoDigits($number)
	{
		if($number <= 9)
			return '0' . $number;
		else
			return $number;
	}

	/* --- SMARTY --- */
	public static function twoDigitsSmarty($params, &$smarty)
	{
		return self::_twoDigits($params['number']);
	}

	public static function secondsToMinutesSmarty($params, &$smarty)
	{
		return self::_secondsToMinutes($params['time']);
	}

	private function _emptyCache($all = false)
	{
		global $smarty;

		if($all)
		{
			foreach(self::$cacheDirs as $dir)
				if (file_exists($dir))
					self::emptyDir($dir);
		}
		else
		{
			foreach(self::$cacheFiles AS $file)
			{
				$cache_id = self::$cacheFiles[0] . '|' . Configuration::get('FS_CACHE_ID');
				$template_name = self::$cacheFiles[0] . '.tpl';
				$template_name = $this->_getApplicableTemplateDir($template_name) . $template_name;
				$smarty->clearCache($template_name, $cache_id);
			}

			Configuration::updateValue('FS_CACHE_ID', strtoupper(Tools::passwdGen(10)));
		}
	}

	public static function emptyDir($dir)
	{
		if (is_dir($dir))
		{
			$objects = scandir($dir);
			foreach ($objects as $object)
				if ($object != "." && $object != ".." && $object != 'index.php')
					if (filetype($dir."/".$object) == "dir")
						self::emptyDir($dir."/".$object);
					else
						@unlink($dir."/".$object);
			reset($objects);
		}
	}
}
?>