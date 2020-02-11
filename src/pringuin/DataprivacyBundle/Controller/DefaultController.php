<?php

namespace pringuin\DataprivacyBundle\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\Model\Document;
use Pimcore\Model\Document\Service;
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

        // Make replacements for locales
        foreach($configuration as $key => $value){
            if(strpos($value,'%locale%')){
                $configuration[$key] = str_replace('%locale%',$request->getLocale(),$value);
            }
        }

        if(!empty($configuration['privacy_url']) && is_numeric($configuration['privacy_url'])){
            $documentService = $this->get('pimcore.document_service');
            $document = Document::getById($configuration['privacy_url']);
            $translations = $documentService->getTranslations($document);
            if(!empty($translations[$request->getLocale()])){
                $configuration['privacy_url'] = Document::getById($translations[$request->getLocale()])->getFullPath();
            } else {
                $configuration['privacy_url'] = $document->getFullPath();
            }
        }

        $this->view->configuration = $configuration;

        return $this->render('pringuinDataprivacyBundle::default/default.html.twig', $this->view->getAllParameters());
    }
}
