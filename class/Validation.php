<?php
// require_once('config/photo_path.php');
require_once('config/error_messages.php');
require_once('class/DatabaseConnection.php');
require_once('config/constants.php');

//session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Validation
{
	public static $form_data = array();
	public $count;
	public static $err;

	// public function __construct()
	// {

	// }

	public function __construct($array)
	{
		// print_r($_POST);exit;
		global $error_msg;
		static::$err = $error_msg;
		$this->count = 0;
		static::$form_data = $array;
	}

	public function validate_form($param)
	{
		if($param == 'submit')
		{
			$this->count += Validation::pure_string('first_name');
			$this->count += Validation::pure_string('middle_name');
			$this->count += Validation::pure_string('last_name');
			$this->count += Validation::email('email');
			$this->count += Validation::password('password');
			$this->count += Validation::radios('prefix');
			$this->count += Validation::radios('gender');
			$this->count += Validation::fields_with_empty('dob');
			$this->count += Validation::radios('marital');
			$this->count += Validation::radios('employment');
			$this->count += Validation::employer('employer');
			$this->count += Validation::alpha_numeric('r_street');
			$this->count += Validation::pure_string('r_city');
			$this->count += Validation::fields_with_empty('r_state');
			$this->count += Validation::zip_code('r_zip');
			$this->count += Validation::phone_fax('r_phone');
			$this->count += Validation::phone_fax('r_fax');
			$this->count += Validation::alpha_numeric('o_street');
			$this->count += Validation::pure_string('o_city');
			$this->count += Validation::fields_with_empty('o_state');
			$this->count += Validation::zip_code('o_zip');
			$this->count += Validation::phone_fax('o_phone');
			$this->count += Validation::phone_fax('o_fax');
		}
		elseif($param == 'login')
		{
			$error_count = 0;
			$error_count = Validation::login_validation();
			return $error_count;
		}		
	}

	public static function pure_string($name)
	{
		
		$error = 0;
		$form_data = static::$form_data;
		// echo static::$form_data[$name];exit;
		if(isset($form_data[$name]) && !empty($form_data[$name]))
		{
			$name_value = Validation::formatted($form_data[$name]);
			if(preg_match("/^[a-zA-Z ]*$/",$name_value))
			{
				$_SESSION['error_array'][$name]['val'] = $name_value;
				$_SESSION['error_array'][$name]['msg'] = '';
			}
			else
			{
				$_SESSION['error_array'][$name]['val'] = $name_value;
				$_SESSION['error_array'][$name]['msg'] = '*Only Alphabets Allowed';
				$error = 1;
			}
		}
		else
		{
			$_SESSION['error_array'][$name]['val'] = '';
			$_SESSION['error_array'][$name]['msg'] = static::$err[$name];			
			$error = 1;
		}
		// print_r($_SESSION);exit;
		return $error;
	}

	public static function radios($field)
	{
		$error = 0;
		$form_data = static::$form_data;
		if(isset($form_data[$field]))
		{
			$field_value = $form_data[$field];
			$_SESSION['error_array'][$field]['val'] = $field_value;
			$_SESSION['error_array'][$field]['msg'] = '';
		}
		else
		{
			$_SESSION['error_array'][$field]['val'] = '';
			$_SESSION['error_array'][$field]['msg'] = static::$err[$field];
			$error = 1;
		}
		return $error;
	}

	public static function fields_with_empty($field)
	{
		$error = 0;
		$form_data = static::$form_data;
		if(isset($form_data[$field]) && !empty($form_data[$field]))
		{
			$field_value = $form_data[$field];
			$_SESSION['error_array'][$field]['val'] = $field_value;
			$_SESSION['error_array'][$field]['msg'] = '';
		}
		else
		{
			$_SESSION['error_array'][$field]['val'] = '';
			$_SESSION['error_array'][$field]['msg'] = static::$err[$field];
			$error = 1;
		}
		return $error;
	}

	public static function alpha_numeric($name)
	{
		$error = 0;
		$form_data = static::$form_data;
		if(isset($form_data[$name]) && !empty($form_data[$name]))
		{
			$name_value = Validation::formatted($form_data[$name]);
			if(preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/', $name_value))
			{
				$_SESSION['error_array'][$name]['val'] = $name_value;
				$_SESSION['error_array'][$name]['msg'] = '';
			}
			else
			{
				$_SESSION['error_array'][$name]['val'] = $name_value;
				$_SESSION['error_array'][$name]['msg'] = '*Only Alphabets and Numeric Allowed';
				$error = 1;
			}
		}
		else
		{
			$_SESSION['error_array'][$name]['val'] = '';
			$_SESSION['error_array'][$name]['msg'] = static::$err[$name];
			$error = 1;
		}
		return $error;
	}

	public static function zip_code($code)
	{
		$error = 0;
		$form_data = static::$form_data;
		if(isset($form_data[$code]) && !empty($form_data[$code]))
		{
			$code_value = Validation::formatted($form_data[$code]);
			$num_length = strlen((string)$code_value);
			if(ctype_digit($code_value) && $num_length == 6)
			{
				$_SESSION['error_array'][$code]['val'] = $code_value;
				$_SESSION['error_array'][$code]['msg'] = '';
			}
			else
			{
				$_SESSION['error_array'][$code]['val'] = $code_value;
				$_SESSION['error_array'][$code]['msg'] = '*Provide a Numeric value of length 6';
				$error = 1;
			}
		}
		else
		{
			$_SESSION['error_array'][$code]['val'] = '';
			$_SESSION['error_array'][$code]['msg'] = static::$err[$code];
			$error = 1;
		}
		return $error;
	}

	public static function phone_fax($number)
	{
		$error = 0;
		$form_data = static::$form_data;
		if(isset($form_data[$number]) && !empty($form_data[$number]))
		{
			$number_value = Validation::formatted($form_data[$number]);
			$num_length = strlen((string)$number_value);
			if(ctype_digit($number_value) && $num_length >= 7 && $num_length <= 12)
			{
				$_SESSION['error_array'][$number]['val'] = $number_value;
				$_SESSION['error_array'][$number]['msg'] = '';
			}
			else
			{
				$_SESSION['error_array'][$number]['val'] = $number_value;
				$_SESSION['error_array'][$number]['msg'] = '*Provide a Numeric value between 7 to 
				12 digits';
				$error = 1;
			}
		}
		else
		{
			$_SESSION['error_array'][$number]['val'] = '';
			$_SESSION['error_array'][$number]['msg'] = static::$err[$number];
			$error = 1;
		}
		return $error;
	}

	public static function employer($emp)
	{
		$error = 0;
		$form_data = static::$form_data;
		if(isset($form_data[$emp]))
		{
			$emp_value = $form_data[$emp];
			$_SESSION['error_array'][$emp]['val'] = $emp_value;
			$_SESSION['error_array'][$emp]['msg'] = '';
		}
		else
		{
			$_SESSION['error_array'][$emp]['val'] = ' ';
			$_SESSION['error_array'][$emp]['msg'] = static::$err[$emp];
		}
		return $error;
	}

	public static function email($email)
	{
		$error = 0;
		$form_data = static::$form_data;
		if(isset($form_data[$email]) && !empty($form_data[$email]))
		{
			$rnum = 0;
			if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			{
				$obj = DatabaseConnection::create_connection();
				$rows = $obj->select_email($form_data[$email], $_SESSION['id']);
				
				$rnum = DatabaseConnection::db_num_rows($rows);
				if($rnum == 0)
				{
					$_SESSION['error_array'][$email]['val'] = $form_data[$email];
					$_SESSION['error_array'][$email]['msg'] = '';
				}
				else
				{	
					$_SESSION['error_array'][$email]['val'] = $form_data[$email];
					$_SESSION['error_array'][$email]['msg'] = '*Email ID already taken';
					$error = 1;
				}
			}
			else
			{
				$_SESSION['error_array'][$email]['val'] = $form_data[$email];
				$_SESSION['error_array'][$email]['msg'] = '*Provide a valid Email ID';
				$error = 1;
			}
		}
		else
		{
			$_SESSION['error_array'][$email]['val'] = "";
			$_SESSION['error_array'][$email]['msg'] = '*Email ID mandatory';
			$error = 1;
		}
		return $error;
	}

	public static function password($password)
	{
		$error = 0;
		$form_data = static::$form_data;
		if(isset($form_data[$password]) && !empty($form_data[$password]))
		{
			//$code_value = Validation::formatted($form_data[$password]);
			$length = strlen((string)$form_data[$password]);
			if($length >= 8 && $length <= 12)
			{
				$_SESSION['error_array'][$password]['val'] = $form_data[$password];
				$_SESSION['error_array'][$password]['msg'] = '';
			}
			else
			{
				$_SESSION['error_array'][$password]['val'] = $form_data[$password];;
				$_SESSION['error_array'][$password]['msg'] = '*Password must be of 8 to 12 
				characters';
				$error = 1;
			}
		}
		else
		{
			$_SESSION['error_array'][$password]['val'] = "";
			$_SESSION['error_array'][$password]['msg'] = '*Forgot to provide a Password';
			$error = 1;
		}
		return $error;
	}

	public function notes_validation($notes)
	{
		if(isset($notes))
		{
			$notes = Validation::formatted($notes);
			$_SESSION['error_array']['notes']['val'] = $notes;
			$_SESSION['error_array']['notes']['msg'] = '';
		}
		else
		{
			$notes = ' ';
			$_SESSION['error_array']['notes']['val'] = $notes;
			$_SESSION['error_array']['notes']['msg'] = '';
		}
		return $notes;
	}

	public function comm_validation($comm_array)
	{
		if(isset($comm_array) && !empty($comm_array))
		{
			$comm = implode(', ', $comm_array);
			$_SESSION['error_array']['comm']['val'] = $comm_array;
			$_SESSION['error_array']['comm']['msg'] = '';
			return $comm;
		}
		else
		{
			$_SESSION['error_array']['comm']['val'] = '';
			$_SESSION['error_array']['comm']['msg'] = '*Select at least one medium';
			$this->count++;
			return 0;
		}
	}

	public function photo_validation()
	{
		$pic_return_data = array("pic_update" => FALSE, "name" => "");
		if(isset($_FILES['pic']))
		{
			$errors = array();
			$pic_return_data["name"] = $file_name = $_FILES['pic']['name'];
			$file_size = $_FILES['pic']['size'];

			if ($file_size > 0) 
			{
				// $pic_update = TRUE;
				$file_tmp = $_FILES['pic']['tmp_name'];
				$file_type= $_FILES['pic']['type'];

				$ext_arr = explode('.',$file_name);
				$file_ext = strtolower(end($ext_arr));
				$extensions = array("jpeg","jpg","png");
				if(in_array($file_ext, $extensions) === false)
				{
					$errors[] = "extension not allowed, please choose a JPEG or PNG file.";
					$_SESSION['error_array']['photo']['val'] = $file_name;
					$_SESSION['error_array']['photo']['msg'] = '*Please choose jpg, jpeg or png 
					file';
					$this->count++;
				}
				if($file_size > 8388608)
				{
					$errors[] = '*File size must be less than 8 MB';
				}
				if(empty($errors) == true)
				{
					move_uploaded_file($file_tmp, PIC_PATH.$file_name);
					$pic_return_data["pic_update"] = TRUE;
				}
				else
				{
					//print_r($errors);
				}
			}
			$_SESSION['error_array']['photo']['val'] = $file_name;
			$_SESSION['error_array']['photo']['msg'] = '*Please Provide a Photo';
			//$pic_return_data["name"] = $file_name;	
		}
		else
		{
			$_SESSION['error_array']['photo']['val'] = ' ';
			$_SESSION['error_array']['photo']['msg'] = '*Invalid Photo';
			$this->count++;
		}
		return $pic_return_data;
	}

	public static function login_validation()
	{
		$error = 0;
		$rnum = 0;
		if(isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && 
			!empty($_POST['password']))
		{
			$obj = DatabaseConnection::create_connection();
			$rows = $obj->select_login($_POST['email'], $_POST['password']);
			$rnum = DatabaseConnection::db_num_rows($rows);
			$fetch_data = $obj->db_fetch_array($rows);
			if($rnum != 0)
			{
				$_SESSION['error_array']['login']['msg'] = '';
				$_SESSION['id'] = $fetch_data['id'];
			}
			else
			{
				$_SESSION['error_array']['login']['msg'] = '*Invalid username or password';
				$error = 1;
			}
		}
		else
		{
			$_SESSION['error_array']['login']['msg'] = '*Please give login credentials';
			$error = 1;
		}
		return $error;
	}

	public static function formatted($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
}
?>