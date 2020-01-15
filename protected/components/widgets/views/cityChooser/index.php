<?php /* @var $this cityChooser */ ?>
<div class="background_popup"></div>
<div class="choose-location">
  <a class="current-location" href="#"><?=$this->location->city->name ?></a>
</div>

<div class="choose-city hidden">
  <div class="yes-ext">
    <div class="your-city">Ваш город <a href="#"><?=$this->location->city->name?></a>?</div>
    <a class="yes" href="#">Да</a><a class="no" href="#">Нет</a>
  </div>

  <div class="no-ext hidden">
    <h4>Выберите город</h4>
    <ul class="cityChooser-list">
      <?php
        foreach($this->defaultCities as $city) {
          echo("<li><a href='#' data-id='{$city->id}'>{$city->name}</a></li>");
        }
      ?>
    </ul>
    <h4>Или укажите другой</h4>
    <input type="hidden" id="city-acp-id" value="<?=$this->location->city->id?>">
    <input type='text' id="city-acp" value="<?=$this->location->city->name?>">

    <div class="note">Многие товары доставляются во все регионы. Вам будут показаны только товары с доставкой в ваш регион.</div>
  </div>
</div>