<?php
    if (isset($_GET['subject']))
    {
         // Обрабатываем полученный subject
        $subject = str_replace('"', '', $_GET['subject']);

        require_once 'createdb.php';

        // Запрос в бд по заданному subject'у
        $stmt = $db->prepare("SELECT * FROM formData WHERE subject=:subject");
        $stmt->bindValue(':subject', $subject, SQLITE3_TEXT);
        $result = $stmt->execute();

        // Выводим выбранный subject
        while ($row = $result->fetchArray())
        {
            echo '<div class="success-page">';
                echo '<div class="content-form content-success"><h3>Запрос:</h3>';
                echo 'Subject: ' . $row['subject'] . '<br>';
                echo 'Text: ' .$row['text'] . '<br>';
                echo 'Priority: ' .$row['priority'] . '<br>';
                echo 'Email: ' . $row['email'] . '<br>';
                echo 'Pin: ' .$row['pin'] . '<br><br>';
                echo '<button class="btn btn-success"><a href="index.php">OK</a></button></div>';
            echo '</div>';
        }
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Subject</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="main.js" defer></script>
</head>
<body>
</body>
</html>
