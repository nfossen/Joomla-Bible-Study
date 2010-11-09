<?php
/**
 * studies Edit Controller for Bible Study Component
 *

 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * studies Edit Controller
 *
 */
class biblestudyControllerstudiesedit extends JController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'studiesedit' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save() {
		$model = $this->getModel('studiesedit');
		if ($model->store($post)) {
			$msg = JText::_( 'JBS_STE_STUDY_SAVED' );
		} else {
			$msg = JText::_( 'JBS_STE_ERROR_SAVING_STUDY' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_biblestudy&view=studieslist';
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * apply a record
	 * @return void
	 */
	function save() {
		$model = $this->getModel('studiesedit');
		$cid 	= JRequest::getVar( 'id', 1, 'post', 'int' );
		if ($model->store($post)) {
			$msg = JText::_( 'JBS_STE_STUDY_SAVED' );
		} else {
			$msg = JText::_( 'JBS_STE_ERROR_SAVING_STUDY' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_biblestudy&controller=studiesedit&task=edit&cid[]='.$cid.'';
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('studiesedit');
		if(!$model->delete()) {
			$msg = JText::_( 'JBS_STE_ERROR_DELETING_STUDY' );
		} else {
			$msg = JText::_( 'JBS_STE_STUDY_DELETED' );
		}

		$this->setRedirect( 'index.php?option=com_biblestudy&view=studieslist', $msg );
	}
	function publish()
	{
		$mainframe =& JFactory::getApplication();

		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'JBS_CMN_SELECT_ITEM_PUBLISH' ) );
		}

		$model = $this->getModel('studiesedit');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_biblestudy&view=studieslist' );
	}


	function unpublish()
	{
		$mainframe =& JFactory::getApplication();

		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'JBS_CMN_SELECT_ITEM_UNPUBLISH' ) );
		}

		$model = $this->getModel('studiesedit');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_biblestudy&view=studieslist' );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'JBS_CMN_OPERATION_CANCELLED' );
		$this->setRedirect( 'index.php?option=com_biblestudy&view=studieslist', $msg );
	}

	function resetHits()
	{
		$msg = null;
		$id 	= JRequest::getInt( 'id', 0, 'post'); //dump ($cid, 'cid: ');
		$db = JFactory::getDBO();
		$db->setQuery("UPDATE #__bsms_studies SET hits='0' WHERE id = ".$id);
		$reset = $db->query();
		if ($db->getErrorNum() > 0)
				{
					$error = $db->getErrorMsg();
                    $msg = JText::_('JBS_CMN_ERROR_RESETTING_HITS').' '.$error;
                    $this->setRedirect( 'index.php?option=com_biblestudy&view=studiesedit&controller=admin&layout=form&cid[]='.$id, $msg );
				}
		else
			{
				$updated = $db->getAffectedRows();
                $msg = JText::_('JBS_CMN_RESET_SUCCESSFUL').' '.$updated.' '.JText::_('JBS_CMN_ROWS_RESET');
				$this->setRedirect( 'index.php?option=com_biblestudy&view=studiesedit&controller=studiesedit&layout=form&cid[]='.$id, $msg );
			}
	}
}
?>