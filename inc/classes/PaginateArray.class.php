<?php
// Recreated from http://code.tutsplus.com/tutorials/how-to-paginate-data-with-php--net-2928
// Thank you so much!
class PaginateArray{
	private $_limit, $_page, $_array, $_total;
	
	public function __construct($array = array()){
		$this->_array = $array;
		$this->_total = count($array);
	}

	public function getData($limit = 10, $page = 1){
		$this->_limit = $limit;
		$this->_page = $page;	
		$index = ($page*$limit)-1;

		if($this->_limit == -1){ // Everything
			$finalArray = $this->_array;
		}else{
			$finalArray = array_slice($this->_array, $index, $limit);
		}
		$res = [];
		foreach($finalArray as $r){
			$res[] = $r;
		}
		$result = new stdClass();
		$result->page = $this->_page;
		$result->limit = $this->_limit;
		$result->total = $this->_total;
		$result->data = $res;
		
		return $result;
	}
	public function getArrayData($limit = 10, $page = 0){
		$this->_limit = $limit;
		$this->_page = $page;	
		
		$start = ($this->_page - 1)*$limit;
		$finish = $start+$limit;
		
		if($this->_limit == -1){ // Everything
			$finalArray = $this->_array;
		}else{
			$finalArray = array_slice($this->_array, $start, $finish);
		}

		$res = [];
		foreach($finalArray as $r){
			$res[] = $r;
		}
		return $res;
	}
	public function getTotalPages(){
		return (ceil($this->_total/$this->_limit)<=10)? ceil($this->_total/$this->_limit):10;
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
