<?php
  session_start();
  $dbUrl = getenv('DATABASE_URL');
  $db;

  if (empty($dbUrl)) {
  // example localhost configuration URL with postgres username and a database called cs313db
  $dbUrl = "postgres://postgres:oumtg8k@localhost:5432/Scriptures";
  }

  $dbopts = parse_url($dbUrl);

  $dbHost = $dbopts["host"];
  $dbPort = $dbopts["port"];
  $dbUser = $dbopts["user"];
  $dbPassword = $dbopts["pass"];
  $dbName = ltrim($dbopts["path"],'/');


  try {
    $db = new PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);
    $_SESSION["dbHost"] = $dbHost;
    $_SESSION["dbPort"] = $dbPort;
    $_SESSION["dbName"] = $dbName;
    $_SESSION["dbUser"] = $dbUser;
    $_SESSION["dbPassword"] = $dbPassword;
  }
  catch (PDOException $ex) {
    print "<p>error: $ex->getMessage() </p>\n\n";
    die();
    }

  function fetchTopics() {
    global $db;
    foreach ($db->query('SELECT "NAME" FROM "TABLE_TOPICS"') as $row)
    {
      echo "<label for='topic_$row[0]'>$row[0]</label><input type='checkbox' id='topic_$row[0]' name='topics[]' value='$row[0]'/><br />";
    }
  }

 ?>

<DOCTYPE! html>
<html>
<head>
</head>
<body>
<div name="title">
  <p>
    <h1>Scripture Database</h1>
  </p>
</div>
<div name="form-container">
  <form method="post" action="scripture_out.php">
    <label for="input_book">Book</label>
    <input type="text" id="input_book" name="input_book" /><br />
    <label for="input_book">Chapter</label>
    <input type="number" id="input_chapter" name="input_chapter" /><br />
    <label for="input_book">Verse</label>
    <input type="number" id="input_verse" name="input_verse" /><br />
    <label for="input_content">Content</label>
    <textarea rows="6" cols="60" id="input_content" name="input_content" /></textarea><br />
    <?php fetchTopics(); ?>
    <input type="submit" name="submit" value="Submit" />
  </form>
</div>
</body>
</html>
</DOCTYPE!>
