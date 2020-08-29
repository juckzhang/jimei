<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
$defaultSourceId = ArrayHelper::getValue(\Yii::$app->request->getPost(), 'id');
?>
<h2 class="contentTitle">材质</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['media/edit-material','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>名称：</dt>
                <dd>
                    <input type="text" name="MaterialModel[name]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'name','')?>"/>
                    <span class="info">名称不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>条码：</dt>
                <dd>
                    <input type="text" name="MaterialModel[barcode]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'barcode','')?>"/>
                    <span class="info">条码</span>
                </dd>
            </dl>
            <dl>
                <dt>左右边距：</dt>
                <dd>
                    <input type="text" name="MaterialModel[left]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'left',0)?>"/>
                    <span class="info">左右不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>上下边距：</dt>
                <dd>
                    <input type="text" name="MaterialModel[top]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'left',0)?>"/>
                    <span class="info">上下不能为空</span>
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