<?php
/**
 * CSmartyViewRenderer class file.
 *
 * @author Svilen Spasov <svilen@svilen.com>
 */

/**
 * CSmartyViewRenderer implements a views renderer that allows users to use Smarty template syntax.
 *
 * To use CSmartyViewRenderer, configure it as an application component named "viewRenderer" in the application configuration:
 * <pre>
 * array(
 *     'com'=>array(
 *         ......
 *         'viewRenderer'=>array(
 *             'class'=>'CSmartyViewRenderer',
 *         ),
 *     ),
 * )
 * </pre>
 *
 * CSmartyViewRenderer allows you to write views files with the Smarty syntax:
 * @author Svilen Spasov <svilen@svilen.com>
 * @version 1.3
 * @package system.web.renderers
 * @since 2010-02-25
 */
 
require_once "smarty/Smarty.class.php";
 
class CSmartyViewRenderer extends CViewRenderer {
    private $_input;
    private $_output;
    private $_sourceFile;
    private $_smarty;
 
    function __construct() {
    }
 	
	/**
	 * Parses the source views file and saves the results as another file.
	 * This method is required by the parent class.
	 * @param	string	$sourceFile		string the source views file path
	 * @param	string	$viewFile		string the resulting views file path
	 */
    protected function generateViewFile( $sourceFile, $viewFile ) {
		// set smarty settings
		$this->setSmartySettings();
		
		return $this->_smarty->fetch($sourceFile);
    }
 
    /**
     * Renders a views file.
     * This method is required by {@link IViewRenderer}.
     * @param CBaseController the controller or widget who is rendering the views file.
     * @param string the views file path
     * @param mixed the data to be passed to the views
     * @param boolean whether the rendering result should be returned
     * @return mixed the rendering result, or null if the rendering result is not needed.
     */
    public function renderFile($context, $sourceFile, $data, $return)
    {
        if (!is_file($sourceFile) || ($file=realpath($sourceFile)) === false )
			throw new CException(Yii::t('yii','View file "{file}" does not exist.',array('{file}'=>$sourceFile)));
		
		$viewFile = $this->getViewFile($sourceFile);
			
		
		// added Yii::app()->params['smarty']['on'] for removing Smarty loading/parsing on some pages
		// this saves some memory (~400kb)
		if ( Yii::app()->params['smarty']['on'] == true) {
			// load smarty
			$this->_smarty = new Smarty();
		
			// assign $data to smarty
			if (!empty($data))				
				$this->setAssigns( $data );
		
			$viewFileRendered = $this->generateViewFile($sourceFile, $viewFile, $data);
		}
		else {
			$viewFileRendered = file_get_contents($sourceFile);
		}
		
		return $this->renderInternal($viewFileRendered, $data, $return);
    }
	
	/**
	 * Renders a views file.
	 * This method includes the views file as a PHP script
	 * and captures the display result if required.
	 * @param string views file
	 * @param array data to be extracted and made available to the views file
	 * @param boolean whether the rendering result should be returned as a string
	 * @return string the rendering result. Null if the rendering result is not required.
	 */
	public function renderInternal(&$_viewFile_,$_data_=null,$_return_=false)
	{
		// we use special variable names here to avoid conflict when extracting data
		if(is_array($_data_))
			extract($_data_,EXTR_PREFIX_SAME,'data');
		else
			$data=$_data_;
		if($_return_)
		{
			ob_start();
			ob_implicit_flush(false);
			eval(' ?>'.$_viewFile_."\n<?php ");
			
			return ob_get_clean();
		}
		else {
			eval(' ?>'.$_viewFile_."\n<?php ");
		}
	}
	
	/**
	 * Assign variables to smarty
	 * @param	array 	$params		data that should be assigned to smarty
	 */
	private function setAssigns( $params ) {
		if ( is_array( $params ) || is_object( $params ) ) {
			$arrKeys = array_keys( $params );

			foreach ( $arrKeys as $key ) {
				if ( is_object( $params[$key] ) && Yii::app()->params['smarty']['register_objects'] )
					$this->_smarty->register_object( $key, $params[$key], null, false );
				else
					$this->_smarty->assign( $key, $params[$key] );
			}
		}
		else {
            // Data assign
            $this->_smarty->assign("__DATA__", $data);
        }
		
		/* Auto-populate to templates */
		
		// pass main Yii object to templates, so we can use Yii functions like:
		// Yii->createUrl()
		if ( !empty( Yii::app()->params['smarty']['register_objects'] ) )
			$this->_smarty->register_object( 'Yii', Yii::app(), null, false );
		else
			$this->_smarty->assign( 'Yii', Yii::app() );
	}
	
	/**
	 * Set Smarty settings
	 */
	public function setSmartySettings() {
		if ( !empty( Yii::app()->params['smarty']['template_dir'] ) )
			$this->_smarty->template_dir = Yii::app()->params['smarty']['template_dir'];
			
		if ( !empty( Yii::app()->params['smarty']['compile_dir'] ) )
			$this->_smarty->compile_dir = Yii::app()->params['smarty']['compile_dir'];
			
		if ( !empty( Yii::app()->params['smarty']['config_dir'] ) )
			$this->_smarty->config_dir = Yii::app()->params['smarty']['config_dir'];
			
		if ( !empty( Yii::app()->params['smarty']['cache_dir'] ) )
			$this->_smarty->cache_dir = Yii::app()->params['smarty']['cache_dir'];
				
		if ( !empty( Yii::app()->params['smarty']['compile_check'] ) )
			$this->_smarty->compile_check = Yii::app()->params['smarty']['compile_check'];
			
		if ( !empty( Yii::app()->params['smarty']['force_compile'] ) )
			$this->_smarty->force_compile = Yii::app()->params['smarty']['force_compile'];
			
		if ( !empty( Yii::app()->params['smarty']['use_sub_dirs'] ) )
			$this->_smarty->use_sub_dirs = Yii::app()->params['smarty']['use_sub_dirs'];
			
		if ( !empty( Yii::app()->params['smarty']['cache_lifetime'] ) )
			$this->_smarty->cache_lifetime = Yii::app()->params['smarty']['cache_lifetime'];
			
		if ( !empty( Yii::app()->params['smarty']['debugging'] ) )
			$this->_smarty->debugging = Yii::app()->params['smarty']['debugging'];
			
		if ( !empty( Yii::app()->params['smarty']['clear_all_cache'] ) )
			$this->_smarty->clear_all_cache();
			
		if ( !empty( Yii::app()->params['smarty']['left_delimiter'] ) )
			$this->_smarty->left_delimiter = Yii::app()->params['smarty']['left_delimiter'];
			
		if ( !empty( Yii::app()->params['smarty']['right_delimiter'] ) )
			$this->_smarty->right_delimiter = Yii::app()->params['smarty']['right_delimiter'];
		
		// set smarty compile_id to server_name
		$this->_smarty->compile_id = $_SERVER['SERVER_NAME'];
	}
}
