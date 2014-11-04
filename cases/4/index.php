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
			.heading {
				font-size: 14pt;
			}
			
			.nav-inner {
				margin-left: 10px;
			}
			
			.morespace {
				margin-bottom: 30px;
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
						<form class="form-inline morespace" method="GET">
							<span class="heading">Прайс-лист на кофе</span>		
							<div class="btn-group navbar-right" id="manufacturers">
								<button name="manufacturer" class="btn btn-default" value="Italy"><img src="flags/it.png" title="Италия"/></button>
								<button name="manufacturer" class="btn btn-default" value="Spain"><img src="flags/es.png" title="Испания"/></button>
								<button name="manufacturer" class="btn btn-default" value="Belgium"><img src="flags/bg.png" title="Бельгия"/></button>
								<button name="manufacturer" class="btn btn-default" value="Switzerland"><img src="flags/ch.png" title="Швейцария"/></button>
								<button name="manufacturer" class="btn btn-default" value="India"><img src="flags/in.png"/ title="Индия"></button>
								<button name="manufacturer" class="btn btn-default" value="Dominicana"><img src="flags/do.png"/ title="Доминикана"></button>
								<button name="manufacturer" class="btn btn-default" value="Russia"><img src="flags/ru.png"/ title="Россия"></button>
								<button name="manufacturer" class="btn btn-default" value="Finland"><img src="flags/fi.png" title="Финляндия"/></button>
							</div>							
						</form>
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
							$conn = @mysqli_connect('localhost', 'sql-lab', 'sql-lab', 'sql-lab');
							mysqli_query($conn, "SET NAMES utf8");
							mysqli_query($conn, "SET CHARACTER SET utf8");
							mysqli_set_charset($conn, 'utf8');

							$manufacturer = isset($_GET['manufacturer']) ? $_GET['manufacturer'] : $null;
							
							if (isset($manufacturer))
							{
								$sql  = "SELECT name, ratio, price";
								$sql .= "  FROM coffee";
								$sql .= "  WHERE manufacturer = '${manufacturer}'";
							}
							else
							{
								$sql  = "SELECT * ";
								$sql .= "  FROM coffee";
								$sql .= "  ORDER BY price";
							}

							if (preg_match('/INSERT|UPDATE|DELETE|CREATE|ALTER|DROP/i', $sql)) {
								die('Запрос не может быть выполнен: обнаружен недопустимый оператор!');
							}

							$res = mysqli_query($conn, $sql);
							if ($res) {
								if (mysqli_num_rows($res) > 0) {
									while ($row = mysqli_fetch_assoc($res)) {
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
							
							mysqli_free_result($res);
							?>
						</table>
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
								В MySQL они находятся в схеме <i>information_schema</i>.
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
