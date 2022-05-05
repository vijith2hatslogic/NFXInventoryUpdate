<?php

declare(strict_types=1);

namespace NFX\InventoryUpdate\Service;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Content\Media\MediaCollection;
use Shopware\Core\System\CustomField\CustomFieldEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Shopware\Core\Content\Media\Pathname\UrlGeneratorInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

class Media
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    protected $urlGenerator;
    protected $context;

    public function __construct(ContainerInterface $container, UrlGeneratorInterface $urlGenerator)
    {
        $this->container = $container;
        $this->context =  Context::createDefaultContext();
        $this->mediaRepository = $container->get('media.repository');
        $this->urlGenerator = $urlGenerator;
    }

    public function searchMedia(array $ids, Context $context): MediaCollection
    {
        if (empty($ids)) {
            return new MediaCollection();
        }

        $criteria = new Criteria($ids);

        /** @var MediaCollection $media */
        $media = $this->mediaRepository
            ->search($criteria, $context)
            ->getEntities();

        return $media;
    }

    public function getMediaUrl($id)
    {
        $mediaUrl = '';

        if (!empty($id)) {
            $mediaItem = $this->searchMedia([$id], $this->context)->first();
            $mediaUrl = $this->encodeMediaUrl($mediaItem);
        }

        return $mediaUrl;
    }

    public function encodeUrl(?string $mediaUrl): ?string
    {
        if ($mediaUrl === null) {
            return null;
        }

        $urlInfo = parse_url($mediaUrl);
        $segments = explode('/', $urlInfo['path']);

        foreach ($segments as $index => $segment) {
            $segments[$index] = rawurlencode($segment);
        }

        $path = implode('/', $segments);
        if (isset($urlInfo['query'])) {
            $path .= "?{$urlInfo['query']}";
        }

        $encodedPath = '';

        if (isset($urlInfo['scheme'])) {
            $encodedPath = "{$urlInfo['scheme']}://";
        }

        if (isset($urlInfo['host'])) {
            $encodedPath .= "{$urlInfo['host']}";
        }

        if (isset($urlInfo['port'])) {
            $encodedPath .= ":{$urlInfo['port']}";
        }

        return $encodedPath . $path;
    }

    public function encodeMediaUrl(?MediaEntity $media): ?string
    {
        if ($media === null || !$media->hasFile()) {
            return null;
        }

        return $this->encodeUrl($media->getUrl());
    }
}
