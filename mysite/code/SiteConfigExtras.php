<?php
class SiteConfigExtras extends DataExtension{
	public static $db = array(
		'Name' => 'Varchar',
		'JobTitle' => 'Varchar',
		'PhoneVietnam' => 'Varchar',
		'PhoneFrance' => 'Varchar',
		'Email' => 'Varchar',
		'Facebook' => 'Varchar',
		'Twitter' => 'Varchar',
		'LinkedIn' => 'Varchar',
	);

	public static $has_one = array(
		'Logo' => 'Image',
	);

	public function updateCMSFields(FieldList $fields){
		$fields->removeByName('Tagline');
		
		$fields->addFieldToTab('Root.Main', new UploadField('Logo', 'Logo'));
		$fields->addFieldToTab('Root.Informations', new TextField('Name', 'Name'));
		$fields->addFieldToTab('Root.Informations', new TextField('Tagline', 'Tagline'));
		$fields->addFieldToTab('Root.Informations', new TextField('JobTitle', 'Job Title'));
		$fields->addFieldToTab('Root.Informations', new TextField('PhoneVietnam', 'Phone Vietnam'));
		$fields->addFieldToTab('Root.Informations', new TextField('PhoneFrance', 'Phone France'));
		$fields->addFieldToTab('Root.Informations', new TextField('Email', 'Email'));
		$fields->addFieldToTab('Root.Informations', new TextField('Facebook', 'Facebook'));
		$fields->addFieldToTab('Root.Informations', new TextField('Twitter', 'Twitter'));
		$fields->addFieldToTab('Root.Informations', new TextField('LinkedIn', 'LinkedIn'));

		if(Member::currentUser()->inGroup(1)){
			$fields->removeByName('Main');
			$fields->removeByName('Access');
		}
	}

	/**
	 * Permissions
	 */
	public static $api_access = true;

	public function canView($member = false) {
		if(!$member) $member = Member::currentUser();

		return Permission::check('INFORMATIONS_VIEW');
	}
	public function canEdit($member = false) {
		if(!$member) $member = Member::currentUser();

		return Permission::check('INFORMATIONS_EDIT');
	}

	public function providePermissions() {
		return array(
			'INFORMATIONS_VIEW' => 'View informations',
			'INFORMATIONS_EDIT' => 'Edit informations',
		);
	}
}