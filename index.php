<?php
session_start();
$goal = './assets/images/jerry.png';
$wall = './assets/images/wall.jpg';
$fog = './assets/images/fog.jpg';
$positionX = isset($_SESSION['positionX']) ? $_SESSION['positionX'] : 0;
$positionY = isset($_SESSION['positionY']) ? $_SESSION['positionY'] : 0;
$defaultTable = [
    [0, 0, 2, 2, 2, 2],
    [2, 0, 0, 2, 2, 2],
    [2, 2, 0, 3, 2, 2]
];
$defaultFoggedTable = [
    [5, 5, 5, 5, 5, 5],
    [5, 5, 5, 5, 5, 5],
    [5, 5, 5, 5, 5, 5]
];
$defaultTableTwo = [
    [0, 0, 2, 2, 2, 2],
    [2, 0, 0, 2, 2, 2],
    [2, 0, 0, 0, 0, 2],
    [0, 0, 2, 2, 0, 3],
    [2, 0, 0, 2, 0, 2],
    [2, 2, 0, 0, 0, 2],
];
$defaultFoggedTableTwo = [
    [5, 5, 5, 5, 5, 5],
    [5, 5, 5, 5, 5, 5],
    [5, 5, 5, 5, 5, 5],
    [5, 5, 5, 5, 5, 5],
    [5, 5, 5, 5, 5, 5],
    [5, 5, 5, 5, 5, 5]
];

$tables = [$defaultTable, $defaultTableTwo];
$foggedTables = [$defaultFoggedTable, $defaultFoggedTableTwo];
$_SESSION['random'] = isset($_SESSION['random']) ? $_SESSION['random'] : rand(0, count($tables) - 1);
$table = $tables[$_SESSION['random']];
$foggedTable  = $foggedTables[$_SESSION['random']];
$_SESSION['table'] = isset($_SESSION['table']) ? $_SESSION['table'] : $tables;
$_SESSION["foggedTable"] = $foggedTables;

if (isset($_POST['right']) && $table[$positionY][min($positionX + 1, count($table[$positionY]) - 1)] !== 2) {
    $positionX++;
} elseif (isset($_POST['left']) && $table[$positionY][max(0, $positionX - 1)] !== 2) {
    $positionX--;
} elseif (isset($_POST['up']) && $table[max(0, $positionY - 1)][$positionX] !== 2) {
    $positionY--;
} elseif (isset($_POST['down']) && $table[min($positionY + 1, count($table) - 1)][$positionX] !== 2) {
    $positionY++;
}
if(isset($_POST['refresh'])) {
    session_destroy();
    header('refresh:0');
}
$positionY = max(0, min($positionY, count($table) - 1));
$positionX = max(0, min($positionX, count($table[$positionY]) - 1));
$_SESSION['positionX'] = $positionX;
$_SESSION['positionY'] = $positionY;

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Labyrinthe</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<header>
    <h1><img src="assets/images/pic.jpg " alt=""> catch the jerry maze</h1>
</header>
<body>
    <form method="POST">
    <div>
                <button class="btn" name="refresh"><b>REPLAY</b></button>
            </div>
        <div class="move-buttons">
            <button class="move-button" name="up">🔼</button>
            <button class="move-button" name="left">◀️</button>
            <button class="move-button" name="right">▶️</button>       
            <button class="move-button" name="down">🔽</button>
        </div>

        <main>
           
            <table>
                <?php
                $foggedTable[$_SESSION['positionY']][$_SESSION['positionX']] = 1;
                if (isset($foggedTable[$_SESSION['positionY'] - 1][$_SESSION['positionX']]))
                    $foggedTable[$_SESSION['positionY'] - 1][$_SESSION['positionX']] = $table[$_SESSION['positionY'] - 1][$_SESSION['positionX']];
                if (isset($foggedTable[$_SESSION['positionY'] + 1][$_SESSION['positionX']]))
                    $foggedTable[$_SESSION['positionY'] + 1][$_SESSION['positionX']] = $table[$_SESSION['positionY'] + 1][$_SESSION['positionX']];
                if (isset($foggedTable[$_SESSION['positionY']][$_SESSION['positionX'] + 1]))
                    $foggedTable[$_SESSION['positionY']][$_SESSION['positionX'] + 1] = $table[$_SESSION['positionY']][$_SESSION['positionX'] + 1];
                if (isset($foggedTable[$_SESSION['positionY']][$_SESSION['positionX'] - 1]))
                    $foggedTable[$_SESSION['positionY']][$_SESSION['positionX'] - 1] = $table[$_SESSION['positionY']][$_SESSION['positionX'] - 1];
                foreach ($foggedTable as $y => $row) {
                    echo "<tr >";
                    foreach ($row as $x => $cell) {
                        switch ($cell) {
                            case 0:
                                echo "<td></td>";
                                break;
                            case 1:
                                echo "<td><img class='tom' src='./assets/images/tom.png' height='80px' width='80px' alt='Chat'></td>";
                                break;
                            case 2:
                                echo "<td><img src='$wall' height='80px' width='80px' alt='Wall'></td>";
                                break;
                            case 3:
                                echo "<td><img src='$goal' height='50' width='50x' alt='mouse'></td>";
                                break;
                            case 5:
                                echo "<td><img class ='foggy' src='$fog' height='80px' width='80px' alt='mouse'></td>";
                                break;
                        }
                    }
                    echo '</tr>';
                }
                if ($table[$positionY][min($positionX, count($table[$positionY]) - 1)] == 3) {
                    echo "<b style='color: white;'>Congratulations! You caught Jerry!</b>";                }
                ?>
            </table>
        </main>
    </form>
   


</body>

</html>
