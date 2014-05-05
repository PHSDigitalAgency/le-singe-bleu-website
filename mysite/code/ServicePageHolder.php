<?php
class ServicePageHolder extends Page {

	public static $db = array(
	);

	public static $has_one = array(
	);

	public static $allowed_children = array(
		'ServicePage'
	);

}
class ServicePageHolder_Controller extends Page_Controller {
	private static $allowed_actions = array(
	);
}
