<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29
 * Time: 14:15
 */
defined('YII_RUN') or exit('Access Denied');


use yii\widgets\LinkPager;

/* @var \app\models\User $user */

$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
$this->title = '订单详情';
$this->params['active_nav_group'] = 3;
?>
<style>
    tr > td:first-child {
        text-align: right;
        width: 100px;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div style="overflow-x: hidden">
                <div class="row">
                    <div class="col-12 col-md-6 mb-4">
                        <table class="table table-bordered">
                            <tr>
                                <td>订单号</td>
                                <td><?= $order['order_no'] ?></td>
                            </tr>
                            <tr>
                                <td>下单时间</td>
                                <td><?= date('Y-m-d H:i', $order['addtime']) ?></td>


                            </tr>
                            <tr>
                                <td>出发时间</td>
                                <td><?=$order['date']." ".$order['time']?></td>


                            </tr>
                            <tr>
                                <td>用户</td>
                                <td><?= $user['nickname'] ?></td>
                            </tr>
                            <tr>
                                <td>支付方式</td>
                                <td>
                                    <?php if($order['pay_type'] == 1):?>
                                        <span>微信支付</span>
                                    <?php else:?>
                                        <span>货到付款</span>
                                    <?php endif;?>
                                </td>
                            </tr>
                            <tr>
                                <td>联系人信息</td>
                                <td>
                                    <div>
                                        <span>联系人：<?= $order['name'] ?></span>
                                    </div>
                                    <div>
                                        <span>电话：<?= $order['mobile'] ?></span>
                                    </div>

                                    <?php if ($order['address']): ?>
                                        <div style="display: none">
                                            <span>联系地址：<?= $order['address'] ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div style="display: none;">
                                            <span>收货方式：上门自提</span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if ($order['express']): ?>
                                <tr style="display: none"
                                    <td>快递信息</td>
                                    <td>
                                        <div>
                                            <span>快递公司：<?= $order['express'] ?></span>
                                        </div>
                                        <div>
                                            <span>快递单号：<?= $order['express_no'] ?></span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="2" style="text-align: center">订单状态</td>
                            </tr>
                            <tr>
                                <td>付款状态</td>
                                <td>
                                    <?php if ($order['is_pay'] == 1): ?>
                                        <span class="badge badge-success">已付款</span>
                                    <?php else: ?>
                                        <span class="badge badge-default">未付款</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if ($order['is_pay'] == 1): ?>
                                <tr>
                                    <td>订单状态</td>
                                    <td>
                                        <?php if ($order['is_send'] == 1): ?>
                                            <span class="badge badge-success">已确认出发时间</span>
                                        <?php else: ?>
                                            <span class="badge badge-default">未确认出发时间</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($order['is_send'] == 1): ?>
                                <tr>
                                    <td>核销状态</td>
                                    <td>
                                        <?php if ($order['is_confirm'] == 1): ?>
                                            <span class="badge badge-success">已核销</span>
                                        <?php else: ?>
                                            <span class="badge badge-default">未核销</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($order['refund']): ?>
                                <tr>
                                    <td>售后状态</td>
                                    <td>
                                        <?php if ($order['refund'] == 0): ?>
                                            <span>待商家处理</span>
                                        <?php elseif ($order['refund'] == 1): ?>
                                            <span>同意并已退款</span>
                                        <?php elseif ($order['refund'] == 2): ?>
                                            <span>已同意换货</span>
                                        <?php elseif ($order['refund'] == 3): ?>
                                            <span>已拒绝退换货</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="2" style="text-align: center">订单金额</td>
                            </tr>
                            <tr>
                                <td>总金额<br></td>
                                <td><?= $order['total_price'] ?>元</td>
                            </tr>
                            <tr style="display: none">
                                <td>运费</td>
                                <td>
                                    <?php if ($order['express_price_1']): ?>
                                        <div><?= $order['express_price_1'] ?>元</div>
                                        <div class="text-danger">包邮，运费减免</div>
                                    <?php else: ?>
                                        <?= $order['express_price'] ?>元
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if ($order['use_coupon_id'] == 1): ?>
                                <tr>
                                    <td>优惠券优惠</td>
                                    <td><?= $order['coupon_sub_price'] ?>元</td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($order['integral_arr'] && $order['integral_arr']['forehead_integral']): ?>
                                <tr>
                                    <td>积分情况</td>
                                    <td>
                                        <div>积分使用：<?= $order['integral_arr']['forehead_integral'] ?></div>
                                        <div>积分抵扣：<?= $order['integral_arr']['forehead'] ?>元</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($order['discount'] && $order['discount'] != 10): ?>
                                <tr>
                                    <td>会员折扣</td>
                                    <td><?= $order['discount'] ?>折</td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($order['before_update_price']): ?>
                                <tr>
                                    <td>下单金额</td>
                                    <td><?= $order['before_update_price'] ?>元</td>
                                </tr>
                                <tr>
                                    <td>后台改价</td>
                                    <?php $money = $order['pay_price'] - $order['before_update_price']; ?>
                                    <td><?= $money < 0 ? "优惠：" . -$money . "元" : "加价：{$money}元" ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($order['discount'] && $order['discount'] != 10): ?>
                                <tr>
                                    <td>实付金额</td>
                                    <td><?= $order['pay_price'] ?>元</td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class="col-12 col-md-6 mb-4">
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="3" style="text-align: center">商品信息</td>
                            </tr>
                            <?php foreach ($goods_list as $index => $value): ?>
                                <tr>
                                    <td rowspan="4">商品<?= $index + 1 ?></td>
                                    <td class="text-right">商品名</td>
                                    <td><?= $value['name'] ?></td>
                                </tr>
                                <tr>
                                    <td>规格</td>
                                    <td>
                                        <div>
                                        <span class="text-danger">
                                            <?php $attr_list = json_decode($value['attr']); ?>
                                            <?php if (is_array($attr_list)):foreach ($attr_list as $attr): ?>
                                                <span class="mr-3"><?= $attr->attr_group_name ?>
                                                    :<?= $attr->attr_name ?></span>
                                            <?php endforeach;;endif; ?>
                                        </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>数量</td>
                                    <td><?= $value['num'] . $value['unit'] ?></td>
                                </tr>
                                <tr>
                                    <td>小计</td>
                                    <td><?= $value['total_price'] ?>元</td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if ($order_form): ?>
                                <tr>
                                    <td colspan="3"
                                        class="text-center"><?= \app\models\Option::get('form_name', $order['store_id'], 'admin', '表单信息') ?></td>
                                </tr>
                                <?php foreach ($order_form as $k => $v): ?>
                                    <tr>
                                        <td><?= $v['key'] ?></td>
                                        <td colspan="2"><?= $v['value'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td>备注</td>
                                    <td colspan="2"><?= $order['content'] ?></td>
                                </tr>
                            <?php endif; ?>
                        </table>
                        <table class="table table-bordered">

                            <thead style="text-align: center">

                            <tr>
                                <td colspan="3" style="text-align: center">保险信息</td>
                            </tr>
                            <tr><td style="text-align: center;">姓名</td><td>电话</td><td>身份证号</td></tr>
                            </thead>

                            <?php foreach ($baoxian_list as $baoxian): ?>
                              <tr>
                                <td style="text-align: left"><?= $baoxian['name']?></td>
                                <td class="text-left"><?= $baoxian['tel'] ?></td>
                                <td style="text-align: left"><?= $baoxian['idCard']?></td>
                            </tr>
                            <?php endforeach; ?>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>