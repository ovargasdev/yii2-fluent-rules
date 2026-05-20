# Yii2 Fluent Rules

[![Latest Stable Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://packagist.org/packages/ovargas/fluentrules)
[![Software License](https://img.shields.io/badge/license-Custom_Commercial-orange.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.2-8892bf.svg)](https://php.net)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%205-success.svg)](https://phpstan.org/)

A lightweight, expressive, and type-safe fluent validation interface for the **Yii2 Framework**.

Tired of maintaining massive, multi-dimensional array trees in your ActiveRecord models? This package replaces arbitrary array configurations with an elegant, IDE-discoverable object chain, dramatically improving code readability, refactoring safety, and static analysis precision.

---

## Key Features

* 🌟 **100% Fluent API:** Chain your validation rules with full autocompletion and type safety.
* 🔀 **Hybrid Validation Engine:** Mix standard Yii2 array layouts alongside fluent definitions within the same compiler context.
* 🏢 **Mirror Architecture:** Features a specialized `RuleBuilder` class for every standard Yii2 core validator, plus convenience builders for streamlined workflows.
* 🛠️ **Migration-to-Model Workflow:** Matches Yii2's schema migration syntax, allowing you to copy, paste, and quickly refactor definitions using multi-cursors.
* 🛡️ **PHP 8.2 Strict Type-Safety:** Fully compatible with PHPStan and modern static analyzers.

---

## Installation

Install the package via [Composer](https://getcomposer.org/):

```bash
composer require ovargas/fluentrules
```

---

## Why Use Yii2 Fluent Rules?

### The Traditional Yii2 Approach
Standard validation rules in Yii2 depend heavily on raw nested arrays. They lack autocomplete support, are prone to typo errors, and quickly become hard to read:

```php
public function rules()
{
    return [
        [['code', 'name'], 'required'],
        ['code', 'string', 'max' => 2],
        ['status', 'string'],
        ['code', 'unique'],
        ['population', 'integer', 'skipOnEmpty' => false],
        [['status'], 'in', 'range' => ['active', 'inactive', 'deleted']],
        ['status', 'default', 'value' => 'active'],
    ];
}
```

### The Fluent Rules Approach
With this library, your logic remains clear, readable, and perfectly discoverable by any modern IDE:

```php
use ovargas\fluentrules\Attribute;
use ovargas\fluentrules\RuleBuilder;

public function rules()
{
    return RuleBuilder::rules([
        Attribute::create('code')->string(2)->notNull()->unique(),
        Attribute::create('name')->string()->notNull(),
        Attribute::create('population')->integer()->skipOnEmpty(false),
        Attribute::create('status')->string()->defaultValue('active')->in(['active', 'inactive', 'deleted']),
    ]);
}
```

---

## From Migration to Model in Seconds (Productivity Trick)

Because the fluent interface mirrors the fluid syntax used by Yii2's Schema Builder during database migrations, writing your model constraints becomes incredibly fast. You can literally copy your column definitions from a migration file, paste them into your model's `rules()` method, and apply a few multi-cursor edits in your IDE:

### 1. Your Migration File
```php
$this->createTable('{{%location}}', [
    'id' => $this->primaryKey(),
    'code' => $this->string(2)->notNull()->unique(),
    'name' => $this->string()->notNull(),
    'population' => $this->integer(),
    'status' => $this->string()->defaultValue('active')->check("status IN ('active', 'inactive', 'deleted')"),
]);
```

### 2. Your Model Rules (After quick multi-cursor refactoring)
```php
return RuleBuilder::rules([
    Attribute::create('code')->string(2)->notNull()->unique(),
    Attribute::create('name')->string()->notNull(),
    Attribute::create('population')->integer(),
    Attribute::create('status')->string()->defaultValue('active')->in(['active', 'inactive', 'deleted']),
]);
```

---

## Advanced Usage: Hybrid Mode

One of the greatest design strengths of this package is its **progressive migration support**. If you have an established project with numerous core array rules, or you need to integrate specialized third-party inline validators, you can mix arrays and fluent configurations smoothly inside the same root array block:

```php
use ovargas\fluentrules\Attribute;
use ovargas\fluentrules\RuleBuilder;

public function rules()
{
    return RuleBuilder::rules([
        // Fluent implementation
        Attribute::create('email')->email()->notNull()->unique(),
        
        // Native Yii2 standalone array rules (Supported seamlessly)
        ['password', 'validatePassword'],
        [['created_at', 'updated_at'], 'safe'],
    ]);
}
```

---

## Complete Exhaustive Reference Table

Every fluent method maps cleanly to an underlying specialized `RuleBuilder` class and compiles directly into its native Yii2 core counterpart:

| Fluent Method | Concrete Underlying Class | Target Yii2 Validator | Description / Sample Default Behavior |
| :--- | :--- | :--- | :--- |
| `->boolean()` | `BooleanRuleBuilder` | `boolean` | Checks for standard boolean flags (0, 1, true, false). |
| `->captcha()` | `CaptchaRuleBuilder` | `captcha` | Validates matching verification codes from a Captcha widget. |
| `->compareAttr(string $otherAttr, ?string $op = null)` | `CompareAttributeRuleBuilder` | `compare` | Validates that the attribute matches another model field (e.g. `password_repeat`). |
| `->compareValue(mixed $value, ?string $op = null)` | `CompareValueRuleBuilder` | `compare` | Compares value statically against a scalar using operators (`==`, `>=`, etc). |
| `->custom(callable\|string $methodName)` | `CustomRuleBuilder` | `custom` | Executes inline closures or custom local validation methods. |
| `->date(?string $format = null)` | `DateRuleBuilder` | `date` | Validates calendar dates. |
| `->dateTime(?string $format = null)` | `DateTimeRuleBuilder` | `date` | Validates full ISO/custom timestamp dates. |
| `->defaultValue(mixed $value = null)` | `DefaultValueRuleBuilder` | `default` | Assigns an explicit fallback value if input is empty. |
| `->email()` | `EmailRuleBuilder` | `email` | Validates standard RFC email addressing. |
| `->exist(string $class, string\|array\|null $attr)` | `ExistRuleBuilder` | `exist` | Assures record exists in database table (Defaults to `skipOnError => true`). |
| `->file()` | `FileRuleBuilder` | `file` | Evaluates file upload properties (size, extension, mime types). |
| `->filter(callable\|string\|null $filter = null)` | `FilterRuleBuilder` | `filter` | Processes input through a PHP callback (Defaults to `skipOnEmpty => false`). |
| `->image()` | `ImageRuleBuilder` | `image` | Specialized file validator checking dimensions, width, and height. |
| `->in(\Closure\|null\|iterable $range = null)` | `RangeRuleBuilder` | `in` | Ensures values exist inside a predefined iterable array. |
| `->integer()` | `IntegerRuleBuilder` | `integer` | Ensures data represents a pure integer format. |
| `->ip()` | `IpRuleBuilder` | `ip` | Validates IPv4 or IPv6 network addresses. |
| `->match(string $pattern)` | `RegularExpressionRuleBuilder` | `match` | Checks input compliance against a custom Regex pattern. |
| `->notNull()` | `RequiredRuleBuilder` | `required` | Shorthand alias. Validates that the attribute value is provided. |
| `->number()` | `NumberRuleBuilder` | `number` | Validates that the input is a valid decimal/float number. |
| `->range(\Closure\|null\|iterable $range = null)` | `RangeRuleBuilder` | `in` | Core Yii2 name endpoint. Ensures values exist inside an iterable collection. |
| `->required()` | `RequiredRuleBuilder` | `required` | Core Yii2 name endpoint. Validates that the attribute value is provided. |
| `->safe()` | `SafeRuleBuilder` | `safe` | Explicitly flags attributes as safe for massive assignments. |
| `->string(?int $length = null)` | `StringRuleBuilder` | `string` | Core Yii2 name endpoint. Sets max length boundaries for string inputs. |
| `->strim()` | `TrimmedStringRuleBuilder` | `trimmedString` | Custom combined builder to trim and evaluate strings sequentially. |
| `->text(?int $length = null)` | `StringRuleBuilder` | `string` | Shorthand alias. Sets max length boundaries for string inputs. |
| `->time(?string $format = null)` | `TimeRuleBuilder` | `date` | Validates custom temporal configurations (Forces internal `type => time`). |
| `->timestamp()` | `IntegerRuleBuilder` | `integer` | Shorthand alias. Ensures data represents a pure integer format. |
| `->trim()` | `TrimRuleBuilder` | `trim` | Strip whitespace from the beginning and end. |
| `->unique(?string $targetClass = null)` | `UniqueRuleBuilder` | `unique` | Validates database unique indices to prevent duplicate entries. |
| `->url()` | `UrlRuleBuilder` | `url` | Verifies well-formed URL syntax. |

> **Global Parameters:** All specialized builders inherit directly from `RuleBuilder`. This means structural parameters like `->on()`, `->except()`, `->message()`, `->skipOnError()`, `->skipOnEmpty()`, `->enableClientValidation()`, and execution callbacks like `->isEmpty()`, `->when()`, and `->whenClient()` are universally available across **all** methods listed above.

---

## Running Tests & Quality Control

The library features strict internal routing validation, ensuring compiled structures completely match the definitions expected by the Yii2 framework engine.

Run the test runner using PHPUnit:
```bash
vendor/bin/phpunit
```

Run static analysis using PHPStan:
```bash
vendor/bin/phpstan analyse
```

---

---

## License

This package is licensed under a **Proprietary Hybrid License** by Omar Vargas.

* **Attribution:** Every use or fork must preserve copyright notices and credit the author.
* **Non-Commercial / Indirect Use:** Free to use for personal projects or internal business tools.
* **Direct Commercial Use (SaaS, Sales, Subscriptions):** Requires an explicit commercial license or royalty agreement per project.

For commercial licensing inquiries, please contact the author directly.