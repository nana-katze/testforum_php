<?php
	require_once('config.php');
	
	// タイムゾーン設定
	date_default_timezone_set('Asia/Tokyo');

	// 変数の初期化
	$now_date = null;
	$data = null;
	$split_data = null;
	$message = array();
	$result = array();
	$success_message = null;
	$error_message = array();
	$clean = array();

	session_start();

	if(!empty($_POST['btn_submit'])){
		if(!empty($_POST['admin_password']) && $_POST['admin_password'] === PASSWORD){
			$_SESSION['admin_login'] = true;
		} else{
			$error_message[] = 'ログインに失敗しました。';
		}
	}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/modern-css-reset/dist/reset.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
	<title>なんでも掲示板 管理ページ</title>
</head>
<body>
	<div class="wrapper">
		<?php if(!empty($error_message)): ?>
			<ul class="error_message">
				<?php foreach($error_message as $value): ?>
					<li>・<?php echo $value; ?></li>
				<?php endforeach ?>
			</ul>
		<?php endif; ?>
		<h1 class="title">なんでも掲示板 管理ページ</h1>
		<section>
			<?php if(!empty($_SESSION['admin_login']) && $_SESSION['admin_login'] === true): ?>
				<?php if(!empty($result)): ?>
					<?php foreach($result as $value): ?>
						<article>
							<div class="info">
								<h2><?php echo $value['name']; ?></h2>
								<time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
							</div>
							<p><?php echo $value['message']; ?></p>
						</article>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php else: ?>
				<form action="" method="post">
					<div>
						<label for="admin_password">ログインパスワード</label>
						<input type="password" id="admin_password" name="admin_password" value="">
					</div>
						<input type="submit" name="btn_submit" value="ログイン">
				 </form>
			<?php endif; ?>
		</section>
	</div>
</body>
</html>