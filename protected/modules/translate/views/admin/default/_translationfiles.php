<?php
$params = array(
            'module' => $module,
            'languageid' => $languageid,
        );
?>
        
<div id="existingfiles">
    <?php echo $this->renderPartial('_existingfiles', $params); ?>
</div>

<div id="missingfiles">
    <?php echo $this->renderPartial('_missingfiles', $params); ?>
</div>