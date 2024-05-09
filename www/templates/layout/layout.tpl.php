<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>

	<!-- CSS FILES -->
	<link rel="stylesheet" href="<?= App()->mkPublicUrl("css/app.css") ?>">

	<?php foreach (Document()->getCssFiles() as $cssFile): ?>
		<link rel="stylesheet" href="<?= $cssFile ?>">
	<?php endforeach; ?>

	<!-- JS FILES -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

	<?php foreach (Document()->getJsFiles() as $jsFile): ?>
		<?php if ($jsFile['module']): ?>
			<script type="module" src="<?= $jsFile['path'] ?>"></script>
		<?php else: ?>
			<script src="<?= $jsFile['path'] ?>"></script>
		<?php endif; ?>
	<?php endforeach; ?>

</head>

<body>

	<div id="root">

		<div id="messages-section">
			<div id="alerts-box">
				<?php foreach (Document()->getPageMessages() as $type => $messages): ?>
					<?php if (count($messages) > 0): ?>
						<div class="alert <?= $type ?>">
							<?php foreach ($messages as $message): ?>
								<span class="material-icons-outlined">
									info
								</span>
								<p class="alert-message"><?= $message ?></p>
							<?php endforeach; ?>
						</div>
					<?php endif ?>
				<?php endforeach; ?>
			</div>
		</div>

		<div id="contents-section">
			<?= Document()->getBody() ?>
		</div>

	</div>

</body>

</html>