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
$notMore = ArrayHelper::getValue($params,'notMore');
$user = CommonHelper::customer();
?>
<div class="" id="theme-list" rel="theme-list">
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
    <form rel="pagerForm" onsubmit="return <?=$search ? 'dialogSearch' : 'navTabSearch'?>(this);" action="<?=Url::to(['theme/theme-list','search' => $search, 'more' => $more, 'notMore' => $notMore])?>" method="post">
        <div class="searchBar">
            <table class="searchContent">
                <tbody>
                <tr>
                    <td>名称/编码：<input name="other[keyword]" class="textInput" type="text" alt="" value="<?=ArrayHelper::getValue($other,'keyword')?>"></td>
                    <td>
                        客户:
                        <input type="hidden" name="other[customer_id]" data-name="customer.id" value="<?=ArrayHelper::getValue($other, 'customer_id',$user['customer_id'])?>">
                        <input type="text" class="textInput readonly" readonly="true" name="customer-name" value="<?=ArrayHelper::getValue($params,'customer-name',$user['customer_name'])?>" data-name="customer.name" suggestfields="name" lookupgroup="customer" autocomplete="off">
                        <?php if(!ArrayHelper::getValue($params,'notMore')):?>
                        <a class="btnLook" href="<?=Url::to(['customer/customer-list', 'search' => 1])?>" lookupgroup="customer">查找带回</a>
                        <?php endif;?>
                    </td>
                    <td>
                        颜色:
                        <select name="other[color]" value="<?=ArrayHelper::getValue($other,'color')?>">
                            <option value="">--选择颜色--</option>
                            <?php foreach ($colorList as $item):?>
                            <option value="<?=$item['name']?>" <?=ArrayHelper::getValue($other,'color') == $item['name'] ? 'selected' : ''?>><?=$item['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </td>
                    <td>信息是否完整:
                        <select name="other[status]" value="<?=ArrayHelper::getValue($other,'status')?>">
                            <option value="">--选择--</option>
                            <option value="0" <?=ArrayHelper::getValue($other,'status') === '0' ? 'selected' : ''?>>是</option>
                            <option value="2" <?=ArrayHelper::getValue($other,'status') === '2' ? 'selected' : ''?>>否</option>
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
            <?php if(\Yii::$app->user->can('theme/edit-theme')):?>
            <li><a class="add" href="<?=Url::to(['theme/edit-theme', 'customer_id' => ArrayHelper::getValue($other, 'customer_id'), 'customer_name' => urlencode(ArrayHelper::getValue($params, 'customer-name',''))])?>" target="navTab"><span>添加</span></a></li>
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
            <?php elseif ($search):?>
                <th width="22">操作</th>
            <?php endif;?>
            <th orderfield="name" width="80">名称</th>
            <th width="80">条码</th>
            <th orderfield="customer_id" width="80">客户</th>
            <th width="80">图案原图名称</th>
            <th width="80">图案</th>
            <th width="80">颜色</th>
            <th orderfield="update_time" width="80">修改时间</th>
            <?php if(!$search):?>
            <th width="70">操作</th>
            <?php endif;?>
        </tr>
        </thead>
        <tbody>
        <?php foreach($dataList as $key => $data):?>
            <tr target="card-id" rel="<?=$data['id']?>" class="cnt">
                <?php if(!$search or $more):?>
                <td><input name="ids[]" value="<?=$search? "{id:{$data['id']},name:'{$data['name']}',customer_id:{$data['customer_id']}}" : $data['id']?>" type="checkbox"></td>
                <?php elseif ($search):?>
                    <td><a class="btnSelect dblclick" href="javascript:$.bringBack({id:<?=$data['id']?>, name:'<?=$data['name']?>',customer_id:<?=$data['customer_id']?>})" title="查找带回">选择</a></td>
                <?php endif;?>
                <td><?=$data['name']?></td>
                <td><?=$data['barcode']?></td>
                <td><?=$data['customer']['name']?></td>
                <td><?=$data['source_pic_name']?></td>
                <td><img width="50" src="<?=rtrim($data['template_url'],'.tif').'.jpg'?>" /></td>
                <td><?=$data['color']?></td>
                <td><?=date('Y-m-d H:i:s',$data['update_time'])?></td>
                <?php if(!$search):?>
                <td>
                    <?php if(\Yii::$app->user->can('theme/delete-theme')):?>
                    <a title="删除" target="ajaxTodo" href="<?=Url::to(['theme/delete-theme','ids' => $data['id']])?>" class="btnDel">删除</a>
                    <?php endif;?>

                    <?php if(\Yii::$app->user->can('theme/edit-theme')):?>
                    <a title="编辑" target="navTab" href="<?=Url::to(['theme/edit-theme','id' => $data['id']])?>" class="btnEdit dblclick">编辑</a>
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
        <div class="pagination" rel='theme-list' targetType="<?=$search?'dialog':'navTab'?>" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
    </div>
</div>
</div>
<script type="text/javascript">
    $(function(){
        //行双击
        $(".cnt").on('dblclick', function() {
            console.log(12333);
            $(this).find('.dblclick').click();
        });
    });
</script>