# Logging Library (PHP)

## Description
The Logging Library offers a suite of tools designed to streamline logging and enhance debugging capabilities. It also includes functionality for generating packages that facilitate debugging and archiving of extensive transactional operations.

## Features
- Standardize your log entries with the LogItem object
- Multiple Log Streaming Options: Saves in memory for processing after application completion, Saves to a file as soon as an entry is logged, or outputs to the console (STDOUT or STDERR).
- Generate Log Packages/Archives

## Installation
To install the library (using composer):

1. `composer require mannydmorales/logging`
2. Include the composer autoloader 
    ```php
    require __DIR__.'/vendor/autoload.php';
    ```

To install the library (if not using composer):

1. Clone/Download the repository
2. Include the *autoload.php* script in your application
    ```php
    require 'path/to/install/autoload.php';
    ```

## Usage
Please view the examples located in the [examples/](examples) directory.

## Contributing
Contributions are welcome! Please fork the repository and create a pull request with your changes.

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact
For any questions or suggestions, please contact [mannydmorales@gmail.com](mailto:mannydmorales@gmail.com).
