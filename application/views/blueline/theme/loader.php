<div class="loader"></div>

<script type="text/javascript">
    $(window).load(function() {
        $(".loader").fadeOut("slow");
    });
</script>

<style type="text/css">
    .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('<?=base_url()?>/files/media/widget-loader-lg-en.gif') 50% 50% no-repeat rgb(249,249,249);
        opacity: .8;
    }
</style>
