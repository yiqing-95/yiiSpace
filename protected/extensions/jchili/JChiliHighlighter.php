<?php
/**
 * JChiliHighlighter class file.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @license http://www.opensource.org/licenses/mit-license.php
 * @version 1.2
 *
 * JS Chili Plugin (c) 2007 Andrea Ercolino
 */

/**
 *
 * Syntax highlighting tool for C++, C#, CSS, Delphi, Java, JavaScript, LotusScript,
 * MySQL, PHP, and XHTML listings.
 *
 * This widget encapsulates the Chili-jQuery plugin and can be used to decorate code
 * listings in most of popular computer languages.
 * ({@link http://noteslog.com/category/chili/}).
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 */

class JChiliHighlighter extends CWidget
{
	
	/**
	 * @var string Source type - 'cplusplus', 'csharp', 'css', 'delphi',
     *             'html', 'java', 'javascript', 'lotusscript', 'mysql', 'php'.
	 * Defaults to "php".
	 */
    public $lang="php";
    /**
	 * @var string A Yii path alias.
	 * Defaults to 'PHP' (higlighting for PHP/.
	 */
    public $pathAlias;
	/**
	 * @var string Name of the source file.
	 * Defaults to an empty string.
	 */
    public $fileName;
	/**
	 * @var string source code to highlight
	 * Defaults to an empty string.
	 */
    public $code;
	/**
	 * @var array the HTML attributes that should be rendered in the HTML code tag
     * which contain the code of the computer languages.
	 */
	public $htmlOptions=array();
	/**
	 * @var boolean show line number or not?
	 * Defaults to TRUE.
	 */
    public $showLineNumbers = true;
    /**
	 * @var string start with following line number if showLineNumbers = true
	 * Defaults to 1.
	 */
    public $firstLineNumber = 1;
	/**
	 * @var mixed the CSS file used for the widget.
	 * If false, the default CSS file will be used. Otherwise, the specified CSS file
	 * will be included when using this widget.
	 */
	public $cssFile=false;
    
	/**
	 * Initializes the widget.
	 * This method registers all needed client scripts 
	 */
	public function init()
	{
		parent::init();

		$id=$this->getId();
		if (isset($this->htmlOptions['id']))
			$id = $this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;

        $this->htmlOptions['class']=$this->lang;
      	
      	$baseUrl = CHtml::asset(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets');
        $url = ($this->cssFile!==false)
             ? $this->cssFile
             : $baseUrl.'/css/jchili.css';

        $chili = (YII_DEBUG)?'/js/jquery.chili-2.2.js':'/js/jquery.chili-2.2.min.js';
        $recipes = (YII_DEBUG)?'/js/recipes.js':'/js/recipes.min.js';
  		
		Yii::app()->getClientScript()
           ->registerCoreScript('jquery')
           ->registerScriptFile($baseUrl.$chili)
           ->registerScriptFile($baseUrl.$recipes)
           ->registerCssFile($url);
        		        
        if ($this->showLineNumbers)
            echo CHtml::openTag("pre",array('class'=>'ln-'.$this->firstLineNumber));
        else
            echo CHtml::openTag("pre");
        echo CHtml::openTag("code",$this->htmlOptions)."\n";
	}

	/**
	 * Renders the close tag of the container.
	 */
	public function run()
	{
        $this->readFile();
        echo htmlspecialchars($this->code);
		echo "\n".CHtml::closeTag("code");
        echo CHtml::closeTag("pre");
	}

    /**
     * Read the code from the source file
     *
     * @access protected
     * @return void
     */
    protected function readFile()
    {
        if ($this->fileName){
            $path="";
            if(strpos($this->pathAlias,'.')) // a path alias
                $path=Yii::getPathOfAlias($this->pathAlias);

            if($path){
                $fileName = $path.DIRECTORY_SEPARATOR.$this->fileName;
            } else {
                $fileName = $this->fileName;
            }

            if (is_file($fileName)) {
                $fp = fopen($fileName, 'r');
                $this->code = fread($fp, filesize($fileName));
                fclose($fp);
            }
        }
    }
	
}
