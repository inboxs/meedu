<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 */

Route::get('/', 'IndexController@index')->name('index');
Route::get('/user/protocol', 'IndexController@userProtocol')->name('user.protocol');
Route::get('/user/private_protocol', 'IndexController@userPrivateProtocol')->name('user.private_protocol');
Route::get('/aboutus', 'IndexController@aboutus')->name('aboutus');
// 登录
Route::get('/login', 'LoginController@showLoginPage')->name('login');
Route::post('/login', 'LoginController@passwordLoginHandler')->middleware(['throttle:30,1']);
// 微信公众号扫码登录
Route::get('/login/wechat/scan', 'LoginController@wechatScanLogin')->name('login.wechat.scan');
Route::get('/login/wechat/scan/query', 'LoginController@wechatScanLoginQuery')->name('login.wechat.scan.query');
// 微信公众号授权登录
Route::get('/login/wechat/oauth', 'LoginController@wechatLogin')->name('login.wechat.oauth');
Route::get('/login/wechat/oauth/callback', 'LoginController@wechatLoginCallback')->name('login.wechat.oauth.callback');
// 社交登录
Route::get('/login/{app}', 'LoginController@socialLogin')->name('socialite');
Route::get('/login/{app}/callback', 'LoginController@socialiteLoginCallback')->name('socialite.callback');
// 注册
Route::get('/register', 'RegisterController@showRegisterPage')->name('register');
Route::post('/register', 'RegisterController@passwordRegisterHandler')->middleware(['sms.check', 'throttle:30,1']);
// 找回密码
Route::get('/password/reset', 'ForgotPasswordController@showPage')->name('password.request');
Route::post('/password/reset', 'ForgotPasswordController@handler')->middleware(['throttle:10,1', 'sms.check']);

// 发送短信
Route::post('/sms/send', 'SmsController@send')->name('sms.send');

// 课程列表
Route::get('/courses', 'CourseController@index')->name('courses');
// 课程详情
Route::get('/course/{id}/{slug}', 'CourseController@show')->name('course.show');
Route::get('/course/attach/{id}/download', 'CourseController@attachDownload')->name('course.attach.download')->middleware(['auth']);
// 视频详情
Route::get('/course/{course_id}/video/{id}/{slug}', 'VideoController@show')->name('video.show');
// 搜索
Route::get('/search', 'SearchController@searchHandler')->name('search');

// VIP
Route::get('/vip', 'RoleController@index')->name('role.index');
// 支付回调
Route::post('/payment/callback/{payment}', 'PaymentController@callback')->name('payment.callback');

// 公告
Route::get('/announcement/{id}', 'AnnouncementController@show')->name('announcement.show');

// 微信JSAPI支付
Route::get('/member/order/pay/wechat/jsapi/page', 'OrderController@wechatJSAPI')->name('order.pay.wechat.jsapi');
// 手动打款支付
Route::get('/member/order/pay/handPay', 'OrderController@handPay')->name('order.pay.handPay');

Route::group([
    'prefix' => '/member',
    'middleware' => ['auth', 'login.status.check', 'mobile.bind.check'],
], function () {
    // 用户首页
    Route::get('/', 'MemberController@index')->name('member');

    // 安全退出
    Route::post('/logout', 'MemberController@logout')->name('logout');

    // 手机号绑定
    Route::get('/mobile_bind', 'MemberController@showMobileBindPage')->name('member.mobile.bind');
    Route::post('/mobile_bind', 'MemberController@mobileBindHandler')->middleware('sms.check');

    // VIP会员购买记录
    Route::get('/join_role_records', 'MemberController@showJoinRoleRecordsPage')->name('member.join_role_records');

    // 我的消息
    Route::get('/messages', 'MemberController@showMessagesPage')->name('member.messages');

    // 我的点播课程
    Route::get('/courses', 'MemberController@showBuyCoursePage')->name('member.courses');

    // 我的点播视频
    Route::get('/course/videos', 'MemberController@showBuyVideoPage')->name('member.course.videos');

    // 我的订单
    Route::get('/orders', 'MemberController@showOrdersPage')->name('member.orders');

    // 社交登录
    Route::get('/socialite/wechat/bind', 'MemberController@showWechatBind')->name('member.socialite.wechat.bind');
    Route::get('/socialite/{app}/bind', 'MemberController@socialiteBind')->name('member.socialite.bind');
    Route::get('/socialite/{app}/bind/callback', 'MemberController@socialiteBindCallback')->name('member.socialite.bind.callback');
    Route::get('/socialite/{app}/delete', 'MemberController@cancelBindSocialite')->name('member.socialite.delete');

    // 邀请码
    Route::get('/promo_code', 'MemberController@showPromoCodePage')->name('member.promo_code');

    // 积分明细
    Route::get('/credit1_records', 'MemberController@credit1Records')->name('member.credit1_records');

    // 我的资料
    Route::get('/profile', 'MemberController@showProfilePage')->name('member.profile');

    // 图片上传
    Route::post('/upload/image', 'UploadController@imageHandler')->name('upload.image');

    // 购买课程
    Route::get('/course/{id}/buy', 'CourseController@showBuyPage')->name('member.course.buy');
    Route::post('/course/{id}/buy', 'CourseController@buyHandler');

    // 购买视频
    Route::get('/video/{id}/buy', 'VideoController@showBuyPage')->name('member.video.buy');
    Route::post('/video/{id}/buy', 'VideoController@buyHandler');

    // 购买VIP
    Route::get('/vip/{id}/buy', 'RoleController@showBuyPage')->name('member.role.buy');
    Route::post('/vip/{id}/buy', 'RoleController@buyHandler');

    // 支付成功界面
    Route::get('/order/pay/success', 'OrderController@paySuccess')->name('order.pay.success');
    // 发起支付
    Route::get('/order/pay', 'OrderController@pay')->name('order.pay');
    // 微信PC扫码支付
    Route::get('/order/pay/wechat/{order_id}/scan', 'OrderController@wechatScan')->name('order.pay.wechat');

    Route::group(['prefix' => 'ajax'], function () {
        // 点播课程评论
        Route::post('/course/{id}/comment', 'AjaxController@courseCommentHandler')->name('ajax.course.comment');
        // 点播视频评论
        Route::post('/video/{id}/comment', 'AjaxController@videoCommentHandler')->name('ajax.video.comment');
        // 视频观看时长统计
        Route::post('/video/{id}/watch/record', 'AjaxController@recordVideo')->name('ajax.video.watch.record');
        // promoCode有效性检测
        Route::post('/promoCodeCheck', 'AjaxController@promoCodeCheck')->name('ajax.promo_code.check');
        // 修改密码
        Route::post('/password/change', 'AjaxController@changePassword')->name('ajax.password.change');
        // 修改头像
        Route::post('/avatar/change', 'AjaxController@changeAvatar')->name('ajax.avatar.change');
        // 修改昵称
        Route::post('/nickname/change', 'AjaxController@changeNickname')->name('ajax.nickname.change');
        // 标记站内消息已读
        Route::post('/message/read', 'AjaxController@notificationMarkAsRead')->name('ajax.message.read');
        Route::post('/message/read/all', 'AjaxController@notificationMarkAllAsRead')->name('ajax.message.read.all');
        // 提现
        Route::post('/inviteBalanceWithdraw', 'AjaxController@inviteBalanceWithdraw')->name('ajax.invite_balance.withdraw');
        // 收藏课程
        Route::post('/course/like/{id}', 'AjaxController@likeACourse')->name('ajax.course.like');
        // 用户资料编辑
        Route::post('/profile', 'AjaxController@profileUpdate')->name('ajax.member.profile.update');
        // 手机号绑定
        Route::post('/mobile_bind', 'AjaxController@mobileBind')->name('ajax.mobile.bind')->middleware(['sms.check']);
        // 视频播放地址
        Route::post('/video/urls', 'AjaxController@getPlayUrls')->name('ajax.video.playUrls');
    });
});

Route::group(['prefix' => 'ajax'], function () {
    // 密码登录
    Route::post('/auth/login/password', 'AjaxController@passwordLogin')->name('ajax.login.password');

    Route::group(['middleware' => ['sms.check']], function () {
        // 手机号短信登录
        Route::post('/auth/login/mobile', 'AjaxController@mobileLogin')->name('ajax.login.mobile');
        // 注册
        Route::post('/auth/register', 'AjaxController@register')->name('ajax.register');
        // 密码重置
        Route::post('/auth/password/reset', 'AjaxController@passwordReset')->name('ajax.password.reset');
    });
});
