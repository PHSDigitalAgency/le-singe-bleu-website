<?php
class Logo extends DataObject implements PermissionProvider{
	public static $db = array(
		'SortOrder'=>'Int',
		'Title' => 'Varchar',
		'Link' => 'Varchar',
	);

	public static $has_one = array(
		'Page' => 'FilePage',
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

		$fields->addFieldToTab('Root.Main', $uf = new UploadField('Image', 'Logo'));
		$uf->setFolderName('logos');
		$uf->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));

		return $fields;
	}

	/**
	 * Permissions
	 */
	public static $api_access = true;

	public function canView($member = false) {
		if(!$member) $member = Member::currentUser();

		return Permission::check('LOGO_VIEW');
	}
	public function canEdit($member = false) {
		if(!$member) $member = Member::currentUser();

		return Permission::check('LOGO_EDIT');
	}
	public function canDelete($member = false) {
		if(!$member) $member = Member::currentUser();

		return Permission::check('LOGO_DELETE');
	}
	public function canCreate($member = false) {
		if(!$member) $member = Member::currentUser();
		
		return Permission::check('VISUAL_CREATE');
	}
	public function providePermissions() {
		return array(
			'LOGO_VIEW' => 'Read a logo',
			'LOGO_EDIT' => 'Edit a logo',
			'LOGO_DELETE' => 'Delete a logo',
			'LOGO_CREATE' => 'Create a logo',
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