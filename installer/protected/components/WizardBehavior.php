<?php
/* SVN FILE: $Id: EWizardBehavior.php 6 2011-02-17 19:31:00Z Chris $*/
/**
* Wizard Behavior class file.
* Handles multi-step form navigation, data persistence, and plot-branching
* navigation.
*
* Wizard Behavoir also allows steps to be expired, the saving and restoring of
* data across sessions, and can generate a menu (defaults to CMenu) of steps.
*
* Inspired by the CakePHP Wizard component by jaredhoyt {@link http://github.com/jaredhoyt}.
*
* @copyright	Copyright &copy; 2011 PBM Web Development - All Rights Reserved
* @package		RBAM
* @since			V1.0.0
* @version		$Revision: 6 $
* @license		BSD License (see documentation)
*/
/**
* Wizard Behavior class
*/
class WizardBehavior extends CBehavior {
	const BRANCH_SELECT = 'Select';
	const BRANCH_SKIP = 'Skip';
	const BRANCH_DESELECT = 'Deselect';

	/**
	* @property boolean If true, the behavior will redirect to the "expected step"
	* after a step has been successfully completed. If false, it will redirect to
	* the next step in the steps array.
	*/
	public $autoAdvance = true;
	/**
	* @property array List of steps, in order, that are to be included in the wizard.
	* basic example: array('login_info', 'profile', 'confirm')
	*
	* Steps can be labled: array('Username and Password'=>'login_info', 'User Profile'=>'profile', 'confirm')
	*
	* The steps array can also contain branch groups that are used to determine
	* the path at runtime.
	* plot-branched example: array('job_application', array('degree' => array('college', 'degree_type'), 'nodegree' => 'experience'), 'confirm');
	*
	* The 'branch names' (ie 'degree', 'nodegree') are arbitrary; they are used as
	* selectors for the branch() method. Branches can point either to another
	* steps array, that can also have branch groups, or a single step.
	*
	* The first "non-skipped" branch in a group (see branch()) is used by default
	* if $defaultBranch==TRUE and a branch has not been specifically selected.
	*/
	public $steps = array();
	/**
	* @property boolean If true, the first "non-skipped" branch in a group will be
	* used if a branch has not been specifically selected.
	*/
	public $defaultBranch = false;
	/**
	* @property boolean Whether the wizard should go to the next step if the
	* current step expires. If true the wizard continues, if false the wizard is
	* reset and the redirects to the expiredUrl.
	*/
	public $continueOnExpired = false;
	/**
	* @property boolean If true, the user will not be allowed to edit previously
	* completed steps.
	*/
	public $forwardOnly = false;
	/**
	* @property array Owner event handlers
	*/
	public $events = array(
		'onFinished'=>'wizardFinished',
		'onProcessStep'=>'wizardProcessStep',
		'onStart'=>'wizardStart',
		'onInvalidStep'=>'wizardInvalidStep'
	);
	/**
	 * @property string Query parameter for the step. This must match the name
	 * of the parameter in the action that calls the wizard.
	 */
	public $queryParam ='step';
	/**
	* @property string The session key for the wizard.
	*/
	public $sessionKey = 'Wizard';
	/**
	* @property integer The timeout in seconds. Set to empty for no timeout.
	* Each step must be completed within the timeout period or else the wizard expires.
	*/
	public $timeout;
	/**
	* @property string The name attribute of the button used to cancel the wizard.
	*/
	public $cancelButton = 'cancel';
	/**
	* @property string The name attribute of the button used to navigate to the previous step.
	*/
	public $previousButton = 'previous';
	/**
	* @property string The name attribute of the button used to reset the wizard
	* and start from the beginning.
	*/
	public $resetButton = 'reset';
	/**
	* @property string The name attribute of the button used to save draft data.
	*/
	public $saveDraftButton = 'save_draft';

	/**
	* @property mixed Url to be redirected to after the wizard has finished.
	*/
	public $finishedUrl = '/';
	/**
	* @property mixed Url to be redirected to after 'Cancel' submit button has been pressed by user.
	*/
	public $cancelledUrl = '/';
	/**
	* @property mixed Url to be redirected to if the timeout expires.
	*/
	public $expiredUrl = '/';
	/**
	* @property mixed Url to be redirected to after 'Draft' submit button has been pressed by user.
	*
	*/
	public $draftSavedUrl = '/';
	/**
	* @property array Menu properties. In addition to the properties of CMenu
	* there is an additional previousItemCssClass that is applied to previous items.
	* @see getMenu()
	*/
	public $menuProperties = array(
		'id'=>'wzd-menu',
		'activeCssClass'=>'wzd-active',
		'firstItemCssClass'=>'wzd-first',
		'lastItemCssClass'=>'wzd-last',
		'previousItemCssClass'=>'wzd-previous'
	);
	/**
	* @property string If not empty, this is added to the menu as the last item.
	* Used to add the conclusion, i.e. what happens when the wizard completes -
	* e.g. Register, to a menu.
	*/
	public $menuLastItem;

	/**
	* @var string Internal step tracking.
	*/
	private $_currentStep;

	/**
	* @var object The menu.
	*/
	private $_menu;

	/**
	* @var CList The steps to be processed.
	*/
	private $_steps;

	/**
	* @var CMap Step Labels.
	*/
	private $_stepLabels;

	/**
	* @var string The session key that holds processed step data.
	*/
	private $_stepsKey;

	/**
	* @var string The session key that holds branch directives.
	*/
	private $_branchKey;

	/**
	* @var string The session key that holds the timeout value.
	*/
	private $_timeoutKey;

	/**
	* @var CHttpSession The session
	*/
	private $_session;

	/**
	* Attaches this behavior to the owner.
	* In addition to the CBehavior default implementation, the owner's event
	* handlers for wizard events are also attached.
	* @param CController $owner The controller that this behavior is to be attached to.
	*/
	public function attach($owner) {
		if (!$owner instanceof CController)
			throw new CException(Yii::t('wizard', 'Owner must be an instance of CController'));

		parent::attach($owner);

		foreach($this->events as $event=>$handler)
			$this->attachEventHandler($event,array($owner,$handler));

		$this->_session = Yii::app()->getSession();
		$this->_stepsKey = $this->sessionKey.'.steps';
		$this->_branchKey = $this->sessionKey.'.branches';
		$this->_timeoutKey = $this->sessionKey.'.timeout';

		$this->parseSteps();
	}

	/**
	* Run the wizard for the given step.
	* This method is called from the controller action using the wizard
	* @param string Name of step to be processed.
	*/
	public function process($step) {
		if (isset($_REQUEST[$this->cancelButton]))
			$this->cancelled($step); // Ends the wizard
		elseif (isset($_REQUEST[$this->resetButton]) && !$this->forwardOnly) {
			$this->resetWizard($step); // Restarts the wizard
			$step = null;
		}

		if (empty($step)) {
			if (!$this->hasStarted() && !$this->start())
				$this->finished(false);
			if ($this->hasCompleted())
				$this->finished(true);
			else
				$this->nextStep();
		}
		else {
			if ($this->isValidStep($step)) {
				$this->_currentStep = $step;
				if (!$this->forwardOnly && isset($_REQUEST[$this->previousButton]))
					$this->previousStep();
				elseif ($this->processStep()) {
					if (isset($_REQUEST[$this->saveDraftButton]))
						$this->saveDraft($step); // Ends the wizard
					$this->nextStep();
				}
			}
			else
				$this->invalidStep($step);
		}
	}

	/**
	* Sets data into wizard session. Particularly useful if the data
	* originated from WizardComponent::read() as this will restore a previous session.
	* $data[0] is the step data, $data[1] the branch data, $data[2] is the timeout value.
	* @param array Data to be written to the wizard session.
	* @return boolean Whether the data was successfully restored; true if the data was successfully restored, false if not
	*/
	public function restore($data) {
		if (sizeof($data)!==3 || !is_array($data[0]) || !is_array($data[1]) || !(is_integer($data[2]) || is_null($data[2])))
			return false;
		$this->_session[$this->_stepsKey]   = new CMap($data[0]);
		$this->_session[$this->_branchKey]  = new CMap($data[1]);
		$this->_session[$this->_timeoutKey] = $data[2];
		return true;
	}

	/**
	* Saves data into the Session.
	* This is normally called automatically after the onProcessStep event,
	* but can be called directly for advanced navigation purposes.
	* @param mixed Data to be saved
	* @param string Step name. If empty the current step is used.
	*/
	public function save($data,$step=null) {
		$this->_session[$this->_stepsKey][(empty($step)?$this->_currentStep:$step)] = $data;
	}

	/**
	* Reads data stored for a step.
	* @param string The name of the step. If empty the data for all steps are returned.
	* @return mixed Data for the specified step; array: data for all steps; null is no data exist for the specified step.
	*/
	public function read($step=null) {
		return (empty($step)?
			$this->_session[$this->_stepsKey]->toArray():
			$this->_session[$this->_stepsKey][$step]
		);
	}

	/**
	* Returns the one-based index of the current step.
	* Note that this is for the current steps; branching may vary the index of a given step
	*/
	public function getCurrentStep() {
		return $this->_steps->indexOf($this->_currentStep)+1;
	}

	/**
	* Returns the number of steps.
	* Note that this is for the current steps; branching may varythe number of steps
	*/
	public function getStepCount() {
		return $this->_steps->count();
	}

	public function getStepLabel($step=null) {
		if (is_null($step))
			$step = $this->_currentStep;
		$label = $this->_stepLabels->itemAt($step);
		if (!is_string($label)) {
			$label = ucwords(trim(strtolower(str_replace(array('-','_','.'),' ',preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $step)))));;
		}
		return $label;
	}

	/**
	* Resets the wizard by deleting the wizard session.
	*/
	public function reset() {
		$this->_session->remove($this->_branchKey);
		$this->_session->remove($this->_stepsKey);
		$this->_session->remove($this->_timeoutKey);
	}

	/**
	* Sets the menu object or the menu object properties.
	* If the value is an object, it must have a property named items.
	* If the value is an array it is an array of CMenu properties that are merged with {@link $menuProperties}
	* @param mixed object: Menu object; array: name=>value property pairs for CMenu
	*/
	public function setMenu($value) {
		if (is_array($value))
			$this->menuProperties = array_merge($this->menuProperties, $value);
		elseif (is_object($value))
			$this->_menu = $value;
	}

	/**
	* @return CMenu
	*/
	public function getMenu() {
		if (null===$this->_menu) {
			$properties = $this->menuProperties;
			unset($properties['previousItemCssClass']);
			$this->_menu = $this->getOwner()->createWidget('zii.widgets.CMenu', $properties);
		}
		$this->generateMenuItems();
		return $this->_menu;
	}

	private function generateMenuItems() {
		$previous = true;
		$items = array();
		$url = array($this->owner->id.'/'.$this->getOwner()->getAction()->getId());

		// We should not have a url for later steps
		// We should not have a url for earlier steps if forwards only
		foreach ($this->_steps as $step) {
			$item = array();
			$item['label'] = $this->getStepLabel($step);
			if (($previous && !$this->forwardOnly) || ($step===$this->_currentStep)) {
				$item['url'] = $url + array($this->queryParam=>$step);
				if ($step===$this->_currentStep)
					$previous = false;
			}
			$item['active'] = $step===$this->_currentStep;
			if ($previous && !empty($this->menuProperties['previousItemCssClass']))
				$item['itemOptions'] = array('class'=>$this->menuProperties['previousItemCssClass']);

			$items[] = $item;
		}
		if (!empty($this->menuLastItem))
			$items[] = array(
				'label'=>$this->menuLastItem,
				'active'=>false
			);
		$this->_menu->items = $items;
	}

	/**
	* Returns a value indicating if the step has expired
	* @return boolean True if the step has expired, false if not
	*/
	protected function hasExpired() {
		return isset($this->_session[$this->_timeoutKey]) && $this->_session[$this->_timeoutKey]<time();
	}

	/**
	* Moves the wizard to the next step
	* If autoAdvance===true this will be the expectedStep,
	* if autoAdvance===false this will be the next step in the steps array
	*/
	protected function nextStep() {
		if ($this->autoAdvance)
			$step = $this->getExpectedStep();
		else {
			$index = $this->_steps->indexOf($this->_currentStep)+1;
			$step = ($index<$this->_steps->count()?$this->_steps[$index]:null);
		}

		if ($this->timeout)
			$this->_session[$this->_timeoutKey] = time() + $this->timeout;

		$this->redirect($step);
	}

	/**
	* Moves the wizard to the previous step
	*/
	protected function previousStep() {
		$index = $this->_steps->indexOf($this->_currentStep)-1;
		$this->redirect($this->_steps[($index>0?$index:0)]);
	}

	/**
	* Returns a value indicating if the wizard has started
	* @return boolean True if the wizard has started, false if not
	*/
	protected function hasStarted() {
		return isset($this->_session[$this->_stepsKey]);
	}

	/**
	* Returns a value indicating if the wizard has completed
	* @return boolean True if the wizard has completed, false if not
	*/
	protected function hasCompleted() {
		return !(bool)$this->getExpectedStep();
	}

	/**
	* Handles Wizard redirection. A null url will redirect to the "expected" step.
	* @param string Step to redirect to.
	* @param boolean If true, the application terminates after the redirect
	* @param integer HTTP status code (eg: 404)
	* @see CController::redirect()
	*/
	protected function redirect($step = null, $terminate=true, $statusCode=302) {
		if (!is_string($step))
			$step = $this->getExpectedStep();
		$url = array($this->owner->id.'/'.$this->getOwner()->getAction()->getId(), $this->queryParam=>$step);
		$this->owner->redirect($url, $terminate, $statusCode);
	}

	/**
	* Selects, skips, or deselects a branch or branches.
	* @param mixed Branch directives.
	* string: The branch name or a list of branch names to select
	* array: either an array of branch names to select or
	* an array of "branch name"=>branchDirective pairs
	* branchDirective = [self::BRANCH_SELECT|self::BRANCH_SKIP|self::BRANCH_DESELECT|]
	*/
	public function branch($branchDirectives) {
		if (is_string($branchDirectives)) {
			if (strpos($branchDirectives,',')) {
				$branchDirectives = explode(',',$branchDirectives);
				foreach ($branchDirectives as &$name) {
					$name = trim($name);
				}
			}
			else
				$branchDirectives = array($branchDirectives);
		}

		$branches = $this->branches();

		foreach ($branchDirectives as $name=>$directive) {
			if ($directive===self::BRANCH_DESELECT)
				$branches->remove($name);
			else {
				if (is_int($name)) {
					$name = $directive;
					$directive = self::BRANCH_SELECT;
				}
				$branches->add($name,$directive);
			}
		}
		$this->_session[$this->_branchKey] = $branches;
		$this->parseSteps();
	}

	/**
	* Returns a map of the current branch directives
	* @return CMap A map of the current branch directives
	*/
	private function branches() {
		return (isset($this->_session[$this->_branchKey])?
			$this->_session[$this->_branchKey]:new CMap());
	}

	/**
	* Validates the $step in two ways:
	*   1. Validates that the step exists in $this->_steps array.
	*   2. Validates that the step is the expected step or,
	*      if forwardsOnly==false, before it.
	* @param string Step to validate.
	* @return boolean Whether the step is valid; true if the step is valid,
	* false if not
	*/
	protected function isValidStep($step) {
		$index = $this->_steps->indexOf($step);
		if ($index>=0) {
			if ($this->forwardOnly)
				return $index===$this->_steps->indexOf($this->getExpectedStep());
			return $index <= $this->_steps->indexOf($this->getExpectedStep());
		}
		return false;
	}

	/**
	* Returns the first unprocessed step (i.e. step data not saved in Session).
	* @return string The first unprocessed step; null if all steps have been
	* processed
	*/
	protected function getExpectedStep() {
		$steps = $this->_session[$this->_stepsKey];
		if (!is_null($steps)) {
			foreach ($this->_steps->toArray() as $step) {
				if (!isset($steps[$step]))
					return $step;
			}
		}
	}

	/**
	* Parse the steps into a flat array and get their labels
	*/
	protected function parseSteps() {
		$this->_steps = $this->_parseSteps($this->steps);
		$this->_stepLabels = new CMap(array_flip($this->_steps),true);
		$this->_steps = new CList($this->_steps,true);
	}

	/**
	* Parses the steps array into a "flat" array by resolving branches.
	* Branches are resolved according the setting
	* @param array The steps array.
	* @return array Steps to take
	*/
	private function _parseSteps($steps) {
		$parsed = array();

		foreach ($steps as $label=>$step) {
			$branch = '';
			if (is_array($step)) {
				foreach (array_keys($step) as $branchName) {
					$branchDirective = $this->branchDirective($branchName);
					if (
						($branchDirective && $branchDirective===self::BRANCH_SELECT) ||
						(empty($branch) && $this->defaultBranch)
					)
						$branch = $branchName;
				}

				if (!empty($branch)) {
					if (is_array($step[$branch]))
						$parsed = array_merge($parsed, $this->_parseSteps($step[$branch]));
					else
						$parsed[$label] = $step[$branch];
				}
			}
			else
				$parsed[$label] = $step;
		}
		return $parsed;
	}

	/**
	* Returns the branch directive.
	* @return string the branch directive or NULL if no directive for the branch
	*/
	private function branchDirective($branch) {
		return (isset($this->_session[$this->_branchKey])?
			$this->_session[$this->_branchKey][$branch]:null
		);
	}

	/**
	* Raises the onStarted event.
	* The event handler must set the event::handled property TRUE for the wizard
	* to process steps.
	*/
	protected function start() {
		$event = new WizardEvent($this);
		$this->onStart($event);
		if ($event->handled)
			$this->_session[$this->_stepsKey] = new CMap();
		return $event->handled;
	}
	/**
	* Raises the onCancelled event.
	* The event::data property contains data for processed steps.
	*/
	protected function cancelled($step) {
		$event = new WizardEvent($this,$step,$this->read());
		$this->onCancelled($event);
		$this->reset();
		$this->owner->redirect($this->cancelledUrl);
	}

	/**
	* Raises the onExpired event.
	*/
	protected function expired($step) {
		$event = new WizardEvent($this,$step);
		$this->onExpiredStep($event);
		if ($this->continueOnExpired)
			return true;
		$this->reset();
		$this->owner->redirect($this->expiredUrl);
	}

	/**
	* Raises the onFinished event.
	* The event::data property contains data for processed steps.
	*/
	protected function finished($step) {
		$event = new WizardEvent($this,$step,$this->read());
		$this->onFinished($event);
		$this->reset();
		$this->owner->redirect($this->finishedUrl);
	}

	/**
	* Raises the onInvalidStep event.
	*/
	protected function invalidStep($step) {
		$event = new WizardEvent($this,$step);
		$this->onInvalidStep($event);
		$this->redirect();
	}

	/**
	* Raises the onProcessStep event.
	* The event::data property contains the current data for the step.
	* The event handler must set the event::handled property TRUE for the wizard
	* to move to the next step.
	*/
	protected function processStep() {
		$event = new WizardEvent($this,$this->_currentStep,$this->read($this->_currentStep));
		$this->onProcessStep($event);
		if ($event->handled && $this->hasExpired())
			$this->expired($this->_currentStep);
		return $event->handled;
	}

	/**
	* Resets the wizard by deleting the wizard session.
	*/
	public function resetWizard($step) {
		$this->reset();
		$event = new WizardEvent($this,$step);
		$this->onReset($event);
	}

	/**
	* Raises the onSaveDraft event.
	* The event::data property contains the data to save.
	*/
	protected function saveDraft($step) {
		$event = new WizardEvent($this, $step, array(
			$this->read(),
			$this->branches()->toArray(),
			$this->_session[$this->_timeoutKey]
		));
		$this->onSaveDraft($event);
		$this->reset();
		$this->owner->redirect($this->draftSavedUrl);
	}

	public function onCancelled($event) {
		$this->raiseEvent('onCancelled', $event);
	}

	public function onExpiredStep($event) {
		$this->raiseEvent('onExpiredStep', $event);
	}

	public function onFinished($event) {
		$this->raiseEvent('onFinished', $event);
	}

	public function onInvalidStep($event) {
		$this->raiseEvent('onInvalidStep', $event);
	}

	public function onProcessStep($event) {
		$this->raiseEvent('onProcessStep', $event);
	}

	public function onReset($event) {
		$this->raiseEvent('onReset', $event);
	}

	public function onSaveDraft($event) {
		$this->raiseEvent('onSaveDraft', $event);
	}

	public function onStart($event) {
		$this->raiseEvent('onStart', $event);
	}
}

/**
* Wizard event class.
* This is the event raised by the wizard.
*/
class WizardEvent extends CEvent {
	private $data=array();
	private $step;

	public function __construct($sender, $step=null, $data=null) {
		parent::__construct($sender);
		$this->step = $step;
		$this->data = $data;
	}

	public function getData() {
		return $this->data;
	}

	public function getStep() {
		return $this->step;
	}
}
