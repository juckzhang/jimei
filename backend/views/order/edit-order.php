<?php
use yii\helpers\ArrayHelper;
?>
<h2 class="contentTitle">编辑订单</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['order/edit-order','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
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
                    <select name="orderModel[mobile_id]" value="<?=ArrayHelper::getValue($model, 'mobile_id')?>">
                        <?php foreach($phone as $item):?>
                            <option value="<?=$item['id']?>" <?=ArrayHelper::getValue($model, 'mobile_id') == $item['id'] ? 'selected' : ''?>><?=$item['modal']?></option>
                        <?php endforeach;?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>材质分类：</dt>
                <dd>
                    <select name="orderModel[material_id]" value="<?=ArrayHelper::getValue($model, 'material_id')?>">
                        <?php foreach($material as $item):?>
                            <option value="<?=$item['id']?>" <?=ArrayHelper::getValue($model, 'material_id') == $item['id'] ? 'selected' : ''?>><?=$item['name']?></option>
                        <?php endforeach;?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>素材分类：</dt>
                <dd>
                    <select name="orderModel[theme_id]" value="<?=ArrayHelper::getValue($model, 'theme_id')?>">
                        <?php foreach($theme as $item):?>
                            <option value="<?=$item['id']?>" <?=ArrayHelper::getValue($model, 'theme_id') == $item['id'] ? 'selected' : ''?>><?=$item['name']?></option>
                        <?php endforeach;?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>素材分类：</dt>
                <dd>
                    <select name="orderModel[color_id]" value="<?=ArrayHelper::getValue($model, 'color_id')?>">
                        <?php foreach($color as $item):?>
                            <option value="<?=$item['id']?>" <?=ArrayHelper::getValue($model, 'color_id') == $item['id'] ? 'selected' : ''?>><?=$item['name']?></option>
                        <?php endforeach;?>
                    </select>
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