<?php

/**
 * @version     
 * @since 7.1.0
 * @package BibleStudy
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 * */
//No Direct Access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

abstract class modelClass extends JModelList {

}

class BiblestudyModelstyles extends modelClass {

    /**
     * locationslist data array
     *
     * @var array
     */
   

    public function __construct() {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'style.id',
                'published', 'style.published',
                'ordering', 'style.ordering',
                'access', 'style.access',
            );
        }

        parent::__construct();
    }

  
    /**
     * @since   7.1
     */
    protected function populateState($ordering = null, $direction = null) {
        
        // Adjust the context to support modal layouts.
        if ($layout = JRequest::getVar('layout')) {
            $this->context .= '.' . $layout;
        }

        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        parent::populateState('style.filename', 'ASC');
    }

    protected function getListQuery() {

        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select(
                $this->getState(
                        'list.select', 'style.id, style.published, style.filename, style.stylecode'));
        $query->from('`#__bsms_styles` AS style');

        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('style.published = ' . (int) $published);
        } else if ($published === '') {
            $query->where('(style.published = 0 OR style.published = 1)');
        }

        return $query;
    }

}