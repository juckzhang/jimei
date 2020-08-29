<?php
namespace common\constants;

class EventQueueConstant
{
    const AFTER_ACTION                = 'after_action';
    /** user module event */
    const AFTER_REGISTER              = 'after_register';               //用户注册事件
    const AFTER_LOGIN                 = 'after_login';                  //用户登录事件
    const AFTER_FIND_PASSWORD         = 'after_find_password';          //找回密码事件
    const AFTER_SIGN                  = 'after_sign';                   //用户签到事件

    /** bbs module event */
    const AFTER_READ_POST             = 'after_read_post';              //用户阅读帖子事件
    const AFTER_LIKE_POST             = 'after_like_post';              //用户为帖子点赞事件队列
    const AFTER_CANCEL_LIKE_POST      = 'after_cancel_like_post';       //用户取消帖子的点赞事件队列
    const AFTER_COLLECTED_POST        = 'after_collected_post';         //收藏帖子事件队列
    const AFTER_CANCEL_COLLECTED_POST = 'after_cancel_collected_post';  //取消帖子收藏事件列表
    const AFTER_CREATE_POST           = 'after_create_post';            //发帖事件队列
    const AFTER_POST_COMMENT          = 'after_post_comment';           //发表帖子评论事件队列
    const AFTER_DELETE_POST_COMMENT   = 'after_delete_post_comment';    //删除评论
    const AFTER_DELETE_POST           = 'after_delete_post';            //删除帖子

    /** follow module */
    const AFTER_USER_FOLLOW           = 'after_user_follow';           //关注用户事件队列
    const AFTER_CANCEL_USER_FOLLOW    = 'after_cancel_user_follow';    //取消用户关注事件队列

    const AFTER_PLATE_FOLLOW          = 'after_plate_follow';          //板块关注事件
    const AFTER_CANCEL_PLATE_FOLLOW   = 'after_cancel_plate_follow';  //取消板块关注事件
    const AFTER_READ_PLATE            = 'after_read_plate';           //浏览板块事件

    /** 玩剧 */
    const AFTER_COLLECTED_MATERIAL    = 'after_collected_material';             //收藏素材事件队列
    const AFTER_CANCEL_COLLECTED_MATERIAL = 'after_cancel_collected_material';  //取消素材收藏事件队列

    /** 用户反馈 */
    const AFTER_USER_FEEDBACK         =  'after_user_feedback';       //用户反馈队列
}