<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Задание №1 / Лабораторная работа №1. SQL-инъекции</title>
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
								<li class="active"><a href=".">Задание №1</a></li>
								<li><a href="../2/">Задание №2</a></li>
								<li><a href="../3/">Задание №3</a></li>
							</ul>
						</li>
						<li><a href="https://github.com/toogle/sql-lab">Исходный код</a></li>
					</ul>
				</div>

				<div class="col-md-9">
					<div class="well well-lg">
						<h4>Авторизация</h4>
						<?php
						if (isset($_POST['login']) && isset($_POST['password'])) {
							// NOTE: The following code intended for demonstration purposes only.
							//       It is EXTREMELY DANGER to use it for real applications.
							$conn = @mysql_connect('localhost', 'sql-lab', 'sql-lab');
							@mysql_select_db('sql-lab', $conn);

							$sql  = "SELECT login, password";
							$sql .= "  FROM users";
							$sql .= "  WHERE login = '${_POST['login']}'";
							$sql .= "    AND password = SHA1('${_POST['password']}')";

							if (preg_match('/INSERT|UPDATE|DELETE|CREATE|ALTER|DROP/i', $sql)) {
								die('Запрос не может быть выполнен: обнаружен недопустимый оператор!');
							}

							$res = mysql_query($sql, $conn);
							if ($res && mysql_num_rows($res) != 0)
								echo '<div class="alert alert-success">Вы успешно авторизованы!</div>';
							else
								echo '<div class="alert alert-danger">Неверный логин и/или пароль!</div>';
						}
						?>

						<form class="form-horizontal" method="POST">
							<div class="form-group">
								<label for="login-field" class="col-lg-2 control-label">Логин</label>
								<div class="col-lg-4">
									<input type="text" name="login" class="form-control" id="login-field" autofocus required>
								</div>
							</div>
							<div class="form-group">
								<label for="password-field" class="col-lg-2 control-label">Пароль</label>
								<div class="col-lg-4">
									<input type="password" name="password" class="form-control" id="password-field" required>
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-4">
									<button type="submit" class="btn btn-primary">Войти</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
