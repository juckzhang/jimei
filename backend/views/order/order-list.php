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
$baseId = ArrayHelper::getValue($params,'base_id');
?>
<div class="" id="order-list" rel="order-list">
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="search" value="<?=$search?>">
    <input type="hidden" name="pageNum" value="<?=$page?>" />
    <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
    <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
    <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
    <input type="hidden" name="base_id" value="<?=$baseId?>" id="base_id"/>
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return <?=$search ? 'dialogSearch' : 'navTabSearch'?>(this);" action="<?=Url::to(['order/order-list','search' => $search, 'base_id' => $baseId])?>" method="post">
        <div class="searchBar">
            <table class="searchContent">
                <tbody>
                <tr>
                    <td>关键词：<input name="other[keyword]" class="textInput" type="text" alt="" value="<?=ArrayHelper::getValue($other,'keyword')?>"></td>
                    <td>异常订单:
                    <select name="other[status]" value="<?=ArrayHelper::getValue($other,'status')?>">
                        <option value="">--选择--</option>
                        <option value="0" <?=ArrayHelper::getValue($other,'status') === '0' ? 'selected' : ''?>>正常订单</option>
                        <option value="2" <?=ArrayHelper::getValue($other,'status') === '2' ? 'selected' : ''?>>异常订单</option>
                    </select>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="subBar">
                <ul>
                    <li><div class="button"><div class="buttonContent"><button type="reset">重置</button></div></div></li>
                    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
                    <?php if(false):?>
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
            <?php if(\Yii::$app->user->can('order/delete-order')):?>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['order/delete-order'])?>" class="delete"><span>批量删除</span></a></li>
            <?php endif;?>

            <?php if(\Yii::$app->user->can('order/delete-order')):?>
                <li><a title="确实要重新解析这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['order/parse-order'])?>" class="edit"><span>批量解析</span></a></li>
            <?php endif;?>
        </ul>
    </div>
    <table class="table" width="1200" layoutH="138">
        <thead>
        <tr>
            <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
            <th width="80">订单号</th>
            <th width="80">网店名称</th>
            <th width="80">商品名称</th>
            <th width="80">网店规格型号</th>
            <th width="80">校验码</th>
            <th width="80">条码</th>
            <th width="80">套餐编码(全渠道)</th>
            <th width="40">机型</th>
            <th width="40">机型宽</th>
            <th width="40">机型高</th>
            <th width="80">材质</th>
            <th width="40">左边距</th>
            <th width="40">上边距</th>
            <th width="80">边框图</th>
            <th width="80">素材</th>
            <th width="80">图片</th>
            <th width="40">颜色</th>
            <th width="40">状态</th>
            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($dataList as $key => $data):?>
            <tr target="card-id" rel="<?=$data['id']?>">
                <td><input name="ids[]" value="<?=$search? "{id:{$data['id']},name:'{$data['modal']}'}" : $data['id']?>" type="checkbox"></td>
                <td><?=$data['order_id']?></td>
                <td><?=$data['shopname']?></td>
                <td><?=$data['goodsname']?></td>
                <td><?=$data['eshopskuname']?></td>
                <td><?=$data['checkcode']?></td>
                <td><?=sprintf(
                        "%s%s%s%s%s%s",
                        ArrayHelper::getValue($data, 'brand.barcode'),
                        ArrayHelper::getValue($data, 'phone.barcode'),
                        ArrayHelper::getValue($data, 'material.barcode'),
                        ArrayHelper::getValue($data, 'color.barcode'),
                        ArrayHelper::getValue($data, 'customer.barcode'),
                        ArrayHelper::getValue($data, 'theme.barcode')
                    )?></td>
                <td><?=$data['suitecode']?></td>
                <td><?=ArrayHelper::getValue($data,'phone.modal')?></td>
                <td><?=ArrayHelper::getValue($data,'phone.width')?></td>
                <td><?=ArrayHelper::getValue($data,'phone.height')?></td>
                <td><?=ArrayHelper::getValue($data,'material.name')?></td>
                <td><?=ArrayHelper::getValue($data,'relat.left')?></td>
                <td><?=ArrayHelper::getValue($data,'relat.top')?></td>
                <td><img width="50" src="<?=ArrayHelper::getValue($data,'relat.border_url') ? rtrim(ArrayHelper::getValue($data,'relat.border_url'),'.tif').'.jpg' : ''?>" /></td>
                <td><?=ArrayHelper::getValue($data,'theme.name')?></td>
                <td><img width="50" src="<?=ArrayHelper::getValue($data,'theme.template_url') ? rtrim(ArrayHelper::getValue($data,'theme.template_url'),'.tif').'.jpg' : ''?>" /></td>
                <td><?=ArrayHelper::getValue($data,'color.name')?></td>
                <td><?php if($data['status'] == 2
                        or ArrayHelper::getValue($data,'relat.status') == 2
                        or ArrayHelper::getValue($data, 'phone.status') == 2
                    or ArrayHelper::getValue($data, 'theme.status') == 2
                    or !ArrayHelper::getValue($data,'relat'))
                    echo '<span style="color: red;">异常</span>';
                else
                    echo '正常';
                ?></td>
                <td>
                    <?php if(\Yii::$app->user->can('order/delete-order')):?>
                    <a title="删除" target="ajaxTodo" href="<?=Url::to(['order/delete-order','ids' => $data['id']])?>" class="btnDel">删除</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('order/delete-order')):?>
                        <li><a title="解析套餐码" target="ajaxTodo" href="<?=Url::to(['order/parse-order','ids' => $data['id']])?>" class="btnAttach"><span>解析套餐码</span></a></li>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('order/delete-order')):?>
                        <li><a title="编辑" target="navTab" href="<?=Url::to(['order/edit-order','id' => $data['id']])?>" class="btnEdit"><span>编辑</span></a></li>
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
                <option value="200" <?=$prePage == 500 ? 'selected' : ''?>>500</option>
                <option value="200" <?=$prePage == 1000 ? 'selected' : ''?>>1000</option>
            </select>
            <span>条，共<?=$dataCount?>条</span>
        </div>
        <div class="pagination" rel='order-list' targetType="<?=$search?'dialog':'navTab'?>" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
    </div>
</div>
</div>
