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
$notMore = ArrayHelper::getValue($params, 'notMore');
?>
<div class="" id="customer-list" rel="customer-list">
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="search" value="<?=$search?>">
    <input type="hidden" name="more" value="<?=$more?>">
    <input type="hidden" name="notMore" value="<?=$notMore?>">
    <input type="hidden" name="pageNum" value="<?=$page?>" />
    <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
    <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
    <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return <?=$search ? 'dialogSearch' : 'navTabSearch'?>(this);" action="<?=Url::to(['customer/customer-list', 'search' => $search, 'more' => $more, 'notMore' => $notMore])?>" method="post">
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
            <?php if(\Yii::$app->user->can('customer/edit-customer')):?>
            <li><a class="add" href="<?=Url::to(['customer/edit-customer'])?>" target="dialog"><span>添加</span></a></li>
            <?php endif;?>

            <?php if(\Yii::$app->user->can('customer/delete-customer')):?>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['customer/delete-customer'])?>" class="delete"><span>批量删除</span></a></li>
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
            <th width="80">账户余额</th>
            <th width="80">顺丰</th>
            <th width="80">中通</th>
            <th width="80">圆通</th>
            <th width="80">汇通</th>
            <th width="80">韵达</th>
            <th width="80">申通</th>
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
                <td><input name="ids[]" value="<?=$search? "{id:$data->id,name:'{$data->name}'}" : $data->id?>" type="checkbox"></td>
                <?php elseif ($search):?>
                    <td><a class="btnSelect" href="javascript:$.bringBack({id:<?=$data->id?>, name:'<?=$data->name?>'})" title="查找带回">选择</a></td>
                <?php endif;?>
                <td><?=$data->name?></td>
                <td><?=$data->barcode?></td>
                <td><?=$data->balance?></td>
                <td><?=$data->sf_diff?></td>
                <td><?=$data->zt_diff?></td>
                <td><?=$data->yt_diff?></td>
                <td><?=$data->ht_diff?></td>
                <td><?=$data->yd_diff?></td>
                <td><?=$data->st_diff?></td>
                <td><?=date('Y-m-d H:i:s',$data->update_time)?></td>
                <?php if(!$search):?>
                <td>
                    <?php if(\Yii::$app->user->can('customer/delete-customer')):?>
                    <a title="删除" target="ajaxTodo" href="<?=Url::to(['customer/delete-customer','ids' => $data->id])?>" class="btnDel">删除</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('customer/edit-customer')):?>
                    <a title="编辑" target="dialog" href="<?=Url::to(['customer/edit-customer','id' => $data->id])?>" class="btnEdit">编辑</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('theme/theme-list')):?>
                        <a title="图案列表" target="navTab" rel="theme-list" href="<?=Url::to(['theme/theme-list', 'notMore' => 1, 'other' => ['customer_id' => $data->id],'customer-name' => urlencode($data->name)])?>" class="btnInfo">图案列表</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('meal/meal-list')):?>
                        <a title="套餐列表" target="navTab" rel="meal-list" href="<?=Url::to(['meal/meal-list', 'notMore' => 1, 'other' => ['customer_id' => $data->id],'customer-name' => urlencode($data->name)])?>" class="btnView">套餐列表</a>
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
        <div class="pagination" rel='customer-list' targetType="<?=$search?'dialog':'navTab'?>" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
    </div>
</div>
</div>
