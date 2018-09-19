<?php

namespace FondOfSpryker\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Cms\Communication\Controller\RedirectController as SprykerRedirectController;
use Spryker\Zed\Cms\Communication\Form\CmsRedirectForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Cms\Business\CmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface getQueryContainer()
 */
class RedirectController extends SprykerRedirectController
{
    /**
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleForUrl(string $url): LocaleTransfer
    {
        $availableLocaleArray = $this->getFactory()->getLocaleFacade()->getAvailableLocales();
        $defaultLocale = $this->getFactory()->getLocaleFacade()->getCurrentLocale();

        $urlLocale = $this->extractLocaleFromUrl($url);
        if ($urlLocale === null) {
            return $defaultLocale;
        }

        foreach ($availableLocaleArray as $localeId => $localeName) {
            $shortLocaleName = substr($localeName, 0, 2);
            if ($shortLocaleName !== $urlLocale) {
                continue;
            }

            return $this->getFactory()->getLocaleFacade()->getLocale($localeName);
        }

        return $defaultLocale;
    }

    /**
     * @param string $url
     *
     * @return null|string
     */
    protected function extractLocaleFromUrl(string $url): ?string
    {
        $urlParts = explode('/', trim($url, '/'));
        if (is_array($urlParts) && count($urlParts) > 0 && is_string($urlParts[0]) && strlen($urlParts[0]) == 2) {
            return $urlParts[0];
        }

        return null;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createCmsRedirectFormDataProvider();
        $form = $this->getFactory()
            ->createCmsRedirectForm(
                $dataProvider->getData()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $sourceUrlTransfer = new UrlTransfer();
            $sourceUrlTransfer
                ->setUrl($data[CmsRedirectForm::FIELD_FROM_URL])
                ->setFkLocale($this->getLocaleForUrl($data[CmsRedirectForm::FIELD_FROM_URL])->getIdLocale());

            $urlRedirectTransfer = new UrlRedirectTransfer();
            $urlRedirectTransfer
                ->fromArray($data, true)
                ->setSource($sourceUrlTransfer);

            $this->getFactory()
                ->getUrlFacade()
                ->createUrlRedirect($urlRedirectTransfer);

            $this->addSuccessMessage(static::MESSAGE_REDIRECT_CREATE_SUCCESS);
            return $this->redirectResponse(static::REDIRECT_ADDRESS);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $idUrl = $this->castId($request->query->get(self::REQUEST_ID_URL));

        $dataProvider = $this->getFactory()->createCmsRedirectFormDataProvider();
        $form = $this->getFactory()
            ->createCmsRedirectForm(
                $dataProvider->getData($idUrl)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $sourceUrlTransfer = new UrlTransfer();
            $sourceUrlTransfer
                ->setIdUrl($idUrl)
                ->setUrl($data[CmsRedirectForm::FIELD_FROM_URL])
                ->setFkLocale($this->getLocaleForUrl($data[CmsRedirectForm::FIELD_FROM_URL])->getIdLocale());

            $urlRedirectTransfer = new UrlRedirectTransfer();
            $urlRedirectTransfer
                ->fromArray($data, true)
                ->setSource($sourceUrlTransfer);

            $this->getFactory()
                ->getUrlFacade()
                ->updateUrlRedirect($urlRedirectTransfer);

            $this->addSuccessMessage(static::MESSAGE_REDIRECT_UPDATE_SUCCESS);
            return $this->redirectResponse(static::REDIRECT_ADDRESS);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }
}
