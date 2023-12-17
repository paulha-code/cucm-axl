<?php

$messageUpdate ="";
/////////////////////////////////////////////////////////////////////////////////
///////////////    Connection Functions    //////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
/* This function create the SOAP connection to the cluster.
All subsequent AXL calls use this client */
function fcreateMyConnection($server, $userId, $password) {
    try {
        $client = null;
        //echo ("<p class=WSDL>Using the current WSDL :</p>");
        $client = new SoapClient("../current/AXLAPI.wsdl", //Make sure the AXL WSDL and xsd files are in this location!!!  Stream verification disabled for PHP5.6 and above
            array('trace'=>true,
        		'stream_context'=> stream_context_create(array('ssl'=> array('verify_peer'=>false,'verify_peer_name'=>false))),
                'exceptions'=>true,                    
                'location'=>"https://".$server.":8443/axl",
                'login'=>$userId,
                'password'=>$password,
                ));
    } catch (SoapFault $fault) {
        echo("<p class=SOAPError>SOAP Fault in createMyConnection: {$fault->faultstring} </p><br/><br/>");   
    }
    return $client;
}
function createRISConnection($server, $userId, $password, $version) {
    try {
        $client = null;
            //$client = new SoapClient("./current/RisPort.wsdl", //Make sure the AXL WSDL and xsd files are in this location!!!  Stream verification disabled for PHP5.6 and above
		$client = new SoapClient("./current/RISService70.wsdl",
            array('trace'=>true,
                    'stream_context'=> stream_context_create(array('ssl'=> array('verify_peer'=>false,'verify_peer_name'=>false))),
                    'exceptions'=>true,                    
                    //'location'=>"https://".$server.":8443/realtimeservice/services/RisPort",
					'location'=>"https://".$server.":8443/realtimeservice2/services/RISService70?wsdl",
                    'login'=>$userId,
                    'password'=>$password,

                ));
    } catch (SoapFault $fault) {
        echo("<p class=SOAPError>SOAP Fault: {$fault->faultstring} </p><br/><br/>"); 
    }
    return $client;
}
/////////////////////////////////////////////////////////////////////////////////
//////////////   End Connection Functions   /////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////////////////  Add Functions   /////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

function faddCallManagerGroup($client,$CMGName,$description,$memberList){
/* First add the group with the first group member, then update the group with remaining memebers */
    try {
        $index=1;
        $removedFirst = array_shift($memberList);
        $response = $client->addCallManagerGroup(array("callManagerGroup"=>
                    array("name"=>$CMGName,
                            "members"=>array(
                                "member"=>array("callManagerName"=>$removedFirst,
                                                "priority"=>1,),
                            )
                            )));
        $result = true;
        } catch (SoapFault $fault) {
    echo("<p class=SOAPError>SOAP Fault in faddCallManager Group:{$fault->faultstring} </p>");
           $result = false;
    }
    if (sizeof($memberList)>0){
        $cmgUpdate = fupdateCallManagerGroup($client,$CMGName,$memberList);
    }
    return $result;
}
function faddCSS($client,$CSSName,$description,$memberList){
/* First create the CSS with the first memeber, then update with the remaining members */
    try {
        $removedFirst = array_shift($memberList);
        $response = $client->addCss(array("css"=>
                                        array("description"=>$description,
                                                "name"=>$CSSName,
                                                "members"=>(array(
                                                    "member"=>array("routePartitionName"=>$removedFirst,
                                                                "index"=>1,),
                                        )))));
        $result = true;
        } catch (SoapFault $fault) {
    echo("<p class=SOAPError>SOAP Fault in faddCSS:{$fault->faultstring} </p>");
           $result = false;
    }
    if (sizeof($memberList)>0){
        $cssUpdate = fupdateCSS($client,$CSSName,$memberList);
    }
    return $result;
}
function faddPartition($client,$partitionName,$description){
    try {
        $response = $client->addroutePartition(array("routePartition"=>
                                        array("description"=>$description,
                                                "name"=>$partitionName,
                                        )));
        $result = true;
        } catch (SoapFault $fault) {
    echo("<p class=SOAPError>SOAP Fault in faddPartition:{$fault->faultstring} </p>");
           $result = false;
    }
    return $result;
}
function faddMediaResourceGroup($client,$MRGName,$description,$memberList){
    try {
        $index=1;
        $removedFirst = array_shift($memberList);
        $response = $client->addMediaResourceGroup(array("mediaResourceGroup"=>
                    array("name"=>$MRGName,
                            "members"=>array(
                                "member"=>array("Name"=>$removedFirst,),
                            )
                            )));
        $result = true;
        } catch (SoapFault $fault) {
    echo("<p class=SOAPError>SOAP Fault in faddMediaResourceGroup:{$fault->faultstring} </p>");
           $result = false;
    }
    if (sizeof($memberList)>0){
        $cmgUpdate = fupdateMediaResourceGroup($client,$MRGName,$memberList);
    }
    return $result;
}
function faddMediaResourceList($client,$MRLName,$description,$memberList){
    try {
        $index=1;
        $removedFirst = array_shift($memberList);
        $response = $client->addMediaResourceList(array("mediaResourceList"=>
                    array("name"=>$MRLName,
                            "members"=>array(
                                "member"=>array("Name"=>$removedFirst,),
                            )
                            )));
        $result = true;
        } catch (SoapFault $fault) {
    echo("<p class=SOAPError>SOAP Fault in faddMediaResourceList:{$fault->faultstring} </p>");
           $result = false;
    }
    if (sizeof($memberList)>0){
        $cmgUpdate = fupdateMediaResourceList($client,$MRLName,$memberList);
    }
    return $result;
}
function faddsipTrunkSecurityProfile($client,$STSPname,$description){
	try {
        $response = $client->addsipTrunkSecurityProfile(array("sipTrunkSecurityProfile"=>
                    					array("name"=>$STSPname,
											"description"=>$description,
											"securityMode"=>"Non Secure",
											"IncomingTransport"=>"TCP+UDP",
											"OutgoingTransport"=>"TCP",
											"incomingPort"=>"5060",
											"acceptPresenceSubscription"=>"True",
											"acceptOutOfDialogRefer"=>"True",
											"acceptUnsolicitedNotification"=>"True",
											"allowReplaceHeader"=>"True",
											"sipV150OutboundSdpOfferFiltering"=>"Use Default Filter",
										),
                            )
                            );
        $result = true;
        } catch (SoapFault $fault) {
    echo("<p class=SOAPError>SOAP Fault in faddsipTrunkSecurityProfile:{$fault->faultstring} </p>");
           $result = false;
    }
    return $result;
}
function faddsipProfile($client,$SPname,$description){
	try {
        $response = $client->addsipProfile(array("sipProfile"=>
                    					array("name"=>$SPname,
											"description"=>$description,
											"enableOutboundOptionsPing"=>"true",
											"sipBandwidthModifier"=>"TIAS and AS",
											"userAgentServerHeaderInfo"=>"Send Unified CM Version Information as User-Agent Header",
											"dialStringInterpretation"=>"Phone number consists of characters 0-9, *, #, and + (others treated as URI addresses)",
											"callingLineIdentification"=>"Default",
											"sipSessionRefreshMethod"=>"Invite",
											"earlyOfferSuppVoiceCall"=>"Disabled (Default value)",
											"cucmVersionInSipHeader"=>"Major And Minor",
											"confidentialAccessLevelHeaders"=>"Disabled",
										),
                            )
                            );
        $result = true;
        } catch (SoapFault $fault) {
    echo("<p class=SOAPError>SOAP Fault in faddsipProfile:{$fault->faultstring} </p>");
           $result = false;
    }
    return $result;
}
function faddsipTrunk($client,$trunkName,$description,$DevicePool,$inCSS,$destinations,$STSPname,$SPname){
	$index=1;
	$removedFirst = array_shift($destinations);
	try {
        $response = $client->addsipTrunk(array("sipTrunk"=>
                    					array("name"=>$trunkName,
											"description"=>$description,
											"product"=>"SIP Trunk",
											"class"=>"Trunk",
											"protocol"=>"SIP",
											"protocolSide"=>"Network",
											"callingSearchSpaceName"=>array("_"=>$inCSS),
											"devicePoolName"=>array("_"=>$DevicePool),
											"locationName"=>array("_"=>"Hub_None"),
											"securityProfileName"=>array("_"=>$STSPname),
											"sipProfileName"=>array("_"=>$SPname),
											"presenceGroupName"=>array("_"=>"Standard Presence group"),
											"callingAndCalledPartyInfoFormat"=>"Deliver DN only in connected party",
											"runOnEveryNode"=>"true",
											"destinations"=>array("destination"=>array("addressIpv4"=>$removedFirst,
																						"port"=>"5060",
																						"sortOrder"=>$index))
										),
                            )
                            );
        $result = true;
        } catch (SoapFault $fault) {
    echo("<p class=SOAPError>SOAP Fault in faddsipTrunk:{$fault->faultstring} </p>");
           $result = false;
    }
	if (sizeof($destinations)>0){
        $cmgUpdate = fupdatesipTrunk($client,$trunkName,$destinations);
    }
    return $result;
}
function faddpresenceRedundancyGroup($client,$PRGName,$description,$memberList){
    ////   may require uuid rather than _
	try {
        $response = $client->addpresenceRedundancyGroup(array("presenceRedundancyGroup"=>
                                        array("description"=>$description,
                                                "name"=>$PRGName,
                                                "server1"=>(array("_"=>$memberlist[0])),
                                                "server2"=>(array("_"=>$memberList[1])),
                                        )));
        $result = true;
        } catch (SoapFault $fault) {
    echo("<p class=SOAPError>SOAP Fault in faddpresenceRedundancyGroup:{$fault->faultstring} </p>");
           $result = false;
    }
    return $result;
}
function fadddateTimeGroup($client,$DTGName){
    try {
        $response = $client->adddateTimeGroup(array("dateTimeGroup"=>
                                        array("name"=>$partitionName,
                                            "timeZone"=>"Etc/GMT",
                                            "separator"=>"/",
                                            "dateformt"=>"D/M/Y",
                                            "timeFormat"=>"24-hour",
                                        )));
        $result = true;
        } catch (SoapFault $fault) {
    echo("<p class=SOAPError>SOAP Fault in fadddateTimeGroup:{$fault->faultstring} </p>");
           $result = false;
    }
    return $result;
}
function faddregion($client,$regionName){
    try {
        $response = $client->addRegion(array("region"=>
                                        array("name"=>$regionName
                                        )));
        $result = true;
        } catch (SoapFault $fault) {
    echo("<p class=SOAPError>SOAP Fault in faddregion:{$fault->faultstring} </p>");
           $result = false;
    }
    return $result;
}
function faddUCServiceCTI($client,$name,$host){
	try {
        $response = $client->addUcService(array("ucService"=>
                                        array("name"=>$name,
										"serviceType"=>"CTI",
										"productType"=>"CTI",
										"description"=>"CTI on ".$host,
										"hostnameorip"=>$host,
										"port"=>"2748",
										"protocol"=>"TCP",
                                        )));
        $result = true;
        } catch (SoapFault $fault) {
    echo("<p class=SOAPError>SOAP Fault in faddUSServiceCTI:{$fault->faultstring} </p>");
           $result = false;
    }
    return $result;
}
function faddUCServiceIMP($client,$name,$host){
	try {
        $response = $client->addUcService(array("ucService"=>
                                        array("name"=>$name,
										"serviceType"=>"IM and Presence",
										"productType"=>"Unified CM (IM and Presence)",
										"description"=>"IMP on ".$host,
										"hostnameorip"=>$host,
                                        )));
        $result = true;
        } catch (SoapFault $fault) {
    echo("<p class=SOAPError>SOAP Fault in faddUSServiceCTI:{$fault->faultstring} </p>");
           $result = false;
    }
    return $result;
}

///////////////////////////////////////////////////////////////////////////////
////////////////  End Add Functions       /////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////////////////  Update Functions   //////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
function fupdateCSS($client,$CSSName,$memberList){
    $result = fupdateTemplate($client,$CSSName,$memberList,"routePartitionName","updateCss","index");
    return $result;
}
function fupdateCallManagerGroup($client,$CMGName,$memberList){
        $result = fupdateTemplate($client,$CMGName,$memberList,"callManagerName","updateCallManagerGroup","priority");
        return $result;
}
function fupdateMediaResourceGroup($client,$CMGName,$memberList){
	$result = fupdateTemplate($client,$MRGName,$memberList,"deviceName","updateMediaResourceGroup","priority");
	//  note deviceNames must be uuid's
	return $result;
}
function fupdateTemplate($client,$updateContainer,$updateList,$updateItem,$updateQuery,$orderType ){
    $index=2;
    foreach ($updateList as $update) {
     try {
        $response = $client->$updateQuery(
                        array("name"=>$updateContainer,
                                    "addMembers"=>array(
                                        "member"=>array($updateItem=>$update,
                                                        $orderType=>$index),
                            )));
        $result = true;
        $index = $index +1;
        } catch (SoapFault $fault) {
            echo("<p class=SOAPError>SOAP Fault in fupdateTemplate:{$fault->faultstring} </p>");
            $result = false;
        }
    }
    return $result;
}
function frenameAnnunciator($client,$oldName,$newName){
	$result= frenameTemplate($client,"updateAnnunciator",$oldName,$newName);
	return $result;
}
function frenameIVR($client,$oldName,$newName){
	$result= frenameTemplate($client,"updateInteractiveVoiceResponse",$oldName,$newName);
	return $result;
}
function frenameConferenceBridge($client,$oldName,$newName){
	$result= frenameTemplate($client,"updateConferenceBridge",$oldName,$newName);
	return $result;
}
function frenameMTP($client,$oldName,$newName){
	$result= frenameTemplate($client,"updateMtp",$oldName,$newName);
	return $result;
}
function frenameMOH($client,$oldName,$newName){
	$result= frenameTemplate($client,"updateMohServer",$oldName,$newName);
	return $result;
}
function frenamedevicePool($client,$oldName,$newName){
	$result= frenameTemplate($client,"updateDevicePool",$oldName,$newName);
	return $result;
}
function frenameTemplate($client,$updateQuery,$oldName,$newName){
     try {
        $response = $client->$updateQuery(
                        array("name"=>$oldName,
                              "newName"=>$newName,
                            ));
        $result = true;
        } catch (SoapFault $fault) {
            echo("<p class=SOAPError>SOAP Fault in frenameTemplate:{$fault->faultstring} </p>");
            $result = false;
        }
    return $result;
}
function fupdatesipTrunk($client,$STName,$destinations){
	$index=2;
    foreach ($destinations as $destination) {
     try {
        $response = $client->updateSipTrunk(
                        array("name"=>$STName,
                                    "addDestinations"=>array(
                                        "destination"=>array("addressIpv4"=>$destination,
															"port"=>"5060",
															"sortOrder"=>$index))
                            ));
        $result = true;
        $index = $index +1;
        } catch (SoapFault $fault) {
            echo("<p class=SOAPError>SOAP Fault in fupdatesipTrunk:{$fault->faultstring} </p>");
            $result = false;
        }
    }
    return $result;
}
///////////////////////////////////////////////////////////////////////////////
////////////////  End Update Functions   //////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////
///////////          GET COMMANDS SECTION      ////////////////
///////////////////////////////////////////////////////////////
function fcheckCallManager($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getCallManager");
    return $result;
}
function fcheckCallManagerGroup($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getCallManagerGroup");
    return $result;
}
function fcheckCSS($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getCss");
    return $result;
}
function fcheckPartition($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getRoutePartition");
    return $result;
}
function fcheckPresenceRedundancyGroup($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getPresenceRedundancyGroup");
    return $result;
}
function fcheckDateTimeGroup($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getDateTimeGroup");
    return $result;
}
function fcheckRegion($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getRegion");
    return $result;
}
function fcheckAnnunciator($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getAnnunciator");
    return $result;
}
function fcheckIVR($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getIVR");
    return $result;
}
function fcheckConferenceBridge($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getConferenceBridge");
    return $result;
}
function fcheckMediaTerminationPoint($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getMtp");
    return $result;
}
function fcheckMohServer($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getMohServer");
    return $result;
}
function fcheckMediaResourceGroup($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getMediaResourceGroup");
    return $result;
}
function fcheckMediaResourceList($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getMediaResourceList");
    return $result;
}
function fcheckDevicePool($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getDevicePool");
    return $result;
}
function fcheckSipTrunkSecurityProfile($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getSipTrunkSecurityProfile");
    return $result;
}
function fcheckSipProfile($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getSipProfile");
    return $result;
}
function fcheckSipTrunk($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getSipTrunk");
    return $result;
}
function fcheckCredentialPolicy($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getCredentialPolicy");
    return $result;
}
function fcheckUCService($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getUcService");
    return $result;
}
function fcheckServiceProfile($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getServiceProfile");
    return $result;
}
function fcheckProcessNode($client, $itemName) {
    $result= fcheckTemplate($client, $itemName, "getProcessNode");
    return $result;
}
function fcheckTemplate ($client, $getItem, $getQuery){
    $ItemExists = false;
    try {
        $response = $client->$getQuery(array("name"=>$getItem));
        $ItemExists = true;
    } 
    catch (SoapFault $fault) {
        echo("<p class=SOAPError> SOAP Fault in fcheckTemplate: ".$getQuery.": {$fault->faultstring} </p>");
    }
    if ($ItemExists) {
        return $response->return;
    } else {
        return $ItemExists;
    }
}
function fcheckLine($client, $dn, $partition) {
	$lineExists = false;	 
	try {
	   $response = $client->getLine(array("pattern"=>$dn,
										   "routePartitionName"=>$partition));
	   $lineExists = true;
   } catch (SoapFault $fault) {
   }
   return $lineExists;
}	
///////////////////////////////////////////////////////////////
///////////       END GET COMMANDS SECTION     ////////////////
///////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////
//////////////     LIST COMANDS SECTION      //////////////////
///////////////////////////////////////////////////////////////

function flistCallManager ($client ) {
    $GroupList = flistTemplate($client,"callManager","listCallManager");
    return $GroupList;
}
function flistCallManagerGroup ($client ) {
    $GroupList = flistTemplate($client,"callManagerGroup","listCallManagerGroup");
    return $GroupList;
}
function flistCSS ($client ) {
    $GroupList = flistTemplate($client,"css","listCSS");
    return $GroupList;
}
function flistRoutePartition($client ) {
    $GroupList = flistTemplate($client,"routePartition","listRoutePartition");
    return $GroupList;
}
function flistPresenceRedundancyGroup($client ) {
    $GroupList = flistTemplate($client,"presenceRedundancyGroup","ListPresenceRedundancyGroup");
    return $GroupList;
}
function flistDateTimeGroup($client ) {
    $GroupList = flistTemplate($client,"dateTimeGroup","listDateTimeGroup");
    return $GroupList;
}
function flistRegion($client ) {
    $GroupList = flistTemplate($client,"region","ListRegion");
    return $GroupList;
}
function flistAnnunciator($client ) {
    $GroupList = flistTemplate($client,"annunciator","ListAnnunciator");
    return $GroupList;
}
function flistIVR($client ) {
    $GroupList = flistTemplate($client,"interactiveVoiceResponse","ListInteractiveVoiceResponse");
    return $GroupList; 
}
function flistConferenceBridge($client ) {
    $GroupList = flistTemplate($client,"conferenceBridge","ListConferenceBridge");
    return $GroupList; 
}
function flistMediaTerminationPoint($client ) {
    $GroupList = flistTemplate($client,"mtp","listMtp");
    return $GroupList; 
}
function flistMohServer($client ) {
    $GroupList = flistTemplate($client,"mohServer","listMohServer");
    return $GroupList; 
}
function flistMediaResourceGroup($client ) {
    $GroupList = flistTemplate($client,"mediaResourceGroup","listMediaResourceGroup");
    return $GroupList; 
}
function flistMediaResourceList($client ) {
    $GroupList = flistTemplate($client,"mediaResourceList","listMediaResourceList");
    return $GroupList;
}
function flistDevicePool($client ) {
    $GroupList = flistTemplate($client,"devicePool","listDevicePool");
    return $GroupList;
}
function flistSipTrunkSecurityProfile($client ) {
    $GroupList = flistTemplate($client,"sipTrunkSecurityProfile","listSipTrunkSecurityProfile");
    return $GroupList;
}
function flistSipProfile($client ) {
    $GroupList = flistTemplate($client,"sipProfile","listSipProfile");
    return $GroupList;
}
function flistSipTrunk($client ) {
    $GroupList = flistTemplate($client,"sipTrunk","listSipTrunk");
    return $GroupList;
}
function flistCredentialPolicy($client ) {
    $GroupList = flistTemplate($client,"credentialPolicy","listCredentialPolicy");
    return $GroupList;
}
function flistUCService($client ) {
    $GroupList = flistTemplate($client,"ucService","listUcService");
    return $GroupList;
}
function flistServiceProfile($client ) {
    $GroupList = flistTemplate($client,"serviceProfile","listServiceProfile");
    return $GroupList;
}
function flistProcessNode($client ) {
    $GroupList = flistTemplate($client,"processNode","listProcessNode");
    return $GroupList;
}
function flistTemplate($client,$listItem,$ListQuery) {

    $TemplateList = array();
    try {
    $searchCriteria = array("name"=>"%");
    $returnTags = array("name"=>"",
                        "description"=>"");
    $response = $client->$ListQuery(array("searchCriteria"=>$searchCriteria,
                                                    "returnedTags"=>$returnTags));
    //var_dump($response->return);
    $ITEMs = $response->return->$listItem;
    if (is_object($ITEMs)){
        $TemplateList [] = $ITEMs->name;
    } else {
        foreach ($ITEMs as $name) {
            $TemplateList [] = $name->name;
        }
    }
    } 
    catch (SoapFault $fault) {
        echo("<p class=SOAPError> SOAP Fault in flistTemplate: ".$ListQuery.": {$fault->faultstring} </p>");
    }
    return $TemplateList; 
}
///////////////////////////////////////////////////////////////////////////////////
///////////////     End of LISTS  /////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////
///////////////     User Handling  ////////////////////////////////////////////////
///////////  note these are old and were task specific so will need some work /////
///////////////////////////////////////////////////////////////////////////////////
function fgetUser($client, $userid) {
	 try {
        $response = $client->getUser(array("userid"=>$userid));          
    } catch (SoapFault $fault) {
        echo("<p class=SOAPError>SOAP Fault in fgetUser:{$fault->faultstring} </p>");      
    }
    return $response;
}
function faddUser($client, $firstName, $lastName, $userId, $email, $department){
		$myFirstName = $firstName;
		$myLastName = $lastName;
		$myUserId = $userId;
		$myEmail = $email;
		$myDepartment = $department;
		try {
		$response = $client->addUser(array("user"=>
                        array("firstName"=>$myFirstName,
                                "lastName"=>$myLastName,
                                "displayName"=>$myFirstName." ".$myLastName,
                                "userid"=>$myUserId,
                                "password"=>"password",
                                "pin"=>"12345",
                                "mailid"=>$myEmail,
								"directoryUri"=>$myEmail,
								"telephoneNumber"=>$myUserId,
								"imAndPresenceEnable"=>"true",
								"enableEmcc"=>"true",
                                "department"=>$myDepartment,
                                "presenceGroupName"=>array("_"=>"Standard Presence Group"),
                                "associatedGroups"=>array("userGroup"=>array("name"=>"Standard CCM End Users"))
        						)));
        	$result = true;
        	} catch (SoapFault $fault) {
        echo("<p class=SOAPError>SOAP Fault in faddUser:{$fault->faultstring} </p>");
       		$result = false;
    	}
    	return $result;
}
function fupdateUserProfile($client, $userId, $profileUUID){
		$myUserId = $userId;
		$myProfileUUID=$profileUUID;	
		try {
		$response = $client->updateUser(array("userid"=>$myUserId,
                        					 "phoneProfiles"=>array("profileName"=>array("uuid"=>$myProfileUUID)),
                        					  "defaultProfile"=>array("_"=>$myUserId)
        						));
        	$result = true;
        	} catch (SoapFault $fault) {
        echo("<p class=SOAPError>SOAP Fault in fupdateUserProfile:{$fault->faultstring} </p>");
       		$result = false;
    	}
    	return $result;
}
function fupdateUserPrimaryExtension($client, $userId, $dirNum, $partition){
		$myUserId = $userId;
		$myDirNum=$dirNum;
		$myPartition = $partition;
		
		try {
		$response = $client->updateUser(array("userid"=>$myUserId,
                        					 "primaryExtension"=>array("pattern"=>$myDirNum,
                        					 							"routePartitionName"=>$myPartition) 
        						));
        	$result = true;
        	} catch (SoapFault $fault) {
        echo("<p class=SOAPError>SOAP Fault in fupdateUserPrimaryExtension:{$fault->faultstring} </p>");
       		$result = false;
    	}
    	return $result;
}
function fcheckUser($client, $userid) {
	 $userExists = false;	 
	 try {
        $response = $client->getUser(array("userid"=>$userid));
        $userExists = true;
    } catch (SoapFault $fault) {
    }
    return $userExists;
}
function flistMultipleUsers($client, $criteria){
		$myCriteria = strtolower($criteria);
		if ($myCriteria == "all") {
		 $myCriteria = "%";
		 }
		 echo ($myCriteria."<br>");
		 $returnedTags = array("firstName"=>"",
		 						"lastName"=>"",
		 						"mailid"=>"");
		 $searchCriteria = array("userid"=>$myCriteria);
		 $response = false;
		 
		 try{
		 $response = $client->listUser(array("searchCriteria"=>$searchCriteria,
		 									 "returnedTags"=>$returnedTags
		 									 ));
		 }catch (SoapFault $fault) {
   		 echo("<p class=SOAPError>SOAP Fault in flistUser:{$fault->faultstring} </p>");
   		 $response = false;
   		 }
   		 
   	return $response;
}
function fauthenticateUser($client,$userID, $userPW){
	$return = false;
	echo $return;
	try{
		$response = $client->doAuthenticateUser(array("userid"=>$userID,
													  "password"=>$userPW,
													  )
												);
		$return = $response->return->userAuthenticated;
	} catch(SoapFault $fault) {
		echo("<p> class=SOAPError> SOAP Faolt in fauthenticateUser:{$failt->faultstring}</p>");
	}
	return $return;
}
function fdelUser($client, $userid) {
	 $userDeleted = false;
	 try {
        $response = $client->removeUser(array("userid"=>$userid));
        $userDeleted=true;          
    } catch (SoapFault $fault) {
        echo("<p class=SOAPError>SOAP Fault in fdelUser:{$fault->faultstring} </p>");
    }
    return $userDeleted;
}
///////////////////////////////////////////////////////////////////////////////////
///////////////    End User Handling  /////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
//  Profile Handling //
function fgetDeviceProfile($client, $profileid) {
	 try {
        $response = $client->getdeviceProfile(array("name"=>$profileid,
        											"returnedTags"=>array("name"=>"",
        																  "description"=>"",
        																  "model"=>"",
        																  "protocol"=>"",
        																  "pattern"=>"",
        																  "uuid"=>"")));           
    } catch (SoapFault $fault) {
        echo("<p class=SOAPError>SOAP Fault in fgetDeviceProfile:{$fault->faultstring} </p>");       
    }
    return $response;
}
function fcheckDeviceProfile($client, $profileid) {
	 $profileExists = false;
	 try {
        $response = $client->getdeviceProfile(array("name"=>$profileid));
        $profileExists = true;          
    } catch (SoapFault $fault) {
       //echo("SOAP Fault in fcheckDeviceProfile:{$fault->faultstring} <br/><br/>");       
    }
    return $profileExists;
}
function faddDeviceProfile($client, $userId, $description, $type, $protocol, $lineUuid, $e164, $dn){
		$myName = $userId;
		$myDescription = $description;
		$myType = $type;
		$myClass = "Device Profile";
		$myProduct = $myType;
		$myProtocol = $protocol;
		$myProtocolSide = "User";
		$myPhoneTemplateName = "Standard 7965 SCCP";
		$myLineCount = "1";
		$myUuid = $lineUuid;
		$userName = explode(" ",$myDescription);
		$firstName = $userName[0];
		$e164Mask = $e164;
		$myDN = $dn;
		$myUserId = $userId;	
		try {
		$response = $client->addDeviceProfile(array("deviceProfile"=>
                        array("name"=>$myName,
                                "description"=>$myDescription." Device Profile",
                                "product"=>$myProduct,
                                "class"=>$myClass,
                                "protocol"=>$myProtocol,
                                "protocolSide"=>$myProtocolSide,
                                "phoneTemplateName"=>array("_"=>$myPhoneTemplateName),
                                "softkeyTemplateName"=>array("_"=>"Standard User"),
                                "lines"=>array("line"=>array("index"=>$myLineCount,
                                							 "display"=>$myDescription,
                                							 "displayAscii"=>$myDescription,
                                							 "label"=>$firstName." - ".$myDN,
                                							 "e164Mask"=>$e164Mask,
                                							 "dirn"=>array(
                                							 			"pattern"=>"",
                                							 			"uuid"=>$myUuid)
                                							 )
        										)
        						)));
        	$result = true;
        	} catch (SoapFault $fault) {
        echo("<p class=SOAPError>SOAP Fault in faddDeviceProfile:{$fault->faultstring} </p>");
       		$result = false;
    	}
    	return $result;
}
function fupdateDeviceProfile($client, $userId, $description, $type, $protocol, $lineUuid, $e164, $dn){
		$myName = $userId;
		$myProduct = $myType;
		$myLineCount = "1";
		$myUuid = $lineUuid;
		$myDN = $dn;
		$myUserId = $userId;
		try {
		$response = $client->updateDeviceProfile(array("name"=>$myName,
                                					   "lines"=>array("line"=>array("index"=>$myLineCount,
                                							 		  				"dirn"=>array("pattern"=>"",
                                							 									   "uuid"=>$myUuid),
																					"associatedEndusers"=>array("enduser"=>array("userId"=>$myUserId))
                                							 						)
        																)
        						));
        	$result = true;
    	} catch (SoapFault $fault) {
        echo("<p class=SOAPError>SOAP Fault in fupdateDeviceProfile:{$fault->faultstring} </p>");
       		$result = false;
    	}
    	return $result;
}
function fgetDeviceProfileEnhanced($client, $profileid) {
	 try {
        $response = $client->getdeviceProfile(array("name"=>$profileid));           
    } catch (SoapFault $fault) {
        echo("<p class=SOAPError>SOAP Fault in fgetDeviceProfileEnhanced:{$fault->faultstring} </p>");       
    }
    return $response;
}
function fupdateDeviceProfileBLF($client, $userId, $blfArray){
		$myName = $userId;		
		try {
		$response = $client->updateDeviceProfile(array("name"=>$myName,
                                					   "busyLampFields"=>array("busyLampField"=>array($blfArray[0],
                                					   												  $blfArray[1],
                                					   												  $blfArray[2],
                                					   												  $blfArray[3],
                                					 								                  )
        																	  )
        												)
        										 );
        	$result = true;
        	} catch (SoapFault $fault) {
        echo("<p class=SOAPError>SOAP Fault in fupdateDeviceProfileBLF:{$fault->faultstring} </p>");
       		$result = false;
    	}
    	return $result;
}
function fdelDeviceProfile($client,$profileName ,$profileid) {
	 $profileDeleted = false;
	 try {
        $response = $client->removeDeviceProfile(array("name"=>$profileName,
														"uuid"=>$profileid));
        $profileDeleted = true;          
    } catch (SoapFault $fault) {
       //echo("SOAP Fault in fcheckDeviceProfile:{$fault->faultstring} <br/><br/>");       
    }
    return $profileDeleted;
}
//Line Handing
function faddLine($connection, $dn, $site, $user, $CSS){
			$myDN = $dn;
			$myLinePartition = $site."_INT_PAR";
			$myForwardsSearchSpace = $site."_FORWARDS_CSS";
			$myUser = $user;
  			$myCSS=$CSS;
			
			
			try {
			$response = $connection->addLine(array("line"=>
											array("pattern"=>$myDN,
           							 			  "routePartitionName"=>$myLinePartition,
           							 			  "description"=>($myUser),
           							 			  "alertingName"=>($myUser),
           							 			  "asciiAlertingName"=>($myUser),
           							 			  "usage"=>"Device",
           							 			  "active"=>true,
                                                  "shareLineAppearanceCssName"=>array("_"=>$myCSS),
           							 			  "voiceMailProfileName"=>array("_"=>$site."_VoiceMail"),
           							 			  "callForwardAll"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardBusy"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardBusyInt"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardNoAnswer"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardNoAnswerInt"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardNoCoverage"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardNoCoverageInt"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardOnFailure"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardNotRegistered"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardNotRegisteredInt"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace)
                                					)));
            $result = true;
            } catch(SoapFault $fault) {
        echo("<p class=SOAPError>SOAP Fault in faddLine:{$fault->faultstring} </p>");
       		$result = false;
    	}
    	return $result;
}
function faddUnassignedLine($connection, $dn, $site, $user, $CSS, $fwdDest){
			$myDN = $dn;
			$myLinePartition = $site."_INT_PAR";
			$myForwardsSearchSpace = $site."_FORWARDS_CSS";
			$myUser = $user;
  			$myCSS=$CSS;
			
			
			try {
			$response = $connection->addLine(array("line"=>
											array("pattern"=>$myDN,
           							 			  "routePartitionName"=>$myLinePartition,
           							 			  "description"=>($myUser),
           							 			  "alertingName"=>($myUser),
           							 			  "asciiAlertingName"=>($myUser),
           							 			  "usage"=>"Device",
           							 			  "active"=>true,
                                                  "shareLineAppearanceCssName"=>array("_"=>$myCSS),
           							 			  "voiceMailProfileName"=>array("_"=>$site."_VoiceMail"),
           							 			  "callForwardAll"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace,
																			"destination"=>$fwdDest),
           							 			  "callForwardBusy"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardBusyInt"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardNoAnswer"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardNoAnswerInt"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardNoCoverage"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardNoCoverageInt"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardOnFailure"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardNotRegistered"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace),
           							 			  "callForwardNotRegisteredInt"=>array("callingSearchSpaceName"=>$myForwardsSearchSpace)
                                					)));
            $result = true;
            } catch(SoapFault $fault) {
        echo("<p class=SOAPError>SOAP Fault in faddLine:{$fault->faultstring} </p>");
       		$result = false;
    	}
    	return $result;
}
function fgetLine($connection, $dn, $partition){
			$myDN = $dn;
			$myPartition = $partition;
		 try {
             $response = $connection->getline(array("pattern"=>$myDN,
             									"routePartitionName"=>$myPartition));           
         } catch (SoapFault $fault) {
             echo("<p class=SOAPError>SOAP Fault in fgetLine:{$fault->faultstring} </p>");       
         }
    return $response;
}	
function fgetLineId($connection, $dn, $partition){
			$myDN = $dn;
			$myPartition = $partition;
		 try {
             $response = $connection->getline(array("pattern"=>$myDN,
             									"routePartitionName"=>$myPartition
             									//"returnedTags"=>"uuid"
             									));           
         } catch (SoapFault $fault) {
             echo("<p class=SOAPError>SOAP Fault in fgetLineId:{$fault->faultstring} </p>");       
         }
    return $response;
}

function fdelLine($client, $dn, $partition) {
	 $lineExists = true;	 
	 try {
        $response = $client->removeLine(array("pattern"=>$dn,
        									"routePartitionName"=>$partition));
        $lineExists = false;
    } catch (SoapFault $fault) {
    }
    return $lineExists;
}			
function fupdateLineE164($connection, $dn, $e164, $ean){
			$myDN = $dn;
			$e164Partition = "E164";
			$eanPartition = "InterSite";
			$myE164 = $e164;
			$myEan = $ean;
			
			try {
			$response = $connection->updateLine(array("pattern"=>$myDN,
           											 "e164AltNum"=>array("numMask"=>$myE164,
           										  					     "isUrgent"=>true,
           										  					     "addLocalRoutePartition"=>true,
           										  					     "routePartition"=>array("_"=>$e164Partition),
           										  					     "advertiseGloballyIls"=>true),
													"enterpriseAltNum"=>array("numMask"=>$myEan,
           										  					     "isUrgent"=>true,
           										  					     "addLocalRoutePartition"=>true,
           										  					     "routePartition"=>array("_"=>$eanPartition),
           										  					     "advertiseGloballyIls"=>true)
                                					));
            $result = true;
            } catch(SoapFault $fault) {
        echo("<p class=SOAPError>SOAP Fault in fupdateLineE164:{$fault->faultstring} </p>");
       		$result = false;
    	}
    	return $result;
}
//Phone Handling //
function flistPhone($client){

	$returnedTags = array("name"=>"",
							"description"=>"",
							"model"=>"");
	$searchCriteria = array("devicePoolName"=>"Default");	
	try{
		$response = $client->listPhone(array("searchCriteria"=>$searchCriteria,
		 									 "returnedTags"=>$returnedTags
		 									 ));
		}catch (SoapFault $fault) {
   		    echo("<p class=SOAPError>SOAP Fault in flistPhone:{$fault->faultstring} </p>");
   		    $response = false;
   		} 
   	return $response;
}
function fdbPhoneInsert($dbh, $name, $description, $model, $uuid){
	$mydbh = $dbh;
	$myName = $name;
	$myDescription = $description;
	$myModel = $model;
	$myUuid = $uuid;
	$myIP = "X";
	try {
	$sql = ("INSERT INTO phones
			(devicename, description, model, uuid, ipaddress)
			VALUES (:devicename, :description, :model, :uuid, :IP)");
	$stmt = $mydbh->prepare($sql);
	$stmt->bindParam(":devicename", $myName);
	$stmt->bindParam(":description", $myDescription);
	$stmt->bindParam(":model", $myModel);
	$stmt->bindParam(":uuid", $myUuid);
	$stmt->bindParam(":IP", $myIP);
	$stmt->execute();
	} catch (PDOException $e) {
	echo ("PDOException Caught ".$e."<br>");
	}
}
//RIS Functions
function fgetRISPhones($connection){
	$myConnection = $connection;
	//$criteria['MaxReturnedDevices']="";
	$criteria['DeviceClass']="Phone";
	//$criteria['Class']="Phone";
	//$criteria['Model']="";
	$criteria['Status']="Registered";
	$criteria['NodeName']="";
	$criteria['SelectBy']="Name";
	$criteria['SelectItems']="";
	$criteria['Protocol']="Any";
	$criteria['DownloadStatus']="Any";
	
	try {												  
			/* $devices = $myConnection->SelectCmDevice("",array(
																"SelectBy" => "Name",
																"Status" => "Any"
																)
													); 
		
		*/
		$devices = $myConnection->selectCmDevice(array("StateInfo"=>"","CmSelectionCriteria"=>$criteria)); 
		} catch (SoapFault $fault){
		echo ("Fault in getRISPhones: ".$fault."<br>");
		}
		return $devices;
}
function fgetPhoneIP($dbh,$phoneInfo){
	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$counter=0;
	foreach($phoneInfo as $value){
		$counter=$counter+1;
		
		if (gettype($value)!=="object"){
			//echo "run ".$counter." is not an object<BR>";
		}else{
			//echo "run ".$counter." is an object<BR>";
			//echo "<pre>";
			//var_dump(get_object_vars($value));
			$phoneCount=count($value->item[0]->CmDevices->item);
			//echo "Number of Phones in here ".$phoneCount."<br>";
			for ($j=0; $j<=$phoneCount-1; $j++){		
				//echo "Device Name: ".$value->item[0]->CmDevices->item[$j]->Name."<br>";
				$myDeviceName = $value->item[0]->CmDevices->item[$j]->Name;
				//echo "Device IP: ".$value->item[0]->CmDevices->item[$j]->IPAddress->item->IP."<br>";
				$myIPAddress = $value->item[0]->CmDevices->item[$j]->IPAddress->item->IP;
				try {
					$sql="UPDATE phones SET ipaddress= :ipaddress WHERE devicename= :devicename;";
					$stmt = $dbh->prepare($sql);
					$stmt->bindParam(":ipaddress", $myIPAddress);
					$stmt->bindParam(":devicename", $myDeviceName);
					$stmt->execute();
				//	echo "DB updated <br>";
					} catch (PDOException $e) {
					echo ("SQL Error: ".$e->getMessage());
					}
				}
			//echo "</pre>";
		}		
	}
}
function fgetPhoneAddress($dbh,$phoneType){
	//$return[] ="Empty";
	$rowCount = 0;
	$model1 = "Cisco 7961%";
	$model2 = "Cisco 7962%";
	$model3 = "Cisco 7965";
	if ($phoneType == 2){
	$sql = "SELECT ipaddress FROM phones WHERE model LIKE :model1 OR model LIKE :model2";
	} else{
	$sql = "SELECT ipaddress FROM phones WHERE model LIKE :model3;";
	}
	try {
		$stmt = $dbh->prepare($sql);
		if ($phoneType == 2){
		$stmt->bindParam(":model1", $model1);
		$stmt->bindParam(":model2", $model2);
		} else{
		$stmt->bindParam(":model3", $model3);
		}
		$stmt->execute();
		//$updateCount = 0;
		while ($row=$stmt->fetchobject()){
			if($row->ipaddress === "X"){
				} else {
				$return[] = $row->ipaddress;
				//$updateCount =$updateCount+1;
				}
			}
		} catch (PDOException $e){
		echo "error: ".$e->getMessage();
		}
	return $return;
}
function floadLogins($fileName){
	$fd = fopen($fileName,"r");
	while(!feof($fd)){
		$fields = fgetcsv($fd);
		$rows[] = $fields;
	}
	return $rows;
}	
///////////////////////////////////////////////////////////////////////////////
////////////////     Extension Mobility    ////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
function fuserLogin($client,$userID,$profileID,$phoneMac){	 
    try{
        $response = $client->doDeviceLogin(array("deviceName"=>$phoneMac,
											"loginDuration"=>0,
											"profileName"=>$profileID,
											"userId"=>$userID
		 									 ));
	    }catch (SoapFault $fault) {
   		    echo("<p class=SOAPError>SOAP Fault in fuserLogin:{$fault->faultstring} </p>");
   		    $response = false;
   	    }	 
   	return $response;
}
function fuserLogout($client,$phoneMac){	 
	try{
		$response = $client->doDeviceLogout(array("deviceName"=>$phoneMac,
		 									 ));
	    }catch (SoapFault $fault) {
   		    echo("<p class=SOAPError>SOAP Fault in fuserLogout:{$fault->faultstring} </p>");
   		    $response = false;
   		} 
   	return $response;
}
function fgetPhoneByDN($client,$dn){
    try{
		$response = $client->executeSQLQuery(array("sequence"=>123456789,
													"sql"=>"SELECT device.name FROM device INNER JOIN devicenumplanmap ON devicenumplanmap.fkdevice=device.pkid INNER JOIN numplan ON devicenumplanmap.fknumplan=numplan.pkid WHERE numplan.dnorpattern LIKE '".$dn."%'"));
        } catch (SoapFault $fault){
		    echo("<p class SOAPError>SOAP Fault in fgetPhoneByDN:".$fault." </p>");
		    $response = false;
        }
return $response;
}
function fgetPhoneByLoggedInUser($client,$userID){
    try{
	    $response = $client->executeSQLQuery(array("sequence"=>987654321,
													"sql"=>"SELECT extensionmobilitydynamic.pkid, extensionmobilitydynamic.fkdevice, device.name AS devicename, extensionmobilitydynamic.fkenduser, enduser.userid AS endusername FROM extensionmobilitydynamic LEFT OUTER JOIN device ON extensionmobilitydynamic.fkdevice = device.pkid  LEFT OUTER JOIN enduser ON extensionmobilitydynamic.fkenduser = enduser.pkid  WHERE ((my_lower(enduser.userid::lvarchar) LIKE my_lower('".$userID."%')  ))"));
        } catch (SoapFault $fault){
		    echo("<p class SOAPError>SOAP Fault in fgetPhoneByLoggedInUser:".$fault." </p>");
		    $response = false;
        }
return $response;
}
///////////////////////////////////////////////////////////////////////////////
////////////////  EndExtensionMobility    /////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////
///////////////     Phone Image Handlers   ////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
function createPhoneStream(){
	$imageURL = "http://10.10.10.15/facebooklogo_w.png";
	$iconURL = "http://10.10.10.15/facebooklogo_w_y.png";
	$phoneIP = "10.10.10.167";
	//$myxml = "<setBackground><background><image>".$imageURL."</image><icon>".$iconURL."</icon></background></setBackground>";
	$myxml = "XML=<CiscoIPPhoneExecute><CiscoIPPhoneText><Text>POPUP MESSAGE 1!</Text></CiscoIPPhoneText></CiscoIPPhoneExecute>";
	$phoneWeb = "http://".$phoneIP."/CGI/Execute";
	$data = urlencode($myxml);
	$auth = base64_encode("paulha:password");
	$sendXML = "XML=".$myxml;
	 //$post="POST /CGI/Execute HTTP/1.1\r\n";
	 $post="POST /CGI/Execute HTTP/1.0\r\n";
     $post.="Host: $phoneIP \r\n";
     $post.="Authorization: Basic $auth \r\n"; 
     $post.="Connection: close \r\n"; 
     $post.="Content-Type: application/x-www-form-urlencoded \r\n";
     $post.="Content-Length: ".strlen($data)." \r\n\r\n";

     
	$response = "";
	$fp=fsockopen($phoneIP,80,$errno,$errstr,30);
	if(!$fp) {
	echo ("no socket");
	echo $errno."<br>";
	echo $errstr."<br>";	
	return false;
	}
	fputs($fp,$post.$data);
	flush();
	while(!feof($fp)){
		$response=fgets($fp,128);
		echo $response;
		flush();
		}
		fclose($fp);
		return $response;
}				  				
function fphoneImageLoader($userID,$userPW,$imageURL,$iconURL,$phoneIP){
$return=false;

$auth = base64_encode($userID.":".$userPW);

$post_data['XML'] = "<setBackground><background><image>".$imageURL."</image><icon>".$iconURL."</icon></background></setBackground>";
$encoded_data = http_build_query($post_data);
$curl_connection=curl_init('http://'.$phoneIP.'/CGI/Execute');
curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($curl_connection, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl_connection, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded','Authorization: Basic '.$auth));
curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $encoded_data);
$result = curl_exec($curl_connection);
$setOK=(curl_getinfo($curl_connection,CURLINFO_HTTP_CODE));
curl_close($curl_connection);
if ($setOK===200) {
	//echo "image set for: ".$phoneIP."<br>";
	$return=true;
	} else {
	echo "image not set ".$phoneIP."<br>";
	$return=false;
	} 
return $return;
}
///////////////////////////////////////////////////////////////////////////////////
////////////    End Phone Image Handlers   ////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////


?>