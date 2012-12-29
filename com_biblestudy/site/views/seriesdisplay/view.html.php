<?php
/**
 * @package    BibleStudy.Site
 * @copyright  (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;
require_once (BIBLESTUDY_PATH_LIB . 'biblestudy.pagebuilder.class.php');
require_once (BIBLESTUDY_PATH_ADMIN_HELPERS . 'translated.php');
JLoader::register('JBSAdmin', BIBLESTUDY_PATH_ADMIN_LIB . '/biblestudy.admin.class.php');
require_once (BIBLESTUDY_PATH_ADMIN_HELPERS . '/params.php');
require_once (BIBLESTUDY_PATH_LIB . '/biblestudy.images.class.php');
include_once (BIBLESTUDY_PATH_ADMIN_HELPERS . '/translated.php');

/**
 * View class for SeriesDisplay
 *
 * @package  BibleStudy.Site
 * @since    7.0.0
 *
 * @todo need to recode to JBSM/MVC Standers and update the way we do params.  bcc
 */
class BiblestudyViewSeriesdisplay extends JViewLegacy
{

	/**
	 * State
	 *
	 * @var array
	 */
	protected $state = null;

	/**
	 * Item
	 *
	 * @var array
	 */
	protected $item = null;

	/**
	 * Items
	 *
	 * @var array
	 */
	protected $items = null;

	/**
	 * Pagination
	 *
	 * @var array
	 */
	protected $pagination = null;

	protected $admin;

	protected $admin_params;

	protected $page;

	protected $seriesstudies;

	protected $template;

	protected $params;

	protected $article;

	protected $passage_link;

	protected $studies;

	protected $request_url;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{

		$mainframe = JFactory::getApplication();
		$input     = new JInput;
		$option    = $input->get('option', '', 'cmd');
		$document  = JFactory::getDocument();
		$document->addScript('http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js');
		$document->addScript(JURI::base() . 'media/com_biblestudy/js/biblestudy.js');
		$document->addScript(JURI::base() . 'media/com_biblestudy/player/jwplayer.js');
		$pathway       = $mainframe->getPathWay();
		$contentConfig = JFactory::getApplication('site')->getParams();
		$dispatcher    = JDispatcher::getInstance();

		// Get the menu item object
		// Load the Admin settings and params from the template
		$this->addHelperPath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers');
		$this->loadHelper('params');
		$this->admin = JBSMParams::getAdmin();
		$items       = $this->get('Item');

		// Get the series image
		$images              = new JBSMImages;
		$image               = $images->getSeriesThumbnail($items->series_thumbnail);
		$items->image        = '<img src="' . $image->path . '" height="' . $image->height . '" width="' . $image->width . '" alt="" />';
		$teacherimage        = $images->getTeacherThumbnail($items->thumb, $image2 = null);
		$items->teacherimage = '<img src="' . $teacherimage->path . '" height="' . $teacherimage->height . '" width="'
				. $teacherimage->width . '" alt="" />';
		$t                   = $input->get('t', '1', 'int');

		if (!$t)
		{
			$t = 1;
		}

		$template = $this->get('template');

		// Convert parameter fields to objects.
		$registry = new JRegistry;
		$registry->loadString($template[0]->params);
		$params   = $registry;

		// Convert parameter fields to objects.
		$this->admin_params = $this->admin->params;
		$css                = $params->get('css');

		if ($css <= "-1"){
			$document->addStyleSheet(JURI::base() . 'media/com_biblestudy/css/biblestudy.css');
		}
		else
		{
			$document->addStyleSheet(JURI::base() . 'media/com_biblestudy/css/site/' . $css);
		}

		$items->slug = $items->alias ? ($items->id . ':' . $items->alias) : str_replace(' ', '-', htmlspecialchars_decode($items->series_text, ENT_QUOTES))
				. ':' . $items->id;
		$itemparams  = $mainframe->getPageParameters();

		// Get studies associated with the series
		$pagebuilder = new JBSPagebuilder;
		$whereitem   = $items->id;
		$wherefield  = 'study.series_id';

		$limit       = $params->get('series_detail_limit', 10);
		$seriesorder = $params->get('series_detail_order', 'DESC');
		$studies     = $pagebuilder->studyBuilder($whereitem, $wherefield, $params, $this->admin_params, $limit, $seriesorder);

		foreach ($studies AS $i => $study)
		{
			$pelements               = $pagebuilder->buildPage($study, $params, $this->admin_params);
			$studies[$i]->scripture1 = $pelements->scripture1;
			$studies[$i]->scripture2 = $pelements->scripture2;
			$studies[$i]->media      = $pelements->media;
			$studies[$i]->duration   = $pelements->duration;
			$studies[$i]->studydate  = $pelements->studydate;
			$studies[$i]->topics     = $pelements->topics;

			if (isset($pelements->study_thumbnail)):
				$studies[$i]->study_thumbnail = $pelements->study_thumbnail;
			else:
				$studies[$i]->study_thumbnail = null;
			endif;

			if (isset($pelements->series_thumbnail)):
				$studies[$i]->series_thumbnail = $pelements->series_thumbnail;
			else:
				$studies[$i]->series_thumbnail = null;
			endif;
			$studies[$i]->detailslink = $pelements->detailslink;
			$studies[$i]->studyintro  = $pelements->studyintro;

			if (isset($pelements->secondary_reference))
			{
				$studies[$i]->secondary_reference = $pelements->secondary_reference;
			}
			else
			{
				$studies[$i]->secondary_reference = '';
			}
			if (isset($pelements->sdescription))
			{
				$studies[$i]->sdescription = $pelements->sdescription;
			}
			else
			{
				$studies[$i]->sdescription = '';
			}

		}
		$this->seriesstudies = $studies;
		$this->page          = $items;

		// Prepare meta information (under development)
		if ($itemparams->get('metakey'))
		{
			$document->setMetadata('keywords', $itemparams->get('metakey'));
		}
		elseif (!$itemparams->get('metakey'))
		{
			$document->setMetadata('keywords', $this->admin_params->get('metakey'));
		}

		if ($itemparams->get('metadesc'))
		{
			$document->setDescription($itemparams->get('metadesc'));
		}
		elseif (!$itemparams->get('metadesc'))
		{
			$document->setDescription($this->admin_params->get('metadesc'));
		}

		// Check permissions for this view by running through the records and removing those the user doesn't have permission to see
		$user   = JFactory::getUser();
		$groups = $user->getAuthorisedViewLevels();

		if (!in_array($items->access, $groups) && $items->access)
		{
			$mainframe->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');

			return;
		}

		$studies = $items;

		$input->set('returnid', $items->id);

		// Passage link to BibleGateway
		$plugin = JPluginHelper::getPlugin('content', 'scripturelinks');

		if ($plugin)
		{
			// Convert parameter fields to objects.
			$registry = new JRegistry;
			$registry->loadString($plugin->params);
			$st_params = $registry;
			$version   = $st_params->get('bible_version');
		}
		$windowopen = "window.open(this.href,this.target,'width=800,height=500,scrollbars=1');return false;";


		if (isset($items->description))
		{
			$items->text        = $items->description;
			$description        = $pagebuilder->runContentPlugins($items, $params);
			$items->description = $description->text;
		}
		// End process prepare content plugins
		$this->template = $template;
		$this->params = $params;
		$this->items = $items;

		// --$this->article = $article;
		// --$this->passage_link = $passage_link;
		$this->studies = $studies;
		$uri       = new JUri;
		$stringuri = $uri->toString();
		$this->request_url = $stringuri;

		parent::display($tpl);
	}

}
