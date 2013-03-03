<?php
/**
 * @package    BibleStudy.Admin
 * @copyright  (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;


/**
 * View class for Migration
 *
 * @package  BibleStudy.Admin
 * @since    8.0.0
 */
class BiblestudyViewMigration extends JViewLegacy
{

	/** @var array The pre versions to process */
	private $_versionStack = array();

	/** @var int Total numbers of Versions */
	public $totalVersions = 0;

	/** @var int Numbers of Versions already processed */
	public $doneVersions = 0;

	/** @var array Call stack for the Visioning System. */
	public $callstack = array();

	/** @var array Array of SQL files to parse. */
	private $_filesStack = array();

	/** @var array Array of PHP Function to parse. */
	private $_afterStack = array();

	protected $more;

	protected $percentage;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @see     fetch()
	 * @since   11.1
	 */
	public function display($tpl = null)
	{
		$app   = JFactory::getApplication();
		$state = $app->input->getBool('scanstate', false);
		$this->loadStack();

		if ($state)
		{
			if ($this->totalVersions > 0)
			{
				$percent = min(max(round(100 * $this->doneVersions / $this->totalVersions), 1), 100);
			}

			$more = true;
		}
		else
		{
			$percent = 100;
			$more    = false;
		}

		$this->more = & $more;
		$this->setLayout('default');

		if (version_compare(JVERSION, '3.0', 'ge'))
		{
			JHTML::_('behavior.framework');
		}
		else
		{
			JHTML::_('behavior.mootools');
		}
		$this->percentage = & $percent;

		if ($more)
		{
			$script = "window.addEvent( 'domready' ,  function() {\n";
			$script .= "document.forms.adminForm.submit();\n";
			$script .= "});\n";
			JFactory::getDocument()->addScriptDeclaration($script);
		}
		JToolBarHelper::title(JText::_('JBS_CMN_ADMINISTRATION'), 'administration');
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('JBS_TITLE_ADMINISTRATION'));

		return parent::display($tpl);
	}

	/**
	 * Loads the Versions/SQL/After stack from the session
	 *
	 * @return void
	 */
	private function loadStack()
	{
		$session = JFactory::getSession();
		$stack   = $session->get('migration_stack', '', 'biblestudy');

		if (empty($stack))
		{
			$this->_versionStack = array();
			$this->_filesStack   = array();
			$this->_afterStack   = array();
			$this->totalVersions = 0;
			$this->doneVersions  = 0;

			return;
		}

		if (function_exists('base64_encode') && function_exists('base64_decode'))
		{
			$stack = base64_decode($stack);

			if (function_exists('gzdeflate') && function_exists('gzinflate'))
			{
				$stack = gzinflate($stack);
			}
		}
		$stack = json_decode($stack, true);

		$this->_versionStack = $stack['version'];
		$this->_filesStack   = $stack['files'];
		$this->_afterStack   = $stack['after'];
		$this->totalVersions = $stack['total'];
		$this->doneVersions  = $stack['done'];

	}
}
