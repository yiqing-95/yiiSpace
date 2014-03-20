<?php

Yii::import('backend.models._base.BaseAdminRolePriv');

class AdminRolePriv extends BaseAdminRolePriv
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}