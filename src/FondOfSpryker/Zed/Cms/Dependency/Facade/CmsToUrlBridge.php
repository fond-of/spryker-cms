<?php

namespace FondOfSpryker\Zed\Cms\Dependency\Facade;

use FondOfSpryker\Zed\Url\Business\UrlFacade;
use Generated\Shared\Transfer\UrlRedirectTransfer;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlBridge as SprykerCmsToUrlBridge;

class CmsToUrlBridge extends SprykerCmsToUrlBridge
{
    /**
     * @var \Spryker\Zed\Url\Business\UrlFacadeInterface
     */
    protected $urlFacade;

    /**
     * CmsToUrlBridge constructor.
     *
     * @param \FondOfSpryker\Zed\Url\Business\UrlFacade $urlFacade
     */
    public function __construct(UrlFacade $urlFacade)
    {
        parent::__construct($urlFacade);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function updateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer): UrlRedirectTransfer
    {
        return $this->urlFacade->updateUrlRedirect($urlRedirectTransfer);
    }
}
