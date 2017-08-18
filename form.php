<?php
error_reporting(1);
require_once "functions.php";
require_once "validationClass.php";
$config = array(
	array(
		'field' => 'url',
		'rules' => 'bucunzai|required|valid_url',
		'errors' => array(
			'required' => 'You must provide a url.',
			'valid_url' => 'Not url.<script>alert(1);</script>--',
		)
	),
	array(
		'field' => 'gender',
		'rules' => 'required|in_list[nan,nv]',
		'errors' => array(
			'required' => 'You must provide a gender.',
			'in_list'  => 'Not in list.',
		)
	),
	array(
		'field' => 'age',
		'rules' => 'required|is_natural_no_zero|max_length[3]|min_length[1]',
		'errors'=> array(
			'required' => 'You must provide a age.', 
			'max_length' => 'Must less than 3.',
			'min_lehgth' => 'Must more than 1.',
			'is_natural_no_zero' => 'Must natural &>0', 
		),

	),
    array(
        'field' => 'username',
        'label' => 'Username',
        'rules' => 'callback_checkname|trim|required',
		'errors'=> array(
			'required'=>'You must provide a username.',
			'callback_checkname'=>'该用户名已被注册了亲。'
		)
    ),
    array(
        'field' => 'password',
        'label' => 'Password',
       	'rules' => 'required|min_length[5]|max_length[7]',
		 'errors' => array(
            'required' => 'You must provide a password.',
			'min_length' => 'MIN:Less 7 and greater 5.',
			'max_length' => 'MAX:Less 7 and greater 5.'
        ),
    ),
    array(
        'field' => 'passconf',
        'label' => 'Password Confirmation',
		'rules' => 'required|matches[password]|min_length[5]|max_length[7]',
		'errors' => array('required'=>'You must provide a passconf.',
						 'matches'=>'Not match with field of password.',
						 'min_length' => 'MIN:Less 7 and greater 5.',
						 'max_length' => 'MAX:Less 7 and greater 5.')
    ),
    array(
        'field' => 'email',
        'label' => 'Email',
        'rules' => 'required|valid_email',
		'errors' => array(
			'required' => 'You must provide an email.',
			'valid_email' => 'Not valid email.'
		)
		
    )
);

/**
 * 处理表单提交
 */

$flag =	validation_run($config);
$errorall = validation_form_all_error();
echo "<br/><br/>";var_dump($errorall);echo "<br/><br/>";

function checkname($param){
	if($param=='wangjing'||$param=='test') {
		return '已注册.';
	}
}

if($flag) {
	echo "<span style='color:#9CC96B'><b><br/>".'Success'."<b/></span>";
} else {
	echo "<span style='color:red'><b><br/>".'Faile'."<b/></span>";
}

require "myform.php";