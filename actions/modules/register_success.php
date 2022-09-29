<?php 
include("../../includes/config.php");

if($domian_url == 'www.plangy.com'){
    putenv('LC_ALL=en_GB');
    setlocale(LC_ALL, 'en_GB');

    bindtextdomain('en_GB', "../../locale");
    bind_textdomain_codeset('en_GB', 'UTF-8'); 

    textdomain('en_GB');
}elseif($domian_url == 'www.plangy.dk'){
    putenv('LC_ALL=da_DK');
    setlocale(LC_ALL, 'da_DK');

    bindtextdomain('da_DK', "../../locale");
    bind_textdomain_codeset('da_DK', 'UTF-8'); 

    textdomain('da_DK');
}
?>

<div class="form-inner">

    <div id="signup-step-1" class="page-success">

        <div class="md-col-12">

            <h2><?php echo _('Great success!'); ?></h2>
            <p><?php echo _("Du er nu oprettet hos Plangy."); ?></p>

            <form action="<?php echo $basehref; ?>actions/login.php" method="post">
                <input type="hidden" name="access" value="<?php echo $_COOKIE['guard']; ?>" />
                <input type="submit" id="submit" class="btn-cta" value="<?php echo _('Go to dashboard'); ?>" />
            </form>

        </div>

    </div>

</div>

<?php
session_destroy();
?>

<script type="text/javascript">
fbq('track', 'CompleteRegistration', {
    content_name: 'Pro lead',
});
gtag('event', 'conversion', {'send_to': 'AW-800884658/kEQQCKm4jocBELKP8v0C'});
</script>