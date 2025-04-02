<?php

namespace TeamsTom\TableSticky\Http\Controllers;

use TeamsTom\TableSticky\ServiceProvider;


class TableStickyController extends ServiceProvider
{
    public function index(Content $content)
    {
        return $content
            ->title('Title')
            ->description('Description')
            ->body(Admin::view('dcat-admin.table-sticky::index'));
    }
}
