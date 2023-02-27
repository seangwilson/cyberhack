<?php
require_once "config.php";

// Query to select all entries from the "users" table with a role of "user" and availability of "yes"
$sql = "SELECT name, email FROM users WHERE role='user' AND availability='yes'";
$result = $conn->query($sql);

// Check if any results were returned
if ($result->num_rows > 0) {
  // Loop through each result and display the name and email as a link
  while($row = $result->fetch_assoc()) {
    echo "<a href='mailto:" . $row["email"] . "'>" . $row["name"] . "</a><br>";
  }
} else {
  echo "0 results";
}

// Close database connection
$conn->close();
?>
