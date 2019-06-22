<?php

use Nrg\Auth\Abstraction\AuthControl;
use Nrg\Auth\Action\LoginAction;
use Nrg\Auth\Middleware\Authorization;
use Nrg\Auth\Persistence\Abstraction\UserRepository;
use Nrg\Auth\Persistence\Repository\JsonUserRepository;
use Nrg\Auth\Service\JwtAuthControl;
use Nrg\FileManager\Action\Directory\ReadDirectoryAction;
use Nrg\FileManager\Action\File\DeleteFileAction;
use Nrg\FileManager\Action\File\OpenFileAction;
use Nrg\Uploader\Action\DownloadFileAction;
use Nrg\Uploader\Action\UploadFileAction;
use Nrg\FileManager\Persistence\Abstraction\FileRepository;
use Nrg\Http\Abstraction\ResponseEmitter;
use Nrg\Http\Abstraction\RouteProvider;
use Nrg\Http\Event\HttpExchangeEvent;
use Nrg\Http\Middleware\AllowCors;
use Nrg\Http\Middleware\ErrorHandler;
use Nrg\Http\Middleware\ParseJsonRequest;
use Nrg\Http\Middleware\SerializeJsonResponse;
use Nrg\Http\Middleware\OffPrettyUrls;
use Nrg\Http\Middleware\EmitResponse;
use Nrg\Http\Middleware\RunAction;
use Nrg\Http\Service\HttpResponseEmitter;
use Nrg\Http\Service\HttpRouteProvider;
use Nrg\I18n\Abstraction\Translator;
use Nrg\I18n\Service\I18nTranslator;
use Nrg\Rx\Abstraction\EventProvider;
use Nrg\Rx\Service\RxEventProvider;
use Nrg\Uploader\Action\ConfigAction;
use Nrg\Uploader\Middleware\UseIfDemo;
use Nrg\Uploader\Persistence\Repository\UploadedFileRepository;
use Nrg\Uploader\Service\AppConfig;
use Nrg\Utility\Abstraction\Config;

return [
    'routes' => [
        '/login' => LoginAction::class,
        '/config' => ConfigAction::class,
        '/upload' => UploadFileAction::class,
        '/download' => DownloadFileAction::class,
        '/open' => OpenFileAction::class,
        '/list' => ReadDirectoryAction::class,
        '/delete' => DeleteFileAction::class,
    ],
    'services' => [
        EventProvider::class => RxEventProvider::class,
        RouteProvider::class => HttpRouteProvider::class,
        ResponseEmitter::class => HttpResponseEmitter::class,
        Translator::class => I18nTranslator::class,
        AuthControl::class => JwtAuthControl::class,
        UserRepository::class => [
            JsonUserRepository::class,
            'path' => realpath(__DIR__.'/users.json'),
        ],
        Config::class => [
            AppConfig::class,
            'path' => realpath(__DIR__.'/../config.json'),
            'publicKeys' => [
                'authorization',
                'directFileAccess',
                'directFileUrl',
                'maxSize',
                'denyExtensions',
                'allowExtensions',
            ],
        ],
        FileRepository::class => [
            UploadedFileRepository::class,
            'id' => 'uploads',
            'filter' => [
                '.htaccess',
            ],
        ],
    ],
    'events' => [
        HttpExchangeEvent::class => [
            UseIfDemo::class,
            AllowCors::class,
            ErrorHandler::class,
            OffPrettyUrls::class,
            ParseJsonRequest::class,
            [
                Authorization::class,
                'freeAccessRoutes' => [
                    '/login',
                    '/config',
                ],
            ],
            RunAction::class,
            SerializeJsonResponse::class,
            EmitResponse::class,
        ],
    ],
];
