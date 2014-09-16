<?php
/*
 # Copyright (C) 2014 Radoslav Vitanov
#
# Permission is hereby granted, free of charge, to any person obtaining a copy of this
# software and associated documentation files (the "Software"), to deal in the Software
# without restriction, including without limitation the rights to use, copy, modify, merge,
# publish, distribute, sublicense, and/or sell copies of the Software, and to permit
# persons to whom the Software is furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in all copies or
# substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING
# BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
# DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

namespace Vi\Url;
use Vi\Exception\UrlException;

/**
 * Class declaration
 * @author Radoslav Vitanov
 * @since 15.09.2014
 */
class Url {
	
	const URL_SCHEME_SEPARATOR 		= '://';
	const URL_PORT_SEPARATOR 		= ':';
	const URL_PATH_SEPARATOR 		= '/';
	const URL_FRAGMENT_SEPARATOR 	= '#';
	const URL_QUERY_GLUE			= '?';
	const URL_QUERY_SEPARATOR		= '&';
	const URL_QUERY_PAIR_GLUE		= '=';
	const URL_AUTH_SEPARATOR		= '@';
	
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
	 * Holds url frament
	 * @var string
	 */
	protected $_fragment = null;
	
	/**
	 * Holds array with query parameters for the url
	 * @var array
	 */
	protected $_queryParams = array();
	
	/**
	 * Flag to force www on domain
	 * @var boolean
	 */
	protected $_forceWWW = false;
	
	/**
	 * Class constructor
	 * @param string $url
	 */
	public function __construct($url = '') {
		if (!empty($url)) {
			$parts = parse_url($url);
			
			if (isset($parts['scheme'])) {
				$this->setScheme($parts['scheme']);
			}
			
			if (isset($parts['host'])) {
				$this->setDomain($parts['host']);
			}
			
			if (isset($parts['user'])) {
				$this->setUsername($parts['user']);
			}
			
			if (isset($parts['pass'])) {
				$this->setPassword($parts['pass']);
			}
			
			if (isset($parts['path'])) {
				$this->setPath($parts['path']);
			}
			
			if (isset($parts['query'])) {
				$this->setQueryParams($parts['query']);
			}
			
			if (isset($parts['fragment'])) {
				$this->setFragment($parts['fragment']);
			}
			
			if (isset($parts['port'])) {
				$this->setPort($parts['port']);
			}
		}
	}
	
	/**
	 * Force domain to be www
	 * @param boolean $boolean
	 * @return \Vi\Url\viUrl
	 */
	public function setForceWWW($boolean) {
		$this->_forceWWW = $boolean;
		return $this;
	}
	
	/**
	 * Get force www url flag
	 * @return boolean
	 */
	public function getForceWWW() {
		return $this->_forceWWW;
	}
	
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
		$prefix = '';
		if ($this->getForceWWW() && substr($this->_domain, 0, 3) != 'www') {
			$prefix = 'www.';
		}
		return $prefix . $this->_domain;
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
	 * Set query params for this url
	 * @param array $params
	 * @return \Vi\Url\viUrl
	 */
	public function setQueryParams($params) {
		if (!is_array($params)) {
			$tmp = explode(self::URL_QUERY_SEPARATOR, $params);
			
			$newParams = array();
			foreach ($tmp as $plain) {
				$parts = explode(self::URL_QUERY_PAIR_GLUE, $plain);
				$newParams[$parts[0]] = $parts[1];
			}
			$params = $newParams;
		}
		
		if (!is_array($params)) {
			throw new \UrlException("Invalid parameter passed to viUrl::setQueryParams. Expecting array.");
		}
		
		$this->_queryParams = $params;
		return $this;
	}
	
	/**
	 * Get query params for this url
	 * @return array
	 */
	public function getQueryParams() {
		return $this->_queryParams;
	}
	
	/**
	 * Get query param
	 * @param unknown $paramName
	 * @return Ambigous <NULL, multitype:>
	 */
	public function getQueryParam($paramName) {
		return (isset($this->_queryParams[$paramName]) ? $this->_queryParams[$paramName] : null);
	}
	
	/**
	 * Add query parameter
	 * @param string $paramName
	 * @param string $paramValue
	 * @param boolean $overwrite
	 * @return \Vi\Url\viUrl
	 */
	public function setQueryParam($paramName, $paramValue, $overwrite = true) {
		if (!isset($this->_queryParams[$paramName]) || $overwrite) {
			$this->_queryParams[$paramName] = $paramValue;
		}
		
		return $this;
	}
	
	/**
	 * Unset query parameter
	 * @param string $paramName
	 * @return \Vi\Url\viUrl
	 */
	public function removeQueryParam($paramName) {
		if (isset($this->_queryParams[$paramName])) {
			unset($this->_queryParams[$paramName]);
		}
		
		return $this;
	}
	
	/**
	 * Set fragment for this url
	 * @param string $fragment
	 * @return \Vi\Url\viUrl
	 */
	public function setFragment($fragment) {
		$this->_fragment = $fragment;
		return $this;
	}
	
	/**
	 * Get url fragment
	 * @return string
	 */
	public function getFragment() {
		return $this->_fragment;
	}
	
	/**
	 * Set path for this Url
	 * @param array|string $path
	 * @return \Vi\Url\viUrl
	 */
	public function setPath($path) {
		// Make sure to always assign array. Convert strings like /my/web/path to array.
		if (!is_array($path)) {
			$path = trim($path, self::URL_PATH_SEPARATOR);
			$path = explode(self::URL_PATH_SEPARATOR, $path);
		}
		
		if (!is_array($path)) {
			throw new \UrlException("Invalid parameter passed to viUrl::setPath. Expecting array.");
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
			if ($this->_stringsAreSame($pathToFind, $pathName, $caseSensitive)) {
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
	
	/**
	 * Replace path fragment with another
	 * @param string $pathToFind
	 * @param string $pathToReplace
	 * @return \Vi\Url\viUrl
	 */
	public function replacePath($pathToFind, $pathToReplace) {
		$this->findPath($pathToFind);
		
		if (isset($this->_path[$this->_currentPathIndex])) {
			$this->_path[$this->_currentPathIndex] = $pathToReplace;
		}
		
		return $this;
	}
	
	/**
	 * Inserts path fragment before another fragment
	 * @param string $fragmentToFind
	 * @param string $fragmentToInsert
	 * @return \Vi\Url\viUrl
	 */
	public function insertPathBefore($fragmentToFind, $fragmentToInsert) {
		$this->findPath($fragmentToFind);
		
		$firstPart = array_slice($this->_path, 0, $this->_currentPathIndex);
		$secondPart = array_slice($this->_path, $this->_currentPathIndex);
		
		$this->_path = array_merge($firstPart, array($fragmentToInsert), $secondPart);
		return $this;
	}
	
	/**
	 * Insert fragment after another path fragment
	 * @param string $fragmentToFind
	 * @param string $fragmentToInsert
	 * @return \Vi\Url\viUrl
	 */
	public function insertPathAfter($fragmentToFind, $fragmentToInsert) {
		$this->findPath($fragmentToFind);
	
		$firstPart = array_slice($this->_path, 0, ($this->_currentPathIndex + 1));
		$secondPart = array_slice($this->_path, ($this->_currentPathIndex + 1));
	
		$this->_path = array_merge($firstPart, array($fragmentToInsert), $secondPart);
		return $this;
	}
	
	/**
	 * Delete path
	 * @param string $pathName
	 * @return \Vi\Url\viUrl
	 */
	public function deltePath($pathName) {
		$this->findPath($pathName);
		if (isset($this->_path[$this->_currentPathIndex])) {
			unset($this->_path[$this->_currentPathIndex]);
		}
		return $this;
	}
	
	/**
	 * Builds an Url
	 * @return string
	 */
	public function buildUrl() {
		$url = '';
		
		$url .= $this->getScheme() . self::URL_SCHEME_SEPARATOR;
		
		if (!empty($this->_userName) && !empty($this->_password)) {
			$url .= $this->getUsername() . self::URL_PORT_SEPARATOR . $this->getPassword() . self::URL_AUTH_SEPARATOR;
		}
		
		$url .= $this->getDomain();
		
		if (!empty($this->_port)) {
			$url .= self::URL_PORT_SEPARATOR . $this->getPort();
		}
		
		if (!empty($this->_path)) {
			$url .= self::URL_PATH_SEPARATOR . join(self::URL_PATH_SEPARATOR, $this->getPath());
		}
		
		if (!empty($this->_queryParams)) {
			$url .= self::URL_QUERY_GLUE . http_build_query($this->_queryParams);
		}
		
		if (!empty($this->_fragment)) {
			$url .= self::URL_FRAGMENT_SEPARATOR . $this->getFragment();
		}
		return $url;
	}
	
	/**
	 * Redirects to the current url
	 * @param number $status
	 */
	public function redirect($status = 301) {
		$url = $this->buildUrl();
		
		if (headers_sent()) {
			echo '<script type="text/javascript">window.location="' . $url . '";</script>';
		} else {
			header('Status: ' . $status);
			header('Location: ' . $url);
		}
		
		die;
	}
}