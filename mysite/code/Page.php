<?php
class Page extends SiteTree {

	private static $db = array(
	);

	private static $has_one = array(
	);
	
	private static $has_many = array(
		'Images' => 'Visual',
	);

	private static $allowed_children = array();
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$className = $this->ClassName;

		if($this->ID /*&& Translatable::get_current_locale() == "en_US"*/
				&& ($className == 'ServicePage'
					|| $className == 'FilePage')){
			$config = GridFieldConfig_RelationEditor::create(50)
				// ->removeComponentsByType('GridFieldAddNewButton')
				->removeComponentsByType('GridFieldSortableHeader')
				->addComponent(new GridFieldBulkImageUpload())
				// ->addComponent(new GridFieldBulkManager())
				->addComponent(new GridFieldSortableRows('SortOrder'))		        
		        ->addComponent(new GridFieldDeleteAction());

		    $config->getComponentByType('GridFieldBulkImageUpload')->setConfig('folderName', 'images');
			
			$f = new GridField('Images', 'Images', $this->Images(), $config);

			$fields->addFieldToTab("Root.Images", $f);
		}

		// Apply Translatable modifications
		$this->applyTranslatableFieldsUpdate($fields, 'updateCMSFields');

		return $fields;
	}

	public function getSettingsFields() {
		$fields = parent::getSettingsFields();

		// Apply Translatable modifications
		$this->applyTranslatableFieldsUpdate($fields, 'updateSettingsFields');

		return $fields;
	}

	public function stripHtmlTags($str){
		return trim(preg_replace('/\s+/', ' ', strip_tags($str)));
	}

	public function onBeforeWrite(){
		if(!$this->MetaDescription){
			if($this->Content){
				$this->MetaDescription = $this->stripHtmlTags($this->Content);
			}
		}
		parent::onBeforeWrite();
	}
}
class Page_Controller extends ContentController {

	/**
	 * An array of actions that can be accessed via a request. Each array element should be an action name, and the
	 * permissions or conditions required to allow the user to access it.
	 *
	 * <code>
	 * array (
	 *     'action', // anyone can access this action
	 *     'action' => true, // same as above
	 *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
	 *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
	 * );
	 * </code>
	 *
	 * @var array
	 */
	private static $allowed_actions = array(
	);

	public function init() {
		parent::init();

		// Note: you should use SS template require tags inside your templates 
		// instead of putting Requirements calls here.  However these are 
		// included so that our older themes still work

		if($this->dataRecord->hasExtension('Translatable')) {
			i18n::set_locale($this->dataRecord->Locale);
		}

		Requirements::insertHeadTags('<meta http-equiv="Content-language" content="' . i18n::get_locale() . '" />');

		Requirements::combine_files(
			'styles.css',
			array(
				'themes/singebleu/css/bootstrap.min.css',
				'themes/singebleu/css/magnific-popup.css',
				'themes/singebleu/css/bootstrap-responsive.min.css',
				'themes/singebleu/css/main.css',
			)
		);
		
		Requirements::combine_files(
			'scripts.js',
			array(
				'themes/singebleu/js/jquery-1.10.2.min.js',
				SAPPHIRE_DIR . '/javascript/i18n.js',
				'themes/singebleu/js/bootstrap.min.js',
				'themes/singebleu/js/jquery.magnific-popup.min.js',
				'themes/singebleu/js/main.js',
			)
		);
		
		Requirements::add_i18n_javascript('themes/singebleu/js/lang');

	}

	public function PageByLang($url, $lang) {
		$SQL_url = Convert::raw2sql($url);
		$SQL_lang = Convert::raw2sql($lang);

		$page = Translatable::get_one_by_lang('SiteTree', $SQL_lang, "URLSegment = '$SQL_url'");

		if ($page->Locale != Translatable::get_current_locale()) {
			$page = $page->getTranslation(Translatable::get_current_locale());
		}
		return $page;
	}

	public function getFilePageImages(){
		$loc = $this->get_current_locale();

		$visuals = Visual::get()
			->innerJoin("SiteTree", "\"SiteTree\".\"ClassName\" = 'FilePage' AND \"SiteTree\".\"ID\" = \"Visual\".\"PageID\" AND \"SiteTree\".\"Locale\" = '$loc' ORDER BY \"SiteTree\".\"ID\" ASC, \"Visual\".\"SortOrder\" ASC");

		if($visuals) return $visuals;
		else return false;
	}
}