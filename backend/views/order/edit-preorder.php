<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
?>
<h2 class="contentTitle">编辑订单</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['order/edit-preorder','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <input type="hidden" name="OrderModel[status]" value="0">
        <input type="hidden" name="OrderModel[finance_status]" value="0">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>订单号：</dt>
                <dd>
                    <input type="text" name="OrderModel[order_id]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'billcode','')?>"/>
                    <span class="info">订单号不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>明细ID：</dt>
                <dd>
                    <input type="text" name="OrderModel[did]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'did','')?>"/>
                    <span class="info">订单号不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>客户：</dt>
                <dd>
                    <input id="precustomer-id" type="hidden" name="OrderModel[customer_id]" data-name="customer.id" value="<?=ArrayHelper::getValue($model, 'customer_id')?>">
                    <input id="precustomer-name" type="text" class="required textInput readonly" readonly="true" name="customer-name" value="<?=ArrayHelper::getValue($model,'customer.name')?>" data-name="customer.name" suggestfields="name" lookupgroup="customer" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['customer/customer-list', 'search' => 1])?>" lookupgroup="customer">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>图案：</dt>
                <dd>
                    <input type="hidden" name="OrderModel[theme_id]" data-name="theme.id" value="<?=ArrayHelper::getValue($model, 'theme_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="theme.name" value="<?=ArrayHelper::getValue($model,'theme.name')?>" data-name="theme.name" suggestfields="name" lookupgroup="theme" autocomplete="off">
                    <a id="prelook-theme" class="btnLook" href="<?=Url::to(['theme/theme-list', 'search' => 1])?>" lookupgroup="theme">查找带回</a>
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
        $('#prelook-theme').on('click',function(){
            var customer_name = $('#precustomer-name').val(),
                customer_id = $('#precustomer-id').val(),
                _href = '<?=Url::to(['theme/theme-list', 'search' => 1, 'notMore' => 1])?>'+'&other[customer_id]='+customer_id+'&customer-name='+customer_name;
            $(this).attr('href', _href);
        });
    });
</script>