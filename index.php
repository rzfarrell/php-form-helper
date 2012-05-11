<?php
include('php-form-helper.php');

$form_options = array 	(	'test_form'		=> array('type' => 'form', 'method' => 'post', 'url' => '', 'class' => 'form-horizontal'),
							'contact'		=> array('type' => 'text', 'required' => true, 'label' => 'Contact', 'max' => 40),
							'address1'		=> array('type' => 'text', 'required' => true, 'label' => 'Address 1', 'max' => 40),
							'address2'		=> array('type' => 'text', 'required' => true, 'label' => 'Address 2', 'max' => 40),
							'city'			=> array('type' => 'text', 'required' => true, 'label' => 'City', 'max' => 25),
							'state'			=> array('type' => 'text', 'required' => true, 'label' => 'State', 'max' => 2, 'class' => 'input-mini'),
							'zip'			=> array('type' => 'text', 'required' => true, 'label' => 'Zip', 'max' => 10, 'class' => 'input-mini'),
							'phone'			=> array('type' => 'text', 'required' => true, 'label' => 'Phone', 'max' => 12, 'validate' => array('phone'))
						);

try {
    $form = new PHPFormHelper($form_options);
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

$form->display();
