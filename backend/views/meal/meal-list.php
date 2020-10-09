<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\MaterialService;

$mediaService = MaterialService::getService();
$params = \Yii::$app->request->getPost();
$page   = ArrayHelper::getValue($params,'pageNum','1');
$orderFiled = ArrayHelper::getValue($params,'orderField','');
$orderDirection = ArrayHelper::getValue($params,'orderDirection','asc');
$prePage = ArrayHelper::getValue($params,'numPerPage',Yii::$app->request->cookies->get('prePage', 100));
$other = ArrayHelper::getValue($params, 'other', []);
$search = ArrayHelper::getValue($params,'search');
?>
<div class="" id="meal-list" rel="meal-list">
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="search", value="<?=$search?>">
    <input type="hidden" name="pageNum" value="<?=$page?>" />
    <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
    <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
    <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return <?=$search ? 'dialogSearch' : 'navTabSearch'?>(this);" action="<?=Url::to(['meal/meal-list','search' => $search])?>" method="post">
        <div class="searchBar">
            <table class="searchContent">
                <tbody>
                <tr>
                    <td>
                        品牌:
                        <input type="hidden" name="other[brand_id]" data-name="brand.id" value="<?=ArrayHelper::getValue($other, 'brand_id')?>">
                        <input type="text" class="textInput readonly" readonly="true" name="brand-name" value="<?=ArrayHelper::getValue($params,'brand-name')?>" data-name="brand.name" suggestfields="name" lookupgroup="brand" autocomplete="off">
                        <a class="btnLook" href="<?=Url::to(['phone/brand-list', 'search' => 1])?>" lookupgroup="brand">查找带回</a>
                    </td>
                    <td>
                        机型:
                        <input type="hidden" name="other[mobile_id]" data-name="phone.id" value="<?=ArrayHelper::getValue($other, 'phone_id')?>">
                        <input type="text" class="textInput readonly" readonly="true" name="phone-name" value="<?=ArrayHelper::getValue($params,'phone-name')?>" data-name="phone.name" suggestfields="name" lookupgroup="phone" autocomplete="off">
                        <a class="btnLook" href="<?=Url::to(['phone/phone-list', 'search' => 1])?>" lookupgroup="phone">查找带回</a>
                    </td>
                    <td>
                        材质:
                        <input type="hidden" name="other[material_id]" data-name="material.id" value="<?=ArrayHelper::getValue($other, 'material_id')?>">
                        <input type="text" class="textInput readonly" readonly="true" name="material-name" value="<?=ArrayHelper::getValue($params,'material-name')?>" data-name="material.name" suggestfields="name" lookupgroup="material" autocomplete="off">
                        <a class="btnLook" href="<?=Url::to(['material/material-list', 'search' => 1])?>" lookupgroup="material">查找带回</a>
                    </td>
                    <td>
                        颜色:
                        <input type="hidden" name="other[color_id]" data-name="color.id" value="<?=ArrayHelper::getValue($other, 'color_id')?>">
                        <input type="text" class="textInput readonly" readonly="true" name="color-name" value="<?=ArrayHelper::getValue($params,'color-name')?>" data-name="color.name" suggestfields="name" lookupgroup="color" autocomplete="off">
                        <a class="btnLook" href="<?=Url::to(['color/color-list', 'search' => 1])?>" lookupgroup="color">查找带回</a>
                    </td>
                    <td>
                        客户:
                        <input type="hidden" name="other[customer_id]" data-name="customer.id" value="<?=ArrayHelper::getValue($other, 'customer_id')?>">
                        <input type="text" class="textInput readonly" readonly="true" name="customer-name" value="<?=ArrayHelper::getValue($params,'customer-name')?>" data-name="customer.name" suggestfields="name" lookupgroup="customer" autocomplete="off">
                        <a class="btnLook" href="<?=Url::to(['customer/customer-list', 'search' => 1])?>" lookupgroup="customer">查找带回</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        图案:
                        <input type="hidden" name="other[theme_id]" data-name="theme.id" value="<?=ArrayHelper::getValue($other, 'theme_id')?>">
                        <input type="text" class="textInput readonly" readonly="true" name="theme-name" value="<?=ArrayHelper::getValue($params,'theme-name')?>" data-name="theme.name" suggestfields="name" lookupgroup="theme" autocomplete="off">
                        <a class="btnLook" href="<?=Url::to(['theme/theme-list', 'search' => 1])?>" lookupgroup="theme">查找带回</a>
                    </td>
                    <td>
                        状态:
                        <select name="other[sync_status]" value="<?=ArrayHelper::getValue($other, 'sync_status')?>">
                            <option value="">--选择同步状态--</option>
                            <option value="0">未同步</option>
                            <option value="1">已同步</option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="subBar">
                <ul>
                    <li><div class="button"><div class="buttonContent"><button type="reset">重置</button></div></div></li>
                    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
                    <?php if($search):?>
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
            <?php if(\Yii::$app->user->can('meal/edit-meal')):?>
            <li><a class="add" href="<?=Url::to(['meal/edit-meal'])?>" target="navTab"><span>添加</span></a></li>
            <?php endif;?>

            <?php if(\Yii::$app->user->can('meal/delete-meal')):?>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['meal/delete-meal'])?>" class="delete"><span>批量删除</span></a></li>
            <?php endif;?>

            <?php if(\Yii::$app->user->can('meal/sync-meal')):?>
                <li><a title="确实要同步这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['meal/sync-meal'])?>" class="add"><span>批量同步</span></a></li>
            <?php endif;?>
        </ul>
    </div>
    <table class="table" width="1200" layoutH="138">
        <thead>
        <tr>
            <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
            <th orderfield="brand_id" width="80">品牌</th>
            <th orderfield="mobile_id" width="80">机型</th>
            <th orderfield="material_id" width="80">材质</th>
            <th orderfield="color_id" width="80">颜色</th>
            <th orderfield="customer_id" width="80">客户</th>
            <th orderfield="theme_id" width="80">素材</th>
            <th width="80">同步状态</th>
            <th orderfield="update_time" width="80">修改时间</th>
            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($dataList as $key => $data):?>
            <tr target="card-id" rel="<?=$data['id']?>">
                <td><input name="ids[]" value="<?=$search? "{id:{$data['id']},name:'{$data['modal']}'}" : $data['id']?>" type="checkbox"></td>
                <td><?=$data['brand']['name']?></td>
                <td><?=$data['phone']['modal']?></td>
                <td><?=$data['material']['name']?></td>
                <td><?=$data['color']['name']?></td>
                <td><?=$data['customer']['name']?></td>
                <td><?=$data['theme']['name']?></td>
                <td><?=$data['barcode']?></td>
                <td><?=date('Y-m-d H:i:s',$data['update_time'])?></td>
                <td>
                    <?php if(\Yii::$app->user->can('meal/delete-meal')):?>
                    <a title="删除" target="ajaxTodo" href="<?=Url::to(['meal/delete-meal','ids' => $data['id']])?>" class="btnDel">删除</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('meal/edit-meal')):?>
                    <a title="编辑" target="navTab" href="<?=Url::to(['meal/edit-meal','id' => $data['id']])?>" class="btnEdit">编辑</a>
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
        <div class="pagination" rel='meal-list' targetType="<?=$search?'dialog':'navTab'?>" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
    </div>
</div>
</div>
