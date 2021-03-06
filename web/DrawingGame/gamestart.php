
<?php
session_start();
//define sql statement constants;
define("SQL_GET_GAME", "SELECT * FROM public.game_table WHERE game_id = :game_id");


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


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//validation for the game_id entered
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["gameid"])) {
  $gameID = test_input($_GET["gameid"]);
  global $db;

//does the game id exist in the database?
  if (isset($gameID)) {
    $sth = $db->prepare(SQL_GET_GAME);
    $sth->execute(array(':game_id' => $gameID));
    $sthOut = $sth->fetchAll();
    if (isset($sthOut[0])) {
      $result = $sthOut[0];
    }
    //print_r($result);
  }
  else {
    echo "<p>
    Game ID is invalid!
    </p>";
  }

  // if the game exists
  if (isset($result)) {
    $_SESSION["gameID"]=$gameID;
    header("Location: gameLobby.php");
    die();
  }
  else {
    $errorMSG = "No game found with Game ID: $gameID";
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
          <a href="index.php">Home</a>
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
      <div class="col-xs-6">
        <div class="well well-lg text-center">
          <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <h2>Picture Telephone Game!</h2>
          <a class="btn btn-primary btn-lg" href="./newGame.php" role="button">Create Game</a>
          <h3>Or...</h3>
          <div class="form-group">
            <input type="number" class="form-control input-lg" id="gameid" name="gameid" placeholder="Game ID"/>
            <button type="submit" class="btn btn-primary btn-lg" >Join Game</button>
          </div>
          </form>
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
