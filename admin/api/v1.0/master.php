<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	

	if($action=='listmaster')   
	{
		$qry="SELECT tm.*,isnull((SELECT count(id) from tblmenuassign where menutypeid=1 AND parentid=cast(tm.id as varchar(50))),0) as totl from tblmenuassign tm 
		where menutypeid=1 AND isnull((SELECT count(id) from tblmenuassign where menutypeid=1 AND parentid=cast(tm.id as varchar(50))),0) > 0 order by timestamp asc";
		$parms = array();
		$result_ary=$DB->getmenual($qry);

		$htmldata='';
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];

				//$htmldata.='<div class="masonry-widget">'; 
				$htmldata.='<div class="masonry-widget-item">';
				$htmldata.='<div class="widget">';
				$htmldata.='<div class="widget-heading">';
				$htmldata.='<div class="row">';
				$htmldata.='<div class="col-12">';
				$htmldata.='<h5><i class="'.$row['iconstyle'].' '.$row['iconclass'].' bi-1x"></i> <span> '.$row['textname'].'</span></h5>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='<div class="widget-content">';
				$htmldata.='<div class="row">';
				$htmldata.='<div class="col-12 px-0">';
				$htmldata.='<ul> ';

				$subqry="SELECT * from tblmenuassign where menutypeid=1 AND parentid=:parentid order by timestamp asc";
				$subparms = array(
					':parentid'=>$row['id'],
				);
				$result_subary=$DB->getmenual($subqry,$subparms);
				if(sizeof($result_subary)>0)
				{
					for($j=0;$j<sizeof($result_subary);$j++)
					{
						$subrow=$result_subary[$j];
						$htmldata.='<li><a pagename="'.$subrow['alias'].'" class="MasterMenu" > <i class="'.$subrow['iconstyle'].' '.$subrow['iconclass'].'"></i><span class="card-title" > '.$subrow['textname'].'<spam></a></li>';
					}
				}
				$htmldata.='</ul>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	} 
	else if($action=='fillstate')   
	{
		$qry="select id,state from tblstatemaster";
		$parms = array();
		$result_ary=$DB->getmenual($qry,$parms);

		$htmldata='';
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['state'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	} 
	else if($action=='fillcity')   
	{
		$stateid=$IISMethods->sanitize($_POST['stateid']);
		$qry="select id,city from tblcitymaster where CONVERT(VARCHAR(255), stateid) =:stateid";
		$parms = array(
			':stateid'=>$stateid
		);
		$result_ary=$DB->getmenual($qry,$parms);

		$htmldata='';
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['city'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='fillprefix')   
	{
		$qry="SELECT id,prefix from tblprefixmaster";
		$result_ary=$DB->getmenual($qry);
			$htmldata.='<option value="">Select Prefix</option>';
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['prefix'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='fillcmptype') //Use by  (branchmaster)  
	{
		$qry="SELECT id,cmptype from tblcompanytype";
		$result_ary=$DB->getmenual($qry);
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'">'.$row['cmptype'].'</option>';
				}
				$response['data']=$htmldata;
			}
		}
		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='fillutype') //usertype  
	{
		$nostoreutype=$IISMethods->sanitize($_POST['nostoreutype']);  //1 When No Store Usertype

		$qry="SELECT id,usertype,ISNULL(hasweblogin,0) as hasweblogin,ISNULL(hasapplogin,0) as hasapplogin,ISNULL(hasposlogin,0) as hasposlogin from tblusertypemaster WHERE ISNULL(hasadmin,0)=0 AND id<>:memberutypeid AND id<>:guestutypeid ";
		$parms = array(
			':memberutypeid'=>$config->getMemberutype(),
			':guestutypeid'=>$config->getGuestutype()
		);
		// if($nostoreutype == 1)
		// {
		// 	$qry.=" AND id<>:storeutypeid";
		// 	$parms[':storeutypeid']=$config->getStoreutype();
		// }
		$result_ary=$DB->getmenual($qry,$parms);
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'" data-isweblogin="'.$row['hasweblogin'].'" data-isapplogin="'.$row['hasapplogin'].'" data-isposlogin="'.$row['hasposlogin'].'">'.$row['usertype'].'</option>';
				}
				$response['data']=$htmldata;
			}
		}
		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='fillstoreutype') //store usertype  
	{
		$qry="SELECT id,usertype,ISNULL(hasweblogin,0) as hasweblogin,ISNULL(hasapplogin,0) as hasapplogin from tblusertypemaster WHERE ISNULL(hasadmin,0)=0 AND id=:utypeid";
		$parms = array(
			':utypeid'=>$config->getStoreutype()
		);
		$result_ary=$DB->getmenual($qry,$parms);
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'" data-isweblogin="'.$row['hasweblogin'].'" data-isapplogin="'.$row['hasapplogin'].'">'.$row['usertype'].'</option>';
				}
				$response['data']=$htmldata;
			}
		}
		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='fillperson') //service (Empmaster)  
	{
		 $preferredusertypeid=$IISMethods->sanitize($_POST['preferredusertypeid']); /*where subcategoryid like :smcategory */
		 $qry="SELECT pm.id,pm.personname from tblpersonmaster pm
		inner join tblpersonutype ut on ut.pid=pm.id where ut.utypeid=:utypeid AND isnull(pm.accgrpid,'') <> '04034326-6FC5-45E5-ABC0-284BEF03CF9A' group by pm.id,pm.personname; ";
		$parms = array(
			'utypeid'=>$preferredusertypeid,
		);
		//print_r($parms);
		$status=0;
		$result_ary=$DB->getmenual($qry,$parms);
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				$htmldata.='<option value="">Select Person Name</option>';
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'">'.$row['personname'].'</option>';
				}
				$response['data']=$htmldata;
			}
			$status=1;
			$message=$errmsg['success'];
		}
		
	}
	else if($action=='fillgsttype') //GST Slab  (Servicemaster)  
	{
		$qry="SELECT id,regtype from tblcompanyregtype";
		$result_ary=$DB->getmenual($qry);
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'">'.$row['regtype'].'</option>';
				}
				$response['data']=$htmldata;
			}
		}
		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='fillcategory')   
	{
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);
		$isall=$IISMethods->sanitize($_POST['isall']);
		$ismshippkgcourse=$IISMethods->sanitize($_POST['ismshippkgcourse']);

		$qry="select c.id,c.category,c.iswebattribute,c.iscourse,c.iscompositeitem from tblcategory c where c.isactive=1 ";
		$parms = array(
			
		);
		if($ismshippkgcourse == 1)
		{
			$qry.=" and c.id in (:defaultcatmembershipid,:defaultcatcourseid,:defaultcatpackageid)  ";

			$parms[':defaultcatmembershipid']=$config->getDefaultCatMembershipId();	
			$parms[':defaultcatcourseid']=$config->getDefaultCatCourseId();	
			$parms[':defaultcatpackageid']=$config->getDefaultCatPackageId();	
		}
		$qry.=" order by (case when (c.displayorder>0) then c.displayorder else 99999 end) ";
		$result_ary=$DB->getmenual($qry,$parms);
		$htmldata='';

		if($selectoption == 1)
		{
			$htmldata.='<option value="" data-iswebattribute="" data-iscourse="" data-iscompositeitem="">Select Category</option>';
		}

		if($isall == 1)
		{
			$htmldata.='<option value="%" data-iswebattribute="1" data-iscourse="1" data-iscompositeitem="1">All</option>';
		}

		
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'" data-iswebattribute="'.$row['iswebattribute'].'" data-iscourse="'.$row['iscourse'].'" data-iscompositeitem="'.$row['iscompositeitem'].'">'.$row['category'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	} 
	//Fill Sub Category
	else if($action == 'fillsubcategory')
	{
		$categoryid=$IISMethods->sanitize($_POST['categoryid']);
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);
		$isall=$IISMethods->sanitize($_POST['isall']);

		$parms = array(
			':categoryid'=>$categoryid,
		);
		$qry="select sc.id,sc.subcategory from tblsubcategory sc where sc.isactive=1 and CONVERT(VARCHAR(255), sc.categoryid) like :categoryid 
			order by (case when (sc.displayorder>0) then sc.displayorder else 99999 end)";
		$result_ary=$DB->getmenual($qry,$parms);

		$htmldata='';
		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Sub Category</option>';
		}

		if($isall == 1)
		{
			$htmldata.='<option value="%">All</option>';
		}

		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['subcategory'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
	//Fill Composite Category
	else if($action=='fillcompositecategory')   
	{
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);
		$isall=$IISMethods->sanitize($_POST['isall']);

		$qry="select c.id,c.category 
			from tblcategory c 
			inner join tblitemmaster im on im.categoryid=c.id 
			where c.isactive=1 and isnull(im.isactive,0)=1 and isnull(im.iscompositeitem,0)=0 and c.id not in (:defaultcatmembershipid,:defaultcatcourseid,:defaultcatpackageid) 
			group by c.id,c.category,c.displayorder  
			order by (case when (c.displayorder>0) then c.displayorder else 99999 end)";
		$params=array(
			':defaultcatmembershipid'=>$config->getDefaultCatMembershipId(),
			':defaultcatcourseid'=>$config->getDefaultCatCourseId(),
			':defaultcatpackageid'=>$config->getDefaultCatPackageId(),
		);	
		$result_ary=$DB->getmenual($qry,$params);
		$htmldata='';

		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Category</option>';
		}

		if($isall == 1)
		{
			$htmldata.='<option value="%">All</option>';
		}

		
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['category'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	} 
	//Fill Composite Sub Category
	else if($action == 'fillcompositesubcategory')
	{
		$categoryid=$IISMethods->sanitize($_POST['categoryid']);
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);

		$parms = array(
			':categoryid'=>$categoryid,
		);
		$qry="select sc.id,sc.subcategory 
			from tblsubcategory sc 
			inner join tblitemmaster im on im.subcategoryid=sc.id 
			where sc.isactive=1 and isnull(im.isactive,0)=1 and isnull(im.iscompositeitem,0)=0 and CONVERT(VARCHAR(255), sc.categoryid) =:categoryid 
			group by sc.id,sc.subcategory,sc.displayorder  
			order by (case when (sc.displayorder>0) then sc.displayorder else 99999 end)";
		$result_ary=$DB->getmenual($qry,$parms);

		$htmldata='';
		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Sub Category</option>';
		}

		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['subcategory'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
	//Fill Item 
	else if($action=='fillitems') 
	{
		$categoryid=$IISMethods->sanitize($_POST['categoryid']);
		$subcategoryid=$IISMethods->sanitize($_POST['subcategoryid']);

		$itemid=$IISMethods->sanitize($_POST['itemid']);
		$iscompositeitem=$IISMethods->sanitize($_POST['iscompositeitem']);
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);
		$alloption = $IISMethods->sanitize($_POST['alloption']);

		$qry="SELECT im.id,im.itemname,im.price,im.gstid,im.gsttypeid from tblitemmaster im where isnull(im.isactive,0)=1 and isnull(im.iscompositeitem,0) like :iscompositeitem and CONVERT(VARCHAR(255), im.categoryid) = :categoryid  and CONVERT(VARCHAR(255), im.subcategoryid) = :subcategoryid";
		$parms = array(
			':iscompositeitem'=>$iscompositeitem,
			':categoryid'=>$categoryid,
			':subcategoryid'=>$subcategoryid,
		);	
		if($itemid != '')
		{
			$qry.=" and im.id<>:itemid";
			$parms[':itemid']=$itemid;
		}
		$result_ary=$DB->getmenual($qry,$parms);
		
		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Item</option>';
		}
		else if($alloption == 1)
		{
			$htmldata.='<option value="0" data-price="0" data-gstid="" data-gsttypeid="">All Item</option>';
		}
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'" data-price="'.$row['price'].'" data-gstid="'.$row['gstid'].'" data-gsttypeid="'.$row['gsttypeid'].'">'.$row['itemname'].'</option>';
				}
				$response['data']=$htmldata;
			}
		}
		$status=1;
		$message=$errmsg['success'];
	}
	//Fill GST Type
	else if($action=='filltaxtype') 
	{
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);

		$qry="SELECT id,taxtype from tbltaxtype";
		$parms = array();	
		$result_ary=$DB->getmenual($qry,$parms);
		
		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Tax Type</option>';
		}
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'">'.$row['taxtype'].'</option>';
				}
				$response['data']=$htmldata;
			}
		}
		$status=1;
		$message=$errmsg['success'];
	}
	//Fill GST 
	else if($action=='fillgst') 
	{
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);

		$qry="SELECT id,taxname,sgst,cgst,igst from tbltax where isnull(isactive,0)=1 order by igst";
		$parms = array();	
		$result_ary=$DB->getmenual($qry,$parms);
		
		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Tax</option>';
		}
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'" data-sgst="'.$row['sgst'].'" data-cgst="'.$row['cgst'].'" data-igst="'.$row['igst'].'">'.$row['taxname'].'</option>';
				}
				$response['data']=$htmldata;
			}
		}
		$status=1;
		$message=$errmsg['success'];
	}
	//Fill Icon
	else if($action=='fillicon') 
	{
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);

		$qry="SELECT id,iconname,iconimg from tblitemiconmaster order by iconname";
		$parms = array();	
		$result_ary=$DB->getmenual($qry,$parms);
		
		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Icon</option>';
		}
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'" data-iconname="'.$row['iconname'].'" data-iconimg="'.$config->getImageurl().$row['iconimg'].'" data-thumbnail="'.$config->getImageurl().$row['iconimg'].'">'.$row['iconname'].'</option>';
				}
				$response['data']=$htmldata;
			}
		}
		$status=1;
		$message=$errmsg['success'];
	}
	// fill duration
	else if($action=='fillduration') 
	{
		$selectoption = $IISMethods->sanitize($_POST['selectoption']);
		$istext = $IISMethods->sanitize($_POST['istext']);
		$isall=$IISMethods->sanitize($_POST['isall']);

		$isonce=0;
		if($IISMethods->sanitize($_POST['isonce']) > 0 || $IISMethods->sanitize($_POST['isonce']) == '%')
		{
			$isonce = $IISMethods->sanitize($_POST['isonce']);
		}

		$qry="SELECT id,name, day,daytext from tbldurationmaster where isactive = 1 and isonce like :isonce order by displayorder";
		$parms = array(
			':isonce'=>$isonce,
		);	
		
		$result_ary=$DB->getmenual($qry,$parms);
		
		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Duration</option>';
		}
		if($isall == 1)
		{
			$htmldata.='<option value="%">All</option>';
		}

		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{
				if($istext == 1)
				{
					for($i=0;$i<sizeof($result_ary);$i++)
					{
						$row=$result_ary[$i];
						$htmldata.='<option value="'.$row['id'].'" data-day="'.$row['day'].'" data-daytext="'.$row['daytext'].'">'.$row['daytext'].'</option>';
					}
				}
				else
				{
					for($i=0;$i<sizeof($result_ary);$i++)
					{
						$row=$result_ary[$i];
						$htmldata.='<option value="'.$row['id'].'" data-day="'.$row['day'].'" data-name="'.$row['name'].'">'.$row['name'].'</option>';
					}
				}
				$response['data']=$htmldata;
			}
		}
		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='fillempstore') //usertype  
	{
		$isall=$IISMethods->sanitize($_POST['isall']);

		$qry="SELECT id,storename from tblstoremaster WHERE ISNULL(isactive,0) = 1 AND ISNULL(isdelete,0) = 0";
		$parms = array();
		
		$result_ary=$DB->getmenual($qry,$parms);
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				if($isall == 1)
				{
					$htmldata.='<option value="%">All</option>';
				}
				
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'">'.$row['storename'].'</option>';
				}
				$response['data']=$htmldata;
			}
		}
		$status=1;
		$message=$errmsg['success'];
	}
	else if($action == 'fillmember')
	{
		$isall = $IISMethods->sanitize($_POST['isall']);
		 $qry="SELECT distinct pm.id,pm.personname,pm.contact
			from tblpersonmaster pm 
			inner join tblpersonutype pu on pu.pid = pm.id
			where ISNULL(pm.isdelete,0)=0 and pm.id <> :adminuid AND pu.utypeid=:memberutypeid order by pm.personname";
		$parms = array(
			':memberutypeid'=>$config->getMemberutype(),
			':adminuid'=>$config->getAdminUserId(),
		);
		//print_r($parms);
		$status=0;
		$result_ary=$DB->getmenual($qry,$parms);
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				if($isall == 1)
				{
					$htmldata.='<option value="%">All</option>';
				}

				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'">'.$row['personname'].' ('.$row['contact'].')</option>';
				}
				$response['data']=$htmldata;
			}
			$status=1;
			$message=$errmsg['success'];
		}
	}
	else if($action=='filloperation') 
	{
		$selectoption = $IISMethods->sanitize($_POST['selectoption']);
		$istext = $IISMethods->sanitize($_POST['istext']);

		$qry="SELECT id,name from tbloperationmaster";
		$parms = array();	
		$result_ary=$DB->getmenual($qry,$parms);
		
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.=' <li class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-operationname="'.$row['name'].'" data-node="'.$row['id'].'">
						<span>'.$row['name'].'<input type="hidden" name="operationid" id="operationid" value="'.$row['id'].'"></span>
					</li>';
				}
				$response['data']=$htmldata;
			}
		}
		$status=1;
		$message=$errmsg['success'];
	}
	else if($action == 'fillmembertype')
	{
		$operationid = $_POST['operationid'];
		$countid = $_POST['countid'];
		$membertypeid = $_POST['membertypeid'];
		$qry="SELECT distinct mt.id,mt.type from tblmembertype mt where isnull(mt.isactive,0)=1";
		$parms = array();
		$status=0;
		$result_ary=$DB->getmenual($qry,$parms);
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'">'.$row['type'].'</option>';
				}
				$response['data']=$htmldata;
			}
			$response['operationid'] = $operationid;
			$response['countid'] = $countid;
			$response['membertypeid'] = $membertypeid;
			$status=1;
			$message=$errmsg['success'];
		}
	}
	else if($action=='fillpagetype') //Series Master
	{
		$qry="SELECT id,typestr,type FROM tbltypemaster where isshow=1 order by type asc";
		$result_ary=$DB->getmenual($qry);
		$status=0;
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'" typename="'.$row['typestr'].'">'.$row['type'].'</option>';
				}
				$response['data']=$htmldata;
			}
			$status=1;
			$message=$errmsg['success'];
		}	
	}
	else if($action=='fillelements') //prefix
	{
		$qry="SELECT id,elements FROM tblserieselementsmaster order by elements asc";
		$result_ary=$DB->getmenual($qry);
		$status=0;
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'" >'.$row['elements'].'</option>';
				}
				$response['data']=$htmldata;
			}
			$status=1;
			$message=$errmsg['success'];
		}	
	}
	else if($action=='gettagid') //Billprefix
	{

		$tags=explode(',',$_POST['tags']);
		for($i=0; $i<sizeof($tags); $i++)
		{
			$qry="SELECT id FROM tblserieselementsmaster WHERE elements = '".$tags[$i]."'";
			$result_ary=$DB->getmenual($qry);

			$rowtag = $result_ary[0];
			$response['result'][$i]['id']=$rowtag['id'];
		}
		$status = 1;
		$message=$errmsg['success'];
		$response['curmonth'] = date('m');
		$response['curyear'] = date('Y');	

	}
	else if($action=='fillcompany') //Company Master
	{
		$qry="SELECT id,companyname,prefix FROM tblcmpmaster order by isdefault desc";
		$result_ary=$DB->getmenual($qry);
		$status=0;
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'" data-prefix="'.$row['prefix'].'">'.$row['companyname'].'</option>';
				}
				$response['data']=$htmldata;
			}
			$status=1;
			$message=$errmsg['success'];
		}	
	}
	else if($action=='checkdatause') //service (Empmaster)  
	{
		//error_reporting(1);
		$id=$_POST['id'];
		$tblname=$_POST['tblname'];
		$cdate=date('Y-m-d');
		 $qry = "SELECT DISTINCT c.*,CASE WHEN(convert(date,c.enddate,103)< :cdate) THEN 1 ELSE 0 END AS chekcdate,
					CASE WHEN (cast (pc.id as  VARCHAR(50))= '') THEN 0 ELSE 1 END AS tmp 
			FROM tblseriesmaster c 
			left JOIN tblserieselements pc ON pc.seriesid = c.id WHERE c.id= :id   ";
		$seriesparams = array(
			':id'=>$id,
			':cdate'=>$cdate,
		);
		//	print_r($seriesparams);
		$res=$SubDB->getmenual($qry, $seriesparams);
		$checkdate=0;
		$rows=$res[0];
		if($rows['tmp']==1)
		{
			$status = 1;
			if($rows['chekcdate'] == 1){
				$checkdate=1;
			}
			
		}

		$eleqry = "SELECT SUBSTRING((select ','+ua.element AS [text()] from tblserieselements ua  
		where ua.seriesid=:id ORDER BY timestamp asc  FOR XML PATH ('')),2,1000) as ele";
		$eleparams = array(
			':id'=>$id,
		);
		$resele = $SubDB->getmenual($eleqry, $eleparams);
		$rowele = $resele[0];
		$ex=explode(',', $rowele['ele']);
		
		$response['element'] = $ex;	
		$response['preview'] = $rows['preview'];	
		$response['message'] = $message;
		$response['status'] = $status;
		$response['checkdate'] = $checkdate;


		
		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='fillcompositeitemtype') //Item Master
	{
		$qry="SELECT id,name,type FROM tblcompositeitemtype where isactive = 1 order by displayorder asc ";
		$result_ary=$DB->getmenual($qry);
		$status=0;
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'" data-type = "'.$row['type'].'">'.$row['name'].'</option>';
				}
				$response['data']=$htmldata;
			}
			$status=1;
			$message=$errmsg['success'];
		}	
	}
	//Fill Filter Store
	else if($action == 'fillfltstore')
	{
		$isall=$IISMethods->sanitize($_POST['isall']);

		$qry="select s.id,s.storename
			from tblstoremaster s 
			where ISNULL(s.isdelete,0)=0 order by s.storename";	
		$parms = array();	
		$result_ary=$DB->getmenual($qry,$parms);

		$htmldata='';
		if($isall == 1)
		{
			$htmldata.='<option value="%">All</option>';
		}

		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['storename'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
	//Fill Attribute
	else if($action=='fillattribute') 
	{
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);

		$qry="SELECT id,attributename from tblattributemaster where isactive=1 order by displayorder";
		$parms = array();	
		$result_ary=$DB->getmenual($qry,$parms);
		
		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Attribute</option>';
		}
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'">'.$row['attributename'].'</option>';
				}
				$response['data']=$htmldata;
			}
		}
		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='fillinstruction')   
	{
		$qry="select id,name from tblinstruction where isactive=1 order by displayorder";
		$parms = array();
		$result_ary=$DB->getmenual($qry,$parms);

		$htmldata='';
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['name'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
	else if($action == 'fillinstructiongroup')
	{
		$operationid = $_POST['operationid'];
		$countid = $_POST['countid'];
		$insgroupid = $_POST['insgroupid'];
		$qry="SELECT distinct ig.id,ig.groupname from tblinstructiongroup ig where isnull(ig.isactive,0)=1";
		$parms = array();
		$status=0;
		$result_ary=$DB->getmenual($qry,$parms);
		
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				$htmldata.='<option value="'.$mssqldefval['uniqueidentifier'].'" selected>Select Instruction Group</option>';
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'">'.$row['groupname'].'</option>';
				}
				$response['data']=$htmldata;
			}
			$response['operationid'] = $operationid;
			$response['countid'] = $countid;
			$response['insgroupid'] = $insgroupid;
			$status=1;
			$message=$errmsg['success'];
		}
	}
	else if($action=='listmemberusertype') //Member User Type
	{
		$isall=$IISMethods->sanitize($_POST['isall']);
		$qry="select distinct ut.id,ut.usertype as name,case when (ut.id=:guestusertype) then 1 else 0 end as isguest
			from tblusertypemaster ut 
			where ut.id in (:memberutype,:guestutype) 
			order by ut.usertype desc";
		$parms = array(
			':memberutype'=>$config->getMemberutype(),
			':guestutype'=>$config->getGuestutype(),
			':guestusertype'=>$config->getGuestutype(),
		);
		$result_ary=$DB->getmenual($qry,$parms);

		if($isall == 1)
		{
			$htmldata.='<option value="%">All</option>';
		}

		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'">'.$row['name'].'</option>';
				}
				
			}
		}
		$response['data']=$htmldata;
		$status=1;
		$message=$errmsg['success'];
	}
	//Fill Report Item 
	else if($action=='fillreportitem') 
	{
		$categoryid=$IISMethods->sanitize($_POST['categoryid']);
		$subcategoryid=$IISMethods->sanitize($_POST['subcategoryid']);
		$isnotmshippkgcourse=$IISMethods->sanitize($_POST['isnotmshippkgcourse']);   //For Service Order Report (Item Wise Sale Report)

		$isall = $IISMethods->sanitize($_POST['isall']);

		$qry="SELECT im.id,im.itemname from tblitemmaster im where CONVERT(VARCHAR(255), im.categoryid) like :categoryid  and CONVERT(VARCHAR(255), im.subcategoryid) like :subcategoryid";
		$parms = array(
			':categoryid'=>$categoryid,
			':subcategoryid'=>$subcategoryid,
		);	
		if($isnotmshippkgcourse == 1)
		{
			$qry.=" and im.categoryid not in (:defaultcatmembershipid,:defaultcatcourseid,:defaultcatpackageid)  ";
			$parms[':defaultcatmembershipid']=$config->getDefaultCatMembershipId();
			$parms[':defaultcatcourseid']=$config->getDefaultCatCourseId();
			$parms[':defaultcatpackageid']=$config->getDefaultCatPackageId();
		}
		else
		{
			$qry.=" and im.categoryid in (:defaultcatmembershipid,:defaultcatcourseid,:defaultcatpackageid)  ";
			$parms[':defaultcatmembershipid']=$config->getDefaultCatMembershipId();
			$parms[':defaultcatcourseid']=$config->getDefaultCatCourseId();
			$parms[':defaultcatpackageid']=$config->getDefaultCatPackageId();
		}
		$result_ary=$DB->getmenual($qry,$parms);
		
		if($isall == 1)
		{
			$htmldata.='<option value="%">All</option>';
		}

		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'">'.$row['itemname'].'</option>';
				}
				
			}
		}
		$response['data']=$htmldata;
		$status=1;
		$message=$errmsg['success'];
	}
	//Fill Lane
	else if($action=='filllane')   
	{
		$qry="select id,name from tbllanemaster order by name";
		$parms = array();
		$result_ary=$DB->getmenual($qry,$parms);

		$htmldata='';
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['name'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
	else if($action == 'fillrange')
	{
		$operationid = $_POST['operationid'];
		$countid = $_POST['countid'];
		$rangeid = $_POST['rangeid'];
		$qry="SELECT distinct r.id,r.rangename from tblrangemaster r where isnull(r.isactive,0)=1 order by r.rangename";
		$parms = array();
		$status=0;
		$result_ary=$DB->getmenual($qry,$parms);
		
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				//$htmldata.='<option value="'.$mssqldefval['uniqueidentifier'].'" selected>Select Range</option>';
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'">'.$row['rangename'].'</option>';
				}
				$response['data']=$htmldata;
			}
			$response['operationid'] = $operationid;
			$response['countid'] = $countid;
			$response['rangeid'] = $rangeid;
			$status=1;
			$message=$errmsg['success'];
		}
	}
	//List Report Range Data
	else if($action == 'listrptrange')
	{	
		$isall = $IISMethods->sanitize($_POST['isall']); 

		$qry="SELECT distinct rm.id,rm.rangename as name 
		from tblrangemaster rm 
		inner join tblrangelane rl on rl.rangeid=rm.id 
		where isnull(rm.isactive,0)=1 
		order by rm.rangename";	
		$params = array(
			
		);
		$result_ary=$DB->getmenual($qry,$params);

		if($isall == 1)
		{
			$htmldata.='<option value="%">All</option>';
		}

		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'">'.$row['name'].'</option>';
				}
				$response['data']=$htmldata;
			}
			
			
		}

		$status = 1;
		$message = $errmsg['success'];
	}
	//List Report Range Wise Lane Data
	else if($action == 'listrptrangelane')
	{	
		$rangeid=$IISMethods->sanitize($_POST['rangeid']);  
		$isall = $IISMethods->sanitize($_POST['isall']);

		$qry="SELECT distinct l.id,l.name 
		from tblrangemaster rm 
		inner join tblrangelane rl on rl.rangeid=rm.id
		inner join tbllanemaster l on l.id=rl.laneid
		where isnull(rm.isactive,0)=1 and convert(varchar(50),rl.rangeid) like :rangeid 
		order by l.name";	
		$params = array(
			':rangeid'=>$rangeid,
		);
		$result_ary=$DB->getmenual($qry,$params);

		if($isall == 1)
		{
			$htmldata.='<option value="%">All</option>';
		}

		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'">'.$row['name'].'</option>';
				}
				$response['data']=$htmldata;
			}
		}

		$status = 1;
		$message = $errmsg['success'];
	}
	//Fill Sale Person
	else if($action == 'fillsaleperson')
	{
		$isall = $IISMethods->sanitize($_POST['isall']);

		 $qry="SELECT distinct pm.id,pm.personname,pm.contact
			from tblpersonmaster pm 
			inner join tblpersonutype pu on pu.pid = pm.id
			where ISNULL(pm.isdelete,0)=0 and pm.id <> :adminuid AND pu.utypeid<>:memberutypeid 
			order by pm.personname";
		$parms = array(
			':memberutypeid'=>$config->getMemberutype(),
			':adminuid'=>$config->getAdminUserId(),
		);
		//print_r($parms);
		$status=0;
		$result_ary=$DB->getmenual($qry,$parms);
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{ 
				if($isall == 1)
				{
					$htmldata.='<option value="%">All</option>';
				}

				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'">'.$row['personname'].' ('.$row['contact'].')</option>';
				}
				$response['data']=$htmldata;
			}
			$status=1;
			$message=$errmsg['success'];
		}
	}
	








	
}


require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  