<?php
use yii\helpers\Url;
?>
<h2 class="contentTitle">关联材质</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['theme/relation-material'])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>客户：</dt>
                <dd>
                    <input type="hidden" name="customer_id" data-name="customer.id" value="">
                    <input type="text" class="textInput readonly" readonly="true" name="customer.name" value="" data-name="customer.name" suggestfields="name" lookupgroup="customer" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['customer/customer-list', 'search' => 1, 'more' => 1])?>" lookupgroup="customer">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>图案：</dt>
                <dd>
                    <input type="hidden" name="theme_id" data-name="theme.id" value="">
                    <input type="text" class="textInput readonly" readonly="true" name="theme.name" value="" data-name="theme.name" suggestfields="name,customer_id" lookupgroup="theme" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['theme/theme-list', 'search' => 1, 'more' => 1])?>" lookupgroup="theme">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>材质：</dt>
                <dd>
                    <input type="hidden" name="material_id" data-name="material.id" value="">
                    <input type="text" class="required textInput readonly" readonly="true" name="material.name" value="" data-name="material.name" suggestfields="name" lookupgroup="material" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['material/material-list', 'search' => 1, 'more' => 1])?>" lookupgroup="material">查找带回</a>
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