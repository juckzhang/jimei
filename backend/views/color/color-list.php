<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\constants\Constant;

$params = \Yii::$app->request->getPost();
$page   = ArrayHelper::getValue($params,'pageNum','1');
$orderFiled = ArrayHelper::getValue($params,'orderField','');
$orderDirection = ArrayHelper::getValue($params,'orderDirection','asc');
$prePage = ArrayHelper::getValue($params,'numPerPage',Constant::DEFAULT_PRE_PAGE);
$other = ArrayHelper::getValue($params, 'other', []);
$search = ArrayHelper::getValue($params,'search');
$more = ArrayHelper::getValue($params, 'more');
$check = ArrayHelper::getValue($params, 'check');
$material_id = ArrayHelper::getValue($params, 'material_id', '');
?>
<div class="" id="color-list" rel="color-list">
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="search" value="<?=$search?>">
    <input type="hidden" name="more" value="<?=$more?>">
    <input type="hidden" name="check" value="<?=$check?>">
    <input type="hidden" name="material_id" value="<?=$material_id?>">
    <input type="hidden" name="pageNum" value="<?=$page?>" />
    <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
    <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
    <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return <?=$search ? 'dialogSearch' : 'navTabSearch'?>(this);" action="<?=Url::to(['color/color-list','search' => $search, 'more' => $more,'check' => $check, 'material_id' => $material_id])?>" method="post">
        <div class="searchBar">
            <table class="searchContent">
                <tbody>
                <tr>
                    <td>名称：<input name="other[keyword]" class="textInput" type="text" alt="" value="<?=ArrayHelper::getValue($other,'keyword')?>"></td>
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
            <?php if(\Yii::$app->user->can('color/edit-color')):?>
            <li><a class="add" href="<?=Url::to(['color/edit-color'])?>" target="dialog"><span>添加</span></a></li>
            <?php endif;?>

            <?php if(\Yii::$app->user->can('color/delete-color')):?>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['color/delete-color'])?>" class="delete"><span>批量删除</span></a></li>
            <?php endif;?>
        </ul>
    </div>
    <table class="table" width="1200" layoutH="138">
        <thead>
        <tr>
            <?php if(!$search or $more):?>
            <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
            <?php elseif ($search):?>
            <th width="22">操作</th>
            <?php endif;?>
            <th width="80">名称</th>
            <th width="80">条码</th>
            <th orderfield="update_time" width="80">修改时间</th>
            <?php if(!$search):?>
            <th width="70">操作</th>
            <?php endif;?>
        </tr>
        </thead>
        <tbody>
        <?php foreach($dataList as $key => $data):?>
            <tr target="card-id" rel="<?=$data->id?>">
                <?php if(!$search or $more):?>
                <td>
                    <?php if(!$check or in_array($data->id, $colorIds)):?>
                    <input name="ids[]" value="<?=$search? "{id:$data->id,name:'{$data->name}'}" : $data->id?>" type="checkbox">
                    <?php endif;?>
                </td>
                <?php elseif ($search):?>
                <td>
                    <?php if(!$check or in_array($data->id, $colorIds)):?>
                    <a class="btnSelect" href="javascript:$.bringBack({id:<?=$data->id?>, name:'<?=$data->name?>'})" title="查找带回">选择</a>
                    <?php endif;?>
                </td>
                <?php endif;?>
                <td><?=$data->name?></td>
                <td><?=$data->barcode?></td>
                <td><?=date('Y-m-d H:i:s',$data->update_time)?></td>
                <?php if(!$search):?>
                <td>
                    <?php if(\Yii::$app->user->can('color/delete-color')):?>
                    <a title="删除" target="ajaxTodo" href="<?=Url::to(['color/delete-color','ids' => $data->id])?>" class="btnDel">删除</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('color/edit-color')):?>
                    <a title="编辑" target="dialog" href="<?=Url::to(['color/edit-color','id' => $data->id])?>" class="btnEdit">编辑</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('meal/meal-list')):?>
                        <a title="套餐列表" target="navTab" rel="meal-list" href="<?=Url::to(['meal/meal-list', 'notMore' => 1, 'other' => ['color_id' => $data->id],'color-name' => urlencode($data->name)])?>" class="btnView">套餐列表</a>
                    <?php endif;?>
                </td>
                <?php endif;?>
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
                <option value="500" <?=$prePage == 500 ? 'selected' : ''?>>500</option>
                <option value="1000" <?=$prePage == 1000 ? 'selected' : ''?>>1000</option>
            </select>
            <span>条，共<?=$dataCount?>条</span>
        </div>
        <div class="pagination" rel='color-list' targetType="<?=$search?'dialog':'navTab'?>" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
    </div>
</div>
</div>
