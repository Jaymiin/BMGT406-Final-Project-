<?php
include_once('support.php');
include_once('connectDB.php');
//connect_database.php contains your connection/creation of a PDO to connect to your MYSQL db on bmgt406.rhsmith.umd.edu/phpmyadmin
ini_set("display_errors","1");
error_reporting(E_ALL);

// Initialize $title and $body.
$title = "My Workout Friends";
$body = "<fieldset><legend> $title </legend>";
$name_of_table = 'workoutFriends';
$thisUser = $_GET['userEmail'];

//require_once('LoginPage.php');
//$thisUser = $inputemail;

// Check if the table exists in the db.
if (tableExists($db, $name_of_table)) {
	// Prepare a SQL query
	$sqlQuery = "SELECT u.email, u.firstname, u.lastname 
	FROM users u, workoutFriends f 
	WHERE 
	u.email = f.email2
	AND f.email1 = :userEmail;	
	 ";
	
/*"SELECT email2 
FROM workoutFriends 
WHERE email1 = :userEmail;
";
*/
//join following query to get names?
//SELECT firstname, lastname
//FROM users u, workoutFriends w
//WHERE u.email=w.email2;";

//SELECT users.firstname, users.lastname, users.email, workoutFriends.email1
//FROM users 
//INNER JOIN workoutFriends
//ON users.email = workoutFriends.email1);";

	$statement1 = $db->prepare($sqlQuery);
	$statement1->bindValue(':userEmail', $thisUser, PDO::PARAM_STR);
	$result = $statement1->execute();

	if (!$result) {
		$body .= "Listing records failed.";
	} else {
		$numberOfRows = $statement1->fetchAll(PDO::FETCH_ASSOC);
		if($numberOfRows) {
			$body .="<table style= \"border-collapse:collapse;\"><tr>";
			foreach ($numberOfRows as $multipleRows)
					{
					$email = $multipleRows['email'];
					$fname = $multipleRows['firstname'];
					$lname = $multipleRows['lastname'];		
					//$body .= "<td>$fname</td><td>$lname</td><td>$email2</td>";
					
					$body .= "<tr>";
					$body .= "<td>$fname</td>";
					$body .= "<td>$lname</td>";
					$body .= "<form  class=\"addF\" action=\"ViewFriendAct.php\" methods=\"GET\">";
					$body .= "<td>
					<input type=\"text\" name=\"friendEmail\" value=". $email . " style=\"display: none\" readonly>
					<input class=\"button\" type=\"submit\" name=\"viewAct\" value=\"View Activities\"/></td>";
					$body .= "</form>";
					$body .= "</tr>";
					}
			$body .="</table>";
		} else {
			$body .= "Table is empty.";
		}
	}
	// Closing query connection
	$statement1->closeCursor();
} else {
	// Table does not exist in db.
	$body .= "Table does not exist in the database.";
}

//$body .= "<a href=\"LoginPage.php\"><input type=\"submit\" value = \"Main Menu\"/></a>";
//Will the login page take us to the right html page?
//$body .= "<a href=\"http://bmgt406.rhsmith.umd.edu/~bmgt406_02/406FinalProject_V.4%20/LoginPage.php?email=mf%40gmail.com&password=02&Login=Login\"><input type=\"submit\" value = \"Main Menu\"/></a>";
$body.= "</fieldset>";

echo generatePage($title,$body);

?>
