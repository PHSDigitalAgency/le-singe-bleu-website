<?php
class FilePageHolder extends Page {

	public static $db = array(
	);

	public static $has_one = array(
	);

	public static $allowed_children = array(
		'FilePage'
	);


}
class FilePageHolder_Controller extends Page_Controller {
	private static $allowed_actions = array(
	);
}
