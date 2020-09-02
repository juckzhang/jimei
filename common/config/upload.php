<?php
use common\constants\CodeConstant;
return [
    'picture' => [
        'extensions' => null,
        'mimeTypes'  => null,
        'minSize' => null,
        'maxSize' => null,
        'uploadRequired' => CodeConstant::UPLOAD_FILE_REQUIRED_ERROR,
        'tooBig'  => CodeConstant::UPLOAD_FILE_SIZE_BIG,
        'tooSmall' => CodeConstant::UPLOAD_FILE_SIZE_SMALL,
        'tooMany' => CodeConstant::UPLOAD_FILE_TOO_MANY,
        'wrongExtension' => CodeConstant::UPLOAD_FILE_EXTENSION_ERROR,
        'wrongMimeType' => CodeConstant::UPLOAD_FILE_MIME_ERROR,
        'path'  => realpath(__DIR__ . '/../upload'),
        'url'   => 'http://localhost',
        'remoteUpload' => false,
        'recursive' => true,
    ],
];