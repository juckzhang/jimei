<?php
namespace common\constants;

class CodeConstant
{
    /**  公共模块 **/

    /** 操作成功 */
    const SUCCESS              = 200;  //成功状态吗

    /** 处理失败 */
    const UNKNOWN_ERROR        = -1;   //未知错误
    const PARAM_ERROR          = - 2;  //参数错误
    const REQUEST_METHOD_ERROR = -3;   //请求方式错误
    const RPC_PARAM_ERROR      = -4;   //rpc调用参数错误
    const RPC_APP_FORBIDDEN_A  = -5;   //rpcOpenID错误
    const RPC_APP_FORBIDDEN_S  = -6;   //rpc签名错误
    const RPC_CLASS_FORBIDDEN  = -7;   //禁止访问
    const RPC_CLASS_NOT_EXIST  = -8;   //类不存在
    const RPC_METHOD_NOT_EXIST = -9;   //方法不存在
    const RPC_METHOD_FORBIDDEN = -10;  //禁止访问的
    const RPC_FAILED           = -11;  //rpc请求失败
    const SOURCE_NOT_EXISTS    = -12;  //资源不存在
    const USER_TOKEN_NOT_EXISTS = -25;//token不存在
    const PERMISSION_DENIED     = -26; //没有权限访问
    const USER_LOGIN_FAILED    = -27; //用户名或密码错误
    const USER_LOGIN_STATUS    = -28; //用户未登入

    /**  upload file **/
    const UPLOAD_FILE_BASE            = -100;
    const UPLOAD_FILE_MIME_ERROR      = self::UPLOAD_FILE_BASE - 1; //文件类型错误
    const UPLOAD_FILE_SIZE_BIG        = self::UPLOAD_FILE_BASE - 2; //文件太大
    const UPLOAD_FILE_SIZE_SMALL      = self::UPLOAD_FILE_BASE - 3; //文件太小
    const UPLOAD_FILE_EXTENSION_ERROR = self::UPLOAD_FILE_BASE - 4; //扩展名错误
    const UPLOAD_FILE_REQUIRED_ERROR  = self::UPLOAD_FILE_BASE - 5; //文件错误
    const UPLOAD_FILE_TOO_MANY        = self::UPLOAD_FILE_BASE - 6; //上传文件数量过多
    const UPLOAD_FILE_FAILED          = self::UPLOAD_FILE_BASE -7;  //文件上传失败

    const MEDIA_BASE = -200;
    const MEDIA_COMMENT_FAILED        = self::MEDIA_BASE - 1;//评论失败
    const COLLECT_MEDIA_FAILED        = self::MEDIA_BASE - 2; //收藏失败
    const CANCEL_COLLECT_MEDIA_FAILED = self::MEDIA_BASE - 3;//取消收藏失败
    const DOWNLOAD_MEDIA_FAILED      = self::MEDIA_BASE - 4;//订阅失败
    const EDIT_MEDIA_FAILED           = self::MEDIA_BASE  - 5;//编辑视频失败
    const FEED_BACK_FAILED              = self::MEDIA_BASE - 6; //反馈失败
}