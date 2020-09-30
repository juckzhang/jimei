<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<h2 class="contentTitle">编辑机型</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['meal/edit-meal','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent" layoutH="97">
            <dl>
                <dt>品牌分类：</dt>
                <dd>
                    <input type="hidden" name="MealModel[brand_id]" data-name="brand.id" value="<?=ArrayHelper::getValue($model, 'brand_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="brand.name" value="<?=ArrayHelper::getValue($model,'brand.name')?>" data-name="brand.name" suggestfields="name" lookupgroup="brand" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['phone/brand-list', 'search' => 1])?>" lookupgroup="brand">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>机型：</dt>
                <dd>
                    <input type="hidden" name="MealModel[mobile_id]" data-name="phone.id" value="<?=ArrayHelper::getValue($model, 'mobile_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="phone.name" value="<?=ArrayHelper::getValue($model,'phone.modal')?>" data-name="phone.name" suggestfields="name" lookupgroup="phone" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['phone/phone-list', 'search' => 1])?>" lookupgroup="phone">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>材质：</dt>
                <dd>
                    <input type="hidden" name="MealModel[material_id]" data-name="material.id" value="<?=ArrayHelper::getValue($model, 'mmaterial_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="material.name" value="<?=ArrayHelper::getValue($model,'material.name')?>" data-name="material.name" suggestfields="name" lookupgroup="material" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['material/material-list', 'search' => 1])?>" lookupgroup="material">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>颜色：</dt>
                <dd>
                    <input type="hidden" name="MealModel[color_id]" data-name="color.id" value="<?=ArrayHelper::getValue($model, 'color_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="color.name" value="<?=ArrayHelper::getValue($model,'color.name')?>" data-name="color.name" suggestfields="name" lookupgroup="color" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['color/color-list', 'search' => 1])?>" lookupgroup="color">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>客户：</dt>
                <dd>
                    <input type="hidden" name="MealModel[customer_id]" data-name="customer.id" value="<?=ArrayHelper::getValue($model, 'customer_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="customer.name" value="<?=ArrayHelper::getValue($model,'customer.name')?>" data-name="customer.name" suggestfields="name" lookupgroup="customer" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['customer/customer-list', 'search' => 1])?>" lookupgroup="customer">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>素材：</dt>
                <dd>
                    <input type="hidden" name="MealModel[theme_id]" data-name="theme.id" value="<?=ArrayHelper::getValue($model, 'theme_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="theme.name" value="<?=ArrayHelper::getValue($model,'theme.name')?>" data-name="theme.name" suggestfields="name" lookupgroup="theme" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['theme/theme-list', 'search' => 1])?>" lookupgroup="theme">查找带回</a>
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