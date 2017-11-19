<?php

//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);

define('DATABASE', 'mjv32');
define('USERNAME', 'mjv32');
define('PASSWORD', 'ccYhBxVxR');
define('CONNECTION', 'sql2.njit.edu');

class dbConn
{
	//variable to hold connection object
	protected static $db;

	//private constructor - class cannot be instantiated externally
	private function __construct()
	{
		try
		{
			//assign PDO object to db variable
			self::$db = new PDO( 'mysql:host=' . CONNECTION.';dbname=' . DATABASE, USERNAME, PASSWORD);
			self::$db->setAttribute( PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e)
		{
			//output error
			echo "Connection Error: " . $e->getMessage();
		}
	}

	// static method - accessible w/o instantiation
	public static function getConnection()
	{
		// guarantees single instance, if no connection object exists then create one.
		if (!self::$db) 
		{
			// new connection object
			new dbConn();
		}

		// return connection
		return self::$db;
	}
}

class collection
{
	static public function create()
	{
		$model = new static::$modelName;
		return $model;
	}

	static public function findAll()
	{
		$db = dbConn::getConnection();
		$tableName = get_called_class();
		$sql = 'SELECT * FROM ' . $tableName;
		$statement = $db->prepare($sql);
		$statement->execute();
		$class = static::$modelName;
		$statement->setFetchMode(PDO::FETCH_CLASS, $class);
		$recordsSet = $statement->fetchAll();
		return $recordsSet;
	}

	static public function findOne($id)
	{
		$db = dbConn::getConnection();
		$tableName = get_called_class(); //gets name of current class
		$sql = 'SELECT * FROM ' . $tableName . ' WHERE id =' . $id;
		$statement = $db->prepare($sql);
		$statement->execute();
		$class = static::$modelName; //$modelName from child class
		$statement->setFetchMode(PDO::FETCH_CLASS, $class); //maps columns of each row to class variables
		$recordsSet = $statement->fetchAll();
		return $recordsSet[0];
	}
}

class accounts extends collection
{
	protected static $modelName = 'account';
}

class todos extends collection
{
	protected static $modelName = 'todo';
}

class model
{
	protected $tableName;
	public function save()
	{
		//$columns = get_object_vars($this);
		$class = get_called_class();
		$tableName = $class::getTableName();

		if ($this->id = '')
		{
			$sql = $this->insert();
		}

		else
		{
			$sql = $this->insert($tableName);
		}
		
		$db = dbConn::getConnection();
		$statement = $db->prepare($sql);
		$statement->execute();
		
		//$tableName = get_called_class();
		$class = get_called_class();
		$tableName = $class::getTableName();

		$array = get_object_vars($this);
		$columnString = implode(',', $array);
		$valueString = ":".implode(',:', $array);

		echo "INSERT INTO $tableName (" . $columnString . ") VALUES (" . $valueString . ")</br>";

		echo 'I just saved record: ' . $this->id;
	}
	
	static public function remove($id)
	{
		$db = dbConn::getConnection();
		$class = get_called_class();
		$tableName = $class::getTableName();
		$sql = 'DELETE FROM ' . $tableName . ' WHERE id = ' . $id;
		
		$statement = $db->prepare($sql);
		$statement->execute();
		return $statement;
	}

	private function insert($tableName)
	{
		$sql = 'INSERT INTO ' . $tableName . ' (' . $this->col[0] . $this->col[1] . $this->col[2] .
		$this->col[3] . $this->col[4] . $this->col[5] . $this->col[6] . $this->col[7] . ') VALUES (' .
		$this->id . $this->email . $this->FNAME . $this->lname . $this->phone . $this->birthday . $this->gender . $this->password . ')';
		return $sql;
	}

	static private function update()
	{

	}

}

class account extends model
{
	public $id;
	public $email;
	public $FNAME;
	public $lname;
	public $phone;
	public $birthday;
	public $gender;
	public $password;

	public $col = array('id', 'email', 'fname', 'lname', 'phone', 'birthday', 'gender', 'password');

	public function __construct()
	{
		$this->tableName = 'accounts';
	}

	public function getTableName()
	{
		$tableName = 'accounts';
		return $tableName;
	}

	//$col = array($id, $email, $fname, $lname, $phone, $birthday, $gender, $password);

}

class todo extends model
{
	public $id;
	public $owneremail;
	public $ownerid;
	public $createddate;
	public $duedate;
	public $message;
	public $isdone;
	
	//$col = array($id, $owneremail, $ownerid, $createddate, $duedate, $message, $isdone);

	static public function getTableName()
	{
		$tableName = 'todos';
		return $tableName;
	}

}

//$records = todos::findOne(3);
//$record = accounts::findAll();
//$del = account::remove(15);
$rec = new account();
$rec->id = 14;
$rec->FNAME = 'att';
$rec->message = 'some task';
$rec->save();
//print_r($records);
//print_r($record);
//print_r($del);

?>
