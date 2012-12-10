/**
 * Extension multimodelform
 *
 * Handling multiple models in a form
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright 2011 myticket it-solutions gmbh
 * @license New BSD License
 * @category User Interface
 * @version 1.0
 */

This extension allows to display and handle with different, multiple models in one editform.
It handles clientside cloning and removing input elements / fieldsets and serverside batchInsert/Update/Delete.


It could be a updated version of my extension jqrelcopy,
but because of the different approach jqrelcopy will stay as lightweight extension for other needs.
If you don't work with models take a look at jqrelcopy.


Features

- Autogenerating input form elements with the 'form builder' of Yii

- Clientside Clone/Remove form elements / fieldsets with
  the jQuery plugin http://www.andresvidal.com/labs/relcopy.html

- Simple handling of the submitted form data in the controllers create/update action

- Supports editing simultanous master/detail in one form

- Supports validation and extra error summary for each record


Usage

- Extract the files under .../protected/extensions

- Master/Detail example:


Assume you have two models 'group' (id, title) and 'member' (id, groupid, firstname,lastname,membersince).

1. Build the models 'Group' and 'Member' with gii.

2. Build the 'GroupController' and the group views with gii.
   You don't need to generate the member controller and views.

3. Add the method 'getMultiModelForm()' to the member model.
   This method return the configuration array for the edit form of the member record.
   See the tutorial 'Using Form Builder' http://www.yiiframework.com/doc/guide/1.1/en/form.builder
   
public function getMultiModelForm()
	{
		//Can be a config file that returns the configuration too
		// return 'pathtoformconfig.formconfig';

		return array(
		  'elements'=>array(

			'firstname'=>array(
				'type'=>'text',
				'maxlength'=>40,
			),

		  	'lastname'=>array(
		  		'type'=>'text',
		  		'maxlength'=>40,
		  	),

			'membersince'=>array(
				'type'=>'dropdownlist',
				//it is important to add an empty item because of new records
				'items'=>array(''=>'-',2008=>2008,2009=>2009,2010=>2010,2011=>2011,),
			),
		));
	}  
	

4. Change the default actionUpdate of the GroupController to
	
public function actionUpdate($id)
{
	Yii::import('ext.multimodelform.MultiModelForm');

	$model=$this->loadModel($id);
	
	//initialize the vars for multimodelform
	$member = new Member;
	$errorIndex = null;

	if(isset($_POST['Group']))
	{
		$model->attributes=$_POST['Group'];

		//the foreign key values for the member
		$masterValues = array ('groupid'=>$model->id);
		
		/*
		  //You can check for valid members if you want to do some other stuff
		  //But the MultiModelForm::save will do the same before saving
		  if (!MultiModelForm::validate($member,$errorIndexes,$updateItems,$deleteItems,$masterValues)) 
		  {
		    //do something if at least one record is not valid
		  }
		*/ 

		//only save Group if members are valid too
		if($model->save() && MultiModelForm::save($member,$errorIndexes,$updateItems,$masterValues))
		{
			$this->redirect(array('view','id'=>$model->id));
		}
	}

	$this->render('update',array(
		'model'=>$model,
		//submit the member, errorIndexes, validatedItems to the widget in the edit form
		'member'=>$member, 
		'errorIndexes' => $errorIndexes,
		'validatedItems' => $updateItems,
	));
}	

5. Change the code of actionCreate to something like this

public function actionCreate()
{
	Yii::import('ext.multimodelform.MultiModelForm');

	$model=new Group;
	
	$member = new Member;
	$errorIndex = null;

	if(isset($_POST['Group']))
	{
		$model->attributes=$_POST['Group'];

		//save the model group to populate $model->id
		if($model->save())
		{
			//the value for the foreign key 'groupid'
			$masterValues = array ('groupid'=>$model->id);
			
			if (MultiModelForm::save($member,$errorIndexes,$masterValues))
				$this->redirect(array('view','id'=>$model->id));
		}
	}

	$this->render('create',array(
		'model'=>$model,
		'member'=>$member,
		'errorIndexes' => $errorIndexes,
		'validatedItems' => $updateItems,
	));
}

6. Change the renderPartial in views/group/create.php and update.php 
   to transfer the parameters member,errorIndexes and validatedItems too.

echo $this->renderPartial('_form', array('model'=>$model,
                          'member'=>$member,'errorIndexes' => $errorIndexes,'validatedItems'=>$validatedItems)); 

						  
7. Change the default code of views/group/_form.php.
   $model is the instance of the group model.
						  
<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'group-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<?php
    
	//render the member models
	$this->widget('ext.multimodelform.MultiModelForm',array(
			//a unique widget id
			'id' => 'id_member',  
			
			//the text for the remove record link
			'removeText' => 'Remove', 
			
			//see the render call in the controller actions create/update 
			//instance of the member model, 
			'model' => $member,  
			
			//array of positions with invalid records
			'errorIndexes' => $errorIndexes, 
			
			//array of member models
			'validatedItems' => $validatedItems, 
			
			//array of member models as list of editable members
			'data' => $member->findAll('groupid=:groupId', array(':groupId'=>$model->id)),
		));
    ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
  

Like this you can add more other models for handling simultaneous in a form.



Ressources

- jQuery plugin 'RelCopy' http://www.andresvidal.com/labs/relcopy.html
- Tutorial 'Collecting Tabular Input' http://www.yiiframework.com/doc/guide/1.1/en/form.table
- Extension jqrelcopy http://www.yiiframework.com/extension/jqrelcopy/