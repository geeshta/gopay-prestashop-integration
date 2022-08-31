# GoPay PrestaShop Integration

## Table of Contents

- [About the Project](#about-the-project)
    - [Built With](#built-with)
- [Development](#development)
    - [Prerequisites](#prerequisites)
    - [Installation](#installation)
    - [Run project](#run-project)
    - [Project Structure](#project-structure)
    - [Migrations](#migrations)
    - [Testing](#testing)
- [Versioning](#versioning)
  - [Contribution](#contribution)
  - [Contribution process in details](#contribution-process-in-details)
- [Deployment](#deployment)
- [Documentation](#documentation)
- [Internationalization](#internationalization)
  - [Add or Update new language](#add-or-update-new-language)
- [Documentation](#documentation)
- [Other useful links](#other-useful-links)

## About The Project

GoPay payment gateway integration with the PrestaShop eCommerce platform.

### Built With

- [GoPay's PHP SDK for Payments REST API](https://github.com/gopaycommunity/gopay-php-api)
- [Composer](https://getcomposer.org/)

## Development

Running project on local machine for development and testing purposes.

### Prerequisites

- [PHP](https://www.php.net)
- [PrestaShop](https://www.prestashop.com/)
- [Docker Desktop](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/) _(is part of Docker Desktop)_

### Installation

### Run project

For local project execution, first install PrestaShop, then upload and configure the plugin by following the steps below:
1. Install the plugin through the PrestaShop modules screen.
2. Activate the plugin through the modules screen.
3. Configure the plugin by providing goid, client id and secret to load the other options (follow these [steps](https://help.gopay.com/en/knowledge-base/gopay-account/gopay-business-account/signing-in-password-reset-activating-and-deactivating-the-payment-gateway/how-to-activate-the-payment-gateway) to activate the payment gateway and get goid, client id and secret).
4. Finally, choose the options you want to be available in the payment gateway (payment methods and banks must be enabled in your GoPay account).

### Project Structure

- **`controllers`**
  - **`admin`**
  - **`front`**
- **`includes`**
- **`translations`**
- **`vendor`**
- **`views`**
  - **`css`**
  - **`js`**
  - **`templates`**
    - **`admin`**
    - **`front`**
    - **`hook`**
- **`readme.md`**

### Migrations

### Testing

## Versioning

This plugin uses [SemVer](http://semver.org/) for versioning scheme.

### Contribution

- `master` - contains production code. You must not make changes directly to the master!
- `staging` - contains staging code. Pre-production environment for testing.
- `development` - contains development code.

### Contribution process in details

1. Use the development branch for the implementation.
2. Update corresponding readmes after the completion of the development.
3. Create a pull request and properly revise all your changes before merging.
4. Push into the development branch.
5. Upload to staging for testing.
6. When the feature is tested and approved on staging, pull you changes to master.

## Deployment

Before deploy change Version in the `prestashopgopay.php`, then commit & push. Staging site uses staging branch.

## Internationalization

### Add or Update new language

Add a new language on _'Add / Update a language'_ from tab _'IMPROVE/International/Translations'_. On _'Modify translations'_ choose _'Type of translation: Installed modules translations'_, _'Select your module: PrestaShop GoPay gateway'_ and _'Select your language: Language to be translated'_. Finally, Click on _'Modify'_, add the translations and click on _'Save'_.

## Documentation

## Other useful links