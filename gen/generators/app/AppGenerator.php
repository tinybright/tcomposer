<?php

class AppGenerator extends CCodeGenerator
{
	public $codeModel='gen.generators.app.AppCode';


	public function actionGenJava(){

		$modelClass=Yii::import($this->codeModel,true);
		$model=new $modelClass;
		$model->attributes=[];
		$model->status=CCodeModel::STATUS_PREVIEW;
		$model->prepare();

		$model->answers=1;
		$model->status=$model->save() ? CCodeModel::STATUS_SUCCESS : CCodeModel::STATUS_ERROR;
		echo '<pre>';
		print_r([$model]);
	}


}