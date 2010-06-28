<?php
require_once dirname(__FILE__).'/../lib/treeMenuConfig.class.php';
class spyTreeMenuComponents extends sfComponents{
	public function executeMenu() {
		if((!$this->sfMenu=$this->getUser()->getAttribute('spyTreeMenu_'.$this->class))||($this->getRequestParameter('recalcul')!='')){
			$menu=new $this->class;
			$sfMenu=$menu->execute();
			$sfMenu->setUser($this->getUser());
			$sfMenu->setCssClass('menu_tree');
			$sfMenu->setCssId('listMenuRootTree_'.$this->class);
			$this->sfMenu=$sfMenu->renderHtml();
			
			$this->getUser()->setAttribute('spyTreeMenu_'.$this->class,$this->sfMenu);
		}else{
			$this->sfMenu= $this->getUser()->getAttribute('spyTreeMenu_'.$this->class);
		}
	}
}
?>