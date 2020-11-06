<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\helpers\CommonHelper;

$user = CommonHelper::customer();
$customerId = $customerName = '';
if($user['related'] and !$user['multi']){
    $customerId = $user['customer_id'];
    $customerName = $user['customer_name'];
}
?>
<h2 class="contentTitle">编辑颜色</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['meal/edit-task','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>客户：</dt>
                <dd>
                    <input id="customer-id" type="hidden" name="SyncMealModel[customer_id]" data-name="customer.id" value="<?=$customerId?>">
                    <input id="customer-name" type="text" class="required textInput readonly" readonly="true" name="customer-name" value="<?=$customerName?>" data-name="customer.name" suggestfields="name" lookupgroup="customer" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['customer/customer-list', 'search' => 1])?>" lookupgroup="customer">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>备注：</dt>
                <dd>
                    <input type="text" name="SyncMealModel[desc]" maxlength="20" value=""/>
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