<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\services\MaterialService;
use common\constants\Constant;

$params = \Yii::$app->request->getPost();
$page   = ArrayHelper::getValue($params,'pageNum','1');
$orderFiled = ArrayHelper::getValue($params,'orderField','');
$orderDirection = ArrayHelper::getValue($params,'orderDirection','asc');
$prePage = ArrayHelper::getValue($params,'numPerPage',Constant::DEFAULT_PRE_PAGE);
$other = ArrayHelper::getValue($params, 'other', []);
$brandId = ArrayHelper::getValue($other, 'brand_id');
$search = ArrayHelper::getValue($params,'search');
$more = ArrayHelper::getValue($params, 'more');
$notMore = ArrayHelper::getValue($params,'notMore');
$select = ArrayHelper::getValue($params,'select');
$canvasType = ['1' => '普通画布', '2' => '大画布'];
$check = ArrayHelper::getValue($params, 'check');
$material_id = ArrayHelper::getValue($params, 'material_id', '');
?>
<div class="" id="phone-list" rel="phone-list">
    <form id="pagerForm" method="post" action="#rel#">
        <input type="hidden" name="search" value="<?=$search?>">
        <input type="hidden" name="select" value="<?=$select?>">
        <input type="hidden" name="more" value="<?=$more?>">
        <input type="hidden" name="check" value="<?=$check?>">
        <input type="hidden" name="material_id" value="<?=$material_id?>">
        <input type="hidden" name="notMore" value="<?=ArrayHelper::getValue($params,'notMore')?>">
        <input type="hidden" name="pageNum" value="<?=$page?>" />
        <input type="hidden" name="numPerPage" value="<?=$prePage?>" />
        <input type="hidden" name="orderField" value="<?=$orderFiled?>" />
        <input type="hidden" name="orderDirection" value="<?=$orderDirection?>" />
    </form>
    <div class="pageHeader">
        <form rel="pagerForm" onsubmit="return <?=$search ? 'dialogSearch' : 'navTabSearch'?>(this);" action="<?=Url::to(['phone/phone-list','search' => $search, 'more' => $more, 'notMore' => $notMore, 'select' => $select,'check' => $check, 'material_id' => $material_id])?>" method="post">
            <div class="searchBar">
                <table class="searchContent">
                    <tbody>
                    <tr>
                        <td>名称：<input name="other[keyword]" class="textInput" type="text" alt="" value="<?=ArrayHelper::getValue($other,'keyword')?>"></td>
                        <?php if(!ArrayHelper::getValue($params, 'select')):?>
                            <td>
                                品牌:
                                <input type="hidden" name="other[brand_id]" data-name="brand.id" value="<?=ArrayHelper::getValue($other, 'brand_id')?>">
                                <input type="text" class="textInput readonly" readonly="true" name="brand-name" value="<?=ArrayHelper::getValue($params,'brand-name')?>" data-name="brand.name" suggestfields="name" lookupgroup="brand" autocomplete="off">
                                <?php if(!ArrayHelper::getValue($params,'notMore')):?>
                                    <a class="btnLook" href="<?=Url::to(['phone/brand-list', 'search' => 1])?>" lookupgroup="brand">查找带回</a>
                                <?php endif;?>
                            </td>
                        <?php else:?>
                        <td>
                            品牌:
                            <select name="other[brand_id]" value="<?=ArrayHelper::getValue($other,'brand_id')?>">
                                <option value="">--选择品牌--</option>
                                <?php foreach ($brandList as $brand):?>
                                    <option value="<?=$brand['id']?>" <?=$brand['id'] == ArrayHelper::getValue($other,'brand_id') ? 'selected' : ''?>><?=$brand['name']?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                        <?php endif;?>
                        <td>
                            信息是否完整:
                            <select name="other[status]" valign="<?=ArrayHelper::getValue($other,'status')?>">
                                <option value="">--选择--</option>
                                <option value="2" <?=ArrayHelper::getValue($other,'status') === '2' ? 'selected' : ''?>>否</option>
                                <option value="0" <?=ArrayHelper::getValue($other,'status') === '0' ? 'selected' : ''?>>是</option>
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
                <?php if(\Yii::$app->user->can('phone/edit-phone')):?>
                <li><a class="add" href="<?=Url::to(['phone/edit-phone'])?>" target="navTab"><span>添加</span></a></li>
                <?php endif;?>
                <?php if(\Yii::$app->user->can('phone/delete-phone')):?>
                <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?=Url::to(['phone/delete-phone'])?>" class="delete"><span>批量删除</span></a></li>
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
                <th orderfield="modal" width="80">名称</th>
                <th width="80">条码</th>
                <th orderfield="brand_id" width="80">品牌</th>
                <th width="80">宽</th>
                <th width="80">高</th>
                <th width="80">画布类型</th>
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
                        <?php if(!$check or in_array($data->id, $mobileIds)):?>
                        <input name="ids[]" value="<?=$search? "{id:$data->id,name:'{$data->modal}',brand_id:$data->brand_id}" : $data->id?>" type="checkbox">
                        <?php endif;?>
                    </td>
                    <?php elseif ($search):?>
                        <td>
                            <?php if(!$check or in_array($data->id, $mobileIds)):?>
                            <a class="btnSelect" href="javascript:$.bringBack({id:<?=$data->id?>, name:'<?=$data->modal?>',brand_id:<?=$data->brand_id?>})" title="查找带回">选择</a>
                            <?php endif;?>
                        </td>
                    <?php endif;?>
                    <td><?=$data->modal?></td>
                    <td><?=$data->barcode?></td>
                    <td><?=$data->brand->name?></td>
                    <td><?=$data->width?></td>
                    <td><?=$data->height?></td>
                    <td><?=ArrayHelper::getValue($canvasType, $data->canvas_type)?></td>
                    <td><?=date('Y-m-d H:i:s',$data->update_time)?></td>
                    <?php if(!$search):?>
                    <td>
                        <?php if(\Yii::$app->user->can('phone/delete-phone')):?>
                        <a title="删除" target="ajaxTodo" href="<?=Url::to(['phone/delete-phone','ids' => $data->id])?>" class="btnDel">删除</a>
                        <?php endif;?>

                        <?php if(\Yii::$app->user->can('phone/edit-phone')):?>
                        <a title="编辑" target="navTab" href="<?=Url::to(['phone/edit-phone','id' => $data->id])?>" class="btnEdit">编辑</a>
                        <?php endif;?>

                        <?php if(\Yii::$app->user->can('meal/meal-list')):?>
                            <a title="套餐列表" target="navTab" rel="meal-list" href="<?=Url::to(['meal/meal-list', 'notMore' => 1, 'other' => ['mobile_id' => $data->id,'brand_id' => $data->brand->id],'mobile-name' => urlencode($data->modal),'brand-name' => urlencode($data->brand->name)])?>" class="btnView">套餐列表</a>
                        <?php endif;?>

                        <?php if(\Yii::$app->user->can('phone/relation-list')):?>
                            <a title="机型材质关系列表" target="navTab" rel="relation-list" href="<?=Url::to(['phone/relation-list', 'notMore' => 1, 'other' => ['mobile_id' => $data->id],'mobile-name' => urlencode($data->modal)])?>" class="btnInfo">机型材质关系</a>
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
            <div class="pagination" rel='phone-list' targetType="<?=$search?'dialog':'navTab'?>" totalCount="<?=$dataCount?>" numPerPage="<?=$prePage?>" pageNumShown="10" currentPage="<?=$page?>"></div>
        </div>
    </div>
</div>
