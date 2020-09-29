<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<h2 class="contentTitle">编辑机型</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['phone/edit-phone','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent" layoutH="97">
            <dl>
                <dt>品牌分类：</dt>
                <dd>
                    <select name="MealModel[brand_id]" value="<?=ArrayHelper::getValue($model, 'brand_id')?>">
                        <?php foreach($brandList as $brand):?>
                            <option value="<?=$brand['id']?>" <?=ArrayHelper::getValue($model, 'brand_id') == $brand['id'] ? 'selected' : ''?>><?=$brand['name']?></option>
                        <?php endforeach;?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>机型：</dt>
                <dd>
                    <select name="MealModel[mobile_id]" value="<?=ArrayHelper::getValue($model, 'mobile_id')?>">
                        <?php foreach($phoneList as $brand):?>
                            <option value="<?=$brand['id']?>" <?=ArrayHelper::getValue($model, 'mobile_id') == $brand['id'] ? 'selected' : ''?>><?=$brand['modal']?></option>
                        <?php endforeach;?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>材质：</dt>
                <dd>
                    <select name="MealModel[material_id]" value="<?=ArrayHelper::getValue($model, 'material_id')?>">
                        <?php foreach($materialList as $brand):?>
                            <option value="<?=$brand['id']?>" <?=ArrayHelper::getValue($model, 'material_id') == $brand['id'] ? 'selected' : ''?>><?=$brand['name']?></option>
                        <?php endforeach;?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>颜色：</dt>
                <dd>
                    <select name="MealModel[color_id]" value="<?=ArrayHelper::getValue($model, 'color_id')?>">
                        <?php foreach($colorList as $brand):?>
                            <option value="<?=$brand['id']?>" <?=ArrayHelper::getValue($model, 'color_id') == $brand['id'] ? 'selected' : ''?>><?=$brand['name']?></option>
                        <?php endforeach;?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>客户：</dt>
                <dd>
                    <select name="MealModel[customer_id]" value="<?=ArrayHelper::getValue($model, 'customer_id')?>">
                        <?php foreach($colorList as $brand):?>
                            <option value="<?=$brand['id']?>" <?=ArrayHelper::getValue($model, 'customer_id') == $brand['id'] ? 'selected' : ''?>><?=$brand['name']?></option>
                        <?php endforeach;?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>素材：</dt>
                <dd>
                    <select name="MealModel[theme_id]" value="<?=ArrayHelper::getValue($model, 'theme_id')?>">
                        <?php foreach($colorList as $brand):?>
                            <option value="<?=$brand['id']?>" <?=ArrayHelper::getValue($model, 'theme_id') == $brand['id'] ? 'selected' : ''?>><?=$brand['name']?></option>
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