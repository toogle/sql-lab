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
						$conn = @mysql_connect('localhost', 'sql-lab', 'sql-lab');
						@mysql_select_db('sql-lab', $conn);

						if (isset($_GET['id'])) {
							$sql  = "SELECT rating, title, year, director, review, votes";
							$sql .= "  FROM reviews";
							$sql .= "  WHERE id = ${_GET['id']}";

							if (preg_match('/INSERT|UPDATE|DELETE|CREATE|ALTER|DROP/i', $sql)) {
								die('Запрос не может быть выполнен: обнаружен недопустимый оператор!');
							}

							$res = mysql_query($sql, $conn);
							if ($res) {
								$row = mysql_fetch_assoc($res);

								$html  = "<h4>${row['title']} <small>(${row['year']})</small></h4>";
								$html .= "<p>${row['review']}</p>";
								$html .= "<p><b>Режиссёр:</b> ${row['director']}</p>";
								$html .= "<p><b>Рейтинг:</b> " . number_format($row['rating'], 1) . " (${row['votes']} голосов)</p>";
								$html .= "<a href=\".\" class=\"btn btn-default\">Вернуться к списку</a>";

								echo $html;
							}
						} else {
							echo "<h4>Список фильмов</h4>";

							$sql  = "SELECT id, rating, title, year, director";
							$sql .= "  FROM reviews";
							$sql .= "  ORDER BY rating DESC";

							$res = mysql_query($sql, $conn);
							if ($res) {
								$html  = "<table class=\"table\">";
								$html .= "  <tr>";
								$html .= "    <th>Рейтинг</th>";
								$html .= "    <th>Название</th>";
								$html .= "    <th>Режиссёр</th>";
								$html .= "  </tr>";

								while ($row = mysql_fetch_assoc($res)) {
									$html .= "  <tr>";
									$html .= "    <td>" . number_format($row['rating'], 1) . "</td>";
									$html .= "    <td><a href=\"?id=${row['id']}\">${row['title']} (${row['year']})</a></td>";
									$html .= "    <td>${row['director']}</td>";
									$html .= "  </tr>";
								}

								$html .= "</table>";

								echo $html;
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
