<?php
/**
 * Login form
 *
 * @package Archon
 * @author Chris Rishel
 */

isset($_ARCHON) or die();

if ($_ARCHON->config->ForceHTTPS && !$_ARCHON->Security->Session->isSecureConnection())
{
   die('<html><body onLoad="location.href=\'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '\';"></body></html>');
}

$go = $_REQUEST['go'] ? $_REQUEST['go'] : '';
$go = str_replace('f=logout', '', $go);

if($_ARCHON->Security->isAuthenticated())
{
    header("Location: ?$go");
}

$PublicPhrasePhraseInputTypeID = $_ARCHON->getPhraseTypeIDFromString('Public Phrase');

$objLoginTitlePhrase = Phrase::getPhrase('login_title', PACKAGE_CORE, 0, $PublicPhrasePhraseInputTypeID);
$strLoginTitle = $objLoginTitlePhrase ? $objLoginTitlePhrase->getPhraseValue(ENCODE_HTML) : 'Login or Register an Account';

$_ARCHON->PublicInterface->Title = $strLoginTitle;
$_ARCHON->PublicInterface->addNavigation($_ARCHON->PublicInterface->Title);

require_once("header.inc.php");

$objSelectOnePhrase = Phrase::getPhrase('register_selectone', PACKAGE_CORE, 0, $PublicPhrasePhraseInputTypeID);
$strSelectOne = $objSelectOnePhrase ? $objSelectOnePhrase->getPhraseValue(ENCODE_HTML) : '(Select One)';

$objLoginPhrase = Phrase::getPhrase('login_login', PACKAGE_CORE, 0, $PublicPhrasePhraseInputTypeID);
$strLogin = $objLoginPhrase ? $objLoginPhrase->getPhraseValue(ENCODE_HTML) : 'Login';
$objPasswordPhrase = Phrase::getPhrase('login_password', PACKAGE_CORE, 0, $PublicPhrasePhraseInputTypeID);
$strPassword = $objPasswordPhrase ? $objPasswordPhrase->getPhraseValue(ENCODE_HTML) : 'Password';
$objRememberMePhrase = Phrase::getPhrase('login_rememberme', PACKAGE_CORE, 0, $PublicPhrasePhraseInputTypeID);
$strRememberMe = $objRememberMePhrase ? $objRememberMePhrase->getPhraseValue(ENCODE_HTML) : 'Remember Me';

$strPageTitle = strip_tags($strLoginTitle);

$strSubmitButton = "<div class=\"form-group\"><div class=\"col-sm-offset-4 col-sm-8\"><input
    type=\"submit\" class=\"btn btn-primary\" value=\"$strLogin\" /></div></div>";

$vars = array();

// Why is the value for this button not internationalized?
$registerButton = "<input type=\"button\" class=\"btn btn-primary\" value=\"Register an Account\" onclick=\"location.href='?p=core/register&amp;go=$go';\" />\n";

$inputs[] = array(
	'strInputLabel' => "<label class=\"col-sm-4 control-label\" for=\"ArchonLoginFieldA\">$strLogin:</label>",
	'strInputElement' => "<input type=\"text\" class=\"form-control\" id=\"ArchonLoginFieldA\" name=\"ArchonLogin\" value=\"$_REQUEST[login]\" maxlength=\"50\" />",
	'strRequired' => '',
	'template' => 'FieldGeneral',
);

$inputs[] = array(
	'strInputLabel' => "<label class=\"col-sm-4 control-label\" for=\"ArchonPasswordFieldA\">$strPassword:</label>",
	'strInputElement' => "<input type=\"password\" class=\"form-control\" id=\"ArchonPasswordFieldA\" name=\"ArchonPassword\" />",
	'strRequired' => '',
	'template' => 'FieldGeneral',
);

$inputs[] = array(
	'strInputLabel' => "$strRememberMe",
	'strInputElement' => "<input type=\"checkbox\" name=\"RememberMe\" id=\"RememberMeFieldA\" value=\"1\" />",
	'strRequired' => '',
	'template' => 'FieldCheckbox',
);

$query_p = htmlspecialchars($_REQUEST['p'], ENT_COMPAT, "UTF-8");
$query_go = htmlspecialchars($_REQUEST['go'], ENT_COMPAT, "UTF-8");

$form = "<input type=\"hidden\" name=\"p\" value=\"{$query_p}\" />\n";
$form .= "<input type=\"hidden\" name=\"go\" value=\"{$query_go}\" />\n";

foreach($inputs as $input)
{
	$template = array_pop($input);
	$form .= $_ARCHON->PublicInterface->executeTemplate('core', $template, $input);
}

echo("<form action=\"index.php\" class=\"form-horizontal col-sm-5\" accept-charset=\"UTF-8\" method=\"post\">\n");
	eval($_ARCHON->PublicInterface->Templates['core']['Login']);
print "</form>\n";
require_once("footer.inc.php");
