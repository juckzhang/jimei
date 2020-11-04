<?php
use yii\helpers\Url;
use common\models\mysql\AdModel;
use common\helpers\CommonHelper;

$user = CommonHelper::customer();
?>
<div id="sidebar">
    <div class="toggleCollapse"><h2>主菜单</h2><div>收缩</div></div>

    <div class="accordion" fillSpace="sidebar">
        <div class="accordionHeader">
            <h2><span>Folder</span>数据管理</h2>
        </div>
        <div class="accordionContent">
            <ul class="tree treeFolder">
                <!-- 材质 -->
                <?php if(\Yii::$app->user->can('material')):?>
                <li><a>材质</a>
                    <ul>
                        <?php if(\Yii::$app->user->can('material/material-list')):?>
                        <li><a href="<?=Url::to(['material/material-list'])?>" target="navTab" rel="material-list">材质列表</a></li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>

                <!-- 素材 -->
                <?php if(\Yii::$app->user->can('theme')):?>
                    <li><a>素材管理</a>
                        <ul>
                            <?php if($user['related'] and !$user['multi']):?>
                                <?php if(\Yii::$app->user->can('theme/theme-list')):?>
                                    <li><a href="<?=Url::to(['theme/theme-list','other' => ['customer_id' => $user['customer_id']],'customer-name' => $user['customer_name'],'notMore' => 1])?>" target="navTab" rel="theme-list">图案列表</a></li>
                                <?php endif;?>
                            <?php else:?>
                                <?php if(\Yii::$app->user->can('customer/customer-list')):?>
                                    <li><a href="<?=Url::to(['customer/customer-list'])?>" target="navTab" rel="customer-list">客户列表</a></li>
                                <?php endif;?>
                            <?php endif;?>
                        </ul>
                    </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('meal')):?>
                    <li><a>套餐信息管理</a>
                        <ul>
                            <?php if(\Yii::$app->user->can('meal/meal-list')):?>
                                <li><a href="<?=Url::to(['meal/meal-list'])?>" target="navTab" rel="meal-list">套餐列表</a></li>
                            <?php endif;?>
                        </ul>
                    </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('phone')):?>
                    <li><a>机型管理</a>
                        <ul>
                            <?php if(\Yii::$app->user->can('phone/phone-list')):?>
                                <li><a href="<?=Url::to(['phone/phone-list'])?>" target="navTab" rel="phone-list">机型列表</a></li>
                            <?php endif;?>
                            <?php if(\Yii::$app->user->can('phone/brand-list')):?>
                                <li><a href="<?=Url::to(['phone/brand-list'])?>" target="navTab" rel="brand-list">品牌列表</a></li>
                            <?php endif;?>
                            <?php if(\Yii::$app->user->can('phone/relation-list')):?>
                                <li><a href="<?=Url::to(['phone/relation-list'])?>" target="navTab" rel="relation-list">机型材质关系</a></li>
                            <?php endif;?>
                        </ul>
                    </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('color')):?>
                    <li><a>颜色管理</a>
                        <ul>
                            <?php if(\Yii::$app->user->can('color/color-list')):?>
                                <li><a href="<?=Url::to(['color/color-list'])?>" target="navTab" rel="color-list">颜色列表</a></li>
                            <?php endif;?>
                        </ul>
                    </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('order')):?>
                    <li><a>订单管理</a>
                        <ul>
                            <?php if(\Yii::$app->user->can('order/distribution-list')):?>
                                <li><a href="<?=Url::to(['order/distribution-list'])?>" target="navTab" rel="distribution-list">配货单列表</a></li>
                            <?php endif;?>
                        </ul>
                    </li>
                <?php endif;?>

<!--                <li><a href="<=Url::to(['site/logout'])?>" target="navTab" rel="logout">退出</a></li>-->
            </ul>
        </div>

        <?php if(\Yii::$app->user->can('system')):?>
        <div class="accordionHeader">
            <h2><span>Folder</span>系统设置</h2>
        </div>
        <div class="accordionContent">
            <ul class="tree treeFolder">
            <?php if(\Yii::$app->user->can('system/source-list')):?>
                <li><a>资源管理</a>
                    <ul>
                        <li><a href="<?=Url::to(['system/source-list'])?>" target="navTab" rel="source-list">资源列表</a></li>
                        <?php if(\Yii::$app->user->can('system/edit-source')):?>
                        <li><a href="<?=Url::to(['system/edit-source'])?>" target="dialog" rel="edit-source">添加资源</a></li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('system/role-list')):?>
                <li><a>角色管理</a>
                    <ul>
                        <li><a href="<?=Url::to(['system/role-list'])?>" target="navTab" rel="role-list">角色列表</a></li>
                        <?php if(\Yii::$app->user->can('system/edit-role')):?>
                        <li><a href="<?=Url::to(['system/edit-role'])?>" target="dialog" rel="edit-role">添加角色 </a></li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>

                <?php if(\Yii::$app->user->can('system/user-list')):?>
                <li><a>管理员信息管理</a>
                    <ul>
                        <li><a href="<?=Url::to(['system/user-list'])?>" target="navTab" rel="user-list">管理员列表</a></li>
                        <?php if(\Yii::$app->user->can('system/edit-user')):?>
                        <li><a href="<?=Url::to(['system/edit-user'])?>" target="dialog" rel="edit-bord">添加管理员</a></li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>
            </ul>
        </div>
        <?php endif;?>
    </div>
</div>