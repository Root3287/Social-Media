// Recreated from http://code.tutsplus.com/tutorials/how-to-paginate-data-with-php--net-2928
// Thank you so much!
<?php
class Pagination{
	private $_db, $_limit, $_page, $_query, $_total;
	
	public function __construct($query){
		$this->_db = DB::getInstance();
		$this->_query = $query;
		$rs = $this->_db->get($this->_query);
		$this->_total = $rs->count();
	}

	public function getData($limit = 10, $page = 1){
		$this->_limit = $limit;
		$this->_page = $page;	
		
		if($this->_limit == -1){ // Everything
			$query = $this->_query;
		}else{
			$query = $this->_query." LIMIT ".$this->_page-1*$this->_limit.", $this->_limit";
		}
		$rs = $this->_db->query($query)->results();
		$res = [];
		foreach($rs as $r){
			$res[] = $r;
		}
		$result = new stdClass();
		$result->page = $this->_page;
		$result->limit = $this->_limit;
		$result->total = $this->_total;
		$result->data = $res;
		
		return $result;
	}
	
	public function createLinks($links, $list_class){
		if($this->_limit == -1){
			return '';
		}
		$last = ceil($this->_total / $this->_limit);
		$start = ($this->_page - $links > 0) ? $this->_page - $links : 1;
		$end = ($this->_page + $links < $last)? $this->_page+$links:$last;

		if($start > 1){
		// Create Links
		}
		for($i = $start; $i <= $end; $i++){
		//Create Links
		}
		if($end < $last){
		//create links
		}
		return $html;
	}
}
/* At the top of the page
    $limit      = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 25;
    $page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
    $links      = ( isset( $_GET['links'] ) ) ? $_GET['links'] : 7;
    $query      = "";
 
    $Paginator  = new Paginator($query );
 
    $results    = $Paginator->getData( $page, $limit );

*/
/* Display Results
<?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>
        <tr>
                <td><?php echo $results->data[$i]['Name']; ?></td>
                <td><?php echo $results->data[$i]['Country']; ?></td>
                <td><?php echo $results->data[$i]['Continent']; ?></td>
                <td><?php echo $results->data[$i]['Region']; ?></td>
        </tr>
<?php endfor; ?>
*/
