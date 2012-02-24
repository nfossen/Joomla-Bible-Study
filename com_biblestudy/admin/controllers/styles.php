<?php

/*
 * controller for the css styles
 * @since 7.1.0
 * @author Tom Fuller
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

abstract class controllerClass extends JControllerAdmin {

}

class biblestudyControllerStyles extends controllerClass {

	/**
	 * Proxy for getModel
	 *
	 * @param <String> $name    The name of the model
	 * @param <String> $prefix  The prefix for the PHP class name
	 * @return JModel
	 *
	 * @since 7.1.0
	 */
	public function &getModel($name = 'style', $prefix = 'biblestudyModel') {
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

}