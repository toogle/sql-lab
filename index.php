<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Лабораторная работа №1. SQL-инъекции</title>
		<meta name="description" content="SQL Injection Lab">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link href="css/bootstrap.min.css" rel="stylesheet">

		<style>
			.nav-inner {
				margin-left: 10px;
			}
		</style>

		<!--[if lt IE 9]>
			<script src="js/lib/respond.min.js"></script>
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
						<li class="active"><a href="#">Главная</a></li>
						<li><a href="documentation.php">Методическое пособие</a></li>
						<li>
							<a class="accordion-toggle" href="#cases" data-toggle="collapse">Рабочее задание</a>						
							<ul id="cases" class="nav nav-pills nav-stacked nav-inner collapse">
								<?php
								$pathsAreSet = $_COOKIE['pathsAreSet'];
								
								if ($pathsAreSet)
								{
									$taskNumber1 = $_COOKIE['taskNumber1'];
									$taskNumber2 = $_COOKIE['taskNumber2'];
									$taskNumber3 = $_COOKIE['taskNumber3'];
								}
								else
								{	
									$taskDirs = glob("cases/*", GLOB_ONLYDIR);
									$numberOfTasks = count($taskDirs);
									$taskNumbers = range(1, $numberOfTasks);
									
									$randomTasks = array_rand($taskNumbers, 3);								
									$taskNumber1 = $taskNumbers[$randomTasks[0]];
									$taskNumber2 = $taskNumbers[$randomTasks[1]];
									$taskNumber3 = $taskNumbers[$randomTasks[2]];
									
									setcookie('taskNumber1', $taskNumber1);
									setcookie('taskNumber2', $taskNumber2);
									setcookie('taskNumber3', $taskNumber3);
									setcookie('pathsAreSet', True);
								}
								
								$path1 = "cases/$taskNumber1/";
								$path2 = "cases/$taskNumber2/";
								$path3 = "cases/$taskNumber3/";
									
								$html  = '<li><a href="' . $path1 . '">Задание №1</a></li>';		
								$html .= '<li><a href="' . $path2 . '">Задание №2</a></li>';										
								$html .= '<li><a href="' . $path3 . '">Задание №3</a></li>';	

								echo $html;
								?>
							</ul>
						</li>
						<li><a href="https://github.com/toogle/sql-lab" target="_blank">Исходный код</a></li>
					</ul>
				</div>

				<div class="col-md-9">
					<div class="well well-lg">
						<p>
							Цель работы: получить практические навыки реализации SQL-инъекций.
						</p>

						<p>
							Для успешного выполнения лабораторной работы необходимо:
							<ul>
								<li>иметь представления о реляционных СУБД и языке SQL;</li>
								<li>понимать принципы реализации SQL-инъекций;</li>
								<li>ознакомиться с методическим пособием.</li>
							</ul>
						</p>
					</div>
				</div>
			</div>
		</div>

		<script src="js/lib/jquery-1.11.1.min.js"></script>
		<script src="js/lib/bootstrap.min.js"></script>
	</body>
</html>
