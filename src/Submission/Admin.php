<?php

namespace ChrisPenny\WebPageTest\Submission;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldImportButton;
use SilverStripe\Forms\GridField\GridFieldPrintButton;

/**
 * Class Admin
 *
 * @package ChrisPenny\WebPageTest\SubmitTest
 */
class Admin extends ModelAdmin
{
    /**
     * @var array
     */
    private static $managed_models = [
        Model::class => [
            'title' => 'Submitted Test',
        ],
    ];

    /**
     * @var string
     */
    private static $menu_title = 'Performance Tests';

    /**
     * @var string
     */
    private static $url_segment = 'performance-tests';

    /**
     * @var string
     */
    private static $menu_icon_class = 'font-icon-rocket';

    /**
     * @param int|null $id
     * @param FieldList|null $fields
     * @return Form
     */
    public function getEditForm($id = null, $fields = null): Form
    {
        $form = parent::getEditForm($id, $fields);

        /** @var GridField $gridField */
        $gridField = $form->Fields()->fieldByName('ChrisPenny-WebPageTest-SubmitTest-Model');

        if ($gridField) {
            $config = $gridField->getConfig();

            $config->removeComponentsByType([
                GridFieldImportButton::class,
//                GridFieldFilterHeader::class,
                GridFieldPrintButton::class,
                GridFieldExportButton::class,
                GridFieldAddNewButton::class,
            ]);
        }

        return $form;
    }
}
