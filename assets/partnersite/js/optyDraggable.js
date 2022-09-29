


$(function(){
	$(".flex").resizable();
	$(".sortable").sortable().resizable();
	$(".draggable").draggable().resizable();
	$(".draggable-block").draggable({

		cursorAt: { left: 50, top: 50 },

		//snap: ".inner",
		revert: 'invalid',
		revertDuration: 600,
		helper: "original",

	});
});


$('.droparea').droppable({

    accept: '.draggable-block',

    drop: function(ev, ui) {

        var dropped = ui.draggable;
        var droppedOn = $(this);
        var parent = $(this).parent('.droparea');


        $(dropped).detach().css({top: 0,left: 0}).appendTo(droppedOn);

        /*if(dropped.hasClass('draggable-block')){
        	$(dropped).detach().css({top: 0,left: 0}).appendTo(droppedOn);
        }else{
        	revert: true
        }

        console.log(parent);*/

    }
});

$( function() {
	$( "#opty-editor" ).sortable();
} );



/*

x = 0, y = 0;

interact('.draggable')

	.draggable({
		onmove: window.dragMoveListener,
		restrict: {
		  restriction: 'parent',
		  elementRect: { top: 0, left: 0, bottom: 1, right: 1 }
		},
	})
	.resizable({
		// resize from all edges and corners
		edges: { left: true, right: true, bottom: true, top: false },

		// keep the edges inside the parent
		restrictEdges: {
		  outer: 'parent',
		  endOnly: true,
		},

		// minimum size
		restrictSize: {
		  min: { width: 100, height: 40 },
		},

		inertia: true,
	})

	.on('resizemove', function (event) {
		var target = event.target,
		    x = (parseFloat(target.getAttribute('data-x')) || 0),
		    y = (parseFloat(target.getAttribute('data-y')) || 0);

		// update the element's style
		target.style.width  = event.rect.width + 'px';
		target.style.height = event.rect.height + 'px';

		// translate when resizing from top or left edges
		x += event.deltaRect.left;
		y += event.deltaRect.top;

		target.style.webkitTransform = target.style.transform =
		    'translate(' + x + 'px,' + y + 'px)';

		target.setAttribute('data-x', x);
		target.setAttribute('data-y', y);
		//target.textContent = Math.round(event.rect.width) + '\u00D7' + Math.round(event.rect.height);
	});



interact('.drag-drop')
  .draggable({
    inertia: true,
    restrict: {
      restriction: "parent",
      endOnly: true,
      elementRect: { top: 0, left: 0, bottom: 1, right: 1 }
    },
    autoScroll: true,
    // dragMoveListener from the dragging demo above
    onmove: dragMoveListener,
  });


  function dragMoveListener (event) {
    var target = event.target,
        // keep the dragged position in the data-x/data-y attributes
        x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx,
        y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

    // translate the element
    target.style.webkitTransform =
    target.style.transform =
      'translate(' + x + 'px, ' + y + 'px)';

    // update the posiion attributes
    target.setAttribute('data-x', x);
    target.setAttribute('data-y', y);
  }

  // this is used later in the resizing and gesture demos
  window.dragMoveListener = dragMoveListener;


// enable draggables to be dropped into this
interact('.dropzone')

	.dropzone({
		// only accept elements matching this CSS selector
		accept: '#yes-drop',
		// Require a 75% element overlap for a drop to be possible
		overlap: 0.75,

		// listen for drop related events:

		ondropactivate: function (event) {
		// add active dropzone feedback
		event.target.classList.add('drop-active');
		},
		ondragenter: function (event) {
		var draggableElement = event.relatedTarget,
		    dropzoneElement = event.target;

		// feedback the possibility of a drop
		dropzoneElement.classList.add('drop-target');
		draggableElement.classList.add('can-drop');
		draggableElement.textContent = 'Dragged in';
		},
		ondragleave: function (event) {
		// remove the drop feedback style
		event.target.classList.remove('drop-target');
		event.relatedTarget.classList.remove('can-drop');
		event.relatedTarget.textContent = 'Dragged out';
		},
		ondrop: function (event) {
		event.relatedTarget.textContent = 'Dropped';
		},
		ondropdeactivate: function (event) {
		// remove active dropzone feedback
		event.target.classList.remove('drop-active');
		event.target.classList.remove('drop-target');
		}
	})


*/



