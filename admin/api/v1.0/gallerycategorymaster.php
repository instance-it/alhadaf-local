<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\gallerycategorymaster.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];

	if($action=='insertgallerycategory')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$category=$IISMethods->sanitize($_POST['categoryname']);
		$displayorder=$IISMethods->sanitize($_POST['displayorder']);
		$id=$IISMethods->sanitize($_POST['id']);

		if($category)
		{
			$insqry=array(
				'[category]'=>$category,
				'[displayorder]'=>$displayorder,
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT category from tblgallerycategorymaster where category=:category or displayorder=:displayorder";
				$parms = array(
					':category'=>$category,
					':displayorder'=>$displayorder,
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
					$insqry['[id]']=$unqid;	
					$insqry['[entry_uid]']=$uid;	
					$insqry['[entry_date]']=$IISMethods->getdatetime();

					$DB->executedata('i','tblgallerycategorymaster',$insqry,'');

					$status=1;
					$message=$errmsg['insert'];
				}
			}
			else if($formevent=='editright')
			{
				$qrychk="SELECT category from tblgallerycategorymaster where (category=:category or displayorder=:displayorder) AND id<>:id";
				$parms = array(
					':category'=>$category,
					':displayorder'=>$displayorder,
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
		
					$insqry['[update_uid]']=$uid;	
					$insqry['[update_date]']=$IISMethods->getdatetime();

					$extraparams=array(
						'[id]'=>$id
					);
					$DB->executedata('u','tblgallerycategorymaster',$insqry,$extraparams);
					$status=1;
					$message=$errmsg['update'];
				}
			}
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='deletegallerycategorymaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{

			$qrychk="SELECT case when(CONVERT(VARCHAR(255), gm.id) !='') then 1 else 0 end as tem,gc.category
			from tblgallerycategorymaster gc
			left join tblgallerymaster gm on gm.categoryid = gc.id
			where gc.id=:id";

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
				$DB->executedata('d','tblgallerycategorymaster','',$extraparams);
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

				$qrychk="SELECT case when(CONVERT(VARCHAR(255), gm.id) !='') then 1 else 0 end as tem,gc.category
				from tblgallerycategorymaster gc
				left join tblgallerymaster gm on gm.categoryid = gc.id
				where gc.id=:id";
				$parms = array(
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				$row=$result_ary[0];

				if($row['tem'] > 0)
				{
					$usemenu.=$row['category'].",";
				}
				else
				{
				
					$extraparams=array(
						'[id]'=>$IISMethods->sanitize($id)
					);
					$DB->executedata('d','tblgallerycategorymaster','',$extraparams);
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
	else if($action=='editgallerycategorymaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select id,category,displayorder from tblgallerycategorymaster where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);


			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['category']=$IISMethods->sanitize($row['category']);
				$response['displayorder']=$IISMethods->sanitize($row['displayorder']);

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
	else if($action=='listgallerycategorymaster')   
	{
		//error_reporting(1);
		$gallerycategorymasters=new gallerycategorymaster();
		$qry="select gcm.timestamp as primary_date,gcm.id,gcm.category,ISNULL(gcm.displayorder,0) AS displayorder 
			from tblgallerycategorymaster gcm where gcm.category like :catfilter or gcm.displayorder like :disorderfilter order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':catfilter'=>$filter,
			':disorderfilter'=>$filter,
		);
		$gallerycategorymasters=$DB->getmenual($qry,$parms,'gallerycategorymaster');
		
		if($responsetype=='HTML')
		{
			if($gallerycategorymasters)
			{
				$i=0;
				foreach($gallerycategorymasters as $gallerycategorymaster)
				{
					$id="'".$gallerycategorymaster->id."'";
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
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($gallerycategorymaster->id).'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($gallerycategorymaster->category).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($gallerycategorymaster->displayorder).'</td>';
					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($gallerycategorymasters)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($gallerycategorymasters);
		}

		$response['nextpage']=$nextpage;
		if(sizeof($gallerycategorymasters)==$per_page){ 
			$showdata=1; 
			$showentries=($nextpage*$per_page);
		}else{ 
			$showdata=0; 
			$showentries=(($nextpage-1)*($per_page))+ sizeof($gallerycategorymasters); }
		$response['loadmore']=$showdata;
		$response['datasize']=sizeof($gallerycategorymasters);
		$response['showentries']=$showentries;
	} 
}



require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  