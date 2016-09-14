<?php
class DBHelper{
  private $_db, $_smessage, $_emessage, $_error = false;
  
  public function __construct(){
    $this->_db = DB::getInstance();
  }
  
  public function startTables(){
    $this->_error = false;
    $tables = array(
    	"CREATE TABLE `cat` (`id` INT(11) NOT NULL AUTO_INCREMENT,`name` TEXT NOT NULL,`description` TEXT NOT NULL,`order` INT(11) NULL DEFAULT NULL,`parent` INT(11) NULL DEFAULT NULL,`news` INT(11) NOT NULL DEFAULT '0',PRIMARY KEY (`id`)) COLLATE='latin1_swedish_ci' ENGINE=InnoDB",
    	"CREATE TABLE `groups` (`id` INT(11) NOT NULL AUTO_INCREMENT,`group_name` TEXT NOT NULL,`permissions` TEXT NOT NULL,PRIMARY KEY (`id`)) COLLATE='latin1_swedish_ci' ENGINE=InnoDB",
    	"CREATE TABLE `post` (`id` INT(11) NOT NULL AUTO_INCREMENT,`post_title` VARCHAR(50) NOT NULL,`cat_id` INT(11) NOT NULL DEFAULT '1',`post_cont` LONGTEXT NOT NULL,`post_user` INT(11) NOT NULL,`post_date` DATE NOT NULL, PRIMARY KEY (`id`)) COLLATE='latin1_swedish_ci' ENGINE=InnoDB",
      	"CREATE TABLE `reply` (`id` INT(11) NOT NULL AUTO_INCREMENT,`post_id` INT(11) NOT NULL,`title` TEXT NOT NULL,`content` LONGTEXT NOT NULL,`user_id` INT(11) NOT NULL,`date` DATETIME NOT NULL,PRIMARY KEY (`id`)) COLLATE='latin1_swedish_ci' ENGINE=InnoDB",
    	"CREATE TABLE `settings` (`id` INT(11) NOT NULL AUTO_INCREMENT,`name` TEXT NOT NULL,`value` TEXT NULL, PRIMARY KEY (`id`)) COLLATE='latin1_swedish_ci' ENGINE=InnoDB",
    	"CREATE TABLE `users` (`id` INT(11) NOT NULL AUTO_INCREMENT,`username` VARCHAR(50) NOT NULL,`password` LONGTEXT NOT NULL,`salt` LONGTEXT NOT NULL,`name` VARCHAR(50) NOT NULL,`email` TEXT NOT NULL,`group` INT(11) NOT NULL,`joined` DATETIME NOT NULL,`private` INT(11) NULL DEFAULT '0',`signature` MEDIUMTEXT NULL DEFAULT NULL,PRIMARY KEY (`id`)) COLLATE='latin1_swedish_ci' ENGINE=InnoDB",
    	"CREATE TABLE `user_session` (`id` INT(11) NOT NULL AUTO_INCREMENT,`user_id` INT(11) NOT NULL,`hash` LONGTEXT NOT NULL,PRIMARY KEY (`id`)) COLLATE='latin1_swedish_ci' ENGINE=InnoDB",
    	"CREATE TABLE `notification` (`id` BIGINT(255) NOT NULL AUTO_INCREMENT, `user` INT(255) NOT NULL, `message` MEDIUMTEXT NULL, `read` INT(11) NULL DEFAULT '0', PRIMARY KEY (`id`)) COLLATE='latin1_swedish_ci' ENGINE=InnoDB",
    	"CREATE TABLE `Friends` (`id` INT(11) NOT NULL AUTO_INCREMENT,`user_id` INT(11) NOT NULL DEFAULT '0',`user_id2` INT(11) NOT NULL DEFAULT '0', `accept` INT(11) NULL DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB",
    	//'INSERT INTO `groups` (`group_name`, `permissions`) VALUES (`Guest`, `{"Post":1, "Admin":0,"Mod":0}`)',
    	//'INSERT INTO `groups` (`group_name`, `permissions`) VALUES (`Mod`, `{"Post":1, "Admin":0,"Mod":1}`)',
      	//'INSERT INTO `groups` (`group_name`, `permissions`) VALUES (`Administrator`, `{"Post":1, "Admin":1,"Mod":1}`)',
    );
    $i = 0;
    foreach($tables as $table){
      $q = $this->_db->query($table)->count();
      if(!$q){
        $this->addMessage('Query index '.$i.' has been added');
      }else{
        $this->addError('There was an error while adding table at index '.$i);
      }
      $i++;
    }
    
    $insert = array(
    		0 =>array(
    		0=> array(
    			'table'=>'groups',
    			'group_name' => 'Register',
    			'permissions'=>'{"Post":1, "Admin":0,"Mod":0}',
    		),
    		),
    		1=> array(
    		1 => array(
    			'table'=>'groups',
    			'group_name'=> 'Mod',
    			'permissions'=> '{"Post":1, "Admin":0,"Mod":1}',
    		),
    		),
    		2=>array(
    		2 => array(
    			'table' => 'groups',
    			'group_name'=> 'Admin',
    			'permissions'=>'{"Post":1, "Admin":1,"Mod":1}',
    		),
    		),
    		3 => array(
    		3 => array(
    			'table'=>'settings',
    			'name'=>'install',
    			'value' => '1',
    		),
    		),
    		4 => array(
    		4 => array(
    			'table'=>'settings',
    			'name' =>'title',
    			'value' => 'PHP-Foro',
    		),
    		),
    		5 => array(
    		5 => array(
    			'table'=>'settings',
    			'name'=> 'bootstrap-theme',
    			'value'=>'cosmo',	
    		),
    		),
    		6 => array(
    		6 => array(
    			'table'=>'settings',
    			'name'=> 'motd',
    			'value'=>'',
    		),
    		),
    		7 => array(
    		7 => array(
    			'table'=>'settings',
    			'name'=> 'debug',
    			'value'=>'Off',
    		),
    		),
    );
    
    $j = 0;
    foreach ($insert as $ins){
    	if(!$this->_db->insert($ins[$j]['table'], array(
    			array_keys($ins[$j])[1]=> array_values($ins[$j])[1],
    			array_keys($ins[$j])[2]=> array_values($ins[$j])[2],
    	))){
    		$this->addMessage('There was an error adding row: '.$j);
    	}else{
    		echo $j.' row added!<br/>';
    	}
    	$j++;
    }
    
    if(!empty($this->_emessage)){
      $this->_error = true;
    }
    
    return $this;
  }
  
  private function addError($string = ''){
    $this->_emessage[] = $string;
  }
  private function addMessage($string = ''){
    $this->_smessage[] = $string;
  }
  public function getError(){
    return $this->_emessage;
  }
  public function getMessage(){
    return $this->_smessage;
  }
  public function hasError(){
    return ($this->_error)? true:false;
  }
}