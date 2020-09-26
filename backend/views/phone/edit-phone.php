<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<h2 class="contentTitle">编辑机型</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['phone/edit-phone','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent" layoutH="97">
            <dl>
                <dt>名称：</dt>
                <dd>
                    <input type="text" name="PhoneModel[modal]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'modal','')?>"/>
                    <span class="info">名称不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>品牌分类：</dt>
                <dd>
                    <select name="PhoneModel[brand_id]" value="<?=ArrayHelper::getValue($model, 'brand_id')?>">
                        <?php foreach($brandList as $brand):?>
                            <option value="<?=$brand['id']?>" <?=ArrayHelper::getValue($model, 'brand_id') == $brand['id'] ? 'selected' : ''?>><?=$brand['name']?></option>
                        <?php endforeach;?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>条码：</dt>
                <dd>
                    <input type="text" name="PhoneModel[barcode]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'barcode','')?>"/>
                    <span class="info">条码</span>
                </dd>
            </dl>
            <dl>
                <dt>宽：</dt>
                <dd>
                    <input type="text" name="PhoneModel[width]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'width',0)?>"/>
                    <span class="info">宽不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>高：</dt>
                <dd>
                    <input type="text" name="PhoneModel[height]" maxlength="20" class="required" value="<?=ArrayHelper::getValue($model,'height',0)?>"/>
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