<?php
class Document extends DataObject implements PermissionProvider{
	public static $db = array(
		'SortOrder'=>'Int',
		'Title' => 'Varchar',
		'Description' => 'HTMLText',
	);

	public static $has_one = array(
		'Page' => 'FilePage',
		'File' => 'File',
	);

	public static $summary_fields = array( 
		'Thumbnail' => 'Image',
		'Title' => 'Title',
	);

	public $default_sort = 'SortOrder ASC';

	public function getThumbnail(){
		if($this->File()) return new LiteralField('Thumbnail', $this->File()->CMSThumbnail());
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

		$fields->addFieldToTab('Root.Main', $uf = new UploadField('File', 'File'));
		$uf->setFolderName('documents');
		$uf->getValidator()->setAllowedExtensions(array('pdf', 'doc', 'xls'));

		return $fields;
	}

	/**
	 * Permissions
	 */
	public static $api_access = true;

	public function canView($member = false) {
		if(!$member) $member = Member::currentUser();

		return Permission::check('DOCUMENT_VIEW');
	}
	public function canEdit($member = false) {
		if(!$member) $member = Member::currentUser();

		return Permission::check('DOCUMENT_EDIT');
	}
	public function canDelete($member = false) {
		if(!$member) $member = Member::currentUser();

		return Permission::check('DOCUMENT_DELETE');
	}
	public function canCreate($member = false) {
		if(!$member) $member = Member::currentUser();

		return Permission::check('DOCUMENT_CREATE');
	}
	public function providePermissions() {
		return array(
			'DOCUMENT_VIEW' => 'Read a document',
			'DOCUMENT_EDIT' => 'Edit a document',
			'DOCUMENT_DELETE' => 'Delete a document',
			'DOCUMENT_CREATE' => 'Create a document',
		);
	}

	public function onBeforeWrite(){
		parent::onBeforeWrite();

		$file = $this->File();
		if($file->ID && !$this->Title){
			$this->Title = $file->Title;
		}
	}

	public function onBeforeDelete(){
		parent::onBeforeDelete();

		$file = $this->File();
		if($file->ID){
			$file->delete();
		}
	}
}