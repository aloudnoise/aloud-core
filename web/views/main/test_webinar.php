<div class="action-content">

    <?php if (!$meeting) { ?>

        <a href="<?=\app\helpers\OrganizationUrl::to(['/main/webinar', 'start' => 1])?>">
            <?="Старт вебинара епта"?>
        </a>

    <?php } else { ?>

        <a href="<?=\app\helpers\OrganizationUrl::to(['/main/webinar', 'join' => $meeting->getMeetingId()])?>"><?="Войти епта"?></a>
        
    <?php } ?>

    
    
</div>