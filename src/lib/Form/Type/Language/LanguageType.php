<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformAdminUi\Form\Type\Language;

use eZ\Publish\API\Repository\LanguageService;
use EzSystems\EzPlatformAdminUi\Form\DataTransformer\LanguageTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LanguageType extends AbstractType
{
    /** @var LanguageService */
    protected $languageService;

    /**
     * @param LanguageService $languageService
     */
    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new LanguageTransformer($this->languageService));
    }

    public function getParent()
    {
        return TextType::class;
    }
}
