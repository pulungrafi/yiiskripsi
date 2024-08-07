<?php
use jeemce\AppView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var AppView $this
 * @var string $message
 * @var string $details
 * @var Exception $exception
 */

$this->title = $this->params['pageName'] = $message;
?>

<div class="error-page">
	<p class="fs-5 text-gray-600">
		<?= nl2br(Html::encode($details)) ?>
	</p>
</div>
