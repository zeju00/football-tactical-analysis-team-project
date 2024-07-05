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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_player'])) {
    $playerID = $_POST['playerID'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $position = $_POST['position'];
    $teamID = $_POST['teamID'];
    
    $sql = "INSERT INTO Player (playerID, name, age, position, teamID) VALUES ('$playerID', '$name', '$age', '$position', '$teamID')";
    if ($conn->query($sql) === TRUE) {
        $addResult = "New player record created successfully";
    } else {
        $addResult = "Error: 해당 선수의 팀이 등록 되어 있는지 확인해주세요.";
    }
}

$updateResult = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_player'])) {
    $playerID = $_POST['playerID'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $position = $_POST['position'];
    $teamID = $_POST['teamID'];
    
    $sql = "UPDATE Player SET name='$name', age='$age', position='$position', teamID='$teamID' WHERE playerID='$playerID'";
    if ($conn->query($sql) === TRUE) {
        $updateResult = "Player record updated successfully";
    } else {
        $updateResult = "Error: 선수 ID를 확인해주세요.";
    }
}

$deleteResult = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_player'])) {
    $playerID = $_POST['playerID'];
    
    $sql = "DELETE FROM Player WHERE playerID = '$playerID'";
    if ($conn->query($sql) === TRUE) {
        $deleteResult = "Player record deleted successfully";
    } else {
        $deleteResult = "Error: 해당 선수가 부상자 명단, 시즌기록 명단에 있으면 선수를 삭제할 수 없습니다.";
    }
}

// Player 테이블과 Team 테이블을 조인하여 데이터를 가져오는 쿼리
$sql = "SELECT Player.playerID, Player.name, Player.age, Player.position, Team.name as teamName FROM Player JOIN Team ON Player.teamID = Team.teamID";
$players = $conn->query($sql);

// 팀 데이터를 가져오는 쿼리
$teamOptions = "";
$teamQuery = "SELECT teamID, name FROM Team";
$teamResult = $conn->query($teamQuery);
if ($teamResult->num_rows > 0) {
    while($teamRow = $teamResult->fetch_assoc()) {
        $teamOptions .= "<option value='" . htmlspecialchars($teamRow['teamID']) . "'>" . htmlspecialchars($teamRow['name']) . "</option>";
    }
}

// 가장 최근의 playerID를 가져오는 쿼리
$latestPlayerID = "";
$latestPlayerQuery = "SELECT playerID FROM Player ORDER BY playerID DESC LIMIT 1";
$latestPlayerResult = $conn->query($latestPlayerQuery);
if ($latestPlayerResult->num_rows > 0) {
    $latestPlayerRow = $latestPlayerResult->fetch_assoc();
    $latestPlayerID = $latestPlayerRow['playerID'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Player Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="index.php">KUFC</a>
    </header>
    <div class="container">
        <h1>Player Management</h1>
        
        <h2>Add Player</h2>
        <?php if ($latestPlayerID): ?>
            <div class="info">
                <p>The latest Player ID is: <?php echo htmlspecialchars($latestPlayerID); ?></p>
            	<p>You can add player only about registered team.</p>
            	<p>If you want to add player who is in unregistered team, please register team first.</p>
        	</div>
        <?php endif; ?>
        <div class="form-container">
            <form method="post">
                <label for="playerID">Player ID: (Please use latest + 1)</label>
                <input type="text" name="playerID" id="playerID" required>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
                <label for="age">Age:</label>
                <input type="text" name="age" id="age" required>
                <label for="position">Position:</label>
                <input type="text" name="position" id="position" required>
                <label for="teamID">Team:</label>
                <select name="teamID" id="teamID" required>
                    <?php echo $teamOptions; ?>
                </select>
                <button type="submit" name="add_player">Add Player</button>
            </form>
            <?php if ($addResult) echo "<div class='result'>$addResult</div>"; ?>
        </div>
        
        <h2>Update Player</h2>
        <div class="form-container">
            <form method="post">
                <label for="playerID">Player ID:</label>
                <input type="text" name="playerID" id="playerID" required>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
                <label for="age">Age:</label>
                <input type="text" name="age" id="age" required>
                <label for="position">Position:</label>
                <input type="text" name="position" id="position" required>
                <label for="teamID">Team:</label>
                <select name="teamID" id="teamID" required>
                    <?php echo $teamOptions; ?>
                </select>
                <button type="submit" name="update_player">Update Player</button>
            </form>
            <?php if ($updateResult) echo "<div class='result'>$updateResult</div>"; ?>
        </div>
        
        <h2>Delete Player</h2>
	    	<p>Before deleting a player, you must delete all of the player's information (injuries, performance).</p>
        <div class="form-container">
            <form method="post">
                <label for="playerID">Player ID:</label>
                <input type="text" name="playerID" id="playerID" required>
                <button type="submit" name="delete_player">Delete Player</button>
            </form>
            <?php if ($deleteResult) echo "<div class='result'>$deleteResult</div>"; ?>
        </div>
        
        <h2>Player Records</h2>
        <div class="table-container">
            <table>
                <tr>
                    <th>Player ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Position</th>
                    <th>Team Name</th>
                </tr>
                <?php while($row = $players->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['playerID']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['age']); ?></td>
                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                    <td><?php echo htmlspecialchars($row['teamName']); ?></td>
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
