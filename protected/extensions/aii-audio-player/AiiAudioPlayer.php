<?php
	/**
	 * This Widget is using Audio Player Wordpress plugin from 1 pixel out
	 * {@link http://www.1pixelout.net/code/audio-player-wordpress-plugin/}
	 * This widget concerns using aforementioned player for non-Wordpress projects
	 * 
	 * To see more information about using aforementioned player for non-Wordpress project, 
	 * please visit {@link http://wpaudioplayer.com/standalone}
	 * 
	 * To see more inormation about options of Audio Player Wordpress plugin
	 * read tutorial "Customizing Audio Player" 
	 * {@link http://www.macloo.com/examples/audio_player/options.html}
	 * 
	 * This extension requires {@link AiiJsAndCssPublishRegisterBehavior}
	 * for publishing assets
	 * 
	 * @author Tomasz Suchanek <tomasz.suchanek@gmail.com>
	 * @copyright Copyright &copy; 2008-2010 Tomasz "Aztech" Suchanek
	 * @license http://www.yiiframework.com/license/
	 * @package aii.extensions
	 * @version 0.1.0
	 * @uses {@link AiiJsAndCssPublishRegisterBehavior}
	 */
  class AiiAudioPlayer extends CWidget
  {
  	
  	const OPTION_TRACK 	= 'track';
  	const OPTION_PLAYER = 'player';
  	const OPTION_FLASH 	= 'flashplayer';
  	const OPTION_COLOUR = 'colourscheme';
  	const OPTION_SEM  	= 'semantic';
  	
  	/**
  	 * @var string - Player Id (needed when using multiple players on one site)
	 * default to 'audioplayer'
  	 */
  	public $playerID = 'audioplayer';
  	
  	/**
  	 * 
  	 * @var boolean, true if only one player appears on page
  	 */
  	public $singlePlayer = false;
  	
  	/**
  	 * @var array - list of mp3 files {@link mp3Folder}
  	 * It's an array with 4 entries
  	 * - soundFile - required, comma-delimited list of mp3 files
  	 * - alternative - required, alternative content if player will not be displayed 
  	 * - titles - optional, comma-delimited list of titles (overrides ID3 information) 
  	 * - artists - optional, comma-delimited list of artists, overrides ID3 information
  	 * with this way of declaration you will get one player with one or multiple mp3s.
  	 * Example:
  	 * 	<code>
  	 * 		array( 
  	 * 			'soundFile' => "example.mp3, interview.mp3", 
  	 * 			'titles' => "Example MP3 , My latest interview",
  	 * 			'artists' => "Artist name 1, artist name 2"
  	 * 			'alternative' => "sorry, no file found"
  	 * 		);
  	 * 	</code>
  	 * If you wan't to create more than one player, 
  	 * please pass to this property array of aforementioned options
  	 * with player ids. I
  	 * Example:
  	 * 	<code>
  	 * 		array(
  	 * 			'player1' => array(
  	 * 				'soundFile' => "poker_face-lady_gaga.mp3", 
  	 * 				'alternative' => "sorry, no file found"
  	 * 			), 
  	 * 			'player2' => array( 
  	 * 				'soundFile' => "example.mp3, interview.mp3", 
  	 * 				'titles' => "Example MP3 , My latest interview",
  	 * 				'artists' => "Artist name 1, artist name 2"
  	 * 				'alternative' => "sorry, no file found"
  	 * 			); 
  	 * 		);
  	 * 	</code>
  	 */
    public $trackOptions = array( );
    
    /**
     * 
     * @var array list of player options
     * Below code shows also default values
     * 	<code>
     * 		array (
     * 			'autostart' => "no",	//if yes, player starts automatically
	 * 			'loop' 		=> "no",	//if yes, player loops
	 * 			'animation'	=> "yes", 	//if no, player is always open
	 * 			'remaining'	=> "no",	//if yes, shows remaining track time rather than ellapsed time
	 * 			'noinfo'	=> "no",	//if yes, disables the track information display
	 *			'initialvolume' => 60, 	//initial volume level (from 0 to 100)
	 *			'buffer'	=> 5,		//buffering time in seconds
	 * 			'encode'	=> "no",	//indicates that the mp3 file urls are encoded
	 * 			'checkpolicy' => "no"	//tells Flash to look for a policy file when loading mp3 files
	 *			(this allows Flash to read ID3 tags from files hosted on a different domain)
	 * 			'rtl'		=> "no"		//switches the layout to RTL (right to left) for Hebrew and Arabic languages
	 * 		);
	 * </code>
	 * 
	 * If you would like to specify different options to each player 
	 * please pass array of array options here, where key in first array is player id
	 * Example:
	 * 	<code>
	 * 		array(
	 * 			'player1' => array ( ... ),	//header options for 1st player
	 * 			'player2' => array ( ... ), //header options for 2nd player
	 * 			...
	 * 			'playerN' => array ( ... ), //header options for Nth player
	 * 		);
	 * 	</code>
     */
    public $playerOptions = array( );
    
    /**
     * 
     * @var array list of flash player options
     * 	<code>
     * 		array(
	 *			'width' => 290,					//required, width of the player. e.g. 290 (290 pixels) or 100%		
	 * 			'transparentpagebg' => "no",	//if yes, the player background is transparent (matches the page background)
	 * 			'pagebg' => NA,					//player background color (set it to your page background when transparentbg is set to ‘no’) 
     * 		);
     * 	</code>
     * 
	 * If you would like to specify different options to each player 
	 * please pass array of array options here, where key in first array is player id
	 * Example:
	 * 	<code>
	 * 		array(
	 * 			'player1' => array ( ... ),	//header options for 1st player
	 * 			'player2' => array ( ... ), //header options for 2nd player
	 * 			...
	 * 			'playerN' => array ( ... ), //header options for Nth player
	 * 		);
	 * 	</code> 
     */
    public $flashPlayerOptions = array( );
    
    
	/**
	 * 
	 * @var array of colour scheme options
	 * 	<code>
	 * 		array(
	 * 			'bg' => "E5E5E5", 				//Background
	 * 			'leftbg' =>	"CCCCCC",			//Speaker icon/Volume control background
	 * 			'lefticon' => "333333",			//Speaker icon
	 * 			'voltrack' => "F2F2F2",			//Volume track
	 * 			'volslider'	=> "666666",		//Volume slider
	 * 			'rightbg' => "B4B4B4"			//Play/Pause button background
	 * 			'rightbghover' => "999999"		//Play/Pause button background (hover state)
	 * 			'righticon' => "333333"			//Play/Pause icon
	 * 			'righticonhover' => "FFFFFF"	//Play/Pause icon (hover state)
	 *			'loader' => "009900",			//Loading bar
	 * 			'track'	=> "FFFFFF"				//Loading/Progress bar track backgrounds
	 * 			'tracker' => "DDDDDD",			//Progress track
	 * 			'border' => "CCCCCC",			//Progress bar border
	 * 			'skip' => "666666",				//Previous/Next skip buttons
	 * 			'text' => "333333",				//Text
	 * 		);
	 * 	</code>
	 * 
	 * If you would like to specify different options to each player 
	 * please pass array of array options here, where key in first array is player id
	 * Example:
	 * 	<code>
	 * 		array(
	 * 			'player1' => array ( ... ),	//header options for 1st player
	 * 			'player2' => array ( ... ), //header options for 2nd player
	 * 			...
	 * 			'playerN' => array ( ... ), //header options for Nth player
	 * 		);
	 * 	</code>
	 */    
    public $colourSchemeOptions = array( );
    
    /**
     * Options used to initialize all players. The are overwritten 
     * options set via {@link playerOptions}, {@link flashPlayerOptions} , 
     * 	<code>
     * 		array(
     * 			AiiAudioPlayer::OPTION_PLAYER = array (	
     * 			... here options like in {@link playerOptions}
     * 			),
     * 			AiiAudioPlayer::OPTION_FLASH = array (	
     * 			... here options like in {@link flashOptions}
     * 			),
     * 			AiiAudioPlayer::OPTION_COLOUR = array (	
     * 			... here options like in {@link colourOptions}
     * 			),
     * 		);
     * 	</code>
     * 
     * {@link colourSchemeOptions}
     * @var array
     */
    public $setupOptions = array( );
  	
  	/**
  	 * 
  	 * @var string - Publised folder with mp3 files
	 * Default to null, which means that standard '{basepath}/mp3' folder under
	 * extension directory will be published
  	 */
  	public $mp3Folder = null;
  	
  	/**
  	 * @var array
  	 */
  	private $_setup = array( );
  	
  	/**
  	 * 
  	 * @var array players
  	 */
  	private $_players = array( );
  	
  	private static $_allowedKeys = array(
  		self::OPTION_TRACK => array(
  			'soundFile' => true, 
  	 		'titles'	=> false,
  	 		'artists'	=> false,  		
  		),
  		self::OPTION_SEM => array(
			'alternative' => true,  		
  		),
		self::OPTION_PLAYER => array(
	     	'autostart' 		=> false,
		 	'loop' 				=> false,
		 	'animation'			=> false,
		 	'remaining'			=> false,
		 	'noinfo'			=> false,
		 	'initialvolume' 	=> false,
		 	'buffer'			=> false,
		 	'encode'			=> false,
		 	'checkpolicy' 		=> false,
			'rtl'				=> false,		
		),
		self::OPTION_FLASH => array(
			'width' 			=> false,		
		 	'transparentpagebg' => false,
		 	'pagebg' 			=> false,  		
		),
		self::OPTION_COLOUR => array(
	  		'bg' 				=> false,
		 	'leftbg' 			=> false,
		 	'lefticon' 			=> false,
		 	'voltrack' 			=> false,
		 	'volslider' 		=> false,
		 	'rightbg' 			=> false,
		 	'rightbghover' 		=> false,
		 	'righticon' 		=> false,
		 	'righticonhover' 	=> false,
		 	'loader' 			=> false,
		 	'track'				=> false,
		 	'tracker' 			=> false,
		 	'border' 			=> false,
		 	'skip' 				=> false,
		 	'text' 				=> false,		
		),
  	);
  	
  	
  	/**
  	 * (non-PHPdoc)
  	 * @see web/widgets/CWidget#init()
  	 */
    public function init()
    {
    	parent::init( );
    	if ( $this->mp3Folder === null )
    		$this->mp3Folder = '{basePath}{/}mp3';
    	$this->attachBehavior( 'pubMan', #publishManager
    		array(
    			'class' => 'AiiPublishRegisterBehavior',
    			'cssPath' => false,
    			'jsToRegister' => array( 'audio-player.js' ),
    			'basePath' => dirname( __FILE__ ),    		
    			'jsPath' => '{assets}/js',
    			'otherResToPublish' => array( 'mp3Folder' => $this->mp3Folder ),
    	) );
    }
  	
  	protected function addOption( $option , $value , $type, $playerId = null )
  	{
  		$id = $playerId === null ? $this->playerID : $playerId;
  		if ( isset( $this->_players[$id] ) )
  		{
  			if ( $this->checkOptionName( $option , $type  ) )
  				$this->_players[$id]['options'][$option] = $value;
  		}
  		else
  			throw new CException( Yii::t( 'aii-audio-player', 'Player with id {id} dosn\'t exist!' , array ( '{id}' => $id ) ) );
  	}
  	
  	public function addTrack( $playerId , $mp3 )
  	{
  		$this->_players[$player_id][] = $mp3;
  	}
  	
  	public function addPlayerOption( )
  	{
  		$this->addOption( $option , $value , self::OPTION_PLAYER , $player_id );  		
  	}
  	
  	public function addFlashPlayerOption( )
  	{
  		$this->addOption( $option , $value , self::OPTION_FLASH , $player_id );
  	}
  	
  	public function addSemanticOption( )
  	{
  		$this->addOption( $option , $value , self::OPTION_SEM , $player_id );
  	}
  	
  	public function checkOptionName( $name , $type )
  	{
  		if ( isset( self::$_allowedKeys[$type][$name] ) )
  			return true;
  		else 
  		{
  			throw new CException( Yii::t( 'aii-audio-player' , 'Unknown option name {name}.' , array ( '{name}' => $name ) ) );
  		}  			
  	}
  	
  	protected function processOptions( )
  	{
  		
  		foreach ( $this->setupOptions as $setupOption )
  			foreach ( array( self::OPTION_PLAYER , self::OPTION_FLASH , self::OPTION_COLOUR ) as $option )
  			if ( isset ( $this->setupOptions[$option] ) )
  				$this->addSetupOptions( $this->setupOptions[$option] );
  		
  		#do we have one or more players to create?
  		if ( is_array ( current( $this->trackOptions ) ) )
	  		foreach ( $this->trackOptions as $playerId => $tracks )
	  			$this->processTrackOptions( $playerId , $tracks );
	  	else
	  		$this->processTrackOptions( $this->playerID , $this->trackOptions );
	  	foreach ( $this->_players as $playerId => $player )
	  	{
	  		#do we have player-based player options?
	  		if ( isset( $this->playerOptions[$playerId] ) )
	  			if ( is_array( $this->playerOptions[$playerId] ) )
	  				$this->addOptions( $playerId , $this->playerOptions[$playerId] , self::OPTION_PLAYER );
	  			else
	  				throw new CException( Yii::t( 'aii-audio-player' , 'Player options for player "{player}" need to be specified via array.' , array ( '{player}' => $playerId ) ) );
	  		elseif ( $this->singlePlayer === true )
	  			$this->addSetupOptions( $this->playerOptions );
			elseif ( is_array( $this->playerOptions ) )
				$this->addOptions( $playerId , $this->playerOptions , self::OPTION_PLAYER );
	  			
	  		#do we have player-based flash options?
	  		if ( isset ( $this->flashPlayerOptions[$playerId] ) )
	  			if ( is_array( $this->flashPlayerOptions[$playerId] ) )
	  				$this->addOptions( $playerId , $this->flashPlayerOptions[$playerId] , self::OPTION_FLASH );
	  			else
	  				throw new CException( Yii::t( 'aii-audio-player' , 'Flash player options for player "{player}" need to be specified via array.' , array ( '{player}' => $playerId ) ) );
			elseif ( $this->singlePlayer === true )
				$this->addSetupOptions( $this->flashPlayerOptions );
			elseif ( is_array( $this->flashPlayerOptions ) )
				$this->addOptions( $playerId , $this->flashPlayerOptions , self::OPTION_FLASH );

			#do we have player-based colour scheme options?
			if ( isset ( $this->colourSchemeOptions[$playerId] ) )
				if ( is_array( $this->colourSchemeOptions[$playerId] ) )
					$this->addOptions( $playerId , $this->colourSchemeOptions[$playerId] , self::OPTION_COLOUR );
				else
					throw new CException( Yii::t( 'aii-audio-player' , 'Colour scheme  player options for player "{player}" need to be specified via array.' , array ( '{player}' => $playerId ) ) );
			elseif ( $this->singlePlayer === true )
				$this->addSetupOptions( $this->colourSchemeOptions );
			elseif ( is_array( $this->colourSchemeOptions ) )
				$this->addOptions( $playerId , $this->colourSchemeOptions , self::OPTION_COLOUR );
	  	}
  	}
  	
  	/**
  	 * Process single track options
  	 * Track options should
  	 * @param string $playerId	player ID
  	 * @param array $options definition of mp3 files to publish
  	 */
  	protected function processTrackOptions( $playerId , array $options )
  	{
  		#mp3 file need to be specified
  		if ( !isset( $options['soundFile'] ) )
  			throw new CException( Yii::t( 'aii-audio-player' , 'Mp3 file name is missing. Please set it via track option "soundFile".') );
  		#if there is a comma we put the soundfiles into an array
  		if(strpos($options['soundFile'],',') != false) 
  		{
			$tempsoundarray=explode(',',$options['soundFile']);
			$options['soundFile']=$tempsoundarray;
  		}
  		#alternative content need to be specified
  		if ( isset( $options['alternative'] ) )
  		{
  			$this->_players[$playerId]['alternative'] = $options['alternative'];
  			unset($options['alternative']);
  		}
  		else
  			throw new CException( Yii::t( 'aii-audio-player' , 'Please specify alternative content for player {player}' , array( 'player' => $playerId ) ) );

		$this->_players[$playerId]['tracks'] = $options;	
  	}
  	
  	/**
  	 * 
  	 * @param string $playerId player ID
  	 * @param array $options options to add 
  	 */
  	protected function addOptions( $playerId , array $options , $type )
  	{
        if(!isset($this->_players[$playerId]['options']))
            $this->_players[$playerId]['options']=array();
  		if ( isset( $this->_players[$playerId] ) )
  			if ( YII_DEBUG )
  				foreach ( $options as $option => $value )
  					$this->addOption( $option , $value , $type , $playerId );
  			else
  				$this->_players[$playerId]['options'] = array_merge( $this->_players[$playerId]['options'] , $options );
  		else
  			if ( YII_DEBUG )
  				foreach ( $options as $option => $value )
  					$this->addOption( $option , $value , $type , $playerId );
  			else
  				$this->_players[$playerId]['options'] = $options; 
  	}
  	
  	protected function addSetupOptions( $options )
  	{
  		$this->_setup = array_merge( $this->_setup , $options );  		
  	}
	
  	/**
  	 * 
  	 */
	public function run( )
	{
    	$this->publishAll( );
    	$this->registerAll( );
		if ( ( $assets = $this->getPublished( '{assets}' ) ) === false )
			throw new CException( Yii::t( 'aii-audio-player' , 'Can\'t find published assets for Aii Audio Player extension.' ) );

		#create all options
		$this->processOptions();			
			
		#templates to be used to generate audio player
		$setupScriptTemplate = 'AudioPlayer.setup("{swf}"{comma}{options})'; 		
		$embedScriptTemplate = 'AudioPlayer.embed("{playerId}", {options})';  					
			
		#publish head JS with audio player setup
		$setupTr['{swf}'] = $this->getPublished( '{assets}' ).'/player.swf';		
		if ( !empty( $this->_setup ) )
		{
			$setupTr['{comma}'] = ',';			
			$setupTr['{options}'] = CJavaScript::encode( $this->_setup );
		}
		else
		{
			$setupTr['{comma}'] = '';
			$setupTr['{options}'] = '';
		}
		Yii::app()->getClientScript( )->registerScript( 'aiiaudioplayer' , strtr( $setupScriptTemplate , $setupTr ) , CClientScript::POS_HEAD ); 
		
		#echoes each players
		foreach ( $this->_players as $id => $player )
		{
			$embedTr = array( );	
			if(is_array($player['tracks']['soundFile']))
			{
				$tempfiles='';
				foreach ($player['tracks']['soundFile'] as $value)
					$tempfiles .=$this->getPublished( 'mp3Folder' ).'/'.$value.',';
				$player['tracks']['soundFile']=$tempfiles;
			}else{		
				$player['tracks']['soundFile'] = $this->getPublished( 'mp3Folder' ).'/'.$player['tracks']['soundFile'];
			}
			if ( isset( $player['options'] ) )
				$embedTr['{options}'] = CJavaScript::encode( array_merge( $player['tracks'] , $player['options'] ) );
			else
				$embedTr['{options}'] = CJavaScript::encode( $player['tracks'] );
			$embedTr['{playerId}'] = $id;
			echo CHtml::openTag( 'p', array( 'id' => $id  ) ).$player['alternative'].CHtml::closeTag( 'p' );
			echo CHtml::script( strtr( $embedScriptTemplate , $embedTr ) ); 
		}
	}
  }
?>