<?php
App::uses('AppController', 'Controller');
/**
 * Log Controller
 *
 */
class LogsController extends AppController {

    public $paginate = array(
    	'limit' 	=> 10,
    	'contain' 	=> false,
        'order' 	=> array("Log.id" => "DESC")
    );

    public function beforeFilter() {
        parent::beforeFilter();
    }

/**
 * index method
 *
 * @return void
 */
    public function admin_index() {

    }

/**
 * view function
 * @param string $type
 * @param string $startDate
 * @param string $endDate
 */
    public function admin_view($type = "USER", $displayType = null, $level = "ALL", $startDate = null, $endDate = null){

    	$logTypes = array(
    		'USER',
    		Configure::read('Config.type.emailmarketing'),
    		Configure::read('Config.type.taskmanagement'),
    		Configure::read('Config.type.webdevelopment'),
    		Configure::read('Config.type.livechat'),
    		Configure::read('Config.type.payment')
    	);

    	if(!in_array($type, $logTypes)){

    		throw new FatalErrorException ( __('Tried to access invalid logs (Passed Log Type: ' .$type .')'), 500, ROOT . DS . APP_DIR . DS ."Controller" .DS .$this->name ."Controller.php", 45 );
    	}

    	if(empty($displayType) && stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    		$displayType = 'PREMIUM';
    	}

    	$this->paginate['conditions'] = $this->__queryConditions($type, $level, $startDate, $endDate);

    	$this->Paginator->settings = $this->paginate;

    	$this->set(compact('type', 'startDate', 'endDate', 'level', 'displayType'));

    	// This is for Datatable view
    	$this->DataTable->mDataProp = true;
    	$this->set('response', $this->DataTable->getResponse());
    	$this->set('_serialize','response');
    	$this->set('defaultSortDir', $this->paginate['order']['Log.id']);

    	// This is for timeline helper
    	$logs = $this->paginate('Log');
    	$this->set('logs', $logs);

    }

/**
 * generate query condition function
 * @param string $type
 * @param string $startDate
 * @param string $endDate
 * @return multitype:multitype:string  string
 */
    private function __queryConditions($type = 'USER', $level = "ALL", $startDate = null, $endDate = null){
    	$conditions = array();
    	if($startDate != null && strtotime($startDate) && $endDate != null && strtotime($endDate)){
    		$conditions['Log.timestamp BETWEEN ? AND ?'] = array("{$startDate} 00:00:00", "{$endDate} 23:59:59");
    	}

    	$conditions['Log.type'] = strtoupper($type);

    	if(!empty($level) && $level != "ALL"){
    		$conditions['Log.level'] = strtoupper($level);
    	}

    	// Check user permission
    	$clientId = null;
    	if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    		foreach($this->superUserAssociatedUsers as $associatedUser){
    			if(empty($associatedUser['User']['active'])){
    				// Only active account can be used to check permission
    				continue;
    			}
    			switch($type){
    				case Configure::read('Config.type.emailmarketing'):
    					if($associatedUser['User']['group_id'] == Configure::read('EmailMarketing.client.group.id')){
    						$clientId = $associatedUser['User']['id'];
    					}
    					break;
    				case Configure::read('Config.type.taskmanagement'):
    					if($associatedUser['User']['group_id'] == Configure::read('WebDevelopment.client.group.id')){
    						$clientId = $associatedUser['User']['id'];
    					}
    					break;
    				case Configure::read('Config.type.webdevelopment'):
    				case Configure::read('Config.type.payment'):
    				case "USER":
    				default:
    					$clientId = $this->superUserId;
    					break;
    			}
    			if(!empty($clientId)){
    				break;
    			}
    		}
    		if(!empty($clientId)){
    			$conditions['Log.user_id'] = $clientId;
    		}else{

    			throw new FatalErrorException(__('Cannot find client ID when viewing logs. View log type: ' .$type), 500, ROOT . DS . APP_DIR . DS ."Controller" .DS .$this->name ."Controller.php", 121);
    		}
    	}else{
    		$conditions['Log.user_id'] = $this->superUserId;
    	}

    	return $conditions;
    }
}