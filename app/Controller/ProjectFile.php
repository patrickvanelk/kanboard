<?php

namespace Kanboard\Controller;

/**
 * Project File Controller
 *
 * @package  controller
 * @author   Frederic Guillot
 */
class ProjectFile extends Base
{
    /**
     * File upload form
     *
     * @access public
     */
    public function create()
    {
        $project = $this->getProject();

        $this->response->html($this->template->render('project_file/create', array(
            'project' => $project,
            'max_size' => $this->helper->text->phpToBytes(ini_get('upload_max_filesize')),
        )));
    }

    /**
     * Save uploaded files
     *
     * @access public
     */
    public function save()
    {
        $project = $this->getProject();

        if (! $this->projectFile->uploadFiles($project['id'], $this->request->getFileInfo('files'))) {
            $this->flash->failure(t('Unable to upload the file.'));
        }

        $this->response->redirect($this->helper->url->to('ProjectOverview', 'show', array('project_id' => $project['id'])), true);
    }

    /**
     * Remove a file
     *
     * @access public
     */
    public function remove()
    {
        $this->checkCSRFParam();
        $project = $this->getProject();
        $file = $this->projectFile->getById($this->request->getIntegerParam('file_id'));

        if ($this->projectFile->remove($file['id'])) {
            $this->flash->success(t('File removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this file.'));
        }

        $this->response->redirect($this->helper->url->to('ProjectOverview', 'show', array('project_id' => $project['id'])));
    }

    /**
     * Confirmation dialog before removing a file
     *
     * @access public
     */
    public function confirm()
    {
        $project = $this->getProject();
        $file = $this->projectFile->getById($this->request->getIntegerParam('file_id'));

        $this->response->html($this->template->render('project_file/remove', array(
            'project' => $project,
            'file' => $file,
        )));
    }
}
