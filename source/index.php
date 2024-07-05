<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KUFC Tactical Analysis Team</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        header {
            background-color: #8B0000; /* 더 진한 크림슨 색 */
            color: white;
            text-align: center;
            padding: 1em 0;
            margin-bottom: 20px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        header a {
            text-decoration: none;
            color: inherit;
            font-size: 2em;
            font-weight: bold;
        }
        .container {
            max-width: 800px;
            margin: 100px auto 0;
            padding: 20px;
        }
        h1 {
            color: #333;
            font-size: 2em;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #808080; /* 회색 요소 */
            padding-bottom: 10px;
        }
        .nav {
            display: flex;
            justify-content: center; /* 메뉴를 가운데 정렬 */
            gap: 20px; /* 메뉴 간 간격을 늘림 */
            flex-wrap: wrap; /* 메뉴가 한 줄에 다 들어가지 않으면 다음 줄로 감 */
        }
        .nav a {
            flex: 1;
            background-color: #8B0000; /* 더 진한 크림슨 색 */
            color: white;
            text-align: center;
            padding: 10px 0; /* 메뉴 칸 크기를 줄임 */
            margin: 10px 0;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s, opacity 0.3s, transform 0.3s;
            border: 2px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: translateY(20px); /* 아래에서 올라오는 효과 */
        }
        .nav a.show {
            opacity: 1;
            transform: translateY(0);
        }
        .nav a:hover {
            background-color: #5B0000; /* 더 진한 호버 크림슨 색 */
            border: 2px solid #808080; /* 회색 테두리 */
        }
        .image-container {
            text-align: center;
            margin-top: 20px;
            opacity: 0; /* 초기 상태는 투명 */
            transition: opacity 1s ease-in-out; /* 페이드 인 효과 */
        }
        .image-container.show {
            opacity: 1;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        footer {
            background-color: #8B0000; /* 더 진한 크림슨 색 */
            color: white;
            text-align: center;
            padding: 1em 0;
            border-top: 2px solid #808080; /* 회색 요소 */
            position: relative;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php">KUFC</a>
    </header>
    <div class="container">
        <h1>KUFC Tactical Analysis Team</h1>
        <div class="nav">
            <a href="injury_management.php">Injury</a>
            <a href="match_management.php">Match</a>
            <a href="season_management.php">Season Performance</a>
            <a href="team_management.php">Team</a>
            <a href="player_management.php">Player</a>
            <a href="about.php">About DB</a>
        </div>
        <div class="image-container">
            <img src="images/image1.png" alt="Team Image">
        </div>
    </div>
    <footer>
        &copy; 2024 KUFC
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const navItems = document.querySelectorAll('.nav a');
            const imageContainer = document.querySelector('.image-container');

            navItems.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add('show');
                    if (index === 0) {
                        imageContainer.classList.add('show');
                    }
                }, 150 * index); // 각 메뉴가 150ms 간격으로 등장
            });

            navItems.forEach(item => {
                item.addEventListener('mouseover', () => {
                    navItems.forEach(i => {
                        if (i !== item) {
                            i.style.opacity = '0.5';
                        }
                    });
                });

                item.addEventListener('mouseout', () => {
                    navItems.forEach(i => {
                        if (i !== item) {
                            i.style.opacity = '1';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
