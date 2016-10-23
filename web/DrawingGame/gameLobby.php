<?php
session_start();
//define sql statement constants;
define("SQL_GET_GAME", "SELECT * FROM public.game_table WHERE game_id = :game_id");
define("SQL_CREATE_GAME", "INSERT INTO public.game_table(current_round) VALUES (0)");
define("SQL_CREATE_PLAYER", "INSERT INTO public.player_table(name) VALUES ( :name )");
define("SQL_LINK_PLAYER", "INSERT INTO public.player_link_table(game_id, player_id, flag_ready, player_pos)
                            VALUES (:game_id, :player_id, false, :player_pos )");
define("SQL_GET_PLAYERS", "SELECT * FROM public.player_link_table WHERE game_id = :game_id ORDER BY player_pos");
define("SQL_GET_PLAYER_NAME", "SELECT name FROM public.player_table WHERE player_id = :player_id");
//open DB Stuff


$errorMSG = "";


// default Heroku Postgres configuration URL
$dbUrl = getenv('DATABASE_URL');

if (empty($dbUrl)) {
 // example localhost configuration URL with postgres username and a database called cs313db
 $dbUrl = "postgres://postgres:oumtg8k@localhost:5432/GAME_DB";
}

$dbopts = parse_url($dbUrl);

//print "<p>$dbUrl</p>\n\n";

$dbHost = $dbopts["host"];
$dbPort = $dbopts["port"];
$dbUser = $dbopts["user"];
$dbPassword = $dbopts["pass"];
$dbName = ltrim($dbopts["path"],'/');

//print "<p>pgsql:host=$dbHost;port=$dbPort;dbname=$dbName</p>\n\n";

try {
 $db = new PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);
}
catch (PDOException $ex) {
 print "<p>DB error: $ex->getMessage() </p>\n\n";
 die();
}

if (!isset($_SESSION["gameID"])) {
  $errorMSG = "No Game ID found!";
  die();
} else {
  $gameID = $_SESSION["gameID"];
}

$gameURL = "https://calm-peak-86088.herokuapp.com/DrawingGame/gamestart.php=$gameID";

$gameSettings = getGame($gameID);
$playerList = getPlayers($gameID);
//print_r($playerList);

function getPlayers($gameID) {
  global $db;
  try {
    $players = array();
    $sth = $db->prepare(SQL_GET_PLAYERS);
    $sth->execute(array(':game_id' => $gameID));
    $sthOut = $sth->fetchAll();
    if (isset($sthOut[0])) {
      foreach ($sthOut as $row) {
        $sth = $db->prepare(SQL_GET_PLAYER_NAME);
        $sth->execute(array(':player_id' => $row["player_id"]));
        $playerName = $sth->fetchAll()[0];
        $row["name"] = $playerName[0];
        array_push($players, $row);
      }
      return $players;
    }
  }
  catch (Exception $ex) {
    echo "<h1>$ex</h1>";
    die();
  }
}

function getGame($gameID) {
  global $db;
  try {
    $sth = $db->prepare(SQL_GET_GAME);
    $sth->execute(array(':game_id' => $gameID));
    $sthOut = $sth->fetchAll();
    if (isset($sthOut[0])) {
      $result = $sthOut[0];
      return $result;
    }
  }
  catch (Exception $ex) {
    echo "<h1>$ex</h1>";
    die();
  }
}
?>

<DOCTYPE! html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Tim Gifford's homepage</title>
  <!-- Bootstrap core css-->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <!-- Custom CSS overrides -->
  <link rel="stylesheet" type="text/css" href="../stylesheets/navstyle.css" />
  <link rel="stylesheet" type="text/css" href="../stylesheets/home.css" />

</head>
<body>
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" href="#top">CS313 - Tim Gifford</a>
      </div>
      <ul class="nav nav-pills">
        <li>
          <a href="../index.php">Home</a>
        </li>
        <li class="dropdown active">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Lessons
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="/PHPSurvey/survey.php">Week 03: PHP Survey</a></li>
            <li><a href="../DrawingGame/gamestart.php">Drawing Game!</a></li>
            <li><a href="/lessons.html">Coming Soon!</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-xs-8">
        <div class="well we1l-lg">
          <h5><?php echo "Game ID = $gameID"?></h5>
          <h5><?php echo "Game URL = $gameURL"?></h5>
          <h5><?php $round = $gameSettings["current_round"];
                    echo "Round = $round";
          ?>
          </h5>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-8">
        <div class="well well-lg text-center">
          <?php
            foreach ($playerList as $player) {
              $playerName = $player['name'];
              echo "<h3>$playerName</h3>";
            }
          ?>
          <?php echo "<h3>$errorMSG</h3>"?>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript
   ================================================== -->
   <!-- Placed at the end of the document so the pages load faster -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
   <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
</DOCTYPE!>
