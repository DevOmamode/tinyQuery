<?php
/*
NAME: tinyQuery

DESC: tinyQuery is a simple, tiny and lightweight PHP library that simplifies writing MySQL queries in PHP as well as doing same in a single line of code possible.

AUTHOR: Joseph Omamode (DevOmamode)

AUTHOR URL: https://devomamode.github.io
*/

//This function is very essential... Please, do not discard
function repeat_char($char,$num)
{
 $str = "";
for ($i = 1; $i <= $num; $i++)
{
	$str .= $char." ";
}
return $str;
}

//The tinyQuery class enables connection to a database and provides basic methods for performing CRUD operations
class tinyQuery{
	public $id = 0; //property - stores a row's id where available and especially when a new data is inserted into its table.
	
	public $conn = NULL; //property - stores mysqli connection object
	
//The constructor initiates a database connection
 function __construct($HOST_NAME = NULL, $USERNAME = NULL, $PASSWORD = NULL, $DATABASE = NULL){
  $this->conn = mysqli_connect($HOST_NAME, $USERNAME, $PASSWORD, $DATABASE);
  if (!$this->conn)
  {
    die("Failed establishing database connection...");
  }
}


/*
insertData() method inserts data into a database. Returns a Boolean which if true denotes a successful data insertion else failure.

REQUIRED PARAMS:
#1 Table name - String.

#2 Order - Array whose values should be strings of field name and that, data will be inserted into.

#3 Params - Array whose values should be characters denoting the type of values accepted in each of the specified fields contained in param #2 Array. "s" denotes string, "i" denotes integer and so on in conformity with PHP prepared statements convention.

#4 Values - Array of values to be inserted. into the specified fields in param #2

EXAMPLE:
insertData("metadata", ["name","content"], ["s","s"], ["author","Joseph Omamode"]);

insertData("posts", ["title","cat_id"], ["s","i"], ["Hello World",$_POST["category_id"]]);
*/

function insertData($table = NULL, $order = NULL, $params = NULL, $values = NULL){
	if (isset($table) && isset($values) && isset($order) && isset($params))
	{
		if (!is_array($values))
			{
				return ("insertData() parameter 4 must be an array...");
			}
			else if (!is_array($order))
				{
				return ("insertData() parameter 2 must be an array...");
				}
				else if (!is_array($params))
				{
				return ("insertData() parameter 3 must be an array...");
				}
					else
				{
	 $conn = $this->conn;
		$stmt = "";
	 $stmt = "INSERT INTO ".$table." (";
		$c = count($order);
		for ($i = 0; $i < $c-1; $i++)
		{
			$stmt .= $order[$i].", ";
		}
		$stmt .= $order[$c-1].") VALUES (".repeat_char("?,",($c - 1))."?)";
		$stmt = $conn->prepare($stmt);
		$params = implode("",$params);
		$stmt->bind_param($params, ...$values);
		if ($stmt->execute())
			{
				$this->id = $conn->insert_id;
				return 1;
			}
			else
			{
				return 0;
			}
			}
	}
	else
	{
		return "insertData() expects exactly four(4) parameters.";
	}
	}
	
	/*
updateData() method updates the existing data of a table in a database. Returns a Boolean which if true denotes a successful data update else failure.

REQUIRED PARAMS:
#1 Table name - string.

#2 Order - Array whose values should be strings of field name to be updated.

#3 Condition - A string specifying the condition of rows to be updated. It should be an empty string if all rows should be updated.

#4 Params - Array whose values should be characters denoting the type of values accepted in each of the specified fields contained in param #2 Array. "s" denotes string, "i" denotes integer and so on in conformity with PHP prepared statements convention.

#5 Values - Array of values to update the specified fields in param #2 with.

EXAMPLE:
	updateData("metadata", ["content"], "name = 'Joseph Omamode'", ["s"], ["Joseph Israel"]);
	
	updateData("users",["status"],"",["i"],[1]);
	*/
	
	function updateData($table = NULL, $order = NULL, $condition = NULL,  $params = NULL, $values = NULL){
	if (isset($table) && isset($values) && isset($order) && isset($params) && isset($condition))
	{
		if (!is_array($values))
			{
				return ("updateData() parameter 5 must be an array...");
			}
			else if (!is_array($order))
				{
				return ("updateData() parameter 2 must be an array...");
				}
				else if (!is_array($params))
					{
				return ("updateData() parameter 4 must be an array...");
					}
					else
					{
	  $conn = $this->conn;
		 $stmt = "";
	  $stmt = "UPDATE ".$table." SET ";
		$c = count($order);
		for ($i = 0; $i < $c-1; $i++)
		{
			$stmt .= $order[$i]." = ?, ";
		}
		$stmt .= $order[$c-1]." = ?".(isset($condition)?" WHERE ".$condition:"");
		$stmt = $conn->prepare($stmt);
		$param = implode("",$params);
		$stmt->bind_param($param, ...$values);
		
		if ($stmt->execute())
			{
				return 1;
			}
			else
			{
				return 0;
			}
			}
	}
	else
	{
		return "updateData() expects exactly five(5) parameters.";
	}
	}
	

/*
deleteData() method basically deletes a row from a table by its id if such column is available. Returns a Boolean which if true means the row was successfully deleted.
 
REQUIRED PARAMS:
#1 Table name - String

#2 Row id - Integer. Omit this parameter to delete all rows in the table.

EXAMPLE:
deleteData("files", 5);

deleteData("unverified_accounts");
*/
function deleteData($table = NULL, $id = NULL){
	$conn = $this->conn;
	$stmt = $conn->prepare("DELETE FROM ".$table.(isset($id)?" WHERE id = ?":""));
	if (isset($id))
	{
	$stmt->bind_param("i", $id);
	}
	if ($stmt->execute())
	{
		return 1;
	}
	else
	{
		return 0;
	}
}


/*
deleteCond() method deletes all rows that meet a specified condition. Returns Boolean (true if successful).

REQUIRED PARAMS:
#1 Table name - String. The database table name e.g comments.

#2 Condition - String. The condition to meet for a row to be deleted.

EXAMPLE:
deleteCond("comments","author = 'Omamode'");

deleteCond("accounts","status = 0 AND deadline >= ".$deadline);
*/
function deleteCond($table = NULL, $condition = NULL){
	$conn = $this->conn;
	$stmt = $conn->prepare("DELETE FROM ".$table." WHERE ".$condition);
	if ($stmt->execute())
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

/*
dataCount() method counts the number of rows in a table. Returns an Integer which is the number of rows available.

REQUIRED PARAMS:
#1 Table name - String. The database table name e.g comments.

#2 Condition - String. The condition to meet for a row to be counted. Omit to count all rows in a table.

EXAMPLE:
dataCount("users","age < 18");

dataCount("comments");
*/

function dataCount($table = NULL, $condition = NULL){
  $conn = $this->conn;
  $stmt = $conn->prepare("SELECT COUNT(id) FROM ".$table.(isset($condition)?" WHERE ".$condition:""));
  if ($stmt->execute())
  {
    $result = $stmt->get_result();
    if ($result->num_rows > 0){
      while ($row = $result->fetch_assoc())
      {
        return $row["COUNT(id)"];
      }
    }
  }
}

/*
multiData() method selects more than one row or record from a table. Returns the result of the query which can then be counted using num_rows or looped over.

REQUIRED PARAMS:
#1 Table name - String. The database table name e.g comments.

#2 Order - String. The order in which the data should be sorted and returned. This can be RAND() i.e randomly, id DESC, id ASC or any other valid order. Should be NULL if no particular order is in mind.

#3 Limit - Integer/String. The maximum number of records to return e.g 10, 5, 25 and etcetera. It should be "*" or NULL to return all records.

#4 Offset - Integer. Number of rows to omit in the records to be returned. 0 to begin from the first row, 10 to omit the first 10 rows and etcetera.

EXAMPLE:
multiData("posts","id DESC",25,$offset);
multiData("users","id ASC",10,20);
*/

function multiData($table = NULL, $order = NULL, $limit = "*", $offset = 0){
	$conn = $this->conn;
	$stmt = $conn->prepare("SELECT * FROM ".$table.(isset($order)?" ORDER BY ".$order.($limit == "*"?"":" LIMIT ?,?"):($limit == "*"?"":" LIMIT ?,?")));
	if ($limit != "*")
	{
	$stmt->bind_param("ii", $offset, $limit);
	}
	if ($stmt->execute())
		{
			
			$result = $stmt->get_result();
    return $result;
		}
}

/*
multiConds() method selects more than one row or record from a table but based on a condition. Returns the result of the query which can then be counted using num_rows or looped over.

REQUIRED PARAMS:
#1 Table name - String. The database table name e.g comments.

#2 Condition - String. The condition to meet for a row to be selected.

#3 Order - String. The order in which the data should be sorted and returned. This can be RAND() i.e randomly, id DESC, id ASC or any other valid order. Should be NULL if no particular order is in mind.

#4 Limit - Integer/String. The maximum number of records to return e.g 10, 5, 25 and etcetera. It should be "*" or NULL to return all records.

#5 Offset - Integer. Number of rows to omit in the records to be returned. 0 to begin from the first row, 10 to omit the first 10 rows and etcetera.

EXAMPLE:
multiCond("registered_users", "username = 'Omamode'"); 

multiCond("registered_users", "username = 'John'", "id DESC", 5, 0)
*/
function multiCond($table, $condition, $order = NULL, $limit = "*", $offset = 0){
	 $conn = $this->conn;
  $stmt = $conn->prepare("SELECT * FROM ".$table." WHERE ".$condition.(isset($order)?" ORDER BY ".$order.($limit == "*"?"":" LIMIT ?,?"):($limit == "*"?"":" LIMIT ?,?")));

	if ($limit != "*")
	{
	$stmt->bind_param("ii", $offset, $limit);
	}
	if ($stmt->execute())
		{
			$result = $stmt->get_result();
	  	return $result;
		}
		}
		
/*
singleData() method selects a single row from a table based on a specified condition.

REQUIRED PARAMS:
#1 Table name - String. The database table name e.g comments.

#2 Condition - String. The condition for a row to be selected.

#3 Param (Optional) - Array. Contains characters which denotes what the column(s) specified in the condition accepts and stores.

#4 Values (Optional) - Array. Contains values which are to be tested with conlumn(s) contained in #2 condition.

EXAMPLE:
singleData("comments", "id = 1") 
singleData("comments", "id = ?", ["i"], 1) --- RECOMMENDED WHERE SECURITY MATTERS
*/

function singleData($table = NULL, $condition = NULL, $params = NULL, $values = NULL){
	$conn = $this->conn;
  $query = "SELECT * FROM ".$table." WHERE ".$condition." LIMIT 1";
  $stmt = $conn->prepare($query);
  if (isset($params))
  {
    $params = implode("", $params);
    $stmt->bind_param($params, ...$values);
  }
	if ($stmt->execute())
		{
			$result = $stmt->get_result();
		  return $result;
		}
}

/*
executeQuery() method executes just any SQL query and is useful when there is need to just execute a query. Returns Boolean (true if the query was successfully executed).

REQUIRED PARAMS:
#1 Query - String. The SQL query.

EXAMPLE:
executeQuery("UPDATE posts SET views = views + 1 WHERE id = ".$post_id);
*/

function executeQuery($query)
{
  $conn = $this->conn;
  $stmt = $conn->prepare($query);
  if ($stmt->execute())
  {
    return 1;
  }
}

//Closes database connection object
function close(){
$conn = $this->conn;
$conn->close();
}
}
?>
