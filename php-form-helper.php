<?php
class PHPFormHelper
{
	public $errors = array();
	private $arguments = array();
	private $main = array();
	private $alternate = array();
	
	function __construct($arguments = '') {
		if (!is_array($arguments))
		{
			throw new Exception('invalid PHPFormHelper arguments');
		}
		else if (empty($arguments))
		{
			throw new Exception('invalid PHPFormHelper arguments');
		}
		else
		{
			$this->arguments = $arguments;
		}
	}
	
	function display($main = '', $alternate = '')
	{
		$this->main = $main;
		$this->alternate = $alternate;
		
		
		$count = 0;
		foreach ($this->arguments as $name => $options)
		{
			if ($count == 0 && (!isset($options['type']) || $options['type'] != 'form'))
			{
				echo 'must start arguments with a form type<br>';
				return false;
			}
			if (!is_array($options)) continue;
			if (!isset($options['type'])) continue;
			
			
			
			switch ($options['type'])
			{
				case 'form':
					$this->createForm($name, $options);
					break;
				case 'text':
					$this->createText($name, $options);
					break;
					
			}			
			$count++;
		}
		echo '</form>';
	}
	
	function createForm($name, $options)
	{
		if (!isset($options['method'])) $options['method'] = 'post';
		echo '<form method="'.$options['method'].'" action="'.$options['url'].'" class="'.$options['class'].'" name="'.$name.'" id="'.$name.'">';
	}
	
	function createText($name, $options)
	{
		if (!isset($options['label'])) $options['label'] = $name;
		
		echo '<div class="control-group">';
			echo '<label class="control-label" for="'.$name.'">'.$options['label'];
				if (isset($options['required'])) echo ' <span class="required">*</span>';
			echo '</label>';
			echo '<div class="controls">';
				echo '<input name="'.$name.'" id="'.$name.'" type="text"';
					if (isset($this->main[$name])) echo ' value="'.htmlspecialchars($this->main[$name]).'"';
					else if (isset($this->alternate[$name])) echo ' value="'.htmlspecialchars($this->alternate[$name]).'"';
					else echo ' value=""';
					if (isset($options['max']) && ctype_digit($options['max'])) echo ' maxlength="'.$options['max'].'"';
					if (isset($options['class'])) echo ' class="'.$options['class'].'"';
				echo '>';
			echo '</div>';
		echo '</div>';
	}
	
}


