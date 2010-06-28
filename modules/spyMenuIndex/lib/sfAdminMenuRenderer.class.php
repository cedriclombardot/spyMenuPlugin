<?php

class sfAdminMenuRenderer extends sfMenuRenderer{

	
	/**
	 * Change the reering methode
	 */
	public function renderHtml(){
		$s=null;
		if($this->getsfMenu()->isRoot()){
				
			$this->getsfMenu()->setRootDisplayed();
			if($this->getsfMenu()->getRootLevel()!='root'){
				if($this->getsfMenu()->isAllowed()){
					$s.='<h1>'.($this->getsfMenu()->getIcon()).$this->getsfMenu()->getNom().'</h1>';
					
				}
			}else{
				$s.='<h1>'.sfConfig::get('app_site_adminpanel','Administration').'</h1>';
			}
			$s.='<div class="other"><ul id="dashboard-buttons">';
		}
		
		
			
		foreach($this->getsfMenu()->getChilds() as $child){
			if($this->getsfMenu()->isRootDisplayed()){
				if($this->getsfMenu()->isRoot()){
					if((($this->getsfMenu()->getParent())||($this->getsfMenu()->getRootLevel()=='root'))&&($child->isAllowed()))
					$s.=content_tag('li',content_tag('a',($child->getIcon()).'<br />'.$child->getNom(), array('class'=>'tooltip','title'=>$child->getNom(), 'href'=>url_for($child->getUrl()))));
				}
			}else{
				if($child->isAllowed()){
					$child->setRendererClass(get_class($this));
					$s.=$child->renderHtml();
				}
			}
		}
		if($this->getsfMenu()->isRoot()){
			$s.='<div class="clearfix"></div></ul></div>';
		}
		return $s;
	}
}
?>