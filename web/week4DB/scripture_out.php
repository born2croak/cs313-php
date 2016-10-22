<?php
  session_start();
  $dbHost = $_SESSION["dbHost"];
  $dbPort = $_SESSION["dbPort"];
  $dbName = $_SESSION["dbName"];
  $dbUser =  $_SESSION["dbUser"];
  $dbPassword = $_SESSION["dbPassword"];

  try {
    $db = new PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);
  }
  catch (PDOException $ex) {
    print "<p>error: $ex->getMessage() </p>\n\n";
    die();
  }

  if (isset($_POST)) {
    sendData();
  }

  function sendData() {
    $topics = array();
    global $db;
    if (isset($_POST["topics"])) {
      $topics = $_POST["topics"];
      $book = $_POST["input_book"];
      $chapter = $_POST["input_chapter"];
      $verse = $_POST["input_verse"];
      $content = $_POST["input_content"];
      insertScripture($book, $chapter, $verse, $content, $topics);
    //  printArray($topics);
    }
  }

  function getScriptureID($book, $chapter, $verse) {
    global $db;
    $sql = 'SELECT "ID" FROM "TABLE_SCRIPTURES" WHERE "BOOK" = :book AND "CHAPTER" = :chapter AND "VERSE" = :verse';
    $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':book' => $book, ':chapter' => $chapter, ':verse' => $verse));
    $result = $sth->fetchColumn();
    //print_r($result);
    //print "<h1>Here it is... $result</h1>";
    return $result;
  }

  function getTopicID($name) {
    global $db;
    $sql = 'SELECT "ID" FROM "TABLE_TOPICS"
    WHERE "NAME" = :name';
    $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':name' => $name));
    $result = $sth->fetchColumn();
    return $result;
  }

  function getTopicName($id) {
    global $db;
    $sql = 'SELECT "NAME" FROM "TABLE_TOPICS"
    WHERE "ID" = :id';
    $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':id' => $id));
    $result = $sth->fetchColumn();
    return $result;
  }

  function linkTopic($topicID, $scriptureID) {
    global $db;
    $sql = 'INSERT INTO "TABLE_COMP_TOPICS"(
      "SCRIPTURE_ID", "TOPIC_ID")
      VALUES (:scriptureID, :topicID)';
    $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':scriptureID' => $scriptureID, ':topicID' => $topicID));
  }

  function updateScripture($id, $content, &$topics) {

  }

  function insertScripture($book, $chapter, $verse, $content, &$topics) {
    global $db;
    $id = getScriptureID($book, $chapter, $verse);
    if (isset($id) && $id > 0) {
      updateScripture($id, $content, $topics);
    }
    else {
      $sql2 = 'INSERT INTO "TABLE_SCRIPTURES" (
        "BOOK", "CHAPTER", "VERSE", "CONTENT")
        VALUES (:book, :chapter, :verse, :content)';
      $sth = $db->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $sth->execute(array(':book' => $book, ':chapter' => $chapter, ':verse' => $verse, ":content" => $content));
      $id = getScriptureID($book, $chapter, $verse);
      if (isset($topics)) {
        foreach ($topics as $tval) {
          $tid = getTopicID($tval);
          if (isset($tid) && isset($id)) {
            linkTopic($tid, $id);
          }
        }
      }
    }
  }

  function printArray(&$array) {
    $n = count($array);
    foreach ($array as $value) {
      echo "$value";
      if($n > 1 ) {
        echo ", ";
      }
      $n--;
    }
  }

  function fetchTopics() {
    global $db;
    foreach ($db->query('SELECT "NAME" FROM "TABLE_TOPICS"') as $row)
    {
      echo "<label for='topic_$row[0]'>$row[0]</label><input type='checkbox' id='topic_$row[0]' name='topics[]' value='$row[0]'/><br />";
    }
  }

  function getTopics($scriptureID) {
    global $db;
    $sql = 'SELECT "TOPIC_ID" FROM "TABLE_COMP_TOPICS"
    WHERE "SCRIPTURE_ID" = :scriptureID';
    $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':scriptureID' => $scriptureID));
    $result = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
    $topicArray = array();
    foreach ($result as $tid) {
      $tname = getTopicName($tid);
      array_push($topicArray, $tname);
    }
    return $topicArray;
  }

  function fetchScriptures() {
    global $db;
    $topicArray = array();
    foreach ($db->query('SELECT * FROM "TABLE_SCRIPTURES"') as $row) {
      $id = getScriptureID($row[1], $row[2], $row[3]);
      $topicArray = getTopics($id);
      echo "<tr>
      <td>
      $row[1]
      </td>
      <td>
      $row[2]
      </td>
      <td>
      $row[3]
      </td>
      <td class='tableContent'>
      $row[4]
      </td>
      <td class='tableTopics'>";
      printArray($topicArray);
      echo "</td></tr>";
    }
  }
 ?>

<DOCTYPE! html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="scriptureStyle.css" />
</head>
<body>
<div class="title">
  <p>
    <h1>Scripture Database</h1>
  </p>
</div>
<div class="table-container">
  <table>
    <tr>
      <th>
        Book
      </th>
      <th>
        Chapter
      </th>
      <th>
        Verse
      </th>
      <th>
        Content
      </th>
      <th>
        Topics
      </th>
    </tr>
    <?php
    fetchScriptures();
     ?>
  </table>
</div>
</body>
</html>
</DOCTYPE!>
