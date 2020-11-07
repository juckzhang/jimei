<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$params = \Yii::$app->request->getPost();
$customer_id = ArrayHelper::getValue($params, 'customer_id');
$customer_name = ArrayHelper::getValue($params, 'customer_name');
?>
<h2 class="contentTitle">编辑素材</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['theme/edit-theme','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <input type="hidden" id="source_pic_name" name="ThemeModel[source_pic_name]" value="<?=ArrayHelper::getValue($model,'source_pic_name','')?>"/>
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>名称：</dt>
                <dd>
                    <input type="text" name="ThemeModel[name]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'name','')?>"/>
                    <span class="info"></span>
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
                    <input type="hidden" name="ThemeModel[customer_id]" data-name="customer.id" value="<?=ArrayHelper::getValue($model, 'customer_id',$customer_id)?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="customer.name" value="<?=ArrayHelper::getValue($model,'customer.name', $customer_name)?>" data-name="customer.name" suggestfields="name" lookupgroup="customer" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['customer/customer-list', 'search' => 1])?>" lookupgroup="customer">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>图案：</dt>
                <dd>
                    <input type="text" readonly="true" name="ThemeModel[template_url]" class='template-url readonly' value="<?=ArrayHelper::getValue($model,'template_url','')?>"/>
                    <input id="template-url" size="60" class="upload-input" data-name="template-url" style="display: none" type="file" data-type="picture" name="UploadForm[file]">
                    <a id="upload" class="btnAdd upload-btn" href="javascript:viod();">上传</a>
                </dd>
            </dl>
            <p>
                <img width="80" src="<?=Yii::$app->params['picUrlPrefix'].rtrim(ArrayHelper::getValue($model, 'template_url',''),'.tif').'.jpg'?>" id="upload-pic"/>
            </p>
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
                        $('#upload-pic').attr("src", posterUrl);
                        $('.'+name).val(result.data.fullFileName);
                        $('#source_pic_name').val(result.data.source_pic_name);
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