<?php
namespace Core\Classess;

class View{

	public function input($type,$name, $options = array()){
		if(isset($type)){$type=' type="'.$type.'" ';}else{$type=NULL;}
		if(isset($name)){$nameform=' name="'.$name.'" ';}else{$nameform=NULL;}
		if(isset($options['id'])){$id=' id="'.$options['id'].'" ';}else{$id=NULL;}
		if(isset($options['class'])){$class=' class="'.$options['class'].'" ';}else{$class=NULL;}
		if(isset($options['placeholder'])){$placeholder=' placeholder="'.$options['placeholder'].'" ';}else{$placeholder=NULL;}
		if(isset($options['value'])){$value=' value="'.$options['value'].'" ';}else{$value=NULL;}
	
		$result = '<input'.$id.$type.$nameform.$class.$placeholder.$value.'/>';
                return preg_replace('/\s\s+/', ' ', $result);
	}
	
	public function option($value1,$value2=NULL){
		if($value2==NULL){$value2=$value1;}
		return '<option value="'.$value1.'">'.$value2.'</option>';
	}
	
}
?>