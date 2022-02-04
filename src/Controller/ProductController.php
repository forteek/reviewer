<?php

namespace App\Controller;

use App\Entity\Product;
use App\Helper\Paginator;
use App\Service\Instantiator;
use App\Constant\ProductGroups;
use App\Repository\ProductRepository;
use App\Component\Attribute as Param;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ProductController extends AbstractController
{
    #[Rest\Get(path: '/products', name: 'index_titles')]
    #[Param\Limit]
    #[Param\Page]
    public function index(
        ParamFetcherInterface $paramFetcher,
        ProductRepository $repository
    ): Response {
        $list = $repository->index();

        $page = Paginator::paginate(
            $list,
            $paramFetcher->get('page'),
            $paramFetcher->get('limit')
        );

        return $this->object($page, groups: ProductGroups::INDEX);
    }

    #[Rest\Get(
        path: '/products/{id}',
        name: 'show_title',
        requirements: ['id' => '\d+']
    )]
    #[ParamConverter(data: ['name' => 'product'], class: Product::class)]
    public function show(
        Product $product
    ): Response {
        return $this->object($product, groups: ProductGroups::SHOW);
    }

    #[Rest\Post(path: '/products', name: 'create_title')]
    #[Param\Instance]
    public function store(
        Instantiator $instantiator,
        ParamFetcherInterface $paramFetcher,
        EntityManagerInterface $manager
    ): Response {
        $product = $instantiator->deserialize(
            $paramFetcher->get('instance'),
            Product::class,
            ProductGroups::CREATE
        );

        $manager->persist($product);
        $manager->flush();

        return $this->object(
            $product,
            201,
            ProductGroups::SHOW
        );
    }

    #[Rest\Patch(
        path: '/products/{id}',
        name: 'update_title',
        requirements: ['id' => '\d+']
    )]
    #[Param\Instance]
    #[ParamConverter(data: ['name' => 'product'], class: Product::class)]
    public function update(
        Instantiator $instantiator,
        ParamFetcherInterface $paramFetcher,
        EntityManagerInterface $manager,
        Product $product
    ): Response {
        $product = $instantiator->deserialize(
            $paramFetcher->get('instance'),
            Product::class,
            ProductGroups::UPDATE,
            $product
        );

        $manager->persist($product);
        $manager->flush();

        return $this->object($product, groups: ProductGroups::SHOW);
    }

    #[Rest\Delete(
        path: '/products/{id}',
        name: 'remove_title',
        requirements: ['id' => '\d+']
    )]
    #[ParamConverter(data: ['name' => 'product'], class: Product::class)]
    public function remove(
        EntityManagerInterface $manager,
        Product $product
    ): Response {
        $manager->remove($product);
        $manager->flush();

        return $this->empty();
    }
}