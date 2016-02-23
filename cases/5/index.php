<?php
require_once('../../config.php');

$conn = @mysqli_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
mysqli_query($conn, "SET NAMES utf8");
mysqli_query($conn, "SET CHARACTER SET utf8");
mysqli_set_charset($conn, 'utf8');

if (isset($_COOKIE['__img'])) {
	$img = htmlspecialchars($_COOKIE['__img']);
} else {
	$sql  = "SELECT MD5(name)";
	$sql .= "  FROM actors";
	$sql .= "  ORDER BY rand()";
	$sql .= "  LIMIT 1";

	$res = mysqli_query($conn, $sql);
	if ($res) {
		$row = mysqli_fetch_array($res);
		$img = $row[0];

		setcookie('__img', $img, -1, '/');

		mysqli_free_result($res);
	} else {
		die('Database error!');
	}
}

if (isset($_POST['answer'])) {
	// NOTE: The following code intended for demonstration purposes only.
	//       It is EXTREMELY DANGER to use it for real applications.
	$sql  = "SELECT name";
	$sql .= "  FROM actors";
	$sql .= "  WHERE name LIKE '%${_POST['answer']}%'";
	$sql .= "    AND MD5(name) = '${img}'";

	$res = mysqli_query($conn, $sql);
	if ($res) {
		if (mysqli_num_rows($res) > 0) {
			$row = mysqli_fetch_assoc($res);
			$answer = $row['name'];

			setcookie('__img', null, -1, '/');
		} else {
			$answer = false;
		}

		mysqli_free_result($res);
	}
} else {
	$answer = null;
}
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Задание №5 / Лабораторная работа №1. SQL-инъекции</title>
		<meta name="description" content="SQL Injection Lab">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link href="<?php echo MEDIA_URL; ?>/css/bootstrap.min.css" rel="stylesheet">

		<style>
			h4 {
				margin-bottom: 30px;
			}

			img {
				margin-bottom: 20px;
			}

			.nav-inner {
				margin-left: 10px;
			}
		</style>

		<!--[if lt IE 9]>
			<script src="<?php echo MEDIA_URL; ?>/js/lib/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<!--[if lt IE 7]>
			<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->

		<div class="container">
			<div class="page-header">
				<h1 class="text-center">Лабораторная работа №1 <small>&laquo;SQL-инъекции&raquo;</small></h1>
			</div>

			<div class="row">
				<div class="col-md-3">
					<ul class="nav nav-pills nav-stacked">
						<li><a href="../../">Главная</a></li>
						<li><a href="../../documentation.php">Методическое пособие</a></li>
						<li>
							<a href="#">Рабочее задание</a>
							<ul class="nav nav-pills nav-stacked nav-inner">
								<li><a href="../1/">Задание №1</a></li>
								<li><a href="../2/">Задание №2</a></li>
								<li><a href="../3/">Задание №3</a></li>
								<li><a href="../4/">Задание №4</a></li>
								<li class="active"><a href=".">Задание №5</a></li>
							</ul>
						</li>
						<li><a href="https://github.com/toogle/sql-lab" target="_blank">Исходный код</a></li>
					</ul>
				</div>

				<div class="col-md-9">
					<div class="well well-lg">
						<h4>Угадай актёра</h4>

						<?php
						$html = "<img src=\"" . MEDIA_URL . "/cases/5/img/${img}.jpg\" class=\"img-responsive\">";

						if (is_string($answer)) {
							$html .= "<div class=\"alert alert-success\">";
							$html .= "  Правильно! ${answer} &mdash; замечательный актёр современного кино.";
							$html .= "  <a href=\".\">Ещё &rarr;</a>";
							$html .= "</div>";
						} else {
							if (!is_null($answer))
								$html .= "<div class=\"alert alert-danger\">Это неправильный ответ. Попробуйте еще раз!</div>";

							$html .= "<form class=\"form-inline\" method=\"POST\">";
							$html .= "  <div class=\"form-group\">";
							$html .= "    <label for=\"answer-field\">Введите фамилию:</label>";
							$html .= "    <input type=\"text\" name=\"answer\" class=\"form-control\" id=\"answer-field\" autocomplete=\"off\" autofocus required>";
							$html .= "  </div>";
							$html .= "  <button type=\"submit\" class=\"btn btn-default\">OK</button>";
							$html .= "</form>";
						}

						echo $html;
						?>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							Подсказка
							<a href="#" class="pull-right" data-toggle="collapse" data-target="#hint">показать</a>
						</div>
						<div id="hint" class="panel-body collapse">
							<p>
								Это задание представляет собой игру, в которой введённый пользователем ответ
								проверяется сервером на соответствие с изображением. Результатом реализации
								SQL-инъекции будет получение информации из БД при ограниченном количестве
								столбцов.
							</p>

							<p>
								Сложность реализации SQL-инъекции может состоять в том, что из БД выбирается
								только одно поле, а нужно получить несколько (например, имя пользователя и
								пароль). В этом случае можно применять конкатенацию строк при помощи
								<tt>CONCAT()</tt>, которая позволяет объединить несколько колонок в одну строку.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="<?php echo MEDIA_URL; ?>/js/lib/jquery-1.11.1.min.js"></script>
		<script src="<?php echo MEDIA_URL; ?>/js/lib/bootstrap.min.js"></script>
		<script>
			$('#hint').on('show.bs.collapse', function() {
				$('a[data-target="#hint"]').html('скрыть');
			});

			$('#hint').on('hide.bs.collapse', function() {
				$('a[data-target="#hint"]').html('показать');
			});
		</script>
	</body>
</html>
