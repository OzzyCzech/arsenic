<?php
/**
 * Simple Selenium IDE Flow generator
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 *
 * @method SeleniumFlow open
 * @method SeleniumFlow verifyElementPresent
 * @method SeleniumFlow verifyElementNotPresent
 * @method SeleniumFlow verifyTextPresent
 * @method SeleniumFlow assertTitle
 * @method SeleniumFlow deleteCookie
 * @method SeleniumFlow waitForPageToLoad
 * @method SeleniumFlow clickAndWait
 * @method SeleniumFlow click
 * @method SeleniumFlow chooseOkOnNextConfirmation
 * @method SeleniumFlow assertConfirmation
 * @method SeleniumFlow waitForVisible
 * @method SeleniumFlow type
 * @method SeleniumFlow store
 *
 * etc..
 *
 * @todo store valid HTML Selenium IDE test
 * @todo autogenerate selenium suit from tests
 */
class SeleniumFlow {

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
	 * @return SeleniumFlow
	 */
	public function __call($method, array $args) {
		$this->flow[] = sprintf(
			'<tr><td>%s</td><td>%s</td><td>%s</td></tr>', $method, htmlentities($args[0]),
			isset($args[1]) ? htmlentities($args[1]) : ''
		);
		return $this;
	}

	/**
	 * @param SeleniumFlow $seleniumFlow
	 * @return SeleniumFlow
	 */
	public function inc(SeleniumFlow $seleniumFlow) {
		$this->flow[] = (string)$seleniumFlow;
		return $this;
	}

	/**
	 * @param string $text
	 * @return SeleniumFlow
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
