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
  <?php

// default Heroku Postgres configuration URL
$dbUrl = getenv('DATABASE_URL');

if (empty($dbUrl)) {
 // example localhost configuration URL with postgres username and a database called cs313db
 $dbUrl = "postgres://postgres:oumtg8k@localhost:5432/GAME_DB";
}

$dbopts = parse_url($dbUrl);

print "<p>$dbUrl</p>\n\n";

$dbHost = $dbopts["host"];
$dbPort = $dbopts["port"];
$dbUser = $dbopts["user"];
$dbPassword = $dbopts["pass"];
$dbName = ltrim($dbopts["path"],'/');

print "<p>pgsql:host=$dbHost;port=$dbPort;dbname=$dbName</p>\n\n";

try {
 $db = new PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);
}
catch (PDOException $ex) {
 print "<p>error: $ex->getMessage() </p>\n\n";
 die();
}

foreach ($db->query('SELECT now()') as $row)
{
 print "<p>$row[0]</p>\n\n";
}

foreach ($db->query('SELECT * FROM "TOPIC_TABLE"') as $row)
{
  print "<p>$row[4]</p>";
}

foreach ($db->query('SELECT * FROM "IMAGE_TABLE"') as $row)
{
  print "<img src='$row[4]' alt='some_text' style='width:500px;height:500px;' />";
}

?>

  <!-- Bootstrap core JavaScript
   ================================================== -->
   <!-- Placed at the end of the document so the pages load faster -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
   <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
</DOCTYPE!>
