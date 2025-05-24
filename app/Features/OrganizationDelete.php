<?php

namespace App\Features;

class OrganizationDelete extends Feature
{
    /**
     * The feature's slug.
     */
    public string $name = 'Delete Organization';

    /**
     * The feature's display title.
     */
    public string $title = 'Delete Organizations';

    /**
     * The feature's description.
     */
    public string $description = 'The ability to delete, restore or permanently delete a Organization.';
}
