<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class SettingForm extends CFormModel
{
	public $site_name;
	public $site_closed;
	public $close_information;
	public $site_url;
	public $keywords;
	public $description;
	public $copyright;
	public $blogdescription;
	public $default_editor;
	public $theme;
	public $email;
	public $rss_output_num;
	public $rss_output_fulltext;
	public $post_num;
	public $time_zone;
	public $icp;
	public $footer_info;
	public $rewrite;
	public $showScriptName;
	public $urlSuffix;
	public $author;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('site_name, email, site_url, keywords', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'site_name'=>'Site Name',
		);
	}
}