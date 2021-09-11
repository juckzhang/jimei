<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<h2 class="contentTitle">编辑客户</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['customer/edit-customer','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>名称：</dt>
                <dd>
                    <input type="text" name="CustomerModel[name]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'name','')?>"/>
                    <span class="info">名称不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>条码：</dt>
                <dd>
                    <input type="text" name="CustomerModel[barcode]" maxlength="2" minlength="2" class="required lettersonly" value="<?=ArrayHelper::getValue($model,'barcode','')?>"/>
                    <span class="info">条码</span>
                </dd>
            </dl>
            <dl>
                <dt>账户余额</dt>
                <dd>
                    <input type="text" name="CustomerModel[balance]" class="required" value="<?=ArrayHelper::getValue($model,'balance','0.00')?>"/>
                    <span class="info">账户余额</span>
                </dd>
            </dl>
            <dl>
                <dt>顺丰</dt>
                <dd>
                    <input type="text" name="CustomerModel[sf_diff]" class="required" value="<?=ArrayHelper::getValue($model,'sf_diff','0.00')?>"/>
                    <span class="info">顺丰</span>
                </dd>
            </dl>
            <dl>
                <dt>圆通</dt>
                <dd>
                    <input type="text" name="CustomerModel[yt_diff]" class="required" value="<?=ArrayHelper::getValue($model,'yt_diff','0.00')?>"/>
                    <span class="info">圆通</span>
                </dd>
            </dl>
            <dl>
                <dt>中通</dt>
                <dd>
                    <input type="text" name="CustomerModel[zt_diff]" class="required" value="<?=ArrayHelper::getValue($model,'zt_diff','0.00')?>"/>
                    <span class="info">中通</span>
                </dd>
            </dl>
            <dl>
                <dt>汇通</dt>
                <dd>
                    <input type="text" name="CustomerModel[ht_diff]" class="required" value="<?=ArrayHelper::getValue($model,'ht_diff','0.00')?>"/>
                    <span class="info">汇通</span>
                </dd>
            </dl>
            <dl>
                <dt>申通</dt>
                <dd>
                    <input type="text" name="CustomerModel[st_diff]" class="required" value="<?=ArrayHelper::getValue($model,'st_diff','0.00')?>"/>
                    <span class="info">申通</span>
                </dd>
            </dl>
            <dl>
                <dt>韵达</dt>
                <dd>
                    <input type="text" name="CustomerModel[yd_diff]" class="required" value="<?=ArrayHelper::getValue($model,'yd_diff','0.00')?>"/>
                    <span class="info">韵达</span>
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