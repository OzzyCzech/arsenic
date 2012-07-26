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

	/** @var array */
	private $flow = array();

	/** @var string */
	private $name = '';

	/**
	 * @param string $name
	 */
	public function __construct($name = '') {
		$this->name = $name;
	}

	/**
	 * @param string $method
	 * @param array<> $args
	 * @return Arsenic
	 */
	public function __call($method, array $args) {
		$this->flow[] = sprintf(
			'<tr><td>%s</td><td>%s</td><td>%s</td></tr>', $method, htmlentities($args[0]),
			isset($args[1]) ? htmlentities($args[1]) : ''
		);
		return $this;
	}

	/**
	 * @param Arsenic $Arsenic
	 * @return Arsenic
	 */
	public function inc(Arsenic $Arsenic) {
		$this->flow[] = (string)$Arsenic;
		return $this;
	}

	/**
	 * @param string $text
	 * @return Arsenic
	 */
	public function comment($text) {
		$this->flow[] = sprintf("<!--'%s'-->", htmlentities($text));
		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return implode(PHP_EOL, $this->flow);
	}

	/**
	 * @param string $file
	 */
	public function save($file) {
		file_put_contents($file, $this->__toString());
	}

}
