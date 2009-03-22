<?php
/***********************************************************************
| Cerberus Helpdesk(tm) developed by WebGroup Media, LLC.
|-----------------------------------------------------------------------
| All source code & content (c) Copyright 2008, WebGroup Media LLC
|   unless specifically noted otherwise.
|
| This source code is released under the Cerberus Public License.
| The latest version of this license can be found here:
| http://www.cerberusweb.com/license.php
|
| By using this software, you acknowledge having read this license
| and agree to be bound thereby.
| ______________________________________________________________________
|	http://www.cerberusweb.com	  http://www.webgroupmedia.com/
***********************************************************************/
/**
 * @author Jeff Standen <jeff@webgroupmedia.com>
 */

class Cerb4_WebApi {
	private $_access_key = '';
	private $_secret_key = '';

	private $_url = '';
	
	private $_content_type = '';
	
	public function __construct($access_key, $secret_key) {
		$this->_access_key = $access_key;
		$this->_secret_key = $secret_key;
	}
	
	public function get($url) {
		return $this->_connect('GET', $url);
	}
	
	public function put($url,$payload) {
		return $this->_connect('PUT', $url, $payload);
	}
	
	public function post($url,$payload) {
		return $this->_connect('POST', $url, $payload);
	}
	
	public function delete($url) {
		return $this->_connect('DELETE', $url);
	}
	
	public function getContentType() {
		return $this->_content_type;
	}
	
	/**
	 * Generate an RFC-compliant Message-ID
	 */
	public static function generateMessageId() {
		$message_id = sprintf('<%s.%s@%s>', base_convert(time(), 10, 36), base_convert(rand(), 10, 36), !empty($_SERVER['HTTP_HOST']) ?  $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']);
		return $message_id;
	}
	
	public static function getTemplateEngine() {
		static $instance = null;
		if(null == $instance) {
			require('libs/smarty/Smarty.class.php');
			$instance = new Smarty();
			$instance->template_dir = 'templates';
			$instance->compile_dir = 'libs/tmp/templates_c';
			$instance->cache_dir = 'libs/tmp/cache';
			
			$instance->caching = 0;
			$instance->cache_lifetime = 0;
		}
		return $instance;
	}
	
	public static function importGPC($var,$cast=null,$default=null) {
	    if(!is_null($var)) {
	        if(is_string($var)) {
	            $var = get_magic_quotes_gpc() ? stripslashes($var) : $var;
	        } elseif(is_array($var)) {
                foreach($var as $k => $v) {
                    $var[$k] = get_magic_quotes_gpc() ? stripslashes($v) : $v;
                }
	        }
	        
	    } elseif (is_null($var) && !is_null($default)) {
	        $var = $default;
	    }
	    	
	    if(!is_null($cast))
	        @settype($var, $cast);

	    return $var;
	}

	private function _sortQueryString($query) {
		// Strip the leading ?
		if(substr($query,0,1)=='?') $query = substr($query,1);
		$args = array();
		$parts = explode('&', $query);
		foreach($parts as $part) {
			$pair = explode('=', $part, 2);
			if(is_array($pair) && 2==count($pair))
				$args[$pair[0]] = $part;
		}
		ksort($args);
		return implode("&", $args);
	}
	
	private function _connect($verb, $url, $payload=null) {
		$header = array();
		$ch = curl_init();

		$verb = strtoupper($verb);
		$http_date = gmdate(DATE_RFC822);

		$header[] = 'Date: '.$http_date;
		
		// HTTP verb-specific options
		switch($verb) {
			case 'DELETE':
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;

			case 'GET':
				break;
				
			case 'PUT':
				$header[] = 'Content-Type: text/xml;';
				$header[] = 'Content-Length: ' .  strlen($payload);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
				break;
				
			case 'POST':
				$header[] = 'Content-Type: text/xml;';
				$header[] = 'Content-Length: ' .  strlen($payload);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
				break;
		}

		// Authentication
		$url_parts = parse_url($url);
		$url_path = $url_parts['path'];
		$url_query = $this->_sortQueryString($url_parts['query']);
		
		$string_to_sign = "$verb\n$http_date\n$url_path\n$url_query\n$payload\n$this->_secret_key\n";
		$hash = base64_encode(sha1($string_to_sign, true));
		$header[] = 'Cerb4-Auth: '.sprintf("%s:%s",$this->_access_key,$hash);
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$output = curl_exec($ch);
		
		$info = curl_getinfo($ch);
		$this->_content_type = $info['content_type'];
		
		curl_close($ch);
		
		return $output;
	}
};
?>