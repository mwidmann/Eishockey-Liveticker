<?php
/**
* 
*/

require ('smarty/Smarty.class.php');

/**
* Smarty Configuration class
*/
class LivetickerSmarty extends Smarty {

	function LivetickerSmarty() {
		$this->template_dir = FRAGMENTDIR;
		$this->compile_dir = OUTPUTDIR . 'templates_c';
		$this->config_dir = '';
		$this->cache_dir = OUTPUTDIR . 'cache';
	}

}