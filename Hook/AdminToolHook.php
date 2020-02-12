<?php


namespace BrandPromo\Hook;


use BrandPromo\BrandPromo;
use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Tools\URL;

class AdminToolHook extends BaseHook
{
    public function onMainTopMenuTools(HookRenderBlockEvent $event)
    {
        $event->add(array(
            'id' => 'tools_menu_selection',
            'class' => '',
            'url' => URL::getInstance()->absoluteUrl('/admin/module/brandpromo/tool'),
            'title' => $this->trans('Brand Promotion', [], BrandPromo::DOMAIN_NAME)
        ));
    }
}