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
						<h4>Прайс-лист на кофе</h4>
						<table class="table">
							<tr>
								<th>Марка</td>							
								<th>Соотношение арабика/робуста, %</td>
								<th>Цена, руб./кг.</td>
							</tr>
							<?php
							date_default_timezone_set('Europe/Moscow');

							// NOTE: The following code intended for demonstration purposes only.
							//       It is EXTREMELY DANGER to use it for real applications.
							$conn = @mysql_connect('localhost', 'sql-lab', 'sql-lab');
							@mysql_select_db('sql-lab', $conn);

							$manufacturer = isset($_GET['manufacturer']) ? $_GET['manufacturer'] : $null;
							
							$sql  = "SELECT name, ratio, price";
							$sql .= "  FROM coffee";
							$sql .= "  WHERE manufacturer = '${manufacturer}'";

							if (preg_match('/INSERT|UPDATE|DELETE|CREATE|ALTER|DROP/i', $sql)) {
								die('Запрос не может быть выполнен: обнаружен недопустимый оператор!');
							}

							$res = mysql_query($sql, $conn);
							if ($res) {
								if (mysql_num_rows($res) > 0) {
									while ($row = mysql_fetch_array($res)) {
										$html  = "<tr>";
										$html .= "  <td>${row['name']}</td>";
										$html .= "  <td>${row['ratio']}</td>";
										$html .= "  <td>${row['price']}</td>";
										$html .= "</tr>";
	
										echo $html;
									}
								} else {
									$html  = "<tr>";
									$html .= "  <td colspan=\"5\">Ничего не найдено.</td>";
									$html .= "</tr>";
									
									echo $html;
								}
							}
							?>
						</table>

						<form class="form-inline" method="GET">
							<div class="form-group">
								<select class="form-control" id="manufacturer-select" name="manufacturer">
									<option>Выберите страну-производителя</option>
									<option value="Italy">Италия</option>
									<option value="Spain">Испания</option>
									<option value="Belgium">Бельгия</option>
									<option value="Switzerland">Швейцария</option>
									<option value="India">Индия</option>
									<option value="Dominicana">Доминикана</option>
									<option value="Russia">Россия</option>
									<option value="Finland">Финляндия</option>
								</select>
							</div>
							<button type="submit" class="btn btn-default">Показать кофе</button>
						</form>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							Подсказка
							<a href="#" class="pull-right" data-toggle="collapse" data-target="#hint">показать</a>
						</div>
						<div id="hint" class="panel-body collapse">
							<p>
								С помощью адресной строки можно попробовать вытащить не только данные таблицы 
								для этой страницы, но и данные из каких-нибудь других таблиц в этой БД. Таким 
								образом можно, например, узнать логины и пароли пользователей, которые
								используются на этом же сайте на странице авторизации. 								
							</p>
							
							<p>
								Для того, чтобы это сделать, можно воспользоваться SQL-оператором UNION, который
								позволяет объединять результаты нескольких запросов, в том числе в разные таблицы.
								Однако необязательно сразу пытаться угадывать и подбирать возможные имена таких 
								таблиц и их колонок. В БД существуют виртуальные таблицы, содержащие ее метаданные. 
								В mySQL они находятся в схеме <i>information_schema</i>.
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
