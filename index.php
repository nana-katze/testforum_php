<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/modern-css-reset/dist/reset.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
	<title>なんでも掲示板</title>
</head>
<body>
	<div class="wrapper">
		<h1 class="title">なんでも掲示板</h1>
		<p>なんでも書き込んでください</p>
		<form action="" method="post">
		<div class="inputarea">
			<label for="name">お名前</label>
			<input type="text" name="name" id="name">
		</div>
		<div class="inputarea">
			<label for="message">メッセージ</label>
			<textarea name="message" id="message" cols="20" rows="10"></textarea>
		</div>
		<input type="submit" name="btn_submit" value="投稿">
		</form>
	</div>
</body>
</html>