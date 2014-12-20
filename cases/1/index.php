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
								<li><a href="../6/">Задание №6</a></li>
							</ul>
						</li>
						<li><a href="https://github.com/toogle/sql-lab" target="_blank">Исходный код</a></li>
					</ul>
				</div>

				<div class="col-md-9">
					<div class="well well-lg">
						<h4>Авторизация</h4>
						<?php
						if (isset($_POST['login']) && isset($_POST['password'])) {
							// NOTE: The following code intended for demonstration purposes only.
							//       It is EXTREMELY DANGER to use it for real applications.
							$conn = @mysqli_connect('localhost', 'sql-lab', 'sql-lab', 'sql-lab');
							mysqli_query($conn, "SET NAMES utf8");
							mysqli_query($conn, "SET CHARACTER SET utf8");
							mysqli_set_charset($conn, 'utf8');

							$sql  = "SELECT login, password";
							$sql .= "  FROM users";
							$sql .= "  WHERE login = '${_POST['login']}'";
							$sql .= "    AND password = SHA1('${_POST['password']}')";

							if (preg_match('/INSERT|UPDATE|DELETE|CREATE|ALTER|DROP/i', $sql)) {
								die('Запрос не может быть выполнен: обнаружен недопустимый оператор!');
							}

							$res = mysqli_query($conn, $sql);
							if ($res) {
								if (mysqli_num_rows($res) != 0)
									echo '<div class="alert alert-success">Вы успешно авторизованы!</div>';
								else
									echo '<div class="alert alert-danger">Неверный логин и/или пароль!</div>';

								mysqli_free_result($res);
							}
						}
						?>

						<form class="form-horizontal" method="POST">
							<div class="form-group">
								<label for="login-field" class="col-lg-2 control-label">Логин</label>
								<div class="col-lg-4">
									<input type="text" name="login" class="form-control" id="login-field" autocomplete="off" autofocus required>
								</div>
							</div>
							<div class="form-group">
								<label for="password-field" class="col-lg-2 control-label">Пароль</label>
								<div class="col-lg-4">
									<input type="password" name="password" class="form-control" id="password-field" autocomplete="off" required>
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-4">
									<button type="submit" class="btn btn-primary">Войти</button>
								</div>
							</div>
						</form>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							Подсказка
							<a href="#" class="pull-right" data-toggle="collapse" data-target="#hint">показать</a>
						</div>
						<div id="hint" class="panel-body collapse">
							<p>
								Это задание имитирует простейшую форму авторизации на сайте. Результатом реализации
								SQL-инъекции будет прохождение процедуры авторизации без обладания необходимыми для
								этого идентификационными данными.
							</p>

							<p>
								Уязвимый к SQL-инъекции код выглядит примерно так:
<pre><code><font color="#009900">$username</font> <font color="#990000">=</font> <font color="#009900">$_GET</font><font color="#990000">[</font><font color="#FF0000">'username'</font><font color="#990000">];</font>
<font color="#009900">$password</font> <font color="#990000">=</font> <font color="#009900">$_GET</font><font color="#990000">[</font><font color="#FF0000">'password'</font><font color="#990000">];</font>
<font color="#009900">$result</font> <font color="#990000">=</font> <b><font color="#000000">mysql_query</font></b><font color="#990000">(</font><font color="#FF0000">"SELECT * FROM users WHERE username = '$username' AND password = SHA1('$password')"</font><font color="#990000">);</font></code></pre>

								Таким образом, для реализации атаки необходимо подобрать такие значения имени
								пользователя и пароля, которые изменят логику выполнения запроса и позволят избежать
								непосредственной проверки идентификационных данных. Одним из таких вариантов является
								внедрение заведомо верного условия (например, <code>'1' = '1'</code>) совместно с
								логическим оператором ИЛИ (OR).
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
