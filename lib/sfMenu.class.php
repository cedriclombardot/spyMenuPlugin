<?php
/**
 * This class will help you to render a menu structure
 *
 * @package spyMenuPlugin
 * @subpackage sfMenu
 * @author Cédric Lombardot <cedric.lombardot@spyrit.net>
 * 
 */


class sfMenu {
	
	
	protected $id;
	protected $nom;
	protected $url;
	protected $icon;
	protected $help;
	protected $credentials;
	protected $_childs=array();
	protected $parent=null;
	protected $cssClass='menulist';
	protected $cssId='listMenuRoot';
	
	static $root_level='root';
	static $display_icons=true;
	static $menu_level0=null;
	static $user=null; // instance de $sf_user
	static $root_displayed=false;//A t'on deja fait une sortie du root
	
	protected $rendererClass='sfMenuRenderer';
	protected $renderer;
	
	/**
	 * Build a new menu
	 */
	public function __construct(){
		$this->setRootLevel();
		$this->setUser(sfContext::getInstance()->getUser());
		$this->setDisplayIcons();
		if(!isset(self::$menu_level0)){
			self::$menu_level0=$this;
		}
		if(!isset(self::$root_displayed)){
			$this->setRootDisplayed(false) ;
		}
		
	}
	/**
	 * @return sfMenu root elem
	 */
	public function getLevel0(){
		return self::$menu_level0;
	}
	/**
	 * To change the current user
	 * 
	 * @param sfUser $user
	 */
	public function setUser($user){
		self::$user=$user;
	}
	
	/**
	 * Try to test if the user has thee credential 
	 * to see this menu item
	 */
	public function isAllowed(){
		if(sizeof($this->getCredentials())==0)	
		return true;
		if(!isset(self::$user)){
			return true;
		}
		return self::$user->hasCredential($this->getCredentials());
	}
	
	/**
	 * Test if the root is allready display
	 * 
	 * @return boolean true si vrai
	 */
	public function isRootDisplayed(){
		return self::$root_displayed;
	}
	
	/**
	 * Change the displayed root status
	 * 
	 * @param boolean $statut Statut default true
	 * 
	 * @return boolean statut
	 */
	public function setRootDisplayed($statut=true){
		return self::$root_displayed=$statut;
	}
	
	/**
	 * Test if the current menu item is in the list of menus 
	 * 
	 * 
	 */
	public function isChildOf($elems=array()){
		return (in_array($this->getId(),$elems));
	}
	
	/**
	 * Set a parent instance 
	 * 
	 */
	protected function setParent($p) {
		if(! $p instanceof self){
			throw new Exception ('Parent Invalide');
		}
		$this->parent=$p;
	}
	
	/**
	 * @return  self return the parent instance
	 */
	public function getParent(){
		return $this->parent;
	}
	
	/**
	 * Return the current requested level
	 */
	public function getLevelByRequest(){
		$url=substr($_SERVER['PATH_INFO'],1);
		foreach($this->getChilds() as $child){
			if($child->isRequestedLevel($url)){
				return $child->getId();
			}
			foreach($child->getChilds() as $subChild){
				if($subChild->getLevelByRequest()){
					return $subChild->getId();
				}
			}
		}
		return false;
	}
	
	public function isRequestedLevel($url){
		return $this->getUrl()==$url;
	}
	
	/**
	 * Change the css class
	 * @param  string $class name of the class
	 * 
	 * @return self
	 */
	public function setCssClass($class){
		$this->cssClass=$class;
		return $this;
	}
	
	/**
	 * give the css class name
	 * 
	 * @return string the css class
	 */
	public function getCssClass(){
		return $this->cssClass;
	}
	
	/**
	 * Change the ID for multiple menus with JS
	 * 
	 * 
	 */
	public function setCssId($id){
		$this->cssId=$id;
		return $this;
	}
	public function getCssId(){
		return $this->cssId;
	}
	
	/**
	 * Change the root level for start_by
	 * 
	 * @param string $root_level ID of the root( root= the all level)
	 */
	public function setRootLevel($root='root'){
		self::$root_level=$root;
	}
	
	/**
	 * Get the root level
	 * 
	 * @return string the root level name
	 */
	public function getRootLevel(){
		return self::$root_level;
	}
	
	/**
	 * Change the id for the instance
	 * 
	 * @param string $id name of the menu
	 * 
	 * @return self
	 */
	public function setId($id){
		$this->id=$id;
		return $this;
	}
	
	/**
	 * Return the id of this item
	 * 
	 * @return string
	 */
	public function getId(){
		return $this->id;
	}
	
	/**
	 * Test if the current level is the root
	 * 
	 * @return boolean true si vrai
	 */
	public function isRoot(){
		return $this->getId()==$this->getRootLevel();
	}
	
	/**
	 * Change the name / label for the item
	 * 
	 * @param string $nom label
	 * 
	 * @return self
	 */
	public function setNom($nom){
		$this->nom=$nom;
		return $this;
	}
	
	/**
	 * Give the name of the current Item
	 * 
	 * @return string the name
	 */
	public function getNom(){
		return $this->nom;
	}
	
	/**
	 * Set the url for the current Item
	 * 
	 * @param string url 
	 * 
	 * @return self
	 */
	public function setUrl($url){
		$this->url=$url;
		return $this;
	}
	
	/**
	 * Return the url for the currrent element
	 * 
	 * @return string
	 */
	public function getUrl(){
		if($this->url=='#'){
			return '@homepage';		
		}
		if(strstr($this->url,'@'))
			return $this->url;
		if(!strstr($this->url,'/'))
			return '@default_index?module='.$this->url;
		return $this->url;
	}
	
	/**
	 * Change the icone
	 * 
	 * @param string icon_path 
	 * 
	 * @return self
	 */
	public function setIconPath($iconpath){
		$this->icon=$iconpath;
		return $this;
	}
	
	/**
	 * Return the IconPath
	 * 
	 * @return string Iconpath
	 */
	public function getIconPath(){
		return $this->icon;
	}
	
	/**
	 * Return the image_tag for the icon
	 * 
	 * @return string
	 */
	public function getIcon(){
		return image_tag($this->getIconPath(),array('height'=>16));
	}
	
	/**
	 * Test if the menu have an icon
	 * 
	 * @return boolean
	 */
	public function hasIcon(){
		return ($this->getIconPath()!='');
	}
	
	/**
	 * Display Icon only for sublebels not for root
	 */
	public function displayIcons(){
		if(!$this->isChildOf(self::$menu_level0->getChildsIds()))
			return self::$display_icons;
	}
	
	/**
	 * Set that the icons are displayes
	 */
	public function setDisplayIcons($d=true){
		self::$display_icons=$d;
	}
	
	/**
	 * Set required crédential for the item
	 * @param array list of credentials
	 */
	public function setCredentials($credentials){
		$this->credentials=$credentials;
		return  $this;
	}
	
	/**
	 * @return array the list of credentials
	 */
	public function getCredentials(){
		return $this->credentials;
	}
	
	/**
	 * Set an help message 
	 * 
	 * @param string help Message
	 */
	public function setHelp($help){
		$this->help=$help;
		return $this;
	}
	
	/**
	 * Give the help message
	 * 
	 * @return string the message
	 */
	public function getHelp(){
		return $this->help;
	}
	
	/**
	 * Add a chold for the current menu
	 * 
	 * @param string id Name of the menu
	 * @param string name Label of the item
	 * @param string url target of link
	 * @param string icon the path for the icon item
	 * @param array array of credentials
	 * @param string array of childs
	 * @param string an help message
	 * 
	 * @return self instance of the child
	 */
	public function addChild($id, $nom, $url='#', $icon='', $credentials=array(), $childs=null, $help=null){
		$c=get_class($this);
		$this->_childs[]=$m=new $c;
		$m->setId($id)->setNom($nom)->setUrl($url)->setIconPath($icon)->setCredentials($credentials)->setHelp($help);
		if(is_array($childs)){
			foreach($childs as $k=>$row){
				$row['nom']=isset($row['nom'])?$row['nom']:'';
		    	$row['url']=isset($row['url'])?$row['url']:'#';
		    	$row['icon']=isset($row['icon'])?$row['icon']:'';
		    	$row['help']=isset($row['help'])?$row['help']:'';
		    	$row['credentials']=isset($row['credentials'])?$row['credentials']:array();
		    	$row['childs']=isset($row['childs'])?$row['childs']:null;
				$m->addChild($k,$row['nom'],$row['url'],$row['icon'],$row['credentials'],$row['childs'],$row['help']);
			}
		}
		$m->setParent($this);
		return $m;
	}
	
	/**
	 * Give the list of childs
	 * 
	 * @return array the array of childs instances
	 */
	public function getChilds(){
		return $this->_childs;
	}
	
	/**
	 * Return the list of childs ids
	 * 
	 * @return array
	 */
	public function getChildsIds(){
		$array=array();
		foreach($this->getChilds() as $child){
			$array[]=$child->getId();
		}
		return $array;
	}
	
	/**
	 * Count the childs
	 * 
	 * @return integer Number of childs
	 */
	public function countChilds(){
		return sizeof($this->_childs);
	}
	
	/**
	 * Test if the current menu have childs
	 * 
	 * @return boolean 
	 */
	public function hasChilds(){
		return ($this->countChilds()>0);
	}
	
	/**
	 * @return sfMenu
	 */
	public function setRendererClass($class){
		$this->rendererClass=$class;
		return $this;
	}
	
	/**
	 * @return sfMenu
	 */
	public function setRenderer(sfMenuRenderer $renderer){
		$this->renderer=$renderer;
		return $this;
	}
	
	/**
	 * @return sfMenuRenderer
	 */
	public function getRenderer(){
		if(!$this->renderer){
			$c=$this->rendererClass;
			$this->renderer=new $c($this);
		}
		return $this->renderer;
	}
	
	
	public function renderHtml(){
		return $this->getRenderer()->renderHtml();
	}
}
?>