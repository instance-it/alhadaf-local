<?php 
//echo '1';
require __DIR__.'/vendor/autoload.php';
require_once 'IISMethods.php';
require_once 'ErrorLog.php';
require_once 'phpmailer.php';

error_reporting(1);

use \Firebase\JWT\JWT;
$privateKey = file_get_contents(__DIR__.'/jwt-token-keys/private_alhadaf.pem');
$publicKey = file_get_contents(__DIR__.'/jwt-token-keys/public_alhadaf.pem');	

class DB {

    private $DBName;
    private $DBUser;
    private $DBHost;
    private $DBPass;
    private $DBPort;
	private $DBConn;
	private $DBType;
	private $errmsg;
	private $errorlog=array();

	
	public function Connect()
	{
		
		$config = new config();
		$errmsg=$config->getErrmsg();
		try
		{
			if($this->DBType=='MSSQL')
			{	
				$this->DBConn = new PDO("sqlsrv:Server=".$this->DBHost.",".$this->DBPort.";Database=".$this->DBName,$this->DBUser,$this->DBPass);
				$this->DBConn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			}
			else if($this->DBType=='MYSQL')
			{
				$this->DBConn = new PDO("mysql:host=".$this->DBHost.",".$this->DBPort.";dbname=".$this->DBName, $this->DBUser, $this->DBPass);
			}
			else if($this->DBType=='HANA')
			{
				$this->DBConn = new PDO($this->DBHost, $this->DBUser, $this->DBPass);
			}
		}
		catch ( Exception $e )
		{
			die( print_r($e->getMessage()));
		}
	}

	public function begintransaction()
	{
		$this->DBConn->beginTransaction();
	}

	public function committransaction()
	{
		$this->DBConn->commit();
		self::insertlogdata();
	}

	public function rollbacktransaction($e)
	{
		$this->DBConn->rollback();
		$errorcode=0;
		if($this->DBType=='MYSQL') // MYSQL DATABSE
		{
			$errorcode=$e->getCode();
		}
		else if($this->DBType=='MSSQL') // MSSQL DATABSE
		{
			$errorcode=$e->errorInfo[1];
			self::insertlogdata();
		}

		$dberrmsg=$errmsg['dberror'].$errorcode;
		return $dberrmsg;
	}

	public function sapexecute($qry)
	{
		$this->DBConn->prepare($qry)->execute(array());
	}
	
	

	public function puthistory($LoginInfo)
	{
		$config = new config();
		// print_r($LoginInfo);
		$uid =  $LoginInfo->getUid();
		if(!$uid)
		{$uid = 'guest';}
		$IISMethods = new IISMethods();
		/*-------------Start History Table data insert ------------- */ 
		$ipaddress=$_SERVER['REMOTE_ADDR'];
		$useragent=$_SERVER['HTTP_USER_AGENT'];
		$logdatetime=$IISMethods->getdatetime();	
		$url=$_SERVER['PHP_SELF'];
		$hdata='';
		foreach ($_REQUEST as $key => $value)
		$hdata.= htmlspecialchars($key)."|".htmlspecialchars($value).",";
		$data=array(
			'ipaddress'=>$ipaddress,
			'platform'=>$useragent,
			'datetime'=>$logdatetime,
			'data'=>$hdata,
			'url'=>$url,
			'uid'=>$uid,
			'session'=>json_encode($_SESSION[$config->getSessionName()],2),

		);

		$datakeys = implode(', ', array_map(
			function ($v, $k) {        
				return $k;       
			}, 
			$data, 
			array_keys($data)
		));

		$dataval = implode(', ', array_map(
			function ($v, $k) {        
				return ':'.$k;       
			}, 
			$data, 
			array_keys($data)
		));

		$strsql = "INSERT INTO tblhistory ($datakeys) VALUES ($dataval)";	
		// print_r($data);
		// exit;
		$this->DBConn->prepare($strsql)->execute($data);	

		/*-------------End History Table data insert ------------- */ 
	}



function executedata($operation,$tblname,$data,$extra=array()) // $operation=i,u,d  $tblname=table name    $data=array of data  $extra= extra id parameter with value, 
{
	

	$mainary=array();
    if($this->DBType=='MYSQL') // MYSQL DATABSE
	{
	

		//create schema query
		$qryschema="SELECT EXTRA,COLUMN_NAME,ORDINAL_POSITION,COLUMN_DEFAULT,IS_NULLABLE,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH,IFNULL(COLUMN_DEFAULT,'') AS DEFAULT_DATA
		FROM information_schema.columns 
		WHERE table_schema = ? AND table_name =? AND EXTRA<>?";

			/********************************************* */
		$result =$this->DBConn->prepare($qryschema); 		
		$result->execute(array($this->DBName,$tblname,'auto_increment'));
		
		while ($row = $result -> fetch())  // loop of columns of given table
		{			
			if(array_key_exists($row['COLUMN_NAME'], $data)) // check if columns is exist in our array
			{
				$mainary[$row['COLUMN_NAME']]=$data[$row['COLUMN_NAME']];
				if($row['CHARACTER_MAXIMUM_LENGTH'] > 0) //check if column has maximum length
				{
					$mainary[$row['COLUMN_NAME']]=substr($data[$row['COLUMN_NAME']], 0, $row['CHARACTER_MAXIMUM_LENGTH']);  // get only maximum length data
				}
			}
			else
			{
				if($row['DEFAULT_DATA']) // check if column has default value
				{
					$mainary[$row['COLUMN_NAME']]=$row['DEFAULT_DATA'];
				}
				else
				{
					$mainary[$row['COLUMN_NAME']]=$mysqldefaultvalue[$row['DATA_TYPE']];
				}
			}
		}

	}
	else  if($this->DBType=='MSSQL') // MSSQL DATABASE
	{
	

		$excludedatatype="timestamp,varbinary,hierarchyid,image,binary,geography,geometry";  //exclude these data type from schema query
		$excludedatatype=explode(',',$excludedatatype); // string to array
		$placeholders = rtrim(str_repeat('?, ', count($excludedatatype)), ', ') ; // create ? string
		array_unshift($excludedatatype,str_replace('[','',str_replace(']','',$tblname))); //  push $tblname in $excludedatatype array in 0th position
		array_unshift($excludedatatype,str_replace('[','',str_replace(']','',$tblname))); //  push $tblname in $excludedatatype array in 0th position
		
		//create schema query
		$qryschema="select '['+c.COLUMN_NAME+']' as columnname,c.ORDINAL_POSITION,c.COLUMN_DEFAULT,c.IS_NULLABLE,c.DATA_TYPE,c.CHARACTER_MAXIMUM_LENGTH,ISNULL(c.COLUMN_DEFAULT,'') as DEFAULT_DATA 
		from INFORMATION_SCHEMA.COLUMNS c 
		INNER JOIN sys.columns s on OBJECT_NAME(s.object_id) = ? AND s.name=c.COLUMN_NAME
		where c.TABLE_NAME=? AND s.is_computed=0 AND s.is_identity=0 AND c.DATA_TYPE NOT IN ($placeholders)";

		$result =$this->DBConn->prepare($qryschema); 		
		$result->execute($excludedatatype);
	
		while ($row = $result -> fetch())  // loop of columns of given table
		{	
			
			$COLUMN_NAME=$row['columnname'];
			if(strpos($COLUMN_NAME, '[') !== true)
			{
				//$COLUMN_NAME='['.$COLUMN_NAME.']';
			}

			if(array_key_exists($COLUMN_NAME, $data)) // check if columns is exist in our array
			{
				$mainary[$COLUMN_NAME]=$data[$COLUMN_NAME];
				if($row['CHARACTER_MAXIMUM_LENGTH'] > 0) //check if column has maximum length
				{
					$mainary[$COLUMN_NAME]=substr($data[$COLUMN_NAME], 0, $row['CHARACTER_MAXIMUM_LENGTH']);  // get only maximum length data
				}
			}
			else
			{
				/*
				if($row['DEFAULT_DATA']) // check if column has default value
				{
					$mainary[$COLUMN_NAME]=$row['DEFAULT_DATA'];
				}
				else
				{
					$mainary[$COLUMN_NAME]=$sqldefaultvalue[$row['DATA_TYPE']];
				}
				*/

				if($row['IS_NULLABLE'] == 'NO' && $row['DEFAULT_DATA']=='') // check if column has allow null value
				{
					$mainary[$COLUMN_NAME]=$sqldefaultvalue[$row['DATA_TYPE']];
				}
				else if($row['IS_NULLABLE'] == 'NO' && $row['DEFAULT_DATA']!='')
				{
					$mainary[$COLUMN_NAME]=$row['DEFAULT_DATA'];
				}
				else if($row['IS_NULLABLE'] == 'YES' && $row['DEFAULT_DATA']!='')
				{
					//$mainary[$COLUMN_NAME]=$row['DEFAULT_DATA'];
				}
				
			}
		}
	}
	//return $mainary;	

	$datakeys = implode(', ', array_map(
	    function ($v, $k) {        
	            return $k;       
	    }, 
	    $mainary, 
	    array_keys($mainary)
	));
	
	
	$dataval = implode(', ', array_map(
	    function ($v, $k) {        
	            return ':'.$k;       
	    }, 
	    $mainary, 
	    array_keys($mainary)
	));

	$updatedata = implode(', ', array_map(
	    function ($v, $k) {
	       return $k."=?";      
	    }, 
	    $data, 
	    array_keys($data)
	));

	
	$updatedatacond = implode(' AND ', array_map(
		function ($v, $k) {
		   return $k."=?";      
		}, 
		$extra, 
		array_keys($extra)
	));
	
	$ques=str_repeat('?,', sizeof($mainary)); //create string like '?,?,?,?,?' as per array length for insert values
	$ques=rtrim($ques,',');
	$newdata=array_values($mainary); // create only values array from associative array
	$sql="";	

	if($operation=='i')
	{
		$sql = "INSERT INTO $tblname ($datakeys) VALUES ($ques)";		
	}
	else if($operation=='u')
	{
		$maindata=array_values($data);
			
		$datacond=array_values($extra);
		$newdata=array_merge($maindata,$datacond);

		$mainary=array_merge($data,$extra);
		//print_R($newdata);
		$sql = "UPDATE $tblname SET $updatedata WHERE $updatedatacond";
		
		//print_r($mainary);
	}	
	else if($operation=='d')
	{
		$sql = "DELETE FROM $tblname WHERE $updatedatacond";
		$newdata=array_values($extra);
		$mainary=array();
	}

	$Errlog = new ErrorLog();

	try
    {
		$Errlog->setTblname($tblname);
		$Errlog->setSqlqry($sql);
		$Errlog->setMainarray($mainary);
		$Errlog->setOpration($operation);
		$this->DBConn->prepare($sql)->execute($newdata);
	
		$errorlog= self::getErrorlog();
		$errorlog[] = $Errlog;
		self::setErrorlog($errorlog);
	  
	}
    catch(PDOException $e)
    {
		

		//return $e;
		$errorcode=0;
		if($this->DBType=='MYSQL') // MYSQL DATABSE
		{
			$errorcode=$e->getCode();
		}
		else if($this->DBType=='MSSQL') // MSSQL DATABSE
		{
			$errorcode=$e->errorInfo[1];
		}
		$dberrmsg=$errmsg['dberror'].$errorcode;	

		$Errlog->setErrormsg($e->getMessage());
		$Errlog->setErrorcode($errorcode);
		
		$errorlog = self::getErrorlog();
		$errorlog[] = $Errlog;
		self::setErrorlog($errorlog);
		throw $e;
		
    }	
}

public function insertlogdata()
{

	$errlog=self::getErrorLog();
	
	for($e=0;$e<sizeof($errlog);$e++)
	{

		//insert data into log table
		$IISMethods = new IISMethods(); 
		$pagename=explode("/",$_SERVER['PHP_SELF']);
		$cnt=count($pagename)-1;
		$page=$pagename[$cnt];
		
		$logdatetime=$IISMethods->getdatetime();   
		$useragent=$_SERVER['HTTP_USER_AGENT'];
		$ipaddress=$_SERVER['REMOTE_ADDR'];
		
		$logsql="INSERT INTO tbllog (tblname, strquery, operation,errorcode,errormsg, pagename, platform, ipaddress, username, logdatetime,sessionary) VALUES (:tblname,:strquery,:operation,:errorcode,:errormsg,:pagename,:platform,:ipaddress,:username,:logdatetime,:sessionary)";
		
		$logdata = array(
			'tblname' => (string)$errlog[$e]->getTblname(),
			'strquery' => (string)$errlog[$e]->getSqlqry().' | '.json_encode($errlog[$e]->getMainarray()),
			'operation' => (string)$errlog[$e]->getOpration(),
			'errorcode' => (string)$errlog[$e]->getErrorcode(),
			'errormsg' => (string)$errlog[$e]->getErrormsg(),
			'pagename' => (string)$page,
			'platform' => (string)$useragent,
			'ipaddress' => (string)$ipaddress,
			'username' => '', // put username from session
			'logdatetime' => $logdatetime,
			'sessionary'=>'', // Put Session Array
		);
	
		//$this->DBConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT); commentedd by chirag on 20/10/2021
		$this->DBConn->prepare($logsql)->execute($logdata);
		
		//Insert Error Data
		if($errlog[$e]->getErrorcode() != '')
		{
			$logsql="INSERT INTO tblcruderrorlog (tblname, strquery, operation,errorcode,errormsg, pagename, platform, ipaddress, username, logdatetime,sessionary) VALUES (:tblname,:strquery,:operation,:errorcode,:errormsg,:pagename,:platform,:ipaddress,:username,:logdatetime,:sessionary)";
		
			$logdata = array(
				'tblname' => (string)$errlog[$e]->getTblname(),
				'strquery' => (string)$errlog[$e]->getSqlqry().' | '.json_encode($errlog[$e]->getMainarray()),
				'operation' => (string)$errlog[$e]->getOpration(),
				'errorcode' => (string)$errlog[$e]->getErrorcode(),
				'errormsg' => (string)$errlog[$e]->getErrormsg(),
				'pagename' => (string)$page,
				'platform' => (string)$useragent,
				'ipaddress' => (string)$ipaddress,
				'username' => '',
				'logdatetime' => $logdatetime,
				'sessionary'=>'',
			);
			//$this->DBConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT); commentedd by chirag on 20/10/2021
			$this->DBConn->prepare($logsql)->execute($logdata);
		}
	}
	
	
	
}

function getmenual($str,$params=array(),$class=null,$isnoreturn=1)
{

	if(($this->DBType=='MYSQL')) // mysql database
	{
		$this->DBConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
		$result =$this->DBConn->prepare($str); 
		$result->execute($params); 
		if($class)
		{	
			$result_ary= $result->fetchAll(PDO::FETCH_CLASS, $class);
		}
		else
		{	
			$result_ary= $result->fetchAll();
		}
		return $result_ary;
	}
	else if ($this->DBType=='MSSQL')
	{
		
		$result =$this->DBConn->prepare($str);
		$result->execute($params);	
		if($class)
		{	
			$result_ary= $result->fetchAll(PDO::FETCH_CLASS,$class);
		}
		else
		{	
			if($isnoreturn == 1)
			{
				$result_ary= $result->fetchAll();
			}
			else
			{
				$result_ary= $result;
			}
			
		}
		return $result_ary;
	}
	else if ($this->DBType=='HANA')
	{
		
		$result =$this->DBConn->prepare($str);
		$result->execute($params);	
		if($class)
		{	
			$result_ary= $result->fetchAll(PDO::FETCH_CLASS,$class);
		}
		else
		{	
			if($isnoreturn == 1)
			{
				$result_ary= $result->fetchAll();
			}
			else
			{
				$result_ary= $result;
			}
			
		}
		return $result_ary;
	}
}


function getmenual1($str,$params=array(),$class=null)
{

	if(($this->DBType=='MYSQL')) // mysql database
	{
		$this->DBConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
		$result =$this->DBConn->prepare($str); 
		$result->execute($params); 
		if($class)
		{	
			$result_ary= $result->fetchAll(PDO::FETCH_CLASS, $class);
		}
		else
		{	
			$result_ary= $result->fetchAll();
		}
		return $result_ary;
	}
	else if ($this->DBType=='MSSQL')
	{
		$result =$this->DBConn->prepare($str);
		$result->execute($params);	
		return $result;
	}
}



function getautoid($tblname){
	
	if ($this->DBType=='MSSQL')
	{
		//SELECT IDENT_CURRENT('table_name')+1; 

		$str="SELECT IDENT_CURRENT('$tblname') as Auto_increment,(Select count(*) from $tblname) as cnt; "; 
		$result = $this->DBConn->prepare($str, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$result->execute(); 
		$row=$result->fetch();
		if($row['Auto_increment']=='1' && $row['cnt']==0)
		{
			return $row['Auto_increment'];
		}
		else{
			return $row['Auto_increment']+1;
		}
		

	}
	else if ($this->DBType=='MYSQL')
	{
		$str="SHOW TABLE STATUS WHERE `Name` = '$tblname'";
		$result =$this->DBConn->prepare($str, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL)); 
		$result->execute(); 
		$row=$result->fetch();
		return $row['Auto_increment'];

	}	
}


function getjwt($uid,$unqkey,$iss,$useragent,$expmin=120)
{
  	global $privateKey;
	$IISMethods = new IISMethods();  
	$resp=array();  
	$status=0;
	$token=''; 
	
	if($iss && $uid && $unqkey && $iss && $useragent)
	{
		$issuedAt   = new DateTimeImmutable();
		$expire     = $issuedAt->modify('+'.$expmin.' minutes')->getTimestamp();    

		$payload = [
			'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
			'iss'  => $iss,                       // Issuer
			'nbf'  => $issuedAt->getTimestamp(),         // Not before
			'exp'  => $expire,                           // Expire
			'uid' =>$uid,                     // unique identifier
			'unqkey'=>$unqkey,
			'useragent'=>$useragent							//unqkey
		];
		//print_r($payload);
		$token = JWT::encode($payload, $privateKey, 'RS256');

		$unqid = $IISMethods->generateuuid();
		$unq_ary=array(
			'[id]'=>$unqid,
			'[unqkey]'=>$unqkey,
			'[uid]'=>$uid,
			'[iss]'=>$iss,
			'[useragent]'=>$useragent,
			'[exp]'=>$expire,
			'[entry_date]'=>$IISMethods->getdatetime(),
			'[isvalid]'=>1
		);
		$this->executedata('i','tblexpiry',$unq_ary,'');
		$status=1;
	}
	
	$resp['status']=$status;
    $resp['token']=$token;
    return $resp;
}

function validatejwt($token,$uid,$unqkey,$iss,$useragent)
{
  	global $publicKey;

	$resp=array();
	$status=0;

	//echo $uid.' <br> '.$unqkey.' <br> '.$iss.' <br> '.$useragent.' <br> '.$token;
		
	//echo $token.'-------'.$uid.'----------'.$unqkey.'--------'.$iss.'-------'.$useragent;

	// print_r($token);

	try
	{	
		// echo $publicKey;
		// print_r($token);
	  	$decoded = JWT::decode($token, $publicKey, ['RS256']);
		 
	  	$decoded_array = (array) $decoded;

		// print_r($decoded_array);

		// echo '<br>'.$useragent.'<br>';
		// echo $decoded_array;

		//echo $decoded_array['iss'].' == '.$iss.' ******* '.$decoded_array['uid'].' == '.$uid.' ******* '.$decoded_array['unqkey'].' == '.$unqkey.' ******* '.$decoded_array['useragent'].' == '.$useragent.' ******* ';

		if($decoded_array['iss'] == $iss && $decoded_array['uid'] == $uid  && $decoded_array['unqkey'] == $unqkey && $decoded_array['useragent'] == $useragent )
		{
			
			$qrychk="SELECT id from tblexpiry where unqkey=:unqkey and uid=:uid and iss=:iss and useragent=:useragent and isvalid=:isvalid";
			$parms = array(
				':unqkey'=>$unqkey,
				':uid'=>$uid,
				':iss'=>$iss,
				':useragent'=>$useragent,
				':isvalid'=>1,
			);
			$result_ary=$this->getmenual($qrychk,$parms);

			if(sizeof($result_ary) > 0)
			{
				$status=1;
				$msg=$errmsg['tokenvalidate'];
			}
			else
			{
				$status=-1;
				$msg=$errmsg['invalidtoken'];
			}
		}
		else
		{
			$msg=$errmsg['invalidtoken'];
		}
	}
	catch(\Firebase\JWT\ExpiredException $e)
  	{
		
		$msg=$e->getMessage();
        $issuedAt   = new DateTimeImmutable();
        //Generate For New Token  (1 : Generate New Token, -1 : Logoup App)
        $unqkey= IISMethods::generateuuid();
        $key=$this->getjwt($uid,$unqkey,$iss,$useragent);	
        $token = '';
        $status=0;
        if($key['status']==1){
            $token = $key['token'];
            $status=1;
            $msg = '';
        }else{
            $msg = $errmsg['invalidtoken'];
        }	
        $resp['unqkey']=$unqkey;
        $resp['key']=$token;
	
	} 
	catch(Exception $e)
	{
	  	$msg=$e->getMessage();
	  	$status=-1;
	}
  
    $resp['status']=$status;
	$resp['message']=$msg;
	
	return $resp;
}


function validateuser($auid,$platform,$key,$unqkey,$iss,$useragent,$userpagename,$useraction,$masterlisting,$action,$formdataactionarr)
{
	$config = new config();
	$IISMethods = new IISMethods();

	$errmsg=$config->getErrmsg();	

	$checkuser=array();
	
	//echo 'test--'.$useragent;
	//echo $auid.' ** '.$unqkey.' ** '.$iss.' ** '.$useragent.' ** '.$key;

	// guestuser-aqfl45chb5oumm1gr050hf97c3 ** acceptcookie ** Array



	$iskeycheck=$this->validatejwt($key,$auid,$unqkey,$iss,$useragent);

	
	$status=$iskeycheck['status'];
	$message=$iskeycheck['message'];

	// print_r($iskeycheck);
	
	if($iskeycheck['status'] == 1) 
	{
		// echo $auid;
		if(strpos($auid, 'guestuser') !== false && (strpos($action, 'list') !== false || in_array($action,$formdataactionarr)) )  //For Website User Without login
		{
			$checkuser['status']=1;
			$checkuser['message']=$errmsg['uservalidate'];
		}
		else
		{	
			
			$str="select pm.* ,
				ISNULL(SUBSTRING((select ','+ cast(cm.cmpid as varchar(50)) FROM tblpersoncmp cm WHERE CONVERT(VARCHAR(255), cm.pid)=pm.id FOR XML PATH ('')),2,1000),'') as cmpid,
				ISNULL(SUBSTRING((select ','+ cast(pu.utypeid as varchar(50)) FROM tblpersonutype pu WHERE CONVERT(VARCHAR(255), pu.pid)=pm.id FOR XML PATH ('')),2,1000),'') as usertypeid
				from tblpersonmaster pm 
				where pm.id='$auid' and pm.isactive=1";	
			$result = $this->DBConn->prepare($str, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));		
			$result->execute();
			$row=$result->fetch();
			if($row['isactive']==1)
			{
			
				//if(($userpagename && $useraction) || ($masterlisting && strpos($action, 'list') !== false))
				// echo $userpagename.'----'.$useraction.'----'.$masterlisting;
				// if(($userpagename && $useraction) || $masterlisting
				if(($userpagename && $useraction) || $masterlisting=='true')
				{
					
					//if($IISMethods->checkutypeexist($config->getAdminutype(),$row['usertypeid'])==0 || strpos($action, 'list') === false || !in_array($action,$formdataactionarr))
					if($IISMethods->checkutypeexist($config->getAdminutype(),$row['usertypeid'])==0)
					{ 
						$usertypeid=$row['usertypeid'];
						$qryright="select formname,formnametext,
							CASE WHEN(SUM(viewright)=0) THEN 0 ELSE 1 END AS viewright,
							CASE WHEN(SUM(allviewright)=0) THEN 0 ELSE 1 END AS allviewright,
							CASE WHEN(SUM(selfviewright)=0) THEN 0 ELSE 1 END AS selfviewright,
							CASE WHEN(SUM(addright)=0) THEN 0 ELSE 1 END AS addright,
							CASE WHEN(SUM(alladdright)=0) THEN 0 ELSE 1 END AS alladdright,
							CASE WHEN(SUM(selfaddright)=0) THEN 0 ELSE 1 END AS selfaddright, 
							CASE WHEN(SUM(editright)=0) THEN 0 ELSE 1 END AS editright, 
							CASE WHEN(SUM(alleditright)=0) THEN 0 ELSE 1 END AS alleditright,
							CASE WHEN(SUM(selfeditright)=0) THEN 0 ELSE 1 END AS selfeditright,
							CASE WHEN(SUM(delright)=0) THEN 0 ELSE 1 END AS delright, 
							CASE WHEN(SUM(alldelright)=0) THEN 0 ELSE 1 END AS alldelright,
							CASE WHEN(SUM(selfdelright)=0) THEN 0 ELSE 1 END AS selfdelright,
							CASE WHEN(SUM(printright)=0) THEN 0 ELSE 1 END AS printright,
							CASE WHEN(SUM(allprintright)=0) THEN 0 ELSE 1 END AS allprintright,
							CASE WHEN(SUM(selfprintright)=0) THEN 0 ELSE 1 END AS selfprintright,
							CASE WHEN(SUM(requestright)=0) THEN 0 ELSE 1 END AS requestright,
							CASE WHEN(SUM(changepriceright)=0) THEN 0 ELSE 1 END AS changepriceright from tbluserrights 
							where formname='$userpagename' ";

						/*if(sizeof($reschk)>0)
						{
							$qryright.=" personid='$auid'";
						}	
						else
						{
							$qryright.=" usertypeid IN ('$usertypeid') AND personid =''0''";
						}*/

						$qryright.=" group by formname,formnametext";
						$resright = $this->DBConn->prepare($qryright, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));		
						$resright->execute();
						$rowright=$resright->fetch();

						if($rowright[$useraction]==1)
						{
							$checkuser['status']=1;
							$checkuser['message']=$errmsg['uservalidate'];
						}
						else
						{
							$checkuser['status']=0;
							$checkuser['message']=$errmsg['userright'];
						}
					}
					else
					{
						$checkuser['status']=1;
						$checkuser['message']=$errmsg['uservalidate'];
					}
				}
				else
				{
					$checkuser['status']=1;
					$checkuser['message']=$errmsg['uservalidate'];
				}
			}
			else
			{
				$checkuser['status']=-1;
				$checkuser['message']=$errmsg['deactivate'];
			}
		}	
	}
	else
	{
		$checkuser['status']=0;
		$checkuser['message']=$errmsg['error'];
	}

	//print_r($checkuser);

	return $checkuser;
}

function getdatafromsp($spname,$parms=[],$class=null,$isnoreturn=1)
{
	$strparams = implode(', ', array_map(
	    function ($v, $k) {
	       return "@".$k."='".$v."'";      
	    }, 
	    $parms, 
	    array_keys($parms)
	));
	
	$str='EXEC '.$spname.' '.$strparams;

	$result =$this->DBConn->prepare($str);
	$result->execute();	
	if($class)
	{	
		$result_ary= $result->fetchAll(PDO::FETCH_CLASS, $class);
	}
	else
	{
		if($isnoreturn == 1)
		{
			$result_ary= $result->fetchAll();
		}
		else
		{
			$result_ary= $result;
		}	
	}
	return $result_ary;
}

function getuserright($uid,$usertype,$type,$class=null)
{

	$config = new config();
	$IISMethods = new IISMethods();

	$qrychk="SELECT id from tbluserrights where personid=:personid ";
	$parms = array(
		':personid'=>$uid
	);
	$result_ary=self::getmenual($qrychk,$parms);

	//if($IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1)
	if($uid == $config->getAdminUserId())
	{
		$parms = array(
			':type'=>$type
		);
		$result_ary=self::getmenual('SELECT alias as formname,textname as formnametext,1 as viewright,1 as addright,1 as editright,1 as delright ,1 as printright,1 as requestright,1 as changepriceright from tblmenuassign where menutypeid=:type',$parms,$class);
	}
	else
	{

		$usertypeidarr = explode(",",$usertype);
		
		$qryrgh="SELECT 
			formname,formnametext,
			CASE WHEN(SUM(viewright)=0) THEN 0 ELSE 1 END AS viewright,
			CASE WHEN(SUM(allviewright)=0) THEN 0 ELSE 1 END AS allviewright,
			CASE WHEN(SUM(selfviewright)=0) THEN 0 ELSE 1 END AS selfviewright,
			CASE WHEN(SUM(addright)=0) THEN 0 ELSE 1 END AS addright,
			CASE WHEN(SUM(alladdright)=0) THEN 0 ELSE 1 END AS alladdright,
			CASE WHEN(SUM(selfaddright)=0) THEN 0 ELSE 1 END AS selfaddright, 
			CASE WHEN(SUM(editright)=0) THEN 0 ELSE 1 END AS editright, 
			CASE WHEN(SUM(alleditright)=0) THEN 0 ELSE 1 END AS alleditright,
			CASE WHEN(SUM(selfeditright)=0) THEN 0 ELSE 1 END AS selfeditright,
			CASE WHEN(SUM(delright)=0) THEN 0 ELSE 1 END AS delright, 
			CASE WHEN(SUM(alldelright)=0) THEN 0 ELSE 1 END AS alldelright,
			CASE WHEN(SUM(selfdelright)=0) THEN 0 ELSE 1 END AS selfdelright,
			CASE WHEN(SUM(printright)=0) THEN 0 ELSE 1 END AS printright,
			CASE WHEN(SUM(allprintright)=0) THEN 0 ELSE 1 END AS allprintright,
			CASE WHEN(SUM(selfprintright)=0) THEN 0 ELSE 1 END AS selfprintright,
			CASE WHEN(SUM(requestright)=0) THEN 0 ELSE 1 END AS requestright,
			CASE WHEN(SUM(changepriceright)=0) THEN 0 ELSE 1 END AS changepriceright
			from tbluserrights ";
		
		if(sizeof($result_ary)>0)
		{
			$parms=array();
			$qryrgh.=" where CONVERT(VARCHAR(255), personid) ='$uid' and type ='$type'"; 
		}	
		else
		{
			$parms = array_combine(
				array_map(function($i){ return ':id'.$i; }, array_keys($usertypeidarr)),
				$usertypeidarr
			);
			$usertypeidkey = implode(',', array_keys($parms));

			$qryrgh.=" where usertypeid IN ($usertypeidkey) and type ='$type' AND  CONVERT(VARCHAR(255), personid) =''"; 
		}
		$qryrgh.=" group by formname,formnametext";
		// print_r($parms);
		// exit;

		$result_ary=self::getmenual($qryrgh,$parms,$class);
	}

	return $result_ary;
}
// get company details 
function getcompanyinfo($class=null)
{
	$parms = array(
	);

	$result_ary=self::getmenual('select * from tblcmpmaster where isdefault = 1',$parms,$class);

	$qryhour="select crh.* from tblcmprangehour crh inner join tblcmpmaster cm on cm.id=crh.cmpid where cm.isdefault=1 order by crh.displayorder";
	$hourresult_ary=self::getmenual($qryhour,$parms,'CmpRangeHourInfo');

	if(sizeof($hourresult_ary)>0)
	{
		$result_ary[0]->setCmpRangeHour($hourresult_ary);
	}
	//print_r($parms);
	return $result_ary;
}

// Get Platform ID 
function getplatformid($platformno)
{
	$parms = array(
		':platformno'=>$platformno,
	);

	$result_ary=self::getmenual('select id from tblplatform where no=:platformno',$parms);
	return $result_ary[0]['id'];
}



// Get Default Language ID 
function getdefaultlanguageid($apptype)
{
	$parms = array(
		':apptype'=>$apptype,
	);

	$result_ary=self::getmenual('select * from tbllanguagemaster where isdefault = 1 and apptypeid=:apptype',$parms);
	return $result_ary;
}


// get Setting details 
function getsettingdata($class=null)
{
	$parms = array(
	);

	$result_ary=self::getmenual('select * from tblsetting',$parms,$class);
	return $result_ary;
}

function RandomCouponString($limit)
{
	try{
		$key = '';
		$keys = array_merge(range('A', 'Z'),range('0', '9'),range('a', 'z'));
	
		for ($i = 0; $i < $limit; $i++) 
		{
			$key .= $keys[array_rand($keys)];
		}	
		$qrychk="SELECT id from tblcouponusage where usagecode=:usagecode";
		$parms = array(
			':usagecode'=>$key
		);
		$result_ary=self::getmenual($qrychk,$parms);
		if(sizeof($result_ary) > 0)
		{
			RandomCouponString($limit);
		}
		else
		{
			return $key;
		}	
		
	}
	catch (Exception $e){
		//die(print_r($e->getMessage()));
	}
}



// GENERATE SERIES NO
function getorderno($seriesid,$type,$idate)
{
	
	$date = date('d/m/Y');
	if($idate)	
		$date = $idate;
	
	$dateyear = substr($date, -4, 4);
	$datemonth = substr($date, -7, 2);
	
	$tblflag=$type;
	if($type == 'receipt')
	{
		$tblflag='payment';
	}
	$tablename = 'tbl'.$tblflag;


	$qryyear = "SELECT * FROM tblfinancialyear WHERE convert(date,:date,103)  BETWEEN convert(date,fromdate,103) AND convert(date,todate,103)";
	$yearparams = array(
		':date'=>$date,
	);
	
	$resyear = self::getmenual($qryyear,$yearparams);
	$rowyear=$resyear[0];
	$fdate = $rowyear['fromdate'];
	$tdate = $rowyear['todate'];
	$fd = substr($fdate, 0, 4);
	$td = substr($tdate, 2, 2);

	 $qrysr="SELECT * from tblseriesmaster where id = :seriesid ";
	$srparams = array(
		':seriesid'=>$seriesid,
	);
	//print_r($srparams);
	$result_sr=self::getmenual($qrysr,$srparams);
	$rowsr=$result_sr[0];
	$seriesstart = $rowsr['startno'] - 1;
	$seriesend = $rowsr['endno'];
	$serieslen = strlen($seriesend);
	$seriesprefix = $rowsr['prefix'];
	$cmpid = $rowsr['cmpid'];


	$qrycmp="SELECT id,companyname,prefix from tblcmpmaster where id=:cmpid ";
	$cmpparams = array(
		':cmpid'=>$cmpid,
	);
	$result_arycmp=self::getmenual($qrycmp,$cmpparams);
	$rowcmp=$result_arycmp[0];

	$qrytb="SELECT ISNULL(MAX(maxid),:seriesstart) as maxid FROM $tablename WHERE seriesid=:seriesid";
	$tbparams = array(
		':seriesid'=>$seriesid,
		':seriesstart'=>$seriesstart,
	);
	//print_r($tbparams);
	$result_tb=self::getmenual($qrytb,$tbparams);
	$rowtb=$result_tb[0];

	if(sizeof($result_tb)>0)
	{
		$iid=$rowtb['maxid']+1;
	}
	else
	{
		$iid=1;
	}
	$ordno ='';

	if($seriesid)
	{
		$qryele = "SELECT * FROM tblserieselements WHERE seriesid = :seriesid ORDER BY timestamp asc ";
		$eleparams = array(
			':seriesid'=>$seriesid,
		);
		//print_r($eleparams);
		$resele =self::getmenual($qryele, $eleparams);
		$ordno = '';
		//while($rowele = $resele -> fetch())
		//echo sizeof($resele);
		for($i=0;$i<sizeof($resele);$i++)
		{
			$rowele=$resele[$i];
			if($rowele['elementid'] == 1)
				$ordno.=$rowcmp['prefix'];
			else if($rowele['elementid'] == 2)
				$ordno.= $seriesprefix;
			else if($rowele['elementid'] == 3)
				$ordno.=$fd.'/'.$td;
			else if($rowele['elementid'] == 4)
				$ordno.=$fd.'-'.$td;
			else if($rowele['elementid'] == 5)
				$ordno.=$dateyear;
			else if($rowele['elementid'] == 6)
				$ordno.=$datemonth;
			else if($rowele['elementid'] == 7)
				$ordno.='/';
			else if($rowele['elementid'] == 8)
				$ordno.='-';
			else if($rowele['elementid'] == 9)
				$ordno.=str_pad($iid,$serieslen,'0',STR_PAD_LEFT);
			else if($rowele['elementid'] == 10)
				$ordno.='';		
		}
		//echo "hi";
		//$ordno = $rowcmp['prefix'].str_pad($iid,4,'0',STR_PAD_LEFT);
	}
	// echo $ordno;
	return $ordno; 
}


function getmaxid($seriesid,$flag)
{
	$tblflag=$flag;
	if($flag == 'receipt')
	{
		$tblflag='payment';
	}
	$tablename = 'tbl'.$tblflag;

	
	$qrysr="SELECT * from tblseriesmaster where id = :seriesid ";
	$srparams = array(
		':seriesid'=>$seriesid,
	);
	//print_r($srparams);
	$result_sr=self::getmenual($qrysr,$srparams);
	$rowsr=$result_sr[0];
	$seriesstart = $rowsr['startno'] - 1;
	$seriesend = $rowsr['endno'];
	$serieslen = strlen($seriesend);
	$seriesprefix = $rowsr['prefix'];


	$qryele = "SELECT ISNULL(MAX(maxid),:seriesstart) as maxid FROM $tablename WHERE seriesid = :seriesid ";
	/* $qryele = "SELECT * FROM tblserieselements WHERE seriesid = :seriesid ORDER BY timestamp asc "; */
	$eleparams = array(
		':seriesid'=>$seriesid,
		':seriesstart'=>$seriesstart,
	);
	//print_r($eleparams);
	$resele =self::getmenual($qryele, $eleparams);
	$rowqt=$resele[0];
	$numrows=sizeof($resele);
	if($numrows>0 && $rowqt['maxid']>0)
	{
		$iid=$rowqt['maxid']+1;
	}
	else
	{
		$iid=1;
	}
	return $iid; 
}


function getseriseprefix($seriesid)
{
	$qry = "SELECT prefix from tblseriesmaster where id = :seriesid";
	$serparams=array(
		':seriesid'=>$seriesid,
	);
	$res = self::getmenual($qry,$serparams);
	$rowseries = $res[0];
	return $rowseries['prefix'];
}


//Get Item Max ID
function getitemmaxid()
{
	$qry = "SELECT ISNULL(MAX(maxid),0) as maxid FROM tblitemmaster ";
	$params = array();
	$res =self::getmenual($qry, $params);
	$row=$res[0];
	$numrows=sizeof($res);
	if($numrows>0 && $row['maxid']>0)
	{
		$iid=$row['maxid']+1;
	}
	else
	{
		$iid=1;
	}
	return $iid; 
}

//Generate Item Number
function generateitemnumber()
{
	$qry = "SELECT ISNULL(MAX(maxid),0) as maxid FROM tblitemmaster ";
	$params = array();
	$res =self::getmenual($qry, $params);
	$row=$res[0];
	$numrows=sizeof($res);
	if($numrows>0 && $row['maxid']>0)
	{
		$iid=$row['maxid']+1;
	}
	else
	{
		$iid=1;
	}

	$itemno='I';
	$itemno.=str_pad($iid,5,'0',STR_PAD_LEFT);	

	return $itemno; 
}



//Generate Order Invoice Number
function generateorderinvoicenumber($order_unqid,$uid,$platform)
{
	$IISMethods = new IISMethods();

	$order_invunqid = $IISMethods->generateuuid();
	$order_invdate = $IISMethods->getcurrdate();
	$datetime=$IISMethods->getdatetime();

	$typename='orderinvoice';
	$qryseries = "SELECT TOP 1 * FROM tblseriesmaster WHERE typename=:typename ORDER BY timestamp DESC";
	$seriesparams = array(
	':typename'=>$typename,
	);
	$result_ary=self::getmenual($qryseries,$seriesparams);
	$rowseries=$result_ary[0];

	$ord_seriesid=$rowseries['id'];
	$ord_seriesno=self::getorderno($ord_seriesid,'orderinvoice',$order_invdate);	
	$ord_maxid=self::getmaxid($ord_seriesid,'orderinvoice');
	$ord_prefix=self::getseriseprefix($ord_seriesid);

	$insinvqry['[id]']=$order_invunqid;
	$insinvqry['[orderid]']=$order_unqid;
	$insinvqry['[invoiceno]']=$ord_seriesno;
	$insinvqry['[invoicedate]']=$order_invdate;
	$insinvqry['[pdfurl]']='';
	$insinvqry['[platform]']=$platform;
	$insinvqry['[seriesid]']=$ord_seriesid;
	$insinvqry['[prefix]']=$ord_prefix;
	$insinvqry['[maxid]']=$ord_maxid;
	$insinvqry['[timestamp]']=$datetime;
	$insinvqry['[entry_uid]']=$uid;	
	$insinvqry['[entry_date]']=$datetime;

	$this->executedata('i','tblorderinvoice',$insinvqry,'');
}



//Send Text SMS
function sendtextsms($mobileNumber,$message)
{
	if($mobileNumber && $message && 1==2)
	{
		
	}
}



//Send SMTP Email
function sendemail($emailto,$subject,$data,$files,$bcc,$cc,$sendername,$emailhostid)
{
	$emailto="ravi.busa@instanceit.com";
	//$emailto="nilesh.bhayani@instanceit.com";

	$emailstatus = 0;
	$errormsg = '';

	$qrysett = "SELECT * FROM tblsmtpconfig ";
	$settparams = array();
	$ressett =self::getmenual($qrysett, $settparams);
	$numsett=sizeof($ressett);
	if($numsett > 0)
	{
		$rowsett=$ressett[0];

		$mailhost=$rowsett['hostname'];
		$mailPort=$rowsett['port'];
		$mailusername=$rowsett['emailid'];
		$mailpassword=$rowsett['password'];
		$mailemail=$rowsett['replyemailid'];
		$mailsendername=$rowsett['name'];
		if($sendername){
			$mailsendername=$sendername;
		}

		if($data)
		{
			$email_body = $data;
			
			//Create a new PHPMailer instance
			$mail = new PHPMailer;
			//Tell PHPMailer to use SMTP
			$mail->isSMTP();
			//Enable SMTP debugging
			// 0 = off (for production use)
			// 1 = client messages
			// 2 = client and server messages
			$mail->SMTPDebug = 0;
			//Ask for HTML-friendly debug output
			$mail->Debugoutput = 'html';
			//Set the hostname of the mail server
			$mail->Host = $mailhost;
			//Set the SMTP port number - likely to be 25, 465 or 587
			$mail->Port = $mailPort;
			//Whether to use SMTP authentication
			$mail->SMTPAuth = true;
			//Username to use for SMTP authentication
			$mail->Username = $mailusername;
			//Password to use for SMTP authentication
			$mail->Password = $mailpassword;

			//Set who the message is to be sent from
			$mail->setFrom($mailemail, $mailsendername);
			//Set an alternative reply-to address
			$mail->addReplyTo($mailemail, $mailsendername);
			
			
			//Set who the message is to be sent to
			$addresses = explode(',',$emailto);
			foreach ($addresses as $address) {
				$mail->AddAddress(trim($address));
			}
			//$mail->addAddress($emailto, '');
			//$mail->addAddress('inquiry@memighty.com', '');

			// bcc
			$bccaddresses = explode(',',$bcc);
			foreach ($bccaddresses as $bccaddress) {
				$mail->AddBCC(trim($bccaddress));
			}

			// cc 
			$ccaddresses = explode(',',$cc);
			foreach ($ccaddresses as $ccaddress) {
				$mail->AddCC(trim($ccaddress));
			}
			// $mail->AddCC("abhay4768@gmail.com", "bla");               
			// $mail->AddBCC("instanceit@gmail.com", "test");	
			
			//Set the subject line
			$mail->Subject = $subject;
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$mail->msgHTML($email_body);
			//Replace the plain text body with one created manually
			//$mail->AltBody = 'This is a plain-text message body';
			//Attach an image file
			$mail->addAttachment($files);

			$sent=$mail->send();
			// if($files)
			// {
			// 	unlink($files);
			// }
			
			if(!$sent) {
				$emailstatus = 0;
				$errormsg = $mail->ErrorInfo;
			}else {
				$emailstatus = 1;
				$errormsg = "Success";
			}
		}	
	}

    $data = ['emailstatus'=>$emailstatus,'emailerrormsg'=>$errormsg];
	return $data;
}




//Send Forgot Verification Email
function userforgotemailotpsms($type,$personid)   //type 1-Email,2-SMS
{
	$CompanyInfo = new CompanyInfo();
	$CompanyInfo=self::getcompanyinfo('CompanyInfo')[0]; 

	$config = new config();

	$qryper = "SELECT id,personname,email,contact,otp FROM tblpersonmaster where id=:personid";
	$perparams = array(
		':personid'=>$personid,
	);
	$resper =self::getmenual($qryper, $perparams);
	$numper=sizeof($resper);

	if($numper > 0)
	{
		$rowper=$resper[0];

		$personname=$rowper['personname'];
		$personemail=$rowper['email'];
		$personcontact=$rowper['contact'];
		$otp=$rowper['otp'];
		
		$websiteurl=$config->getWebsiteurl();
		$logourl=$config->getImageurl().$CompanyInfo->getLogoImg();
		$companyname=$CompanyInfo->getShortname();
		
		if($type == 1)   //For Email
		{
			$emailtxt='';


			$emailtxt.='<table width="100%" border="0"><tr><td align="center">';

			$emailtxt.='<table style="max-width: 600px;margin: 0 auto;width: 100%;background-color: #f4f4f4;padding: 12px;" cellpadding="0" cellspacing="0" border="0" width="600">';
			$emailtxt.='<tbody style="background-color:#fff;">';
			$emailtxt.='<tr align="center">';
			$emailtxt.='<td style="border-bottom: 12px solid #f4f4f4;"><img src="'.$logourl.'" style="width: 70%;"></td>';
			$emailtxt.='</tr>';
			$emailtxt.='<tr>';
			$emailtxt.='<td style="padding:15px"><div style="font-size:26px;font-weight:bold;text-align:center;color:#000000;margin-bottom:20px;font-family:Tahoma,Geneva,sans-serif">OTP</div></td>';
			$emailtxt.='</tr>';
			$emailtxt.='<tr>';
			$emailtxt.='<td>';

			$emailtxt.='<table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 420px;margin: 0 auto;">';
			$emailtxt.='<tbody>';
			$emailtxt.='<tr>';
			$emailtxt.='<td style="border-bottom:1px solid #e4e4e4;font-size:15px;padding-top:9px;padding-bottom:9px;width:50%;font-weight:normal;color:#000000;font-family:Montserrat,Tahoma,Geneva,sans-serif;text-align:left;">';
			$emailtxt.='<table>';
			$emailtxt.='<tr><td>Dear '.$personname.',</td></tr>';
			$emailtxt.='<tr><td>Your OTP for '.$companyname.' is as below. </td></tr>';
			$emailtxt.='<tr><td>Please do not share this OTP.</td></tr>';
			$emailtxt.='</table>';
			$emailtxt.='</td>';
			$emailtxt.='</tr>';
			$emailtxt.='<tr>';
			$emailtxt.='<td style="border-bottom:1px solid #e4e4e4;font-size:32px;padding-top:9px;padding-bottom:9px;width:50%;font-weight:bold;color:#000000;font-family:Tahoma,Geneva,sans-serif;text-align:center;">';
			$emailtxt.=$otp;
			$emailtxt.='</td>';
			$emailtxt.='</tr>';
			$emailtxt.='<tr>';
			$emailtxt.='<td style="border-bottom:1px solid #e4e4e4;font-size:15px;padding-top:9px;padding-bottom:9px;width:50%;font-weight:normal;color:#000000;font-family:Tahoma,Geneva,sans-serif;text-align:left;">';
			$emailtxt.='Regards, <br/>'.$companyname;
			$emailtxt.='</td>';
			$emailtxt.='</tr>';
			$emailtxt.='</tbody>';
			$emailtxt.='</table>';

			$emailtxt.='</td>';
			$emailtxt.='</tr>';
			$emailtxt.='<tr>';
			$emailtxt.='<td>';
			$emailtxt.='<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#F4F4F4">';
			$emailtxt.='<tbody>';
			$emailtxt.='<tr>';
			$emailtxt.='<td style="padding-top:12px;padding-left:15px;padding-right:15px;font-size:13px;line-height:18px;color:#333;text-align:center;border-spacing:0;border-collapse:collapse;font-family:Tahoma,Geneva,sans-serif;letter-spacing: 1px;">&copy;'.date('Y').' <a href="'.$websiteurl.'" style="color: #005b31;text-decoration: none;">'.$companyname.'</a> | All Rights Reserved</td>';
			$emailtxt.='</tr>';
			$emailtxt.='</tbody>';
			$emailtxt.='</table>';
			$emailtxt.='</td>';
			$emailtxt.='</tr>';
			$emailtxt.='</tbody>';
			$emailtxt.='</table>';

			$emailtxt.='</td></tr></table>';
			

			$email=$personemail;
			$subject = $companyname." - Reset Password";
			$data = $emailtxt;
			$files='';
			$bcc='';
			self::sendemail($email,$subject,$data,$files,$bcc,'','','');


		}	
	}
	else if($type == 2)   //For SMS
	{
		$smstxt='';
		$smstxt.='';

		self::sendtextsms($personcontact,$smstxt);
	}	
}


//Send User Contact US Email To Admin
function usercontactusemails($contactusid)   
{
	$config = new config();
	$CompanyInfo = new CompanyInfo();
	$CompanyInfo=self::getcompanyinfo('CompanyInfo')[0];

	$qryper = "SELECT id,name,email,mobile,message,convert(varchar,timestamp,100) as fulledate
		FROM tblcontactus where id=:contactusid";
	$perparams = array(
		':contactusid'=>$contactusid,
	);
	$resper =self::getmenual($qryper, $perparams);
	$numper=sizeof($resper);

	if($numper > 0)
	{
		$rowper=$resper[0];

		
		$websiteurl=$config->getWebsiteurl();
		$logourl=$config->getImageurl().$CompanyInfo->getLogoImg();
		$companyname=$CompanyInfo->getShortname();
		
		
		$emailtxt='';

		$emailtxt.='<table width="100%" border="0"><tr><td align="center">';

		$emailtxt.='<table style="max-width: 600px;margin: 0 auto;width: 100%;background-color: #f4f4f4;padding: 12px;" cellpadding="0" cellspacing="0" border="0" width="600">';
		$emailtxt.='<tbody style="background-color:#fff;">';
		$emailtxt.='<tr>';
		$emailtxt.='<td style="border-bottom: 12px solid #f4f4f4;"><img src="'.$logourl.'" style="width: 70%;"></td>';
		$emailtxt.='</tr>';
		
		$emailtxt.='<tr>';
		$emailtxt.='<td>';

		$emailtxt.='<table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 420px;margin: 0 auto;">';
		$emailtxt.='<tbody>';
		$emailtxt.='<tr>';
		$emailtxt.='<td style="border-bottom:1px solid #e4e4e4;font-size:15px;padding-top:9px;padding-bottom:9px;width:50%;font-weight:normal;color:#000000;font-family:Montserrat,Tahoma,Geneva,sans-serif;text-align:left;">';
		

		$emailtxt.='<table>';
		$emailtxt.='<tr>';
		$emailtxt.='<td><b>Name : </b> '.$rowper['name'].'</td>';
		$emailtxt.='</tr>';
		$emailtxt.='<tr>';
		$emailtxt.='<td><b>Email : </b> '.$rowper['email'].'</td>';
		$emailtxt.='</tr>';
		$emailtxt.='<tr>';
		$emailtxt.='<td><b>Mobile : </b> '.$rowper['mobile'].'</td>';
		$emailtxt.='</tr>';
		$emailtxt.='<tr>';
		$emailtxt.='<td><b>Message : </b> '.$rowper['message'].'</td>';
		$emailtxt.='</tr>';
		$emailtxt.='<tr>';
		$emailtxt.='<td><b>Datetime : </b> '.$rowper['fulledate'].'</td>';
		$emailtxt.='</tr>';
		$emailtxt.='</table>';


		$emailtxt.='</td>';
		$emailtxt.='</tr>';


		$emailtxt.='<tr>';
		$emailtxt.='<td style="border-bottom:1px solid #e4e4e4;font-size:15px;padding-top:9px;padding-bottom:9px;width:50%;font-weight:normal;color:#000000;font-family:Tahoma,Geneva,sans-serif;text-align:left;">';
		$emailtxt.='Regards, <br/>'.$companyname;
		$emailtxt.='</td>';
		$emailtxt.='</tr>';
		$emailtxt.='</tbody>';
		$emailtxt.='</table>';

		$emailtxt.='</td>';
		$emailtxt.='</tr>';
		$emailtxt.='<tr>';
		$emailtxt.='<td>';
		$emailtxt.='<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#F4F4F4">';
		$emailtxt.='<tbody>';
		$emailtxt.='<tr>';
		$emailtxt.='<td style="padding-top:12px;padding-left:15px;padding-right:15px;font-size:13px;line-height:18px;color:#333;text-align:center;border-spacing:0;border-collapse:collapse;font-family:Tahoma,Geneva,sans-serif;letter-spacing: 1px;">&copy;'.date('Y').' <a href="'.$websiteurl.'" style="color: #005b31;text-decoration: none;">'.$companyname.'</a> | All Rights Reserved</td>';
		$emailtxt.='</tr>';
		$emailtxt.='</tbody>';
		$emailtxt.='</table>';
		$emailtxt.='</td>';
		$emailtxt.='</tr>';
		$emailtxt.='</tbody>';
		$emailtxt.='</table>';

		$emailtxt.='</td></tr></table>';
		

		$email=$CompanyInfo->getEmail1();
		$subject = $companyname." - Contact Us";
		$data = $emailtxt;
		$files='';
		$bcc='';
		self::sendemail($email,$subject,$data,$files,$bcc,'','','');
	}	
}



//Send User Register Request Email/SMS
function userregisterrequestemailssms($type,$personid)   //type 1-Email,2-SMS
{
	$config = new config();
	$CompanyInfo = new CompanyInfo();
	$CompanyInfo=self::getcompanyinfo('CompanyInfo')[0];

	$qryper = "SELECT id,personname,email,contact,strpassword FROM tblpersonmaster where id=:personid and isnull(isverified,0)=0";
	$perparams = array(
		':personid'=>$personid,
	);
	$resper =self::getmenual($qryper, $perparams);
	$numper=sizeof($resper);

	if($numper > 0)
	{
		$rowper=$resper[0];

		$personname=$rowper['personname'];
		$personemail=$rowper['email'];
		$personcontact=$rowper['contact'];
		$password=$rowper['strpassword'];
		

		$websiteurl=$config->getWebsiteurl();
		$logourl=$config->getImageurl().$CompanyInfo->getLogoImg();
		$companyname=$CompanyInfo->getShortname();
		
		if($type == 1)   //For Email
		{
			$emailtxt='';

			$emailtxt.='<table width="100%" border="0"><tr><td align="center">';

			$emailtxt.='<table style="max-width: 600px;margin: 0 auto;width: 100%;background-color: #f4f4f4;padding: 12px;" cellpadding="0" cellspacing="0" border="0" width="600">';
			$emailtxt.='<tbody style="background-color:#fff;">';
			$emailtxt.='<tr>';
			$emailtxt.='<td style="text-align: center;padding: 20px;"><img src="'.$logourl.'" style="border-bottom: 12px solid #f4f4f4;width: 80%;"></td>';
			$emailtxt.='</tr>';
			
			$emailtxt.='<tr>';
			$emailtxt.='<td>';

			$emailtxt.='<table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 420px;margin: 0 auto;">';
			$emailtxt.='<tbody>';
			$emailtxt.='<tr>';
			$emailtxt.='<td style="border-bottom:1px solid #e4e4e4;font-size:15px;padding-top:9px;padding-bottom:9px;width:50%;font-weight:normal;color:#000000;font-family:Montserrat,Tahoma,Geneva,sans-serif;text-align:left;">';
			$emailtxt.='<table>';
			$emailtxt.='<tr><td>Dear '.$personname.',</td></tr>';
			$emailtxt.='<tr><td>You are successfully registered.</td></tr>';
			$emailtxt.='<tr><td>Your account is under verification.</td></tr>';
			$emailtxt.='</table>';
			$emailtxt.='</td>';
			$emailtxt.='</tr>';

			$emailtxt.='<tr>';
			$emailtxt.='<td style="border-bottom:1px solid #e4e4e4;font-size:15px;padding-top:9px;padding-bottom:9px;width:50%;font-weight:normal;color:#000000;font-family:Tahoma,Geneva,sans-serif;text-align:left;">';
			$emailtxt.='Regards, <br/>'.$companyname;
			$emailtxt.='</td>';
			$emailtxt.='</tr>';
			$emailtxt.='</tbody>';
			$emailtxt.='</table>';

			$emailtxt.='</td>';
			$emailtxt.='</tr>';
			$emailtxt.='<tr>';
			$emailtxt.='<td>';
			$emailtxt.='<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#F4F4F4">';
			$emailtxt.='<tbody>';
			$emailtxt.='<tr>';
			$emailtxt.='<td style="padding-top:12px;padding-left:15px;padding-right:15px;font-size:13px;line-height:18px;color:#333;text-align:center;border-spacing:0;border-collapse:collapse;font-family:Tahoma,Geneva,sans-serif;letter-spacing: 1px;">&copy;'.date('Y').' <a href="'.$websiteurl.'" style="color: #005b31;text-decoration: none;">'.$companyname.'</a> | All Rights Reserved</td>';
			$emailtxt.='</tr>';
			$emailtxt.='</tbody>';
			$emailtxt.='</table>';
			$emailtxt.='</td>';
			$emailtxt.='</tr>';
			$emailtxt.='</tbody>';
			$emailtxt.='</table>';

			$emailtxt.='</td></tr></table>';
			

			$email=$personemail;
			$subject = $companyname." - New Registeration";
			$data = $emailtxt;
			$files='';
			$bcc='';
			self::sendemail($email,$subject,$data,$files,$bcc,'','','');

		}	
		else if($type == 2)   //For SMS
		{
			$smstxt='';

			self::sendtextsms($personcontact,$smstxt);
		}
	}	
}



//Send User Approval Credential Email/SMS
function userapprovallogincredentialemailssms($type,$personid)   //type 1-Email,2-SMS
{
	$config = new config();
	$CompanyInfo = new CompanyInfo();
	$CompanyInfo=self::getcompanyinfo('CompanyInfo')[0];

	$qryper = "SELECT id,personname,email,contact,strpassword,isnormal FROM tblpersonmaster where id=:personid and isverified=1";
	$perparams = array(
		':personid'=>$personid,
	);
	$resper =self::getmenual($qryper, $perparams);
	$numper=sizeof($resper);

	if($numper > 0)
	{
		$rowper=$resper[0];

		$personname=$rowper['personname'];
		$personemail=$rowper['email'];
		$personcontact=$rowper['contact'];
		$password=$rowper['strpassword'];
		$isnormaluser=$rowper['isnormal'];
		

		$websiteurl=$config->getWebsiteurl();
		$logourl=$config->getImageurl().$CompanyInfo->getLogoImg();
		$companyname=$CompanyInfo->getShortname();
		
		if($type == 1)   //For Email
		{
			$emailtxt='';

			$emailtxt.='<table width="100%" border="0"><tr><td align="center">';

			$emailtxt.='<table style="max-width: 600px;margin: 0 auto;width: 100%;background-color: #f4f4f4;padding: 12px;" cellpadding="0" cellspacing="0" border="0" width="600">';
			$emailtxt.='<tbody style="background-color:#fff;">';
			$emailtxt.='<tr>';
			$emailtxt.='<td style="text-align: center;padding: 20px;"><img src="'.$logourl.'" style="border-bottom: 12px solid #f4f4f4;width: 80%;"></td>';
			$emailtxt.='</tr>';
			
			$emailtxt.='<tr>';
			$emailtxt.='<td>';

			$emailtxt.='<table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 420px;margin: 0 auto;">';
			$emailtxt.='<tbody>';
			$emailtxt.='<tr>';
			$emailtxt.='<td style="border-bottom:1px solid #e4e4e4;font-size:15px;padding-top:9px;padding-bottom:9px;width:50%;font-weight:normal;color:#000000;font-family:Montserrat,Tahoma,Geneva,sans-serif;text-align:left;">';
			$emailtxt.='<table>';
			$emailtxt.='<tr><td>Dear '.$personname.',</td></tr>';
			$emailtxt.='<tr><td>Your account has been verified successfully.</td></tr>';
			$emailtxt.='<tr><td>Below are the login details of '.$companyname.'.</td></tr>';
			$emailtxt.='<tr><td><b>Login Link : </b> <a href="'.$websiteurl.'" target="_blank">'.$websiteurl.'</a></td></tr>';
			$emailtxt.='<tr><td><b>Email : </b> '.$personemail.'</td></tr>';
			$emailtxt.='<tr><td><b>Mobile No : </b> '.$personcontact.'</td></tr>';
			if($isnormaluser == 1)
			{
				$emailtxt.='<tr><td><b>Password : </b> '.$password.'</td></tr>';
			}
			$emailtxt.='</table>';
			$emailtxt.='</td>';
			$emailtxt.='</tr>';

			$emailtxt.='<tr>';
			$emailtxt.='<td style="border-bottom:1px solid #e4e4e4;font-size:15px;padding-top:9px;padding-bottom:9px;width:50%;font-weight:normal;color:#000000;font-family:Tahoma,Geneva,sans-serif;text-align:left;">';
			$emailtxt.='Regards, <br/>'.$companyname;
			$emailtxt.='</td>';
			$emailtxt.='</tr>';
			$emailtxt.='</tbody>';
			$emailtxt.='</table>';

			$emailtxt.='</td>';
			$emailtxt.='</tr>';
			$emailtxt.='<tr>';
			$emailtxt.='<td>';
			$emailtxt.='<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#F4F4F4">';
			$emailtxt.='<tbody>';
			$emailtxt.='<tr>';
			$emailtxt.='<td style="padding-top:12px;padding-left:15px;padding-right:15px;font-size:13px;line-height:18px;color:#333;text-align:center;border-spacing:0;border-collapse:collapse;font-family:Tahoma,Geneva,sans-serif;letter-spacing: 1px;">&copy;'.date('Y').' <a href="'.$websiteurl.'" style="color: #005b31;text-decoration: none;">'.$companyname.'</a> | All Rights Reserved</td>';
			$emailtxt.='</tr>';
			$emailtxt.='</tbody>';
			$emailtxt.='</table>';
			$emailtxt.='</td>';
			$emailtxt.='</tr>';
			$emailtxt.='</tbody>';
			$emailtxt.='</table>';

			$emailtxt.='</td></tr></table>';
			

			$email=$personemail;
			$subject = "Member Login Credentials";
			$data = $emailtxt;
			$files='';
			$bcc='';
			self::sendemail($email,$subject,$data,$files,$bcc,'','','');


		}	
		else if($type == 2)   //For SMS
		{
			$smstxt='';

			self::sendtextsms($personcontact,$smstxt);
		}
	}	
}




//Send Package Expire Email/SMS
function userexpirepackageemails($personemail,$emailbodydata,$subject)   //type 1-Email,2-SMS
{
	$config = new config();
	$CompanyInfo = new CompanyInfo();
	$CompanyInfo=self::getcompanyinfo('CompanyInfo')[0];

	
	$websiteurl=$config->getWebsiteurl();
	$logourl=$config->getImageurl().$CompanyInfo->getLogoImg();
	$companyname=$CompanyInfo->getShortname();
		

	$emailtxt='';

	$emailtxt.='<table width="100%" border="0"><tr><td align="center">';

	$emailtxt.='<table style="max-width: 600px;margin: 0 auto;width: 100%;background-color: #f4f4f4;padding: 12px;" cellpadding="0" cellspacing="0" border="0" width="600">';
	$emailtxt.='<tbody style="background-color:#fff;">';
	$emailtxt.='<tr>';
	$emailtxt.='<td style="text-align: center;padding: 20px;"><img src="'.$logourl.'" style="border-bottom: 12px solid #f4f4f4;width: 80%;"></td>';
	$emailtxt.='</tr>';
	
	$emailtxt.='<tr>';
	$emailtxt.='<td>';

	$emailtxt.='<table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 420px;margin: 0 auto;">';
	$emailtxt.='<tbody>';
	$emailtxt.='<tr>';
	$emailtxt.='<td style="border-bottom:1px solid #e4e4e4;font-size:15px;padding-top:9px;padding-bottom:9px;width:50%;font-weight:normal;color:#000000;font-family:Montserrat,Tahoma,Geneva,sans-serif;text-align:left;">';
	$emailtxt.=$emailbodydata;
	$emailtxt.='</td>';
	$emailtxt.='</tr>';

	$emailtxt.='<tr>';
	$emailtxt.='<td style="border-bottom:1px solid #e4e4e4;font-size:15px;padding-top:9px;padding-bottom:9px;width:50%;font-weight:normal;color:#000000;font-family:Tahoma,Geneva,sans-serif;text-align:left;">';
	$emailtxt.='Regards, <br/>'.$companyname;
	$emailtxt.='</td>';
	$emailtxt.='</tr>';
	$emailtxt.='</tbody>';
	$emailtxt.='</table>';

	$emailtxt.='</td>';
	$emailtxt.='</tr>';
	$emailtxt.='<tr>';
	$emailtxt.='<td>';
	$emailtxt.='<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#F4F4F4">';
	$emailtxt.='<tbody>';
	$emailtxt.='<tr>';
	$emailtxt.='<td style="padding-top:12px;padding-left:15px;padding-right:15px;font-size:13px;line-height:18px;color:#333;text-align:center;border-spacing:0;border-collapse:collapse;font-family:Tahoma,Geneva,sans-serif;letter-spacing: 1px;">&copy;'.date('Y').' <a href="'.$websiteurl.'" style="color: #005b31;text-decoration: none;">'.$companyname.'</a> | All Rights Reserved</td>';
	$emailtxt.='</tr>';
	$emailtxt.='</tbody>';
	$emailtxt.='</table>';
	$emailtxt.='</td>';
	$emailtxt.='</tr>';
	$emailtxt.='</tbody>';
	$emailtxt.='</table>';

	$emailtxt.='</td></tr></table>';
	

	$email=$personemail;
	
	$data = $emailtxt;
	$files='';
	$bcc='';
	self::sendemail($email,$subject,$data,$files,$bcc,'','','');	
}




//Send User New Order (Membership/Package/Course) Email/SMS
function userneworderemailssms($type,$orderid)   //type 1-Email,2-SMS
{
	$config = new config();
	$CompanyInfo = new CompanyInfo();
	$CompanyInfo=self::getcompanyinfo('CompanyInfo')[0];

	$qryord = "select o.id,o.transactionid,o.orderno,pm.personname as membername,pm.contact as membercontact,pm.email as memberemail 
		from tblorder o 
		inner join tblpersonmaster pm on pm.id=o.uid 
		where o.id=:orderid";
	$ordparams = array(
		':orderid'=>$orderid,
	);
	$resord =self::getmenual($qryord, $ordparams);
	$numord=sizeof($resord);

	if($numord > 0)
	{
		$roword=$resord[0];

		$personname=$roword['membername'];
		$personemail=$roword['memberemail'];
		$personcontact=$roword['membercontact'];

		$orderno=$roword['orderno'];

		$websiteurl=$config->getWebsiteurl();
		$logourl=$config->getImageurl().$CompanyInfo->getLogoImg();
		$companyname=$CompanyInfo->getShortname();
		
		if($type == 1)   //For Email
		{
			$emailtxt='';

			$emailtxt.='<table width="100%" border="0"><tr><td align="center">';

			$emailtxt.='<table style="max-width: 600px;margin: 0 auto;width: 100%;background-color: #f4f4f4;padding: 12px;" cellpadding="0" cellspacing="0" border="0" width="600">';
			$emailtxt.='<tbody style="background-color:#fff;">';
			$emailtxt.='<tr>';
			$emailtxt.='<td style="text-align: center;padding: 20px;"><img src="'.$logourl.'" style="border-bottom: 12px solid #f4f4f4;width: 80%;"></td>';
			$emailtxt.='</tr>';
			
			$emailtxt.='<tr>';
			$emailtxt.='<td>';

			$emailtxt.='<table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 420px;margin: 0 auto;">';
			$emailtxt.='<tbody>';
			$emailtxt.='<tr>';
			$emailtxt.='<td style="border-bottom:1px solid #e4e4e4;font-size:15px;padding-top:9px;padding-bottom:9px;width:50%;font-weight:normal;color:#000000;font-family:Montserrat,Tahoma,Geneva,sans-serif;text-align:left;">';
			$emailtxt.='<table>';
			$emailtxt.='<tr><td>Hello '.$personname.',</td></tr>';
			$emailtxt.='<tr><td>Your order('.$orderno.') has been placed successfully.</td></tr>';
			$emailtxt.='</table>';
			$emailtxt.='</td>';
			$emailtxt.='</tr>';

			$emailtxt.='<tr>';
			$emailtxt.='<td style="border-bottom:1px solid #e4e4e4;font-size:15px;padding-top:9px;padding-bottom:9px;width:50%;font-weight:normal;color:#000000;font-family:Tahoma,Geneva,sans-serif;text-align:left;">';
			$emailtxt.='Regards, <br/>'.$companyname;
			$emailtxt.='</td>';
			$emailtxt.='</tr>';
			$emailtxt.='</tbody>';
			$emailtxt.='</table>';

			$emailtxt.='</td>';
			$emailtxt.='</tr>';
			$emailtxt.='<tr>';
			$emailtxt.='<td>';
			$emailtxt.='<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#F4F4F4">';
			$emailtxt.='<tbody>';
			$emailtxt.='<tr>';
			$emailtxt.='<td style="padding-top:12px;padding-left:15px;padding-right:15px;font-size:13px;line-height:18px;color:#333;text-align:center;border-spacing:0;border-collapse:collapse;font-family:Tahoma,Geneva,sans-serif;letter-spacing: 1px;">&copy;'.date('Y').' <a href="'.$websiteurl.'" style="color: #005b31;text-decoration: none;">'.$companyname.'</a> | All Rights Reserved</td>';
			$emailtxt.='</tr>';
			$emailtxt.='</tbody>';
			$emailtxt.='</table>';
			$emailtxt.='</td>';
			$emailtxt.='</tr>';
			$emailtxt.='</tbody>';
			$emailtxt.='</table>';

			$emailtxt.='</td></tr></table>';
			

			$email=$personemail;
			$subject = $companyname." - New Order";
			$data = $emailtxt;
			$files='';
			$bcc='';
			self::sendemail($email,$subject,$data,$files,$bcc,'','','');


		}	
		else if($type == 2)   //For SMS
		{
			$smstxt='';

			self::sendtextsms($personcontact,$smstxt);
		}
	}	
}




//Get Language Wise Message
function getlanguagewisemsg($apptype,$languageid)    //apptype 2-Mobile App, 3-POS
{
	$IISMethods = new IISMethods();
	
	$languagemsgary=array();

	$qrychk="SELECT id FROM tbllanguagemaster l WHERE CONVERT(VARCHAR(255), id)=:languageid AND isactive=1 AND showinapp=1 AND apptypeid=:apptype";
	$parms = array(
		':languageid'=>$languageid,
		':apptype'=>$apptype,
	);
	$result_ary=self::getmenual($qrychk,$parms);

	if(sizeof($result_ary)==0)
	{
		$defaultlanguaeid = self::getdefaultlanguageid($apptype)[0];
		$languageid = $IISMethods->sanitize($defaultlanguaeid['id']);
	}

	$qrylng="SELECT mm.id,mm.messagenameid,mm.messageengname,
		CASE WHEN (isnull(al.langmessagename,'')='') THEN mm.messageengname ELSE al.langmessagename END AS msg
		FROM tblmessagemaster mm 
		LEFT JOIN tblassignlangwisemessage al ON mm.id=al.messageid AND al.languageid=:languageid";
	$lngparms = array(
		':languageid'=>$languageid,
	);
	$reslng=self::getmenual($qrylng,$lngparms);
	$numlng=sizeof($reslng);
		
	for($i=0;$i<sizeof($reslng);$i++)
	{
		$rowlng=$reslng[$i];

		$languagemsgary[$rowlng['messagenameid']]=$rowlng['msg'];
	}

	return $languagemsgary;
}



//Random Generate Member Code For SAP (HaNa DB)
function SAPRandomMemberCode($SubDB,$limit)
{
	try{
		$key = '';
		$keys = array_merge(range('0', '9'));
	
		for ($i = 0; $i < $limit; $i++) 
		{
			$key .= $keys[array_rand($keys)];
		}	

		$key='CUST'.$key;

		$SubDBName=$SubDB->getDBName();
		$qrysap = "SELECT \"CardCode\" from ".$SubDBName.".OCRD where \"CardCode\"='$key' ";
		$ressap=$SubDB->getmenual($qrysap);

		if(sizeof($ressap) > 0)
		{
			self::SAPRandomMemberCode($SubDB,$limit);
		}
		else
		{
			return $key;
		}	
		
	}
	catch (Exception $e){
		
	}
}


//Get Max DocEntry Number For SAP (HaNa DB)
function SAPGetMaxDocEntryNumber($SubDB)
{
	try{
		
		$SubDBName=$SubDB->getDBName();
		$qrysap = "SELECT max(\"DocEntry\") as \"DocEntry\" from ".$SubDBName.".OCRD ";
		$ressap=$SubDB->getmenual($qrysap);
		$rowsap=$ressap[0];

		$DocEntry=0;
		if($rowsap['DocEntry'] > 0)
		{
			$DocEntry=$rowsap['DocEntry'];
		}

		$NewDocEntry=$DocEntry+1;

		return $NewDocEntry;	
		
	}
	catch (Exception $e){

	}
}


//Login API in SAP (HaNa DB)
function SAPLoginAPIData($SubDB)    
{
	$config = new config();
	$IISMethods = new IISMethods();

	$SubDBName=$SubDB->getDBName();

	$SAPusername=$config->getSAPusername();
	$SAPpassword=$config->getSAPpassword();

	
	$SAPData['UserName']=$SAPusername;              
	$SAPData['Password']=$SAPpassword; 
	$SAPData['CompanyDB']=$SubDBName; 

	$SAPDataJosn=json_encode($SAPData,true);

	$CURLOPT_URL=$config->getCurlSAPapiurl().'Login';
	$CURLOPT_CUSTOMREQUEST='POST';
	$CURLOPT_POSTFIELDS=$SAPDataJosn;
	
	$CURLOPT_HTTPHEADER=array(
		'Content-Type: application/json',
	);
	$resLoginData=$IISMethods->FnCURLResponse($CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS,$CURLOPT_HTTPHEADER,'Login');

	return $resLoginData;
}


//Insert Member Data in SAP (HaNa DB)
function SAPInsertMemberData($SubDB,$memberid)    
{
	$config = new config();
	$IISMethods = new IISMethods();

	$SubDBName=$SubDB->getDBName();


	$qrymem="SELECT id,personname,address,email,contact FROM tblpersonmaster WHERE CONVERT(VARCHAR(255), id)=:memberid";
	$memparms = array(
		':memberid'=>$memberid,
	);
	$resmem=self::getmenual($qrymem,$memparms);

	if(sizeof($resmem) > 0)
	{
		$rowmem=$resmem[0];


		//Login API in SAP (HaNa DB)
		$resLoginData=self::SAPLoginAPIData($SubDB); 



		$SAPData['CardCode']="";              
        $SAPData['CardName']=$rowmem['personname']; 
        $SAPData['CardType']='C'; 
		$SAPData['Address']=$rowmem['address']; 
		$SAPData['MailAddres']=$rowmem['email']; 
		$SAPData['Phone1']=$rowmem['contact']; 
		$SAPData['Series']='70'; 
		

		$SAPDataJosn=json_encode($SAPData,true);

		$CURLOPT_URL=$config->getCurlSAPapiurl().'BusinessPartners';
		$CURLOPT_CUSTOMREQUEST='POST';
		$CURLOPT_POSTFIELDS=$SAPDataJosn;
		
		$CURLOPT_HTTPHEADER=array(
			'Content-Type: application/json',
		);
		$resLoginData=$IISMethods->FnCURLResponse($CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS,$CURLOPT_HTTPHEADER,'Add Member');


		if($resLoginData['CardCode'] != '')
		{
			//Update Member Data
			$updqry=array(					
				'sappersonid'=>$resLoginData['CardCode'],					
			);
			$extraparams=array(
				'id'=>$rowmem['id']
			);
			self::executedata('u','tblpersonmaster',$updqry,$extraparams);
		}

	}
}



//Insert Package Data in SAP AR Invoice (HaNa DB)
function SAPInsertARInvoiceData($SubDB,$orderid)    
{
	$IISMethods = new IISMethods();
	$SubDBName=$SubDB->getDBName();


	//Login API in SAP (HaNa DB)
	$resLoginData=self::SAPLoginAPIData($SubDB); 

	
	$qryord="SELECT distinct o.*,pm.sappersonid,pm.personname,isnull(pm.address,'') as pm_address,convert(date,o.timestamp,103) as ord_date 
		FROM tblorder o 
		inner join tblorderdetail od on od.orderid=o.id 
		inner join tblpersonmaster pm on pm.id=o.uid 
		WHERE CONVERT(VARCHAR(255), o.id)=:orderid and isnull(o.saporderid,'')=''";
	$ordparms = array(
		':orderid'=>$orderid,
	);
	$resord=self::getmenual($qryord,$ordparms);
	
	if(sizeof($resord) > 0)
	{
		$roword=$resord[0];


		$SAPData['CardCode']=$roword['sappersonid'];              
        $SAPData['DocDate']=$roword['ord_date']; 
        $SAPData['DocDueDate']=$roword['ord_date']; 
        $SAPData['DocType']="dDocument_Items"; 

		
		$qryod="SELECT distinct od.*,im.sapitemid,tx.saptaxid 
		FROM tblorderdetail od 
		inner join tblitemmaster im on im.id=od.itemid
		inner join tbltax tx on tx.id=im.gstid
		WHERE CONVERT(VARCHAR(255), od.orderid)=:orderid ";
		$odparms = array(
			':orderid'=>$orderid,
		);
		$resod=self::getmenual($qryod,$odparms);
		if(sizeof($resod) > 0)
		{
			for($i=0;$i<sizeof($resod);$i++)
			{
				$rowod=$resod[$i];


				if($rowod['sapitemid'] != '')
				{
					$SAPData['DocumentLines'][$i]['ItemCode']=$rowod['sapitemid'];              
					$SAPData['DocumentLines'][$i]['Quantity']="1";              
					$SAPData['DocumentLines'][$i]['UoMEntry']="1";              
					$SAPData['DocumentLines'][$i]['VatGroup']=$rowod['saptaxid'];              
					$SAPData['DocumentLines'][$i]['UnitPrice']=$rowod['price'];   
				}


				//Update Order Details Data
				$updodqry=array(					
					'sapitemid'=>$rowod['sapitemid'],					
				);
				$extraodparams=array(
					'id'=>$rowod['id']
				);
				self::executedata('u','tblorderdetail',$updodqry,$extraodparams);
			}
		}
		
		

		$SAPDataJosn=json_encode($SAPData,true);

		$CURLOPT_URL=$config->getCurlSAPapiurl().'Invoices';
		$CURLOPT_CUSTOMREQUEST='POST';
		$CURLOPT_POSTFIELDS=$SAPDataJosn;
		
		$CURLOPT_HTTPHEADER=array(
			'Content-Type: application/json',
		);
		$resInvoiceData=$IISMethods->FnCURLResponse($CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS,$CURLOPT_HTTPHEADER,'AR Invoice');


		if($resInvoiceData['DocEntry'] != '')
		{
			//Update Order Data
			$updqry=array(					
				'saporderid'=>$resInvoiceData['DocEntry'],					
			);
			$extraparams=array(
				'id'=>$orderid
			);
			self::executedata('u','tblorder',$updqry,$extraparams);
		}
		
	}
}



//Update Package Data in SAP AR Invoice (HaNa DB)
function SAPUpdateARInvoiceData($SubDB,$orderid)    
{
	$IISMethods = new IISMethods();
	$SubDBName=$SubDB->getDBName();
	$sapentrydate=$IISMethods->getdatetime();
	$saptablename=$SubDBName.'.OINV';
	$sapsubtablename=$SubDBName.'.INV1';
	

	$qryord="SELECT distinct o.*,pm.sappersonid,pm.personname,isnull(pm.address,'') as pm_address 
		FROM tblorder o 
		inner join tblorderdetail od on od.orderid=o.id 
		inner join tblpersonmaster pm on pm.id=o.uid 
		WHERE CONVERT(VARCHAR(255), o.id)=:orderid and isnull(o.saporderid,'')<>''";
	$ordparms = array(
		':orderid'=>$orderid,
	);
	$resord=self::getmenual($qryord,$ordparms);
	
	if(sizeof($resord) > 0)
	{
		$roword=$resord[0];

		$saporderid=$roword['saporderid'];

		$qrysap = "SELECT ifnull((select top 1 \"AbsEntry\" from ".$SubDBName.".OFPR where '$sapentrydate' between \"F_DueDate\" and \"T_DueDate\"),0) as \"FinancePeriod\" 
		from ".$saptablename." where \"DocEntry\"='$saporderid'";
		//echo $qrysap;
		$ressap=$SubDB->getmenual($qrysap);
		$rowsap=$ressap[0];


		$FinancePeriod=$rowsap['FinancePeriod'];
		$JrnlMemo='A/R Invoices - '.$roword['sappersonid'];
		$UpdateTS=$IISMethods->getcurrtimefullstring();     //date(His)


		$qryo="update ".$saptablename." set 
		\"CardCode\"='$roword[sappersonid]',\"CardName\"='$roword[personname]',\"Address\"='$roword[pm_address]',\"VatSum\"='$roword[totaltax]',\"DocTotal\"='$roword[totalpaid]',
		\"GrosProfit\"='$roword[totaltaxableamt]',\"Comments\"='$roword[ordernotes]',\"JrnlMemo\"='$JrnlMemo',\"VatSumSy\"='$roword[totaltax]',\"DocTotalSy\"='$roword[totalpaid]',
		\"PaidSys\"='$roword[totaltaxableamt]',\"UpdateDate\"='$sapentrydate',\"Max1099\"='$roword[totalpaid]',\"UpdateTS\"='$UpdateTS'
		where \"DocEntry\"='$saporderid'";
		$reso = $SubDB->sapexecute($qryo);



		$qrydelod="delete from ".$sapsubtablename." where \"DocEntry\"='$saporderid'";
		$resdelod = $SubDB->sapexecute($qrydelod);


		
		$qryod="SELECT distinct od.*,im.sapitemid,tx.saptaxid 
		FROM tblorderdetail od 
		inner join tblitemmaster im on im.id=od.itemid
		inner join tbltax tx on tx.id=im.gstid
		WHERE CONVERT(VARCHAR(255), od.orderid)=:orderid ";
		$odparms = array(
			':orderid'=>$orderid,
		);
		$resod=self::getmenual($qryod,$odparms);
		if(sizeof($resod) > 0)
		{
			for($i=0;$i<sizeof($resod);$i++)
			{
				$rowod=$resod[$i];

				$subqry="INSERT INTO ".$sapsubtablename." (\"DocEntry\",\"LineNum\",\"BaseRef\",\"ItemCode\",\"Dscription\",\"Quantity\",\"OpenQty\",\"Price\",\"Currency\",\"Rate\",\"DiscPrcnt\",\"LineTotal\",\"TotalFrgn\",\"OpenSum\",\"OpenSumFC\",\"VendorNum\",\"SerialNum\",\"WhsCode\",\"Commission\",\"AcctCode\",\"TaxStatus\",\"GrossBuyPr\",\"PriceBefDi\",\"DocDate\",\"OpenCreQty\",\"SubCatNum\",\"BaseCard\",\"TotalSumSy\",\"OpenSumSys\",\"Project\",\"VatPrcnt\",\"VatGroup\",\"PriceAfVAT\",\"Height1\",\"Height2\",\"Width1\",\"Width2\",\"Length1\",\"length2\",\"Volume\",\"VolUnit\",\"Weight1\",\"Weight2\",\"Factor1\",\"Factor2\",\"Factor3\",\"Factor4\",\"PackQty\",\"SWW\",\"VatSum\",\"VatSumFrgn\",\"VatSumSy\",\"FinncPriod\",\"ObjType\",\"DedVatSum\",\"DedVatSumF\",\"DedVatSumS\",\"DistribSum\",\"DstrbSumFC\",\"DstrbSumSC\",\"GrssProfit\",\"GrssProfSC\",\"GrssProfFC\",\"VisOrder\",\"INMPrice\",\"PoTrgEntry\",\"Address\",\"TaxType\",\"FreeTxt\",\"PickOty\",\"VatAppld\",\"VatAppldFC\",\"VatAppldSC\",\"BaseQty\",\"BaseOpnQty\",\"VatDscntPr\",\"WtLiable\",\"EquVatPer\",\"EquVatSum\",\"EquVatSumF\",\"EquVatSumS\",\"LineVat\",\"LineVatlF\",\"LineVatS\",\"unitMsr\",\"NumPerMsr\",\"ToStock\",\"ToDiff\",\"ExciseAmt\",\"TaxPerUnit\",\"TotInclTax\",\"StckDstSum\",\"ReleasQtty\",\"StockPrice\",\"ConsumeFCT\",\"LstByDsSum\",\"StckINMPr\",\"LstBINMPr\",\"StckDstFc\",\"StckDstSc\",\"LstByDsFc\",\"LstByDsSc\",\"StockSum\",\"StockSumFc\",\"StockSumSc\",\"StckSumApp\",\"StckAppFc\",\"StckAppSc\",\"ShipToDesc\",\"StckAppD\",\"StckAppDFC\",\"StckAppDSC\",\"GTotal\",\"GTotalFC\",\"GTotalSC\",\"DistribExp\",\"GrossBase\",\"VatWoDpm\",\"VatWoDpmFc\",\"VatWoDpmSc\",\"TaxOnly\",\"QtyToShip\",\"DelivrdQty\",\"OrderedQty\",\"CogsAcct\",\"ChgAsmBoMW\",\"ActDelDate\",\"TaxDistSum\",\"TaxDistSFC\",\"TaxDistSSC\",\"AssblValue\",\"StockValue\",\"GPTtlBasPr\",\"unitMsr2\",\"NumPerMsr2\",\"PQTReqQty\",\"PcQuantity\",\"OpenRtnQty\",\"Surpluses\",\"DefBreak\",\"Shortages\",\"UomEntry\",\"UomEntry2\",\"UomCode\",\"UomCode2\",\"FromWhsCod\",\"RetireQty\",\"RetireAPC\",\"RetirAPCFC\",\"RetirAPCSC\",\"InvQty\",\"OpenInvQty\",\"RetCost\",\"LineVendor\",\"ISDistrb\",\"ISDistrbFC\",\"ISDistrbSC\",\"GPBefDisc\",\"ExtTaxRate\",\"ExtTaxSum\",\"ExtTaxSumF\",\"ExtTaxSumS\",\"StdItemId\",\"CommClass\",\"CESTCode\",\"CtrSealQty\") 
				values ('$saporderid','$i','','$rowod[sapitemid]','$rowod[itemname]','1','1','$rowod[price]','QAR','0','0','$rowod[price]','0','$rowod[price]','0','','?','01','0','400000','Y','0','$rowod[price]','$sapentrydate','1','','$roword[sappersonid]','$rowod[price]','$rowod[price]','','$rowod[igst]','$rowod[saptaxid]','$rowod[finalprice]','0','0','0','0','0','0','0','4','0','0','1','1','1','1','1','','$rowod[igsttaxamt]','0','$rowod[igsttaxamt]','$FinancePeriod','13','$rowod[igsttaxamt]','0','$rowod[igsttaxamt]','0','0','0','$rowod[taxable]','$rowod[taxable]','0','$i','$rowod[taxable]','','','Y','','0','0','0','0','0','0','0','N','0','0','0','0','$rowod[igsttaxamt]','0','$rowod[igsttaxamt]','Numbers','1','0','0','0','0','0','0','0','0','N','0','0','0','0','0','0','0','$rowod[taxable]','0','$rowod[taxable]','0','0','0','','0','0','0','$rowod[finalprice]','0','$rowod[finalprice]','N','-1','0','0','0','N','0','0','1','500005','N','$sapentrydate','0','0','0','0','0','0','Numbers','1','0','0','0','0','0','0','1','1','Nos','Nos','?','0','0','0','0','1','1','0','','0','0','0','$rowod[finalprice]','0','0','0','0','0','0','-1','0')";
				//echo '-----'.$subqry.'-----';
				$subres = $SubDB->sapexecute($subqry);


				//Update Order Details Data
				$updodqry=array(					
					'sapitemid'=>$rowod['sapitemid'],					
				);
				$extraodparams=array(
					'id'=>$rowod['id']
				);
				self::executedata('u','tblorderdetail',$updodqry,$extraodparams);
			}
		}
		
		
	}
}



//Update Store Order Stock in SAP Stock Deduct (HaNa DB)
function SAPUpdateItemStockData($SubDB,$storeorderid)    
{
	$config = new config();
	$IISMethods = new IISMethods();
	$SubDBName=$SubDB->getDBName();
	$sapentrydate=$IISMethods->getdatetime();
	

	$qrysod="SELECT distinct sod.id,sod.orderid,sod.itemid,sod.itemname,isnull(sod.qty,0) as qty,isnull(im.sapitemid,'') as sapitemid,im.categoryid
		FROM tblstoreorder so 
		inner join tblstoreorderdetail sod on sod.orderid=so.id 
		inner join tblitemmaster im on im.id=sod.itemid
		WHERE CONVERT(VARCHAR(255), so.id)=:orderid";
	$sodparms = array(
		':orderid'=>$storeorderid,
	);
	$ressod=self::getmenual($qrysod,$sodparms);
	
	if(sizeof($ressod) > 0)
	{

		for($i=0;$i<sizeof($ressod);$i++)
		{	
			$rowsod=$ressod[$i];
			$sapitemid=$rowsod['sapitemid'];

			if($sapitemid)
			{
				if($rowsod['categoryid'] == $config->getDefaultCatSaleableId() || $rowsod['categoryid'] == $config->getDefaultCatConsumableId())
				{

					$qty=$rowsod['qty'];
					

					$qrysap = "SELECT \"OnHand\" from ".$SubDBName.".OITW where \"ItemCode\"='$sapitemid'";
					$ressap=$SubDB->getmenual($qrysap);
					

					if(sizeof($ressap) > 0)
					{
						$rowsap=$ressap[0];

						$OnHand=$rowsap['OnHand'];

						$NewOnHand=0;
						if($OnHand > 0)
						{
							$NewOnHand=$OnHand;
						}

						$updStock=$NewOnHand-$qty;


						$qry1="update ".$SubDBName.".OITW set \"OnHand\"='$updStock' where \"ItemCode\"='$sapitemid'";
						$res1 = $SubDB->sapexecute($qry1);


						/********************** Start For Updation Log *******************/
						$unqid = $IISMethods->generateuuid();
						$ins_ary=array(
							'[id]'=>$unqid,
							'[type]'=>1,
							'[soid]'=>$rowsod['orderid'],
							'[sodid]'=>$rowsod['id'],
							'[itemid]'=>$rowsod['itemid'],
							'[itemname]'=>$rowsod['itemname'],
							'[sapitemid]'=>$rowsod['sapitemid'],
							'[updated_qty]'=>$qty,
							'[before_updateqty]'=>$NewOnHand,
							'[after_updateqty]'=>$updStock,
							'[timestamp]'=>$IISMethods->getdatetime(),
						);
						self::executedata('i','tblstoreorderstocklog',$ins_ary,'');
						/********************** End For Updation Log *******************/


					}
				}
			}	

		}
		
	}
}


//Update Store Return Order Stock in SAP Stock Deduct (HaNa DB)
function SAPUpdateReturnItemStockData($SubDB,$sodid,$returnqty)    
{
	$config = new config();
	$IISMethods = new IISMethods();
	$SubDBName=$SubDB->getDBName();
	$sapentrydate=$IISMethods->getdatetime();
	

	$qrysod="SELECT distinct sod.id,sod.orderid,sod.itemid,sod.itemname,isnull(im.sapitemid,'') as sapitemid,im.categoryid
		FROM tblserviceorderdetail sod 
		inner join tblitemmaster im on im.id=sod.itemid
		WHERE CONVERT(VARCHAR(255), sod.id)=:sodid";
	$sodparms = array(
		':sodid'=>$sodid,
	);
	$ressod=self::getmenual($qrysod,$sodparms);
	
	if(sizeof($ressod) > 0)
	{

		for($i=0;$i<sizeof($ressod);$i++)
		{	
			$rowsod=$ressod[$i];
			$sapitemid=$rowsod['sapitemid'];

			if($sapitemid)
			{
				if($rowsod['categoryid'] == $config->getDefaultCatSaleableId() || $rowsod['categoryid'] == $config->getDefaultCatConsumableId())
				{

					$qty=$returnqty;
					

					$qrysap = "SELECT \"OnHand\" from ".$SubDBName.".OITW where \"ItemCode\"='$sapitemid'";
					$ressap=$SubDB->getmenual($qrysap);
					

					if(sizeof($ressap) > 0)
					{
						$rowsap=$ressap[0];

						$OnHand=$rowsap['OnHand'];

						$NewOnHand=0;
						if($OnHand > 0)
						{
							$NewOnHand=$OnHand;
						}

						$updStock=$NewOnHand+$qty;


						$qry1="update ".$SubDBName.".OITW set \"OnHand\"='$updStock' where \"ItemCode\"='$sapitemid'";
						$res1 = $SubDB->sapexecute($qry1);


						/********************** Start For Updation Log *******************/
						
						$unqid = $IISMethods->generateuuid();
						$ins_ary=array(
							'[id]'=>$unqid,
							'[type]'=>2,
							'[soid]'=>$rowsod['orderid'],
							'[sodid]'=>$rowsod['id'],
							'[itemid]'=>$rowsod['itemid'],
							'[itemname]'=>$rowsod['itemname'],
							'[sapitemid]'=>$rowsod['sapitemid'],
							'[updated_qty]'=>$qty,
							'[before_updateqty]'=>$NewOnHand,
							'[after_updateqty]'=>$updStock,
							'[timestamp]'=>$IISMethods->getdatetime(),
						);
						self::executedata('i','tblstoreorderstocklog',$ins_ary,'');
						
						/********************** End For Updation Log *******************/


					}
				}
			}	

		}
		
	}
}



//Insert Issue Item Data in SAP AR Invoice Delivery (HaNa DB)
function SAPInsertARInvoiceDeliveryData($SubDB,$orderid)    
{
	$IISMethods = new IISMethods();
	$config = new config();
	$SubDBName=$SubDB->getDBName();
	$sapentrydate=$IISMethods->getdatetime();
	$saptablename=$SubDBName.'.ODLN';
	$sapsubtablename=$SubDBName.'.DLN1';
	

	$qryord="SELECT distinct so.*,pm.sappersonid,pm.personname,isnull(pm.address,'') as pm_address,isnull(pm.email,'') as pm_email 
		FROM tblstoreorder so 
		inner join tblstoreorderdetail sod on sod.orderid=so.id 
		inner join tblpersonmaster pm on pm.id=so.uid 
		WHERE CONVERT(VARCHAR(255), so.id)=:orderid and sod.catid in (:defaultcatsaleableid,:defaultcatconsumableid) and isnull(so.saporderid,'')=''";
	$ordparms = array(
		':orderid'=>$orderid,
		':defaultcatsaleableid'=>$config->getDefaultCatSaleableId(),
		':defaultcatconsumableid'=>$config->getDefaultCatConsumableId(),
	);
	$resord=self::getmenual($qryord,$ordparms);
	
	if(sizeof($resord) > 0)
	{
		$roword=$resord[0];

		$qrysap = "SELECT max(\"DocEntry\") as \"DocEntry\",max(\"DocNum\") as \"DocNum\",max(\"Ref1\") as \"Ref11\",max(\"TransId\") as \"TransId\",
		ifnull((select top 1 \"AbsEntry\" from ".$SubDBName.".OFPR where '$sapentrydate' between \"F_DueDate\" and \"T_DueDate\"),0) as \"FinancePeriod\",
		ifnull((select top 1 \"Ref1\" from ".$SubDBName.".ODLN where \"Handwrtten\"='N' order by \"DocEntry\" desc),0) as \"Ref1\",
		ifnull((select \"Series\" from ".$SubDBName.".NNM1 where \"ObjectCode\"='15' and \"Locked\"='N'),0) as \"Series\"  
		from ".$SubDBName.".ODLN where \"Handwrtten\"='N'";
		$ressap=$SubDB->getmenual($qrysap);
		$rowsap=$ressap[0];

		$DocEntry=0;
		if($rowsap['DocEntry'] > 0)
		{
			$DocEntry=$rowsap['DocEntry'];
		}
		$NewDocEntry=$DocEntry+1;

		$DocNum=0;
		if($rowsap['DocNum'] > 0)
		{
			$DocNum=$rowsap['DocNum'];
		}
		$NewDocNum=$DocNum+1;

		$Ref1=0;
		if($rowsap['Ref1'] > 0)
		{
			$Ref1=$rowsap['Ref1'];
		}
		$NewRef1=$Ref1+1;

		$TransId=0;
		if($rowsap['TransId'] > 0)
		{
			$TransId=$rowsap['TransId'];
		}
		$NewTransId=$TransId+1;

		$Series=0;
		if($rowsap['Series'] > 0)
		{
			$Series=$rowsap['Series'];
		}


		$FinancePeriod=$rowsap['FinancePeriod'];
		$JrnlMemo='Deliveries - '.$roword['sappersonid'];
		$DocTime=$IISMethods->getcurrtimestring();     //date(Hi)
		$CreateTS=$IISMethods->getcurrtimefullstring();     //date(His)
		$UpdateTS=$IISMethods->getcurrtimefullstring();     //date(His)

		$qry="INSERT INTO ".$saptablename." (\"DocEntry\",\"DocNum\",\"Handwrtten\",\"Printed\",\"DocDate\",\"DocDueDate\",\"CardCode\",\"CardName\",\"Address\",\"VatPercent\",\"VatSum\",\"VatSumFC\",\"DiscPrcnt\",\"DiscSum\",\"DiscSumFC\",\"DocCur\",\"DocRate\",\"DocTotal\",\"DocTotalFC\",\"PaidToDate\",\"PaidFC\",\"GrosProfit\",\"GrosProfFC\",\"Ref1\",\"Comments\",\"JrnlMemo\",\"TransId\",\"GroupNum\",\"DocTime\",\"CreateTran\",\"UpdInvnt\",\"UpdCardBal\",\"CntctCode\",\"SysRate\",\"CurSource\",\"VatSumSy\",\"DiscSumSy\",\"DocTotalSy\",\"PaidSys\",\"GrosProfSy\",\"UpdateDate\",\"CreateDate\",\"Volume\",\"VolUnit\",\"Weight\",\"WeightUnit\",\"Series\",\"TaxDate\",\"FinncPriod\",\"UserSign\",\"VatPaid\",\"VatPaidFC\",\"VatPaidSys\",\"UserSign2\",\"TotalExpns\",\"TotalExpFC\",\"TotalExpSC\",\"Address2\",\"StationID\",\"AqcsTax\",\"AqcsTaxFC\",\"AqcsTaxSC\",\"CashDiscPr\",\"CashDiscnt\",\"CashDiscFC\",\"CashDiscSC\",\"WTSum\",\"WTSumFC\",\"WTSumSC\",\"RoundDif\",\"RoundDifFC\",\"RoundDifSy\",\"PeyMethod\",\"Max1099\",\"ExpAppl\",\"ExpApplFC\",\"ExpApplSC\",\"DeferrTax\",\"WTApplied\",\"WTAppliedF\",\"WTAppliedS\",\"EquVatSum\",\"EquVatSumF\",\"EquVatSumS\",\"VATFirst\",\"NnSbAmnt\",\"NnSbAmntSC\",\"NbSbAmntFC\",\"ExepAmnt\",\"ExepAmntSC\",\"ExepAmntFC\",\"BaseAmnt\",\"BaseAmntSC\",\"BaseAmntFC\",\"CtlAccount\",\"PIndicator\",\"BaseVtAt\",\"BaseVtAtSC\",\"BaseVtAtFC\",\"NnSbVAt\",\"NnSbVAtSC\",\"NbSbVAtFC\",\"ExptVAt\",\"ExptVAtSC\",\"ExptVAtFC\",\"LYPmtAt\",\"LYPmtAtSC\",\"LYPmtAtFC\",\"ExpAnSum\",\"ExpAnSys\",\"ExpAnFrgn\",\"DpmAmnt\",\"DpmAmntSC\",\"DpmAmntFC\",\"DpmPrcnt\",\"PaidSum\",\"PaidSumFc\",\"PaidSumSc\",\"DpmAppl\",\"DpmApplFc\",\"DpmApplSc\",\"IsPaytoBnk\",\"TrackNo\",\"VersionNum\",\"LangCode\",\"TaxOnExp\",\"TaxOnExpFc\",\"TaxOnExpSc\",\"TaxOnExAp\",\"TaxOnExApF\",\"TaxOnExApS\",\"DpmVat\",\"DpmVatFc\",\"DpmVatSc\",\"DpmAppVat\",\"DpmAppVatF\",\"DpmAppVatS\",\"BuildDesc\",\"PayDuMonth\",\"ExtraMonth\",\"ExtraDays\",\"EDocStatus\",\"BaseDisc\",\"BaseDiscSc\",\"BaseDiscFc\",\"BaseDiscPr\",\"CreateTS\",\"UpdateTS\",\"DocDlvry\",\"PaidDpm\",\"PaidDpmF\",\"PaidDpmS\",\"FreeChrg\",\"FreeChrgFC\",\"FreeChrgSC\",\"NfeValue\",\"FoCTax\",\"FoCTaxFC\",\"FoCTaxSC\",\"FoCFrght\",\"FoCFrghtFC\",\"FoCFrghtSC\",\"SplitTax\",\"SplitTaxFC\",\"SplitTaxSC\",\"UseBilAddr\",\"CtActTax\",\"CtActTaxFC\",\"CtActTaxSC\",\"NnSbCuAmnt\",\"NnSbCuSC\",\"NnSbCuFC\",\"ExepCuAmnt\",\"ExepCuSC\",\"ExepCuFC\") 
		values ('$NewDocEntry','$NewDocNum','N','N','$sapentrydate','$sapentrydate','$roword[sappersonid]','$roword[personname]','$roword[pm_address]','0','$roword[totaltax]','0','0','0','0','QAR','1','$roword[totalpaid]','0','0','0','$roword[totaltaxableamt]','0','$NewRef1','$roword[ordernotes]','$JrnlMemo','$NewTransId','-1','$DocTime','N','G','D','0','1','L','$roword[totaltax]','0','$roword[totalpaid]','0','$roword[totaltaxableamt]','$sapentrydate','$sapentrydate','0','4','0','2','$Series','$sapentrydate','$FinancePeriod','1','0','0','0','1','0','0','0','$roword[pm_email]','2','0','0','0','0','0','0','0','0','0','0','0','0','0','','$roword[totalpaid]','0','0','0','N','0','0','0','0','0','0','N','0','0','0','0','0','0','0','0','0','140000','Default','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','N','?','10.00.170.07','8','0','0','0','0','0','0','0','0','0','0','0','0','','N','0','0','C','0','0','0','0','$CreateTS','$UpdateTS','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','N','0','0','0','0','0','0','0','0','0')";
		$res = $SubDB->sapexecute($qry);


		$NewNextNumber=$NewDocNum+1;

		$qry11="update ".$SubDBName.".NNM1 set \"NextNumber\"='$NewNextNumber' where \"Series\"='$Series'";
		$res11 = $SubDB->sapexecute($qry11);


		
		$qryod="SELECT distinct sod.*,im.sapitemid,tx.saptaxid
		FROM tblstoreorderdetail sod 
		inner join tblitemmaster im on im.id=sod.itemid
		inner join tbltax tx on tx.id=im.gstid
		WHERE CONVERT(VARCHAR(255), sod.orderid)=:orderid and sod.catid in (:defaultcatsaleableid,:defaultcatconsumableid) ";
		$odparms = array(
			':orderid'=>$orderid,
			':defaultcatsaleableid'=>$config->getDefaultCatSaleableId(),
			':defaultcatconsumableid'=>$config->getDefaultCatConsumableId(),
		);
		$resod=self::getmenual($qryod,$odparms);
		if(sizeof($resod) > 0)
		{
			for($i=0;$i<sizeof($resod);$i++)
			{
				$rowod=$resod[$i];

				$totalprice = $rowod['price']*$rowod['qty']; 


				$subqry="INSERT INTO ".$sapsubtablename." (\"DocEntry\",\"LineNum\",\"BaseRef\",\"ItemCode\",\"Dscription\",\"Quantity\",\"OpenQty\",\"Price\",\"Currency\",\"Rate\",\"DiscPrcnt\",\"LineTotal\",\"TotalFrgn\",\"OpenSum\",\"OpenSumFC\",\"VendorNum\",\"SerialNum\",\"WhsCode\",\"Commission\",\"AcctCode\",\"TaxStatus\",\"GrossBuyPr\",\"PriceBefDi\",\"DocDate\",\"OpenCreQty\",\"SubCatNum\",\"BaseCard\",\"TotalSumSy\",\"OpenSumSys\",\"Project\",\"VatPrcnt\",\"VatGroup\",\"PriceAfVAT\",\"Height1\",\"Height2\",\"Width1\",\"Width2\",\"Length1\",\"length2\",\"Volume\",\"VolUnit\",\"Weight1\",\"Weight2\",\"Factor1\",\"Factor2\",\"Factor3\",\"Factor4\",\"PackQty\",\"SWW\",\"VatSum\",\"VatSumFrgn\",\"VatSumSy\",\"FinncPriod\",\"ObjType\",\"DedVatSum\",\"DedVatSumF\",\"DedVatSumS\",\"DistribSum\",\"DstrbSumFC\",\"DstrbSumSC\",\"GrssProfit\",\"GrssProfSC\",\"GrssProfFC\",\"VisOrder\",\"INMPrice\",\"PoTrgEntry\",\"Address\",\"TaxType\",\"FreeTxt\",\"PickOty\",\"VatAppld\",\"VatAppldFC\",\"VatAppldSC\",\"BaseQty\",\"BaseOpnQty\",\"VatDscntPr\",\"WtLiable\",\"EquVatPer\",\"EquVatSum\",\"EquVatSumF\",\"EquVatSumS\",\"LineVat\",\"LineVatlF\",\"LineVatS\",\"unitMsr\",\"NumPerMsr\",\"ToStock\",\"ToDiff\",\"ExciseAmt\",\"TaxPerUnit\",\"TotInclTax\",\"StckDstSum\",\"ReleasQtty\",\"StockPrice\",\"ConsumeFCT\",\"LstByDsSum\",\"StckINMPr\",\"LstBINMPr\",\"StckDstFc\",\"StckDstSc\",\"LstByDsFc\",\"LstByDsSc\",\"StockSum\",\"StockSumFc\",\"StockSumSc\",\"StckSumApp\",\"StckAppFc\",\"StckAppSc\",\"ShipToDesc\",\"StckAppD\",\"StckAppDFC\",\"StckAppDSC\",\"GTotal\",\"GTotalFC\",\"GTotalSC\",\"DistribExp\",\"GrossBase\",\"VatWoDpm\",\"VatWoDpmFc\",\"VatWoDpmSc\",\"TaxOnly\",\"QtyToShip\",\"DelivrdQty\",\"OrderedQty\",\"CogsAcct\",\"ChgAsmBoMW\",\"ActDelDate\",\"TaxDistSum\",\"TaxDistSFC\",\"TaxDistSSC\",\"AssblValue\",\"StockValue\",\"GPTtlBasPr\",\"unitMsr2\",\"NumPerMsr2\",\"PQTReqQty\",\"PcQuantity\",\"OpenRtnQty\",\"Surpluses\",\"DefBreak\",\"Shortages\",\"UomEntry\",\"UomEntry2\",\"UomCode\",\"UomCode2\",\"FromWhsCod\",\"RetireQty\",\"RetireAPC\",\"RetirAPCFC\",\"RetirAPCSC\",\"InvQty\",\"OpenInvQty\",\"RetCost\",\"LineVendor\",\"ISDistrb\",\"ISDistrbFC\",\"ISDistrbSC\",\"GPBefDisc\",\"ExtTaxRate\",\"ExtTaxSum\",\"ExtTaxSumF\",\"ExtTaxSumS\",\"StdItemId\",\"CommClass\",\"CESTCode\",\"CtrSealQty\") 
				values ('$NewDocEntry','$i','','$rowod[sapitemid]','$rowod[itemname]','$rowod[qty]','$rowod[qty]','$rowod[price]','QAR','0','0','$totalprice','0','$totalprice','0','','?','01','0','400000','Y','0','$rowod[price]','$sapentrydate','$rowod[qty]','','$roword[sappersonid]','$totalprice','$totalprice','','$rowod[igst]','$rowod[saptaxid]','$rowod[finalprice]','0','0','0','0','0','0','0','0','0','0','1','1','1','1','$rowod[qty]','','$rowod[igsttaxamt]','0','$rowod[igsttaxamt]','$FinancePeriod','15','0','0','0','0','0','0','$rowod[taxable]','$rowod[taxable]','0','$i','$rowod[price]','','','Y','','0','0','0','0','0','0','0','N','0','0','0','0','$rowod[igsttaxamt]','0','$rowod[igsttaxamt]','Numbers','1','0','0','0','0','0','0','0','0','N','0','0','0','0','0','0','0','$rowod[taxable]','0','$rowod[taxable]','0','0','0','$roword[pm_email]','0','0','0','$rowod[finalprice]','0','$rowod[finalprice]','N','-1','0','0','0','N','0','0','$rowod[qty]','500005','N','$sapentrydate','0','0','0','0','0','0','Numbers','1','0','0','0','0','0','0','1','1','Nos','Nos','?','0','0','0','0','$rowod[qty]','$rowod[qty]','0','','0','0','0','$rowod[finalprice]','0','0','0','0','0','0','-1','0')";
				
				//echo '-----'.$subqry.'-----';
				$subres = $SubDB->sapexecute($subqry);

			}
		}	

		
		//Update Order Data
		$updqry=array(					
			'[saporderid]'=>$NewDocEntry,					
		);
		$extraparams=array(
			'[id]'=>$orderid
		);
		self::executedata('u','tblstoreorder',$updqry,$extraparams);
		
	}
}



//Insert Issue Item Data in SAP AR Invoice Return (HaNa DB)
function SAPInsertARInvoiceReturnData($SubDB,$orderid)    
{
	$IISMethods = new IISMethods();
	$config = new config();
	$SubDBName=$SubDB->getDBName();
	$sapentrydate=$IISMethods->getdatetime();
	$saptablename=$SubDBName.'.ORDN';
	$sapsubtablename=$SubDBName.'.RDN1';
	

	$qryord="SELECT distinct so.*,pm.sappersonid,pm.personname,isnull(pm.address,'') as pm_address,isnull(pm.email,'') as pm_email 
		FROM tblstorereturnorder so 
		inner join tblstorereturnorderdetail sod on sod.sorid=so.id 
		inner join tblpersonmaster pm on pm.id=so.memberid 
		WHERE CONVERT(VARCHAR(255), so.id)=:orderid and sod.catid in (:defaultcatsaleableid,:defaultcatconsumableid) and isnull(so.saporderid,'')=''";
	$ordparms = array(
		':orderid'=>$orderid,
		':defaultcatsaleableid'=>$config->getDefaultCatSaleableId(),
		':defaultcatconsumableid'=>$config->getDefaultCatConsumableId(),
	);
	//echo $qryord;
	//print_r($ordparms);
	$resord=self::getmenual($qryord,$ordparms);
	
	if(sizeof($resord) > 0)
	{
		$roword=$resord[0];

		$qrysap = "SELECT max(\"DocEntry\") as \"DocEntry\",max(\"DocNum\") as \"DocNum\",max(\"Ref1\") as \"Ref11\",
		ifnull((select top 1 \"AbsEntry\" from ".$SubDBName.".OFPR where '$sapentrydate' between \"F_DueDate\" and \"T_DueDate\"),0) as \"FinancePeriod\",
		ifnull((select top 1 \"Ref1\" from ".$SubDBName.".ORDN where \"Handwrtten\"='N' order by \"DocEntry\" desc),0) as \"Ref1\",
		ifnull((select \"Series\" from ".$SubDBName.".NNM1 where \"ObjectCode\"='16' and \"Locked\"='N'),0) as \"Series\"  
		from ".$SubDBName.".ORDN where \"Handwrtten\"='N'";
		$ressap=$SubDB->getmenual($qrysap);
		$rowsap=$ressap[0];

		$DocEntry=0;
		if($rowsap['DocEntry'] > 0)
		{
			$DocEntry=$rowsap['DocEntry'];
		}
		$NewDocEntry=$DocEntry+1;

		$DocNum=0;
		if($rowsap['DocNum'] > 0)
		{
			$DocNum=$rowsap['DocNum'];
		}
		$NewDocNum=$DocNum+1;

		$Ref1=0;
		if($rowsap['Ref1'] > 0)
		{
			$Ref1=$rowsap['Ref1'];
		}
		$NewRef1=$Ref1+1;

		$Series=0;
		if($rowsap['Series'] > 0)
		{
			$Series=$rowsap['Series'];
		}


		$FinancePeriod=$rowsap['FinancePeriod'];
		$JrnlMemo='Returns - '.$roword['sappersonid'];
		$DocTime=$IISMethods->getcurrtimestring();     //date(Hi)
		$CreateTS=$IISMethods->getcurrtimefullstring();     //date(His)
		$UpdateTS=$IISMethods->getcurrtimefullstring();     //date(His)

		$qry="INSERT INTO ".$saptablename." (\"DocEntry\",\"DocNum\",\"Handwrtten\",\"Printed\",\"DocDate\",\"DocDueDate\",\"CardCode\",\"CardName\",\"Address\",\"VatPercent\",\"VatSum\",\"VatSumFC\",\"DiscPrcnt\",\"DiscSum\",\"DiscSumFC\",\"DocCur\",\"DocRate\",\"DocTotal\",\"DocTotalFC\",\"PaidToDate\",\"PaidFC\",\"GrosProfit\",\"GrosProfFC\",\"Ref1\",\"Comments\",\"JrnlMemo\",\"GroupNum\",\"DocTime\",\"CreateTran\",\"UpdInvnt\",\"UpdCardBal\",\"CntctCode\",\"SysRate\",\"CurSource\",\"VatSumSy\",\"DiscSumSy\",\"DocTotalSy\",\"PaidSys\",\"GrosProfSy\",\"UpdateDate\",\"CreateDate\",\"Volume\",\"VolUnit\",\"Weight\",\"WeightUnit\",\"Series\",\"TaxDate\",\"FinncPriod\",\"UserSign\",\"VatPaid\",\"VatPaidFC\",\"VatPaidSys\",\"UserSign2\",\"TotalExpns\",\"TotalExpFC\",\"TotalExpSC\",\"Address2\",\"StationID\",\"AqcsTax\",\"AqcsTaxFC\",\"AqcsTaxSC\",\"CashDiscPr\",\"CashDiscnt\",\"CashDiscFC\",\"CashDiscSC\",\"WTSum\",\"WTSumFC\",\"WTSumSC\",\"RoundDif\",\"RoundDifFC\",\"RoundDifSy\",\"PeyMethod\",\"Max1099\",\"ExpAppl\",\"ExpApplFC\",\"ExpApplSC\",\"DeferrTax\",\"WTApplied\",\"WTAppliedF\",\"WTAppliedS\",\"EquVatSum\",\"EquVatSumF\",\"EquVatSumS\",\"VATFirst\",\"NnSbAmnt\",\"NnSbAmntSC\",\"NbSbAmntFC\",\"ExepAmnt\",\"ExepAmntSC\",\"ExepAmntFC\",\"BaseAmnt\",\"BaseAmntSC\",\"BaseAmntFC\",\"CtlAccount\",\"PIndicator\",\"BaseVtAt\",\"BaseVtAtSC\",\"BaseVtAtFC\",\"NnSbVAt\",\"NnSbVAtSC\",\"NbSbVAtFC\",\"ExptVAt\",\"ExptVAtSC\",\"ExptVAtFC\",\"LYPmtAt\",\"LYPmtAtSC\",\"LYPmtAtFC\",\"ExpAnSum\",\"ExpAnSys\",\"ExpAnFrgn\",\"DpmAmnt\",\"DpmAmntSC\",\"DpmAmntFC\",\"DpmPrcnt\",\"PaidSum\",\"PaidSumFc\",\"PaidSumSc\",\"DpmAppl\",\"DpmApplFc\",\"DpmApplSc\",\"IsPaytoBnk\",\"TrackNo\",\"VersionNum\",\"LangCode\",\"TaxOnExp\",\"TaxOnExpFc\",\"TaxOnExpSc\",\"TaxOnExAp\",\"TaxOnExApF\",\"TaxOnExApS\",\"DpmVat\",\"DpmVatFc\",\"DpmVatSc\",\"DpmAppVat\",\"DpmAppVatF\",\"DpmAppVatS\",\"BuildDesc\",\"PayDuMonth\",\"ExtraMonth\",\"ExtraDays\",\"EDocStatus\",\"BaseDisc\",\"BaseDiscSc\",\"BaseDiscFc\",\"BaseDiscPr\",\"CreateTS\",\"UpdateTS\",\"DocDlvry\",\"PaidDpm\",\"PaidDpmF\",\"PaidDpmS\",\"FreeChrg\",\"FreeChrgFC\",\"FreeChrgSC\",\"NfeValue\",\"FoCTax\",\"FoCTaxFC\",\"FoCTaxSC\",\"FoCFrght\",\"FoCFrghtFC\",\"FoCFrghtSC\",\"SplitTax\",\"SplitTaxFC\",\"SplitTaxSC\",\"UseBilAddr\",\"CtActTax\",\"CtActTaxFC\",\"CtActTaxSC\",\"NnSbCuAmnt\",\"NnSbCuSC\",\"NnSbCuFC\",\"ExepCuAmnt\",\"ExepCuSC\",\"ExepCuFC\") 
			values ('$NewDocEntry','$NewDocNum','N','N','$sapentrydate','$sapentrydate','$roword[sappersonid]','$roword[personname]','$roword[pm_address]','0','0','0','0','0','0','QAR','1','0','0','0','0','0','0','$NewRef1','$roword[comment]','$JrnlMemo','-1','$DocTime','N','G','D','0','1','L','0','0','0','0','0','$sapentrydate','$sapentrydate','0','4','0','2','$Series','$sapentrydate','$FinancePeriod','1','0','0','0','1','0','0','0','$roword[pm_email]','2','0','0','0','0','0','0','0','0','0','0','0','0','0','','0','0','0','0','N','0','0','0','0','0','0','N','0','0','0','0','0','0','0','0','0','140000','Default','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','N','?','10.00.170.07','8','0','0','0','0','0','0','0','0','0','0','0','0','','N','0','0','C','0','0','0','0','$CreateTS','$UpdateTS','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','N','0','0','0','0','0','0','0','0','0')";
		$res = $SubDB->sapexecute($qry);


		$NewNextNumber=$NewDocNum+1;

		$qry11="update ".$SubDBName.".NNM1 set \"NextNumber\"='$NewNextNumber' where \"Series\"='$Series'";
		$res11 = $SubDB->sapexecute($qry11);


		
		$qryod="SELECT distinct sod.*,im.sapitemid,tx.saptaxid
		FROM tblstorereturnorderdetail sod 
		inner join tblitemmaster im on im.id=sod.itemid
		inner join tbltax tx on tx.id=im.gstid
		WHERE CONVERT(VARCHAR(255), sod.sorid)=:orderid and sod.catid in (:defaultcatsaleableid,:defaultcatconsumableid) ";
		$odparms = array(
			':orderid'=>$orderid,
			':defaultcatsaleableid'=>$config->getDefaultCatSaleableId(),
			':defaultcatconsumableid'=>$config->getDefaultCatConsumableId(),
		);
		$resod=self::getmenual($qryod,$odparms);
		if(sizeof($resod) > 0)
		{
			for($i=0;$i<sizeof($resod);$i++)
			{
				$rowod=$resod[$i];

				$totalprice = $rowod['price']*$rowod['qty']; 


				$subqry="INSERT INTO ".$sapsubtablename." (\"DocEntry\",\"LineNum\",\"BaseRef\",\"ItemCode\",\"Dscription\",\"Quantity\",\"OpenQty\",\"Price\",\"Currency\",\"Rate\",\"DiscPrcnt\",\"LineTotal\",\"TotalFrgn\",\"OpenSum\",\"OpenSumFC\",\"VendorNum\",\"SerialNum\",\"WhsCode\",\"Commission\",\"AcctCode\",\"TaxStatus\",\"GrossBuyPr\",\"PriceBefDi\",\"DocDate\",\"OpenCreQty\",\"SubCatNum\",\"BaseCard\",\"TotalSumSy\",\"OpenSumSys\",\"Project\",\"VatPrcnt\",\"VatGroup\",\"PriceAfVAT\",\"Height1\",\"Height2\",\"Width1\",\"Width2\",\"Length1\",\"length2\",\"Volume\",\"VolUnit\",\"Weight1\",\"Weight2\",\"Factor1\",\"Factor2\",\"Factor3\",\"Factor4\",\"PackQty\",\"SWW\",\"VatSum\",\"VatSumFrgn\",\"VatSumSy\",\"FinncPriod\",\"ObjType\",\"DedVatSum\",\"DedVatSumF\",\"DedVatSumS\",\"DistribSum\",\"DstrbSumFC\",\"DstrbSumSC\",\"GrssProfit\",\"GrssProfSC\",\"GrssProfFC\",\"VisOrder\",\"INMPrice\",\"PoTrgEntry\",\"Address\",\"TaxType\",\"FreeTxt\",\"PickOty\",\"VatAppld\",\"VatAppldFC\",\"VatAppldSC\",\"BaseQty\",\"BaseOpnQty\",\"VatDscntPr\",\"WtLiable\",\"EquVatPer\",\"EquVatSum\",\"EquVatSumF\",\"EquVatSumS\",\"LineVat\",\"LineVatlF\",\"LineVatS\",\"unitMsr\",\"NumPerMsr\",\"ToStock\",\"ToDiff\",\"ExciseAmt\",\"TaxPerUnit\",\"TotInclTax\",\"StckDstSum\",\"ReleasQtty\",\"StockPrice\",\"ConsumeFCT\",\"LstByDsSum\",\"StckINMPr\",\"LstBINMPr\",\"StckDstFc\",\"StckDstSc\",\"LstByDsFc\",\"LstByDsSc\",\"StockSum\",\"StockSumFc\",\"StockSumSc\",\"StckSumApp\",\"StckAppFc\",\"StckAppSc\",\"ShipToDesc\",\"StckAppD\",\"StckAppDFC\",\"StckAppDSC\",\"GTotal\",\"GTotalFC\",\"GTotalSC\",\"DistribExp\",\"GrossBase\",\"VatWoDpm\",\"VatWoDpmFc\",\"VatWoDpmSc\",\"TaxOnly\",\"QtyToShip\",\"DelivrdQty\",\"OrderedQty\",\"CogsAcct\",\"ChgAsmBoMW\",\"ActDelDate\",\"TaxDistSum\",\"TaxDistSFC\",\"TaxDistSSC\",\"AssblValue\",\"StockValue\",\"GPTtlBasPr\",\"unitMsr2\",\"NumPerMsr2\",\"PQTReqQty\",\"PcQuantity\",\"OpenRtnQty\",\"Surpluses\",\"DefBreak\",\"Shortages\",\"UomEntry\",\"UomEntry2\",\"UomCode\",\"UomCode2\",\"FromWhsCod\",\"RetireQty\",\"RetireAPC\",\"RetirAPCFC\",\"RetirAPCSC\",\"InvQty\",\"OpenInvQty\",\"RetCost\",\"LineVendor\",\"ISDistrb\",\"ISDistrbFC\",\"ISDistrbSC\",\"GPBefDisc\",\"ExtTaxRate\",\"ExtTaxSum\",\"ExtTaxSumF\",\"ExtTaxSumS\",\"StdItemId\",\"CommClass\",\"CESTCode\",\"CtrSealQty\") 
					values ('$NewDocEntry','$i','','$rowod[sapitemid]','$rowod[itemname]','$rowod[qty]','$rowod[qty]','0','QAR','0','0','0','0','0','0','','?','01','0','400000','Y','0','0','$sapentrydate','$rowod[qty]','','$roword[sappersonid]','0','0','','0','$rowod[saptaxid]','0','0','0','0','0','0','0','0','4','0','0','1','1','1','1','$rowod[qty]','','0','0','0','$FinancePeriod','16','0','0','0','0','0','0','0','0','0','$i','0','','','Y','','0','0','0','0','0','0','0','N','0','0','0','0','0','0','0','Numbers','1','0','0','0','0','0','0','0','0','N','0','0','0','0','0','0','0','0','0','0','0','0','0','$roword[pm_email]','0','0','0','0','0','0','N','-1','0','0','0','N','0','0','0','500005','N','$sapentrydate','0','0','0','0','0','0','Numbers','1','0','0','0','0','0','0','1','1','Nos','Nos','?','0','0','0','0','$rowod[qty]','$rowod[qty]','0','','0','0','0','0','0','0','0','0','0','0','-1','0')";
				
				//echo '-----'.$subqry.'-----';
				$subres = $SubDB->sapexecute($subqry);

			}
		}	

		
		//Update Order Data
		$updqry=array(					
			'[saporderid]'=>$NewDocEntry,					
		);
		$extraparams=array(
			'[id]'=>$orderid
		);
		self::executedata('u','tblstorereturnorder',$updqry,$extraparams);
		
	}
}



//Send Vendor New Open RFQ Generate Email/SMS
function insertoperaionflowdetailsdata($opflowarray,$arraysize,$orderby,$startnodein)   
{
	$config = new config();
	$IISMethods = new IISMethods();

	if((int)$arraysize != (int)$orderby)
	{
		for($iv=0;$iv<$arraysize;$iv++)
		{
			if($orderby==0)
			{
				if($opflowarray[$iv]['nodeforin'] == '')
				{
					if($opflowarray[$iv]['nodeforin'])
					{
						$nodeforin=$opflowarray[$iv]['nodeforin'];
					}
					else
					{
						$nodeforin=0;
					}

					if($opflowarray[$iv]['nodeforout'])
					{
						$nodeforout=$opflowarray[$iv]['nodeforout'];
					}
					else
					{
						$nodeforout=0;
					}
					
					$subinsqry=array(					
						'[id]'=>$opflowarray[$iv]['id'],
						'[oflowid]'=>$opflowarray[$iv]['oflowid'],
						'[operationid]'=>$opflowarray[$iv]['operationid'],
						'[countid]'=>$opflowarray[$iv]['countid'],
						'[storeid]'=>$opflowarray[$iv]['storeid'],
						'[isserviceorder]'=>$opflowarray[$iv]['isserviceorder'],
						'[iscompulsory]'=>$opflowarray[$iv]['iscompulsory'],
						'[insgroupid]'=>$opflowarray[$iv]['insgroupid'],
						'[displayorder]'=>$orderby,
						'[timestamp]'=>$IISMethods->getdatetime(),
						'[nodeforin]'=>$nodeforin,
						'[nodeforout]'=>$nodeforout,
					);
					//echo $iv;
					//print_r($subinsqry);
					self::executedata('i','tbloperationflowdetail',$subinsqry,'');

					$orderby=$orderby + 1;
					self::insertoperaionflowdetailsdata($opflowarray,$arraysize,$orderby,$opflowarray[$iv]['nodeforout']);  
				}
			}else{
				if($opflowarray[$iv]['nodeid'] == $startnodein)
				{
					if($opflowarray[$iv]['nodeforin'])
					{
						$nodeforin=$opflowarray[$iv]['nodeforin'];
					}
					else
					{
						$nodeforin=0;
					}

					if($opflowarray[$iv]['nodeforout'])
					{
						$nodeforout=$opflowarray[$iv]['nodeforout'];
					}
					else
					{
						$nodeforout=0;
					}

					$subinsqry=array(					
						'[id]'=>$opflowarray[$iv]['id'],
						'[oflowid]'=>$opflowarray[$iv]['oflowid'],
						'[operationid]'=>$opflowarray[$iv]['operationid'],
						'[countid]'=>$opflowarray[$iv]['countid'],
						'[storeid]'=>$opflowarray[$iv]['storeid'],
						'[isserviceorder]'=>$opflowarray[$iv]['isserviceorder'],
						'[iscompulsory]'=>$opflowarray[$iv]['iscompulsory'],
						'[insgroupid]'=>$opflowarray[$iv]['insgroupid'],
						'[displayorder]'=>$orderby,
						'[timestamp]'=>$IISMethods->getdatetime(),
						'[nodeforin]'=>$nodeforin,
						'[nodeforout]'=>$nodeforout,
					);
					//print_r($subinsqry);
					//echo $iv;
					self::executedata('i','tbloperationflowdetail',$subinsqry,'');

					$orderby=$orderby + 1;
					self::insertoperaionflowdetailsdata($opflowarray,$arraysize,$orderby,$opflowarray[$iv]['nodeforout']);  
				}
			}
		}
	}else{
		return 0;
	}

}


//Insert Curl API Log Data
function InsertCurlApiLogData($ApiName,$ApiURL,$InputXML,$OutputXML,$httpcode,$total_time)
{
	$IISMethods = new IISMethods();
	
	$datetime=$IISMethods->getdatetime();
	$unqid = $IISMethods->generateuuid();

	$insdata=array(
		'[id]'=>$unqid,
		'[apiname]'=>$ApiName,
		'[apiurl]'=>$ApiURL,
		'[input_xml]'=>$InputXML,
		'[output_xml]'=>json_encode($OutputXML),
		'[httpcode]'=>$httpcode,
		'[total_time]'=>$total_time,
		'[entry_date]'=>$datetime
	);

	self::executedata('i','tblcurlapilog',$insdata,'');
	
}








    public function getDBName(){
		return $this->DBName;
	}

	public function setDBName($DBName){
		$this->DBName = $DBName;
	}

	public function getDBUser(){
		return $this->DBUser;
	}

	public function setDBUser($DBUser){
		$this->DBUser = $DBUser;
	}

	public function getDBHost(){
		return $this->DBHost;
	}

	public function setDBHost($DBHost){
		$this->DBHost = $DBHost;
	}

	public function getDBPass(){
		return $this->DBPass;
	}

	public function setDBPass($DBPass){
		$this->DBPass = $DBPass;
	}

	public function getDBPort(){
		return $this->DBPort;
	}

	public function setDBPort($DBPort){
		$this->DBPort = $DBPort;
	}

	public function getDBType(){
		return $this->DBType;
	}

	public function setDBType($DBType){
		$this->DBType = $DBType;
	}

	public function getDBConn(){
		return $this->DBConn;
	}

	public function setDBConn($DBConn){
		$this->DBConn = $DBConn;
	}

	public function getErrorlog(){
		return $this->errorlog;
	}

	public function setErrorlog($errorlog){
		$this->errorlog = $errorlog;
	}
}


?>