<?php
class RPTOPRecords
{
	var $arrayList;
	var $domDocument;
	var $db;
	
	function RPTOPRecords(){
	
	}
	
	function setDB(){
		$this->db = new DB_RPTS;
	}
	
	function setArrayList($tempVar){
		$this->arrayList[] = $tempVar;
	}
	
	function getArrayList(){
		return $this->arrayList;
	}
	
	function getDomDocument(){
		return $this->domDocument;
	}
	
	function appendToDomList($rootNode,$childNode){
		//$rootNode->append_child($childNode->document_element());

		// test clone_node()
		$nodeTmp = $childNode->document_element();
		$nodeClone = $nodeTmp->clone_node(true);
		$rootNode->append_child($nodeClone);
	}
		
	function setDomDocument(){
		$this->domDocument = domxml_new_doc("1.0");
		$domList = $this->domDocument->create_element("RPTOPList");
		$domList = $this->domDocument->append_child($domList);
		foreach($this->arrayList as $key => $value){
			$domDocument = $value->getDomDocument();
			$this->appendToDomList($domList,$domDocument);
		}
		return true;
	}
	
	///*
	function parseDomDocument($domDoc){
		$baseNode = $domDoc->document_element();
		if ($baseNode->has_child_nodes()){
			$child = $baseNode->first_child();
			while ($child){
				//if ($child->tagname=="RPTOP") {
				if ($child->tagname) {
					$tempXmlStr = $domDoc->dump_node($child);
					$tempDomDoc = domxml_open_mem($tempXmlStr);
					$rptop = new RPTOP;
					$rptop->parseDomDocument($tempDomDoc);
					$this->setArrayList($rptop);
				}
				$child = $child->next_sibling();
			}
		}
		$this->setDomDocument();
		return true;
	}//*/
	
	function selectRecords($condition="",$sortKey="rptopID desc"){
		if($condition!=""){
		// use $condition

        $sql = "SELECT DISTINCT " . RPTOP_TABLE . ".rptopID as rptopID 
			    , CONCAT(".PERSON_TABLE.".lastName,".PERSON_TABLE.".firstName,".PERSON_TABLE.".middleName) as PersonFullName
                FROM " . RPTOP_TABLE . "
				LEFT JOIN " . OWNER_TABLE . "
				ON " . RPTOP_TABLE . ".rptopID = " . OWNER_TABLE . ".rptopID
                LEFT JOIN " . OWNER_COMPANY_TABLE . "
                ON " . OWNER_TABLE . ".ownerID = " . OWNER_COMPANY_TABLE . ".ownerID
                LEFT JOIN " . COMPANY_TABLE . "
                ON " . OWNER_COMPANY_TABLE . ".companyID = " . COMPANY_TABLE . ".companyID
                LEFT JOIN " . OWNER_PERSON_TABLE . "
                ON " . OWNER_TABLE . ".ownerID = " . OWNER_PERSON_TABLE . ".ownerID
                LEFT JOIN " . PERSON_TABLE . "
                ON " . OWNER_PERSON_TABLE . ".personID = " . PERSON_TABLE . ".personID
                LEFT JOIN " . RPTOPTD_TABLE . "
                ON " . RPTOP_TABLE . ".rptopID = " . RPTOPTD_TABLE . ".rptopID
                LEFT JOIN " . TD_TABLE . "
                ON " . RPTOPTD_TABLE . ".tdID = " . TD_TABLE . ".tdID ";

		$sql = $sql . $condition;
		
			
		}
		else{
		// use $sortKey
			$sql = sprintf("select * from %s order by $sortKey %s;",
					RPTOP_TABLE, $condition);
		}
		$this->setDB();

		//$dummySql = sprintf("INSERT INTO dummySQL(queryString) VALUES('%s');",fixQuotes($sql));
		//$this->db->query($dummySql);

		$this->db->query($sql);

		while ($this->db->next_record()) {
			$tmpArray[] = $this->db->f("rptopID");
		}
		$idArray = array_unique($tmpArray);

		foreach($idArray as $key => $rptopID){
			$rptop = new RPTOP;
			$rptop->selectRecord($rptopID);
			$this->arrayList[] = $rptop;
		}

		if(count($this->arrayList) > 0){
			$this->setDomDocument();
			return true;
		}
		else {
			return false;
		}
	}
	
	function searchRecords($searchKey,$fields,$limit){
		$condition = "where (";
		foreach($fields as $key => $value){
			if($key == 0) $condition = $condition.$value." like '%".$searchKey."%'";
			else $condition = $condition." or ".$value." like '%".$searchKey."%' ";
		}

        $sql = "SELECT DISTINCT " . RPTOP_TABLE . ".rptopID as rptopID 
                FROM " . RPTOP_TABLE . "
				LEFT JOIN " . OWNER_TABLE . "
				ON " . RPTOP_TABLE . ".rptopID = " . OWNER_TABLE . ".rptopID
                LEFT JOIN " . OWNER_COMPANY_TABLE . "
                ON " . OWNER_TABLE . ".ownerID = " . OWNER_COMPANY_TABLE . ".ownerID
                LEFT JOIN " . COMPANY_TABLE . "
                ON " . OWNER_COMPANY_TABLE . ".companyID = " . COMPANY_TABLE . ".companyID
                LEFT JOIN " . OWNER_PERSON_TABLE . "
                ON " . OWNER_TABLE . ".ownerID = " . OWNER_PERSON_TABLE . ".ownerID
                LEFT JOIN " . PERSON_TABLE . "
                ON " . OWNER_PERSON_TABLE . ".personID = " . PERSON_TABLE . ".personID
                LEFT JOIN " . RPTOPTD_TABLE . "
                ON " . RPTOP_TABLE . ".rptopID = " . RPTOPTD_TABLE . ".rptopID
                LEFT JOIN " . TD_TABLE . "
                ON " . RPTOPTD_TABLE . ".tdID = " . TD_TABLE . ".tdID ";

        $sql = $sql . $condition . ") " . $limit;        	
		
		$this->setDB();

		$dummySql = sprintf("INSERT INTO dummySQL(queryString) VALUES('%s');",fixQuotes($sql));
		$this->db->query($dummySql);

		$this->db->query($sql);

		while ($this->db->next_record()) {
			$tmpArray[] = $this->db->f("rptopID");
		}
		$idArray = array_unique($tmpArray);

		foreach($idArray as $key => $rptopID){
			$rptop = new RPTOP;
			$rptop->selectRecord($rptopID);
			$this->arrayList[] = $rptop;
		}

		if(count($this->arrayList) > 0){
			$this->setDomDocument();
			return true;
		}
		else {
			return false;
		}
	}
	
	function countRecords($condition=""){
		$sql = sprintf("select count(*) as count from %s %s;",
				RPTOP_TABLE, $condition);
		$this->setDB();
		$this->db->query($sql);
		if($this->db->next_record()) $ret = $this->db->f("count");
		else $ret = false;
		return $ret;
	}
	
	function countSearchRecords($searchKey,$fields){
		$condition = "where (";
		foreach($fields as $key => $value){
			if($key == 0) $condition = $condition.$value." like '%".$searchKey."%'";
			else $condition = $condition." or ".$value." like '%".$searchKey."%' ";
		}

        $sql = "SELECT DISTINCT COUNT(" . RPTOP_TABLE . ".rptopID) as count 
                FROM " . RPTOP_TABLE . "
				LEFT JOIN " . OWNER_TABLE . "
				ON " . RPTOP_TABLE . ".rptopID = " . OWNER_TABLE . ".rptopID
                LEFT JOIN " . OWNER_COMPANY_TABLE . "
                ON " . OWNER_TABLE . ".ownerID = " . OWNER_COMPANY_TABLE . ".ownerID
                LEFT JOIN " . COMPANY_TABLE . "
                ON " . OWNER_COMPANY_TABLE . ".companyID = " . COMPANY_TABLE . ".companyID
                LEFT JOIN " . OWNER_PERSON_TABLE . "
                ON " . OWNER_TABLE . ".ownerID = " . OWNER_PERSON_TABLE . ".ownerID
                LEFT JOIN " . PERSON_TABLE . "
                ON " . OWNER_PERSON_TABLE . ".personID = " . PERSON_TABLE . ".personID
                LEFT JOIN " . RPTOPTD_TABLE . "
                ON " . RPTOP_TABLE . ".rptopID = " . RPTOPTD_TABLE . ".rptopID
                LEFT JOIN " . TD_TABLE . "
                ON " . RPTOPTD_TABLE . ".tdID = " . TD_TABLE . ".tdID ";

        $sql = $sql . $condition . ")" . $limit;
		
		$this->setDB();

		$dummySql = sprintf("INSERT INTO dummySQL(queryString) VALUES('%s');",fixQuotes($sql));
		$this->db->query($dummySql);

		$this->db->query($sql);
		if($this->db->next_record()) $ret = $this->db->f("count");
		else $ret = false;
		return $ret;
	}
	
	function deleteRecords($rptopIDArray=""){
		$rptop = new RPTOP;
		$rows = 0;
		foreach ($rptopIDArray as $key => $value){
			if ($rptop->deleteRecord($value)) $rows++;
		}
		return $rows;
	}
}
?>
