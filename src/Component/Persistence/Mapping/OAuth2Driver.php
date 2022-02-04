<?php

namespace App\Component\Persistence\Mapping;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\Persistence\Mapping\ClassMetadata;
use League\Bundle\OAuth2ServerBundle\Model\AccessToken;
use League\Bundle\OAuth2ServerBundle\Persistence\Mapping\Driver as BaseDriver;

class OAuth2Driver extends BaseDriver
{
    protected string $clientClass;
    protected bool $persistAccessToken;

    private function buildAccessTokenMetadata(ClassMetadata $metadata): void
    {
        (new ClassMetadataBuilder($metadata))
            ->setTable('oauth2.access_token')
            ->createField('identifier', 'string')->makePrimaryKey()->length(80)->option('fixed', true)->build()
            ->addField('expiry', 'datetime_immutable')
            ->createField('userIdentifier', 'string')->length(128)->nullable(true)->build()
            ->createField('scopes', 'oauth2_scope')->nullable(true)->build()
            ->addField('revoked', 'boolean')
            ->createManyToOne('client', $this->clientClass)->addJoinColumn('client', 'identifier', false, false, 'CASCADE')->build()
        ;
    }

    private function buildAuthorizationCodeMetadata(ClassMetadata $metadata): void
    {
        (new ClassMetadataBuilder($metadata))
            ->setTable('oauth2.authorization_code')
            ->createField('identifier', 'string')->makePrimaryKey()->length(80)->option('fixed', true)->build()
            ->addField('expiry', 'datetime_immutable')
            ->createField('userIdentifier', 'string')->length(128)->nullable(true)->build()
            ->createField('scopes', 'oauth2_scope')->nullable(true)->build()
            ->addField('revoked', 'boolean')
            ->createManyToOne('client', $this->clientClass)->addJoinColumn('client', 'identifier', false, false, 'CASCADE')->build()
        ;
    }

    private function buildClientMetadata(ClassMetadata $metadata): void
    {
        (new ClassMetadataBuilder($metadata))
            ->setTable('oauth2.client')
            ->createField('identifier', 'string')->makePrimaryKey()->length(32)->build()
        ;
    }

    private function buildRefreshTokenMetadata(ClassMetadata $metadata): void
    {
        $classMetadataBuilder = (new ClassMetadataBuilder($metadata))
            ->setTable('oauth2.refresh_token')
            ->createField('identifier', 'string')->makePrimaryKey()->length(80)->option('fixed', true)->build()
            ->addField('expiry', 'datetime_immutable')
            ->addField('revoked', 'boolean')
        ;

        if ($this->persistAccessToken) {
            $classMetadataBuilder->createManyToOne('accessToken', AccessToken::class)
                ->addJoinColumn('access_token', 'identifier', true, false, 'SET NULL')
                ->build()
            ;
        }
    }
}