<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$params = \Yii::$app->request->getPost();
$page   = ArrayHelper::getValue($params,'pageNum','1');
$orderFiled = ArrayHelper::getValue($params,'orderField','');
$orderDirection = ArrayHelper::getValue($params,'orderDirection','asc');
$prePage = ArrayHelper::getValue($params,'numPerPage',Yii::$app->request->cookies->getValue('prePage', 100));
$other = ArrayHelper::getValue($params, 'other', []);
$search = ArrayHelper::getValue($params,'search');
$more = ArrayHelper::getValue($params, 'more');
?>
<div class="" id="relation-list" rel="relation-list">
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="search" value="<?=$search?>">
    <input type="hidden" name="pageNum" value="<?=$page?>" />
    <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
    <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
    <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return <?=$search ? 'dialogSearch' : 'navTabSearch'?>(this);" action="<?=Url::to(['phone/relation-list','search' => $search, 'more' => $more])?>" method="post">
        <div class="searchBar">
            <table class="searchContent">
                <tbody>
                <tr>
                    <td>
                        品牌:
                        <input id="bid" type="hidden" name="brand-id" data-name="brand.id" value="<?=ArrayHelper::getValue($params, 'brand-id')?>">
                        <input id="bname" type="text" class="textInput readonly" readonly="true" name="brand-name" value="<?=ArrayHelper::getValue($params,'brand-name')?>" data-name="brand.name" suggestfields="name" lookupgroup="brand" autocomplete="off">
                        <a class="btnLook" href="<?=Url::to(['phone/brand-list', 'search' => 1])?>" lookupgroup="brand">查找带回</a>
                    </td>
                    <td>
                        机型:
                        <input type="hidden" name="other[mobile_id]" data-name="phone.id" value="<?=ArrayHelper::getValue($other, 'phone_id')?>">
                        <input type="text" class="textInput readonly" readonly="true" name="phone-name" value="<?=ArrayHelper::getValue($params,'phone-name')?>" data-name="phone.name" suggestfields="name" lookupgroup="phone" autocomplete="off">
                        <a id="lookup-mobile" class="btnLook" href="<?=Url::to(['phone/phone-list', 'search' => 1])?>" lookupgroup="phone">查找带回</a>
                    </td>
                    <td>
                        材质:
                        <input type="hidden" name="other[material_id]" data-name="material.id" value="<?=ArrayHelper::getValue($other, 'material_id')?>">
                        <input type="text" class="textInput readonly" readonly="true" name="material-name" value="<?=ArrayHelper::getValue($params,'material-name')?>" data-name="material.name" suggestfields="name" lookupgroup="material" autocomplete="off">
                        <a class="btnLook" href="<?=Url::to(['material/material-list', 'search' => 1])?>" lookupgroup="material">查找带回</a>
                    </td>
                    <td>
                        信息是否完整:
                        <select name="other[status]" valign="<?=ArrayHelper::getValue($other,'status')?>">
                            <option value="">--选择--</option>
                            <option value="2" <?=ArrayHelper::getValue($other,'status') === '2' ? 'selected' : ''?>>否</option>
                            <option value="0" <?=ArrayHelper::getValue($other,'status') === '0' ? 'selected' : ''?>>是</option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="subBar">
                <ul>
                    <li><div class="button"><div class="buttonContent"><button type="reset">重置</button></div></div></li>
                    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
                    <?php if($more):?>
                        <li><div class="button"><div class="buttonContent"><button type="button" multLookup="ids[]" warn="请选择部门">选择带回</button></div></div></li>
                    <?php endif;?>
                </ul>
            </div>
        </div>
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <?php if(\Yii::$app->user->can('phone/edit-relation')):?>
            <li><a class="add" href="<?=Url::to(['phone/edit-relation'])?>" target="navTab"><span>添加</span></a></li>
            <?php endif;?>

            <?php if(\Yii::$app->user->can('phone/delete-relation')):?>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['phone/delete-relation'])?>" class="delete"><span>批量删除</span></a></li>
            <?php endif;?>
        </ul>
    </div>
    <table class="table" width="1200" layoutH="138">
        <thead>
        <tr>
            <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
            <th orderfield="mobile_id" width="80">机型名称</th>
            <th orderfield="material_id" width="80">材质名称</th>
            <th>原图名称</th>
            <th width="80">左边距</th>
            <th width="80">上边距</th>
            <th width="80">上框图</th>
            <th orderfield="update_time" width="80">修改时间</th>
            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($dataList as $key => $data):?>
            <tr target="card-id" rel="<?=$data['id']?>">
                <td><input name="ids[]" value="<?=$search? "{id:{$data['id']},name:'{$data['phone']['modal']}-{$data['material']['name']}'}" : $data['id']?>" type="checkbox"></td>
                <td><?=$data['phone']['modal']?></td>
                <td><?=$data['material']['name']?></td>
                <td><?=$data['source_pic_name']?></td>
                <td><?=$data['left']?></td>
                <td><?=$data['top']?></td>
                <td><img width="50" src="<?=rtrim($data['border_url'],'.tif').'.jpg'?>" /></td>
                <td><?=date('Y-m-d H:i:s',$data['update_time'])?></td>
                <td>
                    <?php if(\Yii::$app->user->can('phone/delete-relation')):?>
                    <a title="删除" target="ajaxTodo" href="<?=Url::to(['phone/delete-relation','ids' => $data['id']])?>" class="btnDel">删除</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('phone/edit-relation')):?>
                    <a title="编辑" target="navTab" href="<?=Url::to(['phone/edit-relation','id' => $data['id']])?>" class="btnEdit">编辑</a>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>显示</span>
            <select class="combox" name="numPerPage" onchange="<?=$search ? 'dialogPageBreak':'navTabPageBreak'?>({numPerPage:this.value})">
                <option value="20" <?=$prePage == 20 ?   'selected' : ''?>>20</option>
                <option value="50" <?=$prePage == 50 ?   'selected' : ''?>>50</option>
                <option value="100" <?=$prePage == 100 ? 'selected' : ''?>>100</option>
                <option value="200" <?=$prePage == 200 ? 'selected' : ''?>>200</option>
            </select>
            <span>条，共<?=$dataCount?>条</span>
        </div>
        <div class="pagination" rel='relation-list' targetType="<?=$search?'dialog':'navTab'?>" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
    </div>
</div>
</div>
<script type="text/javascript">
    $(function(){
        $('#lookup-mobile').on('click',function(){
            var brand_name = $('#bname').val(),
                brand_id = $('#bid').val(),
                notMore = 1;
                if(brand_id == ''){
                    notMore = 0;
                }
                var _href = '<?=Url::to(['phone/phone-list', 'search' => 1])?>'+'&other[brand_id]='+brand_id+'&brand-name='+brand_name+'&notMore='+notMore;
            $(this).attr('href', _href);
        });
    });
</script>