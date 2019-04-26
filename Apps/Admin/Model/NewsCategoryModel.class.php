<?php
namespace Admin\Model;
use Think\Model;

class NewsCategoryModel extends Model {
    
	public function allCategory($field='*'){
		return $this->field($field)->select();
	}
	
}