<?php
/*
 * Created on 28.01.2011
 *
 * Copyright: Salvador Aceves
 *
 * GNU LESSER GENERAL PUBLIC LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Requirements:
 * This extension have to be installed into:
 * <Yii-Application>/protected/extensions/ckfceditor
 *
 * Usage:
$this->widget('application.extensions.editor.CKkceditor',array(
    "model"=>$model,                # Data-Model
    "attribute"=>'descripcion',         # Attribute in the Data-Model
    "height"=>'400px',
    "width"=>'100%',
	"filespath"=>(!$model->isNewRecord)?Yii::app()->basePath."/../media/paquetes/".$model->idpaquete."/":"",
	"filesurl"=>(!$model->isNewRecord)?Yii::app()->baseUrl."/media/paquetes/".$model->idpaquete."/":"",
) );
 */
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'source'.DIRECTORY_SEPARATOR.'ckeditor'.DIRECTORY_SEPARATOR.'ckeditor.php');
class CKkceditor extends CInputWidget
{
	public $kcFinderPath;
	public $height = '375px';
	public $width = '100%';
	public $toolbarSet;
	public $config;
	public $filespath;
	public $filesurl;
	private $baseurl;
	public $value;
	public $name;
  
    public function init()
    {
    $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'source';
        $this->baseurl = Yii::app()->getAssetManager()->publish($dir);
    	$this->kcFinderPath = $this->baseurl."/kcfinder/";
        parent::init();
    }
  public function run()
  {
    if (!$this->hasModel() && !isset($this->name)) {
      throw new CHttpException(500,'Parameters "model" and "attribute" or "name" have to be set!');
    }
	$ok = $this->attribute;
	if (!empty($this->model) && $ok)
    {    
      $oCKeditor = new CKeditor();
	  $this->value = CHtml::resolveValue( $this->model, $this->attribute );
	  list( $this->name, $id ) = $this->resolveNameID();
    }
    elseif (!empty($this->name))
    {
        $oCKeditor = new CKeditor();
        $this->value = isset($this->value) ? $this->value : null;
    }
    $oCKeditor->basePath = $this->baseurl."/ckeditor/";
    $oCKeditor->config['height'] = $this->height;
    $oCKeditor->config['width'] = $this->width;
    if (isset($this->config) && is_array($this->config))
    {
      foreach ($this->config as $key=>$value)
      {
        $oCKeditor->config[$key] = $value;
      }
    }
    if ($this->filespath&&$this->filesurl) {		  
      $oCKeditor->config['filebrowserBrowseUrl']  = $this->kcFinderPath.'browse.php?type=files';
      $oCKeditor->config['filebrowserImageBrowseUrl']  = $this->kcFinderPath.'browse.php?type=images';
      $oCKeditor->config['filebrowserFlashBrowseUrl']  = $this->kcFinderPath.'browse.php?type=flash';
      $oCKeditor->config['filebrowserUploadUrl']  = $this->kcFinderPath.'upload.php?type=files';
      $oCKeditor->config['filebrowserImageUploadUrl']  = $this->kcFinderPath.'upload.php?type=images';
      $oCKeditor->config['filebrowserFlashUploadUrl']  = $this->kcFinderPath.'upload.php?type=flash';
	  $session=new CHttpSession;
	  $session->open();
      $session['KCFINDER'] = array(
		'disabled'=>false,
		'uploadURL'=>$this->filesurl,
		'uploadDir'=>realpath($this->filespath).'/',
		);
    }
    $oCKeditor->editor($this->name, isset($this->value) ? $this->value : null);
  }
}
?>