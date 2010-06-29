<?php
/**
 * This class will help you to parse your YML config file
 *
 * @package spyMenuPlugin
 * @subpackage sfMenuConfigHandler
 * @author Cédric Lombardot <cedric.lombardot@spyrit.net>
 * 
 */

class sfMenuConfigHandler extends sfYamlConfigHandler{
	
	/**
	 * @param string $config your YML config file
	 * 
	 * The yml config file have to be structured like that :
	 * all: 
	 *   {ID_FOR_ITEM}:
	 *     nom: {LABEL_OF_THE_ITEM}
	 *     url: {URL_FOR_THE_ITEM} # default "#"
	 *     icon: {ICON_FOR_ITEM} # default ""
	 *     credentials: [ {LIST OF CREDENTIALS } ] # default array() all users allowed
	 * 	   help: {MESSAGE} # not use in the default render
	 *     childs: #Start of repeated struture for submenus
	 * 
	 * @return sfMenu Instance of sfMenu Class
	 * 
	 */
	public function execute($configFiles){
		// Parse the yaml
	    $mconfig = $this->parseYamls($configFiles);
	 
	    $data  = "<?php\n";
	    $data .= "\$sfMenu = new {$this->getOutpoutInstance()}();\n";
	    $data .="\$sfMenu->setId('root')->setNom('')->setUrl('#')->setIconPath('');\n";
	 
	    $mconfig=$mconfig['all'];
		
	    foreach($mconfig as $k=>$row){
	    	$data .=$this->getChildData($row,$k);
	    }
	    
	   return $data;
	}
	protected function getChildData($row,$key, $parent='sfMenu'){
		$data='';
		$row['nom']=isset($row['nom'])?addslashes($row['nom']):'';
    	$row['url']=isset($row['url'])?$row['url']:'#';
    	$row['icon']=isset($row['icon'])?$row['icon']:'';
    	$row['credentials']=isset($row['credentials'])?$row['credentials']:array();
    	$row['childs']=isset($row['childs'])?$row['childs']:null;
    	$row['help']=isset($row['help'])?$row['help']:null;
    	$data .="\${$key}=\${$parent}->addChild('{$key}','{$row['nom']}','{$row['url']}','{$row['icon']}',{$this->asPhp($row['credentials'])},{$this->asPhp(array())},'{$row['help']}');\n";
	    if(count($row['childs'])>0){
	    	foreach($row['childs'] as $k=>$child){
	    		$data .= $this->getChildData($child,$k,$key);
	    	}
    	}
    	return $data;
	}
	public static function asPhp($variable)
	  {
	  
	    $array_str=str_replace(array("\n", 'array ('), array('', 'array('), var_export($variable, true));
	    return $array_str;
	  }
	/**
	 * If you want to change the outpout method
	 * 
	 * @return string Name of menu class
	 */
	protected function getOutpoutInstance(){
		return 'sfMenu';
	}
}

?>