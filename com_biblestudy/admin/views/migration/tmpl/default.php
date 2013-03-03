<?php
/**
 * Default
 *
 * @package    BibleStudy.Admin
 * @copyright  (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.JoomlaBibleStudy.org
 * */

// Protect from unauthorized access
defined('_JEXEC') or die();

if(version_compare(JVERSION, '3.0', 'ge')) {
	JHTML::_('behavior.framework');
	JHtml::_('behavior.modal');
} else {
	JHTML::_('behavior.mootools');
}
?>
<?php if ($this->more): ?>
<h1><?php echo JText::_('JBS_MIG_WORKING'); ?></h1>
<?php else: ?>
<h1><?php echo JText::_('JBS_MIG_MIGRATION_DONE'); ?></h1>
<?php endif; ?>


<div class="progress progress-striped active">
    <div class="bar" style="width: <?php echo $this->percentage ?>%"></div>
</div>

<form action="index.php" name="adminForm" id="adminForm">
    <input type="hidden" name="option" value="com_biblestudy"/>
    <input type="hidden" name="view" value="migration"/>
    <input type="hidden" name="task" value="migration.run"/>
    <input type="hidden" name="tmpl" value="component"/>
</form>

<?php if (!$this->more): ?>
<div class="alert alert-info">
    <p><?php echo JText::_('JBS_MIG__LBL_AUTOCLOSE_IN_3S'); ?></p>
</div>
<script type="text/javascript">
    window.setTimeout('closeme();', 3000);
    function closeme() {
        parent.SqueezeBox.close();
    }
</script>
<?php endif; ?>
