<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ParentController extends BaseController
{
    protected function pageData(string $page_title, string $dashboard_title = "", string $breadcrumbs = null): array
    {
        $companyName = data('company_name', '');

        return [
            'page_title' => "{$page_title} | {$companyName}",
            'dashboard_title' => $dashboard_title,
            'breadcrumb' => $breadcrumbs
        ];
    }




    // for User Dashboard Controllers Only
    protected function userInvalidDataNotificationArray(): array
    {
        return [
            'title' => 'Invalid input detected.',
            'message' => 'The input provided by you is either invalid or not acceptable.',
            'type' => 'danger'
        ];
    }
}
