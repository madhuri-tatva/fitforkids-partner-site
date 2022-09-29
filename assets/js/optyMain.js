function loadSidecontentGraph($landingpageid,$period,$views,$users,$conversions,$conversionrate){

    $('#sidecontent-inner').load("/modules/sidecontent/viewLandingpageStats.php?id="+$landingpageid+"&period="+$period+'&views='+$views+'&users='+$users+'&conversions='+$conversions+'&conversionrate='+$conversionrate,function(){
        $('#opty-js').load("/actions/js.php");
    });

}

function loadDashboardGraph($period,$views,$users,$conversions,$conversionrate){

    $('#dashboard-graph').load("/modules/data/viewGraphDashboard.php?period="+$period+'&views='+$views+'&users='+$users+'&conversions='+$conversions+'&conversionrate='+$conversionrate);

}

function landingpageData($landingpageid){
    
    setTimeout(function(){
        $('#sidecontent-inner').load('modules/sidecontent/viewLandingpageStats.php?id='+$landingpageid+'&period=7&views=0&users=1&conversions=1&conversionrate=0');
        $('#opty-js').load("/actions/js.php");
    }, 500);

}