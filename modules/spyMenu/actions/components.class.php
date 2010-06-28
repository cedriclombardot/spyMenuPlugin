<?php

/**
 * Return a menu configurated  with menu.YML
 *
 * @package spyMenuPlugin
 * @subpackage spyDbMenuComponents
 * @author Cédric Lombardot <cedric.lombardot@spyrit.net>
 * 
 */
class spyMenuComponents extends sfComponents
{
	public function executeIndex() {
		if(is_readable(sfConfig::get('sf_app_config_dir').'/menu.yml')){
			include($this->getContext()->getConfigCache()->checkConfig(sfConfig::get('sf_app_config_dir').'/menu.yml'));
			$this->sfMenu= $sfMenu->renderHtml();
		}
	}
	
}
