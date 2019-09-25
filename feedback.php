<?php
session_start();
$requestType = $_SERVER["REQUEST_METHOD"] === 'POST' ? true : false;

if ($requestType) {
  require_once './db.php';

  $data = [
    "name" => $_POST["name"],
    "content" => $_POST["content"],
    "rate" => (int) $_POST["rate"]
  ];


  if (!$data["rate"]) {
    echo "$key should be number";
    return http_response_code(400);
  }

  foreach ($data as $key => $value) {
    if (!$value || empty($value)) {
      echo "$key is required!";
      return http_response_code(400);
    }
  }

  $stmt = $connection->prepare('INSERT INTO feedbacks (name, content, rate) VALUES (?, ?, ?)');
  $stmt->bind_param('ssi', $data["name"], $data["content"], $data["rate"]);
  $stmt->execute();
  $stmt->close();

  $_SESSION['rated'] = true;
  return header('Location: success.php');
}

echo "<h1>Method not allowed.</h1>";
return http_response_code(405); // return bad request status
