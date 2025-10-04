<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>JohnBBS</title>
	<link rel="stylesheet" href="/style.css">
</head>
<body>
	<header>JohnBBS</header>
	<main>
		<a href="/"><button>刷新页面</button></a>
        <form method="post", action="/">
			<p>发布消息</p>
            <input type="text" name="name" required placeholder="名称" />
            <br />
            <input type="text" name="content" required placeholder="留言" />
            <br />
            <input type="submit" value="发布" />
        </form>
		<hr />
		<?php
        function getPostData($field, $filter = FILTER_SANITIZE_STRING) {
            return filter_input(INPUT_POST, $field, $filter) ?? '';
        }

		$datas = json_decode(file_get_contents(".db.json"), true);

        if ($datas["time"] + 86400 < time()) {
            $datas["messages"] = array();
			$datas["time"] = strtotime("today 05:00");
        }


		if ($_SERVER["REQUEST_METHOD"] === "POST") {	
            $name = getPostData("name") ?? "";
            $content = getPostData("content") ?? "";
            $post_time = time();
            array_push($datas["messages"], array("name"=>$name, "content"=>$content, "post_time"=>$post_time));
		}

        $reversed_messages = array_reverse($datas["messages"]);
        foreach ($reversed_messages as $msg) {
            $name = $msg["name"];
            $content = $msg["content"];
            $post_time = date('m-d H:i:s', $msg["post_time"]);
            echo "<div>";
            echo "<p> $name </p>";
            echo "<p> $content </p>";
            echo "<p> $post_time </p>";
            echo "</div>";
            echo "<hr />";
        }

        file_put_contents(".db.json", json_encode($datas));
		?>
	</main>
	<footer>
		本网站由<a href="https://host-intro.retiehe.com/" target="_blank">热铁盒网页托管</a>强力驱动。
	</footer>
</body>
</html>