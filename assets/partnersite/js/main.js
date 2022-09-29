function addAlertModal(type,message) {

    $("#alertmodal").removeClass('alert');
    $("#alertmodal").removeClass('suc');
    $("#alertmodal").removeClass('war');
    $("#alertmodal").removeClass('err');

    var cls = type;
    if(type == 1){
        cls = 'alert suc';
    }
    if(type == 2){
        cls = 'alert war';
    }
    if(type == 3){
        cls = 'alert err';
    }

    $("#alertmodal").html("<div>"+message+"</div>");
    $("#alertmodal").addClass(cls);

}

function addAlertModal2(type,message) {

    $("#alertmodal2").removeClass('alert');
    $("#alertmodal2").removeClass('suc');
    $("#alertmodal2").removeClass('war');
    $("#alertmodal2").removeClass('err');

    var cls = type;
    if(type == 1){
        cls = 'alert suc';
    }
    if(type == 2){
        cls = 'alert war';
    }
    if(type == 3){
        cls = 'alert err';
    }

    $("#alertmodal2").html("<div>"+message+"</div>");
    $("#alertmodal2").addClass(cls);

}


function addAlert(type,message) {

    $("#alert").removeClass('alert');
    $("#alert").removeClass('suc');
    $("#alert").removeClass('war');
    $("#alert").removeClass('err');

    var cls = type;
    if(type == 1){
        cls = 'alert suc';
    }
    if(type == 2){
        cls = 'alert war';
    }
    if(type == 3){
        cls = 'alert err';
    }

    $("#alert").html("<div>"+message+"</div>");
    $("#alert").addClass(cls);

}



function pad(n, width, z) {
  z = z || '0';
  n = n + '';
  return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}


function difference(start, end){
    return Math.abs(start - end)
}