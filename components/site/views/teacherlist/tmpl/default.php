<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php //$params = &JComponentHelper::getParams($option);  
$user =& JFactory::getUser();
global $mainframe, $option;
$params =& $mainframe->getPageParameters();
$entry_user = $user->get('gid');
$user_submit_name = $user->name;
if ($user->name == ''){$user_submit_name = '';}
$entry_access = ($params->get('entry_access')) - 1;
$allow_entry = $params->get('allow_entry_study');
$templatemenuid = JRequest::getVar('templatemenuid', 1, 'get', 'int');
$path1 = JPATH_SITE.DS.'components'.DS.'com_biblestudy'.DS.'helpers'.DS;
$admin_params = $this->admin_params;
include_once($path1.'image.php');
if (!$templatemenuid){$templatemenuid = 1;}
?>
<table width="100%">
    <tr><td align="center"><h1><?php echo $this->params->get('teacher_title');?></h1></td></tr>
    <tr>
        <td>
            <img src="<?php echo JURI::base().'components/com_biblestudy/images/square.gif'?>" height="3" width="100%" />
        </td>
    </tr>

</table>
<?php if ($allow_entry > 0) {
if ($entry_access <= $entry_user){ ?>
<table><tr><td><a href="index.php?option=com_biblestudy&view=teacheredit&layout=form"><strong><?php echo JText::_('Add a Teacher');?></strong></a></td></tr></table><?php } }?>
<table width="100%" class="contentpaneopen<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<tr><td>
<?php foreach ($this->items as $item) { 
if (!$item->teacher_thumbnail) { $i_path = $item->thumb; }
	if ($item->teacher_thumbnail && !$admin_params->get('teachers_imagefolder')) { $i_path = 'components/com_biblestudy/images/stories/'.$item->teacher_thumbnail; }
	if ($item->teacher_thumbnail && $admin_params->get('teachers_imagefolder')) { $i_path = 'images'.DS.$admin_params->get('teachers_imagefolder').DS.$item->teacher_thumbnail;}
	$image = getImage($i_path);
?>
	
    <table cellpadding="1" cellspacing="1">
    <tr>
    <?php if ($allow_entry > 0) {
if ($entry_access <= $entry_user){ ?>
  <td><a href="index.php?option=com_biblestudy&view=teacheredit&layout=form&controller=teacheredit&cid[]=<?php echo $item->id?>"><?php echo '['.JText::_('Edit').']';?></a></td>
  <?php } }?>
        <td ><?php if ($item->thumb || $item->teacher_thumbnail){?>
        	<img src="<?php echo $image->path;?>" border="1" title="<?php echo $item->teachername;?>" alt="<?php echo $item->teachername;?>" width="<?php echo $image->width;?>" height="<?php echo $image->height;?>" /><?php } ?>
        </td>
        <td width="150" align="left">
            <a href="index.php?option=com_biblestudy&view=teacherdisplay&id=<?php echo $item->id.'&templatemenuid='.$templatemenuid;?>"><?php echo $item->teachername;?></a><?php echo JText::_(' - ');?>
            <?php echo $item->title;?>
        </td>
        <td width="5">  </td>
        <td align="left">
			<?php echo $item->short;?>
        </td>
     </tr>
    </table>
    
<table >
    	<tr><td><img src="<?php echo JURI::base().'components/com_biblestudy/images/square.gif'?>" height="1" width="100%" /></td></tr>
</table>
    <?php } //end of foreach ?>
</td></tr>
</table>
<div class="listingfooter" >
	<?php 
      echo $this->pagination->getPagesLinks();
      echo $this->pagination->getPagesCounter();
	 ?>
</div> <!--end of bsfooter div-->