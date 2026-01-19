<h1 align="center">MarkShust_OrderGrid</h1> 

<div align="center">
  <p>Adds more details to the order grid in the admin.</p>
  <img src="https://img.shields.io/badge/magento-2.2%20|%202.3%20|%202.4-brightgreen.svg?logo=magento&longCache=true&style=flat-square" alt="Supported Magento Versions" />
  <a href="https://packagist.org/packages/markshust/magento2-module-ordergrid" target="_blank"><img src="https://img.shields.io/packagist/v/markshust/magento2-module-ordergrid.svg?style=flat-square" alt="Latest Stable Version" /></a>
  <a href="https://packagist.org/packages/markshust/magento2-module-ordergrid" target="_blank"><img src="https://poser.pugx.org/markshust/magento2-module-ordergrid/downloads" alt="Composer Downloads" /></a>
  <a href="https://GitHub.com/Naereen/StrapDown.js/graphs/commit-activity" target="_blank"><img src="https://img.shields.io/badge/maintained%3F-yes-brightgreen.svg?style=flat-square" alt="Maintained - Yes" /></a>
  <a href="https://opensource.org/licenses/MIT" target="_blank"><img src="https://img.shields.io/badge/license-MIT-blue.svg" /></a>
</div>

## Table of contents

- [Summary](#summary)
- [Installation](#installation)
- [Usage](#usage)
- [Credits](#credits)
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

## Credits

### M.academy

This course is sponsored by <a href="https://macade.my/s4c9Qk" target="_blank">M.academy</a>, the simplest way to learn Magento.

<a href="https://macade.my/s4c9Qk" target="_blank"><img src="docs/macademy-logo.png" alt="M.academy"></a>

### Mark Shust

My name is Mark Shust and I am a 6X Adobe Commerce Certified Developer and the founder of <a href="https://macade.my/s4c9Qk" target="_blank">M.academy</a>. Since the early days of Magento, I've been involved with many intricately complex eCommerce and open-source projects.

My passion is teaching and helping others learn Magento, and has created many courses and tutorials to help thousands of students from all over the world to learn and improve their Magento skills.

- <a href="https://macade.my/s4c9Qk" target="_blank">🖥️ Learn with Magento courses</a>
- <a href="https://www.linkedin.com/in/MarkShust/" target="_blank">🔗 Connect on LinkedIn</a>
- <a href="https://youtube.com/markshust" target="_blank">🎥 Watch on YouTube</a>
- <a href="https://m.academy/contact/" target="_blank">💌 Contact me</a>

## License

[MIT](https://opensource.org/licenses/MIT)
