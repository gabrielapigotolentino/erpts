<?php
# Setup PHPLIB in this Area
include_once("web/prepend.php");
include_once("web/clibPDFWriter.php");
include_once("assessor/Barangay.php");

include_once("assessor/Address.php");
include_once("assessor/Company.php");
include_once("assessor/CompanyRecords.php");
include_once("assessor/Person.php");
include_once("assessor/PersonRecords.php");
include_once("assessor/Owner.php");
include_once("assessor/OwnerRecords.php");
include_once("assessor/RPTOP.php");
include_once("assessor/AFS.php");
include_once("assessor/OD.php");

include_once("assessor/eRPTSSettings.php");

include_once("assessor/LandClasses.php");
include_once("assessor/LandSubclasses.php");
include_once("assessor/LandActualUses.php");

include_once("assessor/PlantsTreesClasses.php");
include_once("assessor/PlantsTreesActualUses.php");
include_once("assessor/ImprovementsBuildingsClasses.php");
include_once("assessor/ImprovementsBuildingsActualUses.php");
include_once("assessor/MachineriesClasses.php");
include_once("assessor/MachineriesActualUses.php");

#####################################
# Define Interface Class
#####################################
class TDDetails{
	
	var $tpl;
	var $formArray;
	function TDDetails($http_post_vars,$sess,$odID,$ownerID,$afsID,$print){
		$this->sess = $sess;
		$this->tpl = new rpts_Template(getcwd(),"keep");

		$this->tpl->set_file("rptsTemplate", "td.xml") ;
		$this->tpl->set_var("TITLE", "TD Details");
		
       	$this->formArray = array(
			"tdNumber" => ""
			, "propertyIndexNumber" => ""
			, "Owner" => ""
			, "Address" => ""
			, "administrator" => ""
			, "adminAddress" => ""
			, "location" => ""
			, "certificateOfTitleNumber" => ""
			, "cadastralLotNumber" => ""
			, "lotNumber" => ""
			, "blockNumber" => ""
			, "north" => ""
			, "south" => ""
			, "east" => ""
			, "west" => ""
			
			, "oLKind1" => ""
			, "oLKind2" => ""
			, "oLKind3" => ""
			, "oLKind4" => ""
			, "oLKind5" => ""
			, "oLKind6" => ""
			, "oLArea1" => ""
			, "oLArea2" => ""
			, "oLArea3" => ""
			, "oLArea4" => ""
			, "oLArea5" => ""
			, "oLArea6" => ""
			, "oLValue1" => ""
			, "oLValue2" => ""
			, "oLValue3" => ""
			, "oLValue4" => ""
			, "oLValue5" => ""
			, "oLValue6" => ""
			, "oLTotalValue" => ""
			, "lKind1" => ""
			, "lKind2" => ""
			, "lKind3" => ""
			, "lKind4" => ""
			, "lKind5" => ""
			, "lKind6" => ""
			, "lArea1" => ""
			, "lArea2" => ""
			, "lArea3" => ""
			, "lArea4" => ""
			, "lArea5" => ""
			, "lArea6" => ""
			, "lClass1" => ""
			, "lClass2" => ""
			, "lClass3" => ""
			, "lClass4" => ""
			, "lClass5" => ""
			, "lClass6" => ""
			, "lUnitValue1" => ""
			, "lUnitValue2" => ""
			, "lUnitValue3" => ""
			, "lUnitValue4" => ""
			, "lUnitValue5" => ""
			, "lUnitValue6" => ""
			, "lMarketValue1" => ""
			, "lMarketValue2" => ""
			, "lMarketValue3" => ""
			, "lMarketValue4" => ""
			, "lMarketValue5" => ""
			, "lMarketValue6" => ""
			, "lTotalMarketValue" => ""
			, "lTotalAdjMarketValue" => ""

			, "oPKind1" => ""
			, "oPKind2" => ""
			, "oPKind3" => ""
			, "oPKind4" => ""
			, "oPKind5" => ""
			, "oPKind6" => ""
			, "oPKind7" => ""
			, "oPKind8" => ""
			, "oPKind9" => ""
			, "oPQuantity1" => ""
			, "oPQuantity2" => ""
			, "oPQuantity3" => ""
			, "oPQuantity4" => ""
			, "oPQuantity5" => ""
			, "oPQuantity6" => ""
			, "oPQuantity7" => ""
			, "oPQuantity8" => ""
			, "oPQuantity9" => ""
			, "oPValue1" => ""
			, "oPValue2" => ""
			, "oPValue3" => ""
			, "oPValue4" => ""
			, "oPValue5" => ""
			, "oPValue6" => ""
			, "oPValue7" => ""
			, "oPValue8" => ""
			, "oPValue9" => ""
			, "oPTotalValue" => ""

			, "pKind1" => ""
			, "pQuantity1" => ""
			, "pUnitValue1" => ""
			, "pMarketValue1" => ""
			, "pKind2" => ""
			, "pQuantity2" => ""
			, "pUnitValue2" => ""
			, "pMarketValue2" => ""
			, "pKind3" => ""
			, "pQuantity3" => ""
			, "pUnitValue3" => ""
			, "pMarketValue3" => ""
			, "pKind4" => ""
			, "pQuantity4" => ""
			, "pUnitValue4" => ""
			, "pMarketValue4" => ""
			, "pKind5" => ""
			, "pQuantity5" => ""
			, "pUnitValue5" => ""
			, "pMarketValue5" => ""
			, "pKind6" => ""
			, "pQuantity6" => ""
			, "pUnitValue6" => ""
			, "pMarketValue6" => ""
			, "pKind7" => ""
			, "pQuantity7" => ""
			, "pUnitValue7" => ""
			, "pMarketValue7" => ""
			, "pKind8" => ""
			, "pQuantity8" => ""
			, "pUnitValue8" => ""
			, "pMarketValue8" => ""
			, "pKind9" => ""
			, "pQuantity9" => ""
			, "pUnitValue9" => ""
			, "pMarketValue9" => ""
			, "pTotalMarketValue" => ""
			, "pTotalAdjMarketValue" => ""

			, "oL2Kind1" => ""
			, "oL2Area1" => ""
			, "oL2Value1" => ""
			, "oL2Kind2" => ""
			, "oL2Area2" => ""
			, "oL2Value2" => ""
			, "oL2Kind3" => ""
			, "oL2Area3" => ""
			, "oL2Value3" => ""
			, "oL2Kind4" => ""
			, "oL2Area4" => ""
			, "oL2Value4" => ""
			, "oL2Kind5" => ""
			, "oL2Area5" => ""
			, "oL2Value5" => ""
			, "oL2TotalValue" => ""

			, "l2Kind1" => ""
			, "l2Area1" => ""
			, "l2UnitValue1" => ""
			, "l2Adjustment1" => ""
			, "l2MarketValue1" => ""
			, "l2Kind2" => ""
			, "l2Area2" => ""
			, "l2UnitValue2" => ""
			, "l2Adjustment2" => ""
			, "l2MarketValue2" => ""
			, "l2Kind3" => ""
			, "l2Area3" => ""
			, "l2UnitValue3" => ""
			, "l2Adjustment3" => ""
			, "l2MarketValue3" => ""
			, "l2Kind4" => ""
			, "l2Area4" => ""
			, "l2UnitValue4" => ""
			, "l2Adjustment4" => ""
			, "l2MarketValue4" => ""
			, "l2Kind5" => ""
			, "l2Area5" => ""
			, "l2UnitValue5" => ""
			, "l2Adjustment5" => ""
			, "l2MarketValue5" => ""
			, "l2TotalMarketValue" => ""

			, "oBDescription1" => ""
			, "oBFloorArea1" => ""
			, "oB1stStorey1" => ""
			, "oB2ndStorey1" => ""
			, "oB3rdStorey1" => ""
			, "oBRoof1" => ""
			, "oBMarketValue1" => ""
			, "oBDescription2" => ""
			, "oBFloorArea2" => ""
			, "oB1stStorey2" => ""
			, "oB2ndStorey2" => ""
			, "oB3rdStorey2" => ""
			, "oBRoof2" => ""
			, "oBMarketValue2" => ""
			, "oBDescription3" => ""
			, "oBFloorArea3" => ""
			, "oB1stStorey3" => ""
			, "oB2ndStorey3" => ""
			, "oB3rdStorey3" => ""
			, "oBRoof3" => ""
			, "oBMarketValue3" => ""
			, "oBTotalMarketValue" => ""

			, "bDescription1" => ""
			, "bFloorArea1" => ""
			, "b1stStorey1" => ""
			, "b2ndStorey1" => ""
			, "b3rdStorey1" => ""
			, "bRoof1" => ""
			, "bMarketValue1" => ""
			, "bDescription2" => ""
			, "bFloorArea2" => ""
			, "b1stStorey2" => ""
			, "b2ndStorey2" => ""
			, "b3rdStorey2" => ""
			, "bRoof2" => ""
			, "bMarketValue2" => ""
			, "bDescription3" => ""
			, "bFloorArea3" => ""
			, "b1stStorey3" => ""
			, "b2ndStorey3" => ""
			, "b3rdStorey3" => ""
			, "bRoof3" => ""
			, "bMarketValue3" => ""
			, "bTotalMarketValue" => ""

			, "oMDescription1" => ""
			, "oMDateOfOperation1" => ""
			, "oMOriginalCost1" => ""
			, "oMDepreciation1" => ""
			, "oMMarketValue1" => ""
			, "oMDescription2" => ""
			, "oMDateOfOperation2" => ""
			, "oMOriginalCost2" => ""
			, "oMDepreciation2" => ""
			, "oMMarketValue2" => ""
			, "oMDescription3" => ""
			, "oMDateOfOperation3" => ""
			, "oMOriginalCost3" => ""
			, "oMDepreciation3" => ""
			, "oMMarketValue3" => ""
			, "oMTotalMarketValue" => ""

			, "mDescription1" => ""
			, "mDateOfOperation1" => ""
			, "mOriginalCost1" => ""
			, "mDepreciation1" => ""
			, "mMarketValue1" => ""
			, "mDescription2" => ""
			, "mDateOfOperation2" => ""
			, "mOriginalCost2" => ""
			, "mDepreciation2" => ""
			, "mMarketValue2" => ""
			, "mDescription3" => ""
			, "mDateOfOperation3" => ""
			, "mOriginalCost3" => ""
			, "mDepreciation3" => ""
			, "mMarketValue3" => ""
			, "mTotalMarketValue" => ""

			, "propertyKind1" => ""
			, "propertyActualUse1" => ""
			, "propertyMarketValue1" => ""
			, "propertyAssessmentLevel1" => ""
			, "propertyAssessedValue1" => ""
			, "propertyKind2" => ""
			, "propertyActualUse2" => ""
			, "propertyMarketValue2" => ""
			, "propertyAssessmentLevel2" => ""
			, "propertyAssessedValue2" => ""
			, "propertyKind3" => ""
			, "propertyActualUse3" => ""
			, "propertyMarketValue3" => ""
			, "propertyAssessmentLevel3" => ""
			, "propertyAssessedValue3" => ""
			, "propertyKind4" => ""
			, "propertyActualUse4" => ""
			, "propertyMarketValue4" => ""
			, "propertyAssessmentLevel4" => ""
			, "propertyAssessedValue4" => ""

			, "totalMarketValue" => ""
			, "totalAssessedValue" => ""
			, "totalAssessedValueInWords" => ""

		);

		$this->formArray["odID"] = $odID;
		$this->formArray["ownerID"] = $ownerID;
		$this->formArray["afsID"] = $afsID;
		$this->formArray["print"] = $print;

	}
	
	function formatCurrency($key){
		if($this->formArray[$key]=="")
			return false;
		$this->formArray[$key] = number_format($this->formArray[$key], 2, ".", ",");
	}
	
	function setForm(){
		$this->formatCurrency("lTotalMarketValue");
		$this->formatCurrency("l2TotalMarketValue");
		$this->formatCurrency("pTotalMarketValue");
		$this->formatCurrency("bTotalMarketValue");
		$this->formatCurrency("mTotalMarketValue");
		$this->formatCurrency("totalMarketValue");
		$this->formatCurrency("totalAssessedValue");

		$this->formatCurrency("lTotalAdjMarketValue");
		$this->formatCurrency("l2TotalAdjMarketValue");
		$this->formatCurrency("pTotalAdjMarketValue");
	
		$this->formatCurrency("lArea1");
		$this->formatCurrency("lArea2");
		$this->formatCurrency("lArea3");
		$this->formatCurrency("lArea4");
		$this->formatCurrency("lArea5");
		$this->formatCurrency("lArea6");

		$this->formatCurrency("lUnitValue1");
		$this->formatCurrency("lUnitValue2");
		$this->formatCurrency("lUnitValue3");
		$this->formatCurrency("lUnitValue4");
		$this->formatCurrency("lUnitValue5");
		$this->formatCurrency("lUnitValue6");

		$this->formatCurrency("lMarketValue1");
		$this->formatCurrency("lMarketValue2");
		$this->formatCurrency("lMarketValue3");
		$this->formatCurrency("lMarketValue4");
		$this->formatCurrency("lMarketValue5");
		$this->formatCurrency("lMarketValue6");

		$this->formatCurrency("l2Area1");
		$this->formatCurrency("l2Area2");
		$this->formatCurrency("l2Area3");
		$this->formatCurrency("l2Area4");
		$this->formatCurrency("l2Area5");

		$this->formatCurrency("l2UnitValue1");
		$this->formatCurrency("l2UnitValue2");
		$this->formatCurrency("l2UnitValue3");
		$this->formatCurrency("l2UnitValue4");
		$this->formatCurrency("l2UnitValue5");

		$this->formatCurrency("l2MarketValue1");
		$this->formatCurrency("l2MarketValue2");
		$this->formatCurrency("l2MarketValue3");
		$this->formatCurrency("l2MarketValue4");
		$this->formatCurrency("l2MarketValue5");

		$this->formatCurrency("pUnitValue1");
		$this->formatCurrency("pUnitValue2");
		$this->formatCurrency("pUnitValue3");
		$this->formatCurrency("pUnitValue4");
		$this->formatCurrency("pUnitValue5");
		$this->formatCurrency("pUnitValue6");
		$this->formatCurrency("pUnitValue7");
		$this->formatCurrency("pUnitValue8");
		$this->formatCurrency("pUnitValue9");

		$this->formatCurrency("bFloorArea1");
		$this->formatCurrency("bFloorArea2");
		$this->formatCurrency("bFloorArea3");
		$this->formatCurrency("bMarketValue1");
		$this->formatCurrency("bMarketValue2");
		$this->formatCurrency("bMarketValue3");

		$this->formatCurrency("mOriginalCost1");
		$this->formatCurrency("mDepreciation1");
		$this->formatCurrency("mMarketValue1");
		$this->formatCurrency("mOriginalCost2");
		$this->formatCurrency("mDepreciation2");
		$this->formatCurrency("mMarketValue2");
		$this->formatCurrency("mOriginalCost3");
		$this->formatCurrency("mDepreciation3");
		$this->formatCurrency("mMarketValue3");

		$this->formatCurrency("propertyMarketValue1");
		$this->formatCurrency("propertyMarketValue2");
		$this->formatCurrency("propertyMarketValue3");
		$this->formatCurrency("propertyMarketValue4");

		$this->formatCurrency("propertyAssessedValue1");
		$this->formatCurrency("propertyAssessedValue2");
		$this->formatCurrency("propertyAssessedValue3");
		$this->formatCurrency("propertyAssessedValue4");
		

		foreach ($this->formArray as $key => $value){
			$this->tpl->set_var($key, html_entity_to_alpha($value));
		}
	}
	
	function displayOwnerList($domDoc){
		$owner = new Owner;
		$owner->parseDomDocument($domDoc);
			$ownerName = "";
			if (count($owner->personArray)){
				foreach($owner->personArray as $personKey =>$personValue){
					if ($ownerName == ""){
						if(is_object($personValue->addressArray[0])){
							$address = $personValue->addressArray[0]->getFullAddress();
							$ownerName = $personValue->getName();
						}
					}
					else{
						$ownerName = $ownerName." , ".$personValue->getName();
					}
				}
			}
			else{
			}
			if (count($owner->companyArray)){
				foreach ($owner->companyArray as $companyKey => $companyValue){
					if ($ownerName == ""){
						$address = $companyValue->addressArray[0]->getFullAddress();
						$ownerName = $companyValue->getCompanyName();
					}
					else{
						$ownerName = $ownerName." , ".$companyValue->getCompanyName();
					}
					
				}
			}
			else{
			}

		$this->formArray["Owner"] = $ownerName;
		$this->formArray["Address"] = $address;
	}
	function displayODAFS($afsID){
		$AFSDetails = new SoapObject(NCCBIZ."AFSDetails.php", "urn:Object");
		if (!$odID = $AFSDetails->getOdID($afsID)){
			echo ("get od id failed");
		}
		else{
			$ODDetails = new SoapObject(NCCBIZ."ODDetails.php", "urn:Object");
			if (!$xmlStr = $ODDetails->getOD($odID)){
				exit("xml failed");
			}
			else{
				//exit($xmlStr);
				if(!$domDoc = domxml_open_mem($xmlStr)) {
					echo "error open xml";
				}
				else {
					$od = new OD;
					$od->parseDomDocument($domDoc);
					if (is_object($od->locationAddress)){
						$location = $od->locationAddress->getNumber();
						$location.= " ".$od->locationAddress->getStreet();
						$location.= ", ".$od->locationAddress->getBarangay();
						$location.= ", ".$od->locationAddress->getDistrict();
						$location.= ", ".$od->locationAddress->getMunicipalityCity();
						$location.= ", ".$od->locationAddress->getProvince();
						$this->formArray["location"] = $location;
					}
					$this->formArray["lotNumber"] = $od->getLotNumber();
					$this->formArray["blockNumber"] = $od->getBlockNumber();
					$ODEncode = new SoapObject(NCCBIZ."ODEncode.php", "urn:Object");
					$this->formArray["ownerID"] = $ODEncode->getOwnerID($this->formArray["odID"]);
					$xmlStr = $od->owner->domDocument->dump_mem(true);
					if (!$xmlStr){
						echo "error xml";
					}
					else {
						//echo $xmlStr;
						if(!$domDoc = domxml_open_mem($xmlStr)) {
							echo "error open xml";
						}
						else {
							$this->displayOwnerList($domDoc);
						}
					}
				}	
			}
		}
	}
	
	function displayTDDetails($propertyType){
		$afsID = $this->formArray["afsID"];
		$propertyID = $this->formArray["propertyID"];
		
		$TDDetails = new SoapObject(NCCBIZ."TDDetails.php", "urn:Object");
		if (!$xmlStr = $TDDetails->getTD("",$afsID,$propertyID,$propertyType)){
			$this->formArray["tdNumber"] = "";
			$this->formArray["tdID"] = "";
			$this->formArray["propertyType"] = $propertyType;
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)) {
				$this->formArray["tdNumber"] = "";
				$this->formArray["tdID"] = "";
				$this->formArray["propertyType"] = $propertyType;
			}
			else {
				$td = new TD;
				$td->parseDomDocument($domDoc);
				$this->formArray["tdNumber"] = $td->getTaxDeclarationNumber();
				foreach($td as $tdkey => $tdvalue){
					switch ($tdkey){
						case "provincialAssessor":
							if (is_a($tdvalue,Assessor)){
								$this->formArray["provincialAssessorID"] = $tdvalue->getAssessorID();
								$this->formArray["provincialAssessorName"] = $tdvalue->getFullName();
							}
							else {
								$this->formArray[$tdkey] = "";
							}
						break;
						case "provincialAssessorDate":
							if (true){
								list($dateArr["year"],$dateArr["month"],$dateArr["day"]) = explode("-",$tdvalue);
								$this->formArray["pa_yearValue"] = removePreZero($dateArr["year"]);
								eval(MONTH_ARRAY);//$monthArray
								$this->formArray["pa_month"] = $monthArray[removePreZero($dateArr["month"])];
								$this->formArray["pa_dayValue"] = removePreZero($dateArr["day"]);
							}
							else {
								$this->formArray[$tdkey] = "";
							}
						break;
						case "cityMunicipalAssessor":
							if (is_a($tdvalue,Assessor)){
								$this->formArray["cityMunicipalAssessorID"] = $tdvalue->getAssessorID();
								$this->formArray["cityMunicipalAssessorName"] = $tdvalue->getFullName();
							}
							else {
								$this->formArray[$tdkey] = "";
							}
						break;
						case "cityMunicipalAssessorDate":
							if (true){
								list($dateArr["year"],$dateArr["month"],$dateArr["day"]) = explode("-",$tdvalue);
								$this->formArray["cm_yearValue"] = removePreZero($dateArr["year"]);
								eval(MONTH_ARRAY);//$monthArray
								$this->formArray["cm_month"] = $monthArray[removePreZero($dateArr["month"])];
								$this->formArray["cm_dayValue"] = removePreZero($dateArr["day"]);
							}
							else {
								$this->formArray[$tdkey] = "";
							}
						break;
						case "enteredInRPARForBy":
							if (is_a($tdvalue,Assessor)){
								$this->formArray["enteredInRPARForByID"] = $tdvalue->getAssessorID();
								$this->formArray["enteredInRPARForByName"] = $tdvalue->getFullName();
							}
							else {
								$this->formArray[$tdkey] = "";
							}
						break;
						default:
						$this->formArray[$tdkey] = $tdvalue;
					}
				}
			}
		}
	}

	function displayLandList($landList){
        if (count($landList)){
			$i = 0;
			$l = 0 ;
			$l2 = 0 ;
			$lTotalMarketValue = 0;
			$l2TotalMarketValue = 0;

			$lTotalAdjMarketValue = 0;

			foreach ($landList as $key => $land){
				// classification
				$landClasses = new LandClasses;
				if(is_numeric($land->getClassification())){
					$landClasses->selectRecord($land->getClassification());
					$landClassesDescription = $landClasses->getDescription();
					$landClassesCode = $landClasses->getCode();
				}
				else{
					$landClassesDescription = $land->getClassification();
					$landClassesCode = $land->getClassification();
				}
				// subClass
				$landSubclasses = new LandSubclasses;
				if(is_numeric($land->getSubClass())){
					$landSubclasses->selectRecord($land->getSubClass());
					$landSubclassesDescription = $landSubclasses->getDescription();
					$landSubclassesCode = $landSubclasses->getCode();
				}
				else{
					$landSubclassesDescription = $land->getSubClass();
					$landSubclassesCode = $land->getSubClass();
				}
				// actualUse
				$landActualUses = new LandActualUses;
				if(is_numeric($land->getActualUse())){
					$landActualUses->selectRecord($land->getActualUse());
					$landActualUsesDescription = $landActualUses->getDescription();
					$landActualUsesCode = $landActualUses->getCode();
					$landActualUsesReportCode = $landActualUses->getReportCode();
				}
				else{
					$landActualUsesDescription = $land->getActualUse();
					$landActualUsesCode = $land->getActualUse();
					$landActualUsesReportCode = $landActualUses->getReportCode();
				}

				if($i==0){
					$this->formArray["propertyID"] = $land->getPropertyID();
					$this->displayTDDetails("Land");
					//$this->formArray["propertyIndexNumber"] = $land->getPropertyIndexNumber();
					$this->formArray["north"] = $land->getNorth();
					$this->formArray["south"] = $land->getSouth();
					$this->formArray["east"] = $land->getEast();
					$this->formArray["west"] = $land->getWest();

					if (is_a($land->propertyAdministrator,Person)){
						$this->formArray["administrator"] = $land->propertyAdministrator->getFullName();
						if(is_array($land->propertyAdministrator->addressArray)){
							$adminAddress = $land->propertyAdministrator->addressArray[0]->getNumber();
							$adminAddress.= " ".$land->propertyAdministrator->addressArray[0]->getStreet();
							$adminAddress.= " ".$land->propertyAdministrator->addressArray[0]->getBarangay();
							$adminAddress.= " ".$land->propertyAdministrator->addressArray[0]->getDistrict();
							$adminAddress.= " ".$land->propertyAdministrator->addressArray[0]->getMunicipalityCity();
							$adminAddress.= " ".$land->propertyAdministrator->addressArray[0]->getProvince();
							$this->formArray["adminAddress"] = $adminAddress;
						}
					}

				}
				if($l<6){
					switch($landActualUsesReportCode){
						case "AG":
						case "MI":
							$this->formArray["lKind".($l+1)] = $landActualUsesDescription;
							$this->formArray["lArea".($l+1)] = $land->getArea();
							$this->formArray["lClass".($l+1)] = $landClassesDescription;
							$this->formArray["lUnitValue".($l+1)] = $land->getUnitValue();
							$this->formArray["lMarketValue".($l+1)] = $land->getMarketValue();
							$lTotalMarketValue += toFloat($land->getMarketValue());
							$lTotalAdjMarketValue += toFloat($land->getAdjustedMarketValue());
							$l++;
							break;
					}
				}

				if($l2<5){
					switch($landActualUsesReportCode){
						case "RE":
						case "CO":
						case "IN":
						case "SP":

						case "CH":
						case "CU":
						case "ED":
						case "GO":
						case "HO":
						case "RL":
						case "SC":
						case "TI":
						case "OTX":
						case "OTE":

							$this->formArray["l2Kind".($l2+1)] = $landActualUsesDescription;
							$this->formArray["l2Area".($l2+1)] = $land->getArea();
							$this->formArray["l2UnitValue".($l2+1)] = $land->getUnitValue();
							$this->formArray["l2Adjustment".($l2+1)] = $land->getValueAdjustment();
							$this->formArray["l2MarketValue".($l2+1)] = $land->getMarketValue();
							$l2TotalMarketValue += toFloat($land->getMarketValue());
							$l2TotalAdjMarketValue += toFloat($land->getAdjustedMarketValue());
							$l2++;
							break;
					}
				}

				$i++;
			}
			$this->formArray["lTotalMarketValue"] = $lTotalMarketValue;
			$this->formArray["l2TotalMarketValue"] = $l2TotalMarketValue;

			$this->formArray["lTotalAdjMarketValue"] = $lTotalAdjMarketValue;
			$this->formArray["l2TotalAdjMarketValue"] = $l2TotalAdjMarketValue;
		}
	}


	function displayPlantsTreesList($plantsTreesList){
        if (count($plantsTreesList)){
			$i = 0;
			$pTotalMarketValue = 0;
			$pTotalAdjMarketValue = 0;
			foreach ($plantsTreesList as $key => $plantsTrees){
				// productClass
				$plantsTreesClasses = new PlantsTreesClasses;
				if(is_numeric($plantsTrees->getProductClass())){
					$plantsTreesClasses->selectRecord($plantsTrees->getProductClass());
					$plantsTreesClassesDescription = $plantsTreesClasses->getDescription();
					$plantsTreesClassesCode = $plantsTreesClasses->getCode();
				}
				else{
					$plantsTreesClassesDescription = $plantsTrees->getProductClass();
					$plantsTreesClassesCode = $plantsTrees->getProductClass();
				}
				// actualUse
				$plantsTreesActualUses = new PlantsTreesActualUses;
				if(is_numeric($plantsTrees->getActualUse())){
					$plantsTreesActualUses->selectRecord($plantsTrees->getActualUse());
					$plantsTreesActualUsesDescription = $plantsTreesActualUses->getDescription();
					$plantsTreesActualUsesCode = $plantsTreesActualUses->getCode();
				}
				else{
					$plantsTreesActualUsesDescription = $plantsTrees->getActualUse();
					$plantsTreesActualUsesCode = $plantsTrees->getActualUse();
				}

				if($i==0){
					$this->formArray["propertyID"] = $plantsTrees->getPropertyID();
					//$this->formArray["propertyIndexNumber"] = $plantsTrees->getPropertyIndexNumber();
					$this->displayTDDetails("PlantsTrees");
				}
				if($i<9){
					$this->formArray["pKind".($i+1)] = $plantsTrees->getKind();
					$this->formArray["pQuantity".($i+1)] = $plantsTrees->getTotalNumber();
					$this->formArray["pUnitValue".($i+1)] = $plantsTrees->getUnitPrice();
					$this->formArray["pMarketValue".($i+1)] = $plantsTrees->getMarketValue();

					$pTotalMarketValue += toFloat($plantsTrees->getMarketValue());

					$pTotalAdjMarketValue += toFloat($plantsTrees->getAdjustedMarketValue());
				}
				$i++;
			}
			$this->formArray["pTotalMarketValue"] = $pTotalMarketValue;
			$this->formArray["pTotalAdjMarketValue"] = $pTotalAdjMarketValue;
		}
	}

	function displayImprovementsBuildingsList($improvementsBuildingsList){
        if (count($improvementsBuildingsList)){
			$i = 0;
			$bTotalMarketValue = 0;
			foreach ($improvementsBuildingsList as $key => $improvementsBuildings){
				// buildingClassification
				$improvementsBuildingsClasses = new ImprovementsBuildingsClasses;
				if(is_numeric($improvementsBuildings->getBuildingClassification())){
					$improvementsBuildingsClasses->selectRecord($improvementsBuildings->getBuildingClassification());
					$improvementsBuildingsClassesDescription = $improvementsBuildingsClasses->getDescription();
					$improvementsBuildingsClassesCode = $improvementsBuildingsClasses->getCode();
				}
				else{
					$improvementsBuildingsClassesDescription = $improvementsBuildings->getBuildingClassification();
					$improvementsBuildingsClassesCode = $improvementsBuildings->getBuildingClassification();
				}
				// actualUse
				$improvementsBuildingsActualUses = new ImprovementsBuildingsActualUses;
				if(is_numeric($improvementsBuildings->getActualUse())){
					$improvementsBuildingsActualUses->selectRecord($improvementsBuildings->getActualUse());
					$improvementsBuildingsActualUsesDescription = $improvementsBuildingsActualUses->getDescription();
					$improvementsBuildingsActualUsesCode = $improvementsBuildingsActualUses->getCode();
				}
				else{
					$improvementsBuildingsActualUsesDescription = $improvementsBuildings->getActualUse();
					$improvementsBuildingsActualUsesCode = $improvementsBuildings->getActualUse();
				}

				if($i==0){
					$this->formArray["propertyID"] = $improvementsBuildings->getPropertyID();
					//$this->formArray["propertyIndexNumber"] = $improvementsBuildings->getPropertyIndexNumber();
					$this->displayTDDetails("ImprovementsBuildings");
				}
				if($i<3){
					$this->formArray["bDescription".($i+1)] = $improvementsBuildings->getKind();
					$this->formArray["bFloorArea".($i+1)] = $improvementsBuildings->getAreaOfGroundFloor();
					$this->formArray["bRoof".($i+1)] = $improvementsBuildings->getRoof();
					$this->formArray["bMarketValue".($i+1)] = $improvementsBuildings->getMarketValue();

					$bTotalMarketValue += toFloat($improvementsBuildings->getMarketValue());
				}
				$i++;
			}
			$this->formArray["bTotalMarketValue"] = $bTotalMarketValue;
		}
	}

	function displayMachineriesList($machineriesList){
        if (count($machineriesList)){
			$i = 0;
			$mTotalMarketValue = 0;
			foreach ($machineriesList as $key => $machineries){
				// kind
				$machineriesClasses = new MachineriesClasses;
				if(is_numeric($machineries->getKind())){
					$machineriesClasses->selectRecord($machineries->getKind());
					$machineriesClassesDescription = $machineriesClasses->getDescription();
					$machineriesClassesCode = $machineriesClasses->getCode();
				}
				else{
					$machineriesClassesDescription = $machineries->getKind();
					$machineriesClassesCode = $machineries->getActualUse();
				}
				// actualUse
				$machineriesActualUses = new MachineriesActualUses;
				if(is_numeric($machineries->getActualUse())){
					$machineriesActualUses->selectRecord($machineries->getActualUse());
					$machineriesActualUsesDescription = $machineriesActualUses->getDescription();
					$machineriesActualUsesCode = $machineriesActualUses->getCode();
				}
				else{
					$machineriesActualUsesDescription = $machineries->getActualUse();
					$machineriesActualUsesCode = $machineries->getActualUse();
				}

				if($i==0){
					$this->formArray["propertyID"] = $machineries->getPropertyID();
					//$this->formArray["propertyIndexNumber"] = $machineries->getPropertyIndexNumber();
					$this->displayTDDetails("Machineries");
				}
				if($i<3){
					$this->formArray["mDescription".($i+1)] = $machineriesClassesDescription;
					$this->formArray["mDateOfOperation".($i+1)] = $machineries->getDateOfOperation();
					$this->formArray["mOriginalCost".($i+1)] = $machineries->getAcquisitionCost();
					$this->formArray["mDepreciation".($i+1)] = $machineries->getDepreciation();
					$this->formArray["mMarketValue".($i+1)] = $machineries->getMarketValue();

					$mTotalMarketValue += toFloat($machineries->getMarketValue());
				}
				$i++;
			}
			$this->formArray["mTotalMarketValue"] = $mTotalMarketValue;
		}
	}

	function displayAssessedValues($landList,$plantsTreesList,$improvementsBuildingsList,$machineriesList){
		$totalMarketValue = 0;
		$totalAssessedValue = 0;
		$i = 0;
		if(count($landList)){
			foreach($landList as $key => $land){
				if($i<4){
					// classification
					$landClasses = new LandClasses;
					if(is_numeric($land->getClassification())){
						$landClasses->selectRecord($land->getClassification());
						$landClassesDescription = $landClasses->getDescription();
						$landClassesCode = $landClasses->getCode();
					}
					else{
						$landClassesDescription = $land->getClassification();
						$landClassesCode = $land->getClassification();
					}
					// subClass
					$landSubclasses = new LandSubclasses;
					if(is_numeric($land->getSubClass())){
						$landSubclasses->selectRecord($land->getSubClass());
						$landSubclassesDescription = $landSubclasses->getDescription();
						$landSubclassesCode = $landSubclasses->getCode();
					}
					else{
						$landSubclassesDescription = $land->getSubClass();
						$landSubclassesCode = $land->getSubClass();
					}
					// actualUse
					$landActualUses = new LandActualUses;
					if(is_numeric($land->getActualUse())){
						$landActualUses->selectRecord($land->getActualUse());
						$landActualUsesDescription = $landActualUses->getDescription();
						$landActualUsesCode = $landActualUses->getCode();
					}
					else{
						$landActualUsesDescription = $land->getActualUse();
						$landActualUsesCode = $land->getActualUse();
					}
	
					$this->formArray["propertyKind".($i+1)] = "Land";
					$this->formArray["propertyActualUse".($i+1)] = $landActualUsesDescription;
					$this->formArray["propertyMarketValue".($i+1)] = $land->getMarketValue();
					$this->formArray["propertyAssessmentLevel".($i+1)] = $land->getAssessmentLevel();
					$this->formArray["propertyAssessedValue".($i+1)] = $land->getAssessedValue();

					$totalMarketValue+=toFloat($land->getMarketValue());
					$totalAssessedValue+=toFloat($land->getAssessedValue());
					$i++;
				}
			}
		}
		if(count($plantsTreesList)){
			foreach($plantsTreesList as $key => $plantsTrees){
				if($i<4){
					// productClass
					$plantsTreesClasses = new PlantsTreesClasses;
					if(is_numeric($plantsTrees->getProductClass())){
						$plantsTreesClasses->selectRecord($plantsTrees->getProductClass());
						$plantsTreesClassesDescription = $plantsTreesClasses->getDescription();
						$plantsTreesClassesCode = $plantsTreesClasses->getCode();
					}
					else{
						$plantsTreesClassesDescription = $plantsTrees->getProductClass();
						$plantsTreesClassesCode = $plantsTrees->getProductClass();
					}
					// actualUse
					$plantsTreesActualUses = new PlantsTreesActualUses;
					if(is_numeric($plantsTrees->getActualUse())){
						$plantsTreesActualUses->selectRecord($plantsTrees->getActualUse());
						$plantsTreesActualUsesDescription = $plantsTreesActualUses->getDescription();
						$plantsTreesActualUsesCode = $plantsTreesActualUses->getCode();
					}
					else{
						$plantsTreesActualUsesDescription = $plantsTrees->getActualUse();
						$plantsTreesActualUsesCode = $plantsTrees->getActualUse();
					}

					$this->formArray["propertyKind".($i+1)] = "PlantsTrees";
					$this->formArray["propertyActualUse".($i+1)] = $plantsTreesActualUsesDescription;
					$this->formArray["propertyMarketValue".($i+1)] = $plantsTrees->getMarketValue();
					$this->formArray["propertyAssessmentLevel".($i+1)] = $plantsTrees->getAssessmentLevel();
					$this->formArray["propertyAssessedValue".($i+1)] = $plantsTrees->getAssessedValue();

					$totalMarketValue+=toFloat($plantsTrees->getMarketValue());
					$totalAssessedValue+=toFloat($plantsTrees->getAssessedValue());
					$i++;
				}
			}
		}
		if(count($improvementsBuildingsList)){
			foreach($improvementsBuildingsList as $key => $improvementsBuildings){
				if($i<4){
					// buildingClassification
					$improvementsBuildingsClasses = new ImprovementsBuildingsClasses;
					if(is_numeric($improvementsBuildings->getBuildingClassification())){
						$improvementsBuildingsClasses->selectRecord($improvementsBuildings->getBuildingClassification());
						$improvementsBuildingsClassesDescription = $improvementsBuildingsClasses->getDescription();
						$improvementsBuildingsClassesCode = $improvementsBuildingsClasses->getCode();
					}
					else{
						$improvementsBuildingsClassesDescription = 	$improvementsBuildings->getBuildingClassification();
						$improvementsBuildingsClassesCode = $improvementsBuildings->getBuildingClassification();
					}
					// actualUse
					$improvementsBuildingsActualUses = new ImprovementsBuildingsActualUses;
					if(is_numeric($improvementsBuildings->getActualUse())){
						$improvementsBuildingsActualUses->selectRecord($improvementsBuildings->getActualUse());
						$improvementsBuildingsActualUsesDescription = 	$improvementsBuildingsActualUses->getDescription();
						$improvementsBuildingsActualUsesCode = $improvementsBuildingsActualUses->getCode();
					}
					else{
						$improvementsBuildingsActualUsesDescription = $improvementsBuildings->getActualUse();
						$improvementsBuildingsActualUsesCode = $improvementsBuildings->getActualUse();
					}

					$this->formArray["propertyKind".($i+1)] = "ImprovementsBuildings";
					$this->formArray["propertyActualUse".($i+1)] = $improvementsBuildingsActualUsesDescription;
					$this->formArray["propertyMarketValue".($i+1)] = $improvementsBuildings->getMarketValue();
					$this->formArray["propertyAssessmentLevel".($i+1)] = $improvementsBuildings->getAssessmentLevel();
					$this->formArray["propertyAssessedValue".($i+1)] = $improvementsBuildings->getAssessedValue();

					$totalMarketValue+=toFloat($improvementsBuildings->getMarketValue());
					$totalAssessedValue+=toFloat($improvementsBuildings->getAssessedValue());
					$i++;
				}
			}
		}
		if(count($machineriesList)){
			foreach($machineriesList as $key => $machineries){
				if($i<4){
					// kind
					$machineriesClasses = new MachineriesClasses;
					if(is_numeric($machineries->getKind())){
						$machineriesClasses->selectRecord($machineries->getKind());
						$machineriesClassesDescription = $machineriesClasses->getDescription();
						$machineriesClassesCode = $machineriesClasses->getCode();
					}
					else{
						$machineriesClassesDescription = $machineries->getKind();
						$machineriesClassesCode = $machineries->getActualUse();
					}
					// actualUse
					$machineriesActualUses = new MachineriesActualUses;
					if(is_numeric($machineries->getActualUse())){
						$machineriesActualUses->selectRecord($machineries->getActualUse());
						$machineriesActualUsesDescription = $machineriesActualUses->getDescription();
						$machineriesActualUsesCode = $machineriesActualUses->getCode();
					}
					else{
						$machineriesActualUsesDescription = $machineries->getActualUse();
						$machineriesActualUsesCode = $machineries->getActualUse();
					}

					$this->formArray["propertyKind".($i+1)] = "Machineries";
					$this->formArray["propertyActualUse".($i+1)] = $machineriesActualUsesDescription;
					$this->formArray["propertyMarketValue".($i+1)] = $machineries->getMarketValue();
					$this->formArray["propertyAssessmentLevel".($i+1)] = $machineries->getAssessmentLevel();
					$this->formArray["propertyAssessedValue".($i+1)] = $machineries->getAssessedValue();

					$totalMarketValue+=toFloat($machineries->getMarketValue());
					$totalAssessedValue+=toFloat($machineries->getAssessedValue());
					$i++;
				}
			}
		}

		$this->formArray["totalMarketValue"] = $totalMarketValue;
		$this->formArray["totalAssessedValue"] = $totalAssessedValue;

		$this->formArray["totalAssessedValueInWords"] = makewords($totalAssessedValue);
	}

	function displayAssessor(){
		$eRPTSSettingsDetails = new SoapObject(NCCBIZ."eRPTSSettingsDetails.php", "urn:Object");
		if(!$xmlStr = $eRPTSSettingsDetails->getERPTSSettingsDetails(1)){
			// error xml
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)){
				// error domDoc
			}
			else{
				$eRPTSSettings = new eRPTSSettings;
				$eRPTSSettings->parseDomDocument($domDoc);

				$this->formArray["provincialAssessorName"] = $eRPTSSettings->getAssessorFullName();
			}
		}

	}
	
	function Main(){
		$AFSDetails = new SoapObject(NCCBIZ."AFSDetails.php", "urn:Object");
		if (!$xmlStr = $AFSDetails->getAFS($this->formArray["afsID"])){
			exit("afs not found");
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)) {
				exit("error xmlDoc");
			}
			else {
				$afs = new AFS;
				$afs->parseDomDocument($domDoc);

				$this->displayODAFS($this->formArray["afsID"]);

				$this->formArray["propertyIndexNumber"] = $afs->propertyIndexNumber;

				//land
				$landList = $afs->getLandArray();
				if(count($landList)){
					$this->displayLandList($landList);
				}

				//plantsTrees
				$plantsTreesList = $afs->getPlantsTreesArray();
				if(count($plantsTreesList)){
					$this->displayPlantsTreesList($plantsTreesList);
				}

				//improvementsBuildings
				$improvementsBuildingsList = $afs->getImprovementsBuildingsArray();
				if(count($improvementsBuildingsList)){
					$this->displayImprovementsBuildingsList($improvementsBuildingsList);
				}

				//machineries
				$machineriesList = $afs->getMachineriesArray();
				if(count($machineriesList)){
					$this->displayMachineriesList($machineriesList);
				}

				$this->displayAssessedValues($landList,$plantsTreesList,$improvementsBuildingsList,$machineriesList);

				$this->displayAssessor();

			}
		}
				
        $this->setForm();
		
        $this->tpl->parse("templatePage", "rptsTemplate");
        $this->tpl->finish("templatePage");

		$testpdf = new PDFWriter;
        $testpdf->setOutputXML($this->tpl->get("templatePage"),"test");
        if(isset($this->formArray["print"])){
        	$testpdf->writePDF($name);//,$this->formArray["print"]);
        }
        else {
        	$testpdf->writePDF($name);
        }		
//		header("location: ".$testpdf->pdfPath);
		exit;
    }
}

#####################################
# Define Procedures and Functions
#####################################

##########################################################
# Begin Program Script
##########################################################
//*
page_open(array("sess" => "rpts_Session"
	,"auth" => "rpts_Challenge_Auth"
	//"perm" => "rpts_Perm"
	));
//*/

$tdDetails = new TDDetails($http_post_vars,$sess,$odID,$ownerID,$afsID,$print);
$tdDetails->Main();
?>
<?php page_close(); ?>
