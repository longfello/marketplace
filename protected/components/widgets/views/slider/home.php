<div class="slider">
  <ul class="bxslider">
    <?php foreach($gallery->galleryPhotos as $slide) : ?>
      <li>
        <?php if ($slide->link!='') echo "<a href='".$slide->link."'>"; ?>
        <img src="<?= $slide->getUrl('slider') ?>" title="<span><?=$slide->name?></span><span><?=$slide->description?></span>" />
        <?php if ($slide->link!='') echo "</a>"; ?>
      </li>
    <?php endforeach ?>
  </ul>

</div>
