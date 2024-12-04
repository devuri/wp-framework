import { defineConfig } from 'vitepress';

// https://vitepress.dev/reference/site-config
export default defineConfig({
    head: [
        ['link', { rel: 'icon', type: 'image/svg+xml', href: '/raydium-logo-mini.svg' }],
        ['link', { rel: 'icon', type: 'image/png', href: '/raydium-logo-mini.png' }],
        ['meta', { name: 'theme-color', content: '#008080' }],
        ['meta', { property: 'og:type', content: 'website' }],
        ['meta', { property: 'og:locale', content: 'en' }],
        ['meta', { property: 'og:title', content: 'Raydium | A WordPress micro-enhancement framework' }],
        ['meta', { property: 'og:site_name', content: 'Raydium Framework' }],
        ['meta', { property: 'og:image', content: 'https://devuri.github.io/wpframework/docs/raydium-logo.png' }],
        ['meta', { property: 'og:url', content: 'https://devuri.github.io/wpframework' }]
    ],
    logo: '/raydium-logo.png',
    lang: 'en-US',
    title: 'Raydium',
    description: 'Effortlessly develop scalable WordPress applications that support multiple tenants from a single installation.',
    srcDir: 'src',
    base: '/wpframework/',
    outDir: '../docs/dist',
    cleanUrls: true,
    themeConfig: {
        nav: [
            { text: 'Guide', link: '/overview/what-is-raydium' },
            { text: 'Reference', link: '/reference/environment-vars' }
        ],
        sidebar: [
            {
                text: 'Overview',
                collapsible: true,
                collapsed: true,
                items: [
                    { text: 'Why Raydium', link: '/overview/why-raydium' },
                    { text: 'What Is Raydium', link: '/overview/what-is-raydium' },
                    { text: 'Modern Development', link: '/overview/modern-development' },
                    { text: 'Is it Still WordPress', link: '/overview/is-it-wordpress' }
                ]
            },
            {
                text: 'Quick Start',
                collapsible: true,
                collapsed: false,
                items: [
                    { text: 'Getting Started', link: '/guide/getting-started' },
                    { text: 'Installation', link: '/guide/installation' },
		            { text: 'Ignore Platform', link: '/guide/ignore-platform' },
                    { text: 'Migration', link: '/guide/migration' },
                    { text: 'Updates', link: '/guide/updates' }
                ]
            },
            {
                text: 'Deployment',
                collapsible: true,
                collapsed: true,
                items: [
                    { text: 'Deploy', link: '/deployment/deploy' },
                    { text: 'Repo Strategy', link: '/deployment/repo-strategy' },
		            { text: 'Automated rsync', link: '/deployment/rsync-strategy' },
                    { text: 'SSH Keys', link: '/deployment/ssh-keys' },
		            { text: 'SSH Key Pairs', link: '/deployment/ssh-keygen' },
		            { text: 'Deploy Keys', link: '/deployment/deploy-keys' },
		            { text: 'Auto Updates', link: '/deployment/auto-updates' },
		            { text: 'Disable Updates', link: '/deployment/disable-updates' }
                ]
            },
            {
                text: 'Customization',
                collapsible: true,
                collapsed: true,
                items: [
                    { text: 'Configuration', link: '/customization/config-overview' },
                    { text: 'Environments', link: '/customization/environments' },
                    { text: 'Environment File', link: '/customization/environment-file' },
                    { text: 'Constants', link: '/customization/constants' },
		            { text: 'GitHub Token', link: '/customization/auth-json' }
                ]
            },
            {
                text: 'Multi-Tenant',
                collapsible: true,
                collapsed: true,
                items: [
                    { text: 'Setup Guide', link: '/multi-tenant/setup' },
                    { text: 'Overview', link: '/multi-tenant/overview' },
                    { text: 'Configuration', link: '/multi-tenant/tenancy-config' },
                    { text: 'Isolation', link: '/multi-tenant/isolation' },
                    { text: 'Architecture', link: '/multi-tenant/architecture' }
                ]
            },
            {
                text: 'Tutorials',
                collapsible: true,
                collapsed: true,
                items: [{ text: 'Overview', link: '/tutorials/overview' }]
            },
            {
                text: 'Reference',
                collapsible: true,
                collapsed: true,
                items: [
                    { text: 'Lifecycle', link: '/reference/lifecycle' },
                    { text: 'Configs', link: '/reference/configuration' },
                    { text: 'Constants', link: '/reference/app-constants' },
                    { text: 'Install Protection', link: '/reference/install-protection' },
                    { text: 'Environment', link: '/reference/environment-vars' },
                    { text: 'Plugin', link: '/reference/plugin' },
                    { text: 'App', link: '/reference/app-component' },
                    { text: 'Kiosk', link: '/reference/kiosk' },
                    { text: 'Kernel', link: '/reference/kernel' },
                    { text: 'Setup', link: '/reference/setup' },
                    { text: 'Tenancy', link: '/reference/tenancy' },
                    { text: 'Switcher', link: '/reference/switcher' },
                    { text: 'Terminate', link: '/reference/terminate' },
                    { text: 'Architecture', link: '/reference/architecture' },
                    { text: 'Framework', link: '/reference/framework' },
                    { text: 'Code', link: 'https://devuri.github.io/wpframework/code/' }
                ]
            },
            {
                text: 'Upgrades',
                collapsible: true,
                collapsed: true,
                items: [
                    { text: '^0.0.5', link: '/upgrade/env-config-upgrade' },
                    { text: 'Changelog', link: '/upgrade/changelog' }
                ]
            },
            {
                text: 'Advanced',
                items: [
                    { text: 'Premium Plugins', link: '/premium-plugins' },
                    { text: 'Managing Updates', link: '/updates' }
                ]
            }
        ],
        socialLinks: [{ icon: 'github', link: 'https://github.com/devuri/wpframework' }],
        search: {
            provider: 'local',
            options: { placeholder: 'Search Raydium Docs...' }
        },
        footer: {
            message: 'Released under the <a href="https://github.com/devuri/wpframework/blob/main/LICENSE">MIT License</a>.',
            copyright: 'Copyright © <a href="https://devuri">Uriel Wilson</a>'
        }
    }
});
