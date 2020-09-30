<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
?>
<h2 class="contentTitle">编辑订单</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['order/edit-order','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>配货分组号：</dt>
                <dd>
                    <input type="hidden" name="orderModel[sn]" data-name="sn.name" value="<?=ArrayHelper::getValue($model, 'sn')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="sn.name" value="<?=ArrayHelper::getValue($model,'sn')?>" data-name="sn.name" suggestfields="name" lookupgroup="sn" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['order/distribution-list', 'search' => 1])?>" lookupgroup="sn">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>订单号：</dt>
                <dd>
                    <input type="text" name="orderModel[order_id]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'order_id','')?>"/>
                    <span class="info">订单号不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>机型分类：</dt>
                <dd>
                    <input type="hidden" name="orderModel[mobile_id]" data-name="phone.id" value="<?=ArrayHelper::getValue($model, 'mobile_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="phone.name" value="<?=ArrayHelper::getValue($model,'phone.modal')?>" data-name="phone.name" suggestfields="name" lookupgroup="phone" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['phone/phone-list', 'search' => 1])?>" lookupgroup="phone">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>材质分类：</dt>
                <dd>
                    <input type="hidden" name="orderModel[material_id]" data-name="material.id" value="<?=ArrayHelper::getValue($model, 'mmaterial_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="material.name" value="<?=ArrayHelper::getValue($model,'material.name')?>" data-name="material.name" suggestfields="name" lookupgroup="material" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['material/material-list', 'search' => 1])?>" lookupgroup="material">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>客户：</dt>
                <dd>
                    <input type="hidden" name="orderModel[customer_id]" data-name="customer.id" value="<?=ArrayHelper::getValue($model, 'customer_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="customer.name" value="<?=ArrayHelper::getValue($model,'customer.name')?>" data-name="customer.name" suggestfields="name" lookupgroup="customer" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['customer/customer-list', 'search' => 1])?>" lookupgroup="customer">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>素材分类：</dt>
                <dd>
                    <input type="hidden" name="orderModel[theme_id]" data-name="theme.id" value="<?=ArrayHelper::getValue($model, 'theme_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="theme.name" value="<?=ArrayHelper::getValue($model,'theme.name')?>" data-name="theme.name" suggestfields="name" lookupgroup="theme" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['theme/theme-list', 'search' => 1])?>" lookupgroup="theme">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>颜色：</dt>
                <dd>
                    <input type="hidden" name="orderModel[color_id]" data-name="color.id" value="<?=ArrayHelper::getValue($model, 'color_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="color.name" value="<?=ArrayHelper::getValue($model,'color.name')?>" data-name="color.name" suggestfields="name" lookupgroup="color" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['color/color-list', 'search' => 1])?>" lookupgroup="color">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>条码：</dt>
                <dd>
                    <input type="text" name="orderModel[barcode]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'barcode','')?>"/>
                    <span class="info">条码</span>
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