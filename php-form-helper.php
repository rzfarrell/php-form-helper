<?php
class PHPFormHelper
{
	public $errors = array();
	private $arguments = array();
	private $main = array();
	private $alternate = array();
	private $require_form_tag = true;
	
	function __construct($arguments = '', $require_form_tag = true) {
		if (!is_array($arguments))
		{
			throw new Exception('invalid PHPFormHelper arguments');
		}
		else if (empty($arguments))
		{
			throw new Exception('invalid PHPFormHelper arguments');
		}
		else if (!isset($_SESSION))
		{
			throw new Exception('Sessions not started.');
		}
		else
		{
			$this->arguments = $arguments;
			$this->require_form_tag = $require_form_tag;
		}
	}
	
	function displayErrors()
	{
		$error = '';
		$error .= '<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">x</a><h4 class="alert-heading">Warning!</h4>';
		foreach ($this->errors as $name => $message)
		{
			$error .= $message;
		}
		$error .= '</div>';
		return $error;
	}
	
	function getClean()
	{
		$clean = array();
		foreach ($this->arguments as $name => $options)
		{
			if (!isset($_POST[$name])) continue;
			$clean[$name] = htmlspecialchars(trim($_POST[$name]));
		}
		return $clean;
	}
	
	function genRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$string = '';
		for ($p = 0; $p < $length; $p++)
		{
			$rand = mt_rand(0, strlen($characters)-1);
			$string .= substr($characters, $rand, 1);
		}
		return $string;
	}
		
	function validate()
	{
		if (!isset($_POST['form_id']))
		{
			$this->errors['form'] = 'Invalid form submission.<br>';
		}
		else if ($_SESSION['form_id'] != $_POST['form_id'])
		{
			$this->errors['form'] = 'Invalid form submission.<br>';
		}
		
		foreach ($this->arguments as $name => $options)
		{
			if (!is_array($options)) continue;
			if (!isset($options['type'])) continue;
			
			switch ($options['type'])
			{
				case 'text':
					$_POST[$name] = trim($_POST[$name]);
					$label = $name;
					if (isset($options['label'])) $label = $options['label'];
					if (isset($options['required']))
					{
						if (!isset($_POST[$name]) || trim($_POST[$name]) == '')
						{
							if (!isset($this->errors[$name]))
								$this->errors[$name] = $label.' is required.<br>';
						}
					}
					if (isset($options['max']))
					{
						if (strlen($_POST[$name]) > $options['max'])
						{
							if (!isset($this->errors[$name]))
								$this->errors[$name] = $label.' can not be longer than '.$options['max'].' characters.<br>';
						}
					}
					if (isset($options['min']))
					{
						if (strlen($_POST[$name]) < $options['min'])
						{
							if (!isset($this->errors[$name]))
								$this->errors[$name] = $label.' can not be less than '.$options['min'].' characters.<br>';
						}
					}
					if (isset($options['valid']) && $options['valid'] == 'email')
					{
						if(!filter_var($_POST[$name], FILTER_VALIDATE_EMAIL))
						{
							if (!isset($this->errors[$name]))
								$this->errors[$name] = $label.' does not look valid, please check and try again.<br>';
							
						}
					}
					break;
					
				case 'radio':
					$label = $name;
					if (isset($options['label'])) $label = $options['label'];
					if (isset($options['required']) && !isset($_POST[$name]))
					{
						if (!isset($this->errors[$name]))
							$this->errors[$name] = $label.' is required.<br>';
					}
					if (isset($_POST[$name]) && !array_key_exists($_POST[$name], $options['options']))
					{
						if (!isset($this->errors[$name]))
							$this->errors[$name] = 'Please select a valid value for '.$label.'.<br>';
					}
					break;
					
				case 'checkbox':
					$label = $name;
					if (isset($options['label'])) $label = $options['label'];
					if (isset($options['required']) && !isset($_POST[$name]))
					{
						if (!isset($this->errors[$name]))
							$this->errors[$name] = $label.' is required.<br>';
					}
					if (isset($_POST[$name]))
					{
						foreach ($_POST[$name] as $key => $value)
						{
							if (!array_key_exists($value, $options['options']))
							{
								if (!isset($this->errors[$name]))
								{
									$this->errors[$name] = 'Please select a valid value for '.$label.'.<br>';
								}
							}
						}
					}
					break;
					
			}
		}
		
		if (empty($this->errors)) return true;
		return false;
	}	
	
	function display($main = '', $alternate = '')
	{
		$this->main = $main;
		$this->alternate = $alternate;
				
		$count = 0;
		foreach ($this->arguments as $name => $options)
		{
			if ($this->require_form_tag && $count == 0 && (!isset($options['type']) || $options['type'] != 'form'))
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
				case 'hidden':
					$this->createHidden($name, $options);
					break;
				case 'submit':
					$this->createSubmit($name, $options);
					break;
				case 'text':
					$this->createText($name, $options);
					break;
				case 'radio':
					$this->createRadio($name, $options);
					break;
				case 'checkbox':
					$this->createCheckbox($name, $options);
					break;
				case 'select':
					$this->createSelect($name, $options);
					break;
					
			}			
			$count++;
		}
		echo '</form>';
	}
	
	function createForm($name, $options)
	{
		$form_id = $this->genRandomString(20);
		$_SESSION['form_id'] = $form_id;
		
		if (!isset($options['method'])) $options['method'] = 'post';
		echo '<form method="'.$options['method'].'" action="'.$options['url'].'" class="'.$options['class'].'" name="'.$name.'" id="'.$name.'">';
			echo '<input type="hidden" name="form_id" value="'.$form_id.'">';
	}
	
	function createHidden($name, $options)
	{
		if (!isset($options['value'])) return false;
		echo '<input type="hidden" name="'.$name.'" value="'.$options['value'].'">';
	}
	
	function createSubmit($name, $options)
	{
		if (isset($options['label'])) $options['label'] = 'Submit';
		echo '<div class="form-actions"><input name="'.$name.'" type="submit" value="'.$options['label'].'" class="btn btn-primary" /></div>';
	}
	
	function createText($name, $options)
	{
		if (!isset($options['label'])) $options['label'] = $name;
		
		echo '<div class="control-group';
		if (isset($this->errors[$name])) echo ' error';
		echo '">';
			echo '<label class="control-label" for="'.$name.'">'.$options['label'];
				if (isset($options['required'])) echo ' <span class="required">*</span>';
			echo '</label>';
			echo '<div class="controls">';
				echo '<input name="'.$name.'" id="'.$name.'" type="text"';
					if (isset($this->main[$name])) echo ' value="'.htmlspecialchars($this->main[$name]).'"';
					else if (isset($this->alternate[$name])) echo ' value="'.htmlspecialchars($this->alternate[$name]).'"';
					else echo ' value=""';
					if (isset($options['max'])) echo ' maxlength="'.$options['max'].'"';
					if (isset($options['class'])) echo ' class="'.$options['class'].'"';
				echo '>';
				if (isset($this->errors[$name])) echo '<span class="help-inline">'.$this->errors[$name].'</span>';
			echo '</div>';
		echo '</div>';
	}

	function createSelect($name, $options)
	{
		if (!isset($options['label'])) $options['label'] = $name;
		
		echo '<div class="control-group';
		if (isset($this->errors[$name])) echo ' error';
		echo '">';
			echo '<label class="control-label" for="'.$name.'">'.$options['label'];
				if (isset($options['required'])) echo ' <span class="required">*</span>';
			echo '</label>';
			echo '<div class="controls">';
				echo '<select name="'.$name.'" id="'.$name.'"';
					if (isset($options['size'])) echo ' size="'.$options['size'].'"';
					if (isset($options['class'])) echo ' class="'.$options['class'].'"';
					if (isset($options['multiple'])) echo ' multiple="'.$options['multiple'].'"';
				echo '>';
				if ( isset($options['options']) && is_array($options['options']) ) {
					foreach($options['options'] as $value => $description) {
						echo '<option value="' . $value . '"';
						if (isset($this->main[$name]) && $this->main[$name] == $value) echo ' selected="selected"';
						echo '>' . $description . '</option>';
					}
				}
				echo '</select>';
				if (isset($this->errors[$name])) echo '<span class="help-inline">'.$this->errors[$name].'</span>';
			echo '</div>';
		echo '</div>';
	}	
	
	function createRadio($name, $options)
	{
		if (!isset($options['label'])) $options['label'] = $name;
		echo '<div class="control-group';
		if (isset($this->errors[$name])) echo ' error';
		echo '">';
			echo '<label class="control-label">'.$options['label'].'</label>';
			echo '<div class="controls">';
				$count = 0;
				foreach ($options['options'] as $value => $label)
				{
					echo '<label class="radio';
					if (isset($options['class'])) echo ' '.$options['class'];
					echo '"><input type="radio" name="'.$name.'" value="'.$value.'"';
					if (!isset($this->main[$name]) && !isset($this->alternate[$name]) && $count == 0) echo ' checked="checked"'; 
					else if (isset($this->main[$name]) && $this->main[$name] == $value) echo ' checked="checked"'; 
					else if (isset($this->alternate[$name]) && $this->alternate[$name] == $value) echo ' checked="checked"';
					echo '> '.$label.'</label>';
					$count++;
				}
				if (isset($this->errors[$name])) echo '<span class="help-inline">'.$this->errors[$name].'</span>';
			echo '</div>';
		echo '</div>';
	}
	
	function createCheckbox($name, $options)
	{
		if (!isset($options['label'])) $options['label'] = $name;
		echo '<div class="control-group';
		if (isset($this->errors[$name])) echo ' error';
		echo '">';
			echo '<label class="control-label">'.$options['label'].'</label>';
			echo '<div class="controls">';
				$count = 0;
				foreach ($options['options'] as $value => $label)
				{
					echo '<label class="checkbox';
					if (isset($options['class'])) echo ' '.$options['class'];
					echo '"><input type="checkbox" name="'.$name.'[]" value="'.$value.'"';
					if (isset($this->main[$name]) && $this->main[$name] == $value) echo ' checked="checked"'; 
					else if (isset($this->alternate[$name]) && $this->alternate[$name] == $value) echo ' checked="checked"';
					echo '> '.$label.'</label>';
					$count++;
				}
				if (isset($this->errors[$name])) echo '<span class="help-inline">'.$this->errors[$name].'</span>';
			echo '</div>';
		echo '</div>';
	}
	
}


