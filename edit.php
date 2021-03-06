<?php
	require_once('config.php');
	
	// タイムゾーン設定
	date_default_timezone_set('Asia/Tokyo');

	// 変数の初期化
	$message_id = null;
	$result = array();
	$error_message = array();

	session_start();

	// 管理者としてログインしているか確認
	if(empty($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true){
		// ログインページへリダイレクト
		header("Location: ./admin.php");
	}

	if(!empty($_GET['message_id']) && empty($_POST['message_id'])){
		$message_id = (int)htmlspecialchars($_GET['message_id'], ENT_QUOTES);

		try {
			// データベースに接続
			$pdo = new PDO(DSN, DB_USER, DB_PASS);
			$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

			// SQL生成
			$stmt = $pdo->prepare('SELECT id, name, message, post_date FROM board WHERE id = :message_id');
			$stmt->bindValue(':message_id', $message_id, PDO::PARAM_INT);
			// 実行
			$stmt->execute();

			if($stmt){
				// 結果を取得
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			} else{
				// データが読み込めない場合、一覧に戻る
				header("Location: ./admin.php");
			}

			// DB切断
			$stmt = null;
			$pdo = null;

		} catch (PDOException $e) {
			echo $e->getMessage();
			exit;
		}
	} elseif(!empty($_POST['message_id'])){
		$message_id = (int)htmlspecialchars($_POST['message_id'], ENT_QUOTES);

		if(empty($_POST['name'])){
			$error_message[] = '名前を入力してください。';
		} else{
			$result['name'] = htmlspecialchars($_POST['name'], ENT_QUOTES);
		}

		if(empty($_POST['message'])){
			$error_message[] = 'メッセージを入力してください。';
		} else{
			$result['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);
		}

		if(empty($error_message)){
			try {
				// データベースに接続
				$pdo = new PDO(DSN, DB_USER, DB_PASS);
				$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
				// SQL生成
				$stmt = $pdo->prepare("UPDATE board SET name = '$result[name]', message = '$result[message]' WHERE id = :message_id");
				$stmt->bindValue(':message_id', $message_id, PDO::PARAM_INT);
				// 実行
				$stmt->execute();

				// DB切断
				$stmt = null;
				$pdo = null;

				// 一覧に戻る
				header("Location: ./admin.php");
	
			} catch (PDOException $e) {
				echo $e->getMessage();
				exit;
			}
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
	<title>なんでも掲示板 管理ページ(投稿の編集)</title>
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
		<h1 class="title">なんでも掲示板 管理ページ(投稿の編集)</h1>
		<form action="" method="post">
			<?php foreach($result as $value): ?>
				<div class="inputarea">
					<label for="name">お名前</label>
					<input type="text" name="name" id="name" value="<?php if(!empty($value['name'])){echo $value['name'];} ?>">
				</div>
				<div class="inputarea">
					<label for="message">メッセージ</label>
					<textarea name="message" id="message" cols="20" rows="10"><?php if(!empty($value['message'])){echo $value['message'];} ?></textarea>
				</div>
				<a href="admin.php"><input type="button" value="キャンセル" class="btn btn_cancel"></a>
				<input class="btn btn_submit" type="submit" name="btn_submit" value="更新">
				<input type="hidden" name="message_id" value="<?php echo $value['id']; ?>">
			<?php endforeach; ?>
		</form>
	</div>
</body>
</html>