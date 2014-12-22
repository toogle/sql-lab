<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Задание №5 / Лабораторная работа №1. SQL-инъекции</title>
		<meta name="description" content="SQL Injection Lab">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link href="../../css/bootstrap.min.css" rel="stylesheet">

		<style>
			h4 {
				margin-bottom: 30px;
			}

			<!-- because http://stackoverflow.com/questions/14266175/how-do-i-left-align-these-bootstrap-form-items -->
			.form-horizontal .control-label {
			  text-align:left;
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
						<li><a href="../../documentation.php">Методическое пособие</a></li>
						<li>
							<a href="#">Рабочее задание</a>
							<ul class="nav nav-pills nav-stacked nav-inner">
								<?php								
								$taskNumber1 = $_COOKIE['taskNumber1'];
								$taskNumber2 = $_COOKIE['taskNumber2'];
								$taskNumber3 = $_COOKIE['taskNumber3'];
										
								$path1 = "../$taskNumber1/";
								$path2 = "../$taskNumber2/";
								$path3 = "../$taskNumber3/";
										
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
						<h4>Отгадайте актера</h4>	
						
						<?php						
						$mysqli = new mysqli('localhost', 'sql-lab', 'sql-lab', 'sql-lab');
						$mysqli->query("SET NAMES utf8");
						$mysqli->query("SET CHARACTER SET utf8");
						$mysqli->set_charset('utf8');	
						
						$path = $_COOKIE['path'];
						if (!$path)
						{
							$sql  = "SELECT path";
							$sql .= "	FROM actors";
							$sql .= "	ORDER BY rand()";
							$sql .= "	LIMIT 1";
						
							$result = $mysqli->query($sql);
							$row = $result->fetch_assoc();
							$path = $row['path'];
							
							setcookie('path', $path);
						}
						
						$html = "<img class='img-responsive' src='$path'/>";
						echo $html;
						
						?>	
						<p>	
						<form class="form-horizontal" method="POST">
							<div class="form-group">
								<label for="actor-field" class="col-md-1 control-label pull-left">Ответ:</label>
								<div class="col-md-5">
									<input type="text" class="form-control" id="actor-field" name="actor">
								</div>
								<button type="submit" class="btn btn-default">OK</button>
							</div>
						</form>
						
						<?php	
						$actor = $_POST['actor'];
						if (!empty($actor))
						{					
							// NOTE: The following code intended for demonstration purposes only.
							//       It is EXTREMELY DANGER to use it for real applications.
							$sql  = "SELECT name";
							$sql .= "	FROM actors";
							$sql .= "	WHERE path = '$path'";
							$sql .= "	AND name = '$actor'";
							
							$result = $mysqli->query($sql);
							if ($result->num_rows > 0)
							{
								// Perhaps a common programmer would just use the $actor var 
								// that is already set; but in this case the CONCAT injection
								// would not be possible. So this way is slightly artifitial.
								$row = $result->fetch_assoc();
								$name = $row['name'];
								echo "Правильно! $name - замечательный актер современного кино.";
							}
							else
							{
								echo "Это неверный ответ. Попробуйте еще раз.";
							}
							
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
								Данное задание представляет собой мини-игру, где введенный пользователем ответ
								проверяется сервером на соответствие с выведенной картинкой. Как и в некоторых
								других заданиях, реализованная здесь SQL-инъекция позволяет узнать различную
								конфиденциальную информацию из БД.
							</p>

							<p>
								Сложность здесь состоит в том, что место вывода серверной информации не сразу
								очевидно и представляет собой единственное поле. В таких случаях помогает
								оператор CONCAT, позволяющий объединять данные из несколько полей в одно.
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
