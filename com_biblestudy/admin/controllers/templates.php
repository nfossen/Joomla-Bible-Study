<?php

/**
 * Controller for Templates
 * @package BibleStudy.Admin
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 * */
//No Direct Access
defined('_JEXEC') or die;
include_once(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_biblestudy' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'biblestudy.backup.php');
require_once ( JPATH_ROOT . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'joomla' . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'parameter.php' );

jimport('joomla.application.component.controlleradmin');

/**
 * Controller for Templates
 * @package BibleStudy.Admin
 * @since 7.0.0
 */
class BiblestudyControllerTemplates extends JControllerAdmin {

    /**
     * Proxy for getModel
     *
     * @param <String> $name    The name of the model
     * @param <String> $prefix  The prefix for the PHP class name
     * @return JModel
     *
     * @since 7.0.0
     */
    public function &getModel($name = 'Template', $prefix = 'BiblestudyModel') {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }

    /**
     * Import Template
     *
     * @return boolean
     */
    function template_import() {
        /**
         * Attempt to increase the maximum execution time for php scripts with check for safe_mode.
         */
        if (!ini_get('safe_mode')) {
            set_time_limit(300);
        }

        $result = false;
        $userfile = JRequest::getVar('template_import', null, 'files', 'array');
        // Make sure that file uploads are enabled in php
        if (!(bool) ini_get('file_uploads')) {
            JError::raiseWarning('SOME_ERROR_CODE', JText::_('JBS_CMN_UPLOADS_NOT_ENABLED'));
            $this->setRedirect('index.php?option=com_biblestudy&view=templates');
        }


        // If there is no uploaded file, we have a problem...
        if (!is_array($userfile)) {
            JError::raiseWarning('SOME_ERROR_CODE', JText::_('JBS_CMN_NO_FILE_SELECTED'));
            $this->setRedirect('index.php?option=com_biblestudy&view=templates');
        }

        // Check if there was a problem uploading the file.
        if ($userfile['error'] || $userfile['size'] < 1) {
            JError::raiseWarning('SOME_ERROR_CODE', JText::_('JBS_CMN_WARN_INSTALL_UPLOAD_ERROR'));
            $this->setRedirect('index.php?option=com_biblestudy&view=templates');
        }

        // Build the appropriate paths
        $config = JFactory::getConfig(); // not sure if this is needed.
        $tmp_dest = JPATH_SITE . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $userfile['name'];

        $tmp_src = $userfile['tmp_name'];

        // Move uploaded file
        jimport('joomla.filesystem.file');
        move_uploaded_file($tmp_src, $tmp_dest); // declaring move not as a vareble not needed.

        $db = JFactory::getDBO();

        $query = file_get_contents(JPATH_SITE . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $userfile['name']);
        $queries = $db->splitSql($query); //dump($query,'query: '); dump($queries,'queries: ');
        if (count($queries) == 0) {
            // No queries to process
            return 0;
        }
//        //get all of the items in the db
//        $query = 'SELECT filename FROM #__bsms_styles WHERE published = 1';
//        $db->setQuery($query);
//        $styles = $db->loadObjectList();
//        //Get all templates
//        $query = 'SELECT title FROM #__bsms_templates WHERE published = 1';
//        $db->setQuery($query);
//        $temps = $db->loadObjectList();
//        //Get all template files
//        $query = 'SELECT filename FROM #__bsms_templatecode WHERE published = 1';
//        $db->setQuery($query);
//        $codes = $db->loadObjectList();

        foreach ($queries as $querie) {
            $querie = trim($querie);
            if (substr_count($querie, 'INSERT')) {
                if ($querie != '' && $querie{0} != '#') {
                    //check for duplicate names and change
                    if (substr_count($querie, '#__bsms_styles')) {
                        $this->performDB($querie);
                        $query = 'SELECT filename, id from #__bsms_styles ORDER BY id DESC LIMIT 1';
                        $db->setQuery($query);
                        $data = $db->loadObject();
                        $querie1 = "UPDATE `#__bsms_styles` SET `filename` = '" . $data->filename . "_copy' WHERE `id` = '" . $data->id . "'";
                        $this->performDB($querie1);
                    } elseif (substr_count($querie, '#__bsms_templatecode')) {
                        $this->performDB($querie);
                        $query = 'SELECT filename, id, type from #__bsms_templatecode ORDER BY id DESC LIMIT 1';
                        $db->setQuery($query);
                        $data = $db->loadObject();
                        $querie2 = "UPDATE #__bsms_templatecode SET `filename` = '" . $data->filename . "_copy' WHERE `id` = '" . $data->id . "'";
                        $this->performDB($querie2);
                        $type = $data->type;
                        switch ($type) {
                            case 1:
                                //sermonlist
                                //  $sermonstemplate = '"sermonstemplate":"'.$data->filename.'"';
                                $sermonstemplate = $data->id;
                                break;

                            case 2:
                                //sermon
                                //  $sermontemplate = '"sermontemplate":'.$data->filename.'"';
                                $sermontemplate = $data->id;
                                break;

                            case 3:
                                //teachers
                                // $teacherstemplate = '"teacherstemplate":'.$data->filename.'"';
                                $teacherstemplate = $data->id;
                                break;

                            case 4:
                                //teacher
                                //  $teachertemplate = '"teachertemplate":'.$data->filename.'"';
                                $teachertemplate = $data->id;
                                break;

                            case 5:
                                //serieslist
                                // $seriesdisplays = '"seriesdisplaystemplate":'.$data->filename.'"';
                                $seriesdisplays = $data->id;
                                break;

                            case 6:
                                //series
                                // $seriesdisplay = '"seriesdisplaytemplate":'.$data->filename.'"';
                                $seriesdisplay = $data->id;
                                break;
                            case 7:
                                //module
                                //  $moduletemplate = '"moduletemplate":"'.$data->filename.'"';
                                $moduletemplate = $data->id;
                                break;
                        }
                    } elseif (substr_count($querie, '#__bsms_templates')) {
                        $query = 'SELECT filename from #__bsms_styles ORDER BY id DESC LIMIT 1';
                        $db->setQuery($query);
                        $data = $db->loadObject();
                        $css = $data->filename . ".css";
                        dump($css, 'css');
                        $this->performDB($querie);
                        $query = 'SELECT id, title, params from #__bsms_templates ORDER BY id DESC LIMIT 1';
                        $db->setQuery($query);
                        $data = $db->loadObject();
                        $querie3 = "UPDATE #__bsms_templates SET`title` = '" . $data->title . "_copy' WHERE `id` = '" . $data->id . "'";
                        $this->performDB($querie3);
                        dump($data->id, 'id');
                        JTable::addIncludePath(JPATH_COMPONENT . '/tables');
                        $table = JTable::getInstance('Template', 'Table', array('dbo' => $db));
                        try {
                            $table->load($data->id);
                        } catch (Exception $e) {
                            echo 'Caught exception: ', $e->getMessage(), "\n";
                        }
                        //need to adjust the params and write back
                        $registry = new JRegistry();
                        $registry->loadJSON($table->params);
                        $params = $registry;
                        $params->set('css', $css);
                        $params->set('sermonstemplate', $sermonstemplate);
                        $params->set('sermontemplate', $sermontemplte);
                        $params->set('teacherstemplate', $teacherstemplate);
                        $params->set('teachertemplate', $teachertemplate);
                        $params->set('seriesdisplaystemplate', $seriesdisplaystemplate);
                        $params->set('seriesdisplaytemplate', $seriesdisplaytemplate);
                        $params->set('moduletemplate', $moduletemplate);
                        dump($params->toString(), 'params');
                        //Now write the params back into the $table array and store.
                        $table->params = (string) $params->toString();
                        if (!$table->store()) {
                            $this->setError($db->getErrorMsg());
                        }
                    }
                }
            }
        }
        $message = JText::_('JBS_TPL_IMPORT_SUCCESS');
        $this->setRedirect('index.php?option=com_biblestudy&view=templates', $message);
    }

    /**
     * Export the Template
     *
     * @return boolean
     */
    function template_export() {
        $data = JRequest::getVar('template_export', '', 'post', '');
        $exporttemplate = $data;
        if (!$exporttemplate) {
            $message = JText::_('JBS_TPL_NO_FILE_SELECTED');
            $this->setRedirect('index.php?option=com_biblestudy&view=templates', $message);
        }
        jimport('joomla.filesystem.file');
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('t.id, t.type, t.params, t.title, t.text');
        $query->from('#__bsms_templates as t');
        $query->where('t.id = ' . $exporttemplate);
        $db->setQuery($query);
        $result = $db->loadObject();
        $objects[] = $this->getExportSetting($result, $data);
        $filecontents = implode(' ', $objects);
        $filename = $result->title . '.sql';
        $filepath = JPATH_ROOT . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $filename;
        if (!JFile::write($filepath, $filecontents)) {
            return false;
        }
        $xport = new JBSExport();
        $xport->output_file($filepath, $filename, 'text/x-sql');
        JFile::delete($filepath);
        $message = JText::_('JBS_TPL_EXPORT_SUCCESS');
        $this->setRedirect('index.php?option=com_biblestudy&view=templates', $message);
    }

    /**
     * Get Exported Template Settings
     *
     * @param object $result
     * @param object $data
     * @return string
     */
    private function getExportSetting($result, $data) {
        $registry = new JRegistry;
        $registry->loadJSON($result->params);
        $params = $registry;
        $db = JFactory::getDBO();
        $objects = '';
        $objects = "--\n-- Template Table\n--\n";
        //Create the main template insert
        $objects .= "\nINSERT INTO #__bsms_templates SET `type` = '" . $db->getEscaped($result->type) . "',";
        $objects .= "\n`params` = '" . $db->getEscaped($result->params) . "',";
        $objects .= "\n`title` = '" . $db->getEscaped($result->title) . "',";
        $objects .= "\n`text` = '" . $db->getEscaped($result->text) . "';";

        //Get the individual template files
        $sermons = $params->get('sermonstemplate');
        if ($sermons) {
            $objects .= "\n--\n-- Sermons\n--";
            $objects .= $this->getTemplate($sermons);
        }
        $sermon = $params->get('sermontemplate');
        if ($sermon) {
            $objects .= "\n--\n-- Sermon\n--";
            $objects .= $this->getTemplate($sermon);
        }
        $teachers = $params->get('teacherstemplate');
        if ($teachers) {
            $objects .= "\n--\n-- Teachers\n--";
            $objects .= $this->getTemplate($teachers);
        }
        $teacher = $params->get('teachertemplate');
        if ($teacher) {
            $objects .= "\n--\n-- Teacher\n--";
            $objects .= $this->getTemplate($teacher);
        }
        $seriesdisplays = $params->get('seriesdisplaystemplate');
        if ($seriesdisplays) {
            $objects .= "\n--\n-- Seriesdisplays\n--";
            $objects .= $this->getTemplate($seriesdisplays);
        }
        $seriesdisplay = $params->get('seriesdisplaytemplate');
        if ($seriesdisplay) {
            $objects .= "\n--\n-- SeriesDisplay\n--";
            $objects .= $this->getTemplate($seriesdisplay);
        }
        $css = $params->get('css');
        $length = strlen($css);
        $css = substr($css, 0, -4);
        if ($css) {
            $objects .= "\n\n--\n-- CSS Style Code\n--\n";
            $query2 = $db->getQuery(true);
            $query2->select('style.*');
            $query2->from('#__bsms_styles AS style');
            $query2->where('style.filename = "' . $css . '"');
            $db->setQuery($query2);
            $db->query();
            $cssresult = $db->loadObject();
            $objects .= "\nINSERT INTO #__bsms_styles SET `published` = '1',\n`filename` = '" . $db->getEscaped($cssresult->filename) . "',\n`stylecode` = '" . $db->getEscaped($cssresult->stylecode) . "';\n";
        }
        $objects .= "\n-- --------------------------------------------------------\n\n";
        return $objects;
    }

    /**
     * Get Template Settings
     *
     * @param array $template
     * @return boolean|string
     */
    function getTemplate($template) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('tc.id, tc.templatecode,tc.type,tc.filename');
        $query->from('#__bsms_templatecode as tc');
        $query->where('tc.filename ="' . $template . '"');
        $db->setQuery($query);
        if (!$object = $db->loadObject()) {
            return false;
        }
        $templatereturn = '
                        INSERT INTO #__bsms_templatecode SET `type` = "' . $db->getEscaped($object->type) . '",
                        `templatecode` = "' . $db->getEscaped($object->templatecode) . '",
                        `filename`="' . $db->getEscaped($template) . '",
                        `published` = "1";
                        ';
        return $templatereturn;
    }

    function performDB($query) {
        $db = JFactory::getDBO();
        $db->setQuery($query);
        if (!$db->query()) {
            JError::raiseWarning(1, JText::_('JBS_CMN_DB_ERROR') . $db->getErrorNum() . " " . $db->stderr(true));
            return false;
        }
        return true;
    }

    function getTemplateType($type) {
        if ($data->id) {
            switch ($type) {
                case 1:
                    //sermonlist
                    $return = 'sermonstemplate';
                    break;

                case 2:
                    //sermon
                    $return = 'sermontemplate';
                    break;

                case 3:
                    //teachers
                    $return = 'teacherstemplate';
                    break;

                case 4:
                    //teacher
                    $return = 'teachertemplate';
                    break;

                case 5:
                    //serieslist
                    $return = 'seriesdisplaystemplate';
                    break;

                case 6:
                    //series
                    $return = 'seriesdisplaytemplate';
                    break;
                case 7:
                    //module
                    $moduletemplate = $data->filename; // FixeMe look like this is not right.
                    break;
            }
            return $return;
        }
    }

}
