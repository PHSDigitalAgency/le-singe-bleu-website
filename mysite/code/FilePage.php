<?php
class FilePage extends Page {

	public static $db = array(
		'SubTitle' => 'Varchar',
		'Header' => 'Text',
		'HeaderLogos' => 'Text',
	);

	public static $has_one = array(
	);

	public static $has_many = array(
		'Documents' => 'Document',
		'Logos' => 'Logo',
	);

	public static $can_be_root = false;

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$className = $this->ClassName;

		$fields->addFieldToTab('Root.Main', TextField::create('SubTitle', 'Sub-Title'), 'Content');
		$fields->addFieldToTab('Root.Main', TextareaField::create('Header', 'Header'), 'Content');
		$fields->addFieldToTab('Root.Logos', TextareaField::create('HeaderLogos', 'Text'));
		
		if($this->ID /* && Translatable::get_current_locale() == "en_US"*/){

			// documents
			$configd = GridFieldConfig_RelationEditor::create(50)
				->removeComponentsByType('GridFieldSortableHeader')
				->addComponent(new GridFieldSortableRows('SortOrder'))		        
		        ->addComponent(new GridFieldDeleteAction());
			
			$fd = new GridField('Documents', 'Documents', $this->Documents(), $configd);

			$fields->addFieldToTab('Root.Documents', $fd);

			// logos
			$configl = GridFieldConfig_RelationEditor::create(50)
				->removeComponentsByType('GridFieldSortableHeader')
				->addComponent(new GridFieldBulkImageUpload())
				// ->addComponent(new GridFieldBulkManager())
				->addComponent(new GridFieldSortableRows('SortOrder'))		        
		        ->addComponent(new GridFieldDeleteAction());

		    $configl->getComponentByType('GridFieldBulkImageUpload')->setConfig('folderName', 'logos');

			$fl = new GridField('Logos', 'Logos', $this->Logos(), $configl);

			$fields->addFieldToTab('Root.Logos', $fl);
		}

		// Apply Translatable modifications
		$this->applyTranslatableFieldsUpdate($fields, 'updateCMSFields');

		return $fields;
	}
}
class FilePage_Controller extends Page_Controller {
	private static $allowed_actions = array(
	);
}
