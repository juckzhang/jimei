<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\constants\Constant;
use common\helpers\CommonHelper;

$params = \Yii::$app->request->getPost();
$page   = ArrayHelper::getValue($params,'pageNum','1');
$orderFiled = ArrayHelper::getValue($params,'orderField','');
$orderDirection = ArrayHelper::getValue($params,'orderDirection','asc');
$prePage = ArrayHelper::getValue($params,'numPerPage',Constant::DEFAULT_PRE_PAGE);
$other = ArrayHelper::getValue($params, 'other', []);
$search = ArrayHelper::getValue($params,'search');
$more = ArrayHelper::getValue($params, 'more');
$notMore = ArrayHelper::getValue($params, 'notMore');
$user = CommonHelper::customer();
?>
<div class="" id="task-list" rel="task-list">
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
        <form rel="pagerForm" onsubmit="return <?=$search ? 'dialogSearch' : 'navTabSearch'?>(this);" action="<?=Url::to(['meal/task-list','search' => $search, 'more' => $more, 'notMore' => $notMore])?>" method="post">
            <div class="searchBar">
                <table class="searchContent">
                    <tbody>
                    <tr>
                        <td>
                            客户:
                            <input id="_customer-id" type="hidden" name="other[customer_id]" data-name="customer.id" value="<?=ArrayHelper::getValue($other, 'customer_id',$user['customer_id'])?>">
                            <input id="_customer-name" type="text" class="textInput readonly" readonly="true" name="customer-name" value="<?=ArrayHelper::getValue($params,'customer-name',$user['customer_name'])?>" data-name="customer.name" suggestfields="name" lookupgroup="customer" autocomplete="off">
                            <a class="btnLook" href="<?=Url::to(['customer/customer-list', 'search' => 1])?>" lookupgroup="customer">查找带回</a>
                        </td>
                        <td>
                            状态:
                            <select name="other[sync_status]" value="<?=ArrayHelper::getValue($other, 'sync_status')?>">
                                <option value="">--选择同步状态--</option>
                                <option value="0" <?=ArrayHelper::getValue($other, 'sync_status') === '0' ? 'selected' : ''?>>等待执行</option>
                                <option value="3" <?=ArrayHelper::getValue($other, 'sync_status') === '0' ? 'selected' : ''?>>执行中</option>
                                <option value="1" <?=ArrayHelper::getValue($other, 'sync_status') === '1' ? 'selected' : ''?>>已完成</option>
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
                <?php if(\Yii::$app->user->can('meal/edit-task')):?>
                    <li><a class="add" href="<?=Url::to(['meal/task-meal'])?>" target="navTab"><span>添加</span></a></li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('meal/delete-task')):?>
                    <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['meal/delete-task'])?>" class="delete"><span>批量删除</span></a></li>
                <?php endif;?>
            </ul>
        </div>
        <table class="table" width="1200" layoutH="138">
            <thead>
            <tr>
                <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
                <th orderfield="brand_id" width="80">序号</th>
                <th orderfield="brand_id" width="80">客户</th>
                <th orderfield="mobile_id" width="80">备注</th>
                <th orderfield="material_id" width="80">任务状态</th>
                <th orderfield="color_id" width="80">同步结果</th>
                <th orderfield="update_time" width="80">修改时间</th>
                <th width="70">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($dataList as $key => $data):?>
                <tr target="card-id" rel="<?=$data['id']?>">
                    <td><input name="ids[]" value="<?=$search? "{id:{$data['id']},name:'{$data['modal']}'}" : $data['id']?>" type="checkbox"></td>
                    <td><?=($page - 1)*$prePage+$key+1?></td>
                    <td><?=$data['customer']['name']?></td>
                    <td><?=$data['desc']?></td>
                    <td>
                        <?php if($data['sync_status'] == 0):?>
                            等待执行
                        <?php elseif ($data['sync_status'] == 3):?>
                            执行中
                        <?php elseif ($data['sync_status'] == 1):?>
                            已完整
                        <?php else:?>
                            失败
                        <?php endif;?>
                    </td>
                    <td><?=$data['result']?></td>
                    <td><?=date('Y-m-d H:i:s',$data['update_time'])?></td>
                    <td>
                        <?php if(\Yii::$app->user->can('meal/delete-meal')):?>
                            <a title="删除" target="ajaxTodo" href="<?=Url::to(['meal/delete-task','ids' => $data['id']])?>" class="btnDel">删除</a>
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
            <div class="pagination" rel='meal-list' targetType="<?=$search?'dialog':'navTab'?>" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
        </div>
    </div>
</div>