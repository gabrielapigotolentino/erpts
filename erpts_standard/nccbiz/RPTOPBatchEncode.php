<?php
# Setup PHPLIB in storey Area
include_once("web/prepend.php");
include_once("assessor/OwnerRecords.php");

//*
$server = new SoapServer("urn:Object");
$server->setClass('RPTOPBatchEncode');
$server->handle();
//*/
class RPTOPBatchEncode
{
    function RPTOPBatchEncode(){
		
    }
    
	function getOwnerNames(){
		$ownerRecords = new OwnerRecords;
		if($ownerNames = $ownerRecords->getOwnerNames()){
			return $ownerNames;
		}
		else{
			return false;
		}
	}	
}

?>