<?php			
	// Setup database credentials
	$servername="localhost";
	$username="hussaina_admin";
	$password="john.f.kennedy";
	$database="hussaina_projecty";
	
	function clearLatestMessagesDB () {
		global $servername, $username, $password, $database;
		
		// Establish a connection
		$connection = new mysqli($servername, $username, $password, $database);
		
		// Check connection
		if ( $connection->connect_error ) {
			die ("Connection failed: " . $connection->connect_error);
		}
		
		$sql = "TRUNCATE TABLE latest_messages";
		
		$connection->query($sql);

		// Close the connection
		$connection->close();
	}
	
	function clearRSSDatabase () {
		global $servername, $username, $password, $database;
		
		// Establish a connection
		$connection = new mysqli($servername, $username, $password, $database);
		
		// Check connection
		if ( $connection->connect_error ) {
			die ("Connection failed: " . $connection->connect_error);
		}
		
		$sql = "TRUNCATE TABLE rss_feeds";
		
		$connection->query($sql);

		// Close the connection
		$connection->close();
	}
		
	function insertIntoDatabase ( $title, $url, $date, $thumbnail, $summary ) {	
		global $servername, $username, $password, $database;
	
		// Establish a connection
		$connection = new mysqli($servername, $username, $password, $database);
		
		// Check connection
		if ( $connection->connect_error ) {
			die ("Connection failed: " . $connection->connect_error);
		}
		
		$sql = "SELECT * FROM rss_feeds WHERE url='$url'";
		$result = $connection->query ($sql);
		
		if ( $result->num_rows == 0 ) {		
			$sql = "INSERT INTO rss_feeds (title, url, date, thumbnail, summary) VALUES ('$title', '$url', '$date', '$thumbnail', '$summary')";
			$connection->query($sql);
		}

		// Close the connection
		$connection->close();
	}
	
	function retrieveArticle ( $id ) {		
		global $servername, $username, $password, $database;
		
		// Establish a connection
		$connection = new mysqli($servername, $username, $password, $database);
		
		// Check connection
		if ( $connection->connect_error ) {
			die ("Connection failed: " . $connection->connect_error);
		}
		
		$sql = "SELECT * FROM rss_feeds WHERE id='$id'";
		$result = $connection->query($sql);	
		
		// Collect the results
		$results = array ();
		$row = $result->fetch_assoc();
		
		array_push ( $results, $row );		
		
		// Close the connection
		$connection->close();
		
		return $results;
	}
	
	function retrieveAllFeeds ( $search ) {		
		global $servername, $username, $password, $database;
	
		// Establish a connection
		$connection = new mysqli($servername, $username, $password, $database);
		
		// Check connection
		if ( $connection->connect_error ) {
			die ("Connection failed: " . $connection->connect_error);
		}
		
		$sql = "SELECT * FROM rss_feeds WHERE summary LIKE '%$search%' ORDER BY id DESC";
		$result = $connection->query($sql);
		
		// Collect the results
		$results = array ();
		
		if ( $result->num_rows > 0 ) {
			while ( $row = $result->fetch_assoc() ) {
				array_push($results, $row);
			}
		}
		
		$sql = "SELECT * FROM rss_feeds WHERE title LIKE '%$search%' ORDER BY id DESC";
		$result = $connection->query($sql);
		
		if ( $result->num_rows > 0 ) {
			while ( $row = $result->fetch_assoc() ) {
				if ( in_array ( $row, $results ) == false ) {
					array_push($results, $row);
				} 
			}
		}

		// Close the connection
		$connection->close();
		
		return $results;
	}
?>