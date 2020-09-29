<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\orderService;

$mediaService = orderService::getService();
$params = \Yii::$app->request->getPost();
$page   = ArrayHelper::getValue($params,'pageNum','1');
$orderFiled = ArrayHelper::getValue($params,'orderField','');
$orderDirection = ArrayHelper::getValue($params,'orderDirection','asc');
$prePage = ArrayHelper::getValue($params,'numPerPage','20');
$other = ArrayHelper::getValue($params, 'other', []);
$search = ArrayHelper::getValue($params,'search');
$baseId = ArrayHelper::getValue($params,'base_id');
?>
<div class="" id="order-list" rel="order-list">
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="search", value="<?=$search?>">
    <input type="hidden" name="pageNum" value="<?=$page?>" />
    <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
    <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
    <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
    <input type="hidden" name="base_id" value="<?=$baseId?>" id="base_id"/>
    <?php foreach ($other as $key => $value):?>
        <input type="hidden" name="other[<?=$key;?>]" value="<?=$value;?>"/>
    <?php endforeach;?>
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return <?=$search ? 'dialogSearch' : 'navTabSearch'?>(this);" action="<?=Url::to(['order/order-list','search' => $search])?>" method="post">
        <?php foreach ($other as $key => $value):?>
            <input type="hidden" name="other[<?=$key;?>]" value="<?=$value;?>"/>
        <?php endforeach;?>
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <?php if(\Yii::$app->user->can('order/edit-order')):?>
            <li><a class="add" href="<?=Url::to(['order/edit-order'])?>" target="dialog"><span>添加</span></a></li>
            <?php endif;?>

            <?php if(\Yii::$app->user->can('order/delete-order')):?>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['order/delete-order'])?>" class="delete"><span>批量删除</span></a></li>
            <?php endif;?>
        </ul>
    </div>
    <table class="table" width="1200" layoutH="138">
        <thead>
        <tr>
            <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
            <th width="40">ID</th>
            <th width="80">条码</th>
            <th width="80">机型</th>
            <th width="80">材质</th>
            <th width="80">素材</th>
            <th width="80">颜色</th>
            <th orderfield="update_time" width="80">修改时间</th>
            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($dataList as $key => $data):?>
            <tr target="card-id" rel="<?=$data['id']?>">
                <td><input name="ids[]" value="<?=$search? "{id:{$data['id']},name:'{$data['modal']}'}" : $data['id']?>" type="checkbox"></td>
                <td><?=$data['id']?></td>
                <td><?=$data['barcode']?></td>
                <td><?=$data['phone']['modal']?></td>
                <td><?=$data['material']['name']?></td>
                <td><?=$data['theme']['name']?></td>
                <td><?=$data['color']['name']?></td>
                <td><?=date('Y-m-d H:i:s',$data['update_time'])?></td>
                <td>
                    <?php if(\Yii::$app->user->can('order/delete-order')):?>
                    <a title="删除" target="ajaxTodo" href="<?=Url::to(['order/delete-order','ids' => $data['id']])?>" class="btnDel">删除</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('order/edit-order')):?>
                    <a title="编辑" target="dialog" href="<?=Url::to(['order/edit-order','id' => $data['id']])?>" class="btnEdit">编辑</a>
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
        <div class="pagination" rel='order-list' targetType="<?=$search?'dialog':'navTab'?>" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
    </div>
</div>
</div>
