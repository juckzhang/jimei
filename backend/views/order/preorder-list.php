<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\constants\Constant;
use yii\base\Arrayable;

$params = \Yii::$app->request->getPost();
$page   = ArrayHelper::getValue($params, 'pageNum', '1');
$orderFiled = ArrayHelper::getValue($params, 'orderField', '');
$orderDirection = ArrayHelper::getValue($params, 'orderDirection', 'asc');
$prePage = ArrayHelper::getValue($params, 'numPerPage', Constant::DEFAULT_PRE_PAGE);
$other = ArrayHelper::getValue($params, 'other', []);
$search = ArrayHelper::getValue($params, 'search');
?>
<div class="" id="preorder-list" rel="preorder-list">
    <form id="pagerForm" method="post" action="#rel#">
        <input type="hidden" name="search" value="<?= $search ?>">
        <input type="hidden" name="pageNum" value="<?= $page ?>" />
        <input type="hidden" name="numPerPage" value="<?= $prePage ?>" />
        <input type="hidden" name="orderField" value="<?= $orderFiled ?>" />
        <input type="hidden" name="orderDirection" value="<?= $orderDirection ?>" />
    </form>
    <div class="pageHeader">
        <form rel="pagerForm" onsubmit="return <?= $search ? 'dialogSearch' : 'navTabSearch' ?>(this);" action="<?= Url::to(['order/preorder-list', 'search' => $search]) ?>" method="post">
            <div class="searchBar">
                <table class="searchContent">
                    <tbody>
                        <tr>
                            <td>关键词：<input name="other[keyword]" class="textInput" type="text" alt="" value="<?= ArrayHelper::getValue($other, 'keyword') ?>"></td>
                            <td>异常订单:
                                <select name="other[status]" value="<?= ArrayHelper::getValue($other, 'status') ?>">
                                    <option value="">--选择--</option>
                                    <option value="0" <?= ArrayHelper::getValue($other, 'status') === '0' ? 'selected' : '' ?>>正常订单</option>
                                    <option value="2" <?= ArrayHelper::getValue($other, 'status') === '2' ? 'selected' : '' ?>>异常订单</option>
                                </select>
                            </td>
                            <td>审核扣款状态:
                                <select name="other[finance_status]" value="<?= ArrayHelper::getValue($other, 'finance_status') ?>">
                                    <option value="">--选择--</option>
                                    <option value="0" <?= ArrayHelper::getValue($other, 'status') === '0' ? 'selected' : '' ?>>未审核</option>
                                    <option value="1" <?= ArrayHelper::getValue($other, 'status') === '2' ? 'selected' : '' ?>>财审不通过</option>
                                    <option value="2" <?= ArrayHelper::getValue($other, 'status') === '2' ? 'selected' : '' ?>>财审已通过</option>
                                    <option value="4" <?= ArrayHelper::getValue($other, 'status') === '2' ? 'selected' : '' ?>>扣款完成</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="subBar">
                    <ul>
                        <li>
                            <div class="button">
                                <div class="buttonContent"><button type="reset">重置</button></div>
                            </div>
                        </li>
                        <li>
                            <div class="buttonActive">
                                <div class="buttonContent"><button type="submit">检索</button></div>
                            </div>
                        </li>
                        <?php if (false) : ?>
                            <li>
                                <div class="button">
                                    <div class="buttonContent"><button type="button" multLookup="ids[]" warn="请选择部门">选择带回</button></div>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </form>
    </div>
    <div class="pageContent">
        <div class="panelBar">
            <ul class="toolBar">
                <?php if (\Yii::$app->user->can('order/add-preorder')) : ?>
                    <li><a class="add" href="<?= Url::to(['order/add-preorder',]) ?>" target="dialog"><span>添加</span></a></li>
                <?php endif; ?>

                <?php if (\Yii::$app->user->can('order/delete-preorder')) : ?>
                    <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids[]" href="<?= Url::to(['order/delete-preorder']) ?>" class="delete"><span>批量删除</span></a></li>
                <?php endif; ?>
            </ul>
        </div>
        <table class="table" width="200%" layoutH="138">
            <thead>
                <tr>
                    <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
                    <th width="22">序号</th>
                    <th width="60">全渠道订单编号</th>
                    <th width="160">网店订单号</th>
                    <th width="40">网店名称</th>
                    <th width="40">网店商品规格编码</th>
                    <th width="40">本地商家编码</th>
                    <th width="80">订单标记</th>
                    <th width="22">数量</th>
                    <th width="60">单价</th>
                    <th width="40">预扣款总额</th>
                    <th width="40">预扣款物流费用</th>
                    <th width="40">客户</th>
                    <th width="70">图案名称</th>
                    <th width="70">套餐编码</th>
                    <th width="70">订单状态</th>
                    <th width="70">扣款审核状态</th>
                    <th width="70">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataList as $key => $data) : ?>
                    <tr target="card-id" rel="<?= $data['id'] ?>">
                        <td><input name="ids[]" value="<?= $search ? "{id:{$data['id']}}" : $data['id'] ?>" type="checkbox"></td>
                        <td><?= ($page - 1) * $prePage + $key + 1 ?></td>
                        <td><?= $data['billcode'] ?></td>
                        <td><?= $data['eshopbillcode'] ?></td>
                        <td><?= $data['eshopname'] ?></td>
                        <td><?= $data['eshopskuname'] ?>" /></td>
                        <td><?= $data['lcmccode'] ?>" /></td>
                        <td><?= $data['billflag'] ?>" /></td>
                        <td><?= $data['qty'] ?>" /></td>
                        <td><?= $data['price'] ?>" /></td>
                        <td><?= $data['payment_total'] ?>" /></td>
                        <td><?= $data['payment_freight'] ?>" /></td>
                        <td><?= ArrayHelper::getValue($data, 'customer.name') ?>" /></td>
                        <td><?= ArrayHelper::getValue($data, 'theme.name') ?>" /></td>
                        <td><?= $data['suitecode'] ?>" /></td>
                        <td><?php if ($data['status'] == 2 or !$data['theme'] or !$data['customer'])
                                echo '<span style="color: red;">异常</span>';
                            else
                                echo '正常';
                            ?>
                        </td>
                        <td><?php if ($data['finance_status'] == '0')
                                echo '待审核';
                            elseif ($data['finance_status'] == '1')
                                echo '财审不通过';
                            elseif ($data['finance_status'] == '2')
                                echo '财审通过';
                            elseif ($data['finance_status'] == '4')
                                echo '扣款完成';
                            ?>
                        </td>
                        <td>
                            <?php if (\Yii::$app->user->can('order/delete-preorder')) : ?>
                                <a title="删除" target="ajaxTodo" href="<?= Url::to(['order/delete-preorder', 'ids' => $data['id']]) ?>" class="btnDel">删除</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="panelBar">
            <div class="pages">
                <span>显示</span>
                <select class="combox" name="numPerPage" onchange="<?= $search ? 'dialogPageBreak' : 'navTabPageBreak' ?>({numPerPage:this.value})">
                    <option value="20" <?= $prePage == 20 ?   'selected' : '' ?>>20</option>
                    <option value="50" <?= $prePage == 50 ?   'selected' : '' ?>>50</option>
                    <option value="100" <?= $prePage == 100 ? 'selected' : '' ?>>100</option>
                    <option value="200" <?= $prePage == 200 ? 'selected' : '' ?>>200</option>
                    <option value="500" <?= $prePage == 500 ? 'selected' : '' ?>>500</option>
                    <option value="1000" <?= $prePage == 1000 ? 'selected' : '' ?>>1000</option>
                </select>
                <span>条，共<?= $dataCount ?>条</span>
            </div>
            <div class="pagination" rel='preorder-list' targetType="<?= $search ? 'dialog' : 'navTab' ?>" totalCount="<?= $dataCount ?>" numPerPage="<?= $prePage ?>" pageNumShown="10" currentPage="<?= $page ?>"></div>
        </div>
    </div>
</div>