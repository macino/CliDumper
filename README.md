# **CliDumper Documentation**

## **Overview**

`CliDumper` is a compact and flexible PHP library designed for efficiently dumping PHP data structures such as arrays, objects, and scalars into a readable and customizable format. It enhances log readability, especially in scenarios involving large and complex data, by eliminating excessive verbosity and emphasizing minimalism, color, and structure.

With features like customizable output and syntax highlighting for scalar types, `CliDumper` stands out as a developer-friendly tool for debugging, logging, and real-time output processing in CLI environments.

---

## **Why CliDumper?**

Dealing with complex data logs can often lead to cluttered log files filled with `var_dump`, `var_export`, or `print_r` outputs. While these methods are useful, they can become cumbersome, especially when searching for specific values in lengthy or nested outputs.

`CliDumper` solves this by:
- **Reducing verbosity:** It formats output compactly and only highlights what's necessary.
- **Improving readability:** It uses color highlights to distinguish between data types (e.g., numbers, strings, nulls, etc.).
- **Customization options:** Developers can define their own formatting rules for scalar values.
- **Structured visualization:** It efficiently handles arrays, objects, and nested structures.

---

## **Key Features**

1. **Compact Output**: Generates concise dumps for even the most complex data structures.
2. **Customizable Formatter**: Modify scalar type representations (e.g., color-coded outputs).
3. **Visual Highlights**:
    - Color differentiation for types (e.g., null, string, numeric, boolean).
    - Options to organize nested elements for clarity.
4. **Efficient Debugging**: Output can be directly printed to CLI or returned as a string for further processing.
5. **Truncation**: Set limits for string length to keep outputs within manageable size.
6. **Optional Separators**: Add separators in output for better log organization.

---

## **Installation**

### **Using Composer**
Install the library via Composer:

```shell script
composer require macino/clidumper
```

---

## **Usage**

Here are the basic usage examples for the `CliDumper` class:

### **Initialization**

```php
use Macino\CliDumper\CliDumper;

$dumper = new CliDumper();
```

---

### **Dump Debugging Data**

The `dump` method outputs a message and variable data to the CLI. Customize the output with optional flags.

```php
$dumper->dump(
    'Dumping data example',
    ['key1' => 'value1', 'key2' => [1, 2, 3]],
    separateLeafs: true,
    separate: true
);
```

#### **Parameters**
- **`$msg`**: (string) A message or title displayed before the dump.
- **`$data`**: (mixed) The data to be dumped.
- **`$separateLeafs`**: (bool) Breaks scalar members onto new lines and organizes them by key.
- **`$separate`**: (bool) Adds a separator line (e.g., `------`) before the output.

---

### **Alias for Dump**

The `d` method is an alias for the `dump` method.

```php
$dumper->d('This is a quick debug message', ['id' => 42, 'active' => true]);
```

---

### **Custom Formatting**

You can define your own formatter callback to modify scalar type representations. Use the `$formatter` property to define a custom formatting function.

#### Example:
```php
$dumper->formatter = function(mixed $value, string $type) {
    switch ($type) {
        case 'null': return "\e[1;30mNULL\e[0m"; // Gray
        case 'bool': return "\e[1;33m" . ($value ? 'TRUE' : 'FALSE') . "\e[0m"; // Yellow
        case 'string': return "\e[1;34m\"$value\"\e[0m"; // Blue
        case 'num': return "\e[1;31m{$value}\e[0m"; // Red
    }
    return $value;
};
```

---

### **Inline Dump**

Use `inlineDump` to output or retrieve a serialized version of a variable without the heading message.

```php
$result = $dumper->inlineDump($data, separateLeafs: true, return: true);
echo $result;
```

#### **Parameters**:
- **`$var`**: (mixed) The variable to be dumped.
- **`$separateLeafs`**: (bool) Breaks scalar members onto new lines and organizes them by key.
- **`$return`**: (bool) If `true`, the method returns the dump as a string instead of printing it.

---

## **Configuration Options**

### **Public Properties**

1. **`$enabled`**:
    - *Type*: `bool`
    - *Default*: `true`
    - Enables or disables dumping.

   #### Example:
```php
$dumper->enabled = false; // Disables all dumps
```

2. **`$truncate`**:
    - *Type*: `int`
    - *Default*: `80`
    - Limits the length of strings in the dump. Set `0` for no limit.

   #### Example:
```php
$dumper->truncate = 50; // Truncate strings longer than 50 characters
```

3. **`$formatter`**:
    - *Type*: `callable`
    - *Default*: A simple formatter that outputs unaltered scalars.
    - Provides custom display formatting for different scalar types.

   #### Example:
```php
$dumper->formatter = fn($value, $type) => strtoupper($value);
```

---

## **Methods**

### **`dump`**
Outputs formatted data to the CLI with an optional message.

### **`d`**
Alias for `dump`.

### **`inlineDump`**
Returns a formatted string of the provided data or prints it directly.

---

## **Use Cases**

1. **Debugging**:
    - Quickly visualize complex data structures in developer-friendly formats.
    - Highlight and differentiate key elements in the output using colors.
2. **Logging**:
    - Write cleaner, compact logs for later analysis.
3. **Real-time Data Processing**:
    - Print readable information from live CLI applications.

---

## **Limitations**

- Primarily suited for CLI-based debugging and logs; not meant to replace JSON, XML, or other structured formats for storage.

---

## **Conclusion**

`CliDumper` is a lightweight solution for PHP developers looking to improve debugging experiences, enhance log readability, and cleanly process real-time data in CLI environments. With its color coding, formatting flexibility, and compact outputs, `CliDumper` simplifies viewing intricate data structures without sacrificing detail.

