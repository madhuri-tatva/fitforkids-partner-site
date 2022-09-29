<?php
$cols = $_GET['cols'];
$colSize = $_GET['colsize'];

function getColumnTemplates($cols,$colsize){

    if($cols == 1){

        $templates = '
            <div class="row">
                
                <a class="block-col col-md-12" data-cols="1" data-col-size="100">
                    <div class="col-md-12"><span>100%</span></div>
                </a>

            </div>
        ';

    }elseif($cols == 2){

        $templates = '
            <div class="row">
                
                <a class="block-col col-md-6" data-cols="2" data-col-size="50/50">
                    <div class="col-md-6"><span>50%</span></div>
                    <div class="col-md-6"><span>50%</span></div>
                </a>
                
                <a class="block-col col-md-6" data-cols="2" data-col-size="25/75">
                    <div class="col-md-3"><span>25%</span></div>
                    <div class="col-md-9"><span>75%</span></div>
                </a>

            </div>

            <div class="row">
                
                <a class="block-col col-md-6" data-cols="2" data-col-size="75/25">
                    <div class="col-md-9"><span>75%</span></div>
                    <div class="col-md-3"><span>25%</span></div>
                </a>

            </div>
        ';

    }elseif($cols == 3){

        $templates = '
            <div class="row">
                
                <a class="block-col col-md-6" data-cols="3" data-col-size="33/33/33">
                    <div class="col-md-4"><span>33%</span></div>
                    <div class="col-md-4"><span>33%</span></div>
                    <div class="col-md-4"><span>33%</span></div>
                </a>
                
                <a class="block-col col-md-6" data-cols="3" data-col-size="25/50/25">
                    <div class="col-md-3"><span>25%</span></div>
                    <div class="col-md-6"><span>50%</span></div>
                    <div class="col-md-3"><span>25%</span></div>
                </a>

            </div>

            <div class="row">
                
                <a class="block-col col-md-6" data-cols="3" data-col-size="50/25/25">
                    <div class="col-md-6"><span>50%</span></div>
                    <div class="col-md-3"><span>25%</span></div>
                    <div class="col-md-3"><span>25%</span></div>
                </a>
                
                <a class="block-col col-md-6" data-cols="3" data-col-size="25/25/50">
                    <div class="col-md-3"><span>25%</span></div>
                    <div class="col-md-3"><span>25%</span></div>
                    <div class="col-md-6"><span>50%</span></div>
                </a>

            </div>
        ';

    }elseif($cols == 4){

        $templates = '
            <div class="row">
                
                <a class="block-col col-md-12" data-cols="4" data-col-size="25/25/25/25">
                    <div class="col-md-3"><span>25%</span></div>
                    <div class="col-md-3"><span>25%</span></div>
                    <div class="col-md-3"><span>25%</span></div>
                    <div class="col-md-3"><span>25%</span></div>
                </a>

            </div>
        ';

    }elseif($cols == 5){

        $templates = '
            <div class="row">
                
                <a class="block-col col-md-12" data-cols="5" data-col-size="20/20/20/20/20">
                    <div class="col-md-2-4"><span>20%</span></div>
                    <div class="col-md-2-4"><span>20%</span></div>
                    <div class="col-md-2-4"><span>20%</span></div>
                    <div class="col-md-2-4"><span>20%</span></div>
                    <div class="col-md-2-4"><span>20%</span></div>
                </a>

            </div>
        ';

    }

    return $templates;

}

echo getColumnTemplates($cols,$colSize);
?>

<script type="text/javascript">
$('.block-col').click(function(){
    $('.block-col').removeClass('active');
    $(this).addClass('active');
});

var colSize = '<?php echo $colSize; ?>';
var cols = '<?php echo $cols; ?>';

$('.block-col[data-cols=\''+cols+'\'][data-col-size=\''+colSize+'\']').addClass('active');
</script>