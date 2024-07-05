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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_match'])) {
    $matchID = $_POST['matchID'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $score = $_POST['score'];
    $result = $_POST['result'];
    $name = $_POST['name'];
    
    $sql = "INSERT INTO Matches (matchID, date, location, score, result, name) VALUES ('$matchID', '$date', '$location', '$score', '$result', '$name')";
    if ($conn->query($sql) === TRUE) {
        $addResult = "New match record created successfully";
    } else {
        $addResult = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$searchResult = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_match'])) {
    $matchID = $_POST['matchID'];
    
    $searchSql = "SELECT * FROM `Matches` WHERE matchID = '$matchID'";
    $searchResult = $conn->query($searchSql);
}

$matches = $conn->query("SELECT * FROM `Matches`");

// 가장 최근의 matchID를 가져오는 쿼리
$latestMatchID = "";
$latestMatchQuery = "SELECT matchID FROM Matches ORDER BY matchID DESC LIMIT 1";
$latestMatchResult = $conn->query($latestMatchQuery);
if ($latestMatchResult->num_rows > 0) {
    $latestMatchRow = $latestMatchResult->fetch_assoc();
    $latestMatchID = $latestMatchRow['matchID'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Match Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="left">
            <a href="index.php">KUFC</a>
        </div>
    </header>
    <div class="container">
        <h1>Match Management</h1>

        <h2>Add Match</h2>
        <?php if ($latestMatchID): ?>
            <div class="info">
                <p>The latest Match ID is: <?php echo htmlspecialchars($latestMatchID); ?></p>
            	<p>You can add match only about registered teams.</p>
            </div>
        <?php endif; ?>
        <div class="form-container">
            <form method="post">
                <label for="matchID">Match ID: (Please use latest + 1)</label>
                <input type="text" name="matchID" id="matchID" required>
                <label for="date">Date:</label>
                <input type="date" name="date" id="date" required>
                <label for="location">Location:</label>
                <input type="text" name="location" id="location" required>
                <label for="score">Score: (Example: 0-0)</label>
                <input type="text" name="score" id="score" required>
                <label for="result">Result: (Format: W/D/L)</label>
                <input type="text" name="result" id="result" required>
                <label for="name">Name: (Example: MCI vs. MUN)</label>
                <input type="text" name="name" id="name" required>
                <button type="submit" name="add_match">Add Match</button>
            </form>
            <?php if ($addResult) echo "<div class='result'>$addResult</div>"; ?>
        </div>

        <h2>Search Match</h2>
        <div class="form-container">
            <form method="post">
                <label for="matchID">Match ID:</label>
                <input type="text" name="matchID" id="matchID" required>
                <button type="submit" name="search_match">Search Match</button>
            </form>
        </div>

        <?php if ($searchResult && $searchResult->num_rows > 0): ?>
            <h2>Search Results</h2>
            <div class="table-container">
                <table>
                    <tr>
                        <th>Match ID</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Score</th>
                        <th>Result</th>
                        <th>Name</th>
                    </tr>
                    <?php while($row = $searchResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['matchID']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo htmlspecialchars($row['score']); ?></td>
                        <td><?php echo htmlspecialchars($row['result']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        <?php endif; ?>

        <h2>Match Records</h2>
        <div class="table-container">
            <table>
                <tr>
                    <th>Match ID</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Score</th>
                    <th>Result</th>
                    <th>Name</th>
                </tr>
                <?php while($row = $matches->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['matchID']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['score']); ?></td>
                    <td><?php echo htmlspecialchars($row['result']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
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
