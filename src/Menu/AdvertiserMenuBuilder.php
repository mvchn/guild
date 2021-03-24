<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdvertiserMenuBuilder
{
    private $factory;

    private $translator;

    private $router;

    protected $requestStack;



    public function __construct(FactoryInterface $factory, TranslatorInterface $translator, RouterInterface $router, RequestStack $requestStack)
    {
        $this->factory = $factory;
        $this->translator = $translator;
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $request = $this->requestStack->getCurrentRequest();
        $menuItems = [
            'Dashboard' => [
                'route' => 'advertiser_dashboard',
                'icon' => 'icon-home4'
            ],
            'Offers' => [
                'route' => 'offers_list',
                'icon' => 'icon-package'
            ],
            'Products' => [
                'route' => 'products_list',
                'icon' => 'icon-cube'
            ],
            'Landings' => [
                'route' => 'landings_list',
                'icon' => 'icon-file-text'
            ]


        ];

        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'nav nav-sidebar')
            ->setChildrenAttribute('data-nav-type', 'accordion')
        ;

        foreach ($menuItems as $key => $meuItem) {
            $activeClass = $this->router->match($request->getPathInfo())['_route'] === $meuItem['route'] ? 'active' : '';
            $menu->
                addChild($this->translator->trans($key, [], 'app'), ['route' => $meuItem['route']])
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', sprintf('nav-link %s', $activeClass))
                ->setCurrent(false)
                ->setExtra('icon',  $meuItem['icon'])
            ;
        }

//        $menu->addChild($this->translator->trans('Offers', [], 'app'), ['uri' => '#'])
//            ->setAttribute('class', 'nav-item nav-item-submenu')
//            ->setLinkAttribute('class', 'nav-link')
//            ->setExtra('icon', 'icon-copy')
//            ->setChildrenAttribute('class', 'nav nav-group-sub')
//            ->setChildrenAttribute('data-submenu-title', 'Offers')
//                ->addChild('test', ['route' => 'homepage'])
//                ->setAttribute('class', 'nav-item')
//                ->setLinkAttribute('class', 'nav-link active')
//        ;

        return $menu;
    }
}