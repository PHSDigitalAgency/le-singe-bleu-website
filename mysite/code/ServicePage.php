<?php
class ServicePage extends Page {

	public static $db = array(
	);

	public static $has_one = array(
	);

	public static $can_be_root = false;

/*	public function getCMSFields() {
		$fields = parent::getCMSFields();

		return $fields;
	}*/
}
class ServicePage_Controller extends Page_Controller {
	private static $allowed_actions = array(
	);
}
