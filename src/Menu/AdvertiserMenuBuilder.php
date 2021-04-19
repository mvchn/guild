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

    //TODO: use $options
    public function createMainMenu(array $options): ItemInterface
    {
        $request = $this->requestStack->getCurrentRequest();

        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'nav nav-sidebar')
            ->setChildrenAttribute('data-nav-type', 'accordion')
        ;

        $activeClass = $this->router->match($request->getPathInfo())['_route'] === 'adm' ? 'active' : '';
        $menu->addChild($this->translator->trans('Dashboard', [], 'app'), ['route' =>'adm'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', sprintf('nav-link %s', $activeClass))
            ->setCurrent(false)
            ->setExtra('icon', 'icon-home4')
        ;

        $activeClass = $this->router->match($request->getPathInfo())['_route'] === 'products_list' ? 'active' : '';
        $menu->addChild($this->translator->trans('Products', [], 'app'), ['route' =>'admin_products_list'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', sprintf('nav-link %s', $activeClass))
            ->setCurrent(false)
            ->setExtra('icon', 'icon-stack')
        ;

        $activeClass = $this->router->match($request->getPathInfo())['_route'] === 'orders_list' ? 'active' : '';
        $menu->addChild($this->translator->trans('Orders', [], 'app'), ['route' =>'admin_orders_list'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', sprintf('nav-link %s', $activeClass))
            ->setCurrent(false)
            ->setExtra('icon', 'icon-copy')
        ;

//        $menu->addChild($this->translator->trans('Offers', [], 'app'), ['uri' => '#'])
//            ->setAttribute('class', 'nav-item nav-item-submenu')
//            ->setLinkAttribute('class', 'nav-link')
//            ->setExtra('icon', 'icon-copy')
//            ->setChildrenAttribute('class', 'nav nav-group-sub')
//            ->setChildrenAttribute('data-submenu-title', 'Offers')
//                ->addChild('test', ['route' => 'app_homepage'])
//                ->setAttribute('class', 'nav-item')
//                ->setLinkAttribute('class', 'nav-link active')
//        ;

        $activeClass = $this->router->match($request->getPathInfo())['_route'] === 'changelog' ? 'active' : '';
        $menu->addChild($this->translator->trans('Changelog', [], 'app'), ['route' =>'changelog'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', sprintf('nav-link %s', $activeClass))
            ->setCurrent(false)
            ->setExtra('icon', 'icon-list-unordered')
            ->setExtra('badge', '0.1')
        ;

        return $menu;
    }
}