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
			"arpNumber" => "" // AFS
			,"taxDeclarationNumber" => "" // TD
			,"propertyIndexNumber" => "" // AFS
			,"ownerName" => "" // OD->Owner
			,"ownerAddress" => "" // OD->Owner
			,"administratorName" => ""
			,"administratorAddress" => ""
			,"numberStreet" => "" // LocationAddress
			,"barangay" => "" // LocationAddress
			,"municipalityCity" => "" // LocationAddress
			,"octTctNumber" => "" // Land
			,"surveyNumber" => "" // Land
			,"lotNumber" => "" // OD
			,"blockNumber" => "" // OD
			,"north" => "" // Land
			,"south" => "" // Land
			,"east" => "" // Land
			,"west" => "" // Land
			,"p" => 1 //propertyCounter
			,"kind1" => "" // Property [L,P,B,M]
			,"classification1" => "" // Property [L,P,B,M]
			,"marketValue1" => "" // Property [L,P,B,M]
			,"assessmentLevel1" => "" // Property [L,P,B,M]
			,"assessedValue1" => "" // Property [L,P,B,M]
			,"kind2" => "" // Property [L,P,B,M]
			,"classification2" => "" // Property [L,P,B,M]
			,"marketValue2" => "" // Property [L,P,B,M]
			,"assessmentLevel2" => "" // Property [L,P,B,M]
			,"assessedValue2" => "" // Property [L,P,B,M]
			,"kind3" => "" // Property [L,P,B,M]
			,"classification3" => "" // Property [L,P,B,M]
			,"marketValue3" => "" // Property [L,P,B,M]
			,"assessmentLevel3" => "" // Property [L,P,B,M]
			,"assessedValue3" => "" // Property [L,P,B,M]
			,"totalMarketValue" => 0  // Property [L,P,B,M]
			,"totalAssessedValue" => 0  // Property [L,P,B,M]
			,"totalAssessedValueInWords" => ""
			,"area" => "" // Land
			,"unit" => "" // Land
			,"effectivity" => "" // AFS
			,"taxability" => "" // AFS
			,"isTaxable" => "" // AFS
			,"isExempt" => "" // AFS
			,"verifiedBy" => ""
			,"cityAssessor" => "" // TD
			,"cancelsTDNumber" => "" // TD
			,"memoranda" => "" // TD
			,"propertyType" => "" // TD
		);

		$this->formArray["odID"] = $odID;
		$this->formArray["ownerID"] = $ownerID;
		$this->formArray["afsID"] = $afsID;
		$this->formArray["print"] = $print;

	}
	
	function formatCurrency($key){
		if($this->formArray[$key]=="")
			return false;

		if(is_numeric($this->formArray[$key]))
			$this->formArray[$key] = number_format(un_number_format($this->formArray[$key]), 2, ".", ",");
	}
	
	function setForm(){
		$this->formatCurrency("marketValue1");
		$this->formatCurrency("assessedValue1");
		$this->formatCurrency("marketValue2");
		$this->formatCurrency("assessedValue2");
		$this->formatCurrency("marketValue3");
		$this->formatCurrency("assessedValue3");

		$this->formatCurrency("totalMarketValue");
		$this->formatCurrency("totalAssessedValue");

		foreach ($this->formArray as $key => $value){
			$this->tpl->set_var($key, html_entity_to_alpha($value));
		}
	}

	function displayLandList($landList){
		if(count($landList)){
			foreach($landList as $lkey => $land){
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

				/* just in case subClass and actualUse needs to be drawn from land

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
				*/

				if($this->formArray["p"] <= 3){
					$p = $this->formArray["p"];

					$this->formArray["kind".$p] = $land->getKind();
					$this->formArray["classification".$p] = $landClassesDescription;

					$this->formArray["marketValue".$p] = $land->getMarketValue();
					$this->formArray["assessmentLevel".$p] = $land->getAssessmentLevel();
					$this->formArray["assessedValue".$p] = $land->getAssessedValue();

					$this->formArray["totalMarketValue"] += un_number_format($this->formArray["marketValue".$p]);
					$this->formArray["totalAssessedValue"] += un_number_format($this->formArray["assessedValue".$p]);

					$this->formArray["p"]++;
				}
			}

		}
	}

	function displayPlantsTreesList($plantsTreesList){
		if(count($plantsTreesList)){
			foreach($plantsTreesList as $pkey => $plantsTrees){
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

				/* just in case actualUse needs to be drawn from plantsTrees

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
				*/

				if($this->formArray["p"] <= 3){
					$p = $this->formArray["p"];

					$this->formArray["kind".$p] = $plantsTrees->getKind();
					$this->formArray["classification".$p] = $plantsTreesClassesDescription;

					$this->formArray["marketValue".$p] = $plantsTrees->getMarketValue();
					$this->formArray["assessmentLevel".$p] = $plantsTrees->getAssessmentLevel();
					$this->formArray["assessedValue".$p] = $plantsTrees->getAssessedValue();

					$this->formArray["totalMarketValue"] += $this->formArray["marketValue".$p];
					$this->formArray["totalAssessedValue"] += $this->formArray["assessedValue".$p];

					$this->formArray["p"]++;
				}
			}

		}
	}

	function displayImprovementsBuildingsList($improvementsBuildingsList){
		if(count($improvementsBuildingsList)){
			foreach($improvementsBuildingsList as $bkey => $improvementsBuildings){

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

				/* just in case actualUse needs to be drawn from improvementsBuildings

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

				*/

				if($this->formArray["p"] <= 3){
					$p = $this->formArray["p"];

					$this->formArray["kind".$p] = $improvementsBuildings->getKind();
					$this->formArray["classification".$p] = $improvementsBuildingsClassesDescription;

					$this->formArray["marketValue".$p] = $improvementsBuildings->getMarketValue();
					$this->formArray["assessmentLevel".$p] = $improvementsBuildings->getAssessmentLevel();
					$this->formArray["assessedValue".$p] = $improvementsBuildings->getAssessedValue();

					$this->formArray["totalMarketValue"] += $this->formArray["marketValue".$p];
					$this->formArray["totalAssessedValue"] += $this->formArray["assessedValue".$p];

					$this->formArray["p"]++;
				}
			}

		}
	}

	function displayMachineriesList($machineriesList){
		if(count($machineriesList)){
			foreach($machineriesList as $mkey => $machineries){
				// "kind" is assumed to be treated as "classes" for machineries

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

				// "classification" is assumed to be treated as "actualUse" for machineries
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

				if($this->formArray["p"] <= 3){
					$p = $this->formArray["p"];

					$this->formArray["kind".$p] = $machineriesClassesDescription;
					$this->formArray["classification".$p] = $machineriesActualUsesDescription;

					$this->formArray["marketValue".$p] = $machineries->getMarketValue();
					$this->formArray["assessmentLevel".$p] = $machineries->getAssessmentLevel();
					$this->formArray["assessedValue".$p] = $machineries->getAssessedValue();

					$this->formArray["totalMarketValue"] += $this->formArray["marketValue".$p];
					$this->formArray["totalAssessedValue"] += $this->formArray["assessedValue".$p];

					$this->formArray["p"]++;
				}
			}

		}
	}

	function displayLandDetails($landList){
        if (count($landList)){
			$land = $landList[0];

			$this->formArray["propertyID"] = $land->getPropertyID();
			$this->formArray["north"] = $land->getNorth();
			$this->formArray["south"] = $land->getSouth();
			$this->formArray["east"] = $land->getEast();
			$this->formArray["west"] = $land->getWest();

			$this->formArray["octTctNumber"] = $land->getOctTctNumber();
			$this->formArray["surveyNumber"] = $land->getSurveyNumber();
			$this->formArray["area"] = $land->getArea();
			$this->formArray["unit"] = $land->getUnit();

			if (is_a($land->propertyAdministrator,Person)){
				$this->formArray["administratorName"] = $land->propertyAdministrator->getFullName();

				if(is_array($land->propertyAdministrator->addressArray)){
					$adminAddress = $land->propertyAdministrator->addressArray[0]->getNumber();
					$adminAddress.= " ".$land->propertyAdministrator->addressArray[0]->getStreet();
					$adminAddress.= " ".$land->propertyAdministrator->addressArray[0]->getBarangay();
					$adminAddress.= " ".$land->propertyAdministrator->addressArray[0]->getDistrict();
					$adminAddress.= " ".$land->propertyAdministrator->addressArray[0]->getMunicipalityCity();
					$adminAddress.= " ".$land->propertyAdministrator->addressArray[0]->getProvince();
					$this->formArray["administratorAddress"] = $adminAddress;
				}
			}

			if(is_numeric($land->getVerifiedBy())){
				$verifiedBy = new Person;
				$verifiedBy->selectRecord($land->getVerifiedBy());
				$this->formArray["verifiedBy"] = $verifiedBy->getFullName();
			}
			else{
				$this->formArray["verifiedBy"] = $land->getVerifiedBy();
			}
		}
	}

	function displayOwnerList($domDoc){
		$owner = new Owner;
		$owner->parseDomDocument($domDoc);

		$ownerName = "";
		if (is_array($owner->personArray)){
			foreach($owner->personArray as $personKey =>$personValue){
				if ($ownerName == ""){
					if(is_object($personValue->addressArray[0])){
						$address = $personValue->addressArray[0]->getFullAddress();
					}
					$ownerName = $personValue->getName();
				}
				else{
					$ownerName = $ownerName." , ".$personValue->getName();
				}
			}
		}
		else{
		}

		if (is_array($owner->companyArray)){
			foreach ($owner->companyArray as $companyKey => $companyValue){
				if ($ownerName == ""){
					if(is_object($companyValue->addressArray[0])){
						$address = $companyValue->addressArray[0]->getFullAddress();
					}
					$ownerName = $companyValue->getCompanyName();
				}
				else{
					$ownerName = $ownerName." , ".$companyValue->getCompanyName();
				}
			}
		}
		else{
		}
		
		$this->formArray["ownerName"] = $ownerName;
		$this->formArray["ownerAddress"] = $address;
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
						$this->formArray["location"] = $od->locationAddress->getFullAddress();
						$this->formArray["numberStreet"] = $od->locationAddress->getNumber()." ". $od->locationAddress->getStreet();
						$this->formArray["barangay"] = $od->locationAddress->getBarangay();
						$this->formArray["municipalityCity"] = $od->locationAddress->getMunicipalityCity();
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

	function displayTDDetails(){
		$afsID = $this->formArray["afsID"];
		
		$TDDetails = new SoapObject(NCCBIZ."TDDetails.php", "urn:Object");
		if (!$xmlStr = $TDDetails->getTD("",$afsID,"","")){
			// error xml
		}
		else{
			if(!$domDoc = domxml_open_mem($xmlStr)) {
				// error domDoc
			}
			else {
				$td = new TD;
				$td->parseDomDocument($domDoc);

				$this->formArray["taxDeclarationNumber"] = $td->getTaxDeclarationNumber();
				$this->formArray["memoranda"] = $td->getMemoranda();
				$this->formArray["cancelsTDNumber"] = $td->getCancelsTDNumber();

				//cityMunicipalAssessor
				if(is_numeric($td->getCityMunicipalAssessor())){
					$cityMunicipalAssessor = new Person;
					$cityMunicipalAssessor->selectRecord($td->cityMunicipalAssessor);
					$this->formArray["cityAssessor"] = $cityMunicipalAssessor->getFullName();
				}
				else{
					$this->formArray["cityAssessor"] = $td->getCityMunicipalAssessor;
				}

				$this->formArray["propertyType"] = $td->getPropertyType();
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

				$this->formArray["propertyIndexNumber"] = $afs->getPropertyIndexNumber();
				$this->formArray["arpNumber"] = $afs->getArpNumber();
				$this->formArray["effectivity"] = $afs->getEffectivity();
				if($afs->getTaxability()=="Taxable"){
					$this->formArray["isTaxable"] = "X";
					$this->formArray["isExempt"] = "  ";
				}
				else if($afs->getTaxability()=="Exempt"){
					$this->formArray["isExempt"] = "X";
					$this->formArray["isTaxable"] = "  ";
				}

				$this->displayODAFS($this->formArray["afsID"]);
				$this->displayTDDetails();

				// if propertyType is "Land", grab Land values plus PlantsTrees values
				// if propertyType is "ImprovementsBuildings" or "Machineries", system should later on grab
				// "Land" from another AFS from based on bldg->landPin or mach->landPin field
				// still needs to be resolved whether to do it this way or not

				switch($this->formArray["propertyType"]){
					case "Land":
						$landList = $afs->getLandArray();
						$plantsTreesList = $afs->getPlantsTreesArray();

						if(is_array($landList)){
							$this->displayLandDetails($landList);
							$this->displayLandList($landList);
						}
						if(is_array($plantsTreesList)){
							$this->displayPlantsTreesList($plantsTreesList);
						}

						break;
					case "ImprovementsBuildings":
						$improvementsBuildingsList = $afs->getImprovementsBuildingsArray();
						if(is_array($improvementsBuildingsList)){
							$this->displayImprovementsBuildingsList($improvementsBuildingsList);
						}
						break;
					case "Machineries":
						$machineriesList = $afs->getMachineriesArray();
						if(is_array($machineriesList)){
							$this->displayMachineriesList($machineriesList);
						}
						break;
				}

				// UNCOMMENT LINES TO GRAB totalMarketValue and totalAssessedValue from AFS object instead of 
				// computing from each property:

				//$this->formArray["totalMarketValue"] = $afs->getTotalMarketValue();
				//$this->formArray["totalAssessedValue"] = $afs->getTotalAssessedValue();

				$this->formArray["totalAssessedValueInWords"] = makewords($this->formArray["totalAssessedValue"]);

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
