Arsenic
=======

Simple PHP selenium IDE generator.

```
<?php
Arsenic::comment('Logout')->open('/logout')->deleteCookie('PHPSESSID')->open('/');
Arsenic::toFile('logout.html', 'Logout from application');

// login
Arsenic::waitForVisible($loginForm = '//form[@id="loginForm"]');
Arsenic::click('//a[@id="showLoginForm"]');
Arsenic::type($loginForm . '//input[@id="email"]', 'email@gmail.com');
Arsenic::type($loginForm . '//input[@id="password"]', '123456');
Arsenic::clickAndWait($loginForm . '//input[@type="submit"]');
Arsenic::waitForPageToLoad(3000);

Arsenic::comment('comment');

// repeats
for ($i = 0; $i < 5; $i++) {
	Arsenic::open('/404/' . $i);
}

// file export
Arsenic::toFile('example.html', 'title');
```