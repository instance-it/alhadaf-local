<input type="hidden" id="ordby" value="desc">
<input type="hidden" id="sortingactivepage" value="1">
<input type="hidden" id="ordbycolumnname" value="primary_date">

<div class="sidebar-wrapper sidebar-theme">            
    <nav id="sidebar">
        <div class="profile-info d-lg-none">
            <figure class="user-cover-image"></figure>
            <div class="user-info">
                <img src="assets/img/user-profile.jpg" alt="avatar">
                <h6 class=""><?php echo $LoginInfo->getFullname(); ?></h6>
                <p class="">Admin</p>
            </div>
        </div>

        <div class="shadow-bottom d-lg-none"></div>
        <ul class="list-unstyled menu-categories" id="accordionMenu">
            
            <?php
            $qry="select * from tblmenuassign where menutypeid=1 order by timestamp asc";
            $parms = array(
                ':id'=>$menuid[$i],
            );
            $result_ary=$DB->getmenual($qry);

            if(sizeof($result_ary)>0)
			{
				for($i=0;$i<sizeof($result_ary);$i++)
				{
                    $rowqry=$result_ary[$i];
                    if($rowqry['isparent']==1) //only parent list 
        		  	{
                        $caretshow='';
                        $alias=$rowqry['alias'];
                        $alias1=$rowqry['alias'];
                        $formname=$rowqry['formname'];
                        $datatoggle='tooltip';
                        if($rowqry['isindividual']==1 || $rowqry['alias']=='reports.php') //only individual right
                        {
                            $lihide="d-none";
                            $Pagerights =$IISMethods->getpageaccess($LoginInfo->getUserrights(), trim($rowqry['alias']));
                            try{
                                if($rowqry['containright']==0) //contain right check
                                {
                                    $lihide="";
                                }
                                else if(  (sizeof($Pagerights)>0 ?  $Pagerights->getViewright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1)
                                {
                                    $lihide="";
                                }
                            }
                            catch (Error  $e) 
                            {
                                if($IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1)
                                {
                                    $lihide="";
                                }
                            }

                        }
                        else if($rowqry['isindividual']==0) //caret show & href manage
                        {
                            $datatoggle='collapse';
                            $alias='';
                            $caretshow='<div class="sub-icon"><i class="bi bi-chevron-right"></i></div>';

                            //$alias='';
                        }
                        ?>
                        <li class="menu <?php echo $lihide; ?>" id="li<?php echo $alias; ?>">
                            <a pagename="<?php echo $alias; ?>" formname="<?php echo $formname; ?>" data-menuid=<?php echo $rowqry['id']; ?> aria-expanded="true" class="dropdown-toggle collapsed" data-toggle="collapse" data-target="#subMenu<?php echo $alias1; ?>">
                                <div class=""><i class="<?php echo $rowqry['iconstyle'].' '.$rowqry['iconclass'] ?>"></i><span><?php echo $rowqry['textname'] ?></span></div>
                                <?php echo $caretshow ?>
                            </a>
                            <?php
                            $parentid=$rowqry['id'];
                            $qry="select * from tblmenuassign where menutypeid=1 AND parentid='$parentid' order by timestamp asc";
                            $parms = array(
                                ':parentid'=>$parentid,
                            );
                            $resassignqry=$DB->getmenual($qry);
                            if(sizeof($resassignqry)>0)
                            {   
                                echo '<ul class="collapse submenu list-unstyled" id="subMenu'.$alias1.'" data-parent="#accordionMenu">';
                                echo '<li id="li'.$alias.'" class="d-none"><a pagename="'.$alias1.'" formname="'.$formname.'">'.$rowqry['textname'].'</a></li>';
                                for($j=0;$j<sizeof($resassignqry);$j++)
                                {
                                    $rowassignqry=$resassignqry[$j];
                                    $Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(), trim($rowassignqry['alias']));
                                    //print_r($Pagerights);
                                    try{
                                        if($rowassignqry['containright']==0) //contain right check
                                        {
                                            echo '<li id="li'.$rowassignqry['alias'].'"><a pagename="'.$rowassignqry['alias'].'" formname="'.$rowassignqry['formname'].'">'.$rowassignqry['textname'].'</a></li>';
                                        }
                                        else if(  (sizeof($Pagerights)>0 ?  $Pagerights->getViewright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1)
                                        {
                                            echo '<li id="li'.$rowassignqry['alias'].'"><a pagename="'.$rowassignqry['alias'].'" formname="'.$rowassignqry['formname'].'">'.$rowassignqry['textname'].'</a></li>';
                                        }
                                    }
                                    catch (Error  $e) 
                                    {
                                        //echo $LoginInfo->getUtypeid();
                                        //echo $IISMethods->checkutypeexist($adminutype,$LoginInfo->getUtypeid());
                                        if($IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1)
                                        {
                                            echo '<li id="li'.$rowassignqry['alias'].'"><a pagename="'.$rowassignqry['alias'].'" formname="'.$rowassignqry['formname'].'">'.$rowassignqry['textname'].'</a></li>';
                                        }
                                    }
                                }
                                echo '</ul>';
                            }
                            ?>
                        </li>
                        <?php
                    }
                }
            }
            ?>
        </ul>
    </nav>
</div>