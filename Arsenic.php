<?php
/**
 * Simple Selenium Flow generator
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 *
 * @method static Arsenic open
 * @method static Arsenic verifyElementPresent
 * @method static Arsenic verifyElementNotPresent
 * @method static Arsenic verifyTextPresent
 * @method static Arsenic assertTitle
 * @method static Arsenic deleteCookie
 * @method static Arsenic waitForPageToLoad
 * @method static Arsenic clickAndWait
 * @method static Arsenic click
 * @method static Arsenic chooseOkOnNextConfirmation
 * @method static Arsenic assertConfirmation
 * @method static Arsenic waitForVisible
 * @method static Arsenic type
 * @method static Arsenic store
 */
class Arsenic {

	const HTML = '<?xml version = "1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head profile="http://selenium-ide.openqa.org/profiles/test-case">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="selenium.base" href="" />
<title>%s</title></head>
<body><table cellpadding="1" cellspacing="1" border="1"><thead><tr><td rowspan="1" colspan="3">%s</td></tr></thead><tbody>
%s</tbody></table></body></html>';

	/** @var Arsenic */
	private static $instance = null;

	/** @var array */
	public $flow = array();

	/**
	 * @param string $method
	 * @param array<> $args
	 * @return Arsenic
	 */
	public static function __callStatic($method, array $args) {
		self::cmd($method, $args[0], isset($args[1]) ? $args[1] : null);
		return self::getInstance();
	}

	/**
	 * @static
	 * @return Arsenic|null
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
		self::getInstance()->flow[] = sprintf("<!--'%s'-->", htmlentities($text));
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
			'<tr><td>%s</td><td>%s</td><td>%s</td></tr>', $command, htmlentities($target), htmlentities($value)
		);
		return self::getInstance();
	}

	/**
	 * @param string $file
	 * @param string $title
	 */
	public static function toFile($file, $title = '') {
		file_put_contents($file, sprintf(self::HTML, $title, $title, self::getInstance()));
	}

	/**
	 * @return Arsenic
	 */
	public static function resetFlow() {
		self::getInstance()->flow = array();
		return self::getInstance();
	}

}
