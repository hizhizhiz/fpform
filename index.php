<?php
    $error = null;
    $errorMessage = [];

    // Если форма отправлена, то обрабатываем данные(валидация)
	if (isset($_POST['Submit']))
	{
		$subject = $_POST['Subject'];
		$text = $_POST['Text'];
		$priority = $_POST['Priority'];
		$email = $_POST['Email'];
		$pin = $_POST['Pin'];

		if ( trim($subject) == '' )
        {
        	$error = true;
            $errorMessage['subject'] = '<span class="error">Некорректное поле</span>';
        }
        else
        {
            filter_var($subject, FILTER_SANITIZE_STRING);
        }

		if ( trim($text) == '' )
        {
            $error = true;
            $errorMessage['text'] = '<span class="error">Некорректное поле</span>';
        }
        else
        {
            filter_var($text, FILTER_SANITIZE_STRING);
        }

        if ( trim($email) == '' )
        {
            $error = true;
            $errorMessage['email'] = '<span class="error">Некорректное поле</span>';
        }
        else
        {
            filter_var($email, FILTER_VALIDATE_EMAIL);
        }

        if ( trim($pin) == '' )
        {
            $error = true;
            $errorMessage['pin'] = '<span class="error">Некорректное поле</span>';
        }
        else
        {
            filter_var($pin, FILTER_VALIDATE_INT);
        }

        // Если все данные прошли валидацию, то записываем в бд
        if (!$error)
        {
            require_once 'createdb.php';

            $stmt = $db->prepare("INSERT INTO formData (subject, text, priority, email, pin)
                    VALUES (:subject, :text, :priority, :email, :pin)");
            $stmt->bindValue(':subject', $subject, SQLITE3_TEXT);
            $stmt->bindValue(':text', $text, SQLITE3_TEXT);
            $stmt->bindValue(':priority', $priority, SQLITE3_INTEGER);
            $stmt->bindValue(':email', $email, SQLITE3_TEXT);
            $stmt->bindValue(':pin', $pin, SQLITE3_INTEGER);
            $stmt->execute();

            //  Узнаем id
            $rowId= $db->querySingle('SELECT id FROM formData WHERE id=last_insert_rowid()');
            // Записываем куки
            setcookie($rowId, $pin, time()+3600);

            // Выводим только что записанные данные пользователю
            $results = $db->query('SELECT * FROM formData WHERE id=last_insert_rowid()');
            while ($row = $results->fetchArray())
            {
                echo '<div class="success-page">';
                    echo '<div class="content-form content-success"><h3>Запрос успешно отправлен!</h3>';
                    echo 'Subject: ' . $row['subject'] . '<br>';
                    echo 'Text: ' .$row['text'] . '<br>';
                    echo 'Priority: ' .$row['priority'] . '<br>';
                    echo 'Email: ' . $row['email'] . '<br>';
                    echo 'Pin: ' .$row['pin'] . '<br><br>';
                    echo '<button class="btn btn-success"><a href="index.php">OK</a></button></div>';
                echo '</div>';
            }
        }
 	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Test</title>
	<!-- Bootstrap -->
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="main.js" defer></script>
</head>
<body>

    <main class="main-content">
        <div class="content-form">
            <form id="testForm" action="index.php" method="POST">
                <label for="subject">Subject</label>
                <input id="subject" onkeyup="validate('subject');" type="text" name="Subject" value="<?php
                    echo ( isset($_POST['Subject']) ) ? $subject : ''; ?>">
                <?php echo ( isset($errorMessage['subject']) ) ? $errorMessage['subject'] : ''; ?><br>

                <label for="text">Text:</label><br>
                <textarea id="text" onkeyup="validate('text');" cols="27" rows="5" name="Text"><?php
                    echo ( isset($_POST['Text']) ) ? $text : ''; ?></textarea>
                <?php echo ( isset($errorMessage['text']) ) ? $errorMessage['text'] : ''; ?><br>

                <label for="priority">Priority</label>
                    <select id="priority" name="Priority">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3" selected>3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                     </select><br>

                <label for="email">Email &nbsp; &nbsp;</label>
                <input id="email" onkeyup="validate('email');" type="email" name="Email" value="<?php echo ( isset($_POST['Email']) ) ? $email : ''; ?>">
                <?php echo ( isset($errorMessage['email']) ) ? $errorMessage['email'] : ''; ?><br>

                <label for="pin">Pin &nbsp; &nbsp; &nbsp; &nbsp;</label>
                <input id="pin" onkeyup="validate('pin');" type="text" name="Pin" value="<?php echo ( isset($_POST['Pin']) ) ? $pin : ''; ?>">
                <?php echo ( isset($errorMessage['pin']) ) ? $errorMessage['pin'] : ''; ?><br><br>

                <button id="button" type="submit" name="Submit" disabled>POST</button>
            </form>
        </div>
        <div class="subjects">
            <h4>Предыдущие запросы:</h4>
            <hr>
            <?php
                if ($_COOKIE)
                {
                    require_once 'createdb.php';

                    // Получаем данные из куки
                    $cookId = array_keys($_COOKIE);
                    $cookValue = array_values($_COOKIE);

                    // Выводим список subject'ов
                    for ($i = 0; $i < count($cookId); $i++)
                    {
                        $subjects[$i] = $db->query("SELECT subject FROM formData WHERE id=$cookId[$i]; pin=$cookValue[$i]");
                        $row = $subjects[$i]->fetchArray();
                        $res = json_encode($row['subject'], JSON_UNESCAPED_UNICODE);
                        echo "<a href='subject.php?subject=". $res ."'>" . str_replace('"', '', $res) . "</a><br><hr>";
                    }
                }
                else
                {
                    echo 'Пусто';
                }
            ?>
        </div>
    </main>

</body>
</html>