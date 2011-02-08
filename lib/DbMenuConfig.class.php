<?php
/**
 * This class will help you to parse your Database config 
 *
 * @package spyMenuPlugin
 * @subpackage DbMenuConfig
 * @author Cédric Lombardot <cedric.lombardot@spyrit.net>
 * 
 */

class DbMenuConfig{
	
	var $current=0;
	
	/**
	 * Generate a menu
	 */
	public function execute($f=null){
		   $this->getMenusFromDb();
		   $mconfig=$this->menus['all'];			
		   	$sfMenu=$this->getOutpoutInstance();
		    $sfMenu->setId('root')->setNom('')->setUrl('#')->setIconPath('');  
		    foreach($mconfig as $k=>$row){
		    	
		    	$row['nom']=isset($row['nom'])?$row['nom']:'';
		    	$row['url']=isset($row['url'])?$row['url']:'#';
		    	$row['icon']=isset($row['icon'])?$row['icon']:'';
		    	$row['credentials']=isset($row['credentials'])?$row['credentials']:array();
		    	$row['childs']=isset($row['childs'])?$row['childs']:null;
		    	$sfMenu->addChild($k,$row['nom'],$row['url'],$row['icon'],$row['credentials'],$row['childs']);
		    	
		    }
		    
		    return $sfMenu;
		}
		
	/**
	 * If you want to change the outpout method
	 * 
	 * @return sfMenu Instance of sfMenu Class
	 */
	protected function getOutpoutInstance(){
		return new sfMenu();
	}
	
	/**
	 * Start to give the menus list
	 */
	protected function getMenusFromDb(){
		//Il faut faire un array
		$this->menus=array('all'=>array());
		return $this->menus['all']=$this->getMenusAtLevel(NULL);
		
	}
	
	/**
	 * Give the dtabases menus for a current level
	 */
	protected function getMenusAtLevel($level){
		$c=new Criteria();
		$c->add(menuPeer::MENU_PARENT,$level);
		$liste=menuPeer::doSelect($c);
		$menus=array();
		
		foreach($liste as $v){
			$menus['menu_'.$this->current]['nom']=$v->getNom();
			$menus['menu_'.$this->current]['url']=$v->getUrl();
			if(defined(menuPeer::PAGES_ID)){
			if($v->getPagesId()){
				$p=pagesPeer::retrieveByPk($v->getPagesId());
				$menus['menu_'.$this->current]['url']='page/'.$p->getAlias();
			}
			}
			$menus['menu_'.$this->current]['credentials']=array();
			$menus['menu_'.$this->current]['icon']=$v->getIcon();
			$menus['menu_'.$this->current]['childs']=$this->getMenusAtLevel($v->getId());
			$this->current++;
		}
		return $menus;
	}
	
}
?>