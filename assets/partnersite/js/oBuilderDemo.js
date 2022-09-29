function calibrateProgress(){

	var progressPoints = 100;
	var progressPointsDone = 0;

	$('#editor').find('.project-child').each(function(){

		var completedPoints = 0;
		var taskCount = $(this).children().length;
		var parentPoints = $(this).parent().attr('data-progress-points');
		var progressPointsTask = parentPoints / taskCount;

		$(this).children().each(function(){

			var childStatus = $(this).attr('data-status');
			if(childStatus == 1){
				completedPoints += progressPointsTask;
			}

			$(this).attr('data-progress-points',progressPointsTask);

		});

		
		$(this).parent().attr('data-completed-points',completedPoints);
		progressPoints = progressPointsTask;

	});


	$('#editor').find('.o-project').not('.parent').each(function(){

		var thisPoints = ($(this).attr('data-progress-points')*10000);
		var thisStatus = $(this).attr('data-status');

		if(thisStatus == 1){
			progressPointsDone += parseInt(thisPoints);
		}

	});

	progressPointsDone = progressPointsDone/10000;

	precision = Math.pow(10, 1);
	progressPointsDone = Math.ceil(progressPointsDone * precision) / precision;

	$('#progressbar .progress').css('width',progressPointsDone+'%');
	$('#progressbar .progress-marker').html(progressPointsDone+'%');


}

function checkProjectIdExist(projectId){

	var check = $('#editor').find('#project-'+projectId).attr('id');

	if(check){
		return false;
	}else{
		return projectId;
	}

}

function toggleTask(projectId){

	var toggle = $('#'+projectId).attr('data-toggle');

	console.log(toggle);
	console.log(projectId);

	if(toggle == 'close'){
		$('#'+projectId).removeClass('close');
		$('#'+projectId).attr('data-toggle','');
	}else{
		$('#'+projectId).addClass('close');
		$('#'+projectId).attr('data-toggle','close');
	}


}

function markCompleted(projectId){


	var projectIdReduced = projectId.substring(8);
	var currentStatus = $('#'+projectId).attr('data-status');
	var currentDephtLevel = $('#'+projectId).attr('data-level');
	var parentId = $('#'+projectId).parents().eq(1).attr('id');
	var parentIdReduced = parentId.substring(8);
	var newStatus = 1;

	if(currentStatus == 0){

		newStatus = 1;
		$('#status-'+projectIdReduced).html('Done');

	}else if(currentStatus == 1){
		newStatus = 0;
		$('#status-'+projectIdReduced).html('Pending');
		$('#status-'+parentIdReduced).html('Pending');
		$('#'+projectId).parents().eq(1).attr('data-status',newStatus);
	}

	$('#'+projectId).attr('data-status',newStatus);
	

    // Check childrens
    var children = $('#'+projectId).find('.o-project').each(function(){

    	$(this).attr('data-status',newStatus);

    	var childId = $(this).attr('id');
    	var childIdReduced = childId.substring(8);

    	if(newStatus == 1){
    		$('#status-'+childIdReduced).html('Done');
    	}else if(newStatus == 0){
    		$('#status-'+childIdReduced).html('Pending');
    	}


    });


    // Check siblings
    var siblingsConsensus = 0;
    var siblingsStatusArray = [''+newStatus+''];

    $('#'+projectId).siblings().each(function(){

    	var thisSiblingStatus = $(this).attr('data-status');
    	siblingsConsensus = thisSiblingStatus;

    	siblingsStatusArray.push(thisSiblingStatus);

    });

    var checkAllEqual = siblingsStatusArray.every((val, i, arr) => val === arr[0]);

    if(checkAllEqual == true){
    	$('#'+parentId).attr('data-status',newStatus);
		$('#status-'+parentIdReduced).html('Done');
    }

	if(checkAllEqual == true && newStatus == 1 || newStatus == 0){
	    // Backtrack parents
		var i;
		for(i=0;i<currentDephtLevel;i++){
			//console.log(i);

			var level = i;
			var thisParentId = $('#'+projectId).parents('.o-project').eq(level).attr('id');
			var thisParentIdReduced = thisParentId.substring(8);

			console.log(level);
			console.log(thisParentId);

		    var siblingsConsensus = 0;
		    var siblingsStatusArray = [''+newStatus+''];

		    $('#'+thisParentId+' .project-child').children().each(function(){

		    	var thisSiblingStatus = $(this).attr('data-status');
		    	siblingsConsensus = thisSiblingStatus;

		    	siblingsStatusArray.push(thisSiblingStatus);

		    });

		    var checkAllEqual = siblingsStatusArray.every((val, i, arr) => val === arr[0]);

		    console.log('All equal?'+checkAllEqual);

		    if(checkAllEqual == true || checkAllEqual == false && newStatus == 0){
		    	$('#'+thisParentId).attr('data-status',newStatus);
				$('#status-'+thisParentIdReduced).html('Done');
		    }

		}

	}

    calibrateProgress();


}


$('#editor').on('keypress','.o-project',function(e){

	//console.log('Keypress');

	var key = e.which;
	var depthCurrent = $(this).attr('data-level');

	var projectId = $(this).attr('id');
	var projectIdReduced = projectId.substring(8);
	var parentId = $('#'+projectId).parents().eq(1).attr('id');
	var parentIdReduced = parentId.substring(8);

	if(depthCurrent == ''){
		depthCurrent = 0;
	}


	// Enter Key
	if(key == 13){

		e.preventDefault();

        var childId = $(this).parent().attr('data-child');
        var childDepth = parseInt($(this).parent().attr('data-depth-level'));


		var i;
		for (i = 0; i < 100; i++) {

			var newProjectId = Math.floor((Math.random() * 10000) + 1) + '-' + Math.floor((Math.random() * 10000) + 1) + '-' + Math.floor((Math.random() * 10000) + 1);
			var checkExist = checkProjectIdExist(newProjectId);

			if(checkExist != false){
				i = 100;
			}else{
				console.log('COPY' + newProjectId);
			}

		}

		var newProject = '<div id="project-'+newProjectId+'" class="o-project row" data-level="'+depthCurrent+'" data-toggle="" data-progress-points="0" data-completed-points="0" data-status="0" data-est-time="0"><div id="container-'+newProjectId+'" class="o-container" contenteditable="true" data-project-id="'+newProjectId+'"></div><div class="o-action" data-project-id-action="'+newProjectId+'"><span class="nav"></span><span class="mark" onclick="markCompleted(\'project-'+newProjectId+'\')"></span></div><div id="status-'+newProjectId+'" class="o-status">Pending</div><div data-project-id="'+newProjectId+'" class="o-deadline"><em>Deadline</em><div class="o-date"><input type="text" id="datepicker" placeholder="Choose date"></div><div class="o-time"><select><option>12:00</option><option>13:00</option></select></div></div></div>';

		//$('#editor .project-child[data-child='+childId+']').append(newProject);

		$('#'+projectId).after(newProject);

		// Change parent status
		$('#status-'+projectIdReduced).html('Pending');
		$('#status-'+parentIdReduced).html('Pending');
		$('#'+projectId).parents().eq(1).attr('data-status',0);

		setCaret('project-'+newProjectId);
		calibrateProgress();

		return false;

	}

});



$('#editor').on('keydown','.o-project',function(e){

	//console.log('Keydown');
	
	var key = e.which;
	var depthCurrent = $(this).attr('data-level');

	if(depthCurrent == ''){
		depthCurrent = 0;
	}

	var projectId = $(this).attr('id');
	var projectIdReduced = projectId.substring(8);

    var ancestorChildId = $(this).parents().eq(2).attr('data-child');
    var currentChildId = $(this).parent().attr('data-child');

	var parentId = $(this).parents().eq(1).attr('id');
	var parentIdReduced = parentId.substring(8);


	var siblingIdPrev = $(this).prev().attr('id');
	if(siblingIdPrev){
		var siblingIdPrevReduced = siblingIdPrev.substring(8);
	}


	var siblingIdNext = $(this).next().attr('id');
	if(siblingIdNext){
		var siblingIdNextReduced = siblingIdNext.substring(8);
	}


	if(key === 38){

		var prevElement = siblingIdPrev;
		if(siblingIdPrev == undefined){
			prevElement = parentId;
		}

		setCaret(prevElement);
		return false;
	}

	if(key === 40){

		var nextElement = $(this).find('.o-project').attr('id');

		if(nextElement == undefined){
			nextElement = siblingIdNext;
		}

		if(nextElement == undefined){
			nextElement = siblingIdNext;
		}

		setCaret(nextElement);
		return false;
	}

	// Shift + Tab Key
	if(e.shiftKey){

		if(key === 9){

			e.preventDefault();
	        var parentId = $(this).parent().attr('id');
	        var parentDepth = parseInt($(this).parent().attr('data-depth-level'));

	        var depthLevelNext = 0;
	        var depthLevelPrev = 0;
	        var depthLevel = 0;

	        var depthLevel = parseInt($(this).attr('data-level'));

	        depthLevelNext = parentDepth + 1;
	        depthLevelPrev = parentDepth - 1;
	        depthLevelThreshold = parentDepth + 1;


	        var parentIdOld = $(this).parents().eq(1).attr('id');
	        var parentIdOldReduced = parentIdOld.substring(8);

	        $('#'+projectId).appendTo('div[data-child='+ancestorChildId+']');
	        $('#'+projectId).attr('data-level',depthLevelPrev);

			$('#'+projectId).find('.project-child').each(function(){

				var currentChildLevel = $(this).attr('data-depth-level');
				var newChildLevel = currentChildLevel - 1;
				$(this).attr('data-depth-level',newChildLevel);

				$(this).find('.o-project').each(function(){
					$(this).attr('data-level',newChildLevel);
				});

			});	        

	        setCaret(projectId);
	        calibrateProgress();

	        // Check if child is empty
	        var currentChildCheck = $('.project-child[data-child='+currentChildId+'] div.o-project').attr('id');

	        if(currentChildCheck == undefined){
	        	$('.project-child[data-child='+currentChildId+']').remove();
	        	$('#'+parentIdOld).removeClass('parent');
				$('#'+parentIdOld+' .o-action[data-project-id-action='+parentIdOldReduced+'] .arrow').remove();
	        }

	        //console.log(currentChildCheck);

	        return false;

		}

	}

	// Tab Key
	if(key == 9){

		e.preventDefault();

        var parentId = $(this).parent().attr('id');
        var parentDepth = parseInt($(this).parent().attr('data-depth-level'));

        var depthLevelNext = 0;
        var depthLevelPrev = 0;
        var depthLevel = 0;

        var depthLevel = parseInt($(this).attr('data-level'));

        depthLevelNext = parentDepth + 1;
        depthLevelPrev = parentDepth - 1;
        depthLevelThreshold = parentDepth + 1;

        // Check siblings

        var siblingChildIdPrev = $('#'+siblingIdPrev+' div[data-depth-level='+depthLevelNext+']').attr('data-child');

        if(siblingIdPrev != undefined){

		    $(this).attr('data-level',depthLevelNext);

        	if(siblingChildIdPrev != undefined){

        		$('#'+projectId).appendTo('#'+siblingIdPrev+' div[data-child='+siblingChildIdPrev+']');
				$('#'+siblingIdPrev+' .o-action[data-project-id-action='+siblingIdPrevReduced+'] .mark').after('<span class="arrow" onclick="toggleTask(\'project-'+siblingIdPrevReduced+'\')"></span>');

        	}else{

		        var childId = Math.floor((Math.random() * 1000) + 1);

		        $('#editor #'+siblingIdPrev).append('<div class="project-child lvl-'+ depthLevelNext +'" data-child="'+childId+'" data-depth-level="'+depthLevelNext+'"></div>');


				$('#'+projectId).appendTo('#'+siblingIdPrev+' div[data-depth-level='+depthLevelNext+']');
				$('#'+siblingIdPrev).addClass('parent');
				$('#'+siblingIdPrev+' .o-action[data-project-id-action='+siblingIdPrevReduced+'] .mark').after('<span class="arrow" onclick="toggleTask(\'project-'+siblingIdPrevReduced+'\')"></span>');


        	}
	
			setCaret(projectId);
			calibrateProgress();

	        // Check if child is empty
	        var currentChildCheck = $('.project-child[data-child='+currentChildId+'] div.o-project').attr('id');

	        if(currentChildCheck == undefined){
	        	$('.project-child[data-child='+currentChildId+']').remove();
	        }

        }else{

        	// Do nothing

        }

        // Check childrens
        var children = $(this).find('.project-child').each(function(){

        	var childrenDepthLevel = $(this).attr('data-depth-level');

        	childrenDepthLevel++;

        	$(this).attr('data-depth-level',childrenDepthLevel);

        	// Children tasks
        	var childrenTasks = $(this).find('.o-project').each(function(){

        		$(this).attr('data-level',childrenDepthLevel);

        	});

        });

        return false;

	}


	// Backspace Key
	if(key === 8){

		var trigger = false;

		var projectContainer = $('.o-container[data-project-id='+projectIdReduced+']');
		var projectContainerHtml = $('.o-container[data-project-id='+projectIdReduced+']').html();

        // Check childrens
        var childrenId = $(this).find('.project-child').attr('data-child');

		if(projectContainerHtml == ''){

			if(projectId != 'project-1111'){

				e.preventDefault();

				$('#project-'+projectIdReduced).remove();

				if(siblingIdPrev != undefined){
					setCaret(siblingIdPrev);
					calibrateProgress();
				}else{
					setCaret(parentId);
					calibrateProgress();
				}

		        // Check if child is empty
		        var currentChildCheck = $('.project-child[data-child='+currentChildId+'] div.o-project').attr('id');

		        if(currentChildCheck == undefined){
					$('#'+parentId).removeClass('parent');
					$('#'+parentId+' .o-action[data-project-id-action='+parentIdReduced+'] .arrow').remove();
		        	$('.project-child[data-child='+currentChildId+']').remove();
		        }

		    }

		}else{

			trigger = true;

		}


		return trigger;

	}

});



function tabindex(){

	var i = 0;
	$('#editor').find('.o-project').each(function(){

		$(this).attr('tabindex',i);
		i++;

	});

}


function calibrateTasks(projectId){

	//var DepthLevel = 0;

	$('#'+projectId).find('.project-child').each(function(){

		var currentDephtLevel = $(this).attr('data-depth-level');

		$(this).children().each(function(){

			$(this).attr('data-level',currentDephtLevel);

		});

	});

}

function setCaret(contenteditableId){

    var contenteditableIdReduced = contenteditableId.substring(8);

    var el = document.getElementById("container-"+contenteditableIdReduced);

    el.focus();
    if (typeof window.getSelection != "undefined"
            && typeof document.createRange != "undefined") {
        var range = document.createRange();
        range.selectNodeContents(el);
        range.collapse(false);
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    } else if (typeof document.body.createTextRange != "undefined") {
        var textRange = document.body.createTextRange();
        textRange.moveToElementText(el);
        textRange.collapse(false);
        textRange.select();
    }


}