<?php
namespace NeonWebId\Utils;

final class WPAdminMenuManager 
{
    /**
     * Daftar user yang boleh melihat semua menu
     */
    private array $accessList = [
        'usernames' => [],
        'emails'    => [],
    ];

    /**
     * Koleksi menu WP-Admin (hasil collect)
     */
    private array $adminMenus = [];

    /**
     * Konfigurasi menu & submenu yang disembunyikan
     */
    private array $hiddenMenus = [];

    public function __construct()
    {
        add_action('admin_init', [$this, 'collectMenus']);
        add_action('admin_menu', [$this, 'applyHiddenMenus'], 999);
    }

    /* =====================================================
     * PUBLIC API
     * ===================================================== */

    /**
     * Tambahkan menu yang disembunyikan
     */
    public function hiddenMenu(string $menu, array $submenus = []): void
    {
        $this->hiddenMenus[$menu] = $submenus;
    }

    public function getHiddenMenus():array
    {
        return $this->hiddenMenus;
    }

    public function allowByUsername(array $usernames): void
    {
        $this->accessList['usernames'] = $usernames;
    }

    public function allowByEmail(array $emails): void
    {
        $this->accessList['emails'] = $emails;
    }

    public function getMenuCollection(): array
    {
        return $this->adminMenus;
    }

    /* =====================================================
     * CORE LOGIC
     * ===================================================== */

    public function collectMenus(): void
    {
        global $menu, $submenu;

        if (empty($menu)) return;

        foreach ($menu as $m) {
            $slug = $m[2];

            if (in_array($slug, ['separator1', 'separator2'])) continue;

            $this->adminMenus[$slug] = [
                'title'   => $this->sanitizeTitle($m[0]),
                'icon'    => $m[6] ?? '',
                'submenu' => [],
            ];

            if (!empty($submenu[$slug])) {
                foreach ($submenu[$slug] as $sm) {
                    $this->adminMenus[$slug]['submenu'][] = [
                        'title' => $this->sanitizeTitle($sm[0]),
                        'slug'  => $sm[2],
                    ];
                }
            }
        }
    }

    public function applyHiddenMenus(): void
    {
        if ($this->currentUserHasAccess()) {
            return;
        }

        foreach ($this->hiddenMenus as $parent => $submenus) {

            if ($submenus === []) {
                remove_menu_page($parent);
                continue;
            }

            foreach ($submenus as $submenu) {
                remove_submenu_page($parent, $submenu);
            }
        }
    }

    /* =====================================================
     * INTERNAL UTILITIES
     * ===================================================== */

    private function currentUserHasAccess(): bool
    {
        $user = wp_get_current_user();
        if (!$user || !$user->exists()) return false;

        $usernameAllowed = empty($this->accessList['usernames'])
            || in_array($user->user_login, $this->accessList['usernames'], true);

        $emailAllowed = empty($this->accessList['emails'])
            || in_array($user->user_email, $this->accessList['emails'], true);

        return $usernameAllowed || $emailAllowed;
    }

    private function sanitizeTitle(string $title): string
    {
        return trim(
            wp_strip_all_tags(
                preg_replace('/<span[^>]*>.*?<\/span>/i', '', $title)
            )
        );
    }
}
