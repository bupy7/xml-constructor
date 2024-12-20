xml-constrcutor
===

v2.0.4 [2024-12-21]
---

- Supporting of PHP 8.4.
- Code style fixes.

v2.0.3 [2023-12-29]
---

- Supporting of PHP 8.3.

v2.0.2 [2023-01-07]
---

- Added some tests.
- Small fixes and improvements.

v2.0.1 [2023-01-07]
---

- Code style fixes.
- Fixed tests.
- Modified documentation.
- Small fixes and improvements.

v2.0.0 [2023-01-06]
---

- Now `bupy7\xml\constructor\XmlConstructor` is a plain PHP class which doesn't extend the `XMLWriter`.
- Call `bupy7\xml\constructor\XmlConstructor::fromArray()` or `bupy7\xml\constructor\XmlConstructor::toOutput()` more 
than once is not supported and will throw an exception.
- Some small fixes and enhancements.

v1.3.5 [2022-12-12]
---

- Reduce size of package.

v1.3.4 [2022-12-11]
---

- Support of PHP 8.2.
- Added some more tests.

v1.3.3 [2022-01-22]
---

- Support PHP 8.0, 8.1.
- Removed the `@author` PHPDoc tag.
- Enhancement the README.
- Moved from Travis-CI to GitHub Actions.

v1.3.2 [2020-02-11]
---

- Support PHP 7.3, 7.4.

v1.3.1 [2019-04-19]
---

- Just upgraded README because of skiped CData description.

v1.3.0 [2018-11-22]
---

- Added [CData](http://php.net/manual/ru/function.xmlwriter-write-cdata.php) feature.
- No longer support for PHP 5.5, 5.6 and HHVM.

v1.2.5 [2018-11-08]
---

- Fixed "no tag content" bug.

v1.2.4 [2017-12-06]
---

- Small notice in README about headers. (pandalowry)

v1.2.3 [2017-11-30]
---

- Added support PHP 7.1 and 7.2.

v1.2.2 [2017-09-18]
---

- Small enhancement.
- Added Docker.
- Fixed tests.

v1.2.1 [2016-01-27]
---

- Added Unit-tests.

v1.2.0 [2015-12-21]
---

- Added changelog file.
- Removed `$root` param from `__construct()` method. 
- Removed `$file` param from `__construct()` method.
- Added ability configuration of `XMLWriter` via `$config` param to `__construct()`
method. 
- Added `libxml` to required section of `composer.json`.
- Small enhancements and fixes.

v1.1.0 [2015-08-27]
---

- Fix naming of method.


v1.0.0 [2015-07-24]
---

- First release.
