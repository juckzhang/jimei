<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\SystemService;
$id       = ArrayHelper::getValue($model,'id');
$sources = SystemService::getService()->sourceAll();
?>
<h2 class="contentTitle">角色编辑</h2>
<div class="pageContent">

    <form method="post" action="<?=Url::to(['system/edit-role','id' => $id])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,submitCallBack)">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>角色名称：</dt>
                <dd>
                    <input type="text" name="RoleModel[name]"  class="required" value="<?=ArrayHelper::getValue($model,'name','')?>"/>
                    <span>资源名称不能为空</span>
                </dd>
            </dl>
            <div class="divider"></div>
            <h3>权限分配:</h3>
            <?php foreach($sources as $source):?>
                <br/><br/>
                <label>
                    <input name="sources[]" value="<?=$source['id']?>" <?=in_array($source['id'],$selected) ? 'checked="checked"' : ''?> type="checkbox"><span style="font-weight:bold;margin-right: 20px;">
                        <strong><?=$source['name']?>：</strong></span>
                </label>
                <?php foreach($source['child'] as $child):?>
                    <div style="float: left;">
                        <label><input name="sources[]" value="<?=$child['id']?>"  <?=in_array($source['id'],$selected) ? 'checked="checked"' : ''?> type="checkbox"><?=$child['name']?></label>
                    </div>
                <?php endforeach;?>
            <?php endforeach;?>
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    </form>
</div>
<script>
    function submitCallBack(json)
    {
        DWZ.ajaxDone(json);
        //判断修改的角色是否是当前用户
        <?php if($id == \Yii::$app->user->identity->role_id):?>
        location.href = '<?=Url::to(['site/logout'])?>';
        <?php endif;?>
        dialogAjaxDone(json);
    }
</script>

