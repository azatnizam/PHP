<?php

namespace Bjlag\Controllers;

use Bjlag\BaseController;
use Bjlag\Entities\ChannelEntity;
use Bjlag\Http\Forms\ChannelForm;
use Bjlag\Repositories\ChannelRepository;
use Bjlag\Services\StatisticsService;
use League\Route\Http\Exception\BadRequestException;
use League\Route\Http\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ChannelController extends BaseController
{
    /** @var \Bjlag\Repositories\ChannelRepository */
    private $channelRepository;

    /** @var \Bjlag\Services\StatisticsService */
    private $statisticsService;

    /**
     * ChannelController constructor.
     */
    public function __construct()
    {
        $this->channelRepository = new ChannelRepository();
        $this->statisticsService = new StatisticsService();
    }

    /**
     * Вывод всех каналов из БД.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(ServerRequestInterface $request): ResponseInterface
    {
        $channels = $this->channelRepository->find();
        $topIds = $this->statisticsService->getTop5Channels()->getChannelIds();

        return $this->getResponseHtml('channel/index', [
            'channels' => $channels,
            'top' => $this->channelRepository->findByIds($topIds),
        ]);
    }

    /**
     * Вывод подробной информации о канале.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param array $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \League\Route\Http\Exception\BadRequestException
     * @throws \League\Route\Http\Exception\NotFoundException
     */
    public function viewAction(ServerRequestInterface $request, array $args): ResponseInterface
    {
        if (!isset($args['id'])) {
            throw new BadRequestException();
        }

        $channel = $this->channelRepository->findById($args['id']);
        if ($channel === null) {
            throw new NotFoundException('Канал не найден');
        }

        $statistics = $this->statisticsService->calcTotalLikesAndDislikesByChannelId($channel->getId());

        $data['channel'] = $channel;
        $data['statistics'] = [
            'like_total' => $statistics->getLikesTotal(),
            'dislike_total' => $statistics->getDislikesTotal(),
        ];

        return $this->getResponseHtml('channel/view', $data);
    }

    /**
     * Добавление нового канала.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Laminas\Diactoros\Response\JsonResponse
     * @throws \League\Route\Http\Exception\UnprocessableEntityException
     */
    public function addAction(ServerRequestInterface $request): ResponseInterface
    {
        $form = (new ChannelForm($request->getParsedBody()))->fillAndValidate();
        $channelEntity = ChannelEntity::create($form);

        return $this->getResponseJson([
            'is_succeed' => true,
            'id' => $channelEntity->save(),
        ], 200);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \League\Route\Http\Exception\BadRequestException
     * @throws \League\Route\Http\Exception\NotFoundException
     * @throws \League\Route\Http\Exception\UnprocessableEntityException
     */
    public function editAction(ServerRequestInterface $request): ResponseInterface
    {
        $rawData = $request->getParsedBody();

        if (!isset($rawData['filter']['id']) || !isset($rawData['data'])) {
            throw new BadRequestException();
        }

        $channelEntity = $this->channelRepository->findById($rawData['filter']['id']);
        if ($channelEntity === null) {
            throw new NotFoundException('Канал не найден');
        }

        $form = (new ChannelForm($rawData['data']))->fillAndValidate();

        $channelEntity
            ->setUrl($form->getUrl())
            ->setName($form->getName())
            ->setDescription($form->getDescription())
            ->setBanner($form->getBanner())
            ->setCountry($form->getCountry())
            ->setRegistrationData($form->getRegistrationData())
            ->setNumberViews($form->getNumberViews())
            ->setNumberSubscribes($form->getNumberSubscribes())
            ->setLinks($form->getLinks());

        return $this->getResponseJson([
            'is_succeed' => (bool)$channelEntity->save(),
        ], 200);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \League\Route\Http\Exception\BadRequestException
     * @throws \League\Route\Http\Exception\NotFoundException
     */
    public function deleteAction(ServerRequestInterface $request): ResponseInterface
    {
        $rawData = $request->getParsedBody();

        if (!isset($rawData['filter']['id'])) {
            throw new BadRequestException();
        }

        $channelEntity = $this->channelRepository->findById($rawData['filter']['id']);
        if ($channelEntity === null) {
            throw new NotFoundException();
        }

        return $this->getResponseJson([
            'is_succeed' => $channelEntity->delete(),
        ], 200);
    }
}
