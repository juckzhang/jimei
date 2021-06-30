<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$params = \Yii::$app->request->getPost();
$customer_id = ArrayHelper::getValue($params, 'customer_id');
$customer_name = ArrayHelper::getValue($params, 'customer_name');
?>
<h2 class="contentTitle">编辑素材</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['theme/edit-theme','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <input type="hidden" id="main_source_pic_name" name="ThemeModel[source_pic_name]" value="<?=ArrayHelper::getValue($model,'source_pic_name','')?>"/>
        <input type="hidden" id="left_source_pic_name" name="ThemeModel[left_source_pic_name]" value="<?=ArrayHelper::getValue($model,'left_source_pic_name','')?>"/>
        <input type="hidden" id="right_source_pic_name" name="ThemeModel[right_source_pic_name]" value="<?=ArrayHelper::getValue($model,'right_source_pic_name','')?>"/>
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>名称：</dt>
                <dd>
                    <input type="text" id="theme-name" name="ThemeModel[name]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'name','')?>"/>
                    <span class="info"></span>
                </dd>
            </dl>
            <dl>
                <dt>素材类型：</dt>
                <dd>
                    <select name="ThemeModel[type]" valign="<?=ArrayHelper::getValue($model,'type', '0')?>">
                        <option value="0" <?=ArrayHelper::getValue($model,'type') === '0' ? 'selected' : ''?>>无侧边</option>
                        <option value="1" <?=ArrayHelper::getValue($model,'type') === '1' ? 'selected' : ''?>>左侧边</option>
                        <option value="2" <?=ArrayHelper::getValue($model,'type') === '2' ? 'selected' : ''?>>右侧边</option>
                        <option value="3" <?=ArrayHelper::getValue($model,'type') === '3' ? 'selected' : ''?>>双侧边</option>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>条码：</dt>
                <dd>
                    <input type="text" name="ThemeModel[barcode]" minlength="4" class="required number" value="<?=ArrayHelper::getValue($model,'barcode',$barcode)?>"/>
                    <span class="info"></span>
                </dd>
            </dl>
            <dl>
                <dt>颜色：</dt>
                <dd>
                    <input type="text" class="textInput readonly" readonly="true" name="ThemeModel[color]" value="<?=ArrayHelper::getValue($model,'color')?>" data-name="color.name" suggestfields="name" lookupgroup="color" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['color/color-list', 'search' => 1, 'more' => 1])?>" lookupgroup="color">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>客户：</dt>
                <dd>
                    <input type="hidden" id="theme-customer" name="ThemeModel[customer_id]" data-name="customer.id" value="<?=ArrayHelper::getValue($model, 'customer_id',$customer_id)?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="customer.name" value="<?=ArrayHelper::getValue($model,'customer.name', $customer_name)?>" data-name="customer.name" suggestfields="name" lookupgroup="customer" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['customer/customer-list', 'search' => 1])?>" lookupgroup="customer">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>图案：</dt>
                <dd>
                    <input type="text" readonly="true" name="ThemeModel[template_url]" class='main-template-url readonly' value="<?=ArrayHelper::getValue($model,'template_url','')?>"/>
                    <input id="main" size="60" class="upload-input" data-name="main-template-url" style="display: none" type="file" data-type="picture" name="UploadForm[file]">
                    <a id="upload" class="btnAdd upload-btn" href="javascript:viod();">上传</a>
                </dd>
            </dl>
            <dl>
            </dl>
                <dd>
                    <img width="80" src="<?=Yii::$app->params['picUrlPrefix'].rtrim(ArrayHelper::getValue($model, 'template_url',''),'.tif').'.jpg'?>" id="main-upload-pic"/>
                </dd>
            </dl>
            <dl>
                <dt>左侧图案：</dt>
                <dd>
                    <input type="text" readonly="true" name="ThemeModel[left_template_url]" class='left-template-url readonly' value="<?=ArrayHelper::getValue($model,'left_template_url','')?>"/>
                    <input id="left" size="60" class="upload-input" data-name="left-template-url" style="display: none" type="file" data-type="picture" name="UploadForm[file]">
                    <a id="upload" class="btnAdd upload-btn" href="javascript:viod();">上传</a>
                </dd>
            </dl>
            <dl>
                <dd>
                    <img height="200" src="<?=Yii::$app->params['picUrlPrefix'].rtrim(ArrayHelper::getValue($model, 'left_template_url',''),'.tif').'.jpg'?>" id="left-upload-pic"/>
                </dd>
            </dl>
            <dl>
                <dt>右侧图案：</dt>
                <dd>
                    <input type="text" readonly="true" name="ThemeModel[right_template_url]" class='right-template-url readonly' value="<?=ArrayHelper::getValue($model,'right_template_url','')?>"/>
                    <input id="right" size="60" class="upload-input" data-name="right-template-url" style="display: none" type="file" data-type="picture" name="UploadForm[file]">
                    <a id="upload" class="btnAdd upload-btn" href="javascript:viod();">上传</a>
                </dd>
            </dl>
            <dl>
                <dd>
                    <img height="200" src="<?=Yii::$app->params['picUrlPrefix'].rtrim(ArrayHelper::getValue($model, 'right_template_url',''),'.tif').'.jpg'?>" id="right-upload-pic"/>
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
                        $('#'+id+'-upload-pic').attr("src", posterUrl);
                        $('.'+name).val(result.data.fullFileName);
                        $('#'+id+'_source_pic_name').val(result.data.source_pic_name);
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