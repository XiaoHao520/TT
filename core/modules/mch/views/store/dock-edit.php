<?php
defined('YII_RUN') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:36
 */

$urlManager = Yii::$app->urlManager;
$this->title = '码头编辑';
$this->params['active_nav_group'] = 3;
?>

<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=key=OB4BZ-D4W3U-B7VVO-4PJWW-6TKDJ-WPB77"></script>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post" return="<?= $urlManager->createUrl(['mch/store/dock']) ?>">


            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">码头名称：</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[name]" value="<?= $list['name'] ?>">
                </div>
            </div>



                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class=" col-form-label required">码头经度：</label>
                    </div>
                    <div class="col-6">
                        <input class="form-control" type="text" name="model[longitude]"
                               value="<?= $list['longitude'] ?>">
                        <div class="fs-sm">码头经纬度可以在地图上选择，也可以自己添加</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">码头纬度：</label>
                    </div>
                    <div class="col-6">
                        <input class="form-control" type="text" name="model[latitude]"
                               value="<?=$list['latitude'] ?>">
                        <div class="fs-sm">码头经纬度可以在地图上选择，也可以自己添加</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">具体位置：</label>
                    </div>
                    <div class="col-6">
                        <input class="form-control" type="text" name="model[address]"
                               value="<?= $list['address'] ?>">
                        <div class="fs-sm">商品位置可以在地图上选择，也可以自己添加</div>
                    </div>
                </div>
                <div class="form-group row">
                    <div style="display: inline-block;vertical-align: top;width: 45%">
                        <div class="form-group row map">
                            <div class="offset-2 col-9">
                                <div class="input-group" style="margin-top: 20px;">
                                    <input class="form-control region" type="text" placeholder="城市">
                                    <span class="input-group-addon ">和</span>
                                    <input class="form-control keyword" type="text" placeholder="关键字">
                                    <a class="input-group-addon search" href="javascript:">搜索</a>
                                </div>
                                <div class="text-info">搜索时城市和关键字必填</div>
                                <div class="text-info">点击地图上的蓝色点，获取经纬度</div>
                                <div class="text-danger map-error mb-3" style="display: none">错误信息</div>
                                <div id="container" style="min-width:600px;min-height:600px;"></div>
                            </div>
                        </div>
                    </div>
                </div>







            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).on('change', '.parent', function () {
        var p = $(this).val();
        if (p == '0') {
            $('.advert').show();
        } else {
            $('input[name="model[advert_url]"]').val('').trigger('change');
            $('input[name="model[advert_pic]"]').val('').trigger('change');
            $('input[name="model[advert_pic]"]').next('.image-picker-view').css('background-image', 'url("")');
            $('.advert').hide();
        }
    })
</script>

<script>

    var searchService, map, markers = [];
    //        window.onload = function(){
    //直接加载地图
    //初始化地图函数  自定义函数名init
    function init() {
        //定义map变量 调用 qq.maps.Map() 构造函数   获取地图显示容器
        var map = new qq.maps.Map(document.getElementById("container"), {
            center: new qq.maps.LatLng(39.916527, 116.397128),      // 地图的中心地理坐标。
            zoom: 15                                                 // 地图的中心地理坐标。
        });
        var latlngBounds = new qq.maps.LatLngBounds();
        //调用Poi检索类
        searchService = new qq.maps.SearchService({
            complete: function (results) {
                var pois = results.detail.pois;
                $('.map-error').hide();
                if (!pois) {
                    $('.map-error').show().html('关键字搜索不到，请重新输入');
                    return;
                }
                for (var i = 0, l = pois.length; i < l; i++) {
                    (function (n) {
                        var poi = pois[n];
                        latlngBounds.extend(poi.latLng);
                        var marker = new qq.maps.Marker({
                            map: map,
                            position: poi.latLng,
                        });

                        marker.setTitle(n + 1);

                        markers.push(marker);
                        //添加监听事件
                        qq.maps.event.addListener(marker, 'click', function (e) {
                            var address = poi.address;
                            console.log(address);
                            $("input[name='model[address]']").val(address);
                            $("input[name='model[longitude]']").val(e.latLng.lng);
                            $("input[name='model[latitude]']").val(e.latLng.lat);
                        });
                    })(i);
                }
                map.fitBounds(latlngBounds);
            }
        });
    }

    //清除地图上的marker
    function clearOverlays(overlays) {
        var overlay;
        while (overlay = overlays.pop()) {
            overlay.setMap(null);
        }
    }

    function searchKeyword() {
        var keyword = $(".keyword").val();
        var region = $(".region").val();
        clearOverlays(markers);
        searchService.setLocation(region);
        searchService.search(keyword);
    }

    //调用初始化函数地图
    init();


    //        }
</script>
<script>
    $(document).on('click', '.search', function () {
        searchKeyword();
    })
</script>