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
		
