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
		// 入力チェック
		if(empty($_POST['name'])){
			$error_message[] = '名前を入力してください。';
		} else{
			$clean['name'] = htmlspecialchars($_POST['name'], ENT_QUOTES);
		}
		if(empty($_POST['message'])){
			$error_message[] = 'メッセージを入力してください。';
		} else{
			$clean['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);
		}

		if(empty($error_message)){
			// 投稿日時を取得
			$now_date = date("Y-m-d H:i:s");

			try {
				// データベースに接続
				$pdo = new PDO(DSN, DB_USER, DB_PASS);
				$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

				// SQL生成
				$stmt = $pdo->prepare('INSERT INTO board( name, message, post_date ) VALUES( :name, :message, :post_date )');
				// 値を挿入
				$stmt->bindValue(':name', $clean['name'], PDO::PARAM_STR);
				$stmt->bindValue(':message', $clean['message'], PDO::PARAM_STR);
				$stmt->bindValue(':post_date', date("Y-m-d H:i:s", strtotime($now_date)), PDO::PARAM_STR);
				// 実行
				$stmt->execute();

				// DB切断
				$stmt = null;
				$pdo = null;

			} catch (PDOException $e) {
				echo $e->getMessage();
				exit;
			}

			$_SESSION['success_message'] = 'メッセージを投稿しました。';
			header('Location: ./');
		}
	}

	try {
			// データベースに接続
			$pdo = new PDO(DSN, DB_USER, DB_PASS);
			$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

			// SQL生成
			$stmt = $pdo->prepare('SELECT name, message, post_date FROM board ORDER BY post_date DESC');
			// 実行
			$stmt->execute();
			// 結果を取得
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			// DB切断
			$stmt = null;
			$pdo = null;

	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
}

?>

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
		<?php if(empty($_POST['btn_submit']) && !empty($_SESSION['success_message'])): ?>
			<p class="success_message"><?php echo $_SESSION['success_message']; ?></p>
			<?php unset($_SESSION['success_message']); ?>
		<?php endif; ?>
		<?php if(!empty($error_message)): ?>
			<ul class="error_message">
				<?php foreach($error_message as $value): ?>
					<li>・<?php echo $value; ?></li>
				<?php endforeach ?>
			</ul>
		<?php endif; ?>
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
			<input class="btn btn_submit" type="submit" name="btn_submit" value="投稿">
		</form>
		<section>
			<?php if(!empty($result)): ?>
				<?php foreach($result as $value): ?>
					<article>
						<div class="info">
							<div class="info-header">
								<h2><?php echo $value['name']; ?></h2>
								<time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
							</div>
						</div>
						<p><?php echo nl2br($value['message']); ?></p>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
		</section>
	</div>
</body>
</html>