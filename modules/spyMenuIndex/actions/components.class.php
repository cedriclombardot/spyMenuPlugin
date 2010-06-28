<?php

/**
 * This class will create the spyMenu based with a start_by parameter
 * the rendering will do the difference
 *
 * @package spyMenuPlugin
 * @subpackage spyMenuIndexComponents
 * @author Cédric Lombardot <cedric.lombardot@spyrit.net>
 * 
 */
class spyMenuIndexComponents extends sfComponents
{

	public function executeIndex() {
		if(is_readable(sfConfig::get('sf_app_config_dir').'/menu.yml')){
			include($this->getContext()->getConfigCache()->checkConfig(sfConfig::get('sf_app_config_dir').'/menu.yml'));
			$sfMenu->setRootLevel($this->start_by);
			$sfMenu->setRendererClass('sfAdminMenuRenderer');
			$this->sfMenu= $sfMenu->renderHtml();
			
		}
	}
	
}

