<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Задание №3 / Лабораторная работа №1. SQL-инъекции</title>
		<meta name="description" content="SQL Injection Lab">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link href="../../css/bootstrap.min.css" rel="stylesheet">

		<style>
			h4 {
				margin-bottom: 30px;
			}

			.nav-inner {
				margin-left: 10px;
			}
		</style>

		<!--[if lt IE 9]>
			<script src="../../js/lib/respond.min.js"></script>
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
						<li><a href="../../documentation.html">Методическое пособие</a></li>
						<li>
							<a href="#">Рабочее задание</a>
							<ul class="nav nav-pills nav-stacked nav-inner">
								<li><a href="../1/">Задание №1</a></li>
								<li><a href="../2/">Задание №2</a></li>
								<li class="active"><a href=".">Задание №3</a></li>
								<li><a href="../4/">Задание №4</a></li>
								<li><a href="../5/">Задание №5</a></li>
								<li><a href="../6/">Задание №6</a></li>
							</ul>
						</li>
						<li><a href="https://github.com/toogle/sql-lab" target="_blank">Исходный код</a></li>
					</ul>
				</div>

				<div class="col-md-9">
					<div class="well well-lg">
						<?php
						// NOTE: The following code intended for demonstration purposes only.
						//       It is EXTREMELY DANGER to use it for real applications.
						$conn = @mysqli_connect('localhost', 'sql-lab', 'sql-lab', 'sql-lab');
						mysqli_query($conn, "SET NAMES utf8");
						mysqli_query($conn, "SET CHARACTER SET utf8");
						mysqli_set_charset($conn, 'utf8');

						if (isset($_GET['id'])) {
							$sql  = "SELECT rating, title, year, director, review, votes";
							$sql .= "  FROM reviews";
							$sql .= "  WHERE id = ${_GET['id']}";

							if (preg_match('/INSERT|UPDATE|DELETE|CREATE|ALTER|DROP/i', $sql)) {
								die('Запрос не может быть выполнен: обнаружен недопустимый оператор!');
							}

							$res = mysqli_query($conn, $sql);
							if ($res) {
								$row = mysqli_fetch_assoc($res);

								$html  = "<h4>${row['title']} <small>(${row['year']})</small></h4>";
								$html .= "<p>${row['review']}</p>";
								$html .= "<p><b>Режиссёр:</b> ${row['director']}</p>";
								$html .= "<p><b>Рейтинг:</b> " . number_format($row['rating'], 1) . " (${row['votes']} голосов)</p>";
								$html .= "<a href=\".\" class=\"btn btn-default\">Вернуться к списку</a>";

								echo $html;

								mysqli_free_result($res);
							}
						} else {
							echo "<h4>Список фильмов</h4>";

							$sql  = "SELECT id, rating, title, year, director";
							$sql .= "  FROM reviews";
							$sql .= "  ORDER BY rating DESC";

							$res = mysqli_query($conn, $sql);
							if ($res) {
								$html  = "<table class=\"table\">";
								$html .= "  <tr>";
								$html .= "    <th>Рейтинг</th>";
								$html .= "    <th>Название</th>";
								$html .= "    <th>Режиссёр</th>";
								$html .= "  </tr>";

								while ($row = mysqli_fetch_assoc($res)) {
									$html .= "  <tr>";
									$html .= "    <td>" . number_format($row['rating'], 1) . "</td>";
									$html .= "    <td><a href=\"?id=${row['id']}\">${row['title']} (${row['year']})</a></td>";
									$html .= "    <td>${row['director']}</td>";
									$html .= "  </tr>";
								}

								$html .= "</table>";

								echo $html;

								mysqli_free_result($res);
							}
						}
						?>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							Подсказка
							<a href="#" class="pull-right" data-toggle="collapse" data-target="#hint">показать</a>
						</div>
						<div id="hint" class="panel-body collapse">
							<p>
								Это задание имитирует таблицу с рядом наименований, по каждому из которых
								можно получить дополнительную информацию. Результатом реализации SQL-инъекции
								будет возможность проверять значения различных системных переменных, например,
								<code>@@version</code>, содержащей номер версии MySQL.
							</p>

							<p>
								В некоторых случаях SQL-инъекция может быть реализована, но результат выполнения
								запроса пользователю не представляется. Тогда может быть проведена слепая
								(<i>blind</i>) инъекция. Для проверки её выполнимости к уязвимому параметру
								необходимо дописать сначала верное условие (<code>AND 1 = 1</code>), а затем
								неверное (<code>AND 1 = 2</code>) и убедиться, что в первом случае страница не
								изменяется.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="../../js/lib/jquery-1.11.1.min.js"></script>
		<script src="../../js/lib/bootstrap.min.js"></script>
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
