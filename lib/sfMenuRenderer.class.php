<?php

class sfMenuRenderer{
	
	protected $sfMenu;
	
	public function __construct(sfMenu $sfMenu){
		$this->sfMenu=$sfMenu;
	}
	/**
	 * @return sfMenu
	 */
	public function getsfMenu(){
		return $this->sfMenu;
	}
	
	public function displayIcons(){
		return true;
	}
	
	public function getIcon($size=24){
		return image_tag($this->getsfMenu()->getIconPath(),array('width'=>$size));
	}
	
    public function isActive($url=null){
        $context=sfContext::getInstance();
        if(is_null($url))
            $url=$context->getModuleName().'/'.$context->getActionName();
        return url_for($url)==url_for($this->getsfMenu()->getUrl());
    }
    
	/**
	 * Give an <ul><li> html rendering
	 * 
	 * @return string the html code
	 */
     public function renderHtml(){
        
        if($this->getsfMenu()->isRoot()){
            $this->getsfMenu()->setRootDisplayed();
            $s='<ul class="'.$this->getsfMenu()->getCssClass().'" id="'.$this->getsfMenu()->getCssId().'">';
            if($this->getsfMenu()->hasChilds()){
                foreach($this->getsfMenu()->getChilds() as $child){
                    $child->setRendererClass(get_class($this));
                    $s.=$child->renderHtml();
                }
            }
            $s.='</ul>';
            return $s;
        }
        if($this->getsfMenu()->isRootDisplayed()) {
            $s=$i=null;
            if($this->getsfMenu()->hasIcon() && $this->displayIcons()){
                
                
                
                    
                $i.= '<span class="icone" style="float:left; margin-right:5px; position:relative;">';
                    $i.= $this->getIcon();
                 $i.= '</span>';
            }
            if($this->getsfMenu()->isAllowed()){
                $active='class="inactive"';
                if($this->isActive()){
                    $active='class="active"';
                    
                    
                }
                /*$url=$this->getUrl();
                if(array_key_exists(str_replace('/','_',str_replace('@','',$this->getUrl())),sfContext::getInstance()->getRouting()->getRoutes())){
                    $url='@'.$this->getUrl();
                }*/
                $s.= '<li '.$active.'>'.link_to($i.$this->getsfMenu()->getNom(),$this->getsfMenu()->getUrl());
                if($this->getsfMenu()->hasChilds()){
                    $s.='<ul>';
                    foreach($this->getsfMenu()->getChilds() as $child){
                        $child->setRendererClass(get_class($this));
                        $s.=$child->renderHtml();
                    }
                    $s.='</ul>';
                }
                
                return $s.'</li>';
            }else{
                
                return $s;
            }
        }else{
            $s=null;
            if($this->getsfMenu()->hasChilds()){
                foreach($this->getsfMenu()->getChilds() as $child){
                    if(!$this->getsfMenu()->isRootDisplayed()){
                        $child->setRendererClass(get_class($this));
                        $s.=$child->renderHtml();
                    }
                }
            }
            return $s;
        }
    }
}
?>