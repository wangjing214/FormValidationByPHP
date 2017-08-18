<?php
error_reporting(1);
require_once "functions.php";
?>
<html>
<head>
    <title>My Form</title>
<style>
.error{
	color:red;
}
</style>
</head>
<body>
<form method="post" action='form.php'>
<div><input type="submit" value="Submit" /></div>

<h5> Url</h5>
<span class='error'><?php echo validation_form_error('url');?></span>
<br/><input type="text" name='url' value="<?php echo validation_set_value("url"); ?>" >

<h5> Gender</h5>
<span class='error'><?php echo validation_form_error('gender'); ?></span>
<br/><input type="text" name="gender" value="<?php echo validation_set_value("gender");?>" size="50">

<h5> Age </h5>
<span class='error'><?php echo validation_form_error('age'); ?></span><br/>
<input type="text" name="age" value="<?php echo validation_set_value('age')?>" size="50" >

<h5> Username(本例中wangjing和test用户已经注册，留意提示。)</h5>
<span class='error'><?php echo validation_form_error('username'); ?></span><br/>
<input type="text" name="username" value="<?php echo validation_set_value('username'); ?>" size="50" />

<h5> Password</h5>
<span class='error'><?php echo validation_form_error('password'); ?></span><br/>
<input type="text" name="password" value="<?php echo validation_set_value('password'); ?>" size="50" />

<h5> Password Confirm</h5>
<span class='error'><?php echo validation_form_error('passconf'); ?></span><br/>
<input type="text" name="passconf" value="<?php echo validation_set_value('passconf'); ?>" size="50" />

<h5> Email Address</h5>
<span class='error'><?php echo validation_form_error('email'); ?></span><br/>
<input type="text" name="email" value="<?php echo validation_set_value('email'); ?>" size="50" />

<div><input type="submit" value="Submit" /></div>

</form>

</body>
</html>
