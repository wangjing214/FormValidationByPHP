<?php
function validation_run($field, $lable='', $rules=array(), $errors=array()) {
	global $validationObj;
	$validationObj =  new Form_validation();
	$validationObj->form_data = $_REQUEST;
	$validationObj->reset_error_message();
	$validationObj->set_rules($field, $lable='', $rules=array(), $errors=array());
	
	$all_error_message = implode(array_values($validationObj->error_message));
	$validationObj->all_error_message = $all_error_message;
	return $validationObj->error_status;
}

function validation_form_all_error()
{
	global $validationObj;
	return htmlspecialchars($validationObj->all_error_message);
}

function validation_form_error($value)
{
	global $validationObj;
	$data = $validationObj->error_message;
	return $message = (empty($data[$value])) ? '' : htmlspecialchars($data[$value]);
}

function validation_set_value($value)
{
	global $validationObj;
	$data = $validationObj->form_data;
	return $message = (empty($data[$value])) ? '' : $data[$value];
}

function validation_data()
{
	global $validationObj;
	return $validationObj->form_data;
}
