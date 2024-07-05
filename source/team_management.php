<?php
$servername = "localhost";
$username = "db2020320026";
$password = "zeju00@korea.ac.kr";
$dbname = "db2020320026";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$addResult = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_team'])) {
    $teamID = $_POST['teamID'];
    $season = $_POST['season'];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $league = $_POST['league'];
    
    $sql = "INSERT INTO Team (teamID, season, name, location, league) VALUES ('$teamID', '$season', '$name', '$location', '$league')";
    if ($conn->query($sql) === TRUE) {
        $addResult = "New team record created successfully";
    } else {
        $addResult = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$updateResult = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_team'])) {
    $teamID = $_POST['teamID'];
    $season = $_POST['season'];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $league = $_POST['league'];
    
    $sql = "UPDATE Team SET season='$season', name='$name', location='$location', league='$league' WHERE teamID='$teamID'";
    if ($conn->query($sql) === TRUE) {
        $updateResult = "Team record updated successfully";
    } else {
        $updateResult = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$deleteResult = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_team'])) {
    $teamID = $_POST['teamID'];
    
    $sql = "DELETE FROM Team WHERE teamID = '$teamID'";
    if ($conn->query($sql) === TRUE) {
        $deleteResult = "Team record deleted successfully";
    } else {
        $deleteResult = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$teams = $conn->query("SELECT * FROM Team");

// 가장 최근의 teamID를 가져오는 쿼리
$latestTeamID = "";
$latestTeamQuery = "SELECT teamID FROM Team ORDER BY teamID DESC LIMIT 1";
$latestTeamResult = $conn->query($latestTeamQuery);
if ($latestTeamResult->num_rows > 0) {
    $latestTeamRow = $latestTeamResult->fetch_assoc();
    $latestTeamID = $latestTeamRow['teamID'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Team Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="index.php">KUFC</a>
    </header>
    <div class="container">
        <h1>Team Management</h1>
        
        <h2>Add Team</h2>
        <?php if ($latestTeamID): ?>
            <div class="info">
            	<p>Teams are managed by season.</p>
                <p>The latest Team ID is: <?php echo htmlspecialchars($latestTeamID); ?></p>
            </div>
        <?php endif; ?>
        <div class="form-container">
            <form method="post">
                <label for="teamID">Team ID: (Please use latest + 1)</label>
                <input type="text" name="teamID" id="teamID" required>
                <label for="season">Season: (Example: 23/24)</label>
                <input type="text" name="season" id="season" required>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
                <label for="location">Location: (Home Stadium)</label>
                <input type="text" name="location" id="location">
                <label for="league">League:</label>
                <input type="text" name="league" id="league">
                <button type="submit" name="add_team">Add Team</button>
            </form>
            <?php if ($addResult) echo "<div class='result'>$addResult</div>"; ?>
        </div>
        
        <h2>Update Team</h2>
        <div class="form-container">
            <form method="post">
                <label for="teamID">Team ID:</label>
                <input type="text" name="teamID" id="teamID" required>
                <label for="season">Season:</label>
                <input type="text" name="season" id="season" required>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
                <label for="location">Location:</label>
                <input type="text" name="location" id="location">
                <label for="league">League:</label>
                <input type="text" name="league" id="league">
                <button type="submit" name="update_team">Update Team</button>
            </form>
            <?php if ($updateResult) echo "<div class='result'>$updateResult</div>"; ?>
        </div>
        
        <h2>Delete Team</h2>
        	<p>Before deleting a team, you must delete all players on that team.</p>
        <div class="form-container">
            <form method="post">
                <label for="teamID">Team ID:</label>
                <input type="text" name="teamID" id="teamID" required>
                <button type="submit" name="delete_team">Delete Team</button>
            </form>
            <?php if ($deleteResult) echo "<div class='result'>$deleteResult</div>"; ?>
        </div>
        
        <h2>Team Records</h2>
        <div class="table-container">
            <table>
                <tr>
                    <th>Team ID</th>
                    <th>Season</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>League</th>
                </tr>
                <?php while($row = $teams->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['teamID']); ?></td>
                    <td><?php echo htmlspecialchars($row['season']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['league']); ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
    <footer>
        &copy; 2024 KUFC
    </footer>
</body>
</html>
<?php
$conn->close();
?>
