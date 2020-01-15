<!-- Vk !-->
<div class="social-like-button social-like-vk">
  <?php
    Yii::app()->clientScript->registerScriptFile('//vk.com/js/api/openapi.js?112', CClientScript::POS_BEGIN);
  ?>
  <script type="text/javascript">
    VK.init({apiId: 4334593, onlyWidgets: true});
  </script>

  <!-- Put this div tag to the place, where the Like block will be -->
  <div id="vk_like"></div>
  <script type="text/javascript">
    VK.Widgets.Like("vk_like", {type: "button"});
  </script>
</div>

<!-- FB !-->
<div class="social-like-button social-like-fb">
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1&appId=1414196762130264";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

  <div class="fb-like"  data-layout="button_count" data-href='<?=$url?>' data-action="like" data-show-faces="true" data-share="false"></div>
</div>

<!-- G+ !-->
<div class="social-like-button social-like-gp">
  <?php
    Yii::app()->clientScript->registerScriptFile('https://apis.google.com/js/platform.js', CClientScript::POS_BEGIN);
  ?>
  <!-- Поместите этот тег туда, где должна отображаться кнопка +1. -->
  <div class="g-plusone" data-size="medium" data-href="<?=$url?>"></div>
</div>

<!-- Twitter !-->
<div class="social-like-button social-like-twitter">
  <a href="https://twitter.com/share" class="twitter-share-button"><?=Yii::t('widget-like','Tweet')?></a>
  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
</div>

<!-- Mail.ru !-->
<div class="social-like-button social-like-mailru">
  <a target="_blank" class="mrc__plugin_uber_like_button" href="http://connect.mail.ru/share" data-mrc-config="{'cm' : '1', 'sz' : '20', 'st' : '2', 'tp' : 'mm'}"><?=Yii::t('widget-likes','Нравится')?></a>
  <script src="http://cdn.connect.mail.ru/js/loader.js" type="text/javascript" charset="UTF-8"></script>
</div>

<!-- Odnoklassniki !-->
<div class="social-like-button social-like-od">
  <div id="ok_shareWidget"></div>
  <script>
    !function (d, id, did, st) {
      var js = d.createElement("script");
      js.src = "http://connect.ok.ru/connect.js";
      js.onload = js.onreadystatechange = function () {
        if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
          if (!this.executed) {
            this.executed = true;
            setTimeout(function () {
              OK.CONNECT.insertShareWidget(id,did,st);
            }, 0);
          }
        }};
      d.documentElement.appendChild(js);
    }(document,"ok_shareWidget",document.URL,"{width:145,height:30,st:'rounded',sz:20,ck:1}");
  </script>
</div>
