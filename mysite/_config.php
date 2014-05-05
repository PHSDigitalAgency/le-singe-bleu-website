<?php

global $project;
$project = 'mysite';

global $database;
$database = '';

require_once('conf/ConfigureFromEnv.php');

Security::setDefaultAdmin("yvestrublin@gmail.com","19cookie76");

// Set the site locale
i18n::set_locale('fr_FR');

Translatable::set_default_locale("fr_FR");
Translatable::set_allowed_locales(array('fr_FR', 'en_US'));

GDBackend::set_default_quality(100);

DashboardGoogleAnalyticsPanel::set_account("yvestrublin@gmail.com", "19foumoila76", "74305104");

// Where $email is your Google email, $password is your password, and $profileID is the profile ID of the account, found on the "Profile Settings" tab of the Google Analytics profile for this project.