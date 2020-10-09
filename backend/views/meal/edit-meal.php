<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$more = 1;
if (ArrayHelper::getValue($model, 'id')) $more = '';
?>
<h2 class="contentTitle">编辑机型</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['meal/edit-meal','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>机型：</dt>
                <dd>
                    <input type="hidden" name="MealModel[brand_id]" data-name="phone.brand_id" value="<?=ArrayHelper::getValue($model, 'brand_id')?>">
                    <input type="hidden" name="MealModel[mobile_id]" data-name="phone.id" value="<?=ArrayHelper::getValue($model, 'mobile_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="phone.name" value="<?=ArrayHelper::getValue($model,'phone.modal')?>" data-name="phone.name" suggestfields="name,brand_id" lookupgroup="phone" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['phone/phone-list', 'search' => 1, 'more' => $more])?>" lookupgroup="phone">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>颜色：</dt>
                <dd>
                    <input type="hidden" name="MealModel[color_id]" data-name="color.id" value="<?=ArrayHelper::getValue($model, 'color_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="color.name" value="<?=ArrayHelper::getValue($model,'color.name')?>" data-name="color.name" suggestfields="name" lookupgroup="color" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['color/color-list', 'search' => 1, 'more' => $more])?>" lookupgroup="color">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>图案：</dt>
                <dd>
                    <input type="hidden" name="MealModel[customer_id]" data-name="theme.customer_id" value="<?=ArrayHelper::getValue($model, 'customer_id')?>">
                    <input type="hidden" name="MealModel[material_id]" data-name="theme.material_id" value="<?=ArrayHelper::getValue($model, 'material_id')?>">
                    <input type="hidden" name="MealModel[theme_id]" data-name="theme.id" value="<?=ArrayHelper::getValue($model, 'theme_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="theme.name" value="<?=ArrayHelper::getValue($model,'theme.name')?>" data-name="theme.name" suggestfields="name,customer_id,material_id" lookupgroup="theme" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['theme/theme-list', 'search' => 1, 'more' => $more])?>" lookupgroup="theme">查找带回</a>
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