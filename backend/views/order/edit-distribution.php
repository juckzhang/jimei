<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
$addType = ['1' => '全渠道','2'=>'手动'];
?>
<h2 class="contentTitle">编辑配货单</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['order/edit-distribution','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>单号：</dt>
                <dd>
                    <input type="text" name="DistributionModel[sn]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'sn','')?>"/>
                    <span class="info">单号不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>数量：</dt>
                <dd>
                    <input type="text" name="DistributionModel[num]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'num',0)?>"/>
                    <span class="info">数量</span>
                </dd>
            </dl>
            <dl>
                <dt>添加方式：</dt>
                <dd>
                    <select name="DistributionModel[add_type]">
                        <?php foreach ($addType as $key => $item):?>
                            <option value="<?=$key?>" <?=ArrayHelper::getValue($model,'add_type')==$key ? 'selected' : ''?>><?=$item?></option>
                        <?php endforeach;?>
                    </select>
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