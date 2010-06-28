<?php

class treeMenuConfig extends DbMenuConfig{
var $current=0;
	
	protected function getOutpoutInstance(){
		return new treeMenu();
	}
	
	
}

class treeMenu extends sfMenu{
	public function isAllowed(){
		return true;
	}
	public function renderHtml(){
		if($this->isRoot()){
			$this->setRootDisplayed();
			$s='<ul class="'.$this->getCssClass().'" id="'.$this->getCssId().'">';
			if($this->hasChilds()){
				foreach($this->getChilds() as $child){
					$s.=$child->renderHtml();
				}
			}
			$s.='</ul>';
			return $s;
		}
		if($this->isRootDisplayed()) {
			$s=$i=null;
			if($this->hasIcon() && $this->displayIcons()){
				$i.= '<span style="position:absolute; left:-20px;">';
				 	$i.= $this->getIcon();
				 $i.= '</span>';
			}
			if($this->isAllowed()){
		
				if($this->hasChilds()){
					
					$s.= '<li>'.$i.content_tag('a',$this->getNom(),array('href'=>"javascript:void 0", 'onclick'=>"treemenu.toggle(this)"));
					$s.='<ul>';
					$s.= '<li>'.$i.link_to('Sommaire '.$this->getNom(),$this->getUrl()).'</li>';
					foreach($this->getChilds() as $child){
						$s.=$child->renderHtml();
					}
					$s.='</ul>';
				}else{
						$s.= '<li>'.$i.link_to($this->getNom(),$this->getUrl());
				}
				
				return $s.'</li>';
			}else{
				return $s;
			}
		}else{
			
			$s=null;
			if($this->hasChilds()){
				foreach($this->getChilds() as $child){
					if(!$this->isRootDisplayed())
						$s.=$child->renderHtml();
				}
			}
			return $s;
		}
		
	}
}
?>