<?php
/**
 * Return a menu configurated  with DB
 *
 * @package spyMenuPlugin
 * @subpackage spyDbMenuComponents
 * @author Cédric Lombardot <cedric.lombardot@spyrit.net>
 * 
 */


class spyDbMenuComponents extends sfComponents
{
	public function preExecute() {
			$menu=new DbMenuConfig();
			$sfMenu=$menu->execute();
			$this->sfMenuObject=$sfMenu;
			$this->sfMenu= $sfMenu->renderHtml();	
	}
	
	/**
	 * To get all the menu form the database
	 */
	public function executeIndex(){
		$this->preExecute();
	}
	
	
	
	/**
	 * Return the submenus of the current menu
	 */
	public function executeSubMenu(){
		$this->preExecute();
		//Retrouve le level du menu
		$mySubMenu=$this->sfMenuObject;
		$mySubMenu->setCssClass('menulistSub');
		$mySubMenu->setCssId('listMenuRootSub');
		
		
		$mySubMenu->setRootLevel($mySubMenu->getLevelByRequest());
		$this->mySubMenu= $mySubMenu->renderHtml();
	}
	
	
	
	
	
}
