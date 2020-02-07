<?php

namespace pringuin\DataprivacyBundle\Controller;

use Pimcore\Controller\FrontendController;
use pringuin\DataprivacyBundle\Helper\Configurationhelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class DefaultController extends FrontendController
{

    public function onKernelController(FilterControllerEvent $event)
    {
        // set auto-rendering to twig
        $this->setViewAutoRender($event->getRequest(), true, 'twig');
    }

    public function defaultAction(Request $request)
    {
        if(\Pimcore\Model\Site::isSiteRequest()) {
            $site = \Pimcore\Model\Site::getCurrentSite()->getId();
        } else {
            $site = 'default';
        }

        $configuration = Configurationhelper::getConfigurationForSite($site);

        //Make replacements for locales
        foreach($configuration as $key => $value){
            if(strpos($value,'%locale%')){
                $configuration[$key] = str_replace('%locale%',$request->getLocale(),$value);
            }
        }

        $this->view->configuration = $configuration;

        return $this->render('pringuinDataprivacyBundle::default/default.html.twig', $this->view->getAllParameters());
    }
}
