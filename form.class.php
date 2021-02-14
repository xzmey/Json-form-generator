<?php

class Form {
    public $json;
    public $method = "post";
    public $action = "./";
    public $html = "";
   
	//конструктор инциализируется файлом
    function __construct($params){
        $this->load($params['file']);
    }
	
	//radio, checkbox, text
    private function parse($data){
        $inputTypes = ['checkbox','color','date','datetime-local','email','file','hidden','image','month','number','password','radio','range','reset','search','submit','tel','text','time','url','week'];
        if(in_array($data['type'], $inputTypes)){
            return $this->input($data);
        }
		else if($data['type'] == 'filler'){
			return $this->filler($data);
		}
		else if($data['type'] == 'select'){
			return $this->select($data);	
		}
		else if($data['type'] == 'textarea'){
			return $this->textarea($data);
		}
		else if($data['type'] == 'button'){
			return $this->button($data);
		}
		
		else{
            return $this->{$data['type']}($data);
        }
    }


    public function load($filePath){
        $data = json_decode(file_get_contents($filePath), true);
        
        if($data){
            $this->json = $data['form'];
            
        }else{
            $this->errorMessage = "File not found";
        }
    }

    public function input($data){
        $tag = '<label for="'.$data['attributes']['label'].'">'.$data['attributes']['title']."</label>";
        $tag .= '<input name="'.$data['attributes']['name'].'" ';
        if(isset($data['type'])) $tag .= ' type="'.$data['attributes']['validationRules']['0']['type'].'"';
        if(isset($data['attributes']['id'])) $tag .= ' id="'.$data['attributes']['id'].'"';
        if(isset($data['attributes']['value'])) $tag .= ' value="'.$data['attributes']['value'].'"';
        if(isset($data['attributes']['autocomplete'])) $tag .= ' autocomplete="'.$data['attributes']['autocomplete'].'"';
        if(isset($data['attributes']['min'])) $tag .= ' min="'.$data['attributes']['min'].'"';
        if(isset($data['attributes']['max'])) $tag .= ' max="'.$data['attributes']['max'].'"';
        if(isset($data['attributes']['minlength'])) $tag .= ' minlength="'.$data['attributes']['minlength'].'"';
        if(isset($data['attributes']['placeholder'])) $tag .= ' placeholder="'.$data['attributes']['placeholder'].'"';
        if(isset($data['attributes']['pattern'])) $tag .= ' pattern="'.$data['attributes']['pattern'].'"';
        if(isset($data['attributes']['size'])) $tag .= ' size="'.$data['attributes']['size'].'"';
        if(isset($data['attributes']['step'])) $tag .= ' step="'.$data['attributes']['step'].'"';     
        if(($data['attributes']['required']) == 'true') $tag .= ' required';
        if(($data['attributes']['checked']) == 'true') $tag .= ' checked';
        if(isset($data['attributes']['readonly'])) $tag .= ' readonly';
        if(isset($data['attributes']['autofocus'])) $tag .= ' autofocus';
        if(isset($data['attributes']['multiple'])) $tag .= ' multiple';
        if(isset($data['attributes']['dir'])) $tag .= ' dir="'.$data['attributes']['dir'].'"';
        if(isset($data['attributes']['class'])) $tag .= ' class="'.$data['attributes']['class'].'"';
		if(($data['attributes']['disabled']) == 'true') $tag .= ' disabled';
        $tag .= " />";

        return $tag;
    }
	
	public function filler($data){
	$tag = '<filler>'.$data['attributes']['message'].'</filler>';
        return $tag;
    }

	
	
    public function select($data){
        $tag = '<label for="'.$data['attributes']['label'].'">'.$data['attributes']['title']."</label>";
        $tag .= '<select name="'.$data['attributes']['name'].'" ';
        if(isset($data['attributes']['class'])) $tag .= ' class="'.$data['attributes']['class'].'"';
        if(isset($data['attributes']['id'])) $tag .= ' id="'.$data['attributes']['id'].'"';
        if(($data['attributes']['required']) == 'true') $tag .= ' required';
        if(($data['attributes']['disabled']) == 'true') $tag .= ' disabled';
        if(isset($data['attributes']['readonly'])) $tag .= ' readonly';
        if(isset($data['attributes']['autofocus'])) $tag .= ' autofocus';
        $tag .= '>';
            if(isset($data['options'])){
                foreach($data['options'] as $key=>$value){
                    $tag .= '<option value="'.$key.'">'.$value.'</option>';
					if(($data['attributes']['selected']) == 'true') $tag .= '<option selected value="'.$key.'">'.$value.'</option>';
                }
            }
        $tag .= '</select>';

        return $tag;
    }

    public function textarea($data){
        $tag = '<label for="'.$data['attributes']['label'].'">'.$data['attributes']['title']."</label>";
        $tag .= '<textarea name="'.$data['attributes']['name'].'" ';
        if(($data['attributes']['required']) == 'true') $tag .= ' required';
        if(($data['attributes']['disabled']) == 'true') $tag .= ' disabled';
        if(isset($data['attributes']['readonly'])) $tag .= ' readonly';
        if(isset($data['attributes']['autofocus'])) $tag .= ' autofocus';
        if(isset($data['attributes']['placeholder'])) $tag .= ' placeholder="'.$data['attributes']['placeholder'].'"';
        $tag .= " >";
        if(isset($data['attributes']['value'])) $tag .= $data['attributes']['value'];
        $tag .= "</textarea>";

        return $tag;

    }
   
    public function button($data){
        $tag = '<button name="'.$data['name'].'" ';
        if(isset($data['attributes']['class'])) $tag .= ' class="'.$data['attributes']['class'].'"';
        if(isset($data['attributes']['id'])) $tag .= ' id="'.$data['attributes']['id'].'"';
        if(($data['attributes']['disabled']) == 'true') $tag .= ' disabled';
        $tag .= ">".$data['attributes']['text'];
        $tag .= '</button>';

        return $tag;

    }
    
    public function render(){
        $data = $this->json;
        
        $this->html = '<form name="'.$data['name'].'" method="'.$data['method'].'" action="'.$data['action'].'"';
        if($data['enctype']){ $this->html .= ' enctype="'.$data['enctype'].'"'; }
        if($data['target']){ $this->html .= ' target="'.$data['target'].'"'; }
        if($data['autocomplete']){ $this->html .= ' autocomplete="'.$data['autocomplete'].'"'; }
        
        $this->html .= '>';
		$this->html .= '<postmessage>'.$data['postmessage'].'</postmessage>';
        
        foreach($data['items'] as $value){
            $this->html .= $this->parse($value);
        }
        $this->html .= "</form>";
        return $this->html;
    }
  
    public function show(){
        echo $this->render();
    }

	
    public function error(){
        echo $this->errorMessage."\r\n";
    }
	
}

?>