<?php
	if (isset($_POST['text'])) {
		echo $_POST['text'];
	} else {
		?>


		<html>
			<body>
			<form method="post">
				<textarea name="text" cols="80" rows="15"></textarea>
				<input type="submit" value="Odeslat" />
			</form>
			</body>

		</html>


<?php
	}
	?>