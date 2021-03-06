<?php
# Setup PHPLIB in this Area
include_once("web/prepend.php");
include_once("assessor/Address.php");
include_once("assessor/Person.php");
include_once("assessor/PersonRecords.php");
include_once("assessor/Company.php");
include_once("assessor/CompanyRecords.php");
include_once("assessor/Owner.php");
include_once("assessor/OwnerRecords.php");
include_once("assessor/ODHistoryRecords.php");
include_once("assessor/TD.php");
include_once("assessor/TDRecords.php");
include_once("assessor/RPTOP.php");
include_once("assessor/RPTOPRecords.php");
include_once("assessor/AFS.php");

class RPTOPBatchRecords
{
	var $db;
	
	function RPTOPBatchRecords(){
	
	}
		
	function setDB(){
		$this->db = new DB_RPTS;
	}
	
	// use the following functions for RPTOPBatchEncode functions
	// added March 02, 2004

	// bypasses xml for faster processing

	// all functions co-relate to one another in some way or the other
	// use: setOwnerRPTOP() to create an RPTOP from a given $ownerName array
	//      which can be generated from getOwnerNameArray() and other rptop field parameters


	function insertOwnerPerson($ownerID,$personID){
		if($ownerID=="") return false;
		if($personID=="") return false;

		$db = new DB_RPTS;
		$sql = sprintf("INSERT INTO %s(".
			"ownerID".
			",personID".
			") VALUES(".
			"'%s','%s');"
			,OWNER_PERSON_TABLE
			,fixQuotes($ownerID)
			,fixQuotes($personID));

		$db->beginTransaction();
		$db->query($sql);
		$ownerPersonID = $db->insert_id();
		if($db->Errno!=0) {
			$db->rollbackTransaction();
			$db->resetErrors();
			return false;
		}
		else{
			$db->endTransaction();
			return $ownerPersonID;
		}
	}

	function insertOwnerCompany($ownerID,$companyID){
		if($ownerID=="") return false;
		if($companyID=="") return false;

		$db = new DB_RPTS;
		$sql = sprintf("INSERT INTO %s(".
			"ownerID".
			",companyID".
			") VALUES(".
			"'%s','%s');"
			,OWNER_COMPANY_TABLE
			,fixQuotes($ownerID)
			,fixQuotes($companyID));

		$db->beginTransaction();
		$db->query($sql);
		$ownerCompanyID = $db->insert_id();
		if($db->Errno!=0) {
			$db->rollbackTransaction();
			$db->resetErrors();
			return false;
		}
		else{
			$db->endTransaction();
			return $ownerCompanyID;
		}
	}

	function insertOwnerRecord($rptopID){
		if($rptopID=="") return false;

		$db = new DB_RPTS;
		$sql = sprintf("INSERT INTO %s(".
			"rptopID".
			") VALUES(".
			"'%s');"
			,OWNER_TABLE
			,fixQuotes($rptopID));

		$db->beginTransaction();
		$db->query($sql);
		$ownerID = $db->insert_id();
		if($db->Errno!=0) {
			$db->rollbackTransaction();
			$db->resetErrors();
			return false;
		}
		else{
			$db->endTransaction();
			return $ownerID;
		}
	}

	function insertRPTOPTDRecord($rptopID,$tdID){
		if($rptopID=="") return false;
		if($tdID=="") return false;

		$db = new DB_RPTS;
		$sql = sprintf("INSERT INTO %s(".
			"rptopID".
			", tdID".
			") VALUES(".
			"'%s','%s');"
			,RPTOPTD_TABLE
			,fixQuotes($rptopID)
			,fixQuotes($tdID));

		$db->beginTransaction();
		$db->query($sql);
		$rptoptdID = $db->insert_id();
		if($db->Errno!=0) {
			$db->rollbackTransaction();
			$db->resetErrors();
			return false;
		}
		else{
			$db->endTransaction();
			return $rptoptdID;
		}
	}

	function insertRPTOPRecord($rptop){
		if(is_array($rptop)){
			$db = new DB_RPTS;
			$sql = sprintf("INSERT INTO %s(".
				"rptopNumber".
				", rptopDate".
				", taxableYear".
				", cityTreasurer".
				", cityAssessor".
				", landTotalMarketValue".
				", landTotalAssessedValue".
				", plantTotalMarketValue".
				", plantTotalAssessedValue".
				", bldgTotalMarketValue".
				", bldgTotalAssessedValue".
				", machTotalMarketValue".
				", machTotalAssessedValue".
				", totalMarketValue".
				", totalAssessedValue".
				", dateCreated".
				", createdBy".
				", dateModified".
				", modifiedBy".
				") VALUES(".
				"'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'".
				",'%s','%s','%s','%s','%s','%s','%s','%s','%s');"
				,RPTOP_TABLE
				,fixQuotes($rptop["rptopNumber"])
				,fixQuotes(date("Y-m-d"),strtotime("now"))
				,fixQuotes($rptop["taxableYear"])
				,fixQuotes($rptop["cityTreasurer"])
				,fixQuotes($rptop["cityAssessor"])
				,fixQuotes($rptop["landTotalMarketValue"])
				,fixQuotes($rptop["landTotalAssessedValue"])
				,fixQuotes($rptop["plantTotalMarketValue"])
				,fixQuotes($rptop["plantTotalAssessedValue"])
				,fixQuotes($rptop["bldgTotalMarketValue"])
				,fixQuotes($rptop["bldgTotalAssessedValue"])
				,fixQuotes($rptop["machTotalMarketValue"])
				,fixQuotes($rptop["machTotalAssessedValue"])
				,fixQuotes($rptop["totalMarketValue"])
				,fixQuotes($rptop["totalAssessedValue"])
				,fixQuotes(time())
				,fixQuotes($rptop["createdBy"])
				,fixQuotes(time())
				,fixQuotes($rptop["modifiedBy"]));

			$db->beginTransaction();
			$db->query($sql);
			$rptopID = $db->insert_id();
			if($db->Errno!=0) {
				$db->rollbackTransaction();
				$db->resetErrors();
				return false;
			}
			else{
				$db->endTransaction();
				return $rptopID;
			}
		}
		else{
			return false;
		}
	}

	function setOwnerRPTOP($formArray,$ownerName,$rptopNumber){
		if(!is_array($formArray)) return false;
		if(!is_array($ownerName)) return false;
		if($rptopNumber=="") return false;

		// create new rptop record as $rptop (array)

		$rptop["rptopNumber"] = $rptopNumber;
		$rptop["taxableYear"] = $formArray["taxableYear"];
		$rptop["cityTreasurer"] = $formArray["cityTreasurer"];
		$rptop["cityAssessor"] = $formArray["cityAssessor"];
		$rptop["createdBy"] = $formArray["userID"];
		$rptop["modifiedBy"] = $formArray["userID"];

		$rptop["landTotalMarketValue"] = 0;
		$rptop["landTotalAssessedValue"] = 0;
		$rptop["plantTotalMarketValue"] = 0;
		$rptop["plantTotalAssessedValue"] = 0;
		$rptop["bldgTotalMarketValue"] = 0;
		$rptop["bldgTotalAssessedValue"] = 0;
		$rptop["machTotalMarketValue"] = 0;
		$rptop["machTotalAssessedValue"] = 0;
  
		$rptop["totalMarketValue"] = 0;
		$rptop["totalAssessedValue"] = 0;

		// get TDIDArray

		$ownerTDIDArray = $this->getTDListOf($ownerName["id"],$ownerName["type"],$formArray["taxableYear"]);
		foreach($ownerTDIDArray as $ownerTDID){
			$td = new TD;
			$afsID = $td->checkAfsID($ownerTDID);
			$afs = new AFS;
			$afs->selectRecord($afsID);

			$rptop["landTotalMarketValue"] += $afs->getLandTotalMarketValue();
			$rptop["landTotalAssessedValue"] += $afs->getLandTotalAssessedValue();
			$rptop["plantTotalMarketValue"] += $afs->getPlantTotalMarketValue();
			$rptop["plantTotalAssessedValue"] += $afs->getPlantTotalAssessedValue();
			$rptop["bldgTotalMarketValue"] += $afs->getBldgTotalMarketValue();
			$rptop["bldgTotalAssessedValue"] += $afs->getBldgTotalAssessedValue();
			$rptop["machTotalMarketValue"] += $afs->getMachTotalMarketValue();
			$rptop["machTotalAssessedValue"] += $afs->getMachTotalAssessedValue();
		}

		$rptop["totalMarketValue"] = $rptop["landTotalMarketValue"]+$rptop["plantTotalMarketValue"]+$rptop["bldgTotalMarketValue"]+$rptop["machTotalMarketValue"];

		$rptop["totalAssessedValue"] = $rptop["landTotalAssessedValue"]+$rptop["plantTotalAssessedValue"]+$rptop["bldgTotalAssessedValue"]+$rptop["machTotalAssessedValue"];

		// insert into RPTOP TABLE
		if($rptop["rptopID"] = $this->insertRPTOPRecord($rptop)){
			// insert into RPTOPTD TABLE

			foreach($ownerTDIDArray as $ownerTDID){
				if(!$rptoptdID = $this->insertRPTOPTDRecord($rptop["rptopID"],$ownerTDID)){
					// error inserting to RPTOPTD (shouldnt reach here)
				}
			}

			// insert into OWNER TABLE
			if($ownerID = $this->insertOwnerRecord($rptop["rptopID"])){
				// insert either into OWNER_PERSON or OWNER_COMPANY TABLE
				switch($ownerName["type"]){
					case "Person":
						if(!$ownerPersonID = $this->insertOwnerPerson($ownerID,$ownerName["id"])){
							// error inserting to OwnerPerson table (shouldnt reach here)
						}
						break;
					case "Company":
						if(!$ownerCompanyID = $this->insertOwnerCompany($ownerID,$ownerName["id"])){
							// error inserting to OwnerCompany table (shouldnt reach here)
						}
						break;
				}
			}

			return $rptop["rptopID"];
		}
		else{
			return false;
		}



		return true;
	}

	function getOwnerRPTOPArray($id,$type,$year){
		// id refers to either personID or companyID
		// depending on type which is either Person or Company
		// filtered by year

		if($id=="") return false;
		if($type=="") return false;
		if($year=="") return false;

		$db = new DB_RPTS;

		// NCC Modification checked and implemented by K2 : November 10, 2005
		// details:
		//         replaced `taxableYear >=` to `taxableYear <=` in line 327
		//		   replaced `taxableYear >=` to `taxableYear <=` in line 341
		switch($type){
			case "Person":
				$sql = sprintf("SELECT "
					.RPTOP_TABLE.".rptopID as rptopID, "
					.RPTOP_TABLE.".rptopNumber as rptopNumber FROM "
					.RPTOP_TABLE.", "
					.OWNER_TABLE.", "
					.OWNER_PERSON_TABLE." WHERE "
					.RPTOP_TABLE.".rptopID = ".OWNER_TABLE.".rptopID AND "
					.OWNER_TABLE.".ownerID = ".OWNER_PERSON_TABLE.".ownerID AND "
					.OWNER_PERSON_TABLE.".personID='%s' AND "
					.RPTOP_TABLE.".taxableYear <='%s'",
					fixQuotes($id),
					fixQuotes($year));
				break;
			case "Company":
				$sql = sprintf("SELECT "
					.RPTOP_TABLE.".rptopID as rptopID, "
					.RPTOP_TABLE.".rptopNumber as rptopNumber FROM "
					.RPTOP_TABLE.", "
					.OWNER_TABLE.", "
					.OWNER_COMPANY_TABLE." WHERE "
					.RPTOP_TABLE.".rptopID = ".OWNER_TABLE.".rptopID AND "
					.OWNER_TABLE.".ownerID = ".OWNER_COMPANY_TABLE.".ownerID AND "
					.OWNER_COMPANY_TABLE.".companyID='%s' AND "
					.RPTOP_TABLE.".taxableYear <='%s'",
					fixQuotes($id),
					fixQuotes($year));
				break;
		}

		$db->query($sql);

		if($db->next_record()) {
			$ownerRPTOP["rptopID"] = $db->f("rptopID");
			$ownerRPTOP["rptopNumber"] = $db->f("rptopNumber");

			return $ownerRPTOP;
		}
		else{
			return false;
		}
	}

	function getOwnerTDYear($id,$type,$year){
		// id refers to either personID or companyID
		// depending on type which is either Person or Company
		// filtered by year

		if($id=="") return false;
		if($type=="") return false;
		if($year=="") return false;

		$db = new DB_RPTS;

		// NCC Modification checked and implemented by K2 : November 10, 2005
		// details:
		//         replaced `effectivity =` to `effectivity <=` in line 387
		//		   replaced `effectivity =` to `effectivity <=` in line 402
		switch($type){
			case "Person":
				$sql = sprintf("SELECT 
					DISTINCT(".AFS_TABLE.".effectivity) as effectivity FROM "
					.AFS_TABLE.", "
					.OD_TABLE.", "
					.OWNER_TABLE.", "
					.OWNER_PERSON_TABLE." WHERE "
					.AFS_TABLE.".odID = ".OD_TABLE.".odID AND "
					.OD_TABLE.".odID = ".OWNER_TABLE.".odID AND "
					.OWNER_TABLE.".ownerID = ".OWNER_PERSON_TABLE.".ownerID AND "
					.OWNER_PERSON_TABLE.".personID = '%s' AND "
					.AFS_TABLE.".effectivity <= '%s'",
					fixQuotes($id),
					fixQuotes($year));
				break;
			case "Company":
				$sql = sprintf("SELECT 
					DISTINCT(".AFS_TABLE.".effectivity) as effectivity FROM "
					.AFS_TABLE.", "
					.OD_TABLE.", "
					.OWNER_TABLE.", "
					.OWNER_COMPANY_TABLE." WHERE "
					.AFS_TABLE.".odID = ".OD_TABLE.".odID AND "
					.OD_TABLE.".odID = ".OWNER_TABLE.".odID AND "
					.OWNER_TABLE.".ownerID = ".OWNER_COMPANY_TABLE.".ownerID AND "
					.OWNER_COMPANY_TABLE.".companyID = '%s' AND "
					.AFS_TABLE.".effectivity <= '%s'",
					fixQuotes($id),
					fixQuotes($year));
				break;
		}

		$db->query($sql);

		if($db->next_record()) {
			$effectivity = $db->f("effectivity");
			return $effectivity;
		}
		else{
			return false;
		}
	}

	// "imported" function from nccbiz/OwnerList.php
	// updated September 10, 2005
	function getTDListOf($id,$type,$year){
		$owner = new Owner;
		eval("\$ownerIDArray = \$owner->selectOwner".$type."(".$id.");");
		if ($ownerIDArray){
			$odArray = "";
			foreach ($ownerIDArray as $key => $value){
				eval("\$odID = \$owner->selectOD".$type."($value);");
				if ($odID) $odArray[] = $odID;
			}
			unset($owner);
			if ($odArray){
				$afsArray = "";
				foreach($odArray as $key => $value){
					$afs = new AFS;
					$odHistoryRecords = new OdHistoryRecords;
					$afsID = $afs->checkAFSYear($value,$year);
					$odHistoryArr = $odHistoryRecords->selectSuccOD($value,$year);
					if ($odHistoryArr){
						$latestAfs = true;
						foreach ($odHistoryArr as $k => $v){
							if ($afs->checkAFSYear($v,$year)) $latestAfs = false;
						}
						if ($latestAfs) $afsIDArray[] = $afsID;
					}
					else {
						if ($afsID <> "") $afsIDArray[] = $afsID;
					}
				}
				unset($afs);
				if ($afsIDArray){
					$tdRecords = new TDRecords;
					$tdIDArray = "";
					foreach ($afsIDArray as $tkey => $tvalue){
						$td = new TD;
						if ($td->selectRecord("",$tvalue)){
							// added the following if($td->getArchive()!="true") line on September 10, 2005 to.. 
							// ..omit 'cancelled' TDs and other TDs that went through a transaction in..
							// ..creating new RPTOPs.
							if($td->getArchive()!="true"){
								$tdIDArray[] = $td->getTdID();
							}
						}
						unset($td);
					}
					$ret = $tdIDArray;
				}
				else $ret = false;
			}
			else $ret = false;
		}
		else $ret = false;
		return  $ret;
	}

	function getOwnerTDArray($id,$type,$year){
		// id refers to either personID or companyID
		// depending on type which is either Person or Company
		// filtered by year

		if($id=="") return false;
		if($type=="") return false;
		if($year=="") return false;

		$db = new DB_RPTS;

		// NCC Modification checked and implemented by K2 : November 10, 2005
		// details:
		//         replaced `effectivity =` to `effectivity <=` in line 516
		//		   replaced `effectivity =` to `effectivity <=` in line 545
		switch($type){
			case "Person":
				$sql = sprintf("SELECT 
					DISTINCT(".TD_TABLE.".tdID) as tdID, 
					".TD_TABLE.".taxDeclarationNumber as taxDeclarationNumber, 
					".AFS_TABLE.".effectivity as effectivity,
					".AFS_TABLE.".landTotalMarketValue as landTotalMarketValue, 
					".AFS_TABLE.".landTotalAssessedValue as landTotalAssessedValue, 
					".AFS_TABLE.".plantTotalMarketValue as plantTotalMarketValue, 
					".AFS_TABLE.".plantTotalAssessedValue as plantTotalAssessedValue, 
					".AFS_TABLE.".bldgTotalMarketValue as bldgTotalMarketValue, 
					".AFS_TABLE.".bldgTotalAssessedValue as bldgTotalAssessedValue, 
					".AFS_TABLE.".machTotalMarketValue as machTotalMarketValue, 
					".AFS_TABLE.".machTotalAssessedValue as machTotalAssessedValue, 
					".AFS_TABLE.".totalMarketValue as totalMarketValue, 
					".AFS_TABLE.".totalAssessedValue as totalAssessedValue 
					FROM ".TD_TABLE.", 
					".AFS_TABLE.", 
					".OD_TABLE.", 
					".OWNER_TABLE.", 
					".OWNER_PERSON_TABLE." WHERE 
					".TD_TABLE.".afsID = ".AFS_TABLE.".afsID AND 
					".AFS_TABLE.".odID = ".OD_TABLE.".odID AND 
					".OD_TABLE.".odID = ".OWNER_TABLE.".odID AND 
					".OWNER_TABLE.".ownerID = ".OWNER_PERSON_TABLE.".ownerID AND 
					".OWNER_PERSON_TABLE.".personID = '%s' AND 
					".AFS_TABLE.".effectivity <= '%s'", 
					fixQuotes($id), 
					fixQuotes($year));
				break;
			case "Company":
				$sql = sprintf("SELECT 
					DISTINCT(".TD_TABLE.".tdID), 
					".TD_TABLE.".taxDeclarationNumber, 
					".AFS_TABLE.".effectivity, 
					".AFS_TABLE.".landTotalMarketValue as landTotalMarketValue, 
					".AFS_TABLE.".landTotalAssessedValue as landTotalAssessedValue, 
					".AFS_TABLE.".plantTotalMarketValue as plantTotalMarketValue, 
					".AFS_TABLE.".plantTotalAssessedValue as plantTotalAssessedValue, 
					".AFS_TABLE.".bldgTotalMarketValue as bldgTotalMarketValue, 
					".AFS_TABLE.".bldgTotalAssessedValue as bldgTotalAssessedValue, 
					".AFS_TABLE.".machTotalMarketValue as machTotalMarketValue, 
					".AFS_TABLE.".machTotalAssessedValue as machTotalAssessedValue, 
					".AFS_TABLE.".totalMarketValue as totalMarketValue, 
					".AFS_TABLE.".totalAssessedValue as totalAssessedValue FROM 
					".TD_TABLE.", 
					".AFS_TABLE.", 
					".OD_TABLE.", 
					".OWNER_TABLE.", 
					".OWNER_COMPANY_TABLE." WHERE 
					".TD_TABLE.".afsID = ".AFS_TABLE.".afsID AND 
					".AFS_TABLE.".odID = ".OD_TABLE.".odID AND 
					".OD_TABLE.".odID = ".OWNER_TABLE.".odID AND 
					".OWNER_TABLE.".ownerID = ".OWNER_COMPANY_TABLE.".ownerID AND 
					".OWNER_COMPANY_TABLE.".companyID = '%s' AND 
					".AFS_TABLE.".effectivity <= '%s'", 
					fixQuotes($id),
					fixQuotes($year));
				break;
		}

		$db->query($sql);

		while ($db->next_record()) {
			$ownerTD["tdID"] = $db->f("tdID");
			$ownerTD["taxDeclarationNumber"] = $db->f("taxDeclarationNumber");
			$ownerTD["effectivity"] = $db->f("effectivity");

			$ownerTD["landTotalMarketValue"] = $db->f("landTotalMarketValue");
			$ownerTD["landTotalAssessedValue"] = $db->f("landTotalAssessedValue");
			$ownerTD["plantTotalMarketValue"] = $db->f("plantTotalMarketValue");
			$ownerTD["plantTotalAssessedValue"] = $db->f("plantTotalAssessedValue");
			$ownerTD["bldgTotalMarketValue"] = $db->f("bldgTotalMarketValue");
			$ownerTD["bldgTotalAssessedValue"] = $db->f("bldgTotalAssessedValue");
			$ownerTD["machTotalMarketValue"] = $db->f("machTotalMarketValue");
			$ownerTD["machTotalAssessedValue"] = $db->f("machTotalAssessedValue");
			$ownerTD["totalMarketValue"] = $db->f("totalMarketValue");
			$ownerTD["totalAssessedValue"] = $db->f("totalAssessedValue");

			$key = $ownerTD["tdID"];
			$ownerTDArray[$key] = $ownerTD;
			unset($ownerTD);
		}

		// key sort

		if(is_array($ownerTDArray)){
			ksort($ownerTDArray);
			reset($ownerTDArray);
			return $ownerTDArray;
		}
		else{
			return false;
		}
	}

	function getOwnerNameArray($year,$limit=5){
		$this->setDB();

		// gather ownerPersons

		$sql = "SELECT 
			DISTINCT ".PERSON_TABLE.".personID as personID, "
			.PERSON_TABLE.".firstName as firstName, "
			.PERSON_TABLE.".lastName as lastName, "
			.PERSON_TABLE.".middleName as middleName FROM "
			.OWNER_TABLE.", "
			.OWNER_PERSON_TABLE.", Person WHERE "
			.OWNER_TABLE.".ownerID = ".OWNER_PERSON_TABLE.".ownerID AND "
			.OWNER_PERSON_TABLE.".personID = ".PERSON_TABLE.".personID ORDER BY "
			.PERSON_TABLE.".lastName";

		$this->db->query($sql);

		$ownerNameCount = 0;

		while ($this->db->next_record()) {
			$personNames["id"] = $this->db->f("personID");
			$personNames["ownerName"] = $this->db->f("lastName").", ".$this->db->f("firstName")." ".$this->db->f("middleName");
			$personNames["type"] = "Person";

			if($personNames["tdYear"] = $this->getOwnerTDYear($this->db->f("personID"), "Person", $year)){

				// check if owner has RPTOP for $year
				// if false, append to ownerNameArray
	
				if(!$personNames["ownerRPTOPArray"] = $this->getOwnerRPTOPArray($this->db->f("personID"), "Person", $year)){
					$key = $this->db->f("lastName").$this->db->f("firstName").$this->db->f("middleName").$personNames["id"];

					if($ownerNameCount < $limit){
						$ownerNameArray[$key] = $personNames;
						$ownerNameCount++;
					}
					else{
						break;
					}
				}

			}

			unset($personNames);
		}

		// gather ownerCompanies

		$sql = "SELECT 
			DISTINCT ".COMPANY_TABLE.".companyID as companyID, "
			.COMPANY_TABLE.".companyName FROM "
			.OWNER_TABLE.", "
			.OWNER_COMPANY_TABLE.", "
			.COMPANY_TABLE." WHERE "
			.OWNER_TABLE.".ownerID = ".OWNER_COMPANY_TABLE.".ownerID AND "
			.OWNER_COMPANY_TABLE.".companyID = ".COMPANY_TABLE.".companyID";

		$this->db->query($sql);

		while ($this->db->next_record()) {
			$companyNames["id"] = $this->db->f("companyID");
			$companyNames["ownerName"] = $this->db->f("companyName");
			$companyNames["type"] = "Company";

			if($companyNames["tdYear"] = $this->getOwnerTDYear($this->db->f("companyID"), "Company", $year)){

				// check if owner has RPTOP for particular tdArray of year
				// if false, append to ownerNameArray
				
				if(!$companyNames["ownerRPTOPArray"] = $this->getOwnerRPTOPArray($this->db->f("companyID"), "Company", $year)){
					$key = $companyNames["ownerName"].$companyNames["id"];


					if($ownerNameCount < $limit){
						$ownerNameArray[$key] = $companyNames;
						$ownerNameCount++;
					}
					else{
						break;
					}
				}

			}
			unset($companyNames);
		}

		// key sort

		if(is_array($ownerNameArray)){
			ksort($ownerNameArray);
			reset($ownerNameArray);

			return $ownerNameArray;
		}
		else{
			return false;
		}
	}

	// -----------------------------------------------------------

	// use just in case to delete duplicate RPTOP if needed. ***do not use if not authorized!***

	function deleteRPTOP($rptopID){
		if($rptopID=="") return false;

		unset($db);
		$db = new DB_RPTS;
		$sql = "SELECT ownerID FROM ".OWNER_TABLE." WHERE rptopID='".fixQuotes($rptopID)."'";
		$db->query($sql);

		if($db->next_record()) {
			$ownerID = $db->f("ownerID");

			$sql = "DELETE FROM ".RPTOP_TABLE." WHERE rptopID='".fixQuotes($rptopID)."'";
			$db->query($sql);

			$sql = "DELETE FROM ".RPTOPTD_TABLE." WHERE rptopID='".fixQuotes($rptopID)."'";
			$db->query($sql);

			$sql = "DELETE FROM ".OWNER_PERSON_TABLE." WHERE ownerID = '".$ownerID."'";
			$db->query($sql);

			$sql = "DELETE FROM ".OWNER_COMPANY_TABLE." WHERE ownerID = '".$ownerID."'";
			$db->query($sql);

			$sql = "DELETE FROM ".OWNER_TABLE." WHERE rptopID='".$rptopID."'";
			$db->query($sql);
		}

		/*
			general SQL flow for deleting RPTOP (just for reference)
			$ownerID = SELECT ownerID FROM Owner WHERE rptopID=$rptopID;

			DELETE FROM RPTOP WHERE rptopID=$rptopID;
			DELETE FROM RPTOPTD WHERE rptopID=$rptopID;
			DELETE FROM OwnerPerson WHERE ownerID = $ownerID;
			DELETE FROM OwnerCompany WHERE ownerID = $ownerID;
			DELETE FROM Owner WHERE rptopID = $rptopID;
		*/

		return false;		
	}

}
?>