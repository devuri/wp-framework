# Changelog

## [0.9.15](https://github.com/devuri/wpframework/compare/v0.9.14...v0.9.15) (2025-01-23)


### Bug Fixes

* copy `mu-plugins` and `app.sample.php` files as needed ([0cc4e50](https://github.com/devuri/wpframework/commit/0cc4e502e0a1d761c794379595897faee6c07df3))

## [0.9.14](https://github.com/devuri/wpframework/compare/v0.9.13...v0.9.14) (2025-01-23)


### Bug Fixes

* add status check for database fix ([dace1ae](https://github.com/devuri/wpframework/commit/dace1aed6017eaacaffaa4b847916e1480cb8b66))

## [0.9.13](https://github.com/devuri/wpframework/compare/v0.9.12...v0.9.13) (2025-01-23)


### Features

* add `headless` and `shortinit` with documentation ([a70fb0c](https://github.com/devuri/wpframework/commit/a70fb0c7c1ac07212a886f042bcee33b50c60714))

## [0.9.12](https://github.com/devuri/wpframework/compare/v0.9.11...v0.9.12) (2025-01-22)


### Features

* adds `shortinit` with `twig` support and `TinyQuery::class` ([#306](https://github.com/devuri/wpframework/issues/306)) ([39eec58](https://github.com/devuri/wpframework/commit/39eec58b8f62b81e29729162b8230f66c24c993a))

## [0.9.11](https://github.com/devuri/wpframework/compare/v0.9.10...v0.9.11) (2025-01-21)


### Bug Fixes

* verify that `app.php` is a php file ([cec6501](https://github.com/devuri/wpframework/commit/cec650113c06696412b9328e5031a04d8e72797c))

## [0.9.10](https://github.com/devuri/wpframework/compare/v0.9.9...v0.9.10) (2025-01-21)


### Features

* adds `WP_SANDBOX_SCRAPING` ([d649b67](https://github.com/devuri/wpframework/commit/d649b677a85ff21029e992fc7fc645ce333ee745))


### Bug Fixes

* minor fix for `$encryptionPath` ([505e95c](https://github.com/devuri/wpframework/commit/505e95cbbce087c224494821d0474562b93574d7))


### Miscellaneous Chores

* delete sqlite ([b8c8abd](https://github.com/devuri/wpframework/commit/b8c8abd9e8a90ae0daac46792fd4b407bb46a537))

## [0.9.9](https://github.com/devuri/wpframework/compare/v0.9.8...v0.9.9) (2025-01-16)


### Bug Fixes

* minor fix for `$encryptionPath` ([9585ebd](https://github.com/devuri/wpframework/commit/9585ebddb9e29a8669ca8abac62a6133492fa7b1))
* update `multisite` urls guide for `.htaccess` ([ce7452a](https://github.com/devuri/wpframework/commit/ce7452a2e93b04d43d6be0ad5686ce019f05306b))

## [0.9.8](https://github.com/devuri/wpframework/compare/v0.9.7...v0.9.8) (2025-01-16)


### Bug Fixes

* strictly return false always ([47b24f3](https://github.com/devuri/wpframework/commit/47b24f33db3a00c321421f0d681c6b6ab4ee21ab))

## [0.9.7](https://github.com/devuri/wpframework/compare/v0.9.6...v0.9.7) (2025-01-16)


### Bug Fixes

* chore codefix ([ded4d41](https://github.com/devuri/wpframework/commit/ded4d4170b895b736c1c3685d661de991189e46b))

## [0.9.6](https://github.com/devuri/wpframework/compare/v0.9.5...v0.9.6) (2025-01-16)


### Features

* add `Str::getExternalIP()` fetch the external IP address ([acf3516](https://github.com/devuri/wpframework/commit/acf351682b40f2f3c8039192c099841f1bbc9510))


### Bug Fixes

* include body in `response` ([dd2a6c6](https://github.com/devuri/wpframework/commit/dd2a6c63e24e1abb2dfe7953ff99a84f157cdc48))
* update for empty `resolveTenantIdFromRequest` ([a5100c8](https://github.com/devuri/wpframework/commit/a5100c80029f33b99898d318e2e9b4bdf9dab1bb))

## [0.9.5](https://github.com/devuri/wpframework/compare/v0.9.4...v0.9.5) (2025-01-14)


### Features

* new `Str::` class ([a1cd178](https://github.com/devuri/wpframework/commit/a1cd1788babdb719c7cb4c192b14ea53157043e2))

## [0.9.4](https://github.com/devuri/wpframework/compare/v0.9.3...v0.9.4) (2025-01-14)


### Bug Fixes

* better `ServerRequest` separations ([0ee76f5](https://github.com/devuri/wpframework/commit/0ee76f5427be3f0f9f9daf0e036689fe17d6b5c0))
* better file reader ([7b5e1c7](https://github.com/devuri/wpframework/commit/7b5e1c739cdb9193b74e993cdc13c1ff1c51c93a))

## [0.9.3](https://github.com/devuri/wpframework/compare/v0.9.2...v0.9.3) (2025-01-14)


### Features

* adds `config['path']` to get `appPath` and `configsPath` ([47fd16e](https://github.com/devuri/wpframework/commit/47fd16e027365881f12db3712998e63f1f69ea36))
* adds env file reader ([3cda878](https://github.com/devuri/wpframework/commit/3cda878fb84066470abe3085956c77724676ee20))


### Miscellaneous Chores

* code comment fixes ([abf55a7](https://github.com/devuri/wpframework/commit/abf55a72e2b88a7b5ace5dd51a862afde9760273))

## [0.9.2](https://github.com/devuri/wpframework/compare/v0.9.1...v0.9.2) (2025-01-13)


### Features

* adds `SQLiteDatabase` A lightweight SQLite database wrapper ([de4b111](https://github.com/devuri/wpframework/commit/de4b111671540112b489a0dae4e8876ce2909319))


### Miscellaneous Chores

* update tests ([012a8cc](https://github.com/devuri/wpframework/commit/012a8cc7c36fbb05ca91f996527e9645344703d4))

## [0.9.1](https://github.com/devuri/wpframework/compare/v0.9.0...v0.9.1) (2025-01-11)


### Features

* add/updated defined constants docs ([cedf57c](https://github.com/devuri/wpframework/commit/cedf57c49e10666b4c63fa2d6aad85807ab89a26))

## [0.9.0](https://github.com/devuri/wpframework/compare/v0.8.0...v0.9.0) (2025-01-11)


### ⚠ BREAKING CHANGES

* the constant file `config.php` is now `constants.php`

### Features

* the constant file `config.php` is now `constants.php` ([78001a6](https://github.com/devuri/wpframework/commit/78001a6650df907ca0587635e40d362e2c449757))

## [0.8.0](https://github.com/devuri/wpframework/compare/v0.7.3...v0.8.0) (2025-01-11)


### ⚠ BREAKING CHANGES

* changed env ref to `WP_ENVIRONMENT_TYPE` is now `ENVIRONMENT_TYPE`
* changed env ref to `WP_HOME` is now `HOME_URL`

### Features

* adds better twig setup with environment-options ([a5b990a](https://github.com/devuri/wpframework/commit/a5b990a66998cfe757f703f9061ee6705e49364a))
* better handling of `tenant` and `kiosk` ID setup ([86fe0ee](https://github.com/devuri/wpframework/commit/86fe0eed3ba0792bf07edd9636ca98060bacddb1))
* changed env ref to `WP_ENVIRONMENT_TYPE` is now `ENVIRONMENT_TYPE` ([b78471a](https://github.com/devuri/wpframework/commit/b78471a014ec6ef8faa68378fdb89c8c9fbb2a03))
* changed env ref to `WP_HOME` is now `HOME_URL` ([b4a3568](https://github.com/devuri/wpframework/commit/b4a356833bf998e23a5046fdc09e91bf1d647b98))
* new `ADMINER_SECRET` env secret for jwt setups ([929c888](https://github.com/devuri/wpframework/commit/929c888d51987727468c27d792b6185c04664073))
* the `kiosk` now runs just like any tenant with a `configs/{uuid}` directory ([0d0c8a2](https://github.com/devuri/wpframework/commit/0d0c8a2574e506f596775d5c78cd184cd46c6de5))


### Bug Fixes

* add `generateKioskFile` to generate kiosk env file ([7b58079](https://github.com/devuri/wpframework/commit/7b58079c0e96d2f29116c75450d62b63bd0e34a2))
* always validate WP_HOME and WP_SITEURL with isValidHomeUrl() ([f99aed3](https://github.com/devuri/wpframework/commit/f99aed3c6ae7596acdcd265f7081e96238782472))
* better handling for dbadmin `autologin` with cofigs and settings ([c664e4a](https://github.com/devuri/wpframework/commit/c664e4aef878c558208e5735a6cf12a897c9f919))
* secure mode access for `Adminer` ([1a0bb1d](https://github.com/devuri/wpframework/commit/1a0bb1dd719acea6ce670ee0a0bacf7053041ac5))


### Miscellaneous Chores

* build ([11395ad](https://github.com/devuri/wpframework/commit/11395ad4481a4290337421104d0dacd69178c2be))
* build ([a46d553](https://github.com/devuri/wpframework/commit/a46d5538eceabb5defebdfa5ea0172f39e29b08a))
* build ([646a214](https://github.com/devuri/wpframework/commit/646a214a1f8b3c30758b237f95f19532411d4282))
* build ([ff54cc7](https://github.com/devuri/wpframework/commit/ff54cc72aa4679f6ee4e72d437c920b050f2f309))
* build ([214d972](https://github.com/devuri/wpframework/commit/214d972a93d77d41ddea2288dba033a754bef7aa))

## [0.7.3](https://github.com/devuri/wpframework/compare/v0.7.2...v0.7.3) (2025-01-09)


### Features

* add `strContains` ([29082d1](https://github.com/devuri/wpframework/commit/29082d1640fa6194bb0a7744492bc447e0bfacff))
* add new `KioskMiddleware` middleware ([7b495ea](https://github.com/devuri/wpframework/commit/7b495ea9fbc2d160e6d1800fead7823351dc12e4))
* adds `HYBRIDX` to bootstrap the framework in lightweight mode. ([49fd2b4](https://github.com/devuri/wpframework/commit/49fd2b4ce171e9ac440a091a5023dd1dea6c702a))
* adds `nikic/fast-route` mainly for use in the `kiosk` admin ([369a135](https://github.com/devuri/wpframework/commit/369a13570ae461742668d96c1005e27be4c35f57))
* adds checks for `kiosk` subdomain like: `kiosk.example.com` ([0604b6b](https://github.com/devuri/wpframework/commit/0604b6baeafd50493d7304b65a6245e15a15f5e1))
* adds new `PanelHandler` for the `kiosk` ([a5cd3d4](https://github.com/devuri/wpframework/commit/a5cd3d4d4479526d16f8f6c9e6e81924f7970566))
* adds templates for `kiosk` ([2b65df5](https://github.com/devuri/wpframework/commit/2b65df52a666b11a75eee065c662317d5da4e9bd))
* domain parser ([27a2b06](https://github.com/devuri/wpframework/commit/27a2b06193873ae29eaff872b44b6acb3cda3189))
* new `kiosk` json updated structure and `isSuperAdmin()` check ([8cec04e](https://github.com/devuri/wpframework/commit/8cec04edb0568b54239dc8cfa7ba91038a393e44))
* the `adminer` now supports .env `ADMINER_URI` as primary setup ([89fafd8](https://github.com/devuri/wpframework/commit/89fafd8661f645564d35d93b286fb399e288a0de))
* the `health_status` now includes `enabled`, `route` and `secret` ([d053176](https://github.com/devuri/wpframework/commit/d053176a18ec6b9964edf88bb377ef0d6d6e6363))
* the `MiddlewareDispatcher` supports `responseHandled` Attribute ([94b03ed](https://github.com/devuri/wpframework/commit/94b03ed781ee7cd4db0a4dc45d7e27c8210b5cad))


### Bug Fixes

* adds `KIOSK_DOMAIN_ID` and `ADMINER_URI` ([ecc9ddf](https://github.com/devuri/wpframework/commit/ecc9ddf2dbb12796a0a838195eae3c74b8b162b9))
* config test update for pull: [#258](https://github.com/devuri/wpframework/issues/258) ([4ad36a2](https://github.com/devuri/wpframework/commit/4ad36a22d016677b64739f0542336b4e3acee7c5))
* fixes the return on `isAdminRouteRestricted` ([c4b84ac](https://github.com/devuri/wpframework/commit/c4b84ac511bcf60f89d10cb54f1c38891b0428e3))
* if framework is not fully loaded we wont have `SECURE_AUTH_SALT` ([bbf0041](https://github.com/devuri/wpframework/commit/bbf0041ca3fdd39b75f6bc4aee4e956207724ec8))
* update !IMPORTANT :restricting access to wp-admin, now allows ajax ([ef1f4d8](https://github.com/devuri/wpframework/commit/ef1f4d8c24594b85bc6805dc0c67cfb768a16808))
* update `$this-&gt;finalRequest` will not always be available ([d44befb](https://github.com/devuri/wpframework/commit/d44befbba5e7a4c636fa46da941678334f6e156e))
* update for single tenant `maintenance` mode ([05a42ee](https://github.com/devuri/wpframework/commit/05a42ee1659d26cd5461398b046d682eb58bb12b))
* we can now ensure the first character is a `letter` in `randStr()` ([bdd6570](https://github.com/devuri/wpframework/commit/bdd6570c8c838cd8b70c4bee3186d6bf52d63646))


### Miscellaneous Chores

* add tests for appSettings `app.php` ([688f9e4](https://github.com/devuri/wpframework/commit/688f9e4abcb6253a08c8bd15c16e69babf60fd9e))
* build ([8b150d1](https://github.com/devuri/wpframework/commit/8b150d1a9856da4c9ef258af7183322005fcd518))
* build ([de9b7be](https://github.com/devuri/wpframework/commit/de9b7be51d3547e23f3a7d0fd1746140e79a7bdc))

## [0.7.2](https://github.com/devuri/wpframework/compare/v0.7.1...v0.7.2) (2025-01-07)


### Bug Fixes

* fixes the authentication for database admin requests ([425f294](https://github.com/devuri/wpframework/commit/425f2944562c7d895f55b3f736961e46eb6609df))

## [0.7.1](https://github.com/devuri/wpframework/compare/v0.7.0...v0.7.1) (2025-01-07)


### Features

* add `DEBUG_STACK_TRACE` override constant to show stack trace. ([2463636](https://github.com/devuri/wpframework/commit/246363637218a97b500243ce3fefcb06a89a1787))
* adds `Adminer` database administrator interface ([a839eca](https://github.com/devuri/wpframework/commit/a839ecaac2e5e4c1458f23c419cb49948c0aaadd))


### Bug Fixes

* add `IgnitionMiddleware` initial `EnvType` to the container ([dd06a58](https://github.com/devuri/wpframework/commit/dd06a58c3dfc21a2da70e296f257465622e8aaa7))
* add `phpcs` to `composer lint` ([86d5bcf](https://github.com/devuri/wpframework/commit/86d5bcffd21bd82f035129c7d276f638bdc50205))
* delete `FaviconCache` ([d6969cc](https://github.com/devuri/wpframework/commit/d6969ccce1364964eec14440ca306a94d1e041c9))
* passes on previous Exception to `HttpException` ([59c7c07](https://github.com/devuri/wpframework/commit/59c7c074570b1231a3a951c8d9b4309ead9893c7))
* use `/` when link path is empty ([2692786](https://github.com/devuri/wpframework/commit/2692786eae2e2612af3985dbeeb3a84ff5f80131))


### Miscellaneous Chores

* build ([62267d8](https://github.com/devuri/wpframework/commit/62267d83f6c8d44e0d11b0e6b5fe1142d3a5e801))
* build ([9bd11c3](https://github.com/devuri/wpframework/commit/9bd11c35cb0b651a786651f967d4dd24fd5c696c))

## [0.7.0](https://github.com/devuri/wpframework/compare/v0.6.2...v0.7.0) (2025-01-05)


### ⚠ BREAKING CHANGES

* implement basic `Middleware Chain of Responsibility` simplify code flow ([#196](https://github.com/devuri/wpframework/issues/196))
* adds `ConstantBuilder` and removes `ConstantBuilderTrait`
* use `wp-content` makes the framework switch much easier.
* updates for `EnvSwitcher`
* adds new `class Config` static class
* composer.json upgrade `0.7.x`
* remove autologin and ref to namespaces
* code standards `@PSR12` updates

### Features

* add `HttpsOnlyMiddleware` disabled by default ([a3292bf](https://github.com/devuri/wpframework/commit/a3292bf69df63f961be1d2a6e08020b9fc8becf7))
* add `Middleware` WIP ([62213f2](https://github.com/devuri/wpframework/commit/62213f2d211277712573e62a056a0b80d214791d))
* add `middlewareFilter` with  `$AppFactory-&gt;filter(['kernel'])` ([9254889](https://github.com/devuri/wpframework/commit/925488989b05a5ef7aebaf7b0aa66283d36ab6cd))
* add `twigit` WIP MU plugin for Twig template support ([e86c613](https://github.com/devuri/wpframework/commit/e86c613d3b4a8816459a079224cc5efa249e61ce))
* add route `/health-status` with new `JsonResponse` and `healthStatus` Attribute ([7a2ad68](https://github.com/devuri/wpframework/commit/7a2ad6850b03c78d8f1b0c271062f0b8f6c1e722))
* add security headers ([eea82e6](https://github.com/devuri/wpframework/commit/eea82e6a928484406ec482ec89e0f8573f631573))
* adds `ConstantBuilder` and removes `ConstantBuilderTrait` ([4080a97](https://github.com/devuri/wpframework/commit/4080a979bf5740b01226825e63e7d25d61af7070))
* adds `DeviceDetector` ([46d2c86](https://github.com/devuri/wpframework/commit/46d2c86e56356fd690212e6a99f096687b3079ac))
* adds `FileLogger`, `logMessage()` with `Log::*` wrapper ([51f0933](https://github.com/devuri/wpframework/commit/51f09336a89768ac994d5010f7cf8d778dc20bf0))
* adds `HttpRequest` class ([aaa2e4f](https://github.com/devuri/wpframework/commit/aaa2e4fd6252771b7ca44dab11417a641cbe23cb))
* adds `IDGenerator` for tenant ID Generator ([1eaa40e](https://github.com/devuri/wpframework/commit/1eaa40e219b000a0eab38d9e4347106f524b0471))
* adds `isMultitenant` as Attribute on `TenantIdMiddleware` ([5020bc8](https://github.com/devuri/wpframework/commit/5020bc8473604b8b4a71e271b5c335d811c6b48b))
* adds `MiddlewareHandler` ([e87d1e7](https://github.com/devuri/wpframework/commit/e87d1e782d46999f73920e4c835cf8274a57b5a0))
* adds `psr/http-server-middleware` and `ResponseInterface` ([1bfe8c8](https://github.com/devuri/wpframework/commit/1bfe8c866450e928c098974ac418dc99106bed27))
* adds `SapiEmitter` ([afade79](https://github.com/devuri/wpframework/commit/afade791215a2a8b7c51c5a9888e43b56ef8f5fd))
* adds basic `PsrContainer` ([afa5699](https://github.com/devuri/wpframework/commit/afa5699c9a91181a299cf47dfec4a3f11485286b))
* adds basic spam middleware ([0f8377e](https://github.com/devuri/wpframework/commit/0f8377e2c12aad3a269d1eeea9ba874f13fa1123))
* adds json style `tenants.json` repository for tenants ([7fc2f22](https://github.com/devuri/wpframework/commit/7fc2f226496ed912044510890a37311cbead7265))
* adds List of production environment identifiers. `config('prod')` ([83ba99c](https://github.com/devuri/wpframework/commit/83ba99c36215587113c51d6d84d21047b2ddb20c))
* adds new `class Config` static class ([29df306](https://github.com/devuri/wpframework/commit/29df3061ef6c7d169aa95c9a454dd2b328f4a9ba))
* adds new `DISABLE_AND_BLOCK_WPSERVER_UPDATES` const ([38e8160](https://github.com/devuri/wpframework/commit/38e8160ddb6a03a544bb5df74199c8eaf321a7a0))
* adds new headless CMS mu-plugin with `HEADLESS_MODE` constant ([3e31347](https://github.com/devuri/wpframework/commit/3e3134725b901a22b88258e980a8a65a6f85551a))
* adds new optional Developer Kiosk with `kiosk.json` ([773286f](https://github.com/devuri/wpframework/commit/773286fb9ccfbb2967e9b049c1e161e69a9d2167))
* adds proper `http message` handler with `nyholm/psr7` ([1695a0c](https://github.com/devuri/wpframework/commit/1695a0cadf16a168fa5f352a2cec51323a52a959))
* adds Twig `$env_options` ([ccf4a52](https://github.com/devuri/wpframework/commit/ccf4a52ee73842a4e28601c2bca59aa51f679df7))
* better templates with more context options ([b5eba18](https://github.com/devuri/wpframework/commit/b5eba18de07e944cc0f64540b1a3e1c0c2274739))
* can add Custom error code for application-specific error handling ([5916fb6](https://github.com/devuri/wpframework/commit/5916fb6620da075e23b9e39c3aa12ee5d23df4e5))
* configs is now an `Attribute` ([81967cd](https://github.com/devuri/wpframework/commit/81967cdd0ce47ce91b1924d28c79a714d8fe69aa))
* implement a basic container ([6c88d94](https://github.com/devuri/wpframework/commit/6c88d94b6bb3edb86c8b7918a138b472ccaed9f9))
* implement basic `Middleware Chain of Responsibility` simplify code flow ([#196](https://github.com/devuri/wpframework/issues/196)) ([2d05ad3](https://github.com/devuri/wpframework/commit/2d05ad3bcc6b324f9f5e1ef4a76bc08eebb0c3db))
* new Tenancy setup (WIP) ([e3be6a7](https://github.com/devuri/wpframework/commit/e3be6a799934e4d440e0c60d698e9b1b8a41ee1f))
* pdo `DB` class can now retrieve options ([db5b6b6](https://github.com/devuri/wpframework/commit/db5b6b6c282c6b77029c17ff622eef9cdce3d2e8))
* the `HttpClient` is now baked in as part of the framework ([130f21b](https://github.com/devuri/wpframework/commit/130f21b177a08cd9f037293fdf37ac6d111a22c2))
* use `wp-content` makes the framework switch much easier. ([61e96c9](https://github.com/devuri/wpframework/commit/61e96c9578dec6dae9eb8ae7e90ce9de871ecaf6))


### Bug Fixes

* add `configs` in `setKernelConstants()`  we needs env vars loaded ([817f599](https://github.com/devuri/wpframework/commit/817f5994e9066ee3b9b9cfbc057c857baabfa14f))
* add `phpcs` ([d4bbecd](https://github.com/devuri/wpframework/commit/d4bbecdbef23c93af5809ab85cd9d63312d505c8))
* add config setting `enable_health_status` ([188912b](https://github.com/devuri/wpframework/commit/188912bdeafc051ed86279c9079be591b9402613))
* add fallback `tenancy.json` in `loadTenancyFile()` ([8c3b197](https://github.com/devuri/wpframework/commit/8c3b197b8bc79d66af04a980360310a23c4ca1c6))
* add missing variable ([d04fc33](https://github.com/devuri/wpframework/commit/d04fc3399734546dc1ae78f3d8cf3f5fe9b4ad2d))
* add new `EnvLoader` ([883526e](https://github.com/devuri/wpframework/commit/883526e821e8c4470c30b8316a9d100192c60217))
* add updated fix for `finalRequest` ([758854c](https://github.com/devuri/wpframework/commit/758854c4eec16a4c49fdc61f8e457bf604552cf9))
* adds a `addConfig()` method for Configs class ([698cb40](https://github.com/devuri/wpframework/commit/698cb408ce6b15a421c53e294f2f52a70a51a206))
* adds auth checks for `wp-admin` routes ([42979f1](https://github.com/devuri/wpframework/commit/42979f1cdce818a39763ec8342d268ac16d3fd0e))
* always do `InvalidArgumentException` on env() fail ([d389c85](https://github.com/devuri/wpframework/commit/d389c852c818c137e2701a2d332807f3e44c2cfa))
* better  Exceptions based on `$environment` ([30d262e](https://github.com/devuri/wpframework/commit/30d262e284d4c051fe578abeb7629d407bcf7c3c))
* better `environment` setup and error handling ([16c8352](https://github.com/devuri/wpframework/commit/16c83522b20c4d0caf5d3e9e41cee22c6212d4dd))
* better `EnvWhitelist` setup using `Configs` ([4d17ca6](https://github.com/devuri/wpframework/commit/4d17ca6ae01a919b8762621bce84079f6b99a4ca))
* better `Whoops` handler integration ([9964e3c](https://github.com/devuri/wpframework/commit/9964e3cd7d73b1a7b82fea8da646e77fcaebdbf8))
* better auth management with `AuthManager` ([c8eee32](https://github.com/devuri/wpframework/commit/c8eee327382f0789f73d5ff5d5e900ffb64ddb61))
* better Response handler for `Exception`  ([d369e88](https://github.com/devuri/wpframework/commit/d369e88d92c9cddf800b913eb02a48e00c526b43))
* better retry link for `Terminate` ([ee67506](https://github.com/devuri/wpframework/commit/ee67506c36fd1921ba8d3991762da1b74dc0480e))
* code standards `@PSR12` updates ([ab1d7ca](https://github.com/devuri/wpframework/commit/ab1d7cacf099234bbd0b9dbcd0e4c499410eef07))
* composer.json upgrade `0.7.x` ([02026e3](https://github.com/devuri/wpframework/commit/02026e3814e61b88e4e7162616dc48211a6e5be8))
* db table lookups adds `::tableExist` ([d107ac5](https://github.com/devuri/wpframework/commit/d107ac5fbe959458af7c5d8615fd268a2e943873))
* do not render exit page in cli mode ([dd78250](https://github.com/devuri/wpframework/commit/dd782506853bcbb8c0178dcf7e5310616a8cacbd))
* exit on `JsonResponse` with `application/json` Headers ([33e4833](https://github.com/devuri/wpframework/commit/33e483394e416970be68879939a415785dd90f9f))
* fix template output ([b348cb1](https://github.com/devuri/wpframework/commit/b348cb13287419cdf54c9740dfb70bb800f9abf0))
* include `conditionals and templates` setup in `twig.php` ([cfdd390](https://github.com/devuri/wpframework/commit/cfdd39021a32730845f91a5b80d6148ad07db216))
* instance of `$app` now includes `$containerBindings` ([5affc66](https://github.com/devuri/wpframework/commit/5affc66f426db1a859913a6aca67cdb4999fd3db))
* isProd now in `Configs` ([98ca879](https://github.com/devuri/wpframework/commit/98ca879a7df97678a9e22bcb6136118ee9774b65))
* make sure config file and composer `installer-paths` match ([04a4bd0](https://github.com/devuri/wpframework/commit/04a4bd03c316f37f678fe121a1f722a294f7da44))
* merged $whitelisted ([87d6644](https://github.com/devuri/wpframework/commit/87d6644cf99ab6479ca8697e0bb85002aee765be))
* moved `makeLogFile()` to `Log::createLogFile()` ([cb942cb](https://github.com/devuri/wpframework/commit/cb942cb5bd7f7d4e72878eef0c555e6e5ccf511f))
* no `trailing_comma_in_multiline` ([2e85f92](https://github.com/devuri/wpframework/commit/2e85f92a9bdca4f350e196e6e1437f9a321b727c))
* null `environment` update ([60e75bf](https://github.com/devuri/wpframework/commit/60e75bfbca1ec9140b09c533a6a45d961a0b10f6))
* null on empty cookie ([7458231](https://github.com/devuri/wpframework/commit/74582315e469f49737972e9c105d70cd02cef0df))
* only `validateTenantdB` on `isMultitenant` ([b635906](https://github.com/devuri/wpframework/commit/b635906b53c790f3a0fd61d1be598455b525a63a))
* refactor `config()` settings with `config()-&gt;composer` and get() ([cafcaf0](https://github.com/devuri/wpframework/commit/cafcaf0d2aab4bb9e85a6274bbbf1d59d3c4a5c5))
* refactor cookie setup ([d15e661](https://github.com/devuri/wpframework/commit/d15e661878d07e7f62f51b3b1bbee3fddf629942))
* remove `"fruitcake/php-cors"` it requires php 8.1 ([d2dc2eb](https://github.com/devuri/wpframework/commit/d2dc2eb4650e78eddac860398c755cbe96ab5a08))
* remove `withHeader('X-Frame-Options'` ([624269f](https://github.com/devuri/wpframework/commit/624269f72d981867e70df2452d247dbe1c19bd9d))
* remove autologin and ref to namespaces ([ca54f43](https://github.com/devuri/wpframework/commit/ca54f43ba320fde16fca12302106842093ad1f7d))
* simplify `configs` use `configs()-&gt;config['composer']->get` ([00a43c3](https://github.com/devuri/wpframework/commit/00a43c3438603625d2ee93a2f39e0c1849f7f8ac))
* stan updates ([320b72d](https://github.com/devuri/wpframework/commit/320b72d3e938e24194ccb726d1d091fd35aef527))
* update `configs/config.php` docs ([f3da72d](https://github.com/devuri/wpframework/commit/f3da72d21327eafa75a2a4fc6bd9e49515fff183))
* update `ErrorHandler` and `error_handler` in `app.php` ([5d954cc](https://github.com/devuri/wpframework/commit/5d954cce149958b87079693e8607606c5c2d22c6))
* update `Terminate` now uses Throwable ([bf72d98](https://github.com/devuri/wpframework/commit/bf72d98cae24cb356f0ef3ecb8c9e232de2880f1))
* update abstract middleware ([7fca365](https://github.com/devuri/wpframework/commit/7fca36530331b936bf19c5a2e680980b4b529381))
* update add `loginUrl()` ([b09f12f](https://github.com/devuri/wpframework/commit/b09f12f5b6bb028c1b47704afa37283c395c4309))
* update auto login, add `last_login_time` uer meta ([49ae3af](https://github.com/devuri/wpframework/commit/49ae3afce39917aa31209509941bf06ff2ce1698))
* update defualts ([5a82ae6](https://github.com/devuri/wpframework/commit/5a82ae6789d46ca5cf16a726de1fc644f1bf9682))
* update docs and revert to `LANDLORD_DB_` from `TENANT_DB_` ([1bd3250](https://github.com/devuri/wpframework/commit/1bd325054b48226efde6e4c0448b5bd469af3620))
* update exit home url to use env file settings ([adf1752](https://github.com/devuri/wpframework/commit/adf1752a315b66bf1fd6fed35d6c79c043348e28))
* update install protection better route matches ([5e17363](https://github.com/devuri/wpframework/commit/5e173631adf1b760b3430392a1b15fd444ebed9e))
* update Install protection for `RAYDIUM_INSTALL_PROTECTION` setups ([544cb8f](https://github.com/devuri/wpframework/commit/544cb8f10a73385b9a0704e577f6520f85fd53d1))
* update show stack trace ([0ff3d62](https://github.com/devuri/wpframework/commit/0ff3d627615e181a461cb6bf5435d28d5f57695d))
* update spam filter ([869b951](https://github.com/devuri/wpframework/commit/869b95147225cacd3afc682ff50cb0f34f520745))
* update stubs ([d2cfef8](https://github.com/devuri/wpframework/commit/d2cfef88d5bdceb685e23167bacd7ed639278d00))
* update tests and add `try.php`, run with `php tests/try.php` ([07fb9bc](https://github.com/devuri/wpframework/commit/07fb9bcf8d612314518c12dbb244b68bb66b7798))
* updated return types on `find()` and `where()` and `getUser()` ([c7dacd5](https://github.com/devuri/wpframework/commit/c7dacd58bcc3b62d370c3f1aaede38e93cecfb43))
* updates for `EnvSwitcher` ([bacd85e](https://github.com/devuri/wpframework/commit/bacd85e9a205a44f69fe06e01c493e6b40787cb0))
* use `*.html.twig` for template names ([a1f7693](https://github.com/devuri/wpframework/commit/a1f76931bd7ab236a87e82ad01e6494f001c3e8e))
* use `devuri/twigit` for optional twig setup. ([9173b29](https://github.com/devuri/wpframework/commit/9173b298caad36636b7f1b9ada940d1f53c7a6af))
* use interface `Psr\Log\LoggerInterface` ([381f14c](https://github.com/devuri/wpframework/commit/381f14cc208327ba9452eee96437473c7cc8a8af))
* uses secure auth cookie `SECURE_AUTH_COOKIE` for https auth checks ([9ceeea4](https://github.com/devuri/wpframework/commit/9ceeea490976a663c7a958885cb362e48e28ca43))
* we can now call `Configs::wpdb('users')` thanks to new param ([12d2c97](https://github.com/devuri/wpframework/commit/12d2c97ba1f2313d30e6c69734476e0f62a309fc))


### Miscellaneous Chores

* add `0.7.0` version number to package ([9553ecf](https://github.com/devuri/wpframework/commit/9553ecf31995f07b6151f811d3c834cadfd1bf6c))
* build ([fd53933](https://github.com/devuri/wpframework/commit/fd5393307840ddd686d95a85c2dd20e302dd6898))
* build ([b7cda6b](https://github.com/devuri/wpframework/commit/b7cda6b776610f2aaca67ecd8b54b2b569a745db))
* build ([d166015](https://github.com/devuri/wpframework/commit/d166015373a5f2cbe30ab06063e08122fc58c529))
* build ([875827c](https://github.com/devuri/wpframework/commit/875827cf8f4fae8c93b3a9b72ebb08eded4f52ca))
* build ([7ecc74b](https://github.com/devuri/wpframework/commit/7ecc74b767c89947946963cfcf627725c9595efc))
* build ([35a27ed](https://github.com/devuri/wpframework/commit/35a27ed9ee47b8d27470d88f76f3fe69af633981))
* build ([bf6d127](https://github.com/devuri/wpframework/commit/bf6d1273cdaf914bd404cfff1a5297ca4648b2d5))
* build ([1bd2539](https://github.com/devuri/wpframework/commit/1bd2539217eece5f3eeb29a865f8529c0ef3eb8a))
* build ([434545e](https://github.com/devuri/wpframework/commit/434545e3175defd9a41e084827f027024e7fdd48))
* build ([2945b01](https://github.com/devuri/wpframework/commit/2945b01e2bf7af10c4f65dd67061ebd4eec5b91b))
* build ([827bbed](https://github.com/devuri/wpframework/commit/827bbedf5dde64d3f8aab2b1f9a767f56a54b09c))
* build ([ef814f2](https://github.com/devuri/wpframework/commit/ef814f204043db4fbee18d340f9ca35edef59024))
* build ([f5418f1](https://github.com/devuri/wpframework/commit/f5418f1db5d3181fc83455db8f4cd45d439b7a19))
* build ([d3db3ab](https://github.com/devuri/wpframework/commit/d3db3ab27f58630b51e2cecd5ef91522d8bdac93))
* build ([dc5b2c7](https://github.com/devuri/wpframework/commit/dc5b2c770fa3c591c2cbfe6d6a8adf40a4b00dc8))
* build ([ecff989](https://github.com/devuri/wpframework/commit/ecff9891b3e1e9c5ebaef20e17f6a2e75fe206a5))
* build ([a37f864](https://github.com/devuri/wpframework/commit/a37f8647bf949b4e10e3ee07be9dfdc4ec383a91))
* build ([e1dfd9f](https://github.com/devuri/wpframework/commit/e1dfd9feab5fde39bb5609d7b7c34adca7681ac1))
* build ([54046c9](https://github.com/devuri/wpframework/commit/54046c9619c90572e42ad62df17f04727a555b33))
* build ([45c339e](https://github.com/devuri/wpframework/commit/45c339ea5cce348e23c1d035e6be0521d78ca35d))
* codefix ([11a40b4](https://github.com/devuri/wpframework/commit/11a40b44a4d56d560a50c9626a0901d8b516317a))
* codefix ([95030b3](https://github.com/devuri/wpframework/commit/95030b31d4b2aae66871f08af3878d21843d52c6))
* codefix ([c2d17c2](https://github.com/devuri/wpframework/commit/c2d17c2c43392df1ed7528df4c6cfb7fbf78dfab))
* codefix ([#176](https://github.com/devuri/wpframework/issues/176)) ([042384c](https://github.com/devuri/wpframework/commit/042384c9d21b23db33f6db71b6a8f6673e5f7aa4))
* css update ([addc69c](https://github.com/devuri/wpframework/commit/addc69c65ca4481c3193c25d70fb8548fc88a37e))
* **deps-dev:** bump elliptic from 6.5.7 to 6.6.0 ([0f7116b](https://github.com/devuri/wpframework/commit/0f7116b8c30f203c239d74d93d2fc0a62d7c0d48))
* **deps-dev:** bump rollup from 4.21.3 to 4.24.0 ([3acaa69](https://github.com/devuri/wpframework/commit/3acaa69db3ae04e43d320b5df90c996ee7f2559b))
* **deps-dev:** bump vite from 5.2.7 to 5.4.6 ([516ad9d](https://github.com/devuri/wpframework/commit/516ad9dd1bd8f8c398e8ee7fe750bc1d5dc99e5a))
* **deps:** bump cookie and express ([bc5b316](https://github.com/devuri/wpframework/commit/bc5b3165d7830ac80a6ad7f514edd984249b7cda))
* fix code outputs ([ca3bebf](https://github.com/devuri/wpframework/commit/ca3bebf0f0ff7088682eeeb48b6759c746ac6e8b))
* fix typo ([bc98507](https://github.com/devuri/wpframework/commit/bc985072abf5796f7c3ce780b47d3d10bbb2ae2b))
* phpstan updates ([ba367d0](https://github.com/devuri/wpframework/commit/ba367d0273ee014284c71a5315f9642b85f937de))
* refactor `Device` for DeviceDetector ([0ef0de0](https://github.com/devuri/wpframework/commit/0ef0de080fa377a2c728909c58c7029203b3a63f))
* refactored to introduce `Config::isProd` ([ea89dd0](https://github.com/devuri/wpframework/commit/ea89dd003c413b28a91b03bfbbfaa86cfde4babc))
* remove `phpbench/phpbench` ([3a4d0a9](https://github.com/devuri/wpframework/commit/3a4d0a976766cabcf01b5db7c48118d4c3b0e088))
* removed /guide/overview/what-is-raydium ([8f05d1d](https://github.com/devuri/wpframework/commit/8f05d1d51f67d6b8c293ea4dc1165e72ad600815))
* update `CoreMiddlewareTrait` is now `CoreMiddleware` ([4d7cc8d](https://github.com/devuri/wpframework/commit/4d7cc8db1758d8926ad5ee28c8901058e8e38cd7))
* updates for `php-cs-fixer` ([fe59379](https://github.com/devuri/wpframework/commit/fe5937907e30c1618ade602ca3ccec2450df3722))

## [0.6.2](https://github.com/devuri/wpframework/compare/v0.6.1...v0.6.2) (2024-09-17)


### Features

* new `Tenant` class to decouple the `tenant()` ([49e41f8](https://github.com/devuri/wpframework/commit/49e41f8d07764e4747e874f07be26b1b1ce2ceb5))

## [0.6.1](https://github.com/devuri/wpframework/compare/v0.6.0...v0.6.1) (2024-09-16)


### Bug Fixes

* update `EnvTypes` refactored and tested ([ef63229](https://github.com/devuri/wpframework/commit/ef632297e8535fb9f4fa1b8edbf44c0856ef9c74))


### Miscellaneous Chores

* build ([d73b541](https://github.com/devuri/wpframework/commit/d73b541cf3dd5662ffe46c2c20f0a01d32b0e394))

## [0.6.0](https://github.com/devuri/wpframework/compare/v0.5.1...v0.6.0) (2024-09-16)


### ⚠ BREAKING CHANGES

* namespace update `WPframework\Component` is now `WPframework`

### Features

* namespace update `WPframework\Component` is now `WPframework` ([6686a46](https://github.com/devuri/wpframework/commit/6686a46042da5737eb49d6db74313fcd80c16cd0))


### Miscellaneous Chores

* build ([72ba9eb](https://github.com/devuri/wpframework/commit/72ba9ebbff80d515e43182ebc749fb7ec49eaafb))

## [0.5.1](https://github.com/devuri/wpframework/compare/v0.5.0...v0.5.1) (2024-09-12)


### Bug Fixes

* update interface `configuration_overrides` error ([dc831f3](https://github.com/devuri/wpframework/commit/dc831f355479ca3d5a570fded95681ab343a8597))

## [0.5.0](https://github.com/devuri/wpframework/compare/v0.4.3...v0.5.0) (2024-09-12)


### ⚠ BREAKING CHANGES

* simplify `Initial application setup`

### Features

* simplify `Initial application setup` ([e7e0afd](https://github.com/devuri/wpframework/commit/e7e0afd6c1fb428a1400bb003cfbe5705a5d985e))

## [0.4.3](https://github.com/devuri/wpframework/compare/v0.4.2...v0.4.3) (2024-09-11)


### Bug Fixes

* add (string) for `wp_env_type` ([cb92628](https://github.com/devuri/wpframework/commit/cb92628fac1febe418b7b05868871bcd5368f429))
* add admin only info including `http_env` ([fe3ec08](https://github.com/devuri/wpframework/commit/fe3ec0878aa02ebda4c9e61bbd39747eae01f3c2))


### Miscellaneous Chores

* build ([289b7ed](https://github.com/devuri/wpframework/commit/289b7ed089be2c09fbfdca2cb921446311b291f0))

## [0.4.2](https://github.com/devuri/wpframework/compare/v0.4.1...v0.4.2) (2024-09-10)


### Bug Fixes

* environment in  `wp_env_type` ([a0c1b9f](https://github.com/devuri/wpframework/commit/a0c1b9f28fa3a4784406eefecba6f54ca193d2f7))

## [0.4.1](https://github.com/devuri/wpframework/compare/v0.4.0...v0.4.1) (2024-09-10)


### Bug Fixes

* auto login require `env( 'WP_ENVIRONMENT_TYPE' )` ([1e9377d](https://github.com/devuri/wpframework/commit/1e9377d7a3e6be6211e41bc9b3a152bdca0272ff))
* autologin fix ([3df4559](https://github.com/devuri/wpframework/commit/3df45590e1cc92b8a6a46c4c555f62daaf7d480c))

## [0.4.0](https://github.com/devuri/wpframework/compare/v0.3.14...v0.4.0) (2024-09-10)


### ⚠ BREAKING CHANGES

* update core mu-plugin file and removed `Plugin`
* adds new `KernelInterface`
* new `App::init()` replaces `http_component_kernel`

### Features

* adds new `KernelInterface` ([9fb7e59](https://github.com/devuri/wpframework/commit/9fb7e59ad8d0c667697739d6132bd1118bbf2106))
* new `App::init()` replaces `http_component_kernel` ([d26eb7e](https://github.com/devuri/wpframework/commit/d26eb7e7050270ccca67474e0019cfb86cc9eb4f))


### Bug Fixes

* auto login is now 60 seconds ([ed4b4ae](https://github.com/devuri/wpframework/commit/ed4b4aeaddd35abe03544b4b8a60ac943ab9357c))
* update bootstrap file for previous versions ([7ce1ad7](https://github.com/devuri/wpframework/commit/7ce1ad7e5d7b7dfec8bc01075b5fe7a91f02f75c))


### Miscellaneous Chores

* build ([cab9d79](https://github.com/devuri/wpframework/commit/cab9d79308364f1d4d5a1e30c24508a41e51d27f))
* build ([430942d](https://github.com/devuri/wpframework/commit/430942d52be137ff532d14fd2fe544eb2dc9b122))
* fix comment ([00a6e43](https://github.com/devuri/wpframework/commit/00a6e431c859fcea5d7d3278cadefa4b0cf1db0a))
* update ([ca2fe87](https://github.com/devuri/wpframework/commit/ca2fe878d9f8bfed705c2d3d38f3337f02585090))


### Code Refactoring

* update core mu-plugin file and removed `Plugin` ([a661fd6](https://github.com/devuri/wpframework/commit/a661fd67aea299ad1be0c58ea26aeee94ec91d58))

## [0.3.14](https://github.com/devuri/wpframework/compare/v0.3.13...v0.3.14) (2024-09-09)


### Bug Fixes

* better support for `app.php` with `_default_configs()` ([126c0a3](https://github.com/devuri/wpframework/commit/126c0a3f31ccdf01d4d14b880b9fc31b5fed17f2))
* docs `web_root_dir` error ([e340437](https://github.com/devuri/wpframework/commit/e34043749f56beced3adde12d73cb04e42098688))
* fixes theme dir location ([5abed88](https://github.com/devuri/wpframework/commit/5abed884b793f0929335d9d1bb3c624189e18d20))
* Update constants.md ([aca76b6](https://github.com/devuri/wpframework/commit/aca76b689b53f1924e39cd953eec060d72a1198e))
* updates `env()` function ([88fcfa0](https://github.com/devuri/wpframework/commit/88fcfa0a87cad2cf4a310065e0b3685dc533ca5e))


### Miscellaneous Chores

* `configs/app.php` file and using environment variables in the ([512f00b](https://github.com/devuri/wpframework/commit/512f00b6999d1075c5f4ec03b26d97e7730feaea))
* **deps-dev:** bump webpack from 5.91.0 to 5.94.0 ([2ed6354](https://github.com/devuri/wpframework/commit/2ed6354c7e7ea3170e68c01f9b4022db77ec43bc))

## [0.3.13](https://github.com/devuri/wpframework/compare/v0.3.12...v0.3.13) (2024-08-01)


### Bug Fixes

* adds `RAYDIUM_ENVIRONMENT_TYPE` Override for .env setup of `WP_ENVIRONMENT_TYPE`. ([13a29c9](https://github.com/devuri/wpframework/commit/13a29c9824c45517c1e7c09b9e1bd0227b186abc))


### Miscellaneous Chores

* build ([11de2f8](https://github.com/devuri/wpframework/commit/11de2f8417796623bf3eec4f9c6189038057470c))
* removes composer dump in admin settings ([6233bfe](https://github.com/devuri/wpframework/commit/6233bfe220d4d5027a13ef126fe8122f3d385082))
* update files and directory structure ([01f03fb](https://github.com/devuri/wpframework/commit/01f03fb5347282a892b698f1e9ee1093eaa85168))

## [0.3.12](https://github.com/devuri/wpframework/compare/v0.3.11...v0.3.12) (2024-07-22)


### Features

* adds `ThemeSwitcher` ([8098b73](https://github.com/devuri/wpframework/commit/8098b73d3fdf71c058317800de03d5e3fd3cd195))


### Bug Fixes

* maybe add `// TODO switch_theme('kadence')` ([086fdb2](https://github.com/devuri/wpframework/commit/086fdb289ec680cacb5ffa6350038010c1a7e6b2))
* show theme name if it is missing Update Plugin.php ([817da15](https://github.com/devuri/wpframework/commit/817da15cab846360f149358e32c0d1c4bd8d3383))

## [0.3.11](https://github.com/devuri/wpframework/compare/v0.3.10...v0.3.11) (2024-07-10)


### Bug Fixes

* `symfony/error-handler v5.0.11` Update composer.json ([652f132](https://github.com/devuri/wpframework/commit/652f132d318620237f5dc0325d5273f9be295e1e))

## [0.3.10](https://github.com/devuri/wpframework/compare/v0.3.9...v0.3.10) (2024-07-10)


### Bug Fixes

* use `"psr/log": "^1",` Update composer.json ([ad12a06](https://github.com/devuri/wpframework/commit/ad12a0619e5fbb235de4c9c6ee88b75f10d24578))

## [0.3.9](https://github.com/devuri/wpframework/compare/v0.3.8...v0.3.9) (2024-07-02)


### Features

* adds docs update guide ([72ca3cf](https://github.com/devuri/wpframework/commit/72ca3cf688c7e71298597211ce9676f5c39a511f))


### Bug Fixes

* fix update doc ([f896b73](https://github.com/devuri/wpframework/commit/f896b73c9501c8f7bc145d629aa5b88f0c08ea2a))


### Miscellaneous Chores

* **deps-dev:** bump braces from 3.0.2 to 3.0.3 ([49a25bb](https://github.com/devuri/wpframework/commit/49a25bb319844934e1df725b28cd79de4f041ba2))
* **deps-dev:** bump ws from 8.16.0 to 8.17.1 ([ed6682e](https://github.com/devuri/wpframework/commit/ed6682e9899696c9a5971d376adf920fb6a3a3e4))
* **deps:** update psr/log requirement from ^1.1 to ^1.1 || ^3.0 ([e86374e](https://github.com/devuri/wpframework/commit/e86374e432ca84f8157c2d5b24c8990faf7afe98))

## [0.3.8](https://github.com/devuri/wpframework/compare/v0.3.7...v0.3.8) (2024-05-24)


### Features

* adds Create rsync-strategy.md ([ecc8f29](https://github.com/devuri/wpframework/commit/ecc8f29a829d1d695527eb8918ed10a0d9926b2d))

## [0.3.7](https://github.com/devuri/wpframework/compare/v0.3.6...v0.3.7) (2024-05-24)


### Features

* docs add Bare Repository Strategy ([c25b2af](https://github.com/devuri/wpframework/commit/c25b2af480d03e5430d13c2d01f826542db5ebfb))

## [0.3.6](https://github.com/devuri/wpframework/compare/v0.3.5...v0.3.6) (2024-05-05)


### Bug Fixes

* adds `WP_DEVELOPMENT_MODE` in `wp_development_mode` [#102](https://github.com/devuri/wpframework/issues/102) ([b1a75f1](https://github.com/devuri/wpframework/commit/b1a75f100cb2474c6e117b40e40b83cdbdc8a918))
* adds `WP_DEVELOPMENT_MODE` in generator as `theme` and whitelist ([389d458](https://github.com/devuri/wpframework/commit/389d4584f98877afc28e3abff51f77635bbc8d8a))

## [0.3.5](https://github.com/devuri/wpframework/compare/v0.3.4...v0.3.5) (2024-05-03)


### Miscellaneous Chores

* build ([8d5b6b6](https://github.com/devuri/wpframework/commit/8d5b6b626aa4e86369fb003a51bc60f1ef7f3211))

## [0.3.4](https://github.com/devuri/wpframework/compare/v0.3.3...v0.3.4) (2024-05-03)


### Bug Fixes

* fixes CAN_DEACTIVATE_PLUGINS bool Update Plugin.php ([b5b6259](https://github.com/devuri/wpframework/commit/b5b62591da314dfb105176d4fd7d0a1d260b3f09))

## [0.3.3](https://github.com/devuri/wpframework/compare/v0.3.2...v0.3.3) (2024-05-03)


### Bug Fixes

* load framework `$default_configs` when no `app.php` is defined ([ae3e7f9](https://github.com/devuri/wpframework/commit/ae3e7f9d18e0eafe51251c7059e41794818897d0))

## [0.3.2](https://github.com/devuri/wpframework/compare/v0.3.1...v0.3.2) (2024-05-03)


### Bug Fixes

* wp moved to upstream Update composer.json ([1867677](https://github.com/devuri/wpframework/commit/1867677d2d03573a91a8787827a466d92fdfb105))

## [0.3.1](https://github.com/devuri/wpframework/compare/v0.3.0...v0.3.1) (2024-04-13)


### Bug Fixes

* always load `default_configs` ([3adbe84](https://github.com/devuri/wpframework/commit/3adbe84a6422f9af0ba916d91d5aeb8c23b7774c))

## [0.3.0](https://github.com/devuri/wpframework/compare/v0.2.16...v0.3.0) (2024-04-10)


### ⚠ BREAKING CHANGES

* update v0.3.0

### Features

* update v0.3.0 ([f567399](https://github.com/devuri/wpframework/commit/f5673996bc3df9c974d7224188301c1d1ce33e7e))

## [0.2.16](https://github.com/devuri/wpframework/compare/v0.2.15...v0.2.16) (2024-04-09)


### Bug Fixes

* update configuration file path ([1a89052](https://github.com/devuri/wpframework/commit/1a890528dd63c2c9c27b5de7d37d2e7c0c7b61c1))

## [0.2.15](https://github.com/devuri/wpframework/compare/v0.2.14...v0.2.15) (2024-04-03)


### Bug Fixes

* add `RAYDIUM_INSTALL_PROTECTION` required for install protection ([e420990](https://github.com/devuri/wpframework/commit/e420990323f108ab9b0eafefc6bd8e495d496933))

## [0.2.14](https://github.com/devuri/wpframework/compare/v0.2.13...v0.2.14) (2024-04-02)


### Bug Fixes

* update `gitattributes` ([0871000](https://github.com/devuri/wpframework/commit/08710001ae75a433b698ef497c809185a611a3c8))

## [0.2.13](https://github.com/devuri/wpframework/compare/v0.2.12...v0.2.13) (2024-04-02)


### Miscellaneous Chores

* adds change logs in docs ([e91f81f](https://github.com/devuri/wpframework/commit/e91f81f20625912a4c2f3714ab001b54ba68f854))

## [0.2.12](https://github.com/devuri/wpframework/compare/v0.2.11...v0.2.12) (2024-04-01)


### Bug Fixes

* mu-plugin version is 0.2x ([0d50e18](https://github.com/devuri/wpframework/commit/0d50e183173f7ee8425e798e223f634cb36ba346))


### Miscellaneous Chores

* refactor docs and update links ([31c72ce](https://github.com/devuri/wpframework/commit/31c72ce15a72a6918d807b331dc8281e3c91c412))

## [0.2.11](https://github.com/devuri/wpframework/compare/v0.2.10...v0.2.11) (2024-04-01)


### Miscellaneous Chores

* remove docs dir ([3bf1774](https://github.com/devuri/wpframework/commit/3bf177448c7e704aea30b9b368277eefa600ff7e))

## [0.2.10](https://github.com/devuri/wpframework/compare/v0.2.9...v0.2.10) (2024-04-01)


### Bug Fixes

* revert remove tailwindcss ([e6e9b58](https://github.com/devuri/wpframework/commit/e6e9b5865f8b15f505c966bcdd1a8d50fabe10ba))

## [0.2.9](https://github.com/devuri/wpframework/compare/v0.2.8...v0.2.9) (2024-03-31)


### Miscellaneous Chores

* build ([dd611b8](https://github.com/devuri/wpframework/commit/dd611b8d814a0619d985407251685adf86daded7))
* build ([47220d6](https://github.com/devuri/wpframework/commit/47220d6a2f41234e60f62ed5bf7fd67617bcff69))

## [0.2.8](https://github.com/devuri/wpframework/compare/v0.2.7...v0.2.8) (2024-03-31)


### Miscellaneous Chores

* build ([2384229](https://github.com/devuri/wpframework/commit/238422978d04352b11bb9ded82d3de45e73978fc))
* docs style update ([de46611](https://github.com/devuri/wpframework/commit/de466113ebffea2544df6eb685456561bbb89cd7))
* fix duplicate data in docs ([6415e77](https://github.com/devuri/wpframework/commit/6415e776a042f0deed46c12fa3a75efa1a868c45))

## [0.2.7](https://github.com/devuri/wpframework/compare/v0.2.6...v0.2.7) (2024-03-29)


### Bug Fixes

* docs `wpframework` base dir ([3045fb3](https://github.com/devuri/wpframework/commit/3045fb3243d94180e1b48c08017fb29546ad3167))
* docs base dir ([d851072](https://github.com/devuri/wpframework/commit/d851072cc527d4ddc006af3d5e17f78e2ab3ee80))
* docs base dir ([91a6bb9](https://github.com/devuri/wpframework/commit/91a6bb9700ad1601f4fd1ff3303ef2be2e63c7d1))


### Miscellaneous Chores

* build out the new docs ([c57caff](https://github.com/devuri/wpframework/commit/c57caff9c369bf822de85f39569b1da67d721602))

## [0.2.6](https://github.com/devuri/wpframework/compare/v0.2.5...v0.2.6) (2024-03-29)


### Bug Fixes

* update docs ([51718f0](https://github.com/devuri/wpframework/commit/51718f01fb3355c404c466ffff8885a9ecd63a3f))

## [0.2.5](https://github.com/devuri/wpframework/compare/v0.2.4...v0.2.5) (2024-03-29)


### Miscellaneous Chores

* docs file location, adds documentation directory for vitepress ([51846e4](https://github.com/devuri/wpframework/commit/51846e470e763425647f537bb26b658dfc0aca8f))
* public docs dir ([1109ae5](https://github.com/devuri/wpframework/commit/1109ae597dc2c356e3d712f3e5620181edb93a20))

## [0.2.4](https://github.com/devuri/wpframework/compare/v0.2.3...v0.2.4) (2024-03-28)


### Bug Fixes

* use `DotAccess` in kernel ([9a7c8bf](https://github.com/devuri/wpframework/commit/9a7c8bfe0e8e03e9e347e08009b9e589f98e72e4))


### Miscellaneous Chores

* build ([1179190](https://github.com/devuri/wpframework/commit/117919025be070fddbed96e80b185eed6fe4a6c1))

## [0.2.3](https://github.com/devuri/wpframework/compare/v0.2.2...v0.2.3) (2024-03-28)


### Bug Fixes

* fixes env() func `$default` value ([64c41e1](https://github.com/devuri/wpframework/commit/64c41e1cfe9f7989a9ce167cd774c547aac84913))


### Miscellaneous Chores

* build ([9b2c9a9](https://github.com/devuri/wpframework/commit/9b2c9a969bcdde545fac6739fdb70a147fe7606c))

## [0.2.2](https://github.com/devuri/wpframework/compare/v0.2.1...v0.2.2) (2024-03-26)


### Miscellaneous Chores

* build ([e25cd9a](https://github.com/devuri/wpframework/commit/e25cd9a1787800af24cb0a325d72057f41fa597b))

## [0.2.1](https://github.com/devuri/wpframework/compare/v0.2.0...v0.2.1) (2024-03-25)


### Features

* new env files should default to `WP_ENVIRONMENT_TYPE='secure'` ([5425856](https://github.com/devuri/wpframework/commit/54258569534387b90f5e99ae63638f71a0131340))

## [0.2.0](https://github.com/devuri/wpframework/compare/v0.1.0...v0.2.0) (2024-03-24)


### ⚠ BREAKING CHANGES

* streamline app setup with `app_kernel()`

### Features

* adds `environment switcher class::Switcher` ([3875fd8](https://github.com/devuri/wpframework/commit/3875fd8be63d4a22fa47076bcef48dd20bec4192))
* generate new `.env` file if none is available ([e061e9b](https://github.com/devuri/wpframework/commit/e061e9b3abaa2cc6fd70daf6d0e0ae7f31f1fac8))


### Bug Fixes

* adds water.css ([969ed3a](https://github.com/devuri/wpframework/commit/969ed3a3568b18c7d7a17861322f46c9e2a004a3))
* fixes `WP_ENVIRONMENT_TYPE` type check since it may not be set ([0c83d2a](https://github.com/devuri/wpframework/commit/0c83d2accda698c0cbda8ff7fc2db961d25eb195))
* streamline app setup with `app_kernel()` ([141c373](https://github.com/devuri/wpframework/commit/141c37330b0127db0b13d126f1a09afda636b2c9))


### Miscellaneous Chores

* build ([164853c](https://github.com/devuri/wpframework/commit/164853c990acbba41320fb81473279eb1699716b))

## [0.1.0](https://github.com/devuri/wpframework/compare/v0.0.9...v0.1.0) (2024-03-16)


### ⚠ BREAKING CHANGES

* Simplify and add Framework update for MU Plugin

### Features

* Simplify and add Framework update for MU Plugin ([7e13b19](https://github.com/devuri/wpframework/commit/7e13b1944a6ea3e4cfae50eb9d00438cb7880a86))


### Miscellaneous Chores

* build ([12ebd43](https://github.com/devuri/wpframework/commit/12ebd4303602fd4cf30f9db0cb8ce0e10b227b51))

## [0.0.9](https://github.com/devuri/wpframework/compare/v0.0.8...v0.0.9) (2024-03-15)


### Miscellaneous Chores

* update readme ([8cc5622](https://github.com/devuri/wpframework/commit/8cc5622726807791b352f2b624ebea0522b2aa41))

## [0.0.8](https://github.com/devuri/wpframework/compare/v0.0.7...v0.0.8) (2024-03-15)


### Bug Fixes

* adds debug to `Terminate::exit` ([15d82d7](https://github.com/devuri/wpframework/commit/15d82d73c65fc220eeceb4bbd5e1c38875c90e20))


### Miscellaneous Chores

* build ([45dd5e7](https://github.com/devuri/wpframework/commit/45dd5e74adf147e74fec7c408c9416a329e8f81e))

## [0.0.7](https://github.com/devuri/wpframework/compare/v0.0.6...v0.0.7) (2024-03-14)


### Bug Fixes

* adds `terminate_debugger` it can show debug info on app termination ([951d424](https://github.com/devuri/wpframework/commit/951d424b048e2dafd38bc368789d2dafdb6173de))
* update the env file vars include missing values ([e23eb16](https://github.com/devuri/wpframework/commit/e23eb165d24f40dea509b967b6bde5c48106e8a0))

## [0.0.6](https://github.com/devuri/wpframework/compare/v0.0.5...v0.0.6) (2024-03-14)


### Miscellaneous Chores

* fix links ([ea7863a](https://github.com/devuri/wpframework/commit/ea7863a8c092fa5246c4e3b35994dc993eea0dd8))

## [0.0.5](https://github.com/devuri/wpframework/compare/v5.1.1...v0.0.5) (2024-03-14)


### ⚠ BREAKING CHANGES

* reverted back version numbers from the 5.x range to 0.0.5

### Code Refactoring

* reverted back version numbers from the 5.x range to 0.0.5 ([097074f](https://github.com/devuri/wpframework/commit/097074f9a3dd0b7a0dd6beaa598737c46ea70992))

## [5.1.1](https://github.com/devuri/wpframework/compare/v5.1.0...v5.1.1) (2024-03-13)


### Bug Fixes

* add error message for missing theme instead of white screen of death ([394341c](https://github.com/devuri/wpframework/commit/394341cd6bb64b2b0600bb2ceef5e17b7e86aad5))
* configs dir for single sites ([d541f48](https://github.com/devuri/wpframework/commit/d541f4875256ebff9f814c75dfb547c8761086ee))
* DotAccess get correct array data ([eaa43be](https://github.com/devuri/wpframework/commit/eaa43be60fdf63d34bdf962060df8b413c370b01))
* for when using localwp. ([bb7b26c](https://github.com/devuri/wpframework/commit/bb7b26cb00c2691e9a08b0c5d1fc72a3d64bfb66))


### Miscellaneous Chores

* build ([3386faf](https://github.com/devuri/wpframework/commit/3386faf135c5df45769fc14753c7818aa5cc4bb8))
* build ([7379b5f](https://github.com/devuri/wpframework/commit/7379b5fe92d6dd38cf8849b7e8495a7b1e30a9cf))

## [5.1.0](https://github.com/devuri/wp-framework/compare/v5.0.4...v5.1.0) (2024-03-03)


### Features

* adds `get_packages()` ([48fc7e1](https://github.com/devuri/wp-framework/commit/48fc7e1de51967ec5f75dc261910d8e59d3d8f42))


### Bug Fixes

* add whitelisted array for wp and framework conts ([cbdf868](https://github.com/devuri/wp-framework/commit/cbdf868cdeb7e2c93d132b004007835ba4489856))
* adds landlord to env whitelist ([03f4eca](https://github.com/devuri/wp-framework/commit/03f4eca1e691d9fcf4b52332b99b70506a28c248))
* use `devuri/env` for `env()` ([9a66679](https://github.com/devuri/wp-framework/commit/9a6667959e8c62f821d93cc60574152fbdb1c4f0))


### Miscellaneous Chores

* build ([24bb046](https://github.com/devuri/wp-framework/commit/24bb0465370c3e2ae58dd1cff6abba7408d2f22e))
* codefix ([e743b89](https://github.com/devuri/wp-framework/commit/e743b89b319d06dd597e536deed9af4c2faa68fa))
* **deps-dev:** update vimeo/psalm requirement || ^5.0 ([e32b794](https://github.com/devuri/wp-framework/commit/e32b79401a99ba9693472b3dc7fd48876ca91b08))

## [5.0.4](https://github.com/devuri/wp-framework/compare/v5.0.3...v5.0.4) (2024-02-18)


### Bug Fixes

* rename `config` to `configs` for configs directory ([73839a6](https://github.com/devuri/wp-framework/commit/73839a6c2381453d90a3ea9acc6e79808723c4aa))
* update `Terminate` to include `log_exception` for monitoring tools ([d3c6ca9](https://github.com/devuri/wp-framework/commit/d3c6ca96a21ac4cf57d99379ecdbc52b019dc3bb))


### Miscellaneous Chores

* build ([a9f30d2](https://github.com/devuri/wp-framework/commit/a9f30d2b656f09a4f5e1a2ba684cb971f5ac215f))
* build ([0b3c2ec](https://github.com/devuri/wp-framework/commit/0b3c2ec2f24799e9ce9713597377052a9994cb83))

## [5.0.3](https://github.com/devuri/wp-framework/compare/v5.0.2...v5.0.3) (2024-02-16)


### Bug Fixes

* add `is_required_tenant_config()` check ([f662e20](https://github.com/devuri/wp-framework/commit/f662e20411d505290c9cce1e185d14ee704f2817))
* only apply tenant uploads filter if is multi tenant ([f53e80a](https://github.com/devuri/wp-framework/commit/f53e80ab2c6f3e15da650db6dff5e2ae63dc5f73))


### Miscellaneous Chores

* build ([432ce2f](https://github.com/devuri/wp-framework/commit/432ce2fd5989434cc0d41f1ed9e255fd8eba0733))

## [5.0.2](https://github.com/devuri/wp-framework/compare/v5.0.1...v5.0.2) (2024-02-16)


### Bug Fixes

* adds `/.maintenance` check for the config dir, to handle entire tenant network. ([b49eccc](https://github.com/devuri/wp-framework/commit/b49ecccdf2bb5c46fe884ad3abdc221030bb6917))
* adds `is_landlord()` check for conditional plugin options ([4de5a27](https://github.com/devuri/wp-framework/commit/4de5a27e2a8bde701902c2855383cbe24f12efd0))
* fix environment reset using `is_environment_null()` it will check null empty 0 etc ([30e3926](https://github.com/devuri/wp-framework/commit/30e39264bcb6c7b7d8ca7c72b8a2a11a099f33bc))
* update fix env return on null as empty string ([2dde351](https://github.com/devuri/wp-framework/commit/2dde3514842c4ad28dc2dc4787799c39d7a1d69c))
* uuid checks do not hash `LANDLORD_UUID` ([f079131](https://github.com/devuri/wp-framework/commit/f079131fd0fb5557f6c4cd35e497d5ec3ade9e81))


### Miscellaneous Chores

* build ([b502dcb](https://github.com/devuri/wp-framework/commit/b502dcb6d8d7ee653dbc3ce4c44d8664dd90469b))
* build ([14dea95](https://github.com/devuri/wp-framework/commit/14dea9582bad69d3281da02d46937461a2c1ecaa))

## [5.0.1](https://github.com/devuri/wp-framework/compare/v5.0.0...v5.0.1) (2024-02-16)


### Bug Fixes

* required options, each tenant, must have their own configuration ([52c04eb](https://github.com/devuri/wp-framework/commit/52c04eb2e9a32971dcc3a6f3ae2401f2f3f36943))


### Miscellaneous Chores

* build ([cac6f96](https://github.com/devuri/wp-framework/commit/cac6f96b0e8820d6e730246b5dd782fefda76905))
* build ([8d8df5a](https://github.com/devuri/wp-framework/commit/8d8df5aa91028f6e4391a954d16c5a754e6101cc))
* build ([ba2f6d3](https://github.com/devuri/wp-framework/commit/ba2f6d311b1f10d40f649f545fa01c698cf49b10))

## [5.0.0](https://github.com/devuri/wp-framework/compare/v4.0.0...v5.0.0) (2024-02-16)


### ⚠ BREAKING CHANGES

* use camelCase for function names. start with a verb indicating the action they perform.

### Features

* use camelCase for function names. start with a verb indicating the action they perform. ([5980b90](https://github.com/devuri/wp-framework/commit/5980b90cf90df011cd37a05fbfd76f65060ad98f))


### Miscellaneous Chores

* build ([140efd5](https://github.com/devuri/wp-framework/commit/140efd5e6503b19f5e648624ff3aa507ea7176d0))
* build ([c370335](https://github.com/devuri/wp-framework/commit/c3703352b66bce11c5be57a745fe5ef357dd5db4))
* build ([2b6ea06](https://github.com/devuri/wp-framework/commit/2b6ea063a9ed10d21cc439251a0258d537c29ac5))
* build ([c9f2c51](https://github.com/devuri/wp-framework/commit/c9f2c51a11dd7e0c91df1b9fe25f5d5238afc464))
* codefix ([c9ebae2](https://github.com/devuri/wp-framework/commit/c9ebae221d231cb0f0a44b46cc14aa69f4c45b80))
* fix test ([45c84bf](https://github.com/devuri/wp-framework/commit/45c84bf193f9bbee59c8aa38fb9f3208b43a06ee))
* readme ([bac1773](https://github.com/devuri/wp-framework/commit/bac1773619dc2cdf084087da5b46ace7cdf0805b))

## [4.0.0](https://github.com/devuri/wp-framework/compare/v3.3.0...v4.0.0) (2024-02-14)


### ⚠ BREAKING CHANGES

* rebrand moved `wp-env-config` is now `wp-framework` and `wp-env-app` is now `wptenancy`

### Features

* rebrand moved `wp-env-config` is now `wp-framework` and `wp-env-app` is now `wptenancy` ([7363ada](https://github.com/devuri/wp-framework/commit/7363ada192f176ccf91b81f97779ee83f5315d67))


### Miscellaneous Chores

* build ([b350065](https://github.com/devuri/wp-framework/commit/b350065a3997be3c134a6692a7c507d240daafae))

## [3.3.0](https://github.com/devuri/wp-env-config/compare/v3.2.0...v3.3.0) (2024-02-14)


### Features

* adds md5 hash for `APP_TENANT_ID` ([775b00b](https://github.com/devuri/wp-env-config/commit/775b00ba920b363ae0cf07922d401e3ea792cf8a))
* in `tenancy.php` we can override `PUBLIC_WEB_DIR` and `APP_CONTENT_DIR` ([74b6524](https://github.com/devuri/wp-env-config/commit/74b6524c8b0ddf816335eadb6f11d99097bd133d))
* tenant ID is always set even in non multi-tenat ([6d1fdae](https://github.com/devuri/wp-env-config/commit/6d1fdaed789aad40df2e51d835bda7fa0f88a603))
* use `wp_terminate` for `maintenance` which now can be per tenant ([baba0c9](https://github.com/devuri/wp-env-config/commit/baba0c97d5254500fc603802744e741cc08b3bec))


### Bug Fixes

* add tenat_id in admin footer ([a474e79](https://github.com/devuri/wp-env-config/commit/a474e790f65db2a035f21265eea1ce844968b1e8))
* adds plugin loader to load plugins via `option_active_plugins` filter ([9f453dd](https://github.com/devuri/wp-env-config/commit/9f453ddf9dcab26e7ed538631fb9d9fa0fdfb7c9))
* APP_TENANT_ID can be set to false in the .env to short-cercuit the custom uploads directory behavior. ([66763cc](https://github.com/devuri/wp-env-config/commit/66763cc0a3e4d96e77055bd99540042af78fa4bc))
* is `env( 'IS_MULTITENANT' )` ([290089a](https://github.com/devuri/wp-env-config/commit/290089ab5a7e719c0fc6a5d888afc504db242929))
* update `APP_CONTENT_DIR` so it does not require `/` ([24069bf](https://github.com/devuri/wp-env-config/commit/24069bf608bd0e722f37d88f813caaa990a43625))


### Miscellaneous Chores

* build ([c341a83](https://github.com/devuri/wp-env-config/commit/c341a835ac71683855951678f346ed29e3dfd3b4))
* build ([7062cba](https://github.com/devuri/wp-env-config/commit/7062cba6c555f615e994229903bb8c2836cc2b01))
* codefix ([e8ed662](https://github.com/devuri/wp-env-config/commit/e8ed6625ae8fcda5ccb1ccf7f0a37553ae309da8))

## [3.2.0](https://github.com/devuri/wp-env-config/compare/v3.1.2...v3.2.0) (2024-02-12)


### Features

* add `REQUIRE_TENANT_CONFIG` enforces a strict requirement ([60fe441](https://github.com/devuri/wp-env-config/commit/60fe4411f61d963129de089b01433092b22de951))
* enable Tenant-Specific Configuration with app.php ([b5b9c23](https://github.com/devuri/wp-env-config/commit/b5b9c23047dcfbc10f486b65aef99c1845067632))


### Miscellaneous Chores

* build ([351a624](https://github.com/devuri/wp-env-config/commit/351a6249c8404be8a8352867e1b9ca2488fb7637))
* build ([d74a24e](https://github.com/devuri/wp-env-config/commit/d74a24ea780a99ea7e69b934035a1d36967c6c11))
* fix file path ([d441619](https://github.com/devuri/wp-env-config/commit/d441619de1c7856128ee90c7bc077554e4680c55))

## [3.1.2](https://github.com/devuri/wp-env-config/compare/v3.1.1...v3.1.2) (2024-02-11)


### Bug Fixes

* better message for missing landlord data ([73447a1](https://github.com/devuri/wp-env-config/commit/73447a1fc415c4c953694364a55963657b49e0b9))

## [3.1.1](https://github.com/devuri/wp-env-config/compare/v3.1.0...v3.1.1) (2024-02-11)


### Bug Fixes

* do not show message it may contain dir info and full serv path ([d2acbc0](https://github.com/devuri/wp-env-config/commit/d2acbc0a7f62a06810bec4c42a8a027805e926a6))

## [3.1.0](https://github.com/devuri/wp-env-config/compare/v3.0.1...v3.1.0) (2024-02-11)


### Features

* auto generate env file if it does not exist ([58e8214](https://github.com/devuri/wp-env-config/commit/58e821446c35a8835876d9c2e901bd8761aab553))


### Miscellaneous Chores

* build ([48020ac](https://github.com/devuri/wp-env-config/commit/48020ac7c07da4d445075368f84176a9a537152c))

## [3.0.1](https://github.com/devuri/wp-env-config/compare/v3.0.0...v3.0.1) (2024-02-10)


### Miscellaneous Chores

* refactor ([95dbd68](https://github.com/devuri/wp-env-config/commit/95dbd68018e23ac33887e62d98f2e44b946e6f72))

## [3.0.0](https://github.com/devuri/wp-env-config/compare/v2.2.1...v3.0.0) (2024-02-10)


### ⚠ BREAKING CHANGES

* get tenants from database table `tenants` requires plugin `Tenancy`

### Features

* get tenant from DB for multi-tenant support ([6854fdf](https://github.com/devuri/wp-env-config/commit/6854fdf0248fa6811eebe3578066eff3eceb23e6))
* get tenants from database table `tenants` requires plugin `Tenancy` ([363d2c0](https://github.com/devuri/wp-env-config/commit/363d2c00e2e08c0ecaa68cb405f9cb03e4484836))
* simplify multi-tenant switching using defined database tables ([63936ce](https://github.com/devuri/wp-env-config/commit/63936cefc87a39016247bf5dc48d2ce0835bc192))


### Bug Fixes

* add _is_multitenant() ([a528712](https://github.com/devuri/wp-env-config/commit/a5287126aeb052375529f1d2f04e34ef7e6ae32a))


### Miscellaneous Chores

* add const stubs in test mode ([1727b34](https://github.com/devuri/wp-env-config/commit/1727b34af2f035f04d240346801f578c2a0a652f))
* build ([1c9b299](https://github.com/devuri/wp-env-config/commit/1c9b29986b094229678b2d309bb4795c41834101))
* build ([1a82379](https://github.com/devuri/wp-env-config/commit/1a82379a2a08d3218f124e0c086b3952786c3254))

## [2.2.1](https://github.com/devuri/wp-env-config/compare/v2.2.0...v2.2.1) (2024-02-08)


### Bug Fixes

* adds `EnvTypes` class ([721bcec](https://github.com/devuri/wp-env-config/commit/721bcec9900d1ff60766ccbfa5917dd98ebf3819))
* fix config location ([05a53d9](https://github.com/devuri/wp-env-config/commit/05a53d9c4a26fe7bfb233128c4539e3b23ae2838))
* if use `WP_ENVIRONMENT_TYPE` if defined in `config` will override  .env ([dfcc1ea](https://github.com/devuri/wp-env-config/commit/dfcc1ea792df59e2f3a638224f9f045513df94ce))


### Miscellaneous Chores

* build ([8e37399](https://github.com/devuri/wp-env-config/commit/8e37399c2aaf07578e700973d292b32b0a089950))

## [2.2.0](https://github.com/devuri/wp-env-config/compare/v2.1.1...v2.2.0) (2024-02-08)


### Features

* add `AppHostManager` to streamline how we handle http, replaces http functions ([a7d10d1](https://github.com/devuri/wp-env-config/commit/a7d10d1712886664c6140ea490d34489e85accb8))
* adds new `wp_terminate()` function  ([116af93](https://github.com/devuri/wp-env-config/commit/116af930cc2a9b12e0d0801326e32cbd044d2bf5))
* Improvements for multi-tenant applications ([e32166c](https://github.com/devuri/wp-env-config/commit/e32166cb86a8f62a11468d15affd1c15cab27f9c))


### Bug Fixes

* ensure we have tenant ID ([b3e392c](https://github.com/devuri/wp-env-config/commit/b3e392c33a137171776a908a930bab15bc453f4c))
* fix FILTER_UNSAFE_RAW ([2b4c2df](https://github.com/devuri/wp-env-config/commit/2b4c2df8fef01cd355e55767418686f202209ae2))
* Loads tenant-specific  `config.php` or default `config.php` ([b31d7bb](https://github.com/devuri/wp-env-config/commit/b31d7bb094db6fdf96ad7d542271791598fe3884))
* separate uploads for multi tenant. ([9c8d82c](https://github.com/devuri/wp-env-config/commit/9c8d82c1a5382ac01769ccc2213b4accbe589a4a))
* user needs 'manage_tenant' or Remove delete action link for plugins ([f3df3ec](https://github.com/devuri/wp-env-config/commit/f3df3ecd762c9713f58c3512c449a11850bcbbf2))


### Miscellaneous Chores

* build ([a061004](https://github.com/devuri/wp-env-config/commit/a061004669cec856e49009e7fec03d03d7af576b))
* build ([d81320c](https://github.com/devuri/wp-env-config/commit/d81320c3af6033d6a65a76aee343a91ac4db857b))
* codefix ([6cfc2e3](https://github.com/devuri/wp-env-config/commit/6cfc2e366daac12a78a154bbd85128506e9d03d2))
* update tests ([afa5b5f](https://github.com/devuri/wp-env-config/commit/afa5b5fa57e5c374cb7792a026da7c118dcbee4e))

## [2.1.1](https://github.com/devuri/wp-env-config/compare/v2.1.0...v2.1.1) (2024-02-06)


### Bug Fixes

* include psr/log version to fix php7.4 errors ([e9b038d](https://github.com/devuri/wp-env-config/commit/e9b038d07d502830077c842217c92b8f1f4c525f))
* psr fix ([d78fecf](https://github.com/devuri/wp-env-config/commit/d78fecff3b2c915c36010be5fa59c583514b4cda))
* Update symfony/error-handler ([4222d9a](https://github.com/devuri/wp-env-config/commit/4222d9a9a266fde3021d7dbecd897e053b0b7181))

## [2.1.0](https://github.com/devuri/wp-env-config/compare/v2.0.0...v2.1.0) (2024-01-05)


### Features

* add `get_default_file_names()` to retrieves the default file names ([e705f0e](https://github.com/devuri/wp-env-config/commit/e705f0eaa7bc78df1a667b6e30fe3282406ef8df))


### Bug Fixes

* update public key handler ([85713aa](https://github.com/devuri/wp-env-config/commit/85713aa115410a1636211d109206d129317083c2))


### Miscellaneous Chores

* build ([56bca88](https://github.com/devuri/wp-env-config/commit/56bca88224b605498d93be611cca5760a12f6847))

## [2.0.0](https://github.com/devuri/wp-env-config/compare/v1.3.3...v2.0.0) (2023-12-09)


### ⚠ BREAKING CHANGES

* re `App.php` to `Urisoft\App\Http\AppFramework`

### Miscellaneous Chores

* build ([10289e9](https://github.com/devuri/wp-env-config/commit/10289e9b9ff276c4c59095235e37c027c1db7f46))


### Code Refactoring

* re `App.php` to `Urisoft\App\Http\AppFramework` ([defe801](https://github.com/devuri/wp-env-config/commit/defe80168d477f4420061bbf4aacf7e9f08ada8f))

## [1.3.3](https://github.com/devuri/wp-env-config/compare/v1.3.2...v1.3.3) (2023-11-03)


### Bug Fixes

* add `app_sanitizer()` ([bdfd3cc](https://github.com/devuri/wp-env-config/commit/bdfd3ccf85536faccac27dd36f79504247d64a70))
* env local added todefault files ([4a563fb](https://github.com/devuri/wp-env-config/commit/4a563fbc58c5aa260830d6208301a2aa8a8ff947))
* exit for when tenant is not defined ([bf058a7](https://github.com/devuri/wp-env-config/commit/bf058a798be90e49e6941be23ce605adfdf6930d))

## [1.3.2](https://github.com/devuri/wp-env-config/compare/v1.3.1...v1.3.2) (2023-11-02)


### Bug Fixes

* remove 'manage_options' check ([a82e43d](https://github.com/devuri/wp-env-config/commit/a82e43db6f90af29d4e91e7b252a2aec40e7d8b3))

## [1.3.1](https://github.com/devuri/wp-env-config/compare/v1.3.0...v1.3.1) (2023-11-02)


### Bug Fixes

* fixes the elementor pro wpenv auto activation ([fe7cb72](https://github.com/devuri/wp-env-config/commit/fe7cb722abfdcb5232cc353995e8ecc9d38eaade))

## [1.3.0](https://github.com/devuri/wp-env-config/compare/v1.2.0...v1.3.0) (2023-11-02)


### Features

* adds plugins list admin page ([c929c9d](https://github.com/devuri/wp-env-config/commit/c929c9d277d20664ba7874d6c2a741480b318bc8))


### Miscellaneous Chores

* **master:** release 1.2.0 ([f68727a](https://github.com/devuri/wp-env-config/commit/f68727a39260509d85be6291aad155f5476e4303))

## [1.2.0](https://github.com/devuri/wp-env-config/compare/v1.1.0...v1.2.0) (2023-11-01)


### Features

* add option to Auto-Activate Elementor as Hourly Cron. ([08fec09](https://github.com/devuri/wp-env-config/commit/08fec09efca45028cd5aee7de0fadec613d58663))
* disable `application_passwords` in .env file ([c3df479](https://github.com/devuri/wp-env-config/commit/c3df479fab54612ffc03965dc7a0b5fdab83717f))


### Bug Fixes

* fixes the core app events runner ([844cf5b](https://github.com/devuri/wp-env-config/commit/844cf5b70a4b1122b2627fff658db2c3cc8e1e09))
* use upgraded encryption 0.3 ([2d479fb](https://github.com/devuri/wp-env-config/commit/2d479fb1782b5096f3a35055195cd3ce237267a6))


### Miscellaneous Chores

* build ([500918d](https://github.com/devuri/wp-env-config/commit/500918dbf94c31ff714bdafc029c2b5ddb8fb894))

## [1.1.0](https://github.com/devuri/wp-env-config/compare/v1.0.5...v1.1.0) (2023-10-28)


### Features

* update encryption to `0.2` ([a96eb0a](https://github.com/devuri/wp-env-config/commit/a96eb0a50c41acc8905bd5752d51e492d04034bf))

## [1.0.5](https://github.com/devuri/wp-env-config/compare/v1.0.4...v1.0.5) (2023-10-28)


### Miscellaneous Chores

* build ([908c8b1](https://github.com/devuri/wp-env-config/commit/908c8b197708701e7062ac13176aab23fdadf119))

## [1.0.4](https://github.com/devuri/wp-env-config/compare/v1.0.3...v1.0.4) (2023-10-28)


### Bug Fixes

* new env function fix ([5eeef88](https://github.com/devuri/wp-env-config/commit/5eeef882535b1a463ec1debb8abe4b11c78bea9a))


### Miscellaneous Chores

* build ([b792b53](https://github.com/devuri/wp-env-config/commit/b792b53d33e3de8f48ff749ac5de52b783669ef8))

## [1.0.3](https://github.com/devuri/wp-env-config/compare/v0.8.2...v1.0.3) (2023-10-27)


### Features

* adds Generates a list of WordPress plugins in Composer format ([1f45e9c](https://github.com/devuri/wp-env-config/commit/1f45e9ca951674ce1c8783004770eacbded919c6))


### Miscellaneous Chores

* build ([3badbef](https://github.com/devuri/wp-env-config/commit/3badbefc9e734e197fd43b9650ee70b6a0022625))

## [0.8.2](https://github.com/devuri/wp-env-config/compare/v0.8.1...v0.8.2) (2023-10-26)


### Features

* Adds a 'WP is not installed' message for dev environments. ([f8b5189](https://github.com/devuri/wp-env-config/commit/f8b51897a9759e9445306649c7d299a0fe39b320))


### Bug Fixes

* adds required `IS_MULTI_TENANT_APP` in .env ([d1ea36c](https://github.com/devuri/wp-env-config/commit/d1ea36c5f4253924bf18f5d9a8f4e088f40dee9c))


### Miscellaneous Chores

* build ([672f12c](https://github.com/devuri/wp-env-config/commit/672f12c7b714bc3bf3283aa00d602496af3e6abb))

## [0.8.1](https://github.com/devuri/wp-env-config/compare/v0.8.0...v0.8.1) (2023-10-23)


### Bug Fixes

* fixes updates on bool ([cea92af](https://github.com/devuri/wp-env-config/commit/cea92af60b266a8a9a406396ce0cf1cb8c1e34a7))

## [0.8.0](https://github.com/devuri/wp-env-config/compare/v0.7.7...v0.8.0) (2023-10-21)


### ⚠ BREAKING CHANGES

* Multi-Tenant Support

### Features

* add filter `wpenv_powered_by` ([19670d9](https://github.com/devuri/wp-env-config/commit/19670d9a4f3055aa5a02ee466d3ace6173249f49))
* adds Color Scheme ([2f6d645](https://github.com/devuri/wp-env-config/commit/2f6d6459aae53ad3267c1fcbfb3384c140f0ac95))
* Multi-Tenant Support ([70b4f02](https://github.com/devuri/wp-env-config/commit/70b4f02b4230fcce59ded6365a176d87e2cb6053))
* WIP adds `multi_tenant` support ([b87c5fe](https://github.com/devuri/wp-env-config/commit/b87c5feece6b9e51fb7122739e38a67377097e11))


### Bug Fixes

* adds `$http_env_type` property ([072d023](https://github.com/devuri/wp-env-config/commit/072d02352abedb9b6173eb017707ceac0e7b6f52))
* get_user_by can be false ([9ed3826](https://github.com/devuri/wp-env-config/commit/9ed3826c38cab42d70cc9f83031606eb1c5553c8))


### Miscellaneous Chores

* build ([7d68095](https://github.com/devuri/wp-env-config/commit/7d68095829788df53f465f0c6f2df537276a0f95))
* build ([1face78](https://github.com/devuri/wp-env-config/commit/1face780ff4a4c5f5b8e669ab8f688130c87e8d1))

## [0.7.7](https://github.com/devuri/wp-env-config/compare/v0.7.6...v0.7.7) (2023-09-25)


### Features

* adds `wpenv_auto_login` action and wp_user_exists() method ([3f24141](https://github.com/devuri/wp-env-config/commit/3f241410192081dce309cbfe47e3263dfca4f8be))

## [0.7.6](https://github.com/devuri/wp-env-config/compare/v0.7.5...v0.7.6) (2023-09-24)


### Bug Fixes

* adds sub domain for Integrated Version Control label ([4d69a2d](https://github.com/devuri/wp-env-config/commit/4d69a2d9af8af5c2cb2a573e4232958705c839e8))
* fixes count on available updates ([7f8ac5e](https://github.com/devuri/wp-env-config/commit/7f8ac5ebb92a71ee25ec9aa6a205463c03c0f70d))
* removes `get_core_updates` use `transient( 'update_core' )` ([71af3b6](https://github.com/devuri/wp-env-config/commit/71af3b6d98af61811ae693762be16fcfd9ee6261))

## [0.7.5](https://github.com/devuri/wp-env-config/compare/v0.7.4...v0.7.5) (2023-09-23)


### Bug Fixes

* remove console split to standalone repo `devuri/wpenv-console` ([e88794d](https://github.com/devuri/wp-env-config/commit/e88794d70830f82a0d87dd2926ce548b555aef4f))

## [0.7.4](https://github.com/devuri/wp-env-config/compare/v0.7.3...v0.7.4) (2023-09-15)


### Bug Fixes

* debug log dates use 'm-d-Y' ([001ac17](https://github.com/devuri/wp-env-config/commit/001ac17703e62ea11d092f39962fa0b8b666ca2d))

## [0.7.3](https://github.com/devuri/wp-env-config/compare/v0.7.2...v0.7.3) (2023-09-15)


### Features

* adds `get_user_constants()` and `get_server_env()` ([4d4aa8a](https://github.com/devuri/wp-env-config/commit/4d4aa8ab66b354c5c3365cd7c37472c2e13c7423))
* adds a way to Display Updates in Secure Environment ([dc30ff8](https://github.com/devuri/wp-env-config/commit/dc30ff8d9b88468c67c17a4a08b5c0c95f06a8c1))
* adds loginkey so we can do `nino config loginkey` ([78394c2](https://github.com/devuri/wp-env-config/commit/78394c261d890350d2c1cc14bb34b94a6367a5b0))


### Bug Fixes

* adds message saying the key wass added ([2c8642b](https://github.com/devuri/wp-env-config/commit/2c8642bb1d876f92ead4e66f99167cd0db5797d1))
* adds some context on failed auto login ([2ceacd8](https://github.com/devuri/wp-env-config/commit/2ceacd853e26dae41ee8e5c9262f1b86f43646a7))
* update package installer: `php nino n:i -p theme brisko` ([e48a1f5](https://github.com/devuri/wp-env-config/commit/e48a1f5852557d933ec0745ea83351fd1acac83a))


### Miscellaneous Chores

* build ([38f356c](https://github.com/devuri/wp-env-config/commit/38f356c347c519145424d135b17b1d442117fc98))

## [0.7.2](https://github.com/devuri/wp-env-config/compare/v0.7.1...v0.7.2) (2023-09-10)


### Bug Fixes

* `ConstantConfigTrait` is now `ConstantBuilderTrait` ([f24c5f8](https://github.com/devuri/wp-env-config/commit/f24c5f861bb4ef0a142226479d49a0555c1f18f4))


### Miscellaneous Chores

* adds test ([d572ab1](https://github.com/devuri/wp-env-config/commit/d572ab167aab73fc629aff7067a0cf11b8f1b262))
* codefix ([b91b91f](https://github.com/devuri/wp-env-config/commit/b91b91f6b0a76daa058b94ebb47a4e82ab47840c))
* docs ([45740ec](https://github.com/devuri/wp-env-config/commit/45740ec19cee0eeb1dbef880717276058178c0e5))

## [0.7.1](https://github.com/devuri/wp-env-config/compare/v0.7.0...v0.7.1) (2023-09-09)


### Bug Fixes

* configMap() is get_constant_map() ([c537d75](https://github.com/devuri/wp-env-config/commit/c537d75f6a9297a9b6fb5e6ef7afdb4b689ced90))

## [0.7.0](https://github.com/devuri/wp-env-config/compare/v0.6.6...v0.7.0) (2023-09-09)


### ⚠ BREAKING CHANGES

* replace `ConfigTrait` with `ConstantConfigTrait`

### Features

* replace `ConfigTrait` with `ConstantConfigTrait` ([780aa6f](https://github.com/devuri/wp-env-config/commit/780aa6fec5783cbe318a86561a7b248af3a8c6d5))


### Bug Fixes

* `Environment` is now `EnvironmentSwitch` adds `EnvInterface` ([de0d579](https://github.com/devuri/wp-env-config/commit/de0d5792a4d61c8b6545867ce93841b4261710fb))


### Miscellaneous Chores

* codefix ([afe79f6](https://github.com/devuri/wp-env-config/commit/afe79f617cb7ea63030dec531598bf3b55511b8e))
* update composer ([512105d](https://github.com/devuri/wp-env-config/commit/512105d0c84060f8fc2c4eb89e8807c7f9f7f910))

## [0.6.6](https://github.com/devuri/wp-env-config/compare/v0.6.5...v0.6.6) (2023-09-08)


### Features

* adds `devuri/cpt-meta-box` 0.4 `use DevUri\PostTypeMeta\MetaBox` ([a4f8024](https://github.com/devuri/wp-env-config/commit/a4f802475195b32dbd40f1343566efa518176661))
* adds `php nino up` and `php nino down` handles maintenance mode, `up` will create .maintenance in the public dir and `down` will remove it ([4d9705c](https://github.com/devuri/wp-env-config/commit/4d9705ced87d4431f0fe53fa171e312499608997))
* new `config()` function to get config options ([757a06f](https://github.com/devuri/wp-env-config/commit/757a06f08e80130843db18851a68749ab23b8f56))


### Bug Fixes

* autofix port number on local ([9437ebc](https://github.com/devuri/wp-env-config/commit/9437ebc891bae8659e67147324c284d6f1015c14))
* only enable auto login if `WPENV_AUTO_LOGIN_SECRET_KEY` is available ([e8aed54](https://github.com/devuri/wp-env-config/commit/e8aed5409d6b8484775a21f8874919d77b6a66e0))


### Miscellaneous Chores

* build ([982b319](https://github.com/devuri/wp-env-config/commit/982b31940bdc24e72e3237452ff19a886deb2002))
* docs ([c1678b3](https://github.com/devuri/wp-env-config/commit/c1678b3d49cb621573e62ff837a014206e671af0))
* misc updates for auto login ([d27efd3](https://github.com/devuri/wp-env-config/commit/d27efd3eae920b8121f1ce700aceced57493ff20))

## [0.6.5](https://github.com/devuri/wp-env-config/compare/v0.6.4...v0.6.5) (2023-08-06)


### Bug Fixes

* fixes created dir before cli is run ([b4e064f](https://github.com/devuri/wp-env-config/commit/b4e064f0a86cfc3b28b9bf6e45be5086a53dbc37))

## [0.6.4](https://github.com/devuri/wp-env-config/compare/v0.6.3...v0.6.4) (2023-08-06)


### Features

* `wpi` is now `wp:install` ([483f349](https://github.com/devuri/wp-env-config/commit/483f349f75310d8a30223bf35c1b6eb24aadc774))
* add blog title to install options ([c81e11f](https://github.com/devuri/wp-env-config/commit/c81e11fadd38a4bf1e455212d025f73d962f862f))
* add support for file `Exception` using `Defuse\Crypto\File` ([0e4ecc9](https://github.com/devuri/wp-env-config/commit/0e4ecc9a9b78e9b107d5d9ac3d466966e02b6cec))
* adds `config('key')` for accessing nested data using dot notation ([a21ab49](https://github.com/devuri/wp-env-config/commit/a21ab49d10d299e2167e5bb568da95d8f2caad95))
* adds `devuri/dot-access` ([d975244](https://github.com/devuri/wp-env-config/commit/d975244ad5f4680197cf6b8947b06107c377d573))
* adds `php-encryption` and `Encryption` class ([d02bd3d](https://github.com/devuri/wp-env-config/commit/d02bd3dd65a0647ed45109d507a5dc33dd1dec60))
* adds Auto-Login MU Plugin and CLI ([6b50e33](https://github.com/devuri/wp-env-config/commit/6b50e33ac3e752ecbf539067a3623f1e7dff369f))
* adds Elementor Pro activation class ([557f696](https://github.com/devuri/wp-env-config/commit/557f6968a4779cddab209a419697e9b54dcf05d5))
* adds encrypted backup option ([4251bcf](https://github.com/devuri/wp-env-config/commit/4251bcffda63e8fbf860cd054d41b2367c56f91b))
* adds s3 backup option ([30d4e3f](https://github.com/devuri/wp-env-config/commit/30d4e3f442c1464f219d1b658855128bdb86855c))
* adds TODO Setup Activity Logs. ([939ee99](https://github.com/devuri/wp-env-config/commit/939ee996869cb347e0a68047b5cb3ede02a26bf9))
* better handle on `S3_BACKUP_DIR` now uses domain as default ([3e44c3e](https://github.com/devuri/wp-env-config/commit/3e44c3e98803c7149e08012600d2b025d22ef3e0))
* can generate key file with: `php nino config cryptkey` ([518f63c](https://github.com/devuri/wp-env-config/commit/518f63c0baca7906609254ad61d069264ffd8374))
* fix filename and use month name, add `DELETE_LOCAL_S3BACKUP` ([51bd6e7](https://github.com/devuri/wp-env-config/commit/51bd6e756a0a4169f582c644acfbbc04352f7ccb))
* s3backup_dir env option `S3_BACKUP_DIR` ([f30e6fc](https://github.com/devuri/wp-env-config/commit/f30e6fcfece2fae740f4e453ddabe97e3df0a361))
* use `devuri/encryption` replaces `Encryption` class ([e54da9f](https://github.com/devuri/wp-env-config/commit/e54da9f97d87592ec4d5aaba9f68a88c484caa86))


### Bug Fixes

* append login secret ([7c52612](https://github.com/devuri/wp-env-config/commit/7c526123da9c65d1f4a66c4ee719df0de7a73421))
* better login and token handling ([bae96fd](https://github.com/devuri/wp-env-config/commit/bae96fd83aa1c22816403dcfe99e0671d41b0f8c))
* create bucket is not always an option ([a29c51e](https://github.com/devuri/wp-env-config/commit/a29c51eed5093085dfb7cd1f9c6043027ede834b))
* deactivate is true ([f36b67e](https://github.com/devuri/wp-env-config/commit/f36b67eda562c0f817095ede91b289f26f28cb72))
* encode and ecode ciphertext ([839f667](https://github.com/devuri/wp-env-config/commit/839f6678718bb2313d690885f35266e2da851ce0))
* fix backup, same day backups now use timestamp ([5751540](https://github.com/devuri/wp-env-config/commit/5751540101e524300e33e1d1e3f9281982e2bbf4))
* fixes bucket creation ([12f9378](https://github.com/devuri/wp-env-config/commit/12f9378a4560cffa068b14bca0fbaeeaa3c73e7a))
* missing Encryption ([b9a7ab0](https://github.com/devuri/wp-env-config/commit/b9a7ab0c32238e517245bc97d26b05b5e1cb0bc8))
* move auto login to core Plugin so its always available ([5df8a86](https://github.com/devuri/wp-env-config/commit/5df8a869e0c7c572867e795831dcbe9c3b767208))
* remove detects the error ([11cabef](https://github.com/devuri/wp-env-config/commit/11cabef72ef2b96fbb9d70fd01326d4775f608d3))
* use `symfony/console:5.4.*` for php 7.4 ([ae8b85e](https://github.com/devuri/wp-env-config/commit/ae8b85e01cda9768b6a55112d038a5efd02d47c1))
* use `WPENV_AUTO_LOGIN_SECRET_KEY` ([55a9743](https://github.com/devuri/wp-env-config/commit/55a9743041bb4ea34229fcddbc5a722b1bc45f01))


### Miscellaneous Chores

* build ([f069a9e](https://github.com/devuri/wp-env-config/commit/f069a9e4ed5cb3f31931d2c794cdb9d05d157c0d))
* build ([f5e7405](https://github.com/devuri/wp-env-config/commit/f5e7405eb5b6a73ba495b73f554964923287fb35))
* build ([3b1538d](https://github.com/devuri/wp-env-config/commit/3b1538d796d206c692b7893c0dd35e29b2a98cdb))
* build ([f23350f](https://github.com/devuri/wp-env-config/commit/f23350f80740852dbe4b8ecc16540b8c062562a0))
* build ([3a28e0d](https://github.com/devuri/wp-env-config/commit/3a28e0dcaeb2f9ace070f431edb96dc6186db604))
* build ([363260b](https://github.com/devuri/wp-env-config/commit/363260b5a305195d94819c466ef1d88654e4bcbb))
* build ([c425c57](https://github.com/devuri/wp-env-config/commit/c425c573dc207559cf3ed2bd5cecd36cf61e5a0f))
* build ([0dad801](https://github.com/devuri/wp-env-config/commit/0dad801cc99b812833b04743ba94e1a429cc4881))
* build ([196e182](https://github.com/devuri/wp-env-config/commit/196e182ca81b223f4e9d8a5a2cf0026ccbdaa2c2))
* code build ([1f4bc3c](https://github.com/devuri/wp-env-config/commit/1f4bc3ccab6a42f0bb6933ad4c2cd4d3d16b222b))
* tests for encoded values ([da2e0d3](https://github.com/devuri/wp-env-config/commit/da2e0d39fb88e96be94e9cc4ddbdb39ef74ecad8))

## [0.6.3](https://github.com/devuri/wp-env-config/compare/v0.6.2...v0.6.3) (2023-07-07)


### Bug Fixes

* fix adn exclude on Sucuri ([ef62d71](https://github.com/devuri/wp-env-config/commit/ef62d71fe3d8d2b5a01aee451d2271afa7caf170))
* fixes undefined array item ([aae1cea](https://github.com/devuri/wp-env-config/commit/aae1ceab8ac9e3675251df314c41dc4615f56d2f))
* use int on port checks ([d06195f](https://github.com/devuri/wp-env-config/commit/d06195faf61aadc66fc817f83be65df52c09da7b))


### Miscellaneous Chores

* build ([15301a0](https://github.com/devuri/wp-env-config/commit/15301a03244e05f9a3b8485e963ed700d767cd8e))

## [0.6.2](https://github.com/devuri/wp-env-config/compare/v0.6.1...v0.6.2) (2023-07-07)


### Features

* add `DatabaseBackup` cli `db:backup` ([648bfc5](https://github.com/devuri/wp-env-config/commit/648bfc5847d8a1a93c5158425c71cab7dcea30dc))
* add backups by month and year to backup command, include snap.json ([b856ed7](https://github.com/devuri/wp-env-config/commit/b856ed7753373dbbb952d34ade59c20157dbf107))
* add upgrade to use `devuri/secure-password` ([5c485a7](https://github.com/devuri/wp-env-config/commit/5c485a745a3f4a9b8cb969c08cbecc5e537919fe))
* adds  Restricted Admin ([d308ca6](https://github.com/devuri/wp-env-config/commit/d308ca69d13e7ae980cc7d8cf350a4252116babc))
* adds `basic auth` plugin option ([ef3c868](https://github.com/devuri/wp-env-config/commit/ef3c868eb9f7c0a36c56f0ab7ec51194b8a1ce8c))
* adds `PublicKey` support for encryption or verification purposes ([c019481](https://github.com/devuri/wp-env-config/commit/c019481604c9c6310e1966e644e4fa14d982a61a))
* adds `sudo_admin_group` users with higher privileges ([90f4dc3](https://github.com/devuri/wp-env-config/commit/90f4dc3cff9ddde8f5934445a4e7ac6dd09ab9aa))
* adds `wpi -u admin` cli wp installer `QuickInstaller` ([d20f90a](https://github.com/devuri/wp-env-config/commit/d20f90a84b8d36d9e77a2d26b26fcc9e6c6f2dee))
* disable error handler with `false` ([5b9f186](https://github.com/devuri/wp-env-config/commit/5b9f18676d3bf8adad43ab5977ba8d1316c93b8d))


### Bug Fixes

* comment fix ([faf94ea](https://github.com/devuri/wp-env-config/commit/faf94eabdf563e0a0afc4c3721b1b12377b1a0fe))
* deps ([3d88f46](https://github.com/devuri/wp-env-config/commit/3d88f465e02e1085efac68fe2c5f1016daea0923))
* do not lock out the admin with basic auth ([fd96d60](https://github.com/devuri/wp-env-config/commit/fd96d600e525087a98d4e77be5a69f09d85cc7c4))
* fix extra theme directory ([0d60b40](https://github.com/devuri/wp-env-config/commit/0d60b4059fbed513242df2c1d7f179a257b039f3))
* fixes require ([e29ada0](https://github.com/devuri/wp-env-config/commit/e29ada08e1bac54f511eb7e91ea9c1952bf57687))


### Miscellaneous Chores

* build ([6f557f9](https://github.com/devuri/wp-env-config/commit/6f557f98d265b3f36c3ffed1b181b69ac32d9862))
* build ([4a7c339](https://github.com/devuri/wp-env-config/commit/4a7c339547e1b84bcfee83950445428095821d30))

## [0.6.1](https://github.com/devuri/wp-env-config/compare/v0.6.0...v0.6.1) (2023-07-01)


### Features

* adds `Setup` and application error handling to `App` ([453d5e9](https://github.com/devuri/wp-env-config/commit/453d5e96ccef412600cce9bff342442fbaada6cd))
* adds `sudo_admin` and Custom `Sucuri settings`  Disable Sucuri WAF ui ([214e1d8](https://github.com/devuri/wp-env-config/commit/214e1d88ea4ac442e4b3c143bae9d477b9ba486c))
* adds try catch block to `wpc_app` helper ([3e449ec](https://github.com/devuri/wp-env-config/commit/3e449eca2696c37b6195ca4806123edca3b50384))
* app config options: `security`, smtp `mailer` and `redis cache` ([5744c54](https://github.com/devuri/wp-env-config/commit/5744c546a2c11d72869ee85776b54d9bb7128004))
* use `ConstantTrait` and add `redis` and `security` settings to BaseKernel ([80924a4](https://github.com/devuri/wp-env-config/commit/80924a496de29b8b8c6389ec6dca91930b996a6b))


### Bug Fixes

* replace cli `install` =&gt; `i` to install plugins and themes ([5f3419b](https://github.com/devuri/wp-env-config/commit/5f3419bf9fe6789f285656395ddfbc30963c16f7))
* trim for 8.1 ([7bed1be](https://github.com/devuri/wp-env-config/commit/7bed1be3f4b06ed9efc8c69719570c6b265aec11))
* various fixes for sucuriscan ([fba2017](https://github.com/devuri/wp-env-config/commit/fba20173accd9b182d0322f47eb531f6804e6b42))


### Miscellaneous Chores

* build ([ca71cc7](https://github.com/devuri/wp-env-config/commit/ca71cc7e7ae068c0acf312f9b66b3af9407dd7ea))

## [0.6.0](https://github.com/devuri/wp-env-config/compare/v0.5.1...v0.6.0) (2023-06-27)


### ⚠ BREAKING CHANGES

* !BREAKING `symfony/console` is now `6.0` requires php `8.0.2`

### Features

* !BREAKING `symfony/console` is now `6.0` requires php `8.0.2` ([01fb09b](https://github.com/devuri/wp-env-config/commit/01fb09b74573fbac8a1e24322f3e9aaa041976c6))
* `templates_dir` replaces theme_dir ([b2859dd](https://github.com/devuri/wp-env-config/commit/b2859dde1f903ec9c177df1ced686e881f27a966))
* adds `config_file` for overrides ([a4bff89](https://github.com/devuri/wp-env-config/commit/a4bff899f58480d07bdd64e06249da4f2d6edd30))
* adds `make:htpass` cli to create htaccess basic auth password file ([f2dbd3f](https://github.com/devuri/wp-env-config/commit/f2dbd3f84b24434990f6ce4344655b0c9618a50b))
* adds alias `prod`, `local`, `dev` and `sec` for environment switch ([f9ee749](https://github.com/devuri/wp-env-config/commit/f9ee7498dd4a5f34e0258f8c4b2851e67bdb4443))
* adds dev `laravel/envoy` and `spatie/ssh` ([824023f](https://github.com/devuri/wp-env-config/commit/824023f8f62d655528d4b00c96de2cbf287e8cfa))
* adds security headers ([c929311](https://github.com/devuri/wp-env-config/commit/c929311d0943bb71ce88bd04bcf96e1c38580ab3))
* get installed plugins as composer dependencies ([f15ac59](https://github.com/devuri/wp-env-config/commit/f15ac5983c5dbbe608a6471c021ef1e888fdd8d5))
* rename `Nino Cli =&gt; 0.1.3` ([41fd1b3](https://github.com/devuri/wp-env-config/commit/41fd1b3deb8ad1de7831665da9a8f77d70065371))


### Bug Fixes

* `symfony/console:6.0` version constraint for console ([1afe274](https://github.com/devuri/wp-env-config/commit/1afe2748bdeeb0eb39cf699379f9de3350673419))
* 7.3 compat fixes ([5765d92](https://github.com/devuri/wp-env-config/commit/5765d92c9e22e20167a399c26c5a3b280062c55c))
* adds `APP_TEST_PATH` to fix tests warning ([183b8c8](https://github.com/devuri/wp-env-config/commit/183b8c8c179be02988ab17e32fcf9d0c42b167ae))
* fix console output for config ([110efb1](https://github.com/devuri/wp-env-config/commit/110efb1e58fe36a1cae62d7c06cbbd249b15a340))
* fix htpass cli ([924c06d](https://github.com/devuri/wp-env-config/commit/924c06d450aeb619937f92fe531e181ce7767bea))
* fix test namespace ([5f30867](https://github.com/devuri/wp-env-config/commit/5f308670f71094aa9b839af990e9950b47c1cd37))
* fixes for cli tools ([8980692](https://github.com/devuri/wp-env-config/commit/8980692f9f4e7722e8a524c5801a114f1b771300))
* php serve cli ([db0fc02](https://github.com/devuri/wp-env-config/commit/db0fc026c2318c19a9c370e7c95629b46f9efcc3))
* use gmdate ([81b1469](https://github.com/devuri/wp-env-config/commit/81b146922a4e34c942eef2c775915050e57e48a5))
* version upgrades ([52fd71e](https://github.com/devuri/wp-env-config/commit/52fd71e8d75c7b45b957f6c2efd6610163de320e))


### Miscellaneous Chores

* build ([fb5d648](https://github.com/devuri/wp-env-config/commit/fb5d648f1d9dfc675e6bd9efca9fc144a85cbc6c))
* **build:** build ([581b61a](https://github.com/devuri/wp-env-config/commit/581b61a811dc946d20bf1b0a62592f840c5154c9))
* **build:** build ([c799978](https://github.com/devuri/wp-env-config/commit/c7999780bd65dae07797c5e671c5c519bcd1bbfa))
* **build:** build ([b153478](https://github.com/devuri/wp-env-config/commit/b15347800bfd08ad6696f0aa7b055c34ff6805b0))

## [0.5.1](https://github.com/devuri/wp-env-config/compare/v0.5.0...v0.5.1) (2023-06-18)


### Features

* adds `wpc_app_config_core()` load core plugin ([62ba254](https://github.com/devuri/wp-env-config/commit/62ba254ed314222b68d063f7f3375d14b16ee769))

## [0.5.0](https://github.com/devuri/wp-env-config/compare/0.4.1...v0.5.0) (2023-06-18)


### ⚠ BREAKING CHANGES

* `breaking change` refactor

### Features

* `breaking change` refactor ([20f7150](https://github.com/devuri/wp-env-config/commit/20f715069822449c9afeeb63da74f8269643d07f))
* `nino` is now available in `vendor/bin` ([561c27d](https://github.com/devuri/wp-env-config/commit/561c27d54ce5dd8696be9fadbbdf5eb6be6e97a0))
* Add `config(false)`  to use WP_ENVIRONMENT_TYPE ([5d5f2e4](https://github.com/devuri/wp-env-config/commit/5d5f2e49c496216999cc0f27c2a1492913cef9f6))
* add `get_http_env()` Get the current set wp app env ([ce3bcdb](https://github.com/devuri/wp-env-config/commit/ce3bcdbd2d11518dcf4c214ea031ef122ee2a2ea))
* add configMap() Display a list of constants defined by Setup. ([68f1fa5](https://github.com/devuri/wp-env-config/commit/68f1fa5b005d9fd5befd60684d5e139b848b9124))
* Adds 'wordpress'  =&gt; 'wp', ([16f5804](https://github.com/devuri/wp-env-config/commit/16f5804254d8c009cf385e294828dfc4103c8f46))
* Adds `asset_url()` ([47d33b8](https://github.com/devuri/wp-env-config/commit/47d33b852bdf0caf8d1e40a5e8ac960b92449f49))
* Adds `Asset::url` ([d8572c2](https://github.com/devuri/wp-env-config/commit/d8572c27f1e44acbe5a5b5f68529751e70802100))
* Adds `CryptTrait`, Encrypts the values of sensitive data in the given configuration array ([e0d8760](https://github.com/devuri/wp-env-config/commit/e0d8760d138604bf3a23bb4830ebe1e18954ca3f))
* Adds `DEVELOPER_ADMIN` const an int user ID ([d935426](https://github.com/devuri/wp-env-config/commit/d9354266aafee02ebc938545bf6172efdcc7c1cf))
* Adds `env()` function ([c9ce38b](https://github.com/devuri/wp-env-config/commit/c9ce38ba7f3ad207ff6e183b0a15a4b4c491e413))
* Adds `generate:composer` to create composer file ([3612106](https://github.com/devuri/wp-env-config/commit/36121060a561a947c63a1430615fcebad6454014))
* Adds `HTTP_ENV_CONFIG` `get_environment()` ([8737d11](https://github.com/devuri/wp-env-config/commit/8737d11c1a626999582f16fd91aefcc05b0bd614))
* Adds `HttpKernel` default args ([5e4a020](https://github.com/devuri/wp-env-config/commit/5e4a020911853d0f613636b65026706cd5948675))
* adds `nino install` to install plugin or theme ([e704045](https://github.com/devuri/wp-env-config/commit/e704045e429034843222b785ab86a9b3789ae279))
* Adds `Nino` Cli ([299a889](https://github.com/devuri/wp-env-config/commit/299a889414fa76c4ccb127b2acffb129fdb2a51d))
* Adds `oops` error handler ([3cbb8f2](https://github.com/devuri/wp-env-config/commit/3cbb8f2454a44dbbf485aaa607b6fc9f3d9d5748))
* Adds `overrides` for `config.php` ([f5c2c6c](https://github.com/devuri/wp-env-config/commit/f5c2c6cdf6829d1b6147e56c543622841054ae9f))
* Adds `set_env_secret( string $key )` to define secret env vars ([f5a4b84](https://github.com/devuri/wp-env-config/commit/f5a4b84cc9c7954ad74ac1b98404339b74b0e062))
* Adds `SSL` support by `certbot` ([9ccb5cf](https://github.com/devuri/wp-env-config/commit/9ccb5cf4cee49947e617008e76db69a4d167ef06))
* Adds `static::detect_error()` and `static::env()` ([b310e16](https://github.com/devuri/wp-env-config/commit/b310e16f680a1261e7150e15b6cc074bc14643ea))
* Adds `USE_APP_THEME` check ([d55fd95](https://github.com/devuri/wp-env-config/commit/d55fd950c7e228fa0a24ecafa9739d5d68ec7365))
* Adds `uuid` ([15c61c1](https://github.com/devuri/wp-env-config/commit/15c61c10de604585fbf7e280f1f9c39aa8bd082d))
* Adds `wpc_app` function ([593e767](https://github.com/devuri/wp-env-config/commit/593e7676e82c5d1ab217c267226f1a3cc5ec8d08))
* adds a list of setup options ([9dea7b3](https://github.com/devuri/wp-env-config/commit/9dea7b39933c05d42bb8b79e57c45d1fdb7fdd75))
* Adds changes `.env` db prefix if set to `wp_` ([39b03e7](https://github.com/devuri/wp-env-config/commit/39b03e7f57c0088d18e1d731ea17263c7c2fd174))
* Adds cookie-related override for WordPress constants ([5039404](https://github.com/devuri/wp-env-config/commit/5039404c658acb821cab3ea1fb9de5ff62dc3528))
* Adds custom theme dir ([39f97ba](https://github.com/devuri/wp-env-config/commit/39f97ba00d20b905090d7c79661f7ec7d1e353f4))
* Adds docs dir ([4f89446](https://github.com/devuri/wp-env-config/commit/4f89446f0a706283c4a8c9cdcbd124c389ca637e))
* adds Environment::secure() ([8a2f109](https://github.com/devuri/wp-env-config/commit/8a2f1099d9be5fd21466d8a674a583be050edee4))
* Adds Generator to create `htpasswd` ([3190fc9](https://github.com/devuri/wp-env-config/commit/3190fc9c9544354cd6fbe7d580ebcadc74df66aa))
* adds getEnvironment() to get the current Environment setup ([46f65d5](https://github.com/devuri/wp-env-config/commit/46f65d550a054ad67e9c9128f66743b6615099eb))
* Adds Kernel ([8fc96c2](https://github.com/devuri/wp-env-config/commit/8fc96c20ecd8034d0691c6a05d634732a225628b))
* adds more error reporting for `debug` ([7c55d36](https://github.com/devuri/wp-env-config/commit/7c55d36c193deb5f3325c89f1901fee9977ea150))
* Adds multiple `env` file support: https://github.com/vlucas/phpdotenv/pull/394 ([a4f97b3](https://github.com/devuri/wp-env-config/commit/a4f97b3da326c967d5d19406f25cd0f17eec2f7a))
* Adds new `core` plugin ([cb219d8](https://github.com/devuri/wp-env-config/commit/cb219d82a8fb0f719662cf832954869681f98886))
* Adds suggest `spatie/ssh` ([cf0befa](https://github.com/devuri/wp-env-config/commit/cf0befaeaa0f5c4d04224310dfe5327df30dea27))
* Adds support for `aaemnnosttv/wp-sqlite-db` ([f8b3d80](https://github.com/devuri/wp-env-config/commit/f8b3d807ad2b3f247d9e48e7422693fb34a3e5a1))
* Adds support for custom log dir `year-month-day.log` ([54c4ba0](https://github.com/devuri/wp-env-config/commit/54c4ba03378dbd8a8d060c331ac7361d8b970a39))
* Adds tests for `HttpKernel` ([b18b06c](https://github.com/devuri/wp-env-config/commit/b18b06c2a2cf652e6d27a91081d87d90d8ecda16))
* can now disable and bypass the default setup process ([617938a](https://github.com/devuri/wp-env-config/commit/617938a07228cde57087be0379a9b3efe77a8588))
* create `uuid` dir path to store phpmyadmin or adminer ([a968668](https://github.com/devuri/wp-env-config/commit/a968668c70c6db06f96da198d82a78e91044d919))
* defines Environment types ([36e7778](https://github.com/devuri/wp-env-config/commit/36e7778f0a1b66cf60a510102360564ef11e2b70))
* error handler can now be passed in as a `Kernel` argument ([da5419c](https://github.com/devuri/wp-env-config/commit/da5419c288719941e941ec93795f6eeefe6ee4fb))
* Hash env output on the command line ([05a6eb2](https://github.com/devuri/wp-env-config/commit/05a6eb252204a4bfb2f7a281013586c999ad2b45))
* optionally pass in the `HttpKernel` instance ([50f2d92](https://github.com/devuri/wp-env-config/commit/50f2d927fb8f931e9ed643789ede52c170c5dca0))
* Prevent Admin users from deactivating plugins. ([1326209](https://github.com/devuri/wp-env-config/commit/1326209cee84a40821371e944d82e5d6da62a468))
* register custom theme directory in `Core Plugin` ([7162fcd](https://github.com/devuri/wp-env-config/commit/7162fcdc9a74b983eacf2b500021a89ef0d43ced))
* Set slug of the default theme ([147fe09](https://github.com/devuri/wp-env-config/commit/147fe0914c2fe3916b383c3636a87aa29a828b89))
* simplify environment setup, allow bypass of default setup ([8ef04d5](https://github.com/devuri/wp-env-config/commit/8ef04d5009528209fdcbf0e33a733707f2bb88aa))
* Validate `.env` port matches `local` server port ([df8297c](https://github.com/devuri/wp-env-config/commit/df8297c6f04b6dd3a9192b0a46a03c737cf8472c))
* when `null` or `false` the `WP_ENVIRONMENT_TYPE` is used ([5adb242](https://github.com/devuri/wp-env-config/commit/5adb242ac31633e70e77bf38662699b4731bfbba))


### Bug Fixes

* Adds `Error Handler` docs ([1292dde](https://github.com/devuri/wp-env-config/commit/1292dde5e8f5fa5ecf94429fd7d1677a66b6ab61))
* bin missing from package ([0d93d5c](https://github.com/devuri/wp-env-config/commit/0d93d5c5d62f15a842bfd15f7371befc48b99ace))
* consolidate `env` methods ([1f093c7](https://github.com/devuri/wp-env-config/commit/1f093c7688bd9b4c2c34e848b45dedb6a200b5e9))
* create `.env` before we serve in cases where it does not exist ([c952204](https://github.com/devuri/wp-env-config/commit/c9522046a08e287e1c5f0eab482c749997860c26))
* dump error message for dotenv ([a186bbd](https://github.com/devuri/wp-env-config/commit/a186bbd4b96475c0d54472aa106e3eca971f61d2))
* fix ConfigInterface ([3570754](https://github.com/devuri/wp-env-config/commit/35707547213bbc77665a9d9d94cc851d300bdcd0))
* fix example file ([c84cd88](https://github.com/devuri/wp-env-config/commit/c84cd8852a8e31bde1f600c11521bffb2c23bf79))
* fix the return type of `env` should be mixed ([5e10591](https://github.com/devuri/wp-env-config/commit/5e10591723be7034c8082d1c118773d0503fec1d))
* fixes `root_dir_path` ([f3481af](https://github.com/devuri/wp-env-config/commit/f3481af3a5be0f9a12b067a434ad2b08be0cdcc5))
* fixes `strtolower` conversion ([92f5820](https://github.com/devuri/wp-env-config/commit/92f58202276c823328b908734650eededf379bf8))
* fixes debug error handlers based on `environment` ([434b06f](https://github.com/devuri/wp-env-config/commit/434b06f81caedc5abc35a01cf42a9bc308726065))
* fixes error log location ([e884570](https://github.com/devuri/wp-env-config/commit/e884570ed0f5f1b94ede53d39f4e1674d2d35e72))
* fixes interface in v0.30.01 ([813ac64](https://github.com/devuri/wp-env-config/commit/813ac64f66662bd27bd5bb998c412ec55c27f6c5))
* fixes return type for `Setup::get_environment() ` ([3d9d8fc](https://github.com/devuri/wp-env-config/commit/3d9d8fcd3c6b3fb4c335d3a5bc61bcc4ea8c9e65))
* fixes rreturn type set to ConfigInterface ([338912a](https://github.com/devuri/wp-env-config/commit/338912a62a8b730181362868a42e8db731f7e93a))
* fixes symfony compatability ([155b0a7](https://github.com/devuri/wp-env-config/commit/155b0a7076f401b0283e4f7f0ca94f8e8fca11e1))
* fixes the `APP_THEME_DIR` ([2123cd6](https://github.com/devuri/wp-env-config/commit/2123cd6ab35491fd23fa89af02b60bc19fe60ff9))
* fixes the `env` function more reliable output ([64559af](https://github.com/devuri/wp-env-config/commit/64559afa6d43a917f3cca20c810b6f21182a76f5))
* fixes translation string ([994e7d2](https://github.com/devuri/wp-env-config/commit/994e7d20cb1be774fb682a4c4592ae8cc38a25fd))
* fixes white lable plugin ([be9fb1b](https://github.com/devuri/wp-env-config/commit/be9fb1b95043e7e099c76808511ff8f2b8f011cf))
* fixes WP_DEBUG not set ([c0129b5](https://github.com/devuri/wp-env-config/commit/c0129b5b795048930aac2e643a8dc70a402821c0))
* improve and fix the `get_config_map()` ([3ba1a9b](https://github.com/devuri/wp-env-config/commit/3ba1a9b2f20358f590fa45bb819a2e60513a03aa))
* remove string constraint in uploads param ([fb5ae22](https://github.com/devuri/wp-env-config/commit/fb5ae220fd1c6cd0a1c81053bdb3d7368e537b4c))
* symfony debug now only depends on `environment` value ([b84171e](https://github.com/devuri/wp-env-config/commit/b84171ef7a66f36d128136beb24b7010a0ef6e58))
* trait `Generator` is now `Generate` ([28383b7](https://github.com/devuri/wp-env-config/commit/28383b7256c9f439d1f2e42f0b41f9d57bc89c36))
* use `$this-&gt;nino` ([1f1338d](https://github.com/devuri/wp-env-config/commit/1f1338dfd248d2d148e33acbeec9f9816866896f))
* Verifiy files to avoid Dotenv warning. ([b762c2d](https://github.com/devuri/wp-env-config/commit/b762c2de3a23aa5d07b55ff6ee2c25192a38590d))


### Miscellaneous Chores

* **master:** release 0.20.1 ([0bdaa7f](https://github.com/devuri/wp-env-config/commit/0bdaa7f5eb5a3cb3b4272901e48d4750418e6667))
* **master:** release 0.20.2 ([fed64c4](https://github.com/devuri/wp-env-config/commit/fed64c4a70aca5013848e8b9d2a0026d77d58477))
* **master:** release 0.30.2 ([d2b6ce5](https://github.com/devuri/wp-env-config/commit/d2b6ce5afa7efc2fb90742aa09b9aa35fc1858ad))
* **master:** release 0.30.3 ([f244bcc](https://github.com/devuri/wp-env-config/commit/f244bcce13a6adff651c9dd4ce897e907fe81be7))
* **master:** release 0.30.4 ([f3962f3](https://github.com/devuri/wp-env-config/commit/f3962f3ed2a43b9e2f0bde80efdb71e7722871d2))
* **master:** release 0.30.5 ([c66a61e](https://github.com/devuri/wp-env-config/commit/c66a61edd4807d35bc06673ecfa0bc547a654518))
* **master:** release 0.30.6 ([a99bbd7](https://github.com/devuri/wp-env-config/commit/a99bbd7a8b945f1b7024d7eba6183d81a9b92f59))
* **master:** release 0.30.7 ([2bbd582](https://github.com/devuri/wp-env-config/commit/2bbd58218fed4445775a29b6f4358317121c25b3))
* **master:** release 0.30.8 ([24317c0](https://github.com/devuri/wp-env-config/commit/24317c0b1c7849a057ebe2d4ae1d2c9179b97033))
* **master:** release 0.30.9 ([250a69c](https://github.com/devuri/wp-env-config/commit/250a69cf8ac4f05b6e999bf4af1560b6d3f38bef))

## [0.30.9](https://github.com/devuri/wp-env-config/compare/v0.30.8...v0.30.9) (2023-06-18)


### Features

* add `get_http_env()` Get the current set wp app env ([ce3bcdb](https://github.com/devuri/wp-env-config/commit/ce3bcdbd2d11518dcf4c214ea031ef122ee2a2ea))
* Adds `generate:composer` to create composer file ([3612106](https://github.com/devuri/wp-env-config/commit/36121060a561a947c63a1430615fcebad6454014))
* adds `nino install` to install plugin or theme ([e704045](https://github.com/devuri/wp-env-config/commit/e704045e429034843222b785ab86a9b3789ae279))
* Adds `USE_APP_THEME` check ([d55fd95](https://github.com/devuri/wp-env-config/commit/d55fd950c7e228fa0a24ecafa9739d5d68ec7365))
* Adds `wpc_app` function ([593e767](https://github.com/devuri/wp-env-config/commit/593e7676e82c5d1ab217c267226f1a3cc5ec8d08))
* Adds custom theme dir ([39f97ba](https://github.com/devuri/wp-env-config/commit/39f97ba00d20b905090d7c79661f7ec7d1e353f4))
* error handler can now be passed in as a `Kernel` argument ([da5419c](https://github.com/devuri/wp-env-config/commit/da5419c288719941e941ec93795f6eeefe6ee4fb))
* Prevent Admin users from deactivating plugins. ([1326209](https://github.com/devuri/wp-env-config/commit/1326209cee84a40821371e944d82e5d6da62a468))
* register custom theme directory in `Core Plugin` ([7162fcd](https://github.com/devuri/wp-env-config/commit/7162fcdc9a74b983eacf2b500021a89ef0d43ced))


### Bug Fixes

* fixes `strtolower` conversion ([92f5820](https://github.com/devuri/wp-env-config/commit/92f58202276c823328b908734650eededf379bf8))
* fixes the `APP_THEME_DIR` ([2123cd6](https://github.com/devuri/wp-env-config/commit/2123cd6ab35491fd23fa89af02b60bc19fe60ff9))

## [0.30.8](https://github.com/devuri/wp-env-config/compare/v0.30.7...v0.30.8) (2023-03-23)


### Features

* Adds `uuid` ([15c61c1](https://github.com/devuri/wp-env-config/commit/15c61c10de604585fbf7e280f1f9c39aa8bd082d))
* Adds cookie-related override for WordPress constants ([5039404](https://github.com/devuri/wp-env-config/commit/5039404c658acb821cab3ea1fb9de5ff62dc3528))
* Adds Generator to create `htpasswd` ([3190fc9](https://github.com/devuri/wp-env-config/commit/3190fc9c9544354cd6fbe7d580ebcadc74df66aa))
* Adds multiple `env` file support: https://github.com/vlucas/phpdotenv/pull/394 ([a4f97b3](https://github.com/devuri/wp-env-config/commit/a4f97b3da326c967d5d19406f25cd0f17eec2f7a))
* Adds suggest `spatie/ssh` ([cf0befa](https://github.com/devuri/wp-env-config/commit/cf0befaeaa0f5c4d04224310dfe5327df30dea27))
* create `uuid` dir path to store phpmyadmin or adminer ([a968668](https://github.com/devuri/wp-env-config/commit/a968668c70c6db06f96da198d82a78e91044d919))
* Set slug of the default theme ([147fe09](https://github.com/devuri/wp-env-config/commit/147fe0914c2fe3916b383c3636a87aa29a828b89))
* Validate `.env` port matches `local` server port ([df8297c](https://github.com/devuri/wp-env-config/commit/df8297c6f04b6dd3a9192b0a46a03c737cf8472c))


### Bug Fixes

* consolidate `env` methods ([1f093c7](https://github.com/devuri/wp-env-config/commit/1f093c7688bd9b4c2c34e848b45dedb6a200b5e9))
* fixes `root_dir_path` ([f3481af](https://github.com/devuri/wp-env-config/commit/f3481af3a5be0f9a12b067a434ad2b08be0cdcc5))
* fixes debug error handlers based on `environment` ([434b06f](https://github.com/devuri/wp-env-config/commit/434b06f81caedc5abc35a01cf42a9bc308726065))
* trait `Generator` is now `Generate` ([28383b7](https://github.com/devuri/wp-env-config/commit/28383b7256c9f439d1f2e42f0b41f9d57bc89c36))
* use `$this-&gt;nino` ([1f1338d](https://github.com/devuri/wp-env-config/commit/1f1338dfd248d2d148e33acbeec9f9816866896f))
* Verifiy files to avoid Dotenv warning. ([b762c2d](https://github.com/devuri/wp-env-config/commit/b762c2de3a23aa5d07b55ff6ee2c25192a38590d))

## [0.30.7](https://github.com/devuri/wp-env-config/compare/v0.30.6...v0.30.7) (2023-03-17)


### Features

* Adds changes `.env` db prefix if set to `wp_` ([39b03e7](https://github.com/devuri/wp-env-config/commit/39b03e7f57c0088d18e1d731ea17263c7c2fd174))


### Bug Fixes

* create `.env` before we serve in cases where it does not exist ([c952204](https://github.com/devuri/wp-env-config/commit/c9522046a08e287e1c5f0eab482c749997860c26))

## [0.30.6](https://github.com/devuri/wp-env-config/compare/v0.30.5...v0.30.6) (2023-03-16)


### Bug Fixes

* dump error message for dotenv ([a186bbd](https://github.com/devuri/wp-env-config/commit/a186bbd4b96475c0d54472aa106e3eca971f61d2))

## [0.30.5](https://github.com/devuri/wp-env-config/compare/v0.30.4...v0.30.5) (2023-03-16)


### Features

* Adds `SSL` support by `certbot` ([9ccb5cf](https://github.com/devuri/wp-env-config/commit/9ccb5cf4cee49947e617008e76db69a4d167ef06))

## [0.30.4](https://github.com/devuri/wp-env-config/compare/v0.30.3...v0.30.4) (2023-03-16)


### Features

* `nino` is now available in `vendor/bin` ([561c27d](https://github.com/devuri/wp-env-config/commit/561c27d54ce5dd8696be9fadbbdf5eb6be6e97a0))
* Adds support for `aaemnnosttv/wp-sqlite-db` ([f8b3d80](https://github.com/devuri/wp-env-config/commit/f8b3d807ad2b3f247d9e48e7422693fb34a3e5a1))


### Bug Fixes

* bin missing from package ([0d93d5c](https://github.com/devuri/wp-env-config/commit/0d93d5c5d62f15a842bfd15f7371befc48b99ace))

## [0.30.3](https://github.com/devuri/wp-env-config/compare/v0.30.2...v0.30.3) (2023-03-14)


### Features

* Adds `CryptTrait`, Encrypts the values of sensitive data in the given configuration array ([e0d8760](https://github.com/devuri/wp-env-config/commit/e0d8760d138604bf3a23bb4830ebe1e18954ca3f))


### Bug Fixes

* Adds `Error Handler` docs ([1292dde](https://github.com/devuri/wp-env-config/commit/1292dde5e8f5fa5ecf94429fd7d1677a66b6ab61))
* fixes return type for `Setup::get_environment() ` ([3d9d8fc](https://github.com/devuri/wp-env-config/commit/3d9d8fcd3c6b3fb4c335d3a5bc61bcc4ea8c9e65))

## [0.30.2](https://github.com/devuri/wp-env-config/compare/v0.30.1...v0.30.2) (2023-03-14)


### Features

* Adds `HTTP_ENV_CONFIG` `get_environment()` ([8737d11](https://github.com/devuri/wp-env-config/commit/8737d11c1a626999582f16fd91aefcc05b0bd614))
* Adds `Nino` Cli ([299a889](https://github.com/devuri/wp-env-config/commit/299a889414fa76c4ccb127b2acffb129fdb2a51d))
* Adds `oops` error handler ([3cbb8f2](https://github.com/devuri/wp-env-config/commit/3cbb8f2454a44dbbf485aaa607b6fc9f3d9d5748))
* Adds `set_env_secret( string $key )` to define secret env vars ([f5a4b84](https://github.com/devuri/wp-env-config/commit/f5a4b84cc9c7954ad74ac1b98404339b74b0e062))
* Adds new `core` plugin ([cb219d8](https://github.com/devuri/wp-env-config/commit/cb219d82a8fb0f719662cf832954869681f98886))
* Hash env output on the command line ([05a6eb2](https://github.com/devuri/wp-env-config/commit/05a6eb252204a4bfb2f7a281013586c999ad2b45))
* optionally pass in the `BaseKernel` instance ([50f2d92](https://github.com/devuri/wp-env-config/commit/50f2d927fb8f931e9ed643789ede52c170c5dca0))


### Bug Fixes

* fix the return type of `env` should be mixed ([5e10591](https://github.com/devuri/wp-env-config/commit/5e10591723be7034c8082d1c118773d0503fec1d))
* fixes interface in v0.30.01 ([813ac64](https://github.com/devuri/wp-env-config/commit/813ac64f66662bd27bd5bb998c412ec55c27f6c5))
* fixes symfony compatability ([155b0a7](https://github.com/devuri/wp-env-config/commit/155b0a7076f401b0283e4f7f0ca94f8e8fca11e1))
* fixes the `env` function more reliable output ([64559af](https://github.com/devuri/wp-env-config/commit/64559afa6d43a917f3cca20c810b6f21182a76f5))
* fixes translation string ([994e7d2](https://github.com/devuri/wp-env-config/commit/994e7d20cb1be774fb682a4c4592ae8cc38a25fd))
* fixes white lable plugin ([be9fb1b](https://github.com/devuri/wp-env-config/commit/be9fb1b95043e7e099c76808511ff8f2b8f011cf))
* fixes WP_DEBUG not set ([c0129b5](https://github.com/devuri/wp-env-config/commit/c0129b5b795048930aac2e643a8dc70a402821c0))
* improve and fix the `get_config_map()` ([3ba1a9b](https://github.com/devuri/wp-env-config/commit/3ba1a9b2f20358f590fa45bb819a2e60513a03aa))
* symfony debug now only depends on `environment` value ([b84171e](https://github.com/devuri/wp-env-config/commit/b84171ef7a66f36d128136beb24b7010a0ef6e58))

## [0.20.2](https://github.com/devuri/wp-env-config/compare/v0.20.1...v0.20.2) (2023-03-10)


### Features

* Add `config(false)`  to use WP_ENVIRONMENT_TYPE ([5d5f2e4](https://github.com/devuri/wp-env-config/commit/5d5f2e49c496216999cc0f27c2a1492913cef9f6))
* Adds `DEVELOPER_ADMIN` const an int user ID ([d935426](https://github.com/devuri/wp-env-config/commit/d9354266aafee02ebc938545bf6172efdcc7c1cf))
* Adds `overrides` for `config.php` ([f5c2c6c](https://github.com/devuri/wp-env-config/commit/f5c2c6cdf6829d1b6147e56c543622841054ae9f))
* Adds support for custom log dir `year-month-day.log` ([54c4ba0](https://github.com/devuri/wp-env-config/commit/54c4ba03378dbd8a8d060c331ac7361d8b970a39))
* when `null` or `false` the `WP_ENVIRONMENT_TYPE` is used ([5adb242](https://github.com/devuri/wp-env-config/commit/5adb242ac31633e70e77bf38662699b4731bfbba))


### Bug Fixes

* fixes error log location ([e884570](https://github.com/devuri/wp-env-config/commit/e884570ed0f5f1b94ede53d39f4e1674d2d35e72))

## [0.20.1](https://github.com/devuri/wp-env-config/compare/v0.20.0...v0.20.1) (2023-03-07)


### Features

* add configMap() Display a list of constants defined by Setup. ([68f1fa5](https://github.com/devuri/wp-env-config/commit/68f1fa5b005d9fd5befd60684d5e139b848b9124))
* Adds 'wordpress'  =&gt; 'wp', ([16f5804](https://github.com/devuri/wp-env-config/commit/16f5804254d8c009cf385e294828dfc4103c8f46))
* Adds `asset_url()` ([47d33b8](https://github.com/devuri/wp-env-config/commit/47d33b852bdf0caf8d1e40a5e8ac960b92449f49))
* Adds `Asset::url` ([d8572c2](https://github.com/devuri/wp-env-config/commit/d8572c27f1e44acbe5a5b5f68529751e70802100))
* Adds `env()` function ([c9ce38b](https://github.com/devuri/wp-env-config/commit/c9ce38ba7f3ad207ff6e183b0a15a4b4c491e413))
* Adds `BaseKernel` default args ([5e4a020](https://github.com/devuri/wp-env-config/commit/5e4a020911853d0f613636b65026706cd5948675))
* Adds `static::detect_error()` and `static::env()` ([b310e16](https://github.com/devuri/wp-env-config/commit/b310e16f680a1261e7150e15b6cc074bc14643ea))
* adds a list of setup options ([9dea7b3](https://github.com/devuri/wp-env-config/commit/9dea7b39933c05d42bb8b79e57c45d1fdb7fdd75))
* adds config method in class Setup ([5a5502b](https://github.com/devuri/wp-env-config/commit/5a5502b2a86712dca1434ae511ba6be310a8021d))
* Adds docs dir ([4f89446](https://github.com/devuri/wp-env-config/commit/4f89446f0a706283c4a8c9cdcbd124c389ca637e))
* adds Environment::secure() ([8a2f109](https://github.com/devuri/wp-env-config/commit/8a2f1099d9be5fd21466d8a674a583be050edee4))
* adds Exception try catch block ([c71034f](https://github.com/devuri/wp-env-config/commit/c71034f3182e510f62ac2418a8d2c570f9cd20df))
* adds getEnvironment() to get the current Environment setup ([46f65d5](https://github.com/devuri/wp-env-config/commit/46f65d550a054ad67e9c9128f66743b6615099eb))
* Adds Kernel ([8fc96c2](https://github.com/devuri/wp-env-config/commit/8fc96c20ecd8034d0691c6a05d634732a225628b))
* adds more error reporting for `debug` ([7c55d36](https://github.com/devuri/wp-env-config/commit/7c55d36c193deb5f3325c89f1901fee9977ea150))
* Adds tests for `BaseKernel` ([b18b06c](https://github.com/devuri/wp-env-config/commit/b18b06c2a2cf652e6d27a91081d87d90d8ecda16))
* can now disable and bypass the default setup process ([617938a](https://github.com/devuri/wp-env-config/commit/617938a07228cde57087be0379a9b3efe77a8588))
* constant can be overridden in wp-config.php, add Directory $path ([e9fa1b5](https://github.com/devuri/wp-env-config/commit/e9fa1b50cc5e0ea33d5a278e6485de9ea6cce0ae))
* defines Environment types ([36e7778](https://github.com/devuri/wp-env-config/commit/36e7778f0a1b66cf60a510102360564ef11e2b70))
* simplify environment setup, allow bypass of default setup ([8ef04d5](https://github.com/devuri/wp-env-config/commit/8ef04d5009528209fdcbf0e33a733707f2bb88aa))


### Bug Fixes

* debug settings, adds DISALLOW_FILE_EDIT ([e908ae1](https://github.com/devuri/wp-env-config/commit/e908ae181bb1680dc47fdd8a4fbacdeb884bcaf1))
* fix ConfigInterface ([3570754](https://github.com/devuri/wp-env-config/commit/35707547213bbc77665a9d9d94cc851d300bdcd0))
* fix example file ([c84cd88](https://github.com/devuri/wp-env-config/commit/c84cd8852a8e31bde1f600c11521bffb2c23bf79))
* fixes rreturn type set to ConfigInterface ([338912a](https://github.com/devuri/wp-env-config/commit/338912a62a8b730181362868a42e8db731f7e93a))
* remove string constraint in uploads param ([fb5ae22](https://github.com/devuri/wp-env-config/commit/fb5ae220fd1c6cd0a1c81053bdb3d7368e537b4c))
