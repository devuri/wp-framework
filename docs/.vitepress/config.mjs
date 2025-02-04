import { defineConfig } from 'vitepress'
import functionsList from './functionsArray'
const pkg = require('../../package.json')

export default defineConfig({
    /* prettier-ignore */
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
	ignoreDeadLinks: false,
    // sitemap: {
    //     hostname: 'https://devuri.github.io/wpframework'
    // },
    themeConfig: {
        nav: navBar(),
		sidebar: {
			'/guide/': [
				{
					text: 'Guide',
					base: '/guide/',
					items: sidebarGuide()
				}
			],
			'/deployment/': [
				{
					text: 'Deployment',
					base: '/deployment/',
					items: sidebarDeployment()
				}
			],
			'/multi-tenant/': [
				{
					text: 'MultiTenant',
					base: '/multi-tenant/',
					items: sidebarMultiTenant()
				}
			],
	    },
        socialLinks: [
            {
                icon: 'github',
                link: 'https://github.com/devuri/wpframework'
            }
        ],
        search: {
            provider: 'local',
            options: {
                placeholder: 'Search Raydium Framework Docs...',
            },
        },
		footer: {
            message: 'Released under the <a href="https://github.com/devuri/wpframework/blob/main/LICENSE">MIT License</a>.',
            copyright: 'Copyright © <a href="https://devuri">Uriel Wilson</a>'
        }
    }
})

function navBar(){
  return [
	{
	text: 'Guide',
	items: [
	  {text: "Overview", link: "guide/overview/what-is-raydium"},
	  {text: "Quick Start", link: "guide/getting-started"},
	  {text: "Multi-Tenant", link: "multi-tenant/overview"},
	  {text: "Deploy", link: "deployment/deploy"},
	]
  },
	{
		text: 'Customization',
		items: [
			{ text: 'Configuration', link: 'guide/customization/config-overview' },
			{ text: 'Environments', link: 'guide/customization/environments' },
			{ text: 'Constants', link: 'guide/customization/constants-file' },
			{ text: 'Defined Constants', link: 'guide/customization/defined-constants' },
			{ text: 'Adminer', link: 'guide/customization/dbadmin' },
			{ text: 'Compatibility', link: 'guide/customization/compatibility' },
			{ text: 'GitHub Token', link: 'guide/customization/auth-json' },
			{ text: 'Kiosk', link: 'guide/customization/kiosk' },
			{ text: 'Middleware', link: 'guide/customization/middleware' },
			{ text: 'Install Protection', link: 'guide/customization/install-protection' }
		]
	},
	{
	  text: 'Ecosystem',
	  items: [
		{
		  text: 'Twigit',
		  link: 'https://github.com/devuri/twigit'
		},
		{text: "xe", link: "https://github.com/devuri/raydiumxe"},
		{text: "deployer", link: "https://github.com/devuri/rdx-release-deployer-action/"},
		{text: "cpt-meta", link: "https://packagist.org/packages/devuri/cpt-meta-box"},
		{text: "zipit", link: "https://packagist.org/packages/devuri/zipit"},
		{text: "site-status", link: "https://packagist.org/packages/devuri/advanced-custom-site-status"},
		{text: "kdx-canvas", link: "https://packagist.org/packages/devuri/wp-kdx-canvas"},
	  ]
   },
    {
      text: pkg.version,
      items: [
        {
          text: 'Changelog',
          link: 'https://github.com/devuri/wpframework/blob/master/CHANGELOG.md'
        },
        {
          text: 'Contributing',
          link: '/reference/framework/'
	  	},
		{text: "Code", link: "https://devuri.github.io/wpframework/code/"}
      ]
  },
  {
	text: 'Environments',
	link: '/guide/customization/environments'
  },
  {
	text: 'Reference',
	items: [
	  {
		  text: 'Twig',
		  link: '/reference/twigit',
	  },
	  {
		  text: 'Premium Plugin',
		  link: '/reference/premium-plugins',
	  },
	  {
		  text: 'Functions',
		  link: '/reference/functions',
	  },
	  {
		  text: 'Constants',
		  link: '/reference/constants-overview',
	  },
	  {
		  text: 'Shortinit',
		  link: '/reference/shortinit',
	  },
	]
  },
  ]
}

function sidebarDeployment() {
	return [
		{
			text: 'Deployment',
			collapsible: true,
			collapsed: true,
			items: [
				{ text: 'Deploy', link: 'deploy' },
				{ text: 'Repo Strategy', link: 'repo-strategy' },
				{ text: 'Automated rsync', link: 'rsync-strategy' },
				{ text: 'SSH Keys', link: 'ssh-keys' },
				{ text: 'SSH Key Pairs', link: 'ssh-keygen' },
				{ text: 'Deploy Keys', link: 'deploy-keys' },
				{ text: 'Auto Updates', link: 'auto-updates' },
				{ text: 'Disable Updates', link: 'disable-updates' }
			]
		}
	]
}

function sidebarMultiTenant() {
	return [
		{
			collapsible: false,
			collapsed: false,
			items: [
				{ text: 'Setup Guide', link: 'setup' },
				{ text: 'Overview', link: 'overview' },
				{ text: 'Configuration', link: 'tenancy-config' },
				{ text: 'Isolation', link: 'isolation' },
				{ text: 'Architecture', link: 'architecture' },
				{ text: 'Domains', link: 'domains' }
			]
		}
	]
}

function sidebarGuide() {
	return [
		{
			text: 'Overview',
			collapsible: false,
			collapsed: false,
			items: [
				{ text: 'Why Raydium', link: 'overview/why-raydium' },
				{ text: 'What Is Raydium', link: 'overview/what-is-raydium' },
				{ text: 'Is it Still WordPress', link: 'overview/is-it-wordpress' }
			]
		},
		{
			text: 'Quick Start',
			collapsible: false,
			collapsed: false,
			items: [
				{ text: 'Getting Started', link: 'getting-started' },
				{ text: 'Installation', link: 'installation' },
				{ text: 'Ignore Platform', link: 'ignore-platform' },
				{ text: 'Specify PHP Version', link: 'php-versions' },
				{ text: 'Migration', link: 'migration' },
				{ text: 'Updates', link: 'updates' }
			]
		},
		{
			text: 'Customization',
			collapsible: false,
			collapsed: false,
			items: [
				{ text: 'Configuration', link: 'customization/config-overview' },
				{ text: 'Environments', link: 'customization/environments' },
				{ text: 'Constants', link: 'customization/constants-file' },
				{ text: 'Multisite', link: 'customization/multisite' },
				{ text: 'Adminer', link: 'customization/dbadmin' },
				{ text: 'Compatibility', link: 'customization/compatibility' },
				{ text: 'GitHub Token', link: 'customization/auth-json' },
				{ text: 'Kiosk', link: 'customization/kiosk' },
				{ text: 'Middleware', link: 'customization/middleware' },
				{ text: 'Install Protection', link: 'customization/install-protection' }
			]
		}
	]
}


function sidebarReference() {
	return [{
		text: 'Reference',
		items: [{
				text: 'Configs',
				link: 'configuration'
			},
			{
				text: 'Env',
				link: 'environments'
			},
			{
				text: 'Helpers',
				link: 'functions'
			},
			{
				text: 'Functions API',
				"collapsible": true,
				"collapsed": true,
				base: '/reference/functions/',
				items: functionsList,
			},
			{
				text: 'Framework',
				collapsible: true,
				collapsed: true,
				base: '/reference/framework/',
				items: [
					{ text: 'Lifecycle', link: 'lifecycle' },
					{ text: 'Terminate', link: 'terminate' },
					{ text: 'Architecture', link: 'architecture' },
					{ text: 'Framework', link: 'framework' }
				]
			},
			{
				text: 'Advanced',
				items: [
					{ text: 'Premium Plugins', link: 'premium-plugins' },
					{ text: 'Managing Updates', link: 'updates' }
				]
			},
			{
				text: 'Contribute',
				"collapsible": true,
				"collapsed": true,
				base: '/reference/contribute/',
				items: [{
						text: "Welcome",
						link: "welcome"
					},
					{
						text: "Contributing",
						link: "contributing"
					},
					{
						text: "Writing Comments",
						link: "documentation"
					},
				]
			},
			{
				text: 'Upgrades',
				collapsible: true,
				collapsed: true,
				items: [
					{ text: '^0.0.5', link: 'upgrade/env-config-upgrade' }
				]
			},
			{ text: 'Changelog', link: 'changelog' }
		]
	}, ]
}
