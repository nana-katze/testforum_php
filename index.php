<?php
	// メッセージ保存ファイルパスの指定
	define('FILENAME', './message.txt');
	
	// タイムゾーン設定
	date_default_timezone_set('Asia/Tokyo');

	// 変数の初期化
	$now_date = null;
	$data = null;
	$file_handle = null;
	$split_data = null;
	$message = array();
	$message_array = array();
	$success_message = null;

	if(!empty($_POST['btn_submit'])){
		if($file_handle = fopen(FILENAME, "a")){
			// 投稿日時を取得
			$now_date = date("Y-m-d H:i:s");
			// 投稿内容を取得
			$data = "'".$_POST['name']."','".$_POST['message']."','".$now_date."'\n";
			// 投稿内容を保存
			fwrite($file_handle, $data);
			// ファイルを閉じる
			fclose($file_handle);

			$success_message = 'メッセージを投稿しました。';
		}
	}

	if($file_handle = fopen(FILENAME, 'r')){
		while($data = fgets($file_handle)){
			$split_data = preg_split('/\'/', $data);

			$message = array(
				'name' => $split_data[1],
				'message' => $split_data[3],
				'post_date' => $split_data[5]
			);
			array_unshift($message_array, $message);
		}

		// ファイルを閉じる
		fclose($file_handle);
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
		<?php if(!empty($success_message)): ?>
			<p class="success_message"><?php echo $success_message; ?></p>
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
		<input type="submit" name="btn_submit" value="投稿">
		</form>
		<section>
			<?php if(!empty($message_array)): ?>
				<?php foreach($message_array as $value): ?>
					<article>
						<div class="info">
							<h2><?php echo $value['name']; ?></h2>
							<time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
						</div>
						<p><?php echo $value['message']; ?></p>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
		</section>
	</div>
</body>
</html>