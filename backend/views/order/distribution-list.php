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
$status = ['0' => '未打印','1'=>'打印中','2'=>'已完成'];
$addType = ['1' => '全渠道','2'=>'手动'];
?>
<div class="" id="distribution-list" rel="distribution-list">
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="search" value="<?=$search?>">
    <input type="hidden" name="more" value="<?=$more?>">
    <input type="hidden" name="pageNum" value="<?=$page?>" />
    <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
    <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
    <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return <?=$search ? 'dialogSearch' : 'navTabSearch'?>(this);" action="<?=Url::to(['order/distribution-list','search' => $search, 'more' => $more])?>" method="post">
        <div class="searchBar">
            <table class="searchContent">
                <tbody>
                <tr>
                    <td>关键词：<input name="other[keyword]" class="textInput" type="text" alt="" value="<?=ArrayHelper::getValue($other,'keyword')?>"></td>
                    <td>打印状态:
                        <select name="other[task_status]">
                            <option value="" selected>--打印状态--</option>
                            <?php foreach ($status as $key => $item):?>
                                <option value="<?=$key?>" <?=ArrayHelper::getValue($other,'task_status')==$key ? 'selected' : ''?>><?=$item?></option>
                            <?php endforeach;?>
                        </select>
                    </td>
                    <td>添加方式:
                        <select name="other[add_type]">
                            <option value="" selected>--添加方式--</option>
                            <?php foreach ($addType as $key => $item):?>
                                <option value="<?=$key?>" <?=ArrayHelper::getValue($other,'add_type')==$key ? 'selected' : ''?>><?=$item?></option>
                            <?php endforeach;?>
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
            <?php if(\Yii::$app->user->can('order/edit-distribution')):?>
            <li><a class="add" href="<?=Url::to(['order/edit-distribution'])?>" target="dialog"><span>全渠道配货单</span></a></li>
                <li><a class="edit" href="<?=Url::to(['order/edit-distribution','add_type' => 2])?>" target="dialog"><span>手工配货单</span></a></li>
            <?php endif;?>

            <?php if(\Yii::$app->user->can('order/delete-distribution')):?>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['order/delete-distribution'])?>" class="delete"><span>批量删除</span></a></li>
            <?php endif;?>
        </ul>
    </div>
    <table class="table" width="1200" layoutH="138">
        <thead>
        <tr>
            <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
            <th width="80">配货分组号</th>
            <th width="80">添加方式</th>
            <th width="80">数量</th>
            <th width="80">状态</th>
            <th orderfield="update_time" width="80">修改时间</th>
            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($dataList as $key => $data):?>
            <tr target="card-id" rel="<?=$data->id?>">
                <td><input name="ids[]" value="<?=$search? "{id:$data->id,name:'{$data->sn}'}" : $data->id?>" type="checkbox"></td>
                <td><?=$data->sn?></td>
                <td><?=$data->add_type == 2 ? '手动' : '全渠道'?></td>
                <td><?=$data->num?></td>
                <td><?=ArrayHelper::getValue($status, $data->task_status,'未打印')?></td>
                <td><?=date('Y-m-d H:i:s',$data->update_time)?></td>
                <td>
                    <?php if(\Yii::$app->user->can('order/delete-distribution')):?>
                    <a title="删除" target="ajaxTodo" href="<?=Url::to(['order/delete-distribution','ids' => $data->id])?>" class="btnDel">删除</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('order/edit-distribution')):?>
                    <a title="编辑" target="dialog" href="<?=Url::to(['order/edit-distribution','id' => $data->id])?>" class="btnEdit">编辑</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('order/order-list')):?>
                        <a title="订单" target="navTab" rel="order-list" href="<?=Url::to(['order/order-list','base_id' => $data['id'],'add_type' => $data['add_type']])?>" class="btnInfo">订单</a>
                    <?php endif;?>

                    <?php if($search):?>
                        <a class="btnSelect" href="javascript:$.bringBack({id:<?=$data->id?>, name:'<?=$data->sn?>'})" title="查找带回">选择</a>
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
                <option value="500" <?=$prePage == 500 ? 'selected' : ''?>>500</option>
                <option value="1000" <?=$prePage == 1000 ? 'selected' : ''?>>1000</option>
            </select>
            <span>条，共<?=$dataCount?>条</span>
        </div>
        <div class="pagination" rel='distribution-list' targetType="<?=$search?'dialog':'navTab'?>" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
    </div>
</div>
</div>
