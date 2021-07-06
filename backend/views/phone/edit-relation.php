<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<h2 class="contentTitle">编辑机型材质关系</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['phone/edit-relation','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <input type="hidden" id="pic_source_pic_name" name="MaterialPhoneModel[source_pic_name]" value="<?=ArrayHelper::getValue($model,'source_pic_name','')?>"/>
        <input type="hidden" id="pic_left_source_pic_name" name="MaterialPhoneModel[left_source_pic_name]" value="<?=ArrayHelper::getValue($model,'left_source_pic_name','')?>"/>
        <input type="hidden" id="pic_right_source_pic_name" name="MaterialPhoneModel[right_source_pic_name]" value="<?=ArrayHelper::getValue($model,'right_source_pic_name','')?>"/>
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>机型：</dt>
                <dd>
                    <input type="hidden" name="MaterialPhoneModel[mobile_id]" data-name="phone.id" value="<?=ArrayHelper::getValue($model, 'mobile_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="phone.name" value="<?=ArrayHelper::getValue($model,'phone.modal')?>" data-name="phone.name" suggestfields="name" lookupgroup="phone" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['phone/phone-list', 'search' => 1, 'select' => 1])?>" lookupgroup="phone">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>材质：</dt>
                <dd>
                    <input type="hidden" name="MaterialPhoneModel[material_id]" data-name="material.id" value="<?=ArrayHelper::getValue($model, 'material_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="material.name" value="<?=ArrayHelper::getValue($model,'material.name')?>" data-name="material.name" suggestfields="name" lookupgroup="material" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['material/material-list', 'search' => 1])?>" lookupgroup="material">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>宽：</dt>
                <dd>
                    <input type="text" name="MaterialPhoneModel[width]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'width',0)?>"/>
                    <span class="info"></span>
                </dd>
            </dl>
            <dl>
                <dt>高：</dt>
                <dd>
                    <input type="text" name="MaterialPhoneModel[height]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'height',0)?>"/>
                    <span class="info"></span>
                </dd>
            </dl>
            <dl>
                <dt>厚：</dt>
                <dd>
                    <input type="text" name="MaterialPhoneModel[fat]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'fat',0)?>"/>
                    <span class="info"></span>
                </dd>
            </dl>
            <dl>
                <dt>弧度：</dt>
                <dd>
                    <input type="text" name="MaterialPhoneModel[side_radian]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'side_radian',0)?>"/>
                    <span class="info"></span>
                </dd>
            </dl>
            <dl>
                <dt>夹具数：</dt>
                <dd>
                    <input type="text" name="MaterialPhoneModel[fixture_num]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'fixture_num',0)?>"/>
                    <span class="info"></span>
                </dd>
            </dl>
            <dl>
                <dt>左边距：</dt>
                <dd>
                    <input type="text" name="MaterialPhoneModel[left]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'left',0)?>"/>
                    <span class="info"></span>
                </dd>
            </dl>
            <dl>
                <dt>上边距：</dt>
                <dd>
                    <input type="text" name="MaterialPhoneModel[top]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'top',0)?>"/>
                    <span class="info"></span>
                </dd>
            </dl>
            <dl>
                <dt>侧边弧度：</dt>
                <dd>
                    <input type="text" name="MaterialPhoneModel[side_radian]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'side_radian',0)?>"/>
                    <span class="info"></span>
                </dd>
            </dl>
            <dl>
                <dt>信息是否完整：</dt>
                <dd>
                    <select name="MaterialPhoneModel[status]" valign="<?=ArrayHelper::getValue($model,'status','0')?>">
                        <option value="2" <?=ArrayHelper::getValue($model,'status') === '2' ? 'selected' : ''?>>否</option>
                        <option value="0" <?=ArrayHelper::getValue($model,'status') === '0' ? 'selected' : ''?>>是</option>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>上框图：</dt>
                <dd>
                    <input type="text" readonly="true" name="MaterialPhoneModel[border_url]" class='source_pic_name readonly' value="<?=ArrayHelper::getValue($model,'border_url','')?>"/>
                    <input id="source_pic_name" size="60" class="upload-input" data-name="source_pic_name" style="display: none" type="file" data-type="picture" name="UploadForm[file]">
                    <a id="upload" class="btnAdd upload-btn" href="javascript:viod();">上传</a>
                </dd>
            </dl>
            <dl>
                <dd>
                    <img height="120" src="<?=Yii::$app->params['picUrlPrefix'] . rtrim(ArrayHelper::getValue($model, 'border_url', ''),'.tif').'.jpg'?>" id="source_pic_name-upload-pic"/>
                </dd>
            </dl>
            <dl>
                <dt>左上框图：</dt>
                <dd>
                    <input type="text" readonly="true" name="MaterialPhoneModel[left_border_url]" class='left_source_pic_name readonly' value="<?=ArrayHelper::getValue($model,'left_border_url','')?>"/>
                    <input id="left_source_pic_name" size="60" class="upload-input" data-name="left_source_pic_name" style="display: none" type="file" data-type="picture" name="UploadForm[file]">
                    <a id="upload" class="btnAdd upload-btn" href="javascript:viod();">上传</a>
                </dd>
            </dl>
            <dl>
                <dd>
                    <img height="200" src="<?=Yii::$app->params['picUrlPrefix'] . rtrim(ArrayHelper::getValue($model, 'left_border_url', ''),'.tif').'.jpg'?>" id="left_source_pic_name-upload-pic"/>
                </dd>
            </dl>
            <dl>
                <dt>右上框图：</dt>
                <dd>
                    <input type="text" readonly="true" name="MaterialPhoneModel[right_border_url]" class='right_source_pic_name readonly' value="<?=ArrayHelper::getValue($model,'right_border_url','')?>"/>
                    <input id="right_source_pic_name" size="60" class="upload-input" data-name="right_source_pic_name" style="display: none" type="file" data-type="picture" name="UploadForm[file]">
                    <a id="upload" class="btnAdd upload-btn" href="javascript:viod();">上传</a>
                </dd>
            </dl>
            <dl>
                <dd>
                    <img height="200" src="<?=Yii::$app->params['picUrlPrefix'] . rtrim(ArrayHelper::getValue($model, 'right_border_url', ''),'.tif').'.jpg'?>" id="right_source_pic_name-upload-pic"/>
                </dd>
            </dl>
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    </form>
</div>

<script src="/js/ajaxfileupload.js"></script>
<script type="text/javascript">
    $(function(){
        $(".upload-btn").on('click', function() {
            $(this).parent().find('input[type=file]').click();
        });

        //上传图片
        //选择文件之后执行上传
        $('.upload-input').on('change',function(){
            var name      = $(this).data('name'),
                type      = $(this).data('type'),
                id        = $(this).attr('id');

            $.ajaxFileUpload({
                url:'<?=Url::to(['upload/upload-file'])?>',
                secureuri:false,
                fileElementId:id,//file标签的id
                dataType: 'json',//返回数据的类型
                data:{type: type},//一同上传的数据
                success: function (result, status) {
                    //把图片替换
                    if(result.code == 200){
                        var posterUrl = $.trim(result.data.url);
                        // imgObj.attr("src", posterUrl);
                        $('#'+id+'-upload-pic').attr("src", posterUrl);
                        $('.'+name).val(result.data.fullFileName);
                        $('#pic_'+id).val(result.data.source_pic_name);
                    }else {
                        alert(result.resultDesc);
                    }
                },
                error: function (data, status, e) {
                    alert(e);
                }
            });
        });
    });
</script>
