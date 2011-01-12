<?php

/**
 * @version     $Id$
 * @package     com_biblestudy
 * @license     GNU/GPL
 */
//No Direct Access
defined('_JEXEC') or die();

JHtml::_('script', 'system/multiselect.js', false, true);
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
?>
<form action="<?php echo JRoute::_('index.php?option=com_biblestudy&view=templateslist'); ?>" method="post" name="adminForm" id="adminForm">
    <fieldset id="filter-bar">
        <div class="filter-select fltrt">
            <select name="filter_state" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>
                <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true); ?>
            </select>
        </div>
    </fieldset>
    <div class="clr"></div>
    <table class="adminlist">
        <thead>
            <tr>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)"/>
                </th>
                <th width="8%">
                    <?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'podcast.published', $listDirn, $listOrder); ?>
                </th>
                <th alicn="center">
                    <?php echo JHtml::_('grid.sort', 'JBS_PDC_PODCAST', 'podcast.title', $listDirn, $listOrder); ?>
                </th>
                <th alicn="center">
                    <?php echo(JText::_('JBS_PODCAST_DESCRIPTION')); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="4">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php
                foreach($this->items as $i => $item) :
            ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td class="center">
                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                </td>
                <td class="center">
                    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'templateslist.', true, 'cb', '', ''); ?>
                </td>
                <td class="center">
                    <a href="<?php echo JRoute::_('index.php?option=com_biblestudy&task=podcastedit.edit&id='.(int)$item->id); ?>">
                        <?php echo $this->escape($item->title); ?>
                    </a>
                </td>
                <td class="center">
                    <?php echo $this->escape($item->description); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?> "/>
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?> "/>
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>