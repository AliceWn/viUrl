<?php

namespace Vi\Url;

/**
 * Class declaration
 * @author Radoslav Vitanov
 * @since 15.09.2014
 */
class viUrl {
	
	const URL_SCHEME_SEPARATOR = '://';
	const URL_PORT_SEPARATOR = ':';
	const URL_PATH_SEPARATOR = '/';
	
	/**
	 * Url scheme
	 * @var string
	 * @see http://en.wikipedia.org/wiki/URI_scheme
	 * @see http://www.iana.org/assignments/uri-schemes/uri-schemes.xhtml
	 */
	protected $_scheme = null;
	
	/**
	 * Url username
	 * @var string
	 * @see http://en.wikipedia.org/wiki/URI_scheme
	 */
	protected $_userName = null;
	
	/**
	 * Url password
	 * @var string
	 * @see http://en.wikipedia.org/wiki/URI_scheme
	 */
	protected $_password = null;
	
	/**
	 * Url domain
	 * @var string
	 * @see http://en.wikipedia.org/wiki/URI_scheme
	 */
	protected $_domain = null;
	
	/**
	 * Url port
	 * @var string
	 * @see http://en.wikipedia.org/wiki/URI_scheme
	 */
	protected $_port = null;
	
	/**
	 * Url path. 
	 * www.example.com/my/web/path => array('my', 'web', 'path');
	 * @var array
	 */
	protected $_path = array();
	
	/**
	 * Holds the position of the element in $_path, previously found by viUrl::findPath()
	 * @var int
	 */
	protected $_currentPathIndex = null;
	
	/**
	 * Set scheme for the Url
	 * @param string $scheme
	 * @return \Vi\Url\viUrl
	 */
	public function setScheme($scheme) {
		$this->_scheme = (string)$scheme;
		return $this;
	}
	
	/**
	 * Get current scheme
	 * @return string
	 */
	public function getScheme() {
		return $this->_scheme;
	}
	
	/**
	 * Sets a username
	 * @param string $username
	 * @return \Vi\Url\viUrl
	 */
	public function setUsername($username) {
		$this->_userName = (string)$username;
		return $this;
	}
	
	/**
	 * Gets username
	 * @return string
	 */
	public function getUsername() {
		return $this->_userName;
	}
	
	/**
	 * Sets a password
	 * @param string $password
	 * @return \Vi\Url\viUrl
	 */
	public function setPassword($password) {
		$this->_password = $password;
		return $this;
	}
	
	/**
	 * Get password
	 * @return string
	 */
	public function getPassword() {
		return $this->_password;
	}
	
	/**
	 * Sets a domain for this url
	 * @param string $domain
	 * @return \Vi\Url\viUrl
	 */
	public function setDomain($domain) {
		$this->_domain = (string)$domain;
		return $this;
	}
	
	/**
	 * Get domain
	 * @return string
	 */
	public function getDomain() {
		return $this->_domain;
	}
	
	/**
	 * Set port
	 * @param string $port
	 * @return \Vi\Url\viUrl
	 */
	public function setPort($port) {
		$this->_port = (string)$port;
		return $this;
	}
	
	/**
	 * Get port
	 * @return string
	 */
	public function getPort() {
		return $this->_port;
	}
	
	/**
	 * Set path for this Url
	 * @param array|string $path
	 * @return \Vi\Url\viUrl
	 */
	public function setPath($path) {
		// Make sure to always assign array. Convert strings like /my/web/path to array.
		if (!is_array($path)) {
			$path = explode(self::URL_PATH_SEPARATOR, $path);
		}
		
		if (!is_array($path)) {
			throw new \viUrlException("Invalid parameter passed to viUrl::setPath. Expecting array.");
		}
		
		$this->_path = $path;
		return $this;
	}
	
	/**
	 * Get current path
	 * @return array
	 */
	public function getPath() {
		return $this->_path;
	}
	
	/**
	 * Sets $_currentPathIndex to the index of the $_path that matches the name.
	 * @param string $pathToFind
	 * @param string $caseSensitive
	 * @return \Vi\Url\viUrl
	 */
	public function findPath($pathToFind, $caseSensitive = false) {
		$currentPathIndex = null;
		
		foreach ($this->_path as $pathIndex => $pathName) {
			if ($this->_stringsAreSame($pathToFind, $pathName, $caseSentitive)) {
				break;
			}
		}
		$this->_currentPathIndex = $pathIndex;
		return $this;
	}
	
	/**
	 * Compare if two strings are same
	 * @param string $nameOne
	 * @param string $nameTwo
	 * @param boolean $caseSentitive
	 * @return boolean
	 */
	protected function _stringsAreSame($nameOne, $nameTwo, $caseSentitive) {
		return (($caseSentitive) ? ($nameOne == $nameTwo) : (strtolower($nameOne) == strtolower($nameTwo)));
	}
	
	public function replacePath($pathToFind, $pathToReplace) {
		$this->findPath($pathToFind);
	}
}