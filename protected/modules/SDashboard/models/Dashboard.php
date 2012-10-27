<?php

/**
 * This is the model class for table "{{dashboard}}".
 *
 * The followings are the available columns in table '{{dashboard}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $content
 * @property integer $position
 * @property string $render
 */
class Dashboard extends CActiveRecord
{
	public $default;
	const EMOTICONS_DIR = "/images/emoticons/";
	public $newrecord;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Dashboard the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{dashboard}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('content,active,ajaxrequest,default','safe'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, title, content, position', 'safe', 'on'=>'search'),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'title' => 'Title',
			'content' => 'Content',
			'position' => 'Position',
			'ajaxrequest' => 'Make an ajaxrequest to this url and display the content:',
			'default' => 'Default (visible for all users)',
		);
	}

	public static function findPortlets($userid)
	{
		$criteria = new CDbCriteria(array(
			'order'=>'position',
			'condition'=>'user_id='.$userid.' OR user_id=0',
		));
		$dataProvider = new CActiveDataProvider('dashboard', array(
			'criteria'=>$criteria,
		));
		return $dataProvider;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('render',$this->render,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

// ----------------------------------------------------------------------------
// markItUp! BBCode Parser
// v 1.0.6
// Dual licensed under the MIT and GPL licenses.
// ----------------------------------------------------------------------------
// Copyright (C) 2009 Jay Salvat
// http://www.jaysalvat.com/
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
// 
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
// 
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.
// ----------------------------------------------------------------------------
// Thanks to Arialdo Martini, Mustafa Dindar for feedbacks.
// ----------------------------------------------------------------------------



public function BBCode2Html($text) {

		$text = trim($text);

		// BBCode [code]
		if (!function_exists('escape')) {
			function escape($s) {
				global $text;
				$text = strip_tags($text);
				$code = $s[1];
				$code = htmlspecialchars($code);
				$code = str_replace("[", "&#91;", $code);
				$code = str_replace("]", "&#93;", $code);
				return '<pre><code>'.$code.'</code></pre>';
			}	
		}
		$text = preg_replace_callback('/\[code\](.*?)\[\/code\]/ms', "escape", $text);

		// Smileys to find...
		$in = array( 	 ':)', 	
						 ':D',
						 ':o',
						 ':p',
						 ':(',
						 ';)'
		);
		// And replace them by...
		$out = array(	 '<img alt=":)" src="'.	Dashboard::EMOTICONS_DIR.'emoticon-happy.png" />',
						 '<img alt=":D" src="'.	Dashboard::EMOTICONS_DIR.'emoticon-smile.png" />',
						 '<img alt=":o" src="'.	Dashboard::EMOTICONS_DIR.'emoticon-surprised.png" />',
						 '<img alt=":p" src="'.	Dashboard::EMOTICONS_DIR.'emoticon-tongue.png" />',
						 '<img alt=":(" src="'.	Dashboard::EMOTICONS_DIR.'emoticon-unhappy.png" />',
						 '<img alt=";)" src="'.	Dashboard::EMOTICONS_DIR.'emoticon-wink.png" />'
		);
		$text = str_replace($in, $out, $text);
		
		// BBCode to find...
		$in = array( 	 '/\[b\](.*?)\[\/b\]/ms',	
						 '/\[i\](.*?)\[\/i\]/ms',
						 '/\[u\](.*?)\[\/u\]/ms',
						 '/\[img\](.*?)\[\/img\]/ms',
						 '/\[email\](.*?)\[\/email\]/ms',
						 '/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms',
						 '/\[size\="?(.*?)"?\](.*?)\[\/size\]/ms',
						 '/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms',
						 '/\[quote](.*?)\[\/quote\]/ms',
						 '/\[list\=(.*?)\](.*?)\[\/list\]/ms',
						 '/\[list\](.*?)\[\/list\]/ms',
						 '/\[\*\]\s?(.*?)\n/ms'
		);
		// And replace them by...
		$out = array(	 '<strong>\1</strong>',
						 '<em>\1</em>',
						 '<u>\1</u>',
						 '<img src="\1" alt="\1" />',
						 '<a href="mailto:\1">\1</a>',
						 '<a href="\1">\2</a>',
						 '<span style="font-size:\1%">\2</span>',
						 '<span style="color:\1">\2</span>',
						 '<blockquote>\1</blockquote>',
						 '<ol start="\1">\2</ol>',
						 '<ul>\1</ul>',
						 '<li>\1</li>'
		);
		$text = preg_replace($in, $out, $text);
			
		// paragraphs
		$text = str_replace("\r", "", $text);
		$text = "<p>".preg_replace("/(\n){2,}/", "</p><p>", $text)."</p>";
		$text = nl2br($text);
		
		// clean some tags to remain strict
		// not very elegant, but it works. No time to do better ;)
		if (!function_exists('removeBr')) {
			function removeBr($s) {
				return str_replace("<br />", "", $s[0]);
			}
		}	
		$text = preg_replace_callback('/<pre>(.*?)<\/pre>/ms', "removeBr", $text);
		$text = preg_replace('/<p><pre>(.*?)<\/pre><\/p>/ms', "<pre>\\1</pre>", $text);
		
		$text = preg_replace_callback('/<ul>(.*?)<\/ul>/ms', "removeBr", $text);
		$text = preg_replace('/<p><ul>(.*?)<\/ul><\/p>/ms', "<ul>\\1</ul>", $text);
		
		return $text;
	}

}