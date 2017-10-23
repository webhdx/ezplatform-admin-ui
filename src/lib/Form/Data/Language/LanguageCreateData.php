<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Form\Data\Language;

use EzSystems\EzPlatformAdminUi\Form\Data\OnFailureRedirect;
use EzSystems\EzPlatformAdminUi\Form\Data\OnFailureRedirectTrait;
use EzSystems\EzPlatformAdminUi\Form\Data\OnSuccessRedirect;
use EzSystems\EzPlatformAdminUi\Form\Data\OnSuccessRedirectTrait;

class LanguageCreateData implements OnSuccessRedirect, OnFailureRedirect
{
    use OnSuccessRedirectTrait;
    use OnFailureRedirectTrait;

    /** @var string */
    private $name;

    /** @var string */
    private $languageCode;

    /** @var bool */
    private $enabled;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLanguageCode(): ?string
    {
        return $this->languageCode;
    }

    /**
     * @param string $languageCode
     */
    public function setLanguageCode(string $languageCode)
    {
        $this->languageCode = $languageCode;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool)$this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
    }
}
