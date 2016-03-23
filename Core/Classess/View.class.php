<?php
namespace Core\Classess;

class View{

	public static function input($type,$name, $options = array()){
		if(isset($type)){$type=' type="'.$type.'" ';}else{$type=NULL;}
		if(isset($name)){$nameform=' name="'.$name.'" ';}else{$nameform=NULL;}
		if(isset($options['id'])){$id=' id="'.$options['id'].'" ';}else{$id=NULL;}
		if(isset($options['class'])){$class=' class="'.$options['class'].'" ';}else{$class=NULL;}
		if(isset($options['placeholder'])){$placeholder=' placeholder="'.$options['placeholder'].'" ';}else{$placeholder=NULL;}
		if(isset($options['value'])){$value=' value="'.$options['value'].'" ';}else{$value=NULL;}
                if(isset($options['size'])){$size=' size="'.$options['size'].'" ';}else{$size=NULL;}
                if(isset($options['style'])){$style=' style="'.$options['style'].'" ';}else{$style=NULL;}
                if(isset($options['myparam'])){$myparam=' '.$options['myparam'].' ';}else{$myparam=NULL;}
	
		$result = '<input'.$id.$type.$nameform.$class.$placeholder.$value.$size.$style.$myparam.'/>';
                return preg_replace('/\s\s+/', ' ', $result);
	}
	
	public static function option($value1,$value2=NULL){
		if($value2==NULL){$value2=$value1;}
		return '<option value="'.$value1.'">'.$value2.'</option>';
	}
	
}
?>