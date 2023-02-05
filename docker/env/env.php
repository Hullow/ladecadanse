<?php
// make a copy of this file with name 'env.php'
define("ENV", "dev");
define("MODE_DEBUG", true);


// full path of your ladecadanse application directory, for ex. "/home/michel/hosts/ladecadanse/"
$rep_absolu = "/var/www/html/";

// URL
// main domain, for ex "http://localhost" or "http://ladecadanse.local";
$url_domaine = "http://localhost:7777";
// complete if your site is in a subdirectory, for ex with "ladecadanse", $url_site will be http://localhost/ladecadanse
$url_site = $url_domaine."/";

// database
$param['dbhost'] = 'ladecadanse_db';
$param['dbname'] = 'ladecadanse';
$param['dbusername'] = 'dev';
$param['dbpassword'] = 'dev';

// SMTP credentials
// $glo_email_host = "mail.darksite.ch"; // prod
// $glo_email_username = "info@ladecadanse.ch"; // prod
$glo_email_host = "";
$glo_email_username = "";
$glo_email_password = "";

// $glo_email_admin = "michel@ladecadanse.ch"; // prod
// $glo_email_info = "info@ladecadanse.ch"; // prod
// $glo_email_support = "info@ladecadanse.ch"; // prod
$glo_email_admin = "";
$glo_email_info = "";
$glo_email_support = "";

define("MAIL_DOMAIN", '');

define("GOOGLE_API_KEY", ''); // use Google Maps library to display maps of venues

define("GOOGLE_RECAPTCHA_API_KEY_CLIENT", ''); // for (public) "Proposer un événement form"
define("GOOGLE_RECAPTCHA_API_KEY_SERVER", '');

define("GOOGLE_ANALYTICS_ID", ''); // 1st analytics tool (enabled only in prod)
define("GOOGLE_ANALYTICS_ENABLED", false);

define("MATOMO_ENABLED", false); // 2nd analytics tool (enabled only in prod)

define("PREVIEW", true);

// closable banner in homepage for announcements
define("HOME_TMP_BANNER_ENABLED", false);
define("HOME_TMP_BANNER_TITLE", "");
define("HOME_TMP_BANNER_CONTENT", "");
