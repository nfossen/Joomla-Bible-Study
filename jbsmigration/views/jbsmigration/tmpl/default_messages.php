<?php

/**
 * @author Tom Fuller
 * @copyright 2011
 */

defined('_JEXEC') or die();

$messages = JRequest::getVar('jbsmessages', null, 'get', 'array' );

foreach ($messages AS $message)
{
 

?>
<div>
 <fieldset class="panelform">
  
 <?php
 echo JHtml::_('sliders.start','content-sliders-migration', array('useCookie'=>1));  
 echo JHtml::_('sliders.panel', JText::_('JBS_MIGRATE_VERSION').' '.$message['build'] , 'publishing-details'); ?>
 <?php foreach ($message AS $msg)
 {   
    if (is_array($msg))
    {
        foreach ($msg AS $m)
        {
            echo $m;
        }
    }
    else
        {
            echo $msg;
        } 
    
 }?>
 <?php echo JHtml::_('sliders.end'); ?>
 </fieldset>
</div>
<?php
}
 ?>      