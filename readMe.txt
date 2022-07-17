NAME: tinyQuery

DESC: tinyQuery is a simple, tiny and lightweight PHP library that simplifies writing MySQL queries in PHP as well as doing same in a single line of code possible.

AUTHOR: Joseph Omamode (DevOmamode)

AUTHOR URL: https://devomamode.github.io

DEVELOPERS' GUIDE
tinyQuery is essentially a class which enables connection to a database and provides basic methods for performing CRUD operations on that database. Therefore, to use, you need to create an instance of the class.

<?php
require_once("relative_path_to_tinyQuery/class.tinyQuery.php");

$tiny_query = new tinyQuery("localhost","database_username","database_password","database");
?>

INSERT DATA INTO TABLE (insertData())

The insertData() method inserts data into a database. Returns a Boolean which if true denotes a successful data insertion else failure.

EXPECTED PARAMETERS:
#1 Table name - String.

#2 Order - Array whose values should be strings of field name and that, data will be inserted into.

#3 Params - Array whose values should be characters denoting the type of values acceptable in each of the specified fields contained in param #2 Array. "s" denotes string, "i" denotes integer and so on in conformity with PHP prepared statements convention.

#4 Values - Array of values to be inserted into the specified fields in param #2

EXAMPLE:
<?php
if ($tiny_query->insertData("metadata", ["name","content"], ["s","s"], ["author","Joseph Omamode"]))
{
    echo "Success";
}

//OR

$tiny_query->insertData("posts", ["title","cat_id"], ["s","i"], ["Hello World",$_POST["category_id"]]);
?>

UPDATE TABLE DATA (updateData())

The updateData() method updates the existing data of a table in a database. Returns a Boolean which if true denotes a successful data update else failure.

EXPECTED PARAMETERS:
#1 Table name - string.

#2 Order - Array whose values should be strings of field name to be updated.

#3 Condition - A string specifying the condition of rows to be updated. It should be an empty string if all rows should be updated.

#4 Params - Array whose values should be characters denoting the type of values acceptable in each of the specified fields contained in param #2 Array. "s" denotes string, "i" denotes integer and so on in conformity with PHP prepared statements convention.

#5 Values - Array of values to update the specified fields in param #2 with.

EXAMPLE:
<?php
if ($tiny_query->updateData("metadata", ["content"], "name = 'Joseph Omamode'", ["s"], ["Joseph Israel"]))
{
    echo "Updated";
}
	

$tiny_query->updateData("users",["status"],"",["i"],[1]);
?>

DELETE A ROW BASED ON ID (deleteData())

The deleteData() method basically deletes a row from a table by its id if such column is available. Returns a Boolean which if true means the row was successfully deleted.
 
EXPECTED PARAMETERS:
#1 Table name - String

#2 Row id (Optional) - Integer. Omit this parameter to delete all rows in the table.

EXAMPLE:
deleteData("files", 5); //delete row where id is 5 in the files table.

deleteData("unverified_accounts"); //deletes all rows from the unverified_accounts table.

DELETE ROWS BASED ON A CONDITION (deleteCond())

The deleteCond() method deletes all rows that meet a specified condition. Returns Boolean (true if successful).

EXPECTED PARAMETERS:
#1 Table name - String. The database table name e.g comments.

#2 Condition - String. The condition to meet for a row to be deleted.

EXAMPLE:
<?php
$tiny_query->deleteCond("comments","author = 'Omamode'");

$tiny_query->deleteCond("accounts","status = 0 AND deadline >= ".$deadline);
?>

COUNT ROWS (dataCount())
The dataCount() method counts the number of rows in a table. Returns an Integer which is the number of rows available.

EXPECTED PARAMETERS:
#1 Table name - String. The database table name e.g comments.

#2 Condition (Optional) - String. The condition to meet for a row to be counted. Omit to count all rows in a table.

EXAMPLE:
<?php
$tiny_query->dataCount("users","age < 18");

$tiny_query->dataCount("comments");
?>

SELECT MULTIPLE ROWS (multiData())

The multiData() method selects more than one row or record from a table. Returns the result of the query which can then be counted using num_rows or looped over.

EXPECTED PARAMETERS:
#1 Table name - String. The database table name e.g comments.

#2 Order (Optional) - String. The order in which the data should be sorted and returned. This can be RAND() i.e randomly, id DESC, id ASC or any other valid order. Should be NULL if no particular order is in mind.

#3 Limit (Optional) - Integer/String. The maximum number of records to return e.g 10, 5, 25 and etcetera. It should be "*" or NULL to return all records.

#4 Offset (Optional) - Integer. Number of rows to omit in the records to be returned. 0 to begin from the first row, 10 to omit the first 10 rows and etcetera.

EXAMPLE:
<?php
$tiny_query->multiData("posts");

$tiny_query->multiData("users","id ASC",10,20);
?>

SELECT MULTIPLE ROWS BASED ON A CONDITION (multiCond())

The multiCond() method selects more than one row or record from a table but based on a condition. Returns the result of the query which can then be counted using num_rows or looped over.

EXPECTED PARAMETERS:
#1 Table name - String. The database table name e.g comments.

#2 Condition - String. The condition to meet for a row to be selected.

#3 Order (Optional) - String. The order in which the data should be sorted and returned. This can be RAND() i.e randomly, id DESC, id ASC or any other valid order. Should be NULL if no particular order is in mind.

#4 Limit (Optional) - Integer/String. The maximum number of records to return e.g 10, 5, 25 and etcetera. It should be "*" or NULL to return all records.

#5 Offset (Optional) - Integer. Number of rows to omit in the records to be returned. 0 to begin from the first row, 10 to omit the first 10 rows and etcetera.

EXAMPLE:
<?php
$tiny_query->multiCond("registered_users", "username = 'Omamode'"); 

$tiny_query->multiCond("registered_users", "username = 'John'", "id DESC", 5, 0)
?>

SELECT A SINGLE ROW (singleData())

The singleData() method selects a single row from a table based on a specified condition.

EXPECTED PARAMETERS:
#1 Table name - String. The database table name e.g comments.

#2 Condition - String. The condition for a row to be selected.

#3 Param (Optional) - Array. Contains characters which denotes what the column(s) specified in the condition accepts and stores. See example 2 below.

#4 Values (Optional) - Array. Contains values which are to be tested with conlumn(s) contained in #2 condition. See example below.

EXAMPLE:
<?php
$tiny_query->singleData("comments", "id = 1");

$tiny_query->singleData("comments", "id = ?", ["i"], 1); //--- RECOMMENDED WHERE SECURITY MATTERS
?>

The two queries above does same thing. Just that, the 2nd is secured and as such recommended especially when users supplies values for the condition.

EXECUTE JUST ANY QUERY (executeQuery())
The executeQuery() method executes just any SQL query and is useful when there is need to just execute a query. Returns Boolean (true if the query was successfully executed).

EXPECTED PARAMETERS:
#1 Query - String. The SQL query.

EXAMPLE:
<?php
$tiny_query->executeQuery("UPDATE posts SET views = views + 1 WHERE id = ".$post_id);
?>

CLOSE MYSQL CONNECTION (close())
The close() method closes the MYSQL connection object.

EXAMPLE:
<?php
$tiny_query->close();
?>