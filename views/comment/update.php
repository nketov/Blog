<?php

$this->params['breadcrumbs'][] =
    'Изменить комментарий'.$model->id;
?>

<h4>Изменить комментарий #<?php echo $model->id; ?></h4>

<?php echo $this->render('_form', array('model'=>$model)); ?>
