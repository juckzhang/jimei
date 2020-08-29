<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<h2 class="contentTitle">编辑机型</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['phone/edit-relation','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>机型：</dt>
                <dd>
                    <select name="MaterialPhoneModel[mobile_id]" value="<?=ArrayHelper::getValue($model, 'mobile_id')?>">
                        <?php foreach($phoneList as $brand):?>
                            <option value="<?=$brand['id']?>" <?=ArrayHelper::getValue($model, 'mobile_id') == $brand['id'] ? 'selected' : ''?>><?=$brand['modal']?></option>
                        <?php endforeach;?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>材质：</dt>
                <dd>
                    <select name="MaterialPhoneModel[material_id]" value="<?=ArrayHelper::getValue($model, 'material_id')?>">
                        <?php foreach($materialList as $brand):?>
                            <option value="<?=$brand['id']?>" <?=ArrayHelper::getValue($model, 'material_id') == $brand['id'] ? 'selected' : ''?>><?=$brand['name']?></option>
                        <?php endforeach;?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>左右边距：</dt>
                <dd>
                    <input type="text" name="MaterialPhoneModel[left]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'left',0)?>"/>
                    <span class="info">宽不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>上下边距：</dt>
                <dd>
                    <input type="text" name="MaterialPhoneModel[top]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'top',0)?>"/>
                    <span class="info">高不能为空</span>
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