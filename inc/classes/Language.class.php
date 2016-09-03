<?php
/**
 * Language 
 * @author Timothy Gibbons <tjgibbons10@gmail.com>
 * @copyright Timothy Gibbons Copyright (c) 2016;
 * @license https://github.com/Root3287/Social-Media/blob/master/LICENSE MIT
 */
class Language{
	/**
	 * Default Language
	 * @var String
	 */
	private $_language;

	/**
	 * Language Array
	 * @var array
	 */
	private $_languageText;

	private static $_instance = null;

	/**
	 * Constructor
	 * @param string $language Language Code
	 */
	public function __construct($lang = 'temp'){
		$this->_language = $lang;
	}

	public static function getInstance($lang = 'temp'){
		if(!isset(self::$_instance)) {
			self::$_instance = new Language($lang);
		}
		return self::$_instance;
	}

	/**
	 * Add a Language
	 * @param string $language Language Code
	 * @param array  $lang     Language array to translate from.
	 */
	public function add($language, $lang = array()){
		$this->_languageText[$language] = $lang;
	}

	/**
	 * Get Language
	 * @param  string $lang Language Code
	 * @return array        Language Text
	 */
	public function get($string){
		$lang = $this->_language;
		return $this->_languageText[$lang][$string];
	}
}