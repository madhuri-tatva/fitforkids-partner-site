function logAction(projectUniqueId,projectKey,action,additional){

    $.ajax({
        url: "/actions/ajax/logAction.php",
        method: "POST",        
        data: {
        	projectUniqueId: projectUniqueId,
        	projectKey: projectKey,
        	action: action,
        	additional: additional
        },
        success: function(data){
        	console.log(data);
        },
        error: function(error) {
            console.log('error');
        }
    });

}


function projectCache(projectId){

	/*var currentProjectVersion = compress('o-'+projectId);

	var cachedProjects = {};
	var lastCachedProject = localStorage.getItem('Last Cached');

	var currentTime = new Date($.now());

	if(currentProjectVersion != lastCachedProject){
		// Save
		console.log('CURRENT PROJECT' + currentProjectVersion);
		console.log('LAST PROJECT' + lastCachedProject);
		console.log('Cool, just saved '+currentProjectVersion+' at '+currentTime);
		localStorage.setItem(currentTime, currentProjectVersion);
		localStorage.setItem('Last Cached', currentProjectVersion);

	}else{
		console.log('Nothing to save');
	}*/

	// Check if any changes

	// Check if user is in team

	// Check for <script> injections

	// Save ajax

	// Unset cache

}

function projectSave(projectId){

	var currentProjectVersion = compress('o-'+projectId);

	var cachedProjects = {};
	var lastCachedProject = localStorage.getItem('Last Cached');

	var projectName = $('#o-'+projectId+' .title input.project-title').val();

	console.log('ProjectNAME: '  + projectName);
	console.log('ProjectCACHE: '  + lastCachedProject);

	var currentTime = new Date($.now());

	if(currentProjectVersion != lastCachedProject){

		$('#o-'+projectId+' .saving .text').html('Saving...');
		$('#o-'+projectId+' .saving').not('.show').toggleClass('show');
		// Save
		/*console.log('CURRENT PROJECT' + currentProjectVersion);
		console.log('LAST PROJECT' + lastCachedProject);
		console.log('Cool, just saved '+currentProjectVersion+' at '+currentTime);*/
		localStorage.setItem(currentTime, currentProjectVersion);
		localStorage.setItem('Last Cached', currentProjectVersion);

		// Save Ajax

	    $.ajax({
	        url: "/actions/ajax/projectSave.php",
	        method: "POST",        
	        data: {
	        	projectId: projectId,
	        	projectName: projectName,
	        	version: currentProjectVersion
	        },
	        success: function(data){
	        	console.log(data);

	        	setTimeout(function(){
	        		$('#o-'+projectId+' .saving.show').toggleClass('show');
	        		$('#o-'+projectId+' .saving .text').html('Everything is saved');
	        	}, 1500);
	        	
	        },
	        error: function(error) {
	            console.log('error');
	        }
	    });


	}else{
		console.log('Nothing to save');
	}

}

function projectTemplate(array,permission,view){

	//console.log(array);

	if(permission <= 2){
		var inputDisabled = '';
		var markDisabled = '';
		var contenteditable = 'true';
	}else if(permission >= 4){
		var inputDisabled = 'disabled';
		var markDisabled = 'disabled';
		var contenteditable = 'false';
	}else{
		var inputDisabled = '';
		var markDisabled = '';
		var contenteditable = 'false';
	}

	var project = '';
	// Project start
	project += '<div id="o-'+array['ProjectId']+'" class="o-builder o-project focused closed" data-project-id="'+array['ProjectId']+'" data-unique-id="'+array['Id']+'">';

		// Toolbar start
		project += '<div class="toolbar">';
		    project += '<div class="title"><input type="text" class="project-title" placeholder="Project name" value="'+array['Name']+'" '+inputDisabled+' /></div>';
		    project += '<ul class="tools">';
			    project += '<li class="fullbar">';
			    project += '<span class="saving icon icon-saving">';
			    project += '<i class="i1"></i>';
			    project += '<span class="text"></span></span>';
			    project += '</li>';
			    project += '<li class="fullbar">';
			    project += '<span class="icon icon-gantt btn-gantt">';
			    project += '<i class="i1"></i><i class="i2"></i><i class="i3"></i><i class="i4"></i>';
			    project += '</span>';
			    project += '</li>';

			    if(view == 1){

			    project += '<li class="fullbar">';
			    project += '<span class="icon icon-bullets btn-deadline">';
			    project += '<i class="i1"></i><i class="i2"></i><i class="i3"></i>';
			    project += '</span>';
			    project += '</li>';
			    project += '<li>';
			    project += '<span class="icon icon-minimize btn-minimize">';
			    project += '<i class="i1"></i><i class="i2"></i><i class="i3"></i><i class="i4"></i>';
			    project += '</span>';
			    project += '</li>';
			    project += '<li class="last">';
			    project += '<span class="icon icon-settings btn-settings" data-project="'+array['ProjectId']+'">';
			    project += '<i class="i1"></i><i class="i2"></i><i class="i3"></i>';
			    project += '<ul class="sub-navigation">';
			    project += '<li><a class="btn-advanced">Advanced</a></li>';
			    project += '<li><a class="btn-share md-trigger" data-modal="modal-share" onclick="shareProject('+array['Id']+')">Share</a></li>';

				}

			    project += '</ul>';
			    project += '</span>';
			    project += '</li>';
		    project += '</ul>';
	    project += '</div>';
		// Toolbar end

	    // Meta start
      	project += '<div class="meta">';
        project += '<div class="progressbar"><div class="progress"><span class="progress-marker">0%</span></div></div>';
		project += '<div class="weeknumbers"></div>';
      	project += '</div>';
      	// Meta end

      	// Editor start
      	project += '<div id="parent-'+array['ProjectId']+'" class="editor" data-progress-points="100" data-completed-points="0" data-first-date-u="" data-last-date-u="">';
 
      	// If empty content
      	if(array['Content'] == ''){

      		project += '<div class="task-child lvl-0" data-child="1001" data-parent="anchor" data-depth-level="0">';

	      		project += '<div data-task-id="1111" class="o-task row" data-level="0" data-child-id="1001" data-project-id="'+array['ProjectId']+'" data-toggle="" data-progress-points="0" data-completed-points="0" data-status="0" data-est-time="0" data-date-start="" data-date-end="" data-position="0">';

	      			project += '<div data-container-id="1111" class="o-container" contenteditable="'+contenteditable+'" data-task-id="1111"></div>';

	      			project += '<div data-task-id="1111" class="o-deadline">';
	      			project += '<div class="o-est-time" data-est-time=""><em>Est. min.</em><input type="text" class="input" placeholder="10"></div>';
	      			project += '<div class="o-date-start" data-date-start=""><em>Start date</em><input type="text" class="datepicker" placeholder="Choose date"></div>';
	      			project += '<div class="o-date-end" data-date-end=""><em>Deadline</em><input type="text" class="datepicker" placeholder="Choose date"></div>';
	      			project += '</div>';

	      			project += '<div class="o-action" data-task-id-action="1111"><span class="nav"></span><span class="mark '+markDisabled+'" onclick="markCompleted(\'task-1111\',\''+array['ProjectId']+'\')"></span></div>';
	      			project += '<div data-status="1111" class="o-status">Pending</div>';

	      			project += '<div data-meta-id="1111" class="o-meta">';
	              	project += '<div class="date-end"></div>';
	              	project += '<div class="timeline"><div class="fill"></div></div>';
	            	project += '</div>';

	      		project += '</div>';

      		project += '</div>';

      	}else{

      		//console.log(array['Content']);
      		var content = JSON.parse(array['Content']);
      		//console.log(content);

			$.each(content, function(key, value) {
			  	//alert( key + ": " + value );
			  	//console.log(key + ": " + value);

			  	var section = '';

			  	// Identify key
			  	var keySplit = key.split("-")
			  	//console.log(keySplit[0]);
			  	//console.log(keySplit[1]);

			  	if(keySplit[0] == 'content'){

			  		var sectionLevel = 0;
			  		var sectionParent = '';
			  		var tasks = '';

			  		//tasks += '<div class="task-child lvl-0" data-child="'+keySplit[1]+'" data-parent="anchor" data-depth-level="0">';

			  		$.each(value, function(key, value) {

			  			var task = '';

			  			sectionLevel = value[4].split("::|").pop();
			  			sectionParent = value[0].split("::|").pop();

			  			taskLevel = value[4].split("::|").pop();
			  			taskChild = value[2].split("::|").pop();
			  			taskId = value[3].split("::|").pop();
			  			taskToggle = value[5].split("::|").pop();
			  			taskProgressPoints = value[6].split("::|").pop();
			  			taskCompletedPoints = value[7].split("::|").pop();
			  			taskStatus = value[8].split("::|").pop();
			  			taskEstTime = value[9].split("::|").pop();
			  			taskStart = value[10].split("::|").pop();
			  			taskEnd = value[11].split("::|").pop();
			  			taskPosition = value[12].split("::|").pop();
			  			taskContent = value[13].split("::|").pop();

			  			taskIsParent = value[1].split("::|").pop();

			  			//console.log(taskIsParent + 'VALUE 2');

			  			if(taskIsParent == 'true'){
			  				var isParent = 'parent';
			  			}else{
			  				var isParent = '';
			  			}

			  			//console.log(value);
			  			//console.log("TEST: " + value);
			      		task += '<div data-task-id="'+taskId+'" class="o-task row '+isParent+'" data-level="'+taskLevel+'" data-child-id="'+taskChild+'" data-project-id="'+array['ProjectId']+'" data-toggle="'+taskToggle+'" data-progress-points="'+taskProgressPoints+'" data-completed-points="'+taskCompletedPoints+'" data-status="'+taskStatus+'" data-est-time="'+taskEstTime+'" data-date-start="'+taskStart+'" data-date-end="'+taskEnd+'" data-position="'+taskPosition+'">';

			      			task += '<div data-container-id="'+taskId+'" class="o-container" contenteditable="'+contenteditable+'" data-task-id="'+taskId+'">'+taskContent+'</div>';

			      			task += '<div data-task-id="'+taskId+'" class="o-deadline">';
			      			task += '<div class="o-est-time" data-est-time="'+taskEstTime+'"><em>Est. min.</em><input type="text" class="input" placeholder="10"></div>';
			      			task += '<div class="o-date-start" data-date-start="'+taskStart+'"><em>Start date</em><input type="text" class="datepicker" placeholder="Choose date" value="'+taskStart+'"></div>';
			      			task += '<div class="o-date-end" data-date-end="'+taskEnd+'"><em>Deadline</em><input type="text" class="datepicker datepicker-deadline" placeholder="Choose date" value="'+taskEnd+'"></div>';
			      			task += '</div>';

			      			task += '<div class="o-action" data-task-id-action="'+taskId+'">';
			      			task += '<span class="nav"></span>';
			      			task += '<span class="mark '+markDisabled+'" onclick="markCompleted(\'task-'+taskId+'\',\''+array['ProjectId']+'\')"></span>';

			      			if(taskIsParent == 'true'){
			      				task += '<span class="arrow" onclick="toggleTask(\'task-'+taskId+'\',\''+array['ProjectId']+'\')"></span>';
			      			}

			      			task += '</div>';
			      			task += '<div data-status="'+taskId+'" class="o-status">Pending</div>';

			      			task += '<div data-meta-id="'+taskId+'" class="o-meta">';
			              	task += '<div class="date-end"></div>';
			              	task += '<div class="timeline"><div class="fill"></div></div>';
			            	task += '</div>';

			      		task += '</div>';

			      		tasks += task;

			  		});

			  		section += '<div class="task-child lvl-'+sectionLevel+'" data-child="'+keySplit[1]+'" data-parent="'+sectionParent+'" data-depth-level="'+sectionLevel+'">';
			  		section += tasks;
			  		section += '</div>';
			  		//project += '</div>';

			  	}

			  	project += section;


			});

      	}

      	project += '</div>';
      	// Editor end

	project += '</div>';
	// Project end

	

	$('#projects').append(project);
	$('.datepicker').datepicker({ 
		firstDay: 1
        /*beforeShowDay: function(date){

        	var holidays= ["2019/07/18", "2019/07/19"];

        	$('#ui-datepicker-div').on('hover','tr td', function(){
        		console.log('test');
        	});


		    for (var i = 0; i < holidays.length; i++) {
		        if (new Date(holidays[i]).toString() == date.toString()) {
		            return [true, 'ui-state-holiday'];
		        }
		    }
		    return [true];
        }*/
	});



	$('#o-'+array['ProjectId']).find('.task-child[data-depth-level != 0]').each(function(){
		var thisParent = $(this).attr('data-parent');
		$(this).appendTo('#o-'+array['ProjectId']+' div.o-task[data-task-id='+thisParent+']');
	});


	calibrateGantt(array['ProjectId']);
	calibrateProgress(array['ProjectId']);

	$('select').niceSelect();

	$.loadScript('/assets/js/modalEffects.js', function(){

	});

	return;

}

function taskTemplate(){

	var newTask = '<div id="task-'+newTaskId+'" class="o-task row" data-level="'+depthCurrent+'" data-toggle="" data-progress-points="0" data-completed-points="0" data-status="0" data-est-time="0" data-deadline="" data-position="0">';
	newTask += '<div id="container-'+newTaskId+'" class="o-container" contenteditable="true" data-task-id="'+newTaskId+'"></div>';
	newTask += '<div data-task-id="'+newTaskId+'" class="o-deadline">';
	newTask += '<div class="o-est-time" data-est-time=""><em>Est. min.</em><input type="text" class="input" placeholder="10"></div>';
	newTask += '<div class="o-date-start" data-date-start=""><em>Start date</em><input type="text" class="datepicker" placeholder="Choose date"></div>';
	newTask += '<div class="o-date-end" data-date-end=""><em>Deadline</em><input type="text" class="datepicker" placeholder="Choose date"></div>';
	newTask += '</div>';
	newTask += '<div class="o-action" data-task-id-action="'+newTaskId+'"><span class="nav"></span><span class="mark" onclick="markCompleted(\'task-'+newTaskId+'\')"></span></div>';
	newTask += '<div id="status-'+newTaskId+'" class="o-status">Pending</div>';
	newTask += '<div id="meta-'+newTaskId+'" class="o-meta"><div class="date-end"></div><div class="timeline"><div class="fill"></div></div></div>';
	newTask += '</div>';
	
}


function newProject(){

    $.ajax({
        url: "/actions/ajax/projectCreate.php",
        method: "POST",        
        data: {
        	json: 'json'
        },
        success: function(data){
        	console.log(data);
        	getProjects();
        },
        error: function(error) {
            console.log('error');
        }
    });

}

function getProjects(){
	$('#projects').load('/actions/getProjects.php');
}

function compress(projectId){

  var time = $.now();
  var content = {};

  var projectName = $('#'+projectId+' input.project-title').val();

  content['projectId'] = projectId;
  content['projectName'] = projectName;

  //console.log(time);
  localStorage = '';

  var thisVersion = $('#'+projectId+' .editor').html();


  $('#'+projectId).find('.task-child').each(function(){


      var thisParentId = $(this).attr('data-parent');
      var thisChildId = $(this).attr('data-child');
      var thisChildDepthLevel = $(this).attr('data-depth-level');

      content['child-'+thisChildId] = thisChildId;

      var tasks  = {};
      $(this).children('.o-task').each(function(){

          var task = [];

          var thisTaskId = $(this).attr('data-task-id');
          var thisIsParent =$(this).hasClass("parent");


          task.push('parent::|' + thisParentId);
          task.push('isParent::|' + thisIsParent);
          task.push('child::|' + $(this).attr('data-child-id'));
          task.push('id::|' + thisTaskId);
          task.push('dataLevel::|' + $(this).attr('data-level'));
          task.push('dataToggle::|' + $(this).attr('data-toggle'));
          task.push('dataProgressPoints::|' + $(this).attr('data-progress-points'));
          task.push('dataCompletedPoints::|' + $(this).attr('data-completed-points'));
          task.push('dataStatus::|' + $(this).attr('data-status'));
          task.push('dataEstTime::|' + $(this).attr('data-est-time'));
          task.push('dataDateStart::|' + $(this).attr('data-date-start'));
          task.push('dataDateEnd::|' + $(this).attr('data-date-end'));
          task.push('dataPosition::|' + $(this).attr('data-position'));
          task.push('dataContent::|' + $('#'+projectId+' div.o-container[data-container-id='+thisTaskId+']').html());

          tasks[thisTaskId] = task;

      });

      //console.log(tasks);
      content['content-'+thisChildId] = tasks;

  });

  var contentStringify = JSON.stringify(content);

  //console.log(content);
  //console.log(contentStringify);

  return contentStringify;

}

function calibrateProgress(projectId){

	var progressPoints = 100;
	var progressPointsDone = 0;

	$('#o-'+projectId).find('.task-child').each(function(){

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


	$('#o-'+projectId).find('.o-task').not('.parent').each(function(){

		var thisPoints = ($(this).attr('data-progress-points')*10000);
		var thisStatus = $(this).attr('data-status');

		if(thisStatus == 1){
			progressPointsDone += parseInt(thisPoints);
		}

	});

	progressPointsDone = progressPointsDone/10000;

	precision = Math.pow(10, 1);
	progressPointsDone = Math.ceil(progressPointsDone * precision) / precision;

	$('#o-'+projectId+' .progressbar .progress').css('width',progressPointsDone+'%');
	$('#o-'+projectId+' .progressbar .progress-marker').html(progressPointsDone+'%');


}

function checkTaskIdExist(taskId){

	var check = $('#editor').find('#task-'+taskId).attr('id');

	if(check){
		return false;
	}else{
		return taskId;
	}

}

function shareProject(projectId){

  $('#modal-share-content').load('/modules/modal/shareProject.php?id='+projectId);

}

function toggleTask(taskId,projectId){

	var taskIdReduced = taskId.substring(5);
	var toggle = $('#o-'+projectId+' div.o-task[data-task-id='+taskIdReduced+']').attr('data-toggle');

	if(toggle == 'close'){
		$('#o-'+projectId+' div.o-task[data-task-id='+taskIdReduced+']').removeClass('close');
		$('#o-'+projectId+' div.o-task[data-task-id='+taskIdReduced+']').attr('data-toggle','');
	}else{
		$('#o-'+projectId+' div.o-task[data-task-id='+taskIdReduced+']').addClass('close');
		$('#o-'+projectId+' div.o-task[data-task-id='+taskIdReduced+']').attr('data-toggle','close');
	}


}

function markCompleted(taskId,projectId){


	var projectUniqueId = $('#o-'+projectId).attr('data-unique-id');

	var taskIdReduced = taskId.substring(5);
	var currentStatus = $('#o-'+projectId+' div.o-task[data-task-id = '+taskIdReduced+']').attr('data-status');
	var currentDephtLevel = $('#o-'+projectId+' div.o-task[data-task-id = '+taskIdReduced+']').attr('data-level');
	var parentId = $('#o-'+projectId+' div.o-task[data-task-id = '+taskIdReduced+']').parents().eq(1).attr('data-task-id');
	var parentIdReduced = parentId;
	var newStatus = 1;

	var taskContent = $('#o-'+projectId+' div.o-container[data-container-id='+taskIdReduced+']').html();
	var taskStartDate = $('#o-'+projectId+' div.o-task[data-task-id='+taskIdReduced+']').attr('data-date-start');
	var taskEndDate= $('#o-'+projectId+' div.o-task[data-task-id='+taskIdReduced+']').attr('data-date-end');

	var loggingAction = '';
	if(currentStatus == 0){

		newStatus = 1;
		loggingAction = 'Task completed';

	}else if(currentStatus == 1){
		newStatus = 0;
		loggingAction = 'Task not completed';
		$('#o-'+projectId+' div[data-task-id = '+taskIdReduced+'].o-task').parents().eq(1).attr('data-status',newStatus);
	}

	$('#o-'+projectId+' div[data-task-id = '+taskIdReduced+'].o-task').attr('data-status',newStatus);
	

    // Check childrens
    var children = $('#o-'+projectId+' div.o-task[data-task-id='+taskIdReduced+']').find('.o-task').each(function(){

    	$(this).attr('data-status',newStatus);

    	var childId = $(this).attr('data-task-id');
    	var childIdReduced = childId;

    	if(newStatus == 1){
    		$('#status-'+childIdReduced).html('Done');
    	}else if(newStatus == 0){
    		$('#status-'+childIdReduced).html('Pending');
    	}


    });


    // Check siblings
    var siblingsConsensus = 0;
    var siblingsStatusArray = [''+newStatus+''];

    $('#o-'+projectId+' div.o-task[data-task-id='+taskIdReduced+']').siblings().each(function(){

    	var thisSiblingStatus = $(this).attr('data-status');
    	siblingsConsensus = thisSiblingStatus;

    	siblingsStatusArray.push(thisSiblingStatus);

    });

    var checkAllEqual = siblingsStatusArray.every((val, i, arr) => val === arr[0]);

    if(checkAllEqual == true){
    	$('#o-'+projectId+' div.o-task[data-task-id='+parentId+']').attr('data-status',newStatus);
		//$('#status-'+parentIdReduced).html('Done');
    }

	if(checkAllEqual == true && newStatus == 1 || newStatus == 0){
	    // Backtrack parents
		var i;
		for(i=0;i<currentDephtLevel;i++){
			//console.log(i);

			var level = i;
			var thisParentId = $('#o-'+projectId+' div.o-task[data-task-id='+taskIdReduced+']').parents('.o-task').eq(level).attr('data-task-id');
			//var thisParentIdReduced = thisParentId;

		    var siblingsConsensus = 0;
		    var siblingsStatusArray = [''+newStatus+''];

		    $('#o-'+projectId+' div.o-task[data-task-id='+thisParentId+'] .task-child').children().each(function(){

		    	var thisSiblingStatus = $(this).attr('data-status');
		    	siblingsConsensus = thisSiblingStatus;

		    	siblingsStatusArray.push(thisSiblingStatus);

		    });

		    var checkAllEqual = siblingsStatusArray.every((val, i, arr) => val === arr[0]);

		    if(checkAllEqual == true || checkAllEqual == false && newStatus == 0){
		    	$('#o-'+projectId+' div.o-task[data-task-id='+thisParentId+']').attr('data-status',newStatus);
		    }

		}

	}


    calibrateProgress(projectId);
    projectSave(projectId);


    var additionalData = [];


    additionalData.push('projectId::|' + projectId);
    additionalData.push('taskId::|' + taskIdReduced);
    additionalData.push('taskContent::|' + taskContent);
    additionalData.push('taskStartDate::|' + taskStartDate);
    additionalData.push('taskEndDate::|' + taskEndDate);
  	var loggingAdditionalData = JSON.stringify(additionalData);

    logAction(projectUniqueId,projectId,loggingAction,loggingAdditionalData)

}


$('#projects').on('keypress','.o-task',function(e){

	var key = e.which;
	var depthCurrent = $(this).attr('data-level');

	//var taskIdReduced = taskId.substring(5);
	var taskId = $(this).attr('data-task-id');
	var projectId = $(this).attr('data-project-id');
	var childId = $(this).attr('data-child-id');
	var childDepth = parseInt($('div[data-child='+childId+']').attr('data-depth-level'));
	var depthCurrent = childDepth;

	if(depthCurrent == ''){
		depthCurrent = 0;
	}


	// Enter Key
	if(key == 13){

		e.preventDefault();

		projectSave(projectId);

		var i;
		for (i = 0; i < 100; i++) {

			var newTaskId = Math.floor((Math.random() * 10000) + 1) + '-' + Math.floor((Math.random() * 10000) + 1) + '-' + Math.floor((Math.random() * 10000) + 1);
			var checkExist = checkTaskIdExist(newTaskId);

			if(checkExist != false){
				i = 100;
			}else{
				
			}

		}

  		var newTask = '<div data-task-id="'+newTaskId+'" class="o-task row" data-level="'+depthCurrent+'" data-child-id="'+childId+'" data-project-id="'+projectId+'" data-toggle="" data-progress-points="0" data-completed-points="0" data-status="0" data-est-time="0" data-date-start="" data-date-end="" data-position="0">';

  			newTask += '<div data-container-id="'+newTaskId+'" class="o-container" contenteditable="true" data-task-id="'+newTaskId+'"></div>';

  			newTask += '<div data-task-id="'+newTaskId+'" class="o-deadline">';
  			newTask += '<div class="o-est-time" data-est-time=""><em>Est. min.</em><input type="text" class="input" placeholder="10"></div>';
  			newTask += '<div class="o-date-start" data-date-start=""><em>Start date</em><input type="text" class="datepicker" placeholder="Choose date"></div>';
  			newTask += '<div class="o-date-end" data-date-end=""><em>Deadline</em><input type="text" class="datepicker" placeholder="Choose date"></div>';
  			newTask += '</div>';

  			newTask += '<div class="o-action" data-task-id-action="'+newTaskId+'"><span class="nav"></span><span class="mark" onclick="markCompleted(\'task-'+newTaskId+'\',\''+projectId+'\')"></span></div>';
  			newTask += '<div data-status="'+newTaskId+'" class="o-status">Pending</div>';

  			newTask += '<div data-meta-id="'+newTaskId+'" class="o-meta">';
          	newTask += '<div class="date-end"></div>';
          	newTask += '<div class="timeline"><div class="fill"></div></div>';
        	newTask += '</div>';

  		newTask += '</div>';

		//$('#editor .project-child[data-child='+childId+']').append(newProject);

		$(this).after(newTask);

		// Change parent status
		//$('#status-'+taskIdReduced).html('Pending');
		//$('#status-'+parentIdReduced).html('Pending');
		$(this).parents().eq(1).attr('data-status',0);


		setCaret('task-' + newTaskId);
		calibrateProgress(projectId);
		calibratePosition(projectId);

		$('.datepicker').datepicker({ firstDay: 1 });
		$('select').niceSelect();

		//console.log(projectId);

		return false;

	}

});


function calibratePosition(projectId){

    var i = 0;
    $('#o-'+projectId+' .editor').find('.o-task').each(function(){

        i++;

        $(this).attr('data-position',i);

    });

}


function calibrateGantt(projectId){

	// Update first date
	var dateMin = 2147483647; // End of the Unix World 2038 - REMEMBER TO DO SOMETHING
	// Update last date
	var dateMax = 0; // Start of the Unix World 1970

	$('#o-'+projectId).find('.o-task').each(function(){

		// Check if new min
		var thisDateStart = $(this).attr('data-date-start');
		var thisDateStartUnix = new Date(thisDateStart).getTime() / 1000;

		if(thisDateStart != undefined){
			if(thisDateStart != ''){

				if(thisDateStartUnix < dateMin){
					dateMin = thisDateStartUnix;
				}

			}
		}

		// Check if new max
		var thisDateEnd = $(this).attr('data-date-end');
		var thisDateEndUnix = new Date(thisDateEnd).getTime() / 1000;

		if(thisDateEnd != undefined){
			if(thisDateEnd != ''){

				if(thisDateEndUnix > dateMax){
					dateMax = thisDateEndUnix;
				}

			}
		}

	});

	if(dateMin == 2147483647){
		dateMin = 0;
	}

	$('#o-'+projectId).attr('data-first-date-u',dateMin);
	$('#o-'+projectId).attr('data-last-date-u',dateMax);




	// 1 week = 604800

	// Get week and current day
    Date.prototype.getWeek = function() {
        var yearFull = new Date(this.getFullYear(), 0, 1);
        return Math.ceil((((this - yearFull) / 86400000) + yearFull.getDay() ) / 7);
    }

    //var dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    var now = new Date();

    var weekMin = (new Date(dateMin*1000)).getWeek();
    var weekMax = (new Date(dateMax*1000)).getWeek();
    var weekOffset = ((now.getDay()-1) * (24*60*60));
    var weekStartPosition = dateMin;
    var weekCurrentPosition = weekStartPosition;


    var timelineMax = dateMax + 7 * 86400; // Extra week
    var weekMaxNew = weekMax + 1;
    var weekDiff = weekMaxNew - weekMin;

	var tasktRange = timelineMax - dateMin;
    var taskRangeNew = weekDiff * 7 * 86400;

    // Nasty code
	d = new Date(dateMin*1000);
	var day = d.getDay(),
	  diff = d.getDate() - day + (day == 0 ? -6:1); // adjust when day is sunday
	
    var weekInitialDay = new Date(d.setDate(diff));
    var weekInitialDayUnix = weekInitialDay.getTime() / 1000;

	$('#o-'+projectId).find('.o-task').each(function(){

		var thisDateStart = $(this).attr('data-date-start');
		var thisDateStartUnix = (new Date(thisDateStart).getTime() / 1000);

		var thisDateEnd = $(this).attr('data-date-end');
		var thisDateEndUnix = new Date(thisDateEnd).getTime() / 1000;

		var now = new Date(thisDateStart);
    	var thisOffset = (now.getDay()-1) * (24*60*60);


		var taskId = $(this).attr('data-task-id');
		//var timelineCssWidth = 0;
		//var timelineCssLeft = 0;

		//console.log('THIS START DATE:'+thisDateStart);
		//console.log('THIS START DATE UNIX:'+thisDateStartUnix);
		//console.log('THIS END DATE:'+thisDateEnd);
		//console.log('THIS END DATE UNIX:'+thisDateEndUnix);

		if(thisDateStart != 0 && thisDateEnd != 0){
			
			var taskRange = dateMax - dateMin + thisOffset; // Add offset
			var timelineRange = thisDateEndUnix - thisDateStartUnix + 86400; 

			var offsetCssLeft = thisOffset / taskRangeNew * 100;


			var timelineCssWidth = timelineRange / taskRangeNew * 100;
			// timelineCssLeft = (100 - timelineCssWidth)+offsetCssLeft;

			var timelineCssLeft = ((thisDateStartUnix - weekInitialDayUnix) / taskRangeNew * 100);


			/*console.log('original max: '+dateMax);
			console.log('task range: '+taskRangeNew);
			console.log('timeline range: '+timelineRange);


			console.log('offset: '+thisOffset);
			console.log('css offset: '+offsetCssLeft);

			console.log('css width: '+timelineCssWidth);
			console.log('css left: '+timelineCssLeft);*/

		}


		$('div.o-meta[data-meta-id='+taskId+'] .timeline .fill').css('width',timelineCssWidth+'%');
		$('div.o-meta[data-meta-id='+taskId+'] .timeline .fill').css('left',timelineCssLeft+'%');

	});



    if(weekMin <= weekMax){

    	// Clear
    	$('.weeknumbers').html('');
    	var weeknumbersCssLeft = 0;

    	var weekClass = '';
    	if(weekDiff > 10){
    		weekClass = 'small';
    	}
    	if(weekDiff > 20){
    		weekClass += ' extra';
    	}

	    var i;
	    for(i = weekMin ; i < weekMaxNew ; i++){

			// Calculate width
			var weeknumbersCssWidth = 100 / weekDiff;

			// Append

			$('.weeknumbers').append('<div class="week week-'+i+' '+weekClass+'"><span><span>Week</span> <em>'+i+'</em></span></div>');
			$('.weeknumbers .week-'+i+'').css('left',weeknumbersCssLeft+'%');
			$('.weeknumbers .week-'+i+'').css('width',weeknumbersCssWidth+'%');

	    	weekCurrentPosition += 604800; // Add 1 week
			weeknumbersCssLeft += weeknumbersCssWidth; // Add 1 space

	    }


    }

    /*var newHeight = $('#o-'+projectId).height();
    $('#o-'+projectId+' .weeknumbers .line').css('height',newHeight);

    console.log(newHeight + 'LINEHEIGHT');
    console.log(projectId + 'LINEHEIGHT');*/

}


$('#projects').on('change','.o-date-start',function(e){

	var projectId = $(this).parents('.o-task').eq(0).attr('data-project-id');

	var dateStart = $(this).find('input.datepicker').val();
	$(this).attr('data-date-start',dateStart);

	// Add to parent
	$(this).parents('.o-task').eq(0).attr('data-date-start',dateStart);

	calibrateGantt(projectId);

});

$('#projects').on('change','.o-date-end',function(e){

	var projectId = $(this).parents('.o-task').eq(0).attr('data-project-id');

	var dateEnd = $(this).find('input.datepicker').val();
	$(this).attr('data-date-end',dateEnd);

	// Add to parent
	$(this).parents('.o-task').eq(0).attr('data-date-end',dateEnd);

	if(dateEnd == ''){
		$(this).parents('.o-task').eq(0).removeClass('has-deadline');
	}else{
		$(this).parents('.o-task').eq(0).addClass('has-deadline');
	}

	// Add to meta
	var parentId = $(this).parents('.o-task').eq(0).attr('data-task-id');
	$('div[data-meta-id='+parentId+'] .date-end').html(dateEnd);

	calibrateGantt(projectId);

});

$('#projects').on('change','.o-time',function(e){

	var deadlineTime = $(this).find('.list li.selected').attr('data-value');
	$(this).attr('data-deadline-time',deadlineTime);

	// Add to parent
	var currentDeadlineDate = $(this).attr('data-deadline-date');
	var currentDeadlineTime = $(this).siblings('.o-time').attr('data-deadline-time');

	if(currentDeadlineDate != '' && currentDeadlineTime != ''){
		$(this).parents('.o-task').eq(0).attr('data-deadline',currentDeadlineDate + ' ' + currentDeadlineTime);
	}

});


$('#projects').on('keydown','.project-title',function(e){
	
	var key = e.which;

	var projectId = $(this).parents().eq(2).attr('data-project-id');

    if((e.ctrlKey || e.metaKey) && key == 83) {

    	e.preventDefault();

		// Save
		projectSave(projectId);

		console.log('SAVE: ' +  projectId);

        return false;

    }

});


$('#projects').on('keydown','.o-task',function(e){
	
	var key = e.which;
	var depthCurrent = $(this).attr('data-level');

	if(depthCurrent == ''){
		depthCurrent = 0;
	}



	var taskId = $(this).attr('data-task-id');
	var projectId = $(this).attr('data-project-id');
	var childId = $(this).attr('data-child-id');

	var taskPosition = parseInt($(this).attr('data-position'));
	var taskToggle = $(this).attr('data-toggle');

	var taskDepth = parseInt($(this).attr('data-level'));
	var childDepth = parseInt($('div[data-child='+childId+']').attr('data-depth-level'));
	var depthCurrent = childDepth;
	var parentId = $(this).parents().eq(1).attr('data-task-id');

    var currentChildId = $('#o-'+projectId+' div.o-task[data-task-id='+taskId+']').attr('data-child-id');


	var siblingIdPrev = $(this).prev().attr('data-task-id');
	if(siblingIdPrev){
		var siblingIdPrevReduced = siblingIdPrev;
	}

	var siblingIdNext = $(this).next().attr('data-task-id');
	if(siblingIdNext){
		var siblingIdNextReduced = siblingIdNext;
	}



    if((e.ctrlKey || e.metaKey) && key == 83) {

    	e.preventDefault();

		// Save
		projectSave(projectId);

		console.log('SAVE');

        return false;

    }

	if(key === 38){

		// Save
		projectSave(projectId);

		var prevElement = siblingIdPrev;
		if(siblingIdPrev == undefined){
			prevElement = parentId;
		}

		setCaret('task-'+prevElement);
		return false;
	}

	if(key === 40){

		// Save
		projectSave(projectId);

		if(taskToggle == 'close'){
			var nextElement = siblingIdNext;
		}else{
			//var nextElement = $(this).find('.o-task').attr('data-task-id');
			var nextElementPosition = taskPosition + 1;
			var nextElement = $('#o-'+projectId+' div.o-task[data-position='+nextElementPosition+']').attr('data-task-id');
		}

		if(nextElement == undefined){
			nextElement = siblingIdNext;
		}

		setCaret('task-'+nextElement);
		return false;
	}

	// Shift + Tab Key
	if(e.shiftKey){

		if(key === 9){

			e.preventDefault();

	        var depthLevelNext = 0;
	        var depthLevelPrev = 0;

	        var currentChildId = $('#o-'+projectId+' div.o-task[data-task-id='+taskId+']').attr('data-child-id');

	        depthLevelNext = taskDepth + 1;
	        depthLevelPrev = taskDepth - 1;
	        depthLevelThreshold = taskDepth + 1;

	        var parentIdOld = $(this).parents().eq(1).attr('data-task-id');
	        var ancestorChildId = $('#o-'+projectId+' div.o-task[data-task-id='+parentIdOld+']').attr('data-child-id');

	        console.log(taskDepth);
	        if(taskDepth > 0){

		        $('#o-'+projectId+' div.o-task[data-task-id='+taskId+']').appendTo('#o-'+projectId+' div[data-child='+ancestorChildId+']');
		        $('#o-'+projectId+' div.o-task[data-task-id='+taskId+']').attr('data-level',depthLevelPrev);
		        $('#o-'+projectId+' div.o-task[data-task-id='+taskId+']').attr('data-child-id',ancestorChildId);

				$('#o-'+projectId+' div.o-task[data-task-id='+taskId+']').find('.task-child').each(function(){

					var currentChildLevel = $(this).attr('data-depth-level');
					var newChildLevel = currentChildLevel - 1;
					$(this).attr('data-depth-level',newChildLevel);

					$(this).find('.o-task').each(function(){
						$(this).attr('data-level',newChildLevel);
					});

				});	        

		        setCaret('task-'+taskId);
		        calibrateProgress();

		        // Check if child is empty
		        var currentChildCheck = $('#o-'+projectId+' .task-child[data-child='+currentChildId+'] div.o-task').attr('data-task-id');

		        if(currentChildCheck == undefined){
		        	$('#o-'+projectId+' .task-child[data-child='+currentChildId+']').remove();
		        	$('#o-'+projectId+' div.o-task[data-task-id='+parentIdOld+']').removeClass('parent');
					$('#o-'+projectId+' div.o-task[data-task-id='+parentIdOld+'] .o-action[data-task-id-action='+parentIdOld+'] .arrow').remove();
		        }

		        //console.log(currentChildCheck);

	        }

	        return false;

		}

	}

	// Tab Key
	if(key == 9){

		e.preventDefault();

        var depthLevelNext = 0;
        var depthLevelPrev = 0;

        depthLevelNext = taskDepth + 1;
        depthLevelPrev = taskDepth - 1;
        depthLevelThreshold = taskDepth + 1;

        // Check siblings

        var siblingChildIdPrev = $('#o-'+projectId+' div.o-task[data-task-id='+siblingIdPrev+'] div[data-depth-level='+depthLevelNext+']').attr('data-child');

        if(siblingIdPrev != undefined){

		    $(this).attr('data-level',depthLevelNext);

		    //console.log(siblingIdPrev + 'PREV');

        	if(siblingChildIdPrev != undefined){

        		$('#o-'+projectId+' div.o-task[data-task-id='+taskId+']').appendTo('#o-'+projectId+' div.o-task[data-task-id='+siblingIdPrev+'] div[data-child='+siblingChildIdPrev+']');
				$('#o-'+projectId+' div.o-task[data-task-id='+siblingIdPrev+'] .o-action[data-task-id-action='+siblingIdPrev+'] .mark').after('<span class="arrow" onclick="toggleTask(\'task-'+siblingIdPrevReduced+'\',\''+projectId+'\')"></span>');
				$('#o-'+projectId+' div.o-task[data-task-id='+taskId+']').attr('data-child-id',siblingChildIdPrev);

        	}else{

		        var childId = Math.floor((Math.random() * 1000) + 1);

		        $('#o-'+projectId+' div.o-task[data-task-id='+siblingIdPrev+']').append('<div class="task-child lvl-'+ depthLevelNext +'" data-child="'+childId+'" data-parent="'+siblingIdPrev+'" data-depth-level="'+depthLevelNext+'"></div>');

				$('#o-'+projectId+' div.o-task[data-task-id='+taskId+']').appendTo('#o-'+projectId+' div.o-task[data-task-id='+siblingIdPrev+'] div[data-depth-level='+depthLevelNext+']');
				$('#o-'+projectId+' div.o-task[data-task-id='+siblingIdPrev+']').addClass('parent');
				$('#o-'+projectId+' div.o-task[data-task-id='+siblingIdPrev+'] .o-action[data-task-id-action='+siblingIdPrev+'] .mark').after('<span class="arrow" onclick="toggleTask(\'task-'+siblingIdPrev+'\',\''+projectId+'\')"></span>');

				$('#o-'+projectId+' div.o-task[data-task-id='+taskId+']').attr('data-child-id',childId);

        	}
	
			setCaret('task-'+taskId);
			calibrateProgress(projectId);

	        // Check if child is empty
	        var currentChildCheck = $('#o-'+projectId+' .task-child[data-child='+currentChildId+'] div.o-task').attr('data-task-id');

	        if(currentChildCheck == undefined){
	        	$('#o-'+projectId+' .task-child[data-child='+currentChildId+']').remove();
	        }

        }else{

        	// Do nothing

        }

        // Check childrens
        var children = $(this).find('.task-child').each(function(){

        	var childrenDepthLevel = $(this).attr('data-depth-level');

        	childrenDepthLevel++;

        	$(this).attr('data-depth-level',childrenDepthLevel);

        	// Children tasks
        	var childrenTasks = $(this).find('.o-task').each(function(){

        		$(this).attr('data-level',childrenDepthLevel);

        	});

        });

        return false;

	}


	// Backspace Key
	if(key === 8){

		var trigger = false;

		// Save changes text
		$('#o-'+projectId+' .saving .text').html('Save changes');

		var taskContainer = $('#o-'+projectId+' .o-container[data-task-id='+taskId+']');
		var taskContainerHtml = $('#o-'+projectId+' .o-container[data-task-id='+taskId+']').html();

        // Check childrens
        var childrenId = $(this).find('.project-child').attr('data-child');

		if(taskContainerHtml == ''){

			console.log(taskId);

			if(taskId != '1111'){

				e.preventDefault();

				$('#o-'+projectId+' div.o-task[data-task-id='+taskId+']').remove();

				if(siblingIdPrev != undefined){
					setCaret('task-'+siblingIdPrev);
					calibrateProgress();
				}else{
					setCaret('task-'+parentId);
					calibrateProgress();
				}

		        // Check if child is empty
		        var currentChildCheck = $('#o-'+projectId+' .task-child[data-child='+currentChildId+'] div.o-task').attr('data-task-id');

		        if(currentChildCheck == undefined){
					$('#o-'+projectId+' div.o-task[data-task-id='+taskId+']').removeClass('parent');
					$('#o-'+projectId+' div.o-task[data-task-id='+taskId+'] .o-action[data-task-id-action='+taskId+'] .arrow').remove();
		        	$('.task-child[data-child='+currentChildId+']').remove();
		        }

		    }

		}else{

			trigger = true;

		}


		return trigger;

	}



	//$('#o-'+projectId+' .saving.show').toggleClass('show');
	$('#o-'+projectId+' .saving .text').html('Save changes');


});



function tabindex(){

	var i = 0;
	$('#editor').find('.o-task').each(function(){

		$(this).attr('tabindex',i);
		i++;

	});

}


function calibrateTasks(projectId){

	//var DepthLevel = 0;

	$('#'+projectId).find('.task-child').each(function(){

		var currentDephtLevel = $(this).attr('data-depth-level');

		$(this).children().each(function(){

			$(this).attr('data-level',currentDephtLevel);

		});

	});

}

function setCaret(contenteditableId){

    var contenteditableIdReduced = contenteditableId.substring(5);

    var el = $('div[data-container-id='+contenteditableIdReduced+']')[0];

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