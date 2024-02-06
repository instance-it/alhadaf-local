/**
 * jQuery org-chart/tree plugin.
 *
 * Author: Wes Nolte
 * http://twitter.com/wesnolte
 *
 * Based on the work of Mark Lee
 * http://www.capricasoftware.co.uk 
 *
 * Copyright (c) 2011 Wesley Nolte
 * Dual licensed under the MIT and GPL licenses.
 *
 */
(function($) {

  $.fn.jOrgChart = function(options) {
    var opts = $.extend({}, $.fn.jOrgChart.defaults, options);
    var $appendTo = $(opts.chartElement);

    // build the tree
    $this = $(this);
    var $container = $("<div class='" + opts.chartClass + "'/>");
    if($this.is("ul")) {
      buildNode($this.find("li:first"), $container, 0, opts);
    }
    else if($this.is("li")) {
      buildNode($this, $container, 0, opts);
    }
    $appendTo.append($container);

    // add drag and drop if enabled
    if(opts.dragAndDrop){
        $('div.node').draggable({
            cursor      : 'move',
            distance    : 40,
            helper      : 'clone',
            opacity     : 0.8,
            revert      : 'invalid',
            revertDuration : 100,
            snap        : 'div.node.expanded',
            snapMode    : 'inner',
            stack       : 'div.node'
        });
        
        
        $('div.node').droppable({
            accept      : '.node',          
            activeClass : 'drag-active',
            hoverClass  : 'drop-hover'
        });
        
      // Drag start event handler for nodes
      $('div.node').bind("dragstart", function handleDragStart( event, ui ){
        var sourceNode = $(this);
        sourceNode.parentsUntil('.node-container')
                   .find('*')
                   .filter('.node');
                   //.droppable('disable');
      });

      // Drag stop event handler for nodes
      $('div.node').bind("dragstop", function handleDragStop( event, ui ){

        /* reload the plugin */
        $(opts.chartElement).children().remove();
        $this.jOrgChart(opts);      
      });
    
      // Drop event handler for nodes
      $('div.node').bind("drop", function handleDropEvent( event, ui ) {    
    
        var targetID = $(this).data("tree-node");
        var targetLi = $this.find("li").filter(function() { return $(this).data("tree-node") === targetID; } );
        var targetUl = targetLi.children('ul');
    
        var sourceID = ui.draggable.data("tree-node");    
        var sourceLi = $this.find("li").filter(function() { return $(this).data("tree-node") === sourceID; } );   
        var sourceUl = sourceLi.parent('ul');
		//console.log('targetID '+targetLi.attr('data-id')+ ' sourceID '+sourceLi.attr('data-id'));
		var tid = targetLi.attr('data-id');
		var sid = sourceLi.attr('data-id');
		$('.loading').show();
	  	jqXHR = $.ajax({
          url : endpointurl+"usertypehierarchy.php",
          headers : { Accept: 'application/json',platform: 1,responsetype:'HTML',userpagename:'usertypehierarchy',useraction:'viewright',masterlisting:false},
          data:{action:'settreedata',tid:tid,sid:sid},
          type : "POST",
          success : function(data) 
          {   
            gettreedata();
          },
      })
		
        if (targetUl.length > 0){
          targetUl.append(sourceLi);
        } else {
          targetLi.append("<ul></ul>");
          targetLi.children('ul').append(sourceLi);
        }
        
        //Removes any empty lists
        if (sourceUl.children().length === 0){
          sourceUl.remove();
        }
    
      }); // handleDropEvent
        
    } // Drag and drop
  };

  // Option defaults
  $.fn.jOrgChart.defaults = {
    chartElement : 'body',
    depth      : -1,
    chartClass : "jOrgChart",
    dragAndDrop: false
  };
  
  var nodeCount = 0;
  // Method that recursively builds the tree
  function buildNode($node, $appendTo, level, opts) {
    var $table = $("<table cellpadding='0' cellspacing='0' border='0'/>");
    var $tbody = $("<tbody/>");

    // Construct the node container(s)
    var $nodeRow = $("<tr/>").addClass("node-cells");
    var $nodeCell = $("<td/>").addClass("node-cell").attr("colspan", 2);
    var $childNodes = $node.children("ul:first").children("li");
    var $nodeDiv;
    
    if($childNodes.length > 1) {
      $nodeCell.attr("colspan", $childNodes.length * 2);
    }
    // Draw the node
    // Get the contents - any markup except li and ul allowed
    var $nodeContent = $node.clone()
                            .children("ul,li")
                            .remove()
                            .end()
                            .html();
  
      //Increaments the node count which is used to link the source list and the org chart
    nodeCount++;
    $node.data("tree-node", nodeCount);
    $nodeDiv = $("<div>").addClass("node")
                                     .data("tree-node", nodeCount)
                                     .append($nodeContent);

    // Expand and contract nodes
    if ($childNodes.length > 0) {
      $nodeDiv.click(function() {
          var $this = $(this);
          var $tr = $this.closest("tr");

          if($tr.hasClass('contracted')){
            $this.css('cursor','n-resize');
            $tr.removeClass('contracted').addClass('expanded');
            $tr.nextAll("tr").css('visibility', '');

            // Update the <li> appropriately so that if the tree redraws collapsed/non-collapsed nodes
            // maintain their appearance
            $node.removeClass('collapsed');
          }else{
            $this.css('cursor','s-resize');
            $tr.removeClass('expanded').addClass('contracted');
            $tr.nextAll("tr").css('visibility', 'hidden');

            $node.addClass('collapsed');
          }
        });
    }
    
    $nodeCell.append($nodeDiv);
    $nodeRow.append($nodeCell);
    $tbody.append($nodeRow);

    if($childNodes.length > 0) {
      // if it can be expanded then change the cursor
      $nodeDiv.css('cursor','n-resize');
    
      // recurse until leaves found (-1) or to the level specified
      if(opts.depth == -1 || (level+1 < opts.depth)) { 
        var $downLineRow = $("<tr/>");
        var $downLineCell = $("<td/>").attr("colspan", $childNodes.length*2);
        $downLineRow.append($downLineCell);
        
        // draw the connecting line from the parent node to the horizontal line 
        $downLine = $("<div></div>").addClass("line down");
        $downLineCell.append($downLine);
        $tbody.append($downLineRow);

        // Draw the horizontal lines
        var $linesRow = $("<tr/>");
        $childNodes.each(function() {
          var $left = $("<td>&nbsp;</td>").addClass("line left top");
          var $right = $("<td>&nbsp;</td>").addClass("line right top");
          $linesRow.append($left).append($right);
        });

        // horizontal line shouldn't extend beyond the first and last child branches
        $linesRow.find("td:first")
                    .removeClass("top")
                 .end()
                 .find("td:last")
                    .removeClass("top");

        $tbody.append($linesRow);
        var $childNodesRow = $("<tr/>");
        $childNodes.each(function() {
           var $td = $("<td class='node-container'/>");
           $td.attr("colspan", 2);
           // recurse through children lists and items
           buildNode($(this), $td, level+1, opts);
           $childNodesRow.append($td);
        });

      }
      $tbody.append($childNodesRow);
    }

    // any classes on the LI element get copied to the relevant node in the tree
    // apart from the special 'collapsed' class, which collapses the sub-tree at this point
    if ($node.attr('class') != undefined) {
        var classList = $node.attr('class').split(/\s+/);
        $.each(classList, function(index,item) {
            if (item == 'collapsed') {
                console.log($node);
                $nodeRow.nextAll('tr').css('visibility', 'hidden');
                    $nodeRow.removeClass('expanded');
                    $nodeRow.addClass('contracted');
                    $nodeDiv.css('cursor','s-resize');
            } else {
                $nodeDiv.addClass(item);
            }
        });
    }

    $table.append($tbody);
    $appendTo.append($table);
    
    /* Prevent trees collapsing if a link inside a node is clicked */
    $nodeDiv.children('a').click(function(e){
        console.log(e);
        e.stopPropagation();
    });
  };

})(jQuery);


function gettreedata()
{
    $(".orgdiv").html('');
    $(".orgChart").html('');
    $('.loading').show();
    jqXHR = $.ajax({
        url : endpointurl+"usertypehierarchy.php",
        headers : { Accept: 'application/json',platform: 1,responsetype:'HTML',userpagename:'usertypehierarchy',useraction:'viewright',masterlisting:false},
        data:{action:'gettreedata'},
        type : "POST",
        dataType: "json",
        success : function(data) 
        {   
            var JsonData = JSON.stringify(data);
            var resultdata = jQuery.parseJSON(JsonData);
            $('.loading').hide();
            if(resultdata.status == 0) 
            {
                
            }
            else
            {
                $(".orgdiv").html(buildList(resultdata.result,false));
                $("#org").jOrgChart({
                    chartElement : '#chart',
                    dragAndDrop  : true
                });
                $('.btnshow').hide();
            }
        },
    })
}

function buildList(data, isSub){
    var html = (isSub)?'<div>':''; // Wrap with div if true
    html += '<ul>';
    for(item in data)
    {
        var id="'"+data[item].id+"'";

        html += '<li data-id="'+data[item].id+'">';
        if(typeof(data[item].children) === 'object') // An array will return 'object'
        { 
            if(isSub)
            {
                html += '<span class="singlelinetext">'+data[item].utypename+'</span><br>';
                if(data[item].addright == 1)
                {
                  html += '<a onclick="addnode('+id+')"  name="btndelete" title="" class="btn btn-success btnshow" id="btndelete"  href="javascript:void(0)" ><i class="fas fa-plus gridbtn"></i></a>';
                }
                if(data[item].delright == 1)
                {
                  html += '<a onclick="deletenode('+id+')" name="btndelete" title="" class="btn btn-danger btnshow" id="btndelete" href="javascript:void(0)" ><i class="fas fa-times gridbtn"></i></a>';
                }
                html += '<a onclick="personnode('+id+')" name="btndelete" title="" class="btn btn-info btnshow" id="btndelete"  href="javascript:void(0)" style="margin-left: 6px;"><i class="fas fa-info gridbtn"></i></a>';
            } 
            else 
            {
              html += '<span class="singlelinetext">'+data[item].utypename+'</span><br>';
              if(data[item].addright == 1)
              {
                html +='<a onclick="addnode('+id+')" name="btndelete" title="" class="btn btn-success btnshow" id="btndelete"  href="javascript:void(0)" d><i class="fas fa-plus gridbtn"></i></a>';
              }
              if(data[item].delright == 1)
              {
                html +='<a onclick="deletenode('+id+')" name="btndelete" title="" class="btn btn-danger btnshow" id="btndelete" href="javascript:void(0)" ><i class="fas fa-times gridbtn"></i></a>';
              }
              html +='<a onclick="personnode('+id+')" name="btndelete" title="" class="btn btn-info btnshow" id="btndelete"  href="javascript:void(0)" style="margin-left: 6px;"><i class="fas fa-info gridbtn"></i></a>';
                
            }
            html += buildList(data[item].children, isSub); // Submenu found. Calling recursively same method (and wrapping it in a div)
        } 
        else 
        {
          html += '<span class="singlelinetext">'+data[item].utypename+'</span><br>';
          if(data[item].addright == 1)
          {
            html += ' <a onclick="addnode('+id+')" name="btndelete" title="" class="btn btn-success btnshow" id="btndelete"  href="javascript:void(0)" ><i class="fas fa-plus gridbtn"></i></a> ';
          }
          if(data[item].delright == 1)
          {
            html += ' <a onclick="deletenode('+id+')" name="btndelete" title="" class="btn btn-danger btnshow" id="btndelete" href="javascript:void(0)" ><i class="fas fa-times gridbtn"></i></a>';
          }
          html += ' <a onclick="personnode('+id+')" name="btndelete" title="" class="btn btn-info btnshow" id="btndelete"  href="javascript:void(0)" style="margin-left: 6px;"><i class="fas fa-info gridbtn"></i></a>';
        
        }
        html += '</li>';
    }
    html += '</ul>';
    html += (isSub)?'</div>':'';
    return html;
}


// function buildList(data, isSub){
//     var html = (isSub)?'<div>':''; // Wrap with div if true
//     html += '<ul>';
//     for(item in data){
//         html += '<li data-id="'+data[item].id+'">';
//         if(typeof(data[item].children) === 'object'){ // An array will return 'object'
//             if(isSub){
//                 html += '<span class="singlelinetext">'+data[item].utypename+'</span><br>';
//                 html += '<a onclick="addnode('+data[item].id+')"  name="btndelete" title="" class="btn btn-success btnshow" id="btndelete"  href="javascript:void(0)" ><i class="fas fa-plus gridbtn"></i></a>';
//                 if(data[item].restrictdelete==0)
//                 {
//                 	html += '<a onclick="deletenode('+data[item].id+')" name="btndelete" title="" class="btn btn-danger btnshow" id="btndelete" href="javascript:void(0)" ><i class="fas fa-times gridbtn"></i></a>';
//             	}
//             } 
//             else 
//             {
//                 html += '<span class="singlelinetext">'+data[item].utypename+'</span><br>';
//                 html += '<a onclick="addnode('+data[item].id+')" name="btndelete" title="" class="btn btn-success btnshow" id="btndelete" href="javascript:void(0)" d><i class="fas fa-plus gridbtn"></i></a>'; 
//                 if(data[item].restrictdelete==0)
//                 {
//            	 		html += '<a onclick="deletenode('+data[item].id+')" name="btndelete" title="" class="btn btn-danger btnshow" id="btndelete" href="javascript:void(0)" ><i class="fas fa-times gridbtn"></i></a>';
//            	 	}
//             }
//             html += buildList(data[item].children, isSub); // Submenu found. Calling recursively same method (and wrapping it in a div)
//         } 
//         else 
//         {
//             html += '<span class="singlelinetext">'+data[item].utypename+'</span><br>';
//             html += '<a onclick="addnode('+data[item].id+')" name="btndelete" title="" class="btn btn-success btnshow" id="btndelete"  href="javascript:void(0)" ><i class="fas fa-plus gridbtn"></i></a>';
//            	if(data[item].restrictdelete==0)
//             {
//             	html += '<a onclick="deletenode('+data[item].id+')" name="btndelete" title="" class="btn btn-danger btnshow" id="btndelete" href="javascript:void(0)" ><i class="fas fa-times gridbtn"></i></a>'; // No submenu
//             }
//         }
//         html += '</li>';
//     }
//     html += '</ul>';
//     html += (isSub)?'</div>':'';
//     return html;
// }