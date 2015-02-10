<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Задание №6 / Лабораторная работа №1. SQL-инъекции</title>
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
								<li class="active"><a href=".">Задание №6</a></li>
							</ul>
						</li>
						<li><a href="https://github.com/toogle/sql-lab" target="_blank">Исходный код</a></li>
					</ul>
				</div>

				<div class="col-md-9">
					<div class="well well-lg">					
						<h4>Самые покупаемые музыкальные альбомы в магазине Соль Диез</h4>
						
						<?php
						$mysqli = new mysqli('localhost', 'sql-lab', 'sql-lab', 'sql-lab');
						$mysqli->query("SET NAMES utf8");
						$mysqli->query("SET CHARACTER SET utf8");
						$mysqli->set_charset('utf8');	

						$sortcol = isset($_GET['sortcol']) ? $_GET['sortcol'] : 'id';
							
						// NOTE: The following code intended for demonstration purposes only.
						//       It is EXTREMELY DANGER to use it for real applications.
						$sql  = "SELECT title, year, country, length";
						$sql .= "  FROM albums";
						$sql .= "  ORDER BY $sortcol";

						$result = $mysqli->query($sql);
						if ($result) {
							$html  = "<form method='GET'>";
							$html .= "	<table class='table'>";
							$html .= "  	<tr class='success'>";
							$html .= "    		<th><button class='btn btn-link' 			  name='sortcol' type='submit' value='title'  >Альбом</button></th>";
							$html .= "    		<th><button class='btn btn-link center-block' name='sortcol' type='submit' value='year'   >Год выпуска</button></th>";
							$html .= "    		<th><button class='btn btn-link center-block' name='sortcol' type='submit' value='country'>Страна</button></th>";
							$html .= "    		<th><button class='btn btn-link center-block' name='sortcol' type='submit' value='length' >Длительность</button></th>";
							$html .= "  	</tr>";

							while ($row = $result->fetch_assoc()) {
								$html .= "  <tr>";
								$html .= "    <td>${row['title']}</td>";
								$html .= "    <td align='center'>${row['year']}</td>";
								$html .= "    <td align='center'>${row['country']}</td>";
								$html .= "    <td align='center'>${row['length']}</td>";
								$html .= "  </tr>";
							}

							$html .= "	</table>";
							$html .= "</form>";
							echo $html;

							$result->free();
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
								Это задание представляет собой таблицу, в которой можно сортировать ряды. 
								Здесь нужно реализовать слепую SQL-инъекцию, чтобы узнать системную
								информацию БД. Для примера предлагается вытащить значение системной 
								переменной <code>@@version</code>. На практике в таких случаях пишутся
								специальные скрипты, которые с помощью таких инъекций бит за битом достают 
								из БД все необходимые метаданные.
							</p>

							<p>
								В данном случае SQL-запрос может выглядеть, например, следующим образом:
								<pre><code><font color="#FF0000">"SELECT * FROM users ORDER BY $sortval"</font></code></pre>
								Во многих SQL-инъекциях испозуется оператор UNION. Однако после оператора
								ORDER BY, который присутствует в данном запросе, язык SQL не позволяет 
								поставить UNION. В этом случае можно воспользоваться условным выводом 
								информации из БД с помощью функции If или оператора CASE. В зависимости
								от некоторого условия информация будет отсортирована по разным столбцам. 
								Этим условием и может быть проверка системной информации.
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
