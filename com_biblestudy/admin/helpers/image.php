<?php

/**
 * Image Helper
 *
 * @package    BibleStudy.Admin
 * @copyright  (C) 2007 - 2013 Joomla Bible Study Team All rights reserved
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;

/**
 * Class for Joomla! Bible Sutdy Image
 *
 * @package  BibleStudy.Admin
 * @since    7.1.2
 */
class JBSMImage
{
	/**
	 * Extension Name
	 *
	 * @var string
	 */
	public static $extension = 'com_biblestudy';

	/**
	 * Get Image
	 *
	 * @param   string $path  Path to file
	 *
	 * @return JObject
	 *
	 * @FIXME need to see if this is a JObject or stdClass. If JObject we must set all of the JObject like set('var','value')
	 * @FIXME It's just a stdClass
	 */
	public static function getImage($path)
	{
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		$tmp       = new JObject;
		$tmp->path = $path;

		if (!empty($path))
		{
			$tmp->size = filesize($tmp->path);
			$ext       = strtolower(JFile::getExt($path));

			switch ($ext)
			{
				// Image
				case 'jpg':
				case 'png':
				case 'gif':
				case 'xcf':
				case 'odg':
				case 'bmp':
				case 'jpeg':
					$info        = getimagesize($tmp->path);
					$tmp->width  = $info[0];
					$tmp->height = $info[1];
					$tmp->type   = $info[2];
					$tmp->mime   = $info['mime'];

					if (!$tmp->width)
					{
						$tmp->width = 0;
					}
					if (!$tmp->height)
					{
						$tmp->height = 0;
					}
			}
		}

		return $tmp;
	}

}
