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
					$child->setRenderer($this);
					$s.=$child->renderHtml();
				}
			}
			$s.='</ul>';
			return $s;
		}
		if($this->getsfMenu()->isRootDisplayed()) {
			$s=$i=null;
			if($this->getsfMenu()->hasIcon() && $this->getsfMenu()->displayIcons()){
				$i.= '<span style="position:absolute; left:-20px;">';
				 	$i.= $this->getsfMenu()->getIcon();
				 $i.= '</span>';
			}
			if($this->getsfMenu()->isAllowed()){
				$active='class="inactive"';
				if($_SERVER['REQUEST_URI']==url_for($this->getsfMenu()->getUrl()))
					$active='class="active"';
					
				$s.= '<li '.$active.'>'.$i.link_to($this->getsfMenu()->getNom(),$this->getsfMenu()->getUrl());
				if($this->getsfMenu()->hasChilds()){
					$s.='<ul>';
					foreach($this->getsfMenu()->getChilds() as $child){
						$child->setRenderer($this);
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
						$child->setRenderer($this);
						$s.=$child->renderHtml();
					}
				}
			}
			return $s;
		}
	}
}
?>