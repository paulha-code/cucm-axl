<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link type="text/css" rel="stylesheet" href="/css/myStyle.css"/>
        <title></title>
    </head>
    <body>
        <?php
		include("functionList.php");
		$formValid = false;
		$enduserId = $_POST['userId'];
	/*	if ($enduserId !=""){
        	echo ("<font color=green>");
			echo ("userID : ".$enduserId."<br>");
        	echo ("</font>");
        } else {
          	echo ("<font color=red>");
			echo ("userID : MISSING <br>");
        	echo ("</font>");
        }
     */   $dirNum = $_POST['dirNum'];
	/*	if ($dirNum !=""){
        	echo ("<font color=green>");
			echo ("Extension Number : ".$dirNum."<br>");
        	echo ("</font>");
        } else {
          	echo ("<font color=red>");
			echo ("Extension Number : MISSING <br>");
        	echo ("</font>");
        }
     */   $firstName=$_POST['firstName'];
	/*	if ($firstName !=""){
        	echo ("<font color=green>");
			echo ("First Name : ".$firstName."<br>");
        	echo ("</font>");
        } else {
          	echo ("<font color=red>");
			echo ("First Name : MISSING <br>");
        	echo ("</font>");
        }
     */   $lastName=$_POST['lastName'];
	/*	if ($lastName !=""){
        	echo ("<font color=green>");
			echo ("Last Name : ".$lastName."<br>");
        	echo ("</font>");
        } else {
          	echo ("<font color=red>");
			echo ("Last Name : MISSING <br>");
        	echo ("</font>");
        }
      */  $email=$_POST['email'];
	/*	if ($email !=""){
        	echo ("<font color=green>");
			echo ("Email : ".$email."<br>");
        	echo ("</font>");
        } else {
          	echo ("<font color=red>");
			echo ("Email : MISSING <br>");
        	echo ("</font>");
        }
    */   // $site=$_POST['site'];
        //echo ($site."<br>");
        //$e164=$_POST['e164'];
        //echo ($e164."<br>");
        $server = "192.168.10.21";
		$userId = "ucadmin";
		$password = "Opnet23!";
		$version = "14.0";
		//$profileName = $enduserId."_udp";
		//$userDisplay = $firstName." ".$lastName;
		//$profileDescription = $userDisplay;
		//$department = "Not Set";
		//$linePartition = $site."_INT_PAR";
		
		/*if($firstName =="" | $lastName =="" | $email =="" |$enduserId =="" | $dirNum ==""){
          echo ("Empty fields are not acceptable, try again!"."<br>");
          echo ("And this time do it properly!"."<br>");
          echo ("Check the fields above, if something is missing fill it in next time."."<br>");
        } else {*/
		//$testCSS="testCSS";
		//$testPartition="DVTC_p";
		//$CSSName = "NewCSS_css";
		//$CSSDescription = "Test add with partitions";
		//$partitionList = array ("DVTC_p","FVS_p");
		$client = fcreateMyConnection($server, $userId, $password);
		$description = "test load item";
		//$addCSS = faddCSS($client,$CSSName,$CSSDescription,$partitionList);
		//$CMGName = "testCMG";
		//$memberList = array("192.168.10.21","192.168.10.23");
		//$cmg = faddCallManagerGroup($client,$CMGName,$memberList);
		//$Node="imppub.w3ftm.com";
		//$myQuery = fcheckProcessNode($client,$Node);
		//var_dump ($myQuery);
		$trunkName = "Test-Trunk";
		$DevicePool ="SUB_Primary_dp";
		$inCSS = "inc_FVS_css";
		$destinations =array("192.168.13.41","192.168.13.51","192.168.13.61");
		$STSPname = "Non Secure SIP Trunk Profile";
		$SPname = "Hub-to-Node SIP Trunk Profile";
		$name = "HUB-Services";
		$host = "192.168.10.41";
		//$myQuery= faddsipTrunk($client,$trunkName,$description,$DevicePool,$inCSS,$destinations,$STSPname,$SPname);
		$myQuery = fcheckServiceProfile($client,$name);
		$object1 = "serviceProfile";
		$object2 = "name";
		$info = ($myQuery->$object1);
		var_dump ($info);

		//$css = faddCSS($connection,$testCSS,$testCSS);
		/*$exists = fcheckPartition($connection, $testPartition);
		if ($exists) {
				echo ("<p> Partition exists </p>");
			} else {
				echo ("<p> Partition is not here </p>");
			};*/
		//$itemlist = flistServiceProfile($connection,);
		//echo ("<p> There are ".sizeof($itemlist)." Items</p>");
		//foreach ($itemlist as $item){ 
		//	echo("<p> Item name:  ".$item."</p>"); 
		//}
		
		
 
 /*
 // First see if the user Exists.  If the User exists we get the user and display the UserID, FirstName, LastName and Email.
 // If the User does not exist we add them and then display the UserID, FirstName, LastName and Email.       
        $userExists = fcheckUser($connection, $enduserId);
        if ($userExists){
			$user = fgetUser($connection, $enduserId);
			echo ("<p class=itemExists>User already exists</p>");
			echo ("<p retrievedItem>UserName: ".$user->return->user->userid."</p>");
			echo ("<p retrievedItem>First Name: ".$user->return->user->firstName."</p>");
			echo ("<p retrievedItem>Last Name: ".$user->return->user->lastName."</p>");
			echo ("<p retrievedItem>email: ".$user->return->user->mailid."</p>");
		} else {
			$userAdded = faddUser($connection, $firstName, $lastName, $enduserId, $email, $department);
			echo ("<p class=itemAdded>User Added = ".$userAdded."</p>");
			$user = fgetUser($connection, $enduserId);
			echo ("<p retrievedItem>UserName: ".$user->return->user->userid."</p>");
			echo ("<p retrievedItem>First Name: ".$user->return->user->firstName."</p>");
			echo ("<p retrievedItem>Last Name: ".$user->return->user->lastName."</p>");
			echo ("<p retrievedItem>email: ".$user->return->user->mailid."</p>");
		}

// Here we check to see if a line exists with the provided DN.  If it does we Display the DN and the DisplayName on the Line.
// If the Line does not exist, we create it and then Display the DN and DisplayName on the line.			
		$lineExists = fcheckLine($connection, $dirNum, $linePartition);
		if ($lineExists){
			$line = fgetLineId($connection, $dirNum, $linePartition);
            echo ("<p class=itemExists>Line already exists</p>");
			echo ("<p retrievedItem>Line DN: ".$line->return->line->pattern."</p>");
			echo ("<p retrievedItem>Line Alerting Name: ".$line->return->line->alertingName."</p>");
			$lineUuid = $line->return->line->uuid;
		} else {
			$lineAdd = faddLine($connection, $dirNum, $site, $userDisplay, $CSS);
			echo ("<p class=itemAdded>Line added = ".$lineAdd."</p>");
			$line = fgetLineId($connection, $dirNum, $linePartition);
			echo ("<p retrievedItem>Line DN: ".$line->return->line->pattern."</p>");
			echo ("<p retrievedItem>Line Alerting Name: ".$line->return->line->alertingName."</p>");
			$lineUuid = $line->return->line->uuid;
		}
			
// Here we check if the DeviceProfile exists.  If it exists we display the Name, Description, Model, Protocol and DN on line 1.
// If the DeviceProfile does not exist, we create it and then display the Name, Description, Model, Protocol and DN on line 1.
		$profileExists = fcheckDeviceProfile($connection, $profileName);
		if ($profileExists){
			$profile = fgetDeviceProfile($connection, $profileName);
            echo ("<p class=itemExists>DeviceProfile already Exists</p>");
			echo ("<p retrievedItem>Profile Name: ".$profile->return->deviceProfile->name."</p>");
			echo ("<p retrievedItem>Profile Description: ".$profile->return->deviceProfile->description."</p>");
			echo ("<p retrievedItem>Profile Type: ".$profile->return->deviceProfile->model."</p>");
			echo ("<p retrievedItem>Protocol: ".$profile->return->deviceProfile->protocol."</p>");
			echo ("<p retrievedItem>Line Number: ".$profile->return->deviceProfile->lines->line->dirn->pattern."</p>");
			$dpUUID = $profile->return->deviceProfile->uuid;
		} else {
			$profileAdd = faddDeviceProfile($connection,  $enduserId, $profileDescription, "Cisco 7961", "SCCP", $lineUuid, $e164,$dirNum);
			echo ("<p class=itemAdded>Profile added = ".$profileAdd."</p>");
			$profile = fgetDeviceProfile($connection, $profileName);
			echo ("<p retrievedItem>Profile Name: ".$profile->return->deviceProfile->name."</p>");
			echo ("<p retrievedItem>Profile Description: ".$profile->return->deviceProfile->description."</p>");
			echo ("<p retrievedItem>Profile Type: ".$profile->return->deviceProfile->model."</p>");
			echo ("<p retrievedItem>Protocol: ".$profile->return->deviceProfile->protocol."</p>");
			echo ("<p retrievedItem>Line Number: ".$profile->return->deviceProfile->pattern."</p>");
			$dpUUID = $profile->return->deviceProfile->uuid;
		}

	   $update=fupdateUserProfile($connection, $enduserId, $dpUUID);
	   $primExtUpdate=fupdateUserPrimaryExtension($connection, $enduserId,$dirNum,$linePartition);
		echo ("<p class=itemUpdated>Controlled Profile updated:".$update.", </p>");
		echo ("<p class=itemUpdated>Primary Extension updated:".$primExtUpdate."</p>");
		$lineE164 = fupdateLineE164($connection, $dirNum, $linePartition, $e164);
		echo ("<p class=itemUpdated>Line E164 Updated = ".$lineE164."</p>");
		$profileUpdate = fupdateDeviceProfile($connection,  $enduserId, $profileDescription, "Cisco 7961", "SCCP", $lineUuid, $e164,$dirNum);
		}
		*/
		?>
		
	</body>
</html>