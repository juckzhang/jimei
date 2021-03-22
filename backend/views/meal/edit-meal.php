<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\helpers\CommonHelper;

$more = 1;
if (ArrayHelper::getValue($model, 'id')){
    $more = '';
}
$user = CommonHelper::customer();
$customerId = $customerName = '';
if($user['related'] and !$user['multi']){
    $customerId = $user['customer_id'];
    $customerName = $user['customer_name'];
}
?>
<h2 class="contentTitle">编辑套餐</h2>
<div class="pageContent">
    <form method="post" action="<?=Url::to(['meal/edit-meal','id' => ArrayHelper::getValue($model,'id','')])?>" class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <input type="hidden" name="MealModel[sync_status]" value="0">
        <div class="pageFormContent nowrap" layoutH="97">
            <dl>
                <dt>材质：</dt>
                <dd>
                    <input id="material-id" type="hidden" name="MealModel[material_id]" data-name="material.id" value="<?=ArrayHelper::getValue($model, 'material_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="material.name" value="<?=ArrayHelper::getValue($model,'material.name')?>" data-name="material.name" suggestfields="name" lookupgroup="material" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['material/material-list', 'search' => 1, 'more' => $more])?>" lookupgroup="material">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>颜色：</dt>
                <dd>
                    <input type="hidden" name="MealModel[color_id]" data-name="color.id" value="<?=ArrayHelper::getValue($model, 'color_id')?>">
                    <input type="text" class="required textInput readonly" readonly="true" name="color.name" value="<?=ArrayHelper::getValue($model,'color.name')?>" data-name="color.name" suggestfields="name" lookupgroup="color" autocomplete="off">
                    <a id="look-color" class="btnLook" href="<?=Url::to(['color/color-list', 'search' => 1, 'more' => $more, 'check' => 1])?>" lookupgroup="color">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>品牌分类：</dt>
                <dd>
                    <input id="brand-id" type="hidden" name="MealModel[brand_id]" data-name="brand.id" value="<?=ArrayHelper::getValue($model, 'brand_id')?>">
                    <input id="brand-name" type="text" class="required textInput readonly" readonly="true" name="brand.name" value="<?=ArrayHelper::getValue($model,'brand.name')?>" data-name="brand.name" suggestfields="name" lookupgroup="brand" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['phone/brand-list', 'search' => 1, 'more' => $more])?>" lookupgroup="brand">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>机型：</dt>
                <dd>
                    <input class="mobile" type="hidden" name="MealModel[mobile_id]" data-name="phone.id" value="<?=ArrayHelper::getValue($model, 'mobile_id')?>">
                    <input type="text" class="textInput readonly mobile" readonly="true" name="phone.name" value="<?=ArrayHelper::getValue($model,'phone.modal')?>" data-name="phone.name" suggestfields="name" lookupgroup="phone" autocomplete="off">
                    <a id="look-mobile" class="btnLook" href="<?=Url::to(['phone/phone-list', 'search' => 1, 'more' => $more, 'notMore' => 1, 'check' => 1])?>" lookupgroup="phone">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>客户：</dt>
                <dd>
                    <input id="customer-id" type="hidden" name="MealModel[customer_id]" data-name="customer.id" value="<?=ArrayHelper::getValue($model, 'customer_id',$customerId)?>">
                    <input id="customer-name" type="text" class="required textInput readonly" readonly="true" name="customer-name" value="<?=ArrayHelper::getValue($model,'customer.name',$customerName)?>" data-name="customer.name" suggestfields="name" lookupgroup="customer" autocomplete="off">
                    <a class="btnLook" href="<?=Url::to(['customer/customer-list', 'search' => 1])?>" lookupgroup="customer">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>图案：</dt>
                <dd>
                    <input class="theme" type="hidden" name="MealModel[theme_id]" data-name="theme.id" value="<?=ArrayHelper::getValue($model, 'theme_id')?>">
                    <input type="text" class="required textInput readonly theme" readonly="true" name="theme.name" value="<?=ArrayHelper::getValue($model,'theme.name')?>" data-name="theme.name" suggestfields="name" lookupgroup="theme" autocomplete="off">
                    <a id="look-theme" class="btnLook" href="<?=Url::to(['theme/theme-list', 'search' => 1, 'more' => $more, 'notMore' => 1])?>" lookupgroup="theme">查找带回</a>
                </dd>
            </dl>
            <dl>
                <dt>侧边图案：</dt>
                <dd>
                    <input class="side-theme" type="hidden" name="MealModel[side_theme_id]" data-name="sidetheme.id" value="<?=ArrayHelper::getValue($model, 'side_theme_id')?>">
                    <input type="text" class="required textInput readonly sidetheme" readonly="true" name="sidetheme.name" value="<?=ArrayHelper::getValue($model,'sidetheme.name')?>" data-name="sidetheme.name" suggestfields="name" lookupgroup="sidetheme" autocomplete="off">
                    <a id="look-side-theme" class="btnLook" href="<?=Url::to(['theme/side-theme-list', 'search' => 1, 'more' => $more, 'notMore' => 1])?>" lookupgroup="sidetheme">查找带回</a>
                </dd>
            </dl>
<!--            <dl>-->
<!--                <dt>左侧边图案：</dt>-->
<!--                <dd>-->
<!--                    <input class="left-theme" type="hidden" name="MealModel[left_theme_id]" data-name="lefttheme.id" value="=ArrayHelper::getValue($model, 'left_theme_id')?>">-->
<!--                    <input type="text" class="required textInput readonly lefttheme" readonly="true" name="lefttheme.name" value="=ArrayHelper::getValue($model,'lefttheme.name')?>" data-name="lefttheme.name" suggestfields="name" lookupgroup="lefttheme" autocomplete="off">-->
<!--                    <a id="look-left-theme" class="btnLook" href="=Url::to(['theme/left-theme-list', 'search' => 1, 'more' => $more, 'notMore' => 1])?>" lookupgroup="lefttheme">查找带回</a>-->
<!--                </dd>-->
<!--            </dl>-->
<!--            <dl>-->
<!--                <dt>右侧边图案：</dt>-->
<!--                <dd>-->
<!--                    <input class="right-theme" type="hidden" name="MealModel[right_theme_id]" data-name="righttheme.id" value="=ArrayHelper::getValue($model, 'right_theme_id')?>">-->
<!--                    <input type="text" class="required textInput readonly righttheme" readonly="true" name="righttheme.name" value="=ArrayHelper::getValue($model,'righttheme.name')?>" data-name="righttheme.name" suggestfields="name" lookupgroup="righttheme" autocomplete="off">-->
<!--                    <a id="look-right-theme" class="btnLook" href="=Url::to(['theme/right-theme-list', 'search' => 1, 'more' => $more, 'notMore' => 1])?>" lookupgroup="righttheme">查找带回</a>-->
<!--                </dd>-->
<!--            </dl>-->
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function(){
        //机型选择
        $('#look-mobile').on('click',function(){
            var brand_name = $('#brand-name').val(),
                brand_id = $('#brand-id').val(),
                material_id = $('#material-id').val(),
                _href = '<?=Url::to(['phone/phone-list', 'search' => 1, 'more' => $more, 'notMore' => 1, 'check' => 1])?>'+'&other[brand_id]='+brand_id+'&brand-name='+brand_name+'&material_id='+material_id;
            $(this).attr('href', _href);
        });

        //图案选择
        $('#look-theme').on('click',function(){
            var customer_name = $('#customer-name').val(),
                customer_id = $('#customer-id').val(),
                _href = '<?=Url::to(['theme/theme-list', 'search' => 1, 'more' => $more, 'notMore' => 1])?>'+'&other[customer_id]='+customer_id+'&customer-name='+customer_name;
            $(this).attr('href', _href);
        });

        //侧边图案选择
        $('#look-side-theme').on('click',function(){
            var customer_name = $('#customer-name').val(),
                customer_id = $('#customer-id').val(),
                _href = '<?=Url::to(['theme/side-theme-list', 'search' => 1, 'more' => $more, 'notMore' => 1])?>'+'&other[customer_id]='+customer_id+'&customer-name='+customer_name;
            $(this).attr('href', _href);
        });

        ////左侧边图案选择
        //$('#look-left-theme').on('click',function(){
        //    var customer_name = $('#customer-name').val(),
        //        customer_id = $('#customer-id').val(),
        //        _href = '<?//=Url::to(['theme/left-theme-list', 'search' => 1, 'more' => $more, 'notMore' => 1])?>//'+'&other[customer_id]='+customer_id+'&customer-name='+customer_name;
        //    $(this).attr('href', _href);
        //});
        //
        ////右侧边图案选择
        //$('#look-right-theme').on('click',function(){
        //    var customer_name = $('#customer-name').val(),
        //        customer_id = $('#customer-id').val(),
        //        _href = '<?//=Url::to(['theme/right-theme-list', 'search' => 1, 'more' => $more, 'notMore' => 1])?>//'+'&other[customer_id]='+customer_id+'&customer-name='+customer_name;
        //    $(this).attr('href', _href);
        //});

        //颜色选择
        //图案选择
        $('#look-color').on('click',function(){
            var material_id = $('#material-id').val(),
                _href = '<?=Url::to(['color/color-list', 'search' => 1, 'more' => $more, 'notMore' => 1, 'check' => 1])?>'+'&material_id='+material_id;
            $(this).attr('href', _href);
        });
    });
</script>