<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Задание №4 / Лабораторная работа №1. SQL-инъекции</title>
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
								<li><a href="../3/">Задание №3</a></li>
								<li class="active"><a href=".">Задание №4</a></li>
							</ul>
						</li>
						<li><a href="https://github.com/toogle/sql-lab" target="_blank">Исходный код</a></li>
					</ul>
				</div>

				<div class="col-md-9">
					<div class="well well-lg">
						<h4>Список музыкальных альбомов</h4>

						<?php
						// NOTE: The following code intended for demonstration purposes only.
						//       It is EXTREMELY DANGER to use it for real applications.
						$conn = @mysqli_connect('localhost', 'sql-lab', 'sql-lab', 'sql-lab');
						mysqli_query($conn, "SET NAMES utf8");
						mysqli_query($conn, "SET CHARACTER SET utf8");
						mysqli_set_charset($conn, 'utf8');

						$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';

						$sql  = "SELECT title, year, country, length";
						$sql .= "  FROM albums";
						$sql .= "  ORDER BY ${sort}";

						$res = mysqli_query($conn, $sql);
						if ($res) {
							$html .= "<table class=\"table\">";
							$html .= "  <tr class=\"info\">";
							$html .= "    <th><a href=\"?sort=title\">Альбом</a></th>";
							$html .= "    <th><a href=\"?sort=year\">Год выпуска</a></th>";
							$html .= "    <th><a href=\"?sort=country\">Страна</a></th>";
							$html .= "    <th><a href=\"?sort=length\">Длительность</a></th>";
							$html .= "  </tr>";

							while ($row = mysqli_fetch_assoc($res)) {
								$html .= "  <tr>";
								$html .= "    <td>${row['title']}</td>";
								$html .= "    <td align=\"center\">${row['year']}</td>";
								$html .= "    <td align=\"center\">${row['country']}</td>";
								$html .= "    <td align=\"center\">${row['length']}</td>";
								$html .= "  </tr>";
							}

							$html .= "</table>";

							echo $html;

							mysqli_free_result($res);
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
								В этом задании представлена таблица, которую можно сортировать по колонкам.
								Результатом реализации SQL-инъекции будет возможность проверять значения различных
								системных переменных, например, <code>@@version</code>, содержащей номер версии MySQL.
							</p>

							<p>
								В ряде случаев реализация SQL-инъекции может осложняться тем, что можно влиять
								только на параметры, подставляемые в операторы <tt>GROUP BY</tt> или <tt>ORDER BY</tt>.
								Это существенно ограничивает возможности по внедрению SQL-операторов. В таких случаях
								можно применять условные операторы <tt>IF</tt> или <tt>CASE</tt> и проверять значения
								выражений, наблюдая за изменением группировок или порядка результатов на странице.
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
