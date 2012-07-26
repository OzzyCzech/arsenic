<?php
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */

require_once 'Arsenic.php';
Arsenic::comment('Logout')->open('/logout')->deleteCookie('PHPSESSID')->open('/');
Arsenic::toFile('logout.html', 'Logout from application', false);

// login
Arsenic::comment('Login to application');
Arsenic::waitForVisible($loginForm = '//form[@id="loginForm"]');
Arsenic::click('//a[@id="showLoginForm"]');
Arsenic::type($loginForm . '//input[@id="email"]', 'email@gmail.com');
Arsenic::type($loginForm . '//input[@id="password"]', '123456');
Arsenic::clickAndWait($loginForm . '//input[@type="submit"]');
Arsenic::waitForPageToLoad(3000);
Arsenic::toFile('loginLogout.html', 'Login and logout');

// repeats
for ($i = 0; $i < 5; $i++) {
	Arsenic::open('/404/' . $i);
}

Arsenic::toFile('example.html', 'title');