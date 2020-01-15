<tr class="<?=($index % 2)?'odd':'even' ?>">
	<td><?=CHtml::encode($data->id); ?></td>
	<td><?=(isset($data->bnrVisible) and $data->bnrVisible==1)?'Yes':'No'; ?></td>
	<td><?=CHtml::encode($data->bnrTag); ?></td>
	<td>
                <?
                        switch($data->bnrTyp)
                        {
                                case 'img' : { $r='<img width="'.$data->bnrWidth.'" height="'.$data->bnrHeight.'" src="'.Yii::app()->params['bannersWebFolder'].$data->bnrFile.'">'; break; }
                                case 'swf' : { $r='<embed width="'.$data->bnrWidth.'" height="'.$data->bnrHeight.'" type="application/x-shockwave-flash" src="'.Yii::app()->params['bannersWebFolder'].$data->bnrFile.'" pluginspage="http://www.adobe.com/go/getflashplayer" />'; break; }
                                case 'text': {
                                               $r=str_replace("\r",'',$data->bnrDescr);
                                               $r=strtr($r,
                                                          array(
                                                                '{bnrClick}'=>CController::createUrl('/brotate/click',array('id'=>$data->id)),
                                                                '{bnrUrl}'=>$data->bnrUrl
                                                                )
                                                        );
                                               break;
                                             }
                        }
                        echo $r;
                ?>
        </td>
	<td><?=CHtml::encode($data->bnrTyp); ?></td>
	<td><?=CHtml::encode($data->bnrViewedCurrent); ?></td>
	<td><?=CHtml::encode($data->bnrViewedTotal); ?></td>
	<td><?=CHtml::encode($data->bnrClicks); ?></td>
	<td>
                <?=CHtml::link('Edit',array('banners/update','id'=>$data->id));?>
                &nbsp;|&nbsp;
                <?=CHtml::link('Delete',array('banners/delete','id'=>$data->id));?>
        </td>
</tr>