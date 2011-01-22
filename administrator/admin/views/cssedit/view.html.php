<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class biblestudyViewcssedit extends JView
{
	
	function display($tpl = null)
	{
		$isNew = $this->item->id == 0;
       if($isNew){
           $text = JText::_('JBS_CMN_NEW');}
       else {
           $text = JText::_('JBS_CMN_EDIT');}		
		
		JHTML::_('stylesheet', 'icons.css', JURI::base().'components/com_biblestudy/css/');
	$lists		=& $this->get('Data');
		$text = JText::_( 'JBS_CMN_EDIT_CSS' );
		JToolBarHelper::title(   JText::_( 'JBS_CSS_CSS_EDIT' ).': <small><small>[ ' . $text.' ]</small></small>', 'css.png' );
		JToolBarHelper::save();
        JToolBarHelper::cancel();
		JToolBarHelper::custom('backup','archive','Backup CSS', 'JBS_CSS_BACKUP_CSS',false, false);
		JToolBarHelper::custom( 'resetcss', 'save', 'Reset CSS', 'JBS_CSS_RESET_CSS', false, false );
		JToolBarHelper::help('biblestudy', true );
		
		$this->assignRef('lists',		$lists);

		parent::display($tpl);
	}
}
?>