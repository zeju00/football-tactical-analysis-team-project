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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_injury'])) {
    $injuryID = $_POST['injuryID'];
    $playerID = $_POST['playerID'];
    $injuredPart = $_POST['injuredPart'];
    $degree = $_POST['degree'];
    
    $sql = "INSERT INTO Injury (injuryID, playerID, injuredPart, degree) VALUES ('$injuryID', '$playerID', '$injuredPart', '$degree')";
    if ($conn->query($sql) === TRUE) {
        $addResult = "New injury record created successfully";
    } else {
        $addResult = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$deleteResult = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_injury'])) {
    $injuryID = $_POST['injuryID'];
    
    $sql = "DELETE FROM Injury WHERE injuryID = '$injuryID'";
    if ($conn->query($sql) === TRUE) {
        $deleteResult = "Injury record deleted successfully";
    } else {
        $deleteResult = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$injuries = $conn->query("SELECT i.injuryID, p.name AS playerName, t.name AS teamName, i.injuredPart, i.degree
                          FROM Injury i
                          JOIN Player p ON i.playerID = p.playerID
                          JOIN Team t ON p.teamID = t.teamID");

$searchResult = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_player'])) {
    $playerName = $_POST['playerName'];
    
    $searchSql = "SELECT p.playerID, p.name, t.name AS teamName 
                  FROM Player p
                  JOIN Team t ON p.teamID = t.teamID 
                  WHERE p.name LIKE '%$playerName%'";
    $searchResult = $conn->query($searchSql);
}

// 가장 최근의 injuryID를 가져오는 쿼리
$latestInjuryID = "";
$latestInjuryQuery = "SELECT injuryID FROM Injury ORDER BY injuryID DESC LIMIT 1";
$latestInjuryResult = $conn->query($latestInjuryQuery);
if ($latestInjuryResult->num_rows > 0) {
    $latestInjuryRow = $latestInjuryResult->fetch_assoc();
    $latestInjuryID = $latestInjuryRow['injuryID'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Injury Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="left">
            <a href="index.php">KUFC</a>
        </div>
    </header>
    <div class="container">
        <h1>Injury Management</h1>

        <h2>Search Player</h2>
        <div class="form-container">
            <form method="post">
                <label for="playerName">Player Name:</label>
                <input type="text" name="playerName" id="playerName" required>
                <button type="submit" name="search_player">Search Player</button>
            </form>
        </div>

        <?php if ($searchResult && $searchResult->num_rows > 0): ?>
            <h2>Search Results</h2>
            <div class="table-container">
                <table>
                    <tr>
                        <th>Player ID</th>
                        <th>Name</th>
                        <th>Team Name</th>
                    </tr>
                    <?php while($row = $searchResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['playerID']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['teamName']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        <?php endif; ?>

        <h2>Add Injury</h2>
        <?php if ($latestInjuryID): ?>
            <div class="info">
                <p>The latest Injury ID is: <?php echo htmlspecialchars($latestInjuryID); ?></p>
            	<p>You can add injury only about registered player.</p>
            </div>
        <?php endif; ?>
        <div class="form-container">
            <form method="post">
                <label for="injuryID">Injury ID: (Please use latest + 1)</label>
                <input type="text" name="injuryID" id="injuryID" required>
                <label for="playerID">Player ID:</label>
                <input type="text" name="playerID" id="playerID" required>
                <label for="injuredPart">Injured Part:</label>
                <input type="text" name="injuredPart" id="injuredPart" required>
                <label for="degree">Degree: (1~10)</label>
                <input type="text" name="degree" id="degree" required>
                <button type="submit" name="add_injury">Add Injury</button>
            </form>
            <?php if ($addResult) echo "<div class='result'>$addResult</div>"; ?>
        </div>

        <h2>Delete Injury</h2>
        <div class="form-container">
            <form method="post">
                <label for="injuryID">Injury ID:</label>
                <input type="text" name="injuryID" id="injuryID" required>
                <button type="submit" name="delete_injury">Delete Injury</button>
            </form>
            <?php if ($deleteResult) echo "<div class='result'>$deleteResult</div>"; ?>
        </div>

        <h2>Injury Records</h2>
        <div class="table-container">
            <table>
                <tr>
                    <th>Injury ID</th>
                    <th>Player Name</th>
                    <th>Team</th>
                    <th>Injured Part</th>
                    <th>Degree</th>
                </tr>
                <?php while($row = $injuries->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['injuryID']); ?></td>
                    <td><?php echo htmlspecialchars($row['playerName']); ?></td>
                    <td><?php echo htmlspecialchars($row['teamName']); ?></td>
                    <td><?php echo htmlspecialchars($row['injuredPart']); ?></td>
                    <td><?php echo htmlspecialchars($row['degree']); ?></td>
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
