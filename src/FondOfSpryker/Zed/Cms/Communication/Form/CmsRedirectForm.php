<?php

namespace FondOfSpryker\Zed\Cms\Communication\Form;

use Spryker\Zed\Cms\Communication\Form\CmsRedirectForm as SprykerCmsRedirectForm;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\Cms\Business\CmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface getQueryContainer()
 */
class CmsRedirectForm extends SprykerCmsRedirectForm
{
    /**
     * @return array
     */
    protected function getUrlConstraints(): array
    {
        $urlConstraints = parent::getUrlConstraints();

        $urlConstraints[] = new Callback([
            'callback' => function ($url, ExecutionContextInterface $context) {
                if ($url[0] !== '/') {
                    $context->addViolation('URL must start with a slash');
                }
            },
        ]);

        return $urlConstraints;
    }

    /**
     * @return array
     */
    protected function getMandatoryConstraints(): array
    {
        return [
            $this->createRequiredConstraint(),
            $this->createNotBlankConstraint(),
            $this->createLengthConstraint(self::MAX_COUNT_CHARACTERS_REDIRECT_URL),
        ];
    }
}
