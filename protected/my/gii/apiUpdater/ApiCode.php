<?php


class ApiCode extends CCodeModel
{

    /**
     * @var string
     */
    public $apiProviderUrl = '';

    public $sdkUpdateUrl = '';

    /**
     * @var string
     */
    public $vendorName = '';

    public $availableApi;

    /**
     * Prepares the code files to be generated.
     * This is the main method that child classes should implement. It should contain the logic
     * that populates the {@link files} property with a list of code files to be generated.
     */
    public function prepare()
    {
        // TODO: Implement prepare() method.
    }
}
