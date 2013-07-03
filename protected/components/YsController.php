<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-26
 * Time: 上午10:54
 * To change this template use File | Settings | File Templates.
 */
class YsController extends  Controller
{

    /**
     * Checks if given view filename exists for 'this' controller.
     * @param string $view_filename basename of the view filename, with no 'file extension' (just as you'd pass to 'render()')
     * @return bool exists or not.
     */
    public function isViewFileExists($view_filename) {
        if (!is_readable($this->getViewPath() . '/' . $view_filename . '.php')) {
            return false;
        }
        return true;
    }

    /**
     * Renders a view with a layout.
     * This method overrides parent one to introduce check on the view file and throw a 404 in case it doesn't.
     * For complete documentation of this method please see parent implementation.
     *
     * @param string $view name of the view to be rendered. See {@link getViewFile} for details
     * about how the view script is resolved.
     * @param array $data data to be extracted into PHP variables and made available to the view script
     * @param boolean $return whether the rendering result should be returned instead of being displayed to end users.
     * @return string the rendering result. Null if the rendering result is not required.
     * @throws CHttpException
     */
    public function render($view, $data = null, $return = false) {
        if (!$this->isViewFileExists($view)) {
            Yii::log("Error: view file doesn't exists: " . $this->getViewPath() . '/' . $view, CLogger::LEVEL_ERROR, __METHOD__);
            throw new CHttpException(404);
        }
        return parent::render($view, $data, $return);
    }
}
