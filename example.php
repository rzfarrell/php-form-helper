<?php
session_start();
include('php-form-helper.php');
$error_message = '';
$form_options = array 	(	'test_form'		=> array('type' => 'form', 'method' => 'post', 'url' => '', 'class' => 'form-horizontal'),
							'action'		=> array('type' => 'hidden', 'value' => 'post_form'),
							'contact'		=> array('type' => 'text', 'required' => true, 'label' => 'Contact', 'max' => 40),
							'email'			=> array('type' => 'text', 'required' => true, 'label' => 'Email', 'valid' => 'email'),
							'address1'		=> array('type' => 'text', 'required' => true, 'label' => 'Address 1', 'max' => 40),
							'address2'		=> array('type' => 'text', 'required' => true, 'label' => 'Address 2', 'max' => 40),
							'city'			=> array('type' => 'text', 'required' => true, 'label' => 'City', 'max' => 25),
							'state'			=> array('type' => 'text', 'required' => true, 'label' => 'State', 'max' => 2, 'min' => 2, 'class' => 'input-mini'),
							'zip'			=> array('type' => 'text', 'required' => true, 'label' => 'Zip', 'max' => 10, 'class' => 'input-mini'),
							'phone'			=> array('type' => 'text', 'required' => true, 'label' => 'Phone', 'max' => 12, 'validate' => array('phone')),
							'cust_2_po_req'	=> array('type' => 'radio', 'required' => true, 'class' => 'inline', 'label' => 'P.O. Required', 'options' => array('N' => 'No', 'Y' => 'Yes')),
							'cust_2_po_req2'=> array('type' => 'checkbox', 'required' => true, 'label' => 'Checkbox Required', 'options' => array('N' => 'No', 'Y' => 'Yes')),
							'active'		=> array('type' => 'select', 'required' => true, 'label' => 'Account Active', 'options' => array('1' => 'Yes', '0' => 'No')),
							'submit'		=> array('type' => 'submit', 'label' => 'Save')
						);

try {
    $form = new PHPFormHelper($form_options);
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
    exit;
}

if (isset($_POST['action']) && $_POST['action'] == 'post_form')
{
	/*
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	*/
	if ($form->validate())
	{
		$clean = $form->getClean();
	}
	else
	{
		$error_message = $form->displayErrors();
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>PHP Form Helper</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	
	<!-- Le styles -->
	<link href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css" rel="stylesheet">
	<style>
		body {
			padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
		}
		.control-group.error label,
		.control-group.error .help-block,
		.control-group.error .help-inline {
			color: #b94a48;
		}
    </style>
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
	<div class="container">
		<?php  	
		echo $error_message;
		$form->display($_POST);
		?>
	</div>
</body>
</html>