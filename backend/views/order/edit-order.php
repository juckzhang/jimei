<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
?>
<h2 class="contentTitle">编辑订单</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['order/edit-order','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <input type="hidden" name="OrderModel[status]" value="0">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>订单号：</dt>
                <dd>
                    <input type="text" name="OrderModel[order_id]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'order_id','')?>"/>
                    <span class="info">订单号不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>品牌分类：</dt>
                <dd>
                    <input id="-brand-id" type="hidden" name="OrderModel[brand_id]" data-name="brand.id" value="<?=ArrayHelper::getValue($model, 'brand_id')?>">
                    <input id="-brand-name" type="text" class="required textInput readonly" readonly="true" name="brand.name" value="<?=ArrayHelper::getValue($model,'brand.name')?>" data-name="brand.name" suggestfields="name" lookupgroup="brand" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['phone/brand-list', 'search' => 1])?>" lookupgroup="brand">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>机型分类：</dt>
                <dd>
                    <input type="hidden" name="OrderModel[mobile_id]" data-name="phone.id" value="<?=ArrayHelper::getValue($model, 'mobile_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="phone.name" value="<?=ArrayHelper::getValue($model,'phone.modal')?>" data-name="phone.name" suggestfields="name" lookupgroup="phone" autocomplete="off">
                    <a id="-look-mobile" class="btnLook" href="<?=Url::to(['phone/phone-list', 'search' => 1, 'notMore' => 1])?>" lookupgroup="phone">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>材质分类：</dt>
                <dd>
                    <input type="hidden" name="OrderModel[material_id]" data-name="material.id" value="<?=ArrayHelper::getValue($model, 'material_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="material.name" value="<?=ArrayHelper::getValue($model,'material.name')?>" data-name="material.name" suggestfields="name" lookupgroup="material" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['material/material-list', 'search' => 1])?>" lookupgroup="material">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>颜色：</dt>
                <dd>
                    <input type="hidden" name="OrderModel[color_id]" data-name="color.id" value="<?=ArrayHelper::getValue($model, 'color_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="color.name" value="<?=ArrayHelper::getValue($model,'color.name')?>" data-name="color.name" suggestfields="name" lookupgroup="color" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['color/color-list', 'search' => 1])?>" lookupgroup="color">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>客户：</dt>
                <dd>
                    <input id="-customer-id" type="hidden" name="OrderModel[customer_id]" data-name="customer.id" value="<?=ArrayHelper::getValue($model, 'customer_id')?>">
                    <input id="-customer-name" type="text" class="required textInput readonly" readonly="true" name="customer-name" value="<?=ArrayHelper::getValue($model,'customer.name')?>" data-name="customer.name" suggestfields="name" lookupgroup="customer" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['customer/customer-list', 'search' => 1])?>" lookupgroup="customer">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>图案：</dt>
                <dd>
                    <input type="hidden" name="OrderModel[theme_id]" data-name="theme.id" value="<?=ArrayHelper::getValue($model, 'theme_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="theme.name" value="<?=ArrayHelper::getValue($model,'theme.name')?>" data-name="theme.name" suggestfields="name" lookupgroup="theme" autocomplete="off">
                    <a id="-look-theme" class="btnLook" href="<?=Url::to(['theme/theme-list', 'search' => 1])?>" lookupgroup="theme">查找带回</a>
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
<script type="text/javascript">
    $(function(){
        $('#-look-mobile').on('click',function(){
            var brand_name = $('#-brand-name').val(),
                brand_id = $('#-brand-id').val(),
                _href = '<?=Url::to(['phone/phone-list', 'search' => 1, 'notMore' => 1])?>'+'&other[brand_id]='+brand_id+'&brand-name='+brand_name;
            $(this).attr('href', _href);
        });

        $('#-look-theme').on('click',function(){
            var customer_name = $('#-customer-name').val(),
                customer_id = $('#-customer-id').val(),
                _href = '<?=Url::to(['theme/theme-list', 'search' => 1, 'notMore' => 1])?>'+'&other[customer_id]='+customer_id+'&customer-name='+customer_name;
            $(this).attr('href', _href);
        });
    });
</script>