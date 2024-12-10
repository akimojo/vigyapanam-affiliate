<?php
namespace VigyapanamAffiliate\Core\Roles;

class RoleManager {
    public function setup_roles() {
        $this->setup_affiliate_role();
        $this->setup_admin_capabilities();
    }

    private function setup_affiliate_role() {
        if (!get_role('affiliate')) {
            add_role('affiliate', __('Affiliate', 'vigyapanam-affiliate'), [
                'read' => true,
                'edit_posts' => false,
                'delete_posts' => false,
                'upload_files' => true,
                'vigyapanam_view_dashboard' => true,
                'vigyapanam_withdraw_earnings' => true,
                'vigyapanam_view_programs' => true,
            ]);
        }
    }

    private function setup_admin_capabilities() {
        $admin = get_role('administrator');
        $admin_caps = [
            'vigyapanam_manage_settings',
            'vigyapanam_manage_clients',
            'vigyapanam_manage_affiliates',
            'vigyapanam_view_reports',
            'vigyapanam_manage_withdrawals',
            'vigyapanam_manage_programs',
        ];

        foreach ($admin_caps as $cap) {
            $admin->add_cap($cap);
        }
    }
}