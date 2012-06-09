<?php
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */

require_once 'SeleniumFlow.php';

// logout
$logout = new SeleniumFlow();
$logout->open('/logout')->deleteCookie('PHPSESSID')->open('/');

// login
$login = new SeleniumFlow();
$login->waitForVisible($loginForm = '//form[@id="loginForm"]');
$login->click('//a[@id="showLoginForm"]');
$login->type($loginForm . '//input[@id="email"]', 'email@gmail.com');
$login->type($loginForm . '//input[@id="password"]', '123456');
$login->clickAndWait($loginForm . '//input[@type="submit"]');
$login->waitForPageToLoad(3000);


// poskladani testu
$sel = new SeleniumFlow('test404Page');
$sel->comment('komentar do selenia IDE');
$sel->inc($logout);
$sel->inc($login);

// repeats
for ($i = 0; $i < 5; $i++) {
	$sel->open('/404/' . $i);
}

$sel->open('/404');
$sel->verifyElementPresent('/body[contains, "pageNotFound"]');
$sel->inc($logout); // nakonci se odhlasim

echo $sel;