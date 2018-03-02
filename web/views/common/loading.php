<?php
$bgs = [
    'bg-primary',
    'bg-info',
    'bg-warning',
    'bg-danger',
    'bg-success'
];

?>
<div id="preloader" class="">
    <div class="bg <?=$bgs[mt_rand(0,4)]?>"></div>
    <div class="sk-spinner sk-spinner-wave" id="status">
        <div class="sk-rect1"></div>
        <div class="sk-rect2"></div>
        <div class="sk-rect3"></div>
        <div class="sk-rect4"></div>
        <div class="sk-rect5"></div>
    </div>
</div><!-- End Preload -->