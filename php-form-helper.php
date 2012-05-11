<?php
class PHPFormHelper
{
	$errors = array();
	$arguments = array();
	function __construct($arguments = '') {
		if (!is_array($arguments))
		{
			throw new Exception('invalid PHPFormHelper arguments');				
		}
		else
		{
			$this->arguments = $arguments;
		}
	}
}


