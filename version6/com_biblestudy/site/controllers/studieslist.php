<?php

defined('_JEXEC') or die();
jimport('joomla.application.component.controller');

class biblestudyControllerstudieslist extends JController
{
	var $mediaCode;

	/**
	 *@desc Method to display the view
	 *@access public
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks

	}
	function display()
	{
		//	$this->registerTask( 'play' );
		parent::display();
	}

	function download() {
		$abspath    = JPATH_SITE;
		require_once($abspath.DS.'components/com_biblestudy/class.biblestudydownload.php');
		$task = JRequest::getVar('task');
		if ($task == 'download')
		{
			$downloader = new Dump_File();
			$downloader->download();

		 die;
		}
	}

	function avplayer()
	{
		$task = JRequest::getVar('task');
		if ($task == 'avplayer')
		{
			$mediacode = JRequest::getVar('code');
			$this->mediaCode = $mediacode;
			echo $mediacode;
			return;
		}
	}

	function playHit() {
		require_once (JPATH_ROOT  .DS. 'components' .DS. 'com_biblestudy' .DS. 'lib' .DS. 'biblestudy.media.class.php');
		$getMedia = new jbsMedia();
		$getMedia->hitPlay(JRequest::getInt('id'));
	}

	/**
	 * @desc: This function is supposed to generate the Media Player that is requested via AJAX
	 * from the studiesList view "default.php". It has not been implemented yet, so its not used.
	 * @return unknown_type
	 */
	function inlinePlayer() {
		//echo $this->mediaCode;
		echo('{m4vremote}http://www.livingwatersweb.com/video/John_14_15-31.m4v{/m4vremote}');
	}


}

?>