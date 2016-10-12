<?php

$this->params['breadcrumbs'][] =
    'Update Comment #'.$model->id;
?>

<h4>Update Comment #<?php echo $model->id; ?></h4>

<?php echo $this->render('_form', array('model'=>$model)); ?>
