<?php
use common\constants\CodeConstant;
return [
    /** 公用模块 **/

    /** 出错信息 */
    CodeConstant::UNKNOWN_ERROR        => '位置错误',
    CodeConstant::PARAM_ERROR          => '参数错误',
    CodeConstant::REQUEST_METHOD_ERROR => '请求方式错误',
    CodeConstant::RPC_PARAM_ERROR      => 'rpc调用请求参数错误',
    CodeConstant::RPC_APP_FORBIDDEN_A  => 'openID错误',
    CodeConstant::RPC_APP_FORBIDDEN_S  => 'rpc签名错误',
    CodeConstant::RPC_CLASS_FORBIDDEN  => '禁止访问的服务',
    CodeConstant::RPC_CLASS_NOT_EXIST  => 'class 不存在',
    CodeConstant::RPC_METHOD_NOT_EXIST => 'method 不存在',
    CodeConstant::RPC_METHOD_FORBIDDEN => '禁止访问的方法',
    CodeConstant::RPC_FAILED           => 'rpc失败',
    CodeConstant::SOURCE_NOT_EXISTS    => '资源不存在',
    CodeConstant::USER_TOKEN_NOT_EXISTS     => 'TOKEN 无效!',
    CodeConstant::PERMISSION_DENIED    => '对不起,你无权进行此项操作',

    /** upload file **/
    CodeConstant::UPLOAD_FILE_MIME_ERROR      => '文件mime类型错误',
    CodeConstant::UPLOAD_FILE_SIZE_BIG        => '文件过大',
    CodeConstant::UPLOAD_FILE_SIZE_SMALL      => '文件太小',
    CodeConstant::UPLOAD_FILE_EXTENSION_ERROR => '文件扩展名错误',
    CodeConstant::UPLOAD_FILE_REQUIRED_ERROR  => '文件错误',
    CodeConstant::UPLOAD_FILE_TOO_MANY        => '上传文件数量过多',
    CodeConstant::UPLOAD_FILE_FAILED          => '文件上传失败',

    //video
    CodeConstant::MEDIA_COMMENT_FAILED        => '专辑评论失败',
    CodeConstant::COLLECT_MEDIA_FAILED        => '收藏失败',
    CodeConstant::CANCEL_COLLECT_MEDIA_FAILED => '取消收藏失败',

    CodeConstant::USER_LOGIN_FAILED           => '用户名或密码错误!',
];