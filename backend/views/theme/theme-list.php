<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\MaterialService;

$mediaService = MaterialService::getService();
$params = \Yii::$app->request->getPost();
$page   = ArrayHelper::getValue($params,'pageNum','1');
$orderFiled = ArrayHelper::getValue($params,'orderField','');
$orderDirection = ArrayHelper::getValue($params,'orderDirection','asc');
$prePage = ArrayHelper::getValue($params,'numPerPage','20');
$other = ArrayHelper::getValue($params, 'other', []);
$search = ArrayHelper::getValue($params,'search');
$more = ArrayHelper::getValue($params, 'more');
?>
<div class="" id="theme-list" rel="theme-list">
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="search", value="<?=$search?>">
    <input type="hidden" name="more", value="<?=$more?>">
    <input type="hidden" name="pageNum" value="<?=$page?>" />
    <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
    <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
    <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return <?=$search ? 'dialogSearch' : 'navTabSearch'?>(this);" action="<?=Url::to(['theme/theme-list','search' => $search])?>" method="post">
        <div class="searchBar">
            <table class="searchContent">
                <tbody>
                <tr>
                    <td>名称：<input name="other[keyword]" class="textInput" type="text" alt="" value="<?=ArrayHelper::getValue($other,'keyword')?>"></td>
                    <td>
                        客户:
                        <input type="hidden" name="other[customer_id]" data-name="customer.id" value="<?=ArrayHelper::getValue($other, 'customer_id')?>">
                        <input type="text" class="textInput readonly" readonly="true" name="customer-name" value="<?=ArrayHelper::getValue($params,'customer-name')?>" data-name="customer.name" suggestfields="name" lookupgroup="customer" autocomplete="off">
                        <a class="btnLook" href="<?=Url::to(['customer/customer-list', 'search' => 1])?>" lookupgroup="customer">查找带回</a>
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
            <?php if(\Yii::$app->user->can('theme/edit-theme')):?>
            <li><a class="add" href="<?=Url::to(['theme/edit-theme'])?>" target="navTab"><span>添加</span></a></li>
            <?php endif;?>

            <?php if(\Yii::$app->user->can('theme/delete-theme')):?>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['theme/delete-theme'])?>" class="delete"><span>批量删除</span></a></li>
            <?php endif;?>
        </ul>
    </div>
    <table class="table" width="1200" layoutH="138">
        <thead>
        <tr>
            <?php if(!$search or $more):?>
            <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
            <?php endif;?>
            <th orderfield="name" width="80">名称</th>
            <th width="80">条码</th>
            <th orderfield="brand_id" width="80">客户</th>
            <th width="80">图案链接</th>
            <th orderfield="update_time" width="80">修改时间</th>
            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($dataList as $key => $data):?>
            <tr target="card-id" rel="<?=$data['id']?>">
                <?php if(!$search or $more):?>
                <td><input name="ids[]" value="<?=$search? "{id:{$data['id']},name:'{$data['name']}'}" : $data['id']?>" type="checkbox"></td>
                <?php endif;?>
                <td><?=$data['name']?></td>
                <td><?=$data['barcode']?></td>
                <td><?=$data['customer']['name']?></td>
                <td><?=$data['template_url']?></td>
                <td><?=date('Y-m-d H:i:s',$data['update_time'])?></td>
                <td>
                    <?php if(\Yii::$app->user->can('theme/delete-theme')):?>
                    <a title="删除" target="ajaxTodo" href="<?=Url::to(['theme/delete-theme','ids' => $data['id']])?>" class="btnDel">删除</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('theme/edit-theme')):?>
                    <a title="编辑" target="navTab" href="<?=Url::to(['theme/edit-theme','id' => $data['id']])?>" class="btnEdit">编辑</a>
                    <?php endif;?>

                    <?php if($search):?>
                        <a class="btnSelect" href="javascript:$.bringBack({id:<?=$data['id']?>, name:'<?=$data['name']?>'})" title="查找带回">选择</a>
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
        <div class="pagination" rel='theme-list' targetType="<?=$search?'dialog':'navTab'?>" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
    </div>
</div>
</div>
