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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_performance'])) {
    $performanceID = $_POST['performanceID'];
    $playerID = $_POST['playerID'];
    $season = $_POST['season'];
    $goals = $_POST['goals'];
    $assists = $_POST['assists'];
    $minutesPlayed = $_POST['minutesPlayed'];
    $passes = $_POST['passes'];
    $touches = $_POST['touches'];
    $team = $_POST['team'];
    
    $sql = "INSERT INTO PlayerPerformance (preformanceID, playerID, season, team, goals, assissts, passes, touches, minutesPlayed) VALUES ('$performanceID', '$playerID', '$season', '$team', '$goals', '$assists', '$passes', '$touches', '$minutesPlayed')";
    if ($conn->query($sql) === TRUE) {
        $addResult = "New performance record created successfully";
    } else {
        $addResult = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$deleteResult = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_performance'])) {
    $performanceID = $_POST['performanceID'];
    
    $sql = "DELETE FROM PlayerPerformance WHERE preformanceID = '$performanceID'";
    if ($conn->query($sql) === TRUE) {
        $deleteResult = "Performance record deleted successfully";
    } else {
        $deleteResult = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$searchResult = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_performance'])) {
    $playerID = $_POST['playerID'];
    
    $searchSql = "SELECT pp.*, p.name AS playerName FROM PlayerPerformance pp JOIN Player p ON pp.playerID = p.playerID WHERE pp.playerID = '$playerID'";
    $searchResult = $conn->query($searchSql);
}

$performances = $conn->query("SELECT pp.*, p.name AS playerName FROM PlayerPerformance pp JOIN Player p ON pp.playerID = p.playerID");

// 선수 데이터를 가져오는 쿼리
$playerOptions = "";
$playerQuery = "SELECT p.playerID, p.name AS playerName, t.name AS teamName FROM Player p JOIN Team t ON p.teamID = t.teamID";
$playerResult = $conn->query($playerQuery);
if ($playerResult->num_rows > 0) {
    while($playerRow = $playerResult->fetch_assoc()) {
        $playerOptions .= "<option value='" . htmlspecialchars($playerRow['playerID']) . "'>" . htmlspecialchars($playerRow['playerName'] . " - " . $playerRow['teamName']) . "</option>";
    }
}

// 가장 최근의 preformanceID를 가져오는 쿼리
$latestPerformanceID = "";
$latestPerformanceQuery = "SELECT preformanceID FROM PlayerPerformance ORDER BY preformanceID DESC LIMIT 1";
$latestPerformanceResult = $conn->query($latestPerformanceQuery);
if ($latestPerformanceResult->num_rows > 0) {
    $latestPerformanceRow = $latestPerformanceResult->fetch_assoc();
    $latestPerformanceID = $latestPerformanceRow['preformanceID'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Season Performance Management</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function setPlayerID(dropdownID, inputID) {
            var playerDropdown = document.getElementById(dropdownID);
            var selectedOption = playerDropdown.options[playerDropdown.selectedIndex];
            var playerID = selectedOption.value;
            document.getElementById(inputID).value = playerID;
        }
    </script>
</head>
<body>
    <header>
        <div class="left">
            <a href="index.php">KUFC</a>
        </div>
    </header>
    <div class="container">
        <h1>Season Performance Management</h1>

        <h2>Add Performance</h2>
        <?php if ($latestPerformanceID): ?>
            <div class="info">
                <p>The latest Performance ID is: <?php echo htmlspecialchars($latestPerformanceID); ?></p>
            	<p>You can add performance only about registered player.</p>
            </div>
        <?php endif; ?>
        <div class="form-container">
            <form method="post">
                <label for="performanceID">Performance ID: (Please use latest + 1)</label>
                <input type="text" name="performanceID" id="performanceID" required>
                <label for="playerDropdownAdd">Player:</label>
                <select id="playerDropdownAdd" onchange="setPlayerID('playerDropdownAdd', 'playerIDAdd')" required>
                    <option value="" disabled selected>Select a player</option>
                    <?php echo $playerOptions; ?>
                </select>
                <input type="hidden" name="playerID" id="playerIDAdd" required>
                <label for="season">Season:</label>
                <input type="text" name="season" id="season" required>
                <label for="goals">Goals:</label>
                <input type="text" name="goals" id="goals" required>
                <label for="assists">Assists:</label>
                <input type="text" name="assists" id="assists" required>
                <label for="minutesPlayed">Minutes Played:</label>
                <input type="text" name="minutesPlayed" id="minutesPlayed" required>
                <label for="passes">Passes:</label>
                <input type="text" name="passes" id="passes" required>
                <label for="touches">Touches:</label>
                <input type="text" name="touches" id="touches" required>
                <label for="team">Team:</label>
                <input type="text" name="team" id="team" required>
                <button type="submit" name="add_performance">Add Performance</button>
            </form>
            <?php if ($addResult) echo "<div class='result'>$addResult</div>"; ?>
        </div>

        <h2>Delete Performance</h2>
        <div class="form-container">
            <form method="post">
                <label for="performanceID">Performance ID:</label>
                <input type="text" name="performanceID" id="performanceID" required>
                <button type="submit" name="delete_performance">Delete Performance</button>
            </form>
            <?php if ($deleteResult) echo "<div class='result'>$deleteResult'></div>"; ?>
        </div>

        <h2>Search Performance</h2>
        <div class="form-container">
            <form method="post">
                <label for="playerDropdownSearch">Player:</label>
                <select id="playerDropdownSearch" onchange="setPlayerID('playerDropdownSearch', 'playerIDSearch')" required>
                    <option value="" disabled selected>Select a player</option>
                    <?php echo $playerOptions; ?>
                </select>
                <input type="hidden" name="playerID" id="playerIDSearch" required>
                <button type="submit" name="search_performance">Search Performance</button>
            </form>
        </div>

        <?php if ($searchResult && $searchResult->num_rows > 0): ?>
            <h2>Search Results</h2>
            <div class="table-container">
                <table>
                    <tr>
                        <th>Performance ID</th>
                        <th>Player Name</th>
                        <th>Season</th>
                        <th>Goals</th>
                        <th>Assists</th>
                        <th>Minutes Played</th>
                        <th>Passes</th>
                        <th>Touches</th>
                        <th>Team</th>
                    </tr>
                    <?php while($row = $searchResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['preformanceID']); ?></td>
                        <td><?php echo htmlspecialchars($row['playerName']); ?></td>
                        <td><?php echo htmlspecialchars($row['season']); ?></td>
                        <td><?php echo htmlspecialchars($row['goals']); ?></td>
                        <td><?php echo htmlspecialchars($row['assissts']); ?></td>
                        <td><?php echo htmlspecialchars($row['minutesPlayed']); ?></td>
                        <td><?php echo htmlspecialchars($row['passes']); ?></td>
                        <td><?php echo htmlspecialchars($row['touches']); ?></td>
                        <td><?php echo htmlspecialchars($row['team']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        <?php endif; ?>

        <h2>Performance Records</h2>
        <div class="table-container">
            <table>
                <tr>
                    <th>Performance ID</th>
                    <th>Player Name</th>
                    <th>Season</th>
                    <th>Goals</th>
                    <th>Assists</th>
                    <th>Minutes Played</th>
                    <th>Passes</th>
                    <th>Touches</th>
                    <th>Team</th>
                </tr>
                <?php while($row = $performances->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['preformanceID']); ?></td>
                    <td><?php echo htmlspecialchars($row['playerName']); ?></td>
                    <td><?php echo htmlspecialchars($row['season']); ?></td>
                    <td><?php echo htmlspecialchars($row['goals']); ?></td>
                    <td><?php echo htmlspecialchars($row['assissts']); ?></td>
                    <td><?php echo htmlspecialchars($row['minutesPlayed']); ?></td>
                    <td><?php echo htmlspecialchars($row['passes']); ?></td>
                    <td><?php echo htmlspecialchars($row['touches']); ?></td>
                    <td><?php echo htmlspecialchars($row['team']); ?></td>
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
