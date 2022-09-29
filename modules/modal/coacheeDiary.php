<?php
    include("../../includes/config.php");
    if(isset($_GET['Id']) && ($_GET['Id']>0) && (!empty($_SESSION['UserId']))){
        $db->where('id',$_GET['Id']);
        $db->where('coachee_id',$_SESSION['UserId']);
        $cDiary = $db->getOne("coachee_diary");
        $saved_date = date('d-m-Y',strtotime($cDiary['date']));
        $saved_note = $cDiary['notes'];
        $diary_id = $_GET['Id'];
        $diary_page_number = $cDiary['page_number'];

?>

<div class="popup-header">
    <span class="title">KÆRE Dagbog</span>
    <div class="date-wrapper">
        <span>dato</span>
        <input type="text" placeholder="<?= date('d-m-Y')?>" value="<?= $saved_date?>" name="date" id="diary-updated-date">
    </div>
</div>
<div class="popup-content-inner">
    <div class="comment-page-wrapper">
        <textarea placeholder="" id="coachee-updated-notes" maxlength="1080"><?= $saved_note?></textarea>
        <div class="btn-wrapper">
            <button title="Save" class="btn save-btn" id="diary-update-btn" onclick="updatediary()">Gem</button>
        </div>
    </div>
</div>
<input type="hidden" id="diary-id" value="<?= $diary_id?>">
<input type="hidden" id="diary-page-number" value="<?= $diary_page_number?>">

<?php } ?>
<?php
if(isset($_GET['Action']) && $_GET['Action']==2){
    $db->where('coachee_id',$_SESSION['UserId']);
    $coacheeDiary = $db->get("coachee_diary");?>
    <div class="slider-wrapper diary-pages-slider">
    <?php
     $page_number=0;$cover_page=$cover_image='';
     if(!empty($coacheeDiary)){
                    foreach ($coacheeDiary as $diary) {?>
                    <div class="slide-item diary-pages-items">
                        <div class="popup-top-bar">
                            <div class="back-button" onclick="backCoverPages()">
                                <a href="#"><img src="../assets/img/left-arrow-back.svg" alt="Back"></a>
                            </div>
                            <div class="add-button">
                                <!-- <a href="#" class="btn" title="Add New Page" data-popup="diary-pages-edit-popup"> Ny side</a> -->
                                <a href="#" class="btn" title="Add New Page" onclick="addDiaryPage()"> Ny side</a>
                            </div>
                             <a href="#" class="btn btn-edit" title="Edit" onclick="getDiary(<?= $diary['id']?>)"> <i class="fa fa-pencil"></i></a>
                        </div>
                        <div class="popup-header">
                            <span class="title">KÆRE DAGBOG</span>
                            <div class="date-wrapper">
                                <span>dato</span>
                                <span><?= date('d-m-Y',strtotime($diary['date']))?></span>
                            </div>
                        </div>
                        <div class="popup-content-inner">
                            <div class="comment-page-wrapper">
                                <p style="white-space:pre-line"><?= $diary['notes']?></p>
                            </div>
                        </div>
                    </div>
                <?php } $page_number = $diary['page_number']; $cover_page = $diary['cover_page'];$cover_image = $diary['cover_image'];}?>
</div>
<input type="hidden" id="cover-id" value="<?= $cover_page?>">
<input type="hidden" id="cover-image" value="<?= $cover_image?>">
<input type="hidden" id="page-number" value="<?= $page_number+1?>">
<?php }?>