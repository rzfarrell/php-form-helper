php-form-helper
===============

php class to help generate and validate forms

Sample Usage:

<pre><code>
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
							'state'			=> array('type' => 'text', 'required' => true, 'label' => 'State', 'max' => 2, 'class' => 'input-mini'),
							'zip'			=> array('type' => 'text', 'required' => true, 'label' => 'Zip', 'max' => 10, 'class' => 'input-mini'),
							'phone'			=> array('type' => 'text', 'required' => true, 'label' => 'Phone', 'max' => 12, 'validate' => array('phone')),
							'cust_2_po_req'	=> array('type' => 'radio', 'required' => true, 'label' => 'P.O. Required', 'options' => array('N' => 'No', 'Y' => 'Yes')),
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
	if ($form->validate())
	{
		$clean = $form->getClean();
	}
	else
	{
		$error_message = $form->displayErrors();
	}
}

echo $error_message;


$form->display($_POST);
</code></pre>