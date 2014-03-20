<?php
class ElasticaSort extends CSort
{
	
	public function resolveAttribute($attribute)
	{
		if($this->attributes!==array())
			$attributes=$this->attributes;
		elseif($this->modelClass!==null)
			$attributes=CActiveRecord::model($this->modelClass)->attributeNames();
		else
			return false;
		foreach($attributes as $name=>$definition)
		{
			if(is_string($name))
			{
				if($name===$attribute)
					return $definition;
			}
			elseif($definition==='*')
			{
				if($this->modelClass!==null && CActiveRecord::model($this->modelClass)->hasAttribute($attribute))
					return $attribute;
			}
			elseif($definition===$attribute)
				return $attribute;
		}
		return $attribute;
	}

}
