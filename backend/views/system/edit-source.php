<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\SystemService;
$parentId = ArrayHelper::getValue($model,'parent_id');
$id       = ArrayHelper::getValue($model,'id');
$sources = SystemService::getService()->sources();
?>
<h2 class="contentTitle">文章编辑</h2>
<div class="pageContent">

    <form method="post" action="<?=Url::to(['system/edit-source','id' => $id])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>资源名称：</dt>
                <dd>
                    <input type="text" name="SourceModel[name]"  class="required" value="<?=ArrayHelper::getValue($model,'name','')?>"/>
                    <span>资源名称不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>请求地址：</dt>
                <dd>
                    <input type="text" name="SourceModel[request]"  class="required" value="<?=ArrayHelper::getValue($model,'request','')?>"/>
                    <span>请求地址不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>所属资源：</dt>
                <dd>
                    <select name="SourceModel[parent_id]" value="<?=$parentId?>">
                        <option value="0" selected>一级资源</option>
                        <?php foreach($sources as $source):?>
                            <?php if($id != $source['id']):?>
                                <option value="<?=$source['id']?>" <?=$parentId == $source['id'] ? 'selected="selected"' : ''?>><?=$source['name']?></option>
                            <?php endif;?>
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

