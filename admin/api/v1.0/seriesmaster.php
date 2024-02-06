<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'/config/init.php';
require_once dirname(__DIR__, 3).'/config/apiconfig.php';
require_once dirname(__DIR__, 2).'/model/seriesmaster.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];

	if($action=='insertseriesmaster')   
	{
		//error_reporting(1);
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$cmpid=$IISMethods->sanitize($_POST['cmpid']);
		$pagetype=$IISMethods->sanitize($_POST['pagetype']);
		$startdate=$IISMethods->sanitize($_POST['startdate']);
		$enddate=$IISMethods->sanitize($_POST['enddate']);
		$startno=$IISMethods->sanitize($_POST['startno']);
		$endno=$IISMethods->sanitize($_POST['endno']);
		$prefix=$IISMethods->sanitize($_POST['prefix']);
		$elements=$_POST['tags'];
		$preview=$IISMethods->sanitize($_POST['preview']);
		
		$id=$IISMethods->sanitize($_POST['id']);	

		$datetime=$IISMethods->getdatetime();
		$qtyname="SELECT 
		ISNULL((select typestr from tbltypemaster where id=:pagetype),'') as typestr";
		$parms = array(
			':pagetype'=>$pagetype,
		);
		$result_name=$DB->getmenual($qtyname,$parms);
	

		if($pagetype && $enddate && $endno && $prefix )
		{	
	
			if($formevent=='addright')  // insert
			{
				$insqry=array(		
					'[cmpid]'=>$cmpid,			
					'[type]'=>$pagetype,
					'[typename]'=>$result_name[0]['typestr'],
					'[endno]'=>$endno,
					'[startno]'=>$startno,	
					'[startdate]'=>$startdate,	
					'[enddate]'=>$enddate,	
					'[prefix]'=>$prefix,		
					'[element]'=>$elements,
					'[preview]'=>$preview,	
					'[isactive]'=>1,			
				);
				$qrychk="SELECT * FROM tblseriesmaster WHERE prefix=:prefix or (type=:pagetype and (
					(convert(date,:startdate1,103) between convert(date,startdate,103) and convert(date,enddate,103))
					or
					(convert(date,startdate,103) between convert(date,:startdate,103) and convert(date,:enddate,103))
					))";
				$parms = array(
					':prefix'=>$prefix,
					':pagetype'=>$pagetype,
					':startdate'=>$startdate,
					':enddate'=>$enddate,
					':startdate1'=>$startdate,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				if(sizeof($result_ary) > 0)
				{
					$status=0;
					$message=$errmsg['isexist'];
				}
				else
				{
					$unqid = $IISMethods->generateuuid();
					$tags=explode(',',$elements);	
					$tg='';	
					for($i=0; $i<sizeof($tags); $i++)
					{
						$subunqid = $IISMethods->generateuuid();
						$qrychk="SELECT id FROM tblserieselementsmaster WHERE elements =:elements ";
						$parms = array(
							':elements'=>$tags[$i],
						);
						$result_ary=$DB->getmenual($qrychk,$parms);

						$rowtag=$result_ary[0];
						$eleid=$rowtag['id'];
						
						$insqry_sub=array(	
							'[id]'=>$subunqid,				
							'[seriesid]'=>$unqid,
							'[elementid]'=>$eleid,
							'[element]'=>$tags[$i],
							'[displayorder]'=>$i,
						);
						$DB->executedata('i','tblserieselements',$insqry_sub,'');
						if($i!=0)
							$tg.= ',';
						$tg.=$eleid;
					}
					$insqry['[elementid]']=$tg;
					$insqry['[id]']=$unqid;	
					$insqry['[entry_uid]']=$uid;	
					$insqry['[entry_date]']=$datetime;

					$DB->executedata('i','tblseriesmaster',$insqry,'');

					$status=1;
					$message=$errmsg['insert'];
				}
			}
			else if($formevent=='editright')  // Updtae
			{
				$insqry=array(					
					'[type]'=>$pagetype,
					'[typename]'=>$result_name[0]['typestr'],
					'[endno]'=>$endno,
					'[enddate]'=>$enddate,	
					'[prefix]'=>$prefix,		
					'[element]'=>$elements,
					'[preview]'=>$preview,			
				);

				$qrychk="SELECT * FROM tblseriesmaster WHERE prefix=:prefix or (type=:pagetype and (
					(convert(date,:startdate1,103) between convert(date,startdate,103) and convert(date,enddate,103))
					or
					(convert(date,startdate,103) between convert(date,:startdate,103) and convert(date,:enddate,103))
					)) AND id<>:id";
				$parms = array(
					':prefix'=>$prefix,
					':pagetype'=>$pagetype,
					':startdate'=>$startdate,
					':enddate'=>$enddate,
					':startdate1'=>$startdate,
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				if(sizeof($result_ary) > 0)
				{
					$status=0;
					$message=$errmsg['isexist'];
				}
				else
				{
					$extraparams_d=array(
						'[seriesid]'=>$id
					);
					$DB->executedata('d','tblserieselements','',$extraparams_d);
					$tags=explode(',',$elements);	
					$tg='';	
					for($i=0; $i<sizeof($tags); $i++)
					{
						$subunqid = $IISMethods->generateuuid();
						$qrychk="SELECT id FROM tblserieselementsmaster WHERE elements =:elements ";
						$parms = array(
							':elements'=>$tags[$i],
						);
						$result_ary=$DB->getmenual($qrychk,$parms);

						$rowtag=$result_ary[0];
						$eleid=$rowtag['id'];
						
						$insqry_sub=array(	
							'[id]'=>$subunqid,				
							'[seriesid]'=>$id,
							'[elementid]'=>$eleid,
							'[element]'=>$tags[$i],
							'[displayorder]'=>$i,
						);
						$DB->executedata('i','tblserieselements',$insqry_sub,'');
						if($i!=0)
							$tg.= ',';
						$tg.=$eleid;
					}

					$insqry['[update_uid]']=$uid;	
					$insqry['[update_date]']=$datetime;

					$insqry['[elementid]']=$tg;
					$extraparams=array(
						'id'=>$id
					);
					$DB->executedata('u','tblseriesmaster',$insqry,$extraparams);

					$status=1;
					$message=$errmsg['update'];
				}
			}
		}
		else
		{
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='deleteseriesmaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{
			$qrychk="SELECT DISTINCT CASE WHEN (convert(varchar(50),o.id) <> '' or convert(varchar(50),oi.id) <> '' or convert(varchar(50),so.id) <> '') THEN 1 ELSE 0 END AS tem 
				FROM tblseriesmaster s 
				left join tblorder o on o.seriesid=s.id
				left join tblorderinvoice oi on oi.seriesid=s.id
				left join tblstoreorder so on so.seriesid=s.id
				WHERE s.id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);
			$row=$result_ary[0];


			if($row['tem'] > 0)
			{
				$status=0;
				$message=$errmsg['inuse'];
			}
			else
			{
				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('d','tblseriesmaster','',$extraparams);

				$subextraparams=array(
					'[seriesid]'=>$id
				);
				$DB->executedata('d','tblserieselements','',$subextraparams);

				$status=1;
				$message=$errmsg['delete'];
			}
		}
		else if(sizeof($bulk)>0)
		{
			$usemenu='';
			for($i=0;$i<sizeof($bulk);$i++)
			{
				$id=$bulk[$i];

				$qrychk="SELECT DISTINCT CASE WHEN (convert(varchar(50),o.id) <> '' or convert(varchar(50),oi.id) <> '' or convert(varchar(50),so.id) <> '') THEN 1 ELSE 0 END AS tem 
					FROM tblseriesmaster s 
					left join tblorder o on o.seriesid=s.id
					left join tblorderinvoice oi on oi.seriesid=s.id
					left join tblstoreorder so on so.seriesid=s.id
					WHERE s.id=:id";
				$parms = array(
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				$row=$result_ary[0];


				if($row['tem'] > 0)
				{
					$usemenu.="";
				}
				else
				{
					$extraparams=array(
						'[id]'=>$id
					);
					$DB->executedata('d','tblseriesmaster','',$extraparams);
	
					$subextraparams=array(
						'[seriesid]'=>$id
					);
					$DB->executedata('d','tblserieselements','',$subextraparams);

				}
			}
			$status=1;
			$message=$errmsg['delete'].' '.$usemenu;
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='editseriesmaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="SELECT ts.*,tm.tblname
			from tblseriesmaster ts
			inner join tbltypemaster tm on tm.id=ts.type
			where ts.id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['cmpid']=$IISMethods->sanitize($row['cmpid']);
				$response['branchid']=$IISMethods->sanitize($row['branchid']);
				$response['type']=$IISMethods->sanitize($row['type']);
				$response['startno']=$IISMethods->sanitize($row['startno']);
				$response['endno']=$IISMethods->sanitize($row['endno']);
				$response['startdate']=$IISMethods->sanitize($row['startdate']);
				$response['enddate']=$IISMethods->sanitize($row['enddate']);
				$response['prefix']=$IISMethods->sanitize($row['prefix']);
				$response['preview']=$IISMethods->sanitize($row['preview']);
				$response['tblname']=$IISMethods->sanitize($row['tblname']);

			}

			$status=1;
			$message=$errmsg['datafound'];
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='listseriesmaster')   
	{
		$issidebarflt=$IISMethods->sanitize($_POST['issidebarflt']);

		$seriesmasters=new seriesmaster();
		$qry_main="SELECT ts.timestamp as primary_date,ts.id,tm.typestr,ts.endno,ts.startdate,ts.enddate,ts.prefix,ts.preview
		from tblseriesmaster ts
		INNER JOIN tbltypemaster tm on tm.id=ts.type
		WHERE (tm.typestr like :typestrflt OR ts.startdate like :startdateflt OR ts.enddate like :enddateflt OR ts.prefix like :prefixflt or ts.preview like :previewflt)";
		$parms = array(
			':typestrflt'=>$filter,
			':startdateflt'=>$filter,
			':enddateflt'=>$filter,
			':prefixflt'=>$filter,
			':previewflt'=>$filter,
		);

	
		$qry_extra=" order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$qry=$qry_main.$qry_extra;
		$seriesmasters=$DB->getmenual($qry,$parms,'seriesmaster');
		
		if($responsetype=='HTML')
		{
			if($seriesmasters)
			{
				$i=0;
				foreach($seriesmasters as $seriesmaster)
				{
					$id="'".$seriesmaster->id."'";
					$htmldata.='<tr data-index="'.$i.'">';
					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || (sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						$htmldata.='<td class="tbl-grid">';
						$htmldata.='<div class="dropdown table-dropdown">';
						$htmldata.='<button class="dropdown-toggle btn-tbl rounded-circle" type="button" id="tableDropdown3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>';
						$htmldata.='<div class="dropdown-menu" aria-labelledby="tableDropdown3">';

						if(((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1) || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1 ) //admin all right,alleditright - all person data show,selfedit - only self person data show
						{
							$htmldata.='<a class="dropdown-item" href="javascript:void(0);" onclick="editdata('.$IISMethods->sanitize($id).')"><i class="bi bi-pencil"></i> Edit</a>';
						}
						if(((sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1) || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1 ) //admin all right,alleditright - all person data show,selfedit - only self person data show
						{
							$htmldata.='<a class="dropdown-item" href="javascript:void(0);" onclick="deletedata('.$IISMethods->sanitize($id).')"><i class="bi bi-trash"></i> Delete</a>';
						}

						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					if((sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						$htmldata.='<td class="tbl-check d-none">';
						$htmldata.='<div class="text-center">';
						$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($seriesmaster->id).'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($seriesmaster->typestr).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($seriesmaster->endno).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($seriesmaster->startdate).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($seriesmaster->enddate).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($seriesmaster->prefix).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($seriesmaster->preview).'</td>';
					


					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				$htmldata.=file_get_contents($config->getNoDataFound());
				$message=$errmsg['nodatafound'];
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= $seriesmasters;

			$status=1;
			$message=$errmsg['success'];
		}

		$common_listdata=$seriesmasters;
	} 
	
}



require_once dirname(__DIR__, 3).'/config/apifoot.php';

?>

  