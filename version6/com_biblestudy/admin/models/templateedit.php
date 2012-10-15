<?php
/**
 * @version     $Id: templateedit.php 1330 2011-01-06 08:01:38Z genu $
 * @package     com_biblestudy
 * @license     GNU/GPL
 */
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.model');
class biblestudyModeltemplateedit extends JModel {
	var $_id;
	var $_template;


	function __construct() {
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}
	/**
	 * @desc Sets the id, and the _tmpl variable
	 * @param $id
	 * @return null
	 */
	function setId($id) {
		// Set id and wipe data
		$this->_id		= $id;
		$this->_tmpl	= null;

	}

	function getTemplate(){
		if(empty($this->_template)) {

			$query = ' SELECT * FROM #__bsms_templates '.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_template = $this->_db->loadObject();
		}

		if (!$this->_template)
		{
			$this->_template = new stdClass();
			$this->_template->id = 0;
			$this->_template->type = null;
			$this->_template->published = 1;
			$this->_template->params = null;
			$this->_template->title = null;
			$this->_template->text = null;
			$this->_template->pdf = null;
		}
		return $this->_template;
	}

	function store($data = null, $tmpl = null){
		$row =& $this->getTable();
		if(!isset($data)) {
			$data = JRequest::get('post');
		}
		$data['tmpl'] = JRequest::getVar( 'tmpl', '', 'post', 'string', JREQUEST_ALLOWRAW );
		// Bind the form fields to the hello table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Make sure the hello record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}

	function copy($cid) {
		foreach($cid as $id) {
			$tmplCurr =& JTable::getInstance('templateedit', 'Table');

			$tmplCurr->load($id);
			$tmplCurr->id = null;
			$tmplCurr->title .= " - copy";
			if (!$tmplCurr->store()) {
				$this->setError($curr->getError());
				return false;
			}
		}
		return true;
	}
	/**
	 * @return unknown_type
	 */
	function delete() {
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row =& $this->getTable();

		if (count( $cids ))
		{
			foreach($cids as $cid) {
				if ($cid == 1)
				{
					$this->setError('You cannot delete the default template');
					return false;
				}
				if (!$row->delete( $cid )) {
					if($cid == 1)
					{
						$this->setError('You cannot delete the default template');
					}
					else {$this->setError( $row->getErrorMsg() );
					}
					return false;
				}
			}
		}
		return true;
	}
	function publish($cid = array(), $publish = 1) {
		if (count( $cid )) {
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__bsms_templates'
			. ' SET published = ' . intval($publish)
			. ' WHERE id IN ('.$cids.')'
				
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
	}

}