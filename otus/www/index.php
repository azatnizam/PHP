<?php

use App\Controllers\EventController;
use App\Controllers\EventCrudController;
use App\Controllers\YoutubeChannelCrudController;
use App\Controllers\YoutubeChannelStatisticController;
use App\Controllers\YoutubeVideoCrudController;
use Classes\Database\DbConnectionImpl;
use Classes\Database\RedisDb;
use Classes\Repositories\EventRepositoryImpl;
use Classes\Repositories\YoutubeChannelRepositoryImpl;
use Classes\Repositories\YoutubeVideoRepositoryImpl;
use DI\Container;
use Services\ChannelStatisticServiceImpl;
use Services\EventServiceImpl;
use Services\YoutubeChannelServiceImpl;
use Services\YoutubeVideoServiceImpl;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$container = new Container();

AppFactory::setContainer($container);
$app = AppFactory::create();


$container->set(YoutubeVideoRepositoryImpl::class, static function (Container $c) {
    $connection = DbConnectionImpl::getConnection(new \Classes\Database\MongoDb());
    return new YoutubeVideoRepositoryImpl($connection);
});

$container->set(YoutubeChannelRepositoryImpl::class, static function (Container $c) {
    $connection = DbConnectionImpl::getConnection(new \Classes\Database\MongoDb());
    return new YoutubeChannelRepositoryImpl($connection);
});

$container->set(YoutubeVideoServiceImpl::class, static function (Container $c) {
    $repository = $c->get(YoutubeVideoRepositoryImpl::class);
    return new YoutubeVideoServiceImpl($repository);
});

$container->set(YoutubeChannelServiceImpl::class, static function (Container $c) {
    $repository = $c->get(YoutubeChannelRepositoryImpl::class);
    return new YoutubeChannelServiceImpl($repository);
});

$container->set(YoutubeVideoCrudController::class, static function (Container $c) {
    $videoService = $c->get(YoutubeVideoServiceImpl::class);
    return new YoutubeVideoCrudController($videoService);
});

$container->set(YoutubeChannelCrudController::class, static function (Container $c) {
    $videoService = $c->get(YoutubeChannelServiceImpl::class);
    return new YoutubeChannelCrudController($videoService);
});

$container->set(ChannelStatisticServiceImpl::class, static function (Container $c) {
    $channelRepository = $c->get(YoutubeChannelRepositoryImpl::class);
    $videoRepository = $c->get(YoutubeVideoRepositoryImpl::class);
    return new ChannelStatisticServiceImpl($channelRepository, $videoRepository);
});

$container->set(YoutubeChannelStatisticController::class, static function (Container $c) {
    $channelStatisticServiceImpl = $c->get(ChannelStatisticServiceImpl::class);
    return new YoutubeChannelStatisticController($channelStatisticServiceImpl);
});

$container->set(EventServiceImpl::class, static function (Container $c) {
    $eventRepository = $c->get(EventRepositoryImpl::class);
    return new EventServiceImpl($eventRepository);
});

$container->set(EventRepositoryImpl::class, static function (Container $c) {
    $dbClient = DbConnectionImpl::getConnection(new RedisDb());
    return new EventRepositoryImpl($dbClient);
});

$container->set(EventCrudController::class, static function (Container $c) {
    $eventService = $c->get(EventServiceImpl::class);
    return new EventCrudController($eventService);
});

$container->set(EventController::class, static function (Container $c) {
    $eventService = $c->get(EventServiceImpl::class);
    return new EventController($eventService);
});

//Youtube
$app->post('/video/create', 'App\Controllers\YoutubeVideoCrudController:createVideo');
$app->post('/video/delete', 'App\Controllers\YoutubeVideoCrudController:deleteVideoById');
$app->post('/channel/create', 'App\Controllers\YoutubeChannelCrudController:createChannel');
$app->post('/channel/delete', 'App\Controllers\YoutubeChannelCrudController:deleteChannelById');

$app->get('/channels/video-rating/{channelId}/{limit}', 'App\Controllers\YoutubeChannelStatisticController:TotalChannelVideosLikesNumber');
$app->get('/channels/top/{limit}', 'App\Controllers\YoutubeChannelStatisticController:TopChannelsVideosLikesDislikesRating');

//Events
$app->post('/event/create', 'App\Controllers\EventCrudController:create');
$app->post('/event/delete', 'App\Controllers\EventCrudController:delete');
$app->post('/event/priority', 'App\Controllers\EventController:getPriority');

$app->run();
