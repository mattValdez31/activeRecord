<?php

//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);

define('DATABASE', 'mjv32');
define('USERNAME', 'mjv32');
define('PASSWORD', '');
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
			self::$db = new PDO( 'mysql:host=' . CONNECTION.':dbname=' . DATABASE, USERNAME, PASSWORD);
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
		$tableName = get_called_class()
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
		$tableName = get_called_class();
		$sql = 'SELECT * FROM ' . $tableName . ' WHERE id =' . $id;
		$statement = $db->prepare($sql);
		$statement->execute();
		$class = static::$modelName;
		$statement->setFetchMode(PDO::FETCH_CLASS, $class);
		$recordsSet = $statement->fetchAll();
		return $recordsSet[0];
	}
}
