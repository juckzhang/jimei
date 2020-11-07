<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<h2 class="contentTitle">编辑机型</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['phone/edit-phone','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>名称：</dt>
                <dd>
                    <input type="text" name="PhoneModel[modal]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'modal','')?>"/>
                    <span class="info"></span>
                </dd>
            </dl>
            <dl>
                <dt>品牌分类：</dt>
                <dd>
                    <input type="hidden" name="PhoneModel[brand_id]" data-name="brand.id" value="<?=ArrayHelper::getValue($model, 'brand_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="brand.name" value="<?=ArrayHelper::getValue($model,'brand.name')?>" data-name="brand.name" suggestfields="name" lookupgroup="brand" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['phone/brand-list', 'search' => 1])?>" lookupgroup="brand">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>条码：</dt>
                <dd>
                    <input type="text" name="PhoneModel[barcode]" maxlength="20" class="required number" value="<?=ArrayHelper::getValue($model,'barcode','')?>"/>
                    <span class="info"></span>
                </dd>
            </dl>
            <dl>
                <dt>宽：</dt>
                <dd>
                    <input type="text" name="PhoneModel[width]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'width',0)?>"/>
                    <span class="info"></span>
                </dd>
            </dl>
            <dl>
                <dt>高：</dt>
                <dd>
                    <input type="text" name="PhoneModel[height]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'height',0)?>"/>
                    <span class="info"></span>
                </dd>
            </dl>
            <dl>
                <dt>画布类型：</dt>
                <dd>
                    <select name="PhoneModel[canvas_type]" value="<?=ArrayHelper::getValue($model, 'canvas_type')?>">
                        <option value="1">普通画布</option>
                        <option value="2" <?=ArrayHelper::getValue($model, 'canvas_type') == '2' ? 'selected' : ''?>>大画布</option>
                    </select>
                    <span class="info"></span>
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