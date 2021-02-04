<h1 align="center">MarkShust_OrderGrid</h1> 

<div align="center">
  <p>Adds more details to the order grid in the admin.</p>
  <img src="https://img.shields.io/badge/magento-2.2%20|%202.3|%202.4-brightgreen.svg?logo=magento&longCache=true&style=flat-square" alt="Supported Magento Versions" />
  <a href="https://packagist.org/packages/markshust/magento2-module-ordergrid" target="_blank"><img src="https://img.shields.io/packagist/v/markshust/magento2-module-ordergrid.svg?style=flat-square" alt="Latest Stable Version" /></a>
  <a href="https://packagist.org/packages/markshust/magento2-module-ordergrid" target="_blank"><img src="https://poser.pugx.org/markshust/magento2-module-ordergrid/downloads" alt="Composer Downloads" /></a>
  <a href="https://GitHub.com/Naereen/StrapDown.js/graphs/commit-activity" target="_blank"><img src="https://img.shields.io/badge/maintained%3F-yes-brightgreen.svg?style=flat-square" alt="Maintained - Yes" /></a>
  <a href="https://opensource.org/licenses/MIT" target="_blank"><img src="https://img.shields.io/badge/license-MIT-blue.svg" /></a>
</div>

## Table of contents

- [Summary](#summary)
- [Installation](#installation)
- [Usage](#usage)
- [License](#license)

## Summary

Out of the box, the Magento admin displays high-level information within the sales order grid. It does not provide more detailed information would could be useful to specific businesses.

This module adds more detailed information to the admin order grid. Initially, a new column is added to the order grid which contains order line items, which includes specific quantity and product names of items ordered.

## Installation

```
composer require markshust/magento2-module-ordergrid
bin/magento module:enable MarkShust_OrderGrid
bin/magento setup:upgrade
```

## Usage

This module has no configuration. Just install, and you'll see a new column in the order grid called Order Items.

![Screenshot](https://raw.githubusercontent.com/markshust/magento2-module-ordergrid/master/docs/demo.png)

## License

[MIT](https://opensource.org/licenses/MIT)
