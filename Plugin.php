<?php namespace Tiipiik\Catalog;

use Event;
use Backend;
use System\Classes\PluginBase;
use Tiipiik\Catalog\Models\Category;
use Tiipiik\Catalog\Models\Product;

/**
 * Catalog Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'tiipiik.catalog::lang.plugin_name',
            'description' => 'tiipiik.catalog::lang.plugin_description',
            'author'      => 'Tiipiik',
            'icon'        => 'icon-th'
        ];
    }

    public function registerPermissions()
    {
        return [
            'tiipiik.catalog.manage_categories' => [
                'tab' => 'Catalog',
                'label' => 'tiipiik.catalog::lang.settings.access_categories'
            ],
            'tiipiik.catalog.manage_products' => [
                'tab' => 'Catalog',
                'label' => 'tiipiik.catalog::lang.settings.access_products'
            ],
            'tiipiik.catalog.manage_custom_fields' => [
                'tab' => 'Catalog',
                'label' => 'tiipiik.catalog::lang.settings.access_custom_fields'
            ],
            'tiipiik.catalog.manage_groups' => [
                'tab' => 'Catalog',
                'label' => 'tiipiik.catalog::lang.settings.access_groups'
            ],
            'tiipiik.catalog.manage_stores' => [
                'tab' => 'Catalog',
                'label' => 'Manage Stores'
            ],
        ];
    }

    public function registerComponents()
    {
        return [
            '\Tiipiik\Catalog\Components\Categories' => 'categories',
            '\Tiipiik\Catalog\Components\ProductList' => 'product_list',
            '\Tiipiik\Catalog\Components\ProductDetails' => 'product_details',
            '\Tiipiik\Catalog\Components\StoreList' => 'store_list',
            '\Tiipiik\Catalog\Components\StoreDetails' => 'store_details',
        ];
    }

    public function registerNavigation()
    {
        return [
            'catalog' => [
                'label'       => 'tiipiik.catalog::lang.plugin_name',
                'url'         => Backend::url('tiipiik/catalog/products'),
                'icon'        => 'icon-th',
                'permissions' => ['tiipiik.catalog.*'],
                'order'       => 20,

                'sideMenu' => [
                    'categories' => [
                        'label'       => 'tiipiik.catalog::lang.categories.menu_label',
                        'icon'        => 'icon-list-ul',
                        'url'         => Backend::url('tiipiik/catalog/categories'),
                        'attributes'  => ['data-menu-item'=>'categories'],
                        'permissions' => ['tiipiik.catalog.manage_categories'],
                    ],
                    'reorder' => [
                        'label'       => 'tiipiik.catalog::lang.categories.reorder_category',
                        'icon'        => 'icon-exchange',
                        'url'         => Backend::url('tiipiik/catalog/categories/reorder'),
                        'attributes'  => ['data-menu-item'=>'categories'],
                        'permissions' => ['tiipiik.catalog.manage_categories'],
                    ],
                    'products' => [
                        'label'       => 'tiipiik.catalog::lang.products.menu_label',
                        'icon'        => 'icon-th',
                        'url'         => Backend::url('tiipiik/catalog/products'),
                        'attributes'  => ['data-menu-item'=>'products'],
                        'permissions' => ['tiipiik.catalog.manage_products'],
                    ],
                    'customfields' => [
                        'label'       => 'tiipiik.catalog::lang.custom_fields.menu_label',
                        'icon'        => 'icon-list-alt',
                        'url'         => Backend::url('tiipiik/catalog/customfields'),
                        'attributes'  => ['data-menu-item'=>'custom_fields'],
                        'permissions' => ['tiipiik.catalog.manage_custom_fields'],
                    ],
                    'groups' => [
                        'label'       => 'Groups',
                        'icon'        => 'icon-list-alt',
                        'url'         => Backend::url('tiipiik/catalog/groups'),
                        'attributes'  => ['data-menu-item'=>'groups'],
                        'permissions' => ['tiipiik.catalog.manage_groups'],

                    ],
                    'stores' => [
                        'label'       => 'Stores',
                        'icon'        => 'icon-list-ul',
                        'url'         => Backend::url('tiipiik/catalog/stores'),
                        'attributes'  => ['data-menu-item'=>'stores'],
                        'permissions' => ['tiipiik.catalog.manage_stores'],
                    ],
                ]
            ]
        ];
    }

    public function boot()
    {
        /*
         * Register menu items for the RainLab.Pages and RainLab.Sitemap plugin
         */
        Event::listen('pages.menuitem.listTypes', function () {
            return [
                'all-catalog-categories' => 'All Catalog categories',
                'catalog-category' => 'Catalog category',
            ];
        });

        Event::listen('pages.menuitem.getTypeInfo', function ($type) {
            if ($type == 'url') {
                return [];
            }

            if ($type == 'all-catalog-categories'|| $type == 'catalog-category') {
                return Category::getMenuTypeInfo($type);
            }
        });

        Event::listen('pages.menuitem.resolveItem', function ($type, $item, $url, $theme) {
            if ($type == 'all-catalog-categories' || $type == 'catalog-category') {
                return Category::resolveMenuItem($item, $url, $theme);
            }
        });
    }
}
