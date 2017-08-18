<?php
class Form_validation {

	public $form_data;
	public function __construct()
	{
		$this->form_data = array();
		$this->error_message = array();
		$this->error_status = true;
	}
	
	/**
	 * @param string
 	 *
	 * return bool
	 */
	private function required($str)
	{
		return is_string($str) ? (trim($str)!=='') : false;
	}	
	
	private function matches($field, $match_key, $match_value)
	{
		if(empty($this->form_data[$match_key])) return 'The field nameed "'.$match_key.'" is empty.';
		if($this->form_data[$field]==$this->form_data[$match_key]) return true;
		return false;
	}
	
	/**
	 * @param int $match_value
	 */
	private function min_length($field, $match_key, $match_value)
	{
		if(!is_numeric($match_key)) return 'Pleas input a number.';
		if(mb_strlen($match_value) >= $match_key) return true;
		return false;	
	}
	
	/**
	 * @param int $matchy_value
	 */
	private function max_length($field, $match_key, $match_value)
	{
		if(!is_numeric($match_key)) return 'Pleas input a number.';
		if(mb_strlen($match_value) <= $match_key) return true;
		return false;
	}

	private function numeric($str)
	{
		return (bool) preg_match('/^[\-+]?[0-9]*\.?[0-9]+$/', $str);
	}

	private function integerr($str)
	{
		return (bool) preg_match('/^[\-+]?[0-9]+$/', $str);
	}

	private function is_natural($str)
	{
		return ctype_digit((string) $str);
	}

	private function is_natural_no_zero($str)
	{
		return ($str != 0 && ctype_digit((string) $str));
	}

	private function valid_url($str)
	{
		if (empty($str)) {
			return false;
		} elseif (preg_match('/^(?:([^:]*)\:)?\/\/(.+)$/', $str, $matches)) {
			if (empty($matches[2])) {
				return false;
			} elseif ( ! in_array(strtolower($matches[1]), array('http', 'https'), true)) {
				return false;
			}

			$str = $matches[2];
		}

		// PHP 7 accepts IPv6 addresses within square brackets as hostnames,
		// but it appears that the PR that came in with https://bugs.php.net/bug.php?id=68039
		// was never merged into a PHP 5 branch ... https://3v4l.org/8PsSN
		if (preg_match('/^\[([^\]]+)\]/', $str, $matches) && ! is_php('7') && filter_var($matches[1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
			$str = 'ipv6.host'.substr($str, strlen($matches[1]) + 2);
		}

		return (filter_var('http://'.$str, FILTER_VALIDATE_URL) !== false);
	}

	private function valid_email($str)
	{
		if (function_exists('idn_to_ascii') && sscanf($str, '%[^@]@%s', $name, $domain) === 2) {
			$str = $name.'@'.idn_to_ascii($domain);
		}

		return (bool) filter_var($str, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * @param string value
	 * @param string list
	 */
	private function in_list($field, $list, $value)
	{
		return in_array($value, explode(',', $list), true);
	}

	private function check_rules($rules, $field, $errors)
	{
		if(!is_string($rules) || !$rules || !is_string($field) || !$field) return false;
		$rule_arr = preg_split('/\|(?![^\[]*\])/', $rules);// "matches[password]" array[0]="matches[password]" array[1]="matches" array[2]="password"
		
		$error_str = '';
		$error_status = true;
		foreach($rule_arr as $k=>$rule) {
			if(!is_string($rule) || $rule==='') continue;
		    preg_match('/(.*?)\[(.*)\]/', $rule, $match);
			if(count($match)==3) {
				if(!$match[1] || !$match[2]) continue;
				$rule = $match[1];
				$param = $match[2];
				$flag = $this->$rule($field, $param, $this->form_data[$field]);
				if($flag===true) {
					//$error_str .= '';
				} elseif($flag === false) {
					$error_status = false;
					$error_str .= (empty($errors[$rule]) ? '': $errors[$rule]);
				} else {
					$error_status = false;
					$error_str .= $flag;	
				}
			} else {
				// callback
				if(is_string($rule) && strncmp('callback_', $rule, 9) === 0) {
					if (strpos($rule, 'callback_') === 0) {
						$call_rule = substr($rule, 9);
						
					}
					if(!empty($call_rule) && is_callable($call_rule)) {
						$param = $this->form_data[$field];
						$flag = $call_rule($param);
						if($flag) {
							$error_status = false;
							$error_str .=(empty($errors[$rule]) ? '': $errors[$rule]);			
						}
					}
					continue;
				}

				// php system func
				if(is_callable($rule)) {
					$str = $this->form_data[$field] = $rule($this->form_data[$field]);
				
				// valid func
				} else {
					if(!method_exists($this, $rule)) {
						//echo 'nimei' . $rule;
						$error_status = false;
						$error_str = (empty($errors[$rule]) ? 'Valid method named "'.$rule.'" is not exist.': $error_str.$errors[$rule]);
						continue;
					} 
					$flag = $this->$rule($this->form_data[$field]);
					if(!$flag) {
						$error_status = false;
						$error_str = (empty($errors[$rule]) ? '' : $error_str.$errors[$rule]);
					}
				}
			}
		}
		return array('error_str'=>$error_str, 'error_status'=>$error_status);
	}

	/**
     * 表单验证入口
	 * @param array $config 验证规则
     * @return
	 */
	public function set_rules($config)
	{
		if(!is_array($config) || count($config)<1) {
			return $this;
		}		
		foreach($config as $k=>$v) {
			if(!isset($v['field'], $v['rules'])) continue;
			if($v['rules']) {
				$arr = $this->check_rules($v['rules'], $v['field'], isset($v['errors'])? $v['errors'] : false);
				$flag = $arr['error_status'];
				$msg = $arr['error_str'];
				if($flag === false) {
					$this->error_status = false;
					$this->set_message($v['field'], $msg);
				}
			}
		}
	}

	public function set_message($lang, $val)
	{
		if(!is_array($lang)) {
			$lang = array($lang=>$val);
		}
		$this->error_message = array_merge($this->error_message, $lang);
	}

	public function reset_error_message()
	{
		$this->error_message = array();
	}
}

