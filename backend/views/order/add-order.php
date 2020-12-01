<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$params = \Yii::$app->request->getPost();
?>
<h2 class="contentTitle">添加订单</h2>
<div class="pageContent nowrap">
    <form id="add-order-form" method="post" action="<?=Url::to(['order/add-order'])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,submitCallBack)">
        <input type="hidden" name="base_id" value="<?=ArrayHelper::getValue($params,'base_id')?>"/>
        <div class="pageFormContent" layoutH="97">
            <dl>
                <dt>物流/网店单号：</dt>
                <dd>
                    <input type="text" name="keyWord" maxlength="50" class="required" value=""/>
                    <span class="info">单号不能为空</span>
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
<script>
    function submitCallBack(json)
    {
        // DWZ.ajaxDone(json);
        dialogAjaxDone(json);
        if(json[DWZ.keys.statusCode] == DWZ.statusCode.ok){
            $('input[name=keyWord]').val('');
            //关闭弹框
            alertMsg.close();
            $("input[name=keyWord]").focus();
        }
    }

    $(function(){
        $('input[name=keyWord]').on('input propertychange',function(){
            var val = $(this).val(),
                len = val.length;
            if((val.indexOf("EO-") == 0 && len == 17) || (val.indexOf("YT") == 0 && len == 15)){
                $('#add-order-form').submit();
            }
        });
    });
</script>