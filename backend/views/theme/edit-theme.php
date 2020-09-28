<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
$defaultSourceId = ArrayHelper::getValue(\Yii::$app->request->getPost(), 'id');
?>
<h2 class="contentTitle">编辑素材</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['theme/edit-theme','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent" layoutH="97">
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
                    <input type="text" name="ThemeModel[barcode]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'barcode','')?>"/>
                    <span class="info"></span>
                </dd>
            </dl>
            <dl>
                <dt>客户：</dt>
                <dd>
                    <select name="ThemeModel[customer_id]" value="<?=ArrayHelper::getValue($model, 'customer_id')?>">
                        <?php foreach($customerList as $brand):?>
                            <option value="<?=$brand['id']?>" <?=ArrayHelper::getValue($model, 'customer_id') == $brand['id'] ? 'selected' : ''?>><?=$brand['name']?></option>
                        <?php endforeach;?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>图案：</dt>
                <dd>
                    <input type="text" readonly="true" name="ThemeModel[template_url]" class='template-url readonly' value="<?=ArrayHelper::getValue($model,'template_url','')?>"/>
                </dd>
            </dl>
            <p>
                <label>&nbsp;</label>
                <input id="template-url" size="60" class="upload-input" data-name="template-url" style="display: none" type="file" data-type="picture" name="UploadForm[file]">
                <img id="upload" class="upload-btn" src='/images/upload.png' width="100px"/>
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
                id        = $(this).attr('id'),
                imgObj    = $(this).parent().find('img[class=upload-btn]'),
                inputText = $('.'+name);

            $.ajaxFileUpload({
                url:'<?=Url::to(['upload/upload-file'])?>',
                secureuri:false,
                fileElementId:id,//file标签的id
                dataType: 'json',//返回数据的类型
                data:{type: type},//一同上传的数据
                success: function (result, status) {
                    //把图片替换
                    if(result.code == 200){
                        var posterUrl = $.trim(result.data.url),
                            fullName  = result.data.fullFileName;
                        // imgObj.attr("src", posterUrl);
                        inputText.val(fullName);
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