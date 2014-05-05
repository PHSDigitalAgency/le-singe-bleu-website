<?php
class Visual extends DataObject implements PermissionProvider{
	public static $db = array(
		'SortOrder'=>'Int',
		'Title' => 'Varchar',
		'Description' => 'HTMLText',
	);

	public static $has_one = array(
		'Page' => 'Page',
		'Image' => 'Image',
	);

	public static $summary_fields = array( 
		'Thumbnail' => 'Image',
		'Title' => 'Title',
	);

	public $default_sort = 'SortOrder ASC';

	/*public static $searchable_fields = array(
		'Title',
	);*/

	public function getThumbnail(){
		if($this->Image()) return $this->Image()->CroppedImage(110,46);
	}

	public function getCMSFields(){
		$fields = parent::getCMSFields();

		$fields->removeByName('PageID');
		$fields->removeByName('SortOrder');
		$fields->removeByName('Description');

		HtmlEditorConfig::get('cms')->removeButtons(
			'tablecontrols',
			'justifyleft',
			'justifycenter',
			'justifyright',
			'justifyfull',
			'formatselect'
			);

		$fields->addFieldToTab('Root.Main', $editor = new HTMLEditorField('Description', 'Description'));
		$editor->setRows(15);

		$fields->addFieldToTab('Root.Main', $uf = new UploadField('Image', 'Image'));
		$uf->setFolderName('images');
		$uf->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));

		return $fields;
	}

	/**
	 * Permissions
	 */
	public static $api_access = true;

	public function canView($member = false) {
		if(!$member) $member = Member::currentUser();

		return Permission::check('VISUAL_VIEW');
	}
	public function canEdit($member = false) {
		if(!$member) $member = Member::currentUser();

		return Permission::check('VISUAL_EDIT');
	}
	public function canDelete($member = false) {
		if(!$member) $member = Member::currentUser();

		return Permission::check('VISUAL_DELETE');
	}
	public function canCreate($member = false) {
		if(!$member) $member = Member::currentUser();
		
		return Permission::check('VISUAL_CREATE');
	}
	public function providePermissions() {
		return array(
			'VISUAL_VIEW' => 'Read a visual',
			'VISUAL_EDIT' => 'Edit a visual',
			'VISUAL_DELETE' => 'Delete a visual',
			'VISUAL_CREATE' => 'Create a visual',
		);
	}


	public function onBeforeWrite(){
		parent::onBeforeWrite();

		$image = $this->Image();
		if($image->ID && !$this->Title){
			$this->Title = $image->Title;
		}
	}

	public function onBeforeDelete(){
		parent::onBeforeDelete();

		$image = $this->Image();
		if($image->ID){
			$image->delete();
		}
	}
}