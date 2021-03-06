<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\SystemService;
$params = \Yii::$app->request->getPost();
$page   = ArrayHelper::getValue($params,'pageNum','1');
$orderFiled = ArrayHelper::getValue($params,'orderField','');
$orderDirection = ArrayHelper::getValue($params,'orderDirection','asc');
$prePage = ArrayHelper::getValue($params,'numPerPage','20');

//查询条件
$roleId = ArrayHelper::getValue($params,'other.roleId');
$roles = SystemService::getService()->roleAll();
?>
<div class="" id="user-list" rel="user-list">
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$page?>" />
    <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
    <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
    <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=Url::to(['system/user-list'])?>" method="post">
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <?php if(\Yii::$app->user->can('system/edit-user')):?>
            <li><a class="add" href="<?=Url::to(['system/edit-user'])?>" target="navTab"><span>添加</span></a></li>
            <?php endif;?>

            <?php if(\Yii::$app->user->can('system/delete-user')):?>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['system/delete-user'])?>" class="delete"><span>批量删除</span></a></li>
            <?php endif;?>
            <!--            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids" postType="string" href="demo/common/ajaxDone.html" class="delete"><span>批量删除逗号分隔</span></a></li>-->
<!--            <li><a class="edit" href="demo_page4.html?uid={sid_user}" target="navTab" warn="请选择一个用户"><span>修改</span></a></li>-->
<!--            <li class="line">line</li>-->
<!--            <li><a class="icon" href="demo/common/dwz-team.xls" target="dwzExport" targetType="navTab" title="实要导出这些记录吗?"><span>导出EXCEL</span></a></li>-->
            <!--<li><a target="selectedLoad" rel="ids" postType="string" href="demo_page1.html" class="icon"><span>批量Dialog Load逗号分隔</span></a></li>-->
        </ul>
    </div>
    <table class="table" width="1200" layoutH="138">
        <thead>
        <tr>
            <th width="10"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
            <th width="22">用户ID</th>
            <th width="20">用户名</th>
            <th width="60">所属角色</th>
            <th width="80">关联客户</th>
            <th class="<?=$orderDirection?>" style="cursor: pointer;" orderfield="update_time" width="40">建档日期</th>
            <th width="25">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($dataList as $key => $data):?>
            <tr target="card-id" rel="<?=$data->id?>">
                <td><input name="ids[]" value="<?=$data->id?>" type="checkbox"></td>
                <td><?=$data->id?></td>
                <td><?=$data->username?></td>
                <td><?=ArrayHelper::getValue($data, 'role.name', '超级管理员')?></td>
                <td><?=$data->customer_name?></td>
                <td><?=date('Y-m-d H:i:s',$data->create_time)?></td>
                <td>
                    <?php if(\Yii::$app->user->can('system/delete-user')):?>
                    <a title="删除" target="ajaxTodo" href="<?=Url::to(['system/delete-user','ids' => $data->id])?>" class="btnDel">删除</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('system/edit-user')):?>
                    <a title="编辑" target="navTab" href="<?=Url::to(['system/edit-user','id' => $data->id])?>" class="btnEdit">编辑</a>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>显示</span>
            <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
                <option value="20" <?=$prePage == 20 ?   'selected' : ''?>>20</option>
                <option value="50" <?=$prePage == 50 ?   'selected' : ''?>>50</option>
                <option value="100" <?=$prePage == 100 ? 'selected' : ''?>>100</option>
                <option value="200" <?=$prePage == 200 ? 'selected' : ''?>>200</option>
            </select>
            <span>条，共<?=$dataCount?>条</span>
        </div>
        <div class="pagination" rel='user-list' targetType="navTab" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
    </div>
</div>
</div>
