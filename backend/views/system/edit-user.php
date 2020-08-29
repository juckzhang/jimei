<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\SystemService;
$parentId = ArrayHelper::getValue($model,'parent_id');
$id       = ArrayHelper::getValue($model,'id');
$roleId   = ArrayHelper::getValue($model,'role_id');
?>
<h2 class="contentTitle">文章编辑</h2>
<div class="pageContent">

    <form method="post" action="<?=Url::to(['system/edit-user','id' => $id])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>用户名：</dt>
                <dd>
                    <input type="text" name="AdminModel[username]"  class="required" value="<?=ArrayHelper::getValue($model,'username','')?>"/>
                    <span>用户名不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>用户密码：</dt>
                <dd>
                    <input type="text" name="AdminModel[password]"  class="required" value=""/>
                    <span>请求地址不能为空</span>
                </dd>
            </dl>
            <dl>
                <dt>所属角色：</dt>
                <dd>
                    <select name="AdminModel[role_id]" value="<?=$parentId?>">
                        <option value="0" selected>超级用户</option>
                        <?php foreach($roles as $role):?>
                            <option value="<?=$role['id']?>" <?=$roleId == $role['id'] ? 'selected="selected"' : ''?>><?=$role['name']?></option>
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

