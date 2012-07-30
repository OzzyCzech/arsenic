<?php
/**
 * Simple Selenium Flow generator
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 * @see https://github.com/OzzyCzech/arsenic
 *
 * @method static Arsenic open
 * @method static Arsenic verifyElementPresent
 * @method static Arsenic verifyElementNotPresent
 * @method static Arsenic verifyTextPresent
 * @method static Arsenic verifyTextNotPresent
 * @method static Arsenic verifyText
 * @method static Arsenic assertTitle
 * @method static Arsenic deleteCookie
 * @method static Arsenic waitForPageToLoad
 * @method static Arsenic clickAndWait
 * @method static Arsenic click
 * @method static Arsenic chooseOkOnNextConfirmation
 * @method static Arsenic assertConfirmation
 * @method static Arsenic waitForVisible
 * @method static Arsenic waitForElementPresent
 * @method static Arsenic type
 * @method static Arsenic store
 * @method static Arsenic storeValue
 * @method static Arsenic storeAttribute
 * @method static Arsenic chooseOkOnNextConfirmation
 * @method static Arsenic assertBodyText
 * @method static Arsenic verifyXpathCount
 * @method static Arsenic waitForTextPresent
 */
class Arsenic {

	/** @var null|string */
	public static $outputDir = null;

	const ALERT = '
		<!-- # # # # # # # # # # # # # # # # # # # # # # # # -->
		<!-- # Generated by Arsenic -->
		<!-- # # # # # # # # # # # # # # # # # # # # # # # # -->';

	const TEST_HTML = '<?xml version = "1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head profile="http://selenium-ide.openqa.org/profiles/test-case">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="selenium.base" href="" />
	<title>%s</title>
</head>
<body>
	<table cellpadding="1" cellspacing="1" border="1">
		<thead>
			<tr>
				<td rowspan="1" colspan="3">%s</td>
			</tr>
		</thead>
		<tbody>%s
		</tbody>
	</table>
</body>
</html>';

	const SUITE_HTML = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type" />
	<title>%s</title>
</head>
<body>
	<table id="suiteTable" cellpadding="1" cellspacing="1" border="1" class="selenium">
	<tbody>
		<tr>
			<td><b>%s</b></td>
		</tr>%s
	</tbody>
	</table>
</body>
</html>';

	/** @var Arsenic */
	private static $instance = null;

	/** @var array */
	public $flow = array();

	/** @var array */
	public $files = array();

	/**
	 * @param string $method
	 * @param array $args
	 * @return Arsenic
	 */
	public static function __callStatic($method, array $args) {
		self::cmd($method, isset($args[0]) ? $args[0] : null, isset($args[1]) ? $args[1] : null);
		return self::getInstance();
	}

	/**
	 * @return Arsenic
	 */
	public function __call($method, array $args) {
		$this->cmd($method, $args[0], isset($args[1]) ? $args[1] : null);
		return $this;
	}

	/**
	 * @static
	 * @return Arsenic|null
	 */
	public static function getInstance() {
		if (self::$instance == null) self::$instance = new self;
		return self::$instance;
	}

	/**
	 * @param string $text
	 * @return Arsenic
	 */
	public static function comment($text) {
		self::getInstance()->flow[] = sprintf("\t\t<!--%s-->", htmlentities($text));
		return self::getInstance();
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return implode(PHP_EOL, self::getInstance()->flow);
	}

	/**
	 * Add command to flow
	 *
	 * @param string $command
	 * @param null|string $target
	 * @param null|string $value
	 * @return \Arsenic
	 */
	public static function cmd($command, $target = null, $value = null) {
		self::getInstance()->flow[] = sprintf(
			"\t\t\t<tr>\n\t\t\t\t<td>%s</td>\n\t\t\t\t<td>%s</td>\n\t\t\t\t<td>%s</td>\n\t\t\t</tr>",
			$command,
			htmlentities($target),
			htmlentities($value)
		);
		return self::getInstance();
	}

	/**
	 * @param string $file
	 * @param string $title
	 * @return \Arsenic
	 */
	public static function toFile($file, $title = null, $reset = true) {
		if ($title === null) $title = basename($file, '.html');
		file_put_contents(
			self::$outputDir . $file, sprintf(self::TEST_HTML, $title, $title, self::ALERT . PHP_EOL . self::getInstance())
		);
		self::getInstance()->files[$file] = $title;
		if ($reset) self::reset();
		return self::getInstance();
	}

	/**
	 * @param string $file
	 * @param string $title
	 * @return \Arsenic
	 */
	public static function saveSuite($file, $title = 'Test Suite', $pathPrefix = './') {
		$links = '';
		foreach (self::getInstance()->files as $test => $title) {
			$links .= "\n\t\t" . sprintf(
				"<tr>\n\t\t\t<td><a href=\"%s\">%s</a></td>\n\t\t</tr>", $pathPrefix . $test, $title
			);
		}
		file_put_contents(self::$outputDir . $file, sprintf(self::SUITE_HTML, $title, $title, $links));
		return self::getInstance();
	}

	/**
	 * @param string $class
	 * @return \Arsenic
	 */
	public static function useClass($class) {
		foreach (get_class_methods($class) as $test) {
			$class::$test();
			Arsenic::toFile(str_replace('_', DIRECTORY_SEPARATOR, $test) . '.html');
		}
		return self::getInstance();
	}

	/**
	 * Reset flow
	 *
	 * @return Arsenic
	 */
	public static function reset() {
		self::getInstance()->flow = array();
		return self::getInstance();
	}

}
